<?php
/*
 * 암 보드에서는 콘솔 명령어의 옵션이 제한적이므로
 * 거기에 맞춰서 수정
 */
require_once 'burning_brows_conf.php';
require_once 'burning_brows_common_func.php';
require 'nas_brow_common.php';
/* To handle a folder name with special characters */
require_once 'nas_comm.php';

//=======================================================//
// e-SATA init. value
//=======================================================//
$esata_file = "/tmp/esata/esata_job";
set_time_limit(0);

class Remote_func {

	var $apache_exe_user = array('nobody','root','admin');
	var $month_arr = array( 'Jan' => '01',
							'Feb' => '02',
							'Mar' => '03',
							'Apr' => '04',
							'May' => '05',
							'Jun' => '06',
							'Jul' => '07',
							'Aug' => '08',
							'Sep' => '09',
							'Oct' => '10',
							'Nov' => '11',
							'Dec' => '12'
							);

    function Remote_func() {
    	ini_set('display_errors',0);
    }
	    
	// 주어진 디렉토리에 해당 유저가 요구되는 퍼미션을 가지고있는지 확인
	private function check_dir_permission($full_path,$request_permission){
		if(empty($full_path)) return false;
		if(eregi("w", $request_permission)){
			if( in_array($full_path,$_SESSION['rw_dir']) ){
				return true;
			}
		}else if(eregi("r", $request_permission)){
			if( in_array($full_path,$_SESSION['ro_dir']) ){
				return true;
			}
		}
		
		
		
		
		$cmd = 'ls -ld "'.$full_path.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		
		$pattern = "/([^\s]+)/";
		$exploded = array();
		preg_match_all($pattern,$cmd_result[0],$exploded);
		$exploded = $exploded[0];
		/*
		 * $exploded[0] = permission
		 * $exploded[1] = number of links
		 * $exploded[2] = owner
		 */
		 
		 //디렉토리 소유자 확인,해당 퍼미션 확인
		 $directory_permission = '';
		 if($exploded[2] == $_SESSION['username']){
		 	$directory_permission = substr($exploded[0],1,3);
		 }else{
		 	$directory_permission = substr($exploded[0],7,3);
		 }
		 
		 //요구된 퍼미션에 부합하는지 확인
		 $request_p_arr = str_split($request_permission);
		 $return_val = true;
		 foreach($request_p_arr as $p_name){
		 	if($p_name != '-'){
			 	if(strpos($directory_permission,$p_name)===false){
			 		$return_val = false;
			 	}
		 	}
		 }
		 return $return_val;
	}
	
	private function human_readable($byte){
		$K = 1024;
		$M = 1048576;
		$G = 1073741824;
		//$T = 133143986176;
		$T = 1099511627776;
		$ret = '';
		
		if($byte<$K){
			$ret = $byte;
		}elseif($byte < $M){
			$ret = round($byte/$K,2).'K';
		}elseif($byte < $G){
			$ret = round($byte/$M,2).'M';
		}elseif($byte < $T){
			$ret = round($byte/$G,2).'G';
		}else{
			$ret = round($byte/$T,2).'T';
		}
		return $ret;
	}
		
	//현재 디렉토리의 디렉토리 및 파일을 리턴
	function show_me_files(){	

		//check whether volume is configured
		$dir = trim(exec("sudo nas-storage get vol_default"));
		if($dir =='' || $dir =='/')
		{			
			//echo 'no volume configuration';
			echo lang_get('volume_8');
			return;
		}
	
		// Loading session info
   //KJS_DEBUG$mtime = microtime(); 
   //KJS_DEBUG$mtime = explode(" ",$mtime); 
   //KJS_DEBUG$mtime = $mtime[1] + $mtime[0]; 
   //KJS_DEBUG$starttime = $mtime; 		
		

		$prefix_path=$_SESSION['user_directory'];
		$post_path=$_SESSION['current_dir'];
		
		$current_path = get_currDirectory();
		
		if(!empty($_SESSION['buffer'])){
			$copy_cut_data = $_SESSION['buffer']['target_data'];
			$copy_cut_type = $_SESSION['buffer']['select_type'];
			$copy_cut_path = $_SESSION['buffer']['origin_path'];
		}
		$__current_dir = $_SESSION['current_dir'];
		$__full_current_dir = $_SESSION['storage_url'].$_SESSION['current_dir'];
		session_write_close();
   
		//if ( ($_SESSION['current_dir'] == "/linear") || ($_SESSION['current_dir'] == "/raid")  || ($_SESSION['current_dir'] == "/disk1")) {


		if ( $_SESSION['current_dir'] == "/" ) {

			$_user = $_SESSION['username'];
			$_group ='';

			try{
				$dbh=new PDO("sqlite:/etc/nas/db/share.db");
				$sth=$dbh->prepare("select gid from group_user where uid='$_user'");
				$sth->execute();
				$DB_group_info=$sth->fetchAll();
				$dbh=null;
			}
			catch(PDOException $e) {
				print "";
				die();
			}

			$_group = $DB_group_info[0][0];
					
			//From folder_member & folder_info
			try{
				$dbh=new PDO("sqlite:/etc/nas/db/share.db");
				if (is_null($_group)){
				  $sth=$dbh->prepare("select folder from folder_member where ro='$_user' or rw='$_user'");
				}
				else{
				  $sth=$dbh->prepare("select folder from folder_member where ro='$_user' or rw='$_user' or ro='$_group' or rw='$_group' group by folder");
				}
				$sth->execute();
				
				$sth1=$dbh->prepare("select folder from folder_info where acl='NO'");
				$sth1->execute();
				
				//$DB_folder_info=$sth->fetchAll();
				$DB_folder_info = array_merge($sth->fetchAll(), $sth1->fetchAll());
				
				$dbh=null;
			}
			catch(PDOException $e) {
				print "";
				die();
			}
			
			$folder_count=count($DB_folder_info);
			
			$count = 0;
			$list = array();
			$encoded_filename_list = array();	
						
			for($index=0;$index<$folder_count;$index++) {
				$test_val = $DB_folder_info[$index][0];
				try{
					$dbh=new PDO("sqlite:/etc/nas/db/share.db");
					$sth=$dbh->prepare("select path from folder_info where folder='$test_val'");
					$sth->execute();
					$DB_folder_info_path=$sth->fetchAll();
					$dbh=null;
				}
				catch(PDOException $e) {
					print "";
					die();
				}				
				
				$_folder_path = $DB_folder_info_path[0][0];
				if($_folder_path == '')
					continue;	

				$encoded_filename_list[] = func_urlencode($DB_folder_info[$index][0]);	
				$_folder_ls_info = exec("sudo ls -del $_folder_path");				
				$list[$count]=str_replace($_folder_path, $test_val, $_folder_ls_info);

				$count++;
			}						
			return json_encode(array($list,$encoded_filename_list));						
		}
		else {
			@$sort_cond = $_GET['sort_cond'];
			$display_mode = $_GET['mode'];
			$file_or_dir_only = $_GET['file_or_dir_only'];
			$sort_cond_str = '';
			switch($sort_cond){
			case 'type':
				//$sort_cond_str = '--sort=extension '; break;
				$sort_cond_str = 'X'; break;
			case 'name':
				$sort_cond_str = ''; break;
			case 'size':
				$sort_cond_str = 'S'; break;
			case 'time':
			default:
				 //$sort_cond_str = '--sort=time ';
				 $sort_cond_str = '';
			}
			// Current folder size
			$return_array = array();
			$files = array();
			$dirs = array();
			/*
			$pattern = "/([^\s]+)/";
			$cmd = 'sudo du -sh "'.$current_path.'"';
			$cmd_result = array();
			exec($cmd,$cmd_result);
			$exploded = array();
			preg_match_all($pattern,$cmd_result[0],$exploded);
			*/
			//$return_array['total_size'] = $exploded[0][0];	// total size
			$_total_size = 0;	// for calculation of total size
	
			/* To convert a folder name to available form */		
			$current_path = encode_filename($current_path);				
			$cmd = 'ls -le'.$sort_cond_str.' '.$current_path;	
			
   //KJS_DEBUG$mtime = microtime(); 
   //KJS_DEBUG$mtime = explode(" ",$mtime); 
   //KJS_DEBUG$mtime = $mtime[1] + $mtime[0]; 
   //KJS_DEBUG$endtime = $mtime; 
   //KJS_DEBUG$totaltime = ($endtime - $starttime); 
   //KJS_DEBUGexec("sudo echo time3 = $totaltime >> /var/www/smb.txt");
			
			$cmd_result = array();
			exec($cmd,$cmd_result);
			
			//KJS_NO_CHECK_PERMISSION$_tmp = explode('/',$__current_dir);
			//KJS_NO_CHECK_PERMISSIONif(count($_tmp)-2 < 2){
			//KJS_NO_CHECK_PERMISSION	
			//KJS_NO_CHECK_PERMISSION	
			//KJS_NO_CHECK_PERMISSION	// Permission check
			//KJS_NO_CHECK_PERMISSION	$pattern = "/([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)(.*)/";
			//KJS_NO_CHECK_PERMISSION	$_ret_arr = array();
			//KJS_NO_CHECK_PERMISSION	foreach($cmd_result as $row){
			//KJS_NO_CHECK_PERMISSION		$file_row = array();
			//KJS_NO_CHECK_PERMISSION		$exploded = array();
			//KJS_NO_CHECK_PERMISSION		
			//KJS_NO_CHECK_PERMISSION		preg_match_all($pattern,$row,$exploded);
			//KJS_NO_CHECK_PERMISSION		$_filename = $exploded[21][0];
			//KJS_NO_CHECK_PERMISSION		unset($exploded);
			//KJS_NO_CHECK_PERMISSION		
			//KJS_NO_CHECK_PERMISSION		if(substr($current_path,-1)!='/') $current_path .= '/';
			//KJS_NO_CHECK_PERMISSION		$current_file = $current_path.$_filename;
			//KJS_NO_CHECK_PERMISSION		if(!nas_chk_dir_prms($current_file,'r')) continue;
			//KJS_NO_CHECK_PERMISSION		
			//KJS_NO_CHECK_PERMISSION		$_ret_arr[] = $row;
			//KJS_NO_CHECK_PERMISSION		
			//KJS_NO_CHECK_PERMISSION		//echo $current_file;
			//KJS_NO_CHECK_PERMISSION	}
			//KJS_NO_CHECK_PERMISSION	
			//KJS_NO_CHECK_PERMISSION	$cmd_result = $_ret_arr;
			//KJS_NO_CHECK_PERMISSION}else{
			//KJS_NO_CHECK_PERMISSION	// No permission check	
			//KJS_NO_CHECK_PERMISSION}
			
   //KJS_DEBUG$mtime = microtime(); 
   //KJS_DEBUG$mtime = explode(" ",$mtime); 
   //KJS_DEBUG$mtime = $mtime[1] + $mtime[0]; 
   //KJS_DEBUG$endtime = $mtime; 
   //KJS_DEBUG$totaltime = ($endtime - $starttime); 
   //KJS_DEBUGexec("sudo echo time2 = $totaltime >> /var/www/smb.txt");
			
			////		
			$list = array();		
			foreach($cmd_result as $value) {			
				$list[] = htmlspecialchars($value,ENT_QUOTES);		
			}
						
			$cmd_fn = 'ls '.$sort_cond_str.' '.$current_path;				
			$cmd_result_fn = array();		
			$encoded_filename_list = array();		
			exec($cmd_fn,$cmd_result_fn);	
						
			foreach($cmd_result_fn as $filename) {			
				//KJS_NO_CHECK_PERMISSIONif(substr($current_path,-1)!='/') 
				//KJS_NO_CHECK_PERMISSION	$current_path .= '/';			
				//KJS_NO_CHECK_PERMISSION$current_file = $current_path.$filename;			
				//KJS_NO_CHECK_PERMISSIONif(!nas_chk_dir_prms($current_file,'r')) 
				//KJS_NO_CHECK_PERMISSION	continue;						
				$encoded_filename_list[] = func_urlencode($filename);		
			}						
			
   //KJS_DEBUG$mtime = microtime(); 
   //KJS_DEBUG$mtime = explode(" ",$mtime); 
   //KJS_DEBUG$mtime = $mtime[1] + $mtime[0]; 
   //KJS_DEBUG$endtime = $mtime; 
   //KJS_DEBUG$totaltime = ($endtime - $starttime); 
   //KJS_DEBUGexec("sudo echo time1 = $totaltime >> /var/www/smb.txt");
			
			return json_encode(array($list,$encoded_filename_list));
		}
				
		$copy_cut_data = array();
		$copy_cut_type = ''; //copy or cut
		$copy_cut_path = '';
		/* Session buffer info
		if(!empty($_SESSION['buffer'])){
			$copy_cut_data = $_SESSION['buffer']['target_data'];
			$copy_cut_type = $_SESSION['buffer']['select_type'];
			$copy_cut_path = $_SESSION['buffer']['origin_path'];
		}
		*/
		$pattern = "/([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)(.*)/";
		foreach($cmd_result as $row){
			$file_row = array();
			$exploded = array();
			
			preg_match_all($pattern,$row,$exploded);
			$exp = array('permission' 	=> $exploded[1][0],
			 			  'links' 	=> $exploded[3][0],
			 			  'owner' 	=> $exploded[5][0],
			 			  'group' 	=> $exploded[7][0],
			 			  'file_size' 	=> $exploded[9][0],
			 			  'week' 		=> $exploded[11][0],
			 			  'month_str'	=> $exploded[13][0],
			 			  'day' 		=> $exploded[15][0],
			 			  'time' 		=> $exploded[17][0],
			 			  'year' 		=> $exploded[19][0],
			 			  'file_name' 	=> $exploded[21][0]);
			unset($exploded);
			$exp['date'] = $exp['year'].'/'.$this->month_arr[$exp['month_str']].'/'.$exp['day'];
			//=======================================================//
			// Filter out link folder
			//=======================================================//
			if(substr($exp['permission'],0,1) == 'l')
			{
				continue;
			}
			//=======================================================//
			// Permission check
			//=======================================================//
			if(substr($current_path,-1)!='/') $current_path .= '/';
			$current_file = $current_path.$exp['file_name'];
			if(!nas_chk_dir_prms($current_file,'r')) continue;
			
								
			$file_row = array();
			$file_row['fd'] = (substr($exp['permission'],0,1) == 'd')?'directory':'file';
			//=======================================================//
			// Folder size
			//=======================================================//
			if($file_row["fd"]=="directory")
			{
				//$exp["file_size"] = $this->get_folder_size($current_file);
				$exp["file_size"] = "";
			}
			
			
			if($file_row['fd'] == 'directory' 
				&& (empty($file_or_dir_only) || $file_or_dir_only == 'directory')){
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
				$file_row['selected'] = '';
				//if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['directory'])){
				if(($copy_cut_path == $__current_dir) && @in_array($file_row['file_name'],$copy_cut_data['directory'])){
					$file_row['selected'] = $copy_cut_type;
				}
				
				if($display_mode == 'list'){
					$file_row['size'] = $this->human_readable($exp['file_size']);
					$file_row['date'] = $exp['date'];
					$file_row['time'] = $exp['time'];
				}
				$_total_size += (float)$exp['file_size'];
				$dirs[] = $file_row;
			}elseif(empty($file_or_dir_only) || $file_or_dir_only == 'file'){
				
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
			
				//$full_url = $_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name'];
				$full_url = $__full_current_dir.$file_row['file_name'];
				$full_url_exploded = explode('/',$full_url);
				foreach($full_url_exploded as $url_cell_key => $url_cell_row){
					$full_url_exploded[$url_cell_key] = func_urlencode($url_cell_row);
				}
				$file_row['url'] = implode('/',$full_url_exploded);
				
				//$file_row['encoded_url'] = func_urlencode($_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name']);
				$file_row['encoded_url'] = func_urlencode($__full_current_dir.$file_row['file_name']);
				
				$mime_type = getLocalFileMimeType($current_path.$file_row['file_name']);
				$mime_type_arr = explode('/',$mime_type); //     image/gif
				$file_row['type'] = $mime_type_arr[0]; //     image
				
				if($display_mode == 'list'){
					$semi_cl_pos = strpos($mime_type_arr[1],';');
					if($semi_cl_pos ===false){
						$file_row['subtype'] = substr($mime_type_arr[1],0); //     gif
					}else{
						$file_row['subtype'] = substr($mime_type_arr[1],0,$semi_cl_pos); //     gif
					}
					$file_row['size'] = $this->human_readable($exp['file_size']);
					$file_row['date'] = $exp['date'];
					$file_row['time'] = $exp['time'];
				}
				
				$file_row['selected'] = '';
				if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['file'])){
					$file_row['selected'] = $copy_cut_type;
				}
				$_total_size += (float)$exp['file_size'];
				$files[] = $file_row;
			}
		}
		function file_array_cmp($a,$b){
			return strcmp($a['file_name'], $b['file_name']);
		}
				
		if($sort_cond=='name'){
			usort($dirs,'file_array_cmp');
			usort($files,'file_array_cmp');
		}
		$return_array['dirs'] = $dirs;
		$return_array['files'] = $files;
		
		// NAS free capacity
		$_nas_free_cap = shell_exec("sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain Vol1'");
		$_nas_free_cap = floatval($_nas_free_cap);
		$return_array['nas_free_cap'] = $_nas_free_cap;
		$return_array['total_size'] = $this->human_readable($_total_size);
		
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//현재 참조중인 디렉토리의 패스를 리턴
	function get_curr_dir_path(){
		//$return_array = array('curr_url'=>$_SESSION['current_dir']);		
		$current_path=$_SESSION['current_dir'];

		
		$return_array = array('curr_url'=>$current_path,'cur_encd_path'=>func_urlencode($current_path));
		session_write_close();
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//현재 디렉토리에 주어진 이름의 디렉토리 생성
	function create_dir(){
		// Loading session info
		
		$return_array = array();
		$dir_name = trim($_POST['new_directory_name']);
		
		
		if(empty($dir_name)){
			$return_array = array('result' => '4'
								 ,'error_msg' => 'Folder name was not entered.');
			/*$return_array = array('result' => '4'
								 ,'error_msg' => '디렉토리 이름이 지정되지 않았습니다');
								 */
		}
		$dir_naming_limit = array('\\','/',':','*','?','"','<','>','|');
		
		
		
		foreach($dir_naming_limit as $limit_char){
			if(strpos($dir_name,$limit_char)!==false){
				$return_array = array('result' => '0'
									 ,'error_msg' => '\"\\,/,:,*,?,",<,>,|\" cannot be used in a folder name.');
				/*$return_array = array('result' => '0'
									 ,'error_msg' => '디렉토리 이름에는 \\,/,:,*,?,",<,>,|\ 문자를 사용할 수 없습니다');
									 */
				$return_str = json_encode($return_array);
				return $return_str;
			}
		}
		
		//=======================================================//
		// Permission check
		//=======================================================//
		//$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];

		$current_path = get_currDirectory();
		if( !$this->check_dir_permission($current_path, 'w') ){
			$return_array = array('result' => '4'
									 ,'error_msg' => 'No permission to create a folder.');
			$return_str = json_encode($return_array);
			return $return_str;
		}
		
		//return "test";
		
		if(!is_dir($current_path.$dir_name) && !is_file($current_path.$dir_name)){
			/*$cmd = 'mkdir -m 0700 "'.$current_path.$dir_name.'"';
			$cmd_result = array();		
			exec($cmd,$cmd_result);*/
			
			//**permission denied error**//
			//mkdir($current_path.$dir_name, 0755);
			$full_path = $current_path.$dir_name;
			shell_exec("sudo mkdir $full_path; sudo chmod -R 755 $full_path");
			
			//print_r(error_get_last());
			$temp=error_get_last();
			$tmp_ret=ereg("Permission denied",$temp['message']);
			
			
			if($tmp_ret)
			{
				$return_array = array('result' => '2'
									 ,'error_msg' => 'Folder creation was failed.');
			}else if(empty($cmd_result)){
				$cmd = $current_path.$dir_name;
				$ret = shell_exec("sudo chown admin.admin $cmd");
				$return_array = array('result' => '1', 'chown' => $cmd);
			}else{
				$return_array = array('result' => '2'
									 ,'error_msg' => 'Folder creation was failed.');
				/*$return_array = array('result' => '2'
									 ,'error_msg' => '디렉토리 생성에 실패했습니다');
									 */
			}
		}else{
			$return_array = array('result' => '3'
								 ,'error_msg' => 'There is already same name of folder or file.');
			/*$return_array = array('result' => '3'
								 ,'error_msg' => '같은 이름의 디렉토리 또는 파일이 이미 존재합니다');
								 */
		}
		$return_str = json_encode($return_array);
		return $return_str;
		//return $tmp_ret;
	}
	
	//다른 디렉토리로 이동
	function move_dir(){

		$dir_name = trim($_GET['dir_name']);
		
		$new_path = $current_path = get_currDirectory();
		exec("sudo echo path new2: $current_path >> /home/tmp.txt");

		$return_array = array();
		if( is_dir($new_path) && nas_chk_dir_prms($new_path,'r') ){
			$_SESSION['current_dir'] = $_SESSION['current_dir'].$dir_name;
			session_write_close();
			$return_array = array('result' => '1');
		}else
		{
			$return_array = array('result' => '0'
								 ,'error_msg' => 'The folder cannot be accessed.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//상위 디렉토리로 이동
	function move_up(){

		$current_dir = $_SESSION['current_dir'];
		$pre_dir = $_SESSION['prefix_dir'];
		exec("sudo echo ------------------------- >> /home/tmp.txt");
		exec("sudo echo move_up INIT: SESSION_CUR:  $current_dir >> /home/tmp.txt");
		exec("sudo echo move_up INIT: SESSION_PRE   :  $pre_dir >> /home/tmp.txt");
		$current_path = get_currDirectory();	
		$_SESSION['prefix_dir'] = $current_path;	
		
		exec("sudo echo move_up FROM: GET_CUR:  $current_path >> /home/tmp.txt");		
		exec("sudo echo move_up UPDT: SESSION_PRE   :  $current_path >> /home/tmp.txt");

		$return_array = array('result' => '2'
							  ,'error_msg' => 'The folder cannot be accessed.');

		//juny : access depth limit 
		/*
		$_dirs = explode('/',$current_path);
		$_dir_depth = count($_dirs);
		exec("sudo echo move_up : $_dir_depth >> /home/phplog.txt");
		if($_dir_depth<=4){
			$return_str = json_encode($return_array);
			return $return_str;
		}
		*/

		if(strlen($current_path)>=3){
			$parent_path = dirname($current_path);
			if($parent_path == '\\'){
				$return_array = array('result' => '0'
										 ,'error_msg' => 'Here is root.');
			}else{
				
				if(is_dir($parent_path)){						
					$parent_dir = dirname($current_dir);
					if($parent_dir!='/'){												
						$_SESSION['current_dir'] = $parent_dir.'/';						
					}else{					
						$_SESSION['current_dir'] = $parent_dir;						
					}					
					session_write_close();
					//success to move parent directory 
					$return_array = array('result' => '1');
				}else{
					$return_array = array('result' => '0'
										 ,'error_msg' => 'The folder cannot be accessed.');
				}
			}
		}

		$current_dir = $_SESSION['current_dir'];
		exec("sudo echo move_up UPDT: SESSION_CUR : $current_dir >> /home/tmp.txt");
		exec("sudo echo ------------------------- >> /home/tmp.txt");
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//선택된 파일을 COPY모드로 버퍼에 저장
	function copy(){
		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			$current_path = $_SESSION['current_dir'];
			
			$session_copy_data = array('select_type' => 'copy',
										'origin_path' => $current_path,
										'target_data' =>
												array('directory' => $checked_dir,
													  'file' => $checked_files
													)
								);
			$_SESSION['buffer'] = $session_copy_data;
			session_write_close();
			$return_array = array('result' => '1');
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => json_encode($_GET).'No data was selected.');
			/*$return_array = array('result' => '0'
								 ,'error_msg' => json_encode($_GET).'선택된 데이터가 없습니다');
								 */
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//선택된 파일을 CUT모드로 버퍼에 저장
	function cut(){
		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			$current_path = $_SESSION['current_dir'];
			
			$session_copy_data = array('select_type' => 'cut',
										'origin_path' => $current_path,
										'target_data' =>
												array('directory' => $checked_dir,
													  'file' => $checked_files
													)
								);
			$_SESSION['buffer'] = $session_copy_data;
			session_write_close();
			$return_array = array('result' => '1');
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');
			/*$return_array = array('result' => '0'
								 ,'error_msg' => '선택된 데이터가 없습니다');
								 */
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//버퍼에 저장된 내용을 초기화
	function clear_selected(){
		$_SESSION['buffer'] = null;
		session_write_close();
		$return_array = array('result' => '1');
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//버퍼에 저장된 내용을 COPY 또는 CUT & PASTE
	function paste(){
		$buffer = $_SESSION['buffer'];
		$current_path = $_SESSION['current_dir'];
		if(empty($buffer)){
			$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');
			/*$return_array = array('result' => '0'
								 ,'error_msg' => '선택된 데이터가 없습니다');
								 */
		}elseif($buffer['select_type'] == 'cut' && $buffer['origin_path'] == $current_path){
			$return_array = array('result' => '2'
								 ,'error_msg' => 'The folder is same to the folder to be moved.');
			/*$return_array = array('result' => '2'
								 ,'error_msg' => '파일을 이동시킬 디렉토리가 동일 디렉토리입니다');
								 */
		}else{
			$destination = $_SESSION['user_directory'].$current_path;
			$original_path = $_SESSION['user_directory'].$buffer['origin_path'];
			
			$sources = '';
			$new_name_copy = array();
			foreach($buffer['target_data']['directory'] as $row){
				if(is_file($destination.$row) || is_dir($destination.$row)){
					$new_name_copy['dir'][] = $row;
				}else{
					if($buffer['select_type'] == 'copy'){
						$cmd = 'cp -r "'.bsh_double_quote($original_path.$row).'" "'.$destination.'" ; sudo sync';
						$cmd_result_1 = array();
						exec($cmd,$cmd_result_1);
					}else{
						$sources .= ' "'.bsh_double_quote($original_path.$row).'"';
					}
				}
			}
			foreach($buffer['target_data']['file'] as $row){
				if(is_file($destination.$row) || is_dir($destination.$row)){
					$new_name_copy['file'][] = $row;
				}else{
					if($buffer['select_type'] == 'copy'){
						$cmd = 'cp "'.bsh_double_quote($original_path.$row).'" "'.$destination.'" ; sudo sync';
						$cmd_result_1 = array();
						exec($cmd,$cmd_result_1);
					}else{
						$sources .= ' "'.bsh_double_quote($original_path.$row).'"';
					}
				}
			}
			if($buffer['select_type'] == 'cut'){
				$cmd = 'mv '.$sources.' "'.$destination.'" ; sudo sync';
				$cmd_result_1 = array();
				exec($cmd,$cmd_result_1);
			}
			
			$cmd_result_2 = array();
			$new_naming_copy_result = true;
			//directory new naming and copy
			foreach($new_name_copy['dir'] as $row){
				$new_dir_name = $row;
				$cnt = 2;
				while(is_file($destination.$new_dir_name) || is_dir($destination.$new_dir_name)){
					$new_dir_name = $row.'('.$cnt.')';
					$cnt++;
				}
				if($buffer['select_type'] == 'copy'){
					$cmd = 'cp -r "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_dir_name).'" ; sudo sync';
				}elseif($buffer['select_type'] == 'cut'){
					$cmd = 'mv "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_dir_name).'" ; sudo sync';
				}
				
				exec($cmd,$cmd_result_2);
				if($new_naming_copy_result && !empty($cmd_result_2)){
					$new_naming_copy_result = false;
				}
			}
			
			//file new naming and copy
			foreach($new_name_copy['file'] as $row){
				$last_dot_pos = strrpos($row,'.');
				if(!is_numeric($last_dot_pos))$last_dot_pos = strlen($row);
				$file_name_without_extention = substr($row,0,$last_dot_pos);
				$file_extension =  substr($row,$last_dot_pos);
				$new_file_name = $file_name_without_extention.$file_extension;
				$cnt = 2;
				while(is_file($destination.$new_file_name) || is_dir($destination.$new_file_name)){
					$new_file_name = $file_name_without_extention.'('.$cnt.')'.$file_extension;
					$cnt++;
				}				
				if($buffer['select_type'] == 'copy'){
					$cmd = 'cp "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_file_name).'" ; sudo sync';
				}elseif($buffer['select_type'] == 'cut'){
					$cmd = 'mv "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_file_name).'" ; sudo sync';
				}
				exec($cmd,$cmd_result_2);
				if($new_naming_copy_result && !empty($cmd_result_2)){
					$new_naming_copy_result = false;
				}
			}
			
			if(empty($cmd_result_1) && $new_naming_copy_result){
				$return_array = array('result' => '1');
				$_SESSION['buffer'] = null;
			}else{
				if($buffer['select_type'] == 'copy'){
					$return_array = array('result' => '3'
								 ,'error_msg' => 'File copy was failed.');
					/*$return_array = array('result' => '3'
								 ,'error_msg' => '파일 복사에 실패했습니다');
								 */
				}elseif($buffer['select_type'] == 'cut'){
					$return_array = array('result' => '3'
								 ,'error_msg' => 'File copy was failed.');
					/*$return_array = array('result' => '3'
								 ,'error_msg' => '파일 이동에 실패했습니다');
								 */
				}
				
			}
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//선택된 디렉토리 또는 파일을  삭제
	function delete(){
		return nas_dlt();
	}
	
	//파일 또는 디렉토리의 이름 변경
	function rename_file(){
		$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
		$old_name = $_POST['old_name'];
		$type = $_POST['type'];
		$new_name = trim($_POST['new_name']);
		$return_array = array('result' => '0'
								 ,'error_msg' => 'Renaming was failed.');
		/*$return_array = array('result' => '0'
								 ,'error_msg' => '이름 변경에 실패했습니다');
								 */
								 
		if(empty($old_name) || ($type != 'directory' && $type != 'file')){
			$return_array = array('result' => '2'
								 ,'error_msg' => 'No file was selected to be renamed.');
			/*$return_array = array('result' => '2'
								 ,'error_msg' => '이름을 변경할 파일이 선택되지 않았습니다');
								 */
		}elseif(empty($new_name)){
			$return_array = array('result' => '3'
								 ,'error_msg' => 'Enter new name to rename.');
			/*$return_array = array('result' => '3'
								 ,'error_msg' => '변경할 이름을 입력해주십시오');
								 */
		}elseif(is_file($current_path.$new_name) || is_dir($current_path.$new_name)){
			$return_array = array('result' => '4'
								 ,'error_msg' => 'Entered name exists in the folder.\nEnter new name.');
			/*$return_array = array('result' => '4'
								 ,'error_msg' => '입력하신 이름의 파일 또는 디렉토리가 존재합니다'."\n".'다른 이름을 입력해주십시오');
								 */
		}elseif($new_name == $old_name){
			$return_array = array('result' => '5'
								 ,'error_msg' => 'Enter different name from previous one.');
			/*$return_array = array('result' => '5'
								 ,'error_msg' => '기존이름과 다른 이름을 입력해주십시오');
								 */
		}else{
			$dir_naming_limit = array('\\','/',':','*','?','"','<','>','|');
		
			foreach($dir_naming_limit as $limit_char){
				if(strpos($new_name,$limit_char)!==false){
					$return_array = array('result' => '6'
										 ,'error_msg' => '\"\\,/,:,*,?,",<,>,|\" cannot be used in a folder name.');
					/*$return_array = array('result' => '6'
										 ,'error_msg' => '파일 이름에는 \\,/,:,*,?,",<,>,| 문자를 사용할 수 없습니다');
										 */
					$return_str = json_encode($return_array);
					return $return_str;
				}
			}
			
			$cmd = 'mv "'.bsh_double_quote($current_path.$old_name).'" "'.bsh_double_quote($current_path.$new_name).'" ; sudo sync';
			$cmd_result = array();
			exec($cmd,$cmd_result);
			if(empty($cmd_result)){
				$this->clear_selected();
				$return_array = array('result' => '1');
			}
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	function get_directory_size(){
		$path = trim($_GET['path']);
		$target_dir = $_SESSION['user_directory'].$path;
		
		$pattern = "/([^\s]+)/";
		//calcurate directory total size
		$cmd = 'du -sh "'.$target_dir.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		$exploded = array();
		preg_match_all($pattern,$cmd_result[0],$exploded);
		
		$return_array['size'] = $exploded[0][0];
		
		$return_str = json_encode($return_array);
		return $return_str;
	}
	private function get_folder_size($path){
		//$path = trim($_GET['path']);
		//$target_dir = $_SESSION['user_directory']."/".$path;
		$ret = shell_exec("sudo du -s '$path'");
		$pattern = "/([^\s]+)/";
		$exploded = array();
		preg_match_all($pattern,$ret,$exploded);
		return floatval($exploded[0][0])*1024;
	}

	
	//=======================================================//
	// e-SATA
	//=======================================================//
	function show_me_files_esata(){
		alert("show_me_files_esata");
		// Loading session info
		$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
		if(!empty($_SESSION['buffer'])){
			$copy_cut_data = $_SESSION['buffer']['target_data'];
			$copy_cut_type = $_SESSION['buffer']['select_type'];
			$copy_cut_path = $_SESSION['buffer']['origin_path'];
		}
		$__current_dir = $_SESSION['current_dir'];
		$__full_current_dir = $_SESSION['storage_url'].$_SESSION['current_dir'];
		session_write_close();
		
		
		//$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
		//return $current_path;
		
		//$path_mode = $_GET['path_mode'];
		//echo $path_mode;	//
		
		
		//echo $current_path;	//
				
		@$sort_cond = $_GET['sort_cond'];
		$display_mode = $_GET['mode'];
		$file_or_dir_only = $_GET['file_or_dir_only'];
		$sort_cond_str = '';
		switch($sort_cond){
		case 'type':
			//$sort_cond_str = '--sort=extension '; break;
			$sort_cond_str = 'X'; break;
		case 'name':
			$sort_cond_str = ''; break;
		case 'size':
			$sort_cond_str = 'S'; break;
		case 'time':
		default:
			 //$sort_cond_str = '--sort=time ';
			 $sort_cond_str = '';
		}
		
		$return_array = array();
		$files = array();
		$dirs = array();
		/*
		$pattern = "/([^\s]+)/";
		//calcurate directory total size
		$cmd = 'du -sh "'.$current_path.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		if(!$cmd_result){
			return "NO ESATA".$current_path;
		}
		$exploded = array();
		preg_match_all($pattern,$cmd_result[0],$exploded);
		*/
		//$return_array['total_size'] = $exploded[0][0];	// total size
		$_total_size = 0;	// for calculation of total size
		
		//mime_content_type
		//$cmd = 'ls -lhG --full-time '.$sort_cond_str.'"'.$current_path.'"';
		$cmd = 'ls -le'.$sort_cond_str.' "'.$current_path.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		/*if(!$cmd_result){
			return "NO ESATA".$cmd;
		}*/
		//unset($cmd_result[0]);
		
		
		/*$_tmp = explode('/',$__current_dir);
		if(count($_tmp)-2 < 2){
			
			
			// Permission check
			$pattern = "/([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)(.*)/";
			$_ret_arr = array();
			foreach($cmd_result as $row){
				$file_row = array();
				$exploded = array();
				
				preg_match_all($pattern,$row,$exploded);
				$_filename = $exploded[21][0];
				unset($exploded);
				
				if(substr($current_path,-1)!='/') $current_path .= '/';
				$current_file = $current_path.$_filename;
				if(!nas_chk_dir_prms($current_file,'r')) continue;
				
				$_ret_arr[] = $row;
				
				//echo $current_file;
			}
			
			$cmd_result = $_ret_arr;
			
			
		}else{
			// No permission check
			
			
		}
		*/
		return json_encode($cmd_result);
		
		
		
		$copy_cut_data = array();
		$copy_cut_type = ''; //copy or cut
		$copy_cut_path = '';
		/*if(!empty($_SESSION['buffer'])){
			$copy_cut_data = $_SESSION['buffer']['target_data'];
			$copy_cut_type = $_SESSION['buffer']['select_type'];
			$copy_cut_path = $_SESSION['buffer']['origin_path'];
		}*/
		$pattern = "/([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)(.*)/";
		//return 'test';
		foreach($cmd_result as $row){
			$file_row = array();
			$exploded = array();
			
			preg_match_all($pattern,$row,$exploded);
			$exp = array('permission' 	=> $exploded[1][0],
			 			  'links' 	=> $exploded[3][0],
			 			  'owner' 	=> $exploded[5][0],
			 			  'group' 	=> $exploded[7][0],
			 			  'file_size' 	=> $exploded[9][0],
			 			  'week' 		=> $exploded[11][0],
			 			  'month_str'	=> $exploded[13][0],
			 			  'day' 		=> $exploded[15][0],
			 			  'time' 		=> $exploded[17][0],
			 			  'year' 		=> $exploded[19][0],
			 			  'file_name' 	=> $exploded[21][0]);
			unset($exploded);
			 			 
			$exp['date'] = $exp['year'].'/'.$this->month_arr[$exp['month_str']].'/'.$exp['day'];
			
			
			$file_row = array();
			$file_row['fd'] = (substr($exp['permission'],0,1) == 'd')?'directory':'file';
			
			// Filter out link
			if(substr($exp['permission'],0,1) == 'l') continue;
			// Permission Check
			if(substr($current_path,-1)!='/') $current_path .= '/';
			$current_file = $current_path.$exp['file_name'];
			if(!ext_chk_dir_prms()) continue;
			
			
			if($file_row["fd"]=="directory")
			{
				//$exp["file_size"]=$this->get_folder_size($current_file);
				$exp["file_size"]=" ";
			}
			
			
			if($file_row['fd'] == 'directory' 
				&& (empty($file_or_dir_only) || $file_or_dir_only == 'directory')){
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
				$file_row['selected'] = '';
				//if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['directory'])){
				if(($copy_cut_path == $__current_dir) && @in_array($file_row['file_name'],$copy_cut_data['directory'])){
					$file_row['selected'] = $copy_cut_type;
				}
				
				if($display_mode == 'list'){
					$file_row['size'] = $this->human_readable($exp['file_size']);
					$file_row['date'] = $exp['date'];
					$file_row['time'] = $exp['time'];
				}
				$_total_size += (float)$exp['file_size'];
				$dirs[] = $file_row;
			}elseif(empty($file_or_dir_only) || $file_or_dir_only == 'file'){
				
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
			
				//$full_url = $_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name'];
				$full_url = $__full_current_dir.$file_row['file_name'];
				$full_url_exploded = explode('/',$full_url);
				foreach($full_url_exploded as $url_cell_key => $url_cell_row){
					$full_url_exploded[$url_cell_key] = func_urlencode($url_cell_row);
				}
				$file_row['url'] = implode('/',$full_url_exploded);
				
				//$file_row['encoded_url'] = func_urlencode($_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name']);
				$file_row['encoded_url'] = func_urlencode($__full_current_dir.$file_row['file_name']);
				
				$mime_type = getLocalFileMimeType($current_path.$file_row['file_name']);
				$mime_type_arr = explode('/',$mime_type); //     image/gif
				$file_row['type'] = $mime_type_arr[0]; //     image
				
				if($display_mode == 'list'){
					$semi_cl_pos = strpos($mime_type_arr[1],';');
					if($semi_cl_pos ===false){
						$file_row['subtype'] = substr($mime_type_arr[1],0); //     gif
					}else{
						$file_row['subtype'] = substr($mime_type_arr[1],0,$semi_cl_pos); //     gif
					}
					$file_row['size'] = $this->human_readable($exp['file_size']);
					$file_row['date'] = $exp['date'];
					$file_row['time'] = $exp['time'];
				}
				
				$file_row['selected'] = '';
				//if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['file'])){
				if(($copy_cut_path == $__current_dir) && @in_array($file_row['file_name'],$copy_cut_data['file'])){
					$file_row['selected'] = $copy_cut_type;
				}
				$_total_size += (float)$exp['file_size'];
				$files[] = $file_row;
			}
		}
		//return 'test';
		function file_array_cmp($a,$b){
			return strcmp($a['file_name'], $b['file_name']);
		}
				
		if($sort_cond=='name'){
			usort($dirs,'file_array_cmp');
			usort($files,'file_array_cmp');
		}
		$return_array['dirs'] = $dirs;
		$return_array['files'] = $files;
		
		// NAS free capacity
		$_dev = $_GET['device'];
		$_tmp = shell_exec("df -B1 | grep '$_dev'");
		$_res = preg_match_all('/\b\d+\b/',$_tmp, $_matches);
		$_esata_free_cap = floatval($_matches[0][2]);
		$return_array['esata_free_cap'] = $_esata_free_cap;
		$return_array['total_size'] = $this->human_readable($_total_size);
		
		$return_str = json_encode($return_array);
		return $return_str;
		//return 'test';
	}
	function move_dir_esata(){
		$dir_name = trim($_GET['dir_name']);
		$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
		$new_path = $current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'].$dir_name.'/';
		$return_array = array();
		if(is_dir($new_path) && ext_chk_dir_prms()){
			$_SESSION['current_dir_esata'] = $_SESSION['current_dir_esata'].$dir_name.'/';
			session_write_close();
			$return_array = array('result' => '1');
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => 'The folder cannot be accessed.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	function get_curr_dir_path_esata(){
		$return_array = array('curr_url'=>$_SESSION['current_dir_esata']);
		session_write_close();
		$return_str = json_encode($return_array);
		return $return_str;
	}
	function move_up_esata(){
		$current_path = $_SESSION['current_dir_esata'];
		$return_array = array('result' => '2'
							  ,'error_msg' => 'The folder cannot be accessed.');
		
		if(strlen($current_path)>=3){
			$parent_dir = dirname($current_path);
			if($parent_dir == '\\'){
				$return_array = array('result' => '0'
										 ,'error_msg' => 'Here is root.');
			}else{
				if(is_dir($_SESSION['esata_dir'].$parent_dir)){
					if($parent_dir!='/'){
						$_SESSION['current_dir_esata'] = $parent_dir.'/';
					}else{
						$_SESSION['current_dir_esata'] = $parent_dir;
					}
					session_write_close();
					//success to move parent directory 
					$return_array = array('result' => '1');
				}else{
					$return_array = array('result' => '0'
										 ,'error_msg' => 'The folder cannot be accessed.');
				}
			}
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	// Copy a file or a folder from nas or e-sata to the other //
	function copy_esata_n2e(){

		// Loading session info
		$_user = $_SESSION['username'];
		$destination = $_SESSION['esata_dir'].$_SESSION['current_dir_esata']; //e-SATA
		$original_path = $_SESSION['user_directory'].$_SESSION['current_dir']; //NAS
		session_write_close();

		
		//Detect other's copy
		if(is_copying()){
			$return_array = array('result' => '11','error_msg' => 'Someone is copying from/to e-SATA');
			return json_encode($return_array);
		}		

		// Make e-sata copy file list //
		/* * * * * * * Using cmscopy * * * * * * */
		$_esataFolder = '/tmp/esata';
		shell_exec("sudo mkdir $_esataFolder; sudo chmod -R 777 $_esataFolder");
	
		
	
		$_srclist = '/tmp/esata/esata_srclist';
		if( !file_exists($_srclist) ){
			for($i=0;$i<3;$i++){
				if(file_exists($_srclist)){
					break;
				}else{
					$_tmp = shell_exec("sudo touch '$_srclist';sudo chmod 666 '$_srclist'");
				}
			}
			if( !file_exists($_srclist) ){
				$return_array = array('result' => '-10','error_msg' => 'Cannot create source list file');
				return json_encode($return_array);
			}
		}


		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			/* Using cmscopy */
			/* make source list */
			$_fh = fopen($_srclist, 'w');
			foreach($checked_dir as $_val){
				if(trim($_val)){
					fwrite($_fh,$original_path.$_val."\n");
				}
			}
			foreach($checked_files as $_val){
				if(trim($_val)){
					fwrite($_fh,$original_path.$_val."\n");
				}
			}
			fclose($_fh);
			//shell_exec("sudo touch /tmp/esata/esata_user ; sudo chmod 666 /tmp/esata/esata_user ; sudo echo '$_user' > /tmp/esata/esata_user");
			$return_array = $this->paste_esata($original_path,$destination,'ext',$_total_src_size); // Paste
			shell_exec('sudo date >> /tmp/esata/log ; sudo echo copy_esata_end >> /tmp/esata/log');
			return json_encode($return_array);
		}else{
			$return_array = array('result' => '-20','error_msg' => 'No selected file or folder');
			return json_encode($return_array);
		}		
	}
	private function paste_esata($original_path,$destination,$mode,$_src_size){
		// Close the opened session
		//session_write_close();
		
		
		// Permission check
		if($mode=='ext'){
			// copy to external device
			if(!ext_chk_dir_prms($destination,'w')){
				$return_array = array('result' => '0'
								,'error_msg' => 'No permission to copy');
				return $return_array;
			}
		}else{
			// copy to nas 
			if(!nas_chk_dir_prms($destination,'w')){
				$return_array = array('result' => '0'
								,'error_msg' => 'No permission to copy');
				return $return_array;
			}
		}
		
		/* * * * * * * Using cmscopy * * * * * * */
		$_tmpfile = '/tmp/esata/esata.tmp';
		for($i=0;$i<3;$i++){
			shell_exec("sudo touch '$_tmpfile' ; sudo chmod 666 '$_tmpfile'");
			if(file_exists($_tmpfile)){
				break;
			}
		}
		if(!file_exists($_tmpfile)){
			$return_array = array('result' => '-1'
							,'error_msg' => 'Cannot create temp file');
			return $return_array;
		}
		$_esata_prog = '/tmp/esata/esata_prog';
		$_esata_ccl = '/tmp/esata/esata_ccl';
		$_srclist = '/tmp/esata/esata_srclist';
		if( !file_exists($_esata_prog) ){
			for($i=0;$i<3;$i++){
				if(file_exists($_esata_prog)){
					break;
				}else{
					$_tmp = shell_exec("sudo touch '$_esata_prog';sudo chmod 666 '$_esata_prog'");
				}
			}
			if( !file_exists($_esata_prog) ){
				$return_array = array('result' => '-10','error_msg' => 'Cannot create source list file');
				return json_encode($return_array);
			}
		}		
		

		if( !file_exists($_esata_ccl) ){
			for($i=0;$i<3;$i++){
				if(file_exists($_esata_ccl)){
					break;
				}else{
					$_tmp = shell_exec("sudo touch '$_esata_ccl';sudo chmod 666 '$_esata_prog'");
				}
			}
			if( !file_exists($_esata_ccl) ){
				$return_array = array('result' => '-10','error_msg' => 'Cannot create source list file');
				return json_encode($return_array);
			}
		}
              $_tmp = shell_exec("sudo echo '' > '$_esata_ccl'");
		$_tmp = shell_exec("sudo echo 0 > '$_esata_prog'");

		shell_exec("sudo date > /tmp/esata/log");
		shell_exec("sudo echo start_cmscopy >> /tmp/esata/log");
		
		$_res = shell_exec("sudo nas-storage usb_copy '$_srclist' '$destination' '$_esata_ccl'");
		
		//if($_res == '153')
		//	shell_exec("sudo echo -153 > /tmp/esata/esata_prog");
		//shell_exec("sudo sync ; sudo date >> /tmp/esata/log");
		//shell_exec("sudo echo end_cmscopy >> /tmp/esata/log");

		exec("sudo rm $_esata_ccl");					
		$return_array = array('result' => '1','msg' => 'Start cmscopy','error_msg' => $_res);
		return $return_array;
		
	}
	
	
	/*********************************************************************************************/
	function copy_esata_e2n(){
		shell_exec('sudo date >> /tmp/esata/log ; sudo echo copy_esata_start >> /tmp/esata/log');
		// Loading session info
		$_user = $_SESSION['username'];
		$original_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata']; //e-SATA
		$destination = $_SESSION['user_directory'].$_SESSION['current_dir']; //NAS
		session_write_close();


		// Detect other's copy
		if(is_copying()){
			$return_array = array('result' => '11','error_msg' => 'Someone is copying from/to e-SATA');
			return json_encode($return_array);
		}

		// Make e-sata copy file list //
		/* * * * * * * Using cmscopy * * * * * * */
		$_srclist = '/tmp/esata/esata_srclist';
		if( !file_exists($_srclist) ){
			for($i=0;$i<3;$i++){
				if(file_exists($_srclist)){
					break;
				}else{
					$_tmp = shell_exec("sudo touch '$_srclist';sudo chmod 666 '$_srclist'");
				}
			}
			if( !file_exists($_srclist) ){
				$return_array = array('result' => '-10','error_msg' => 'Cannot create source list file');
				return json_encode($return_array);
			}
		}
		
		
		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			/* Using cmscopy */
			/* make source list */
			$_fh = fopen($_srclist, 'w');
			foreach($checked_dir as $_val){
				if(trim($_val)){
					fwrite($_fh,$original_path.$_val."\n");
				}
			}
			foreach($checked_files as $_val){
				if(trim($_val)){
					fwrite($_fh,$original_path.$_val."\n");
				}
			}
			fclose($_fh);

			$return_array = $this->paste_esata($original_path,$destination,'nas',$_total_src_size); // Paste
			
			shell_exec('sudo date >> /tmp/esata/log ; sudo echo copy_esata_end >> /tmp/esata/log');
			return json_encode($return_array);
		}else{
			$return_array = array('result' => '-20','error_msg' => 'No selected file or folder');
			return json_encode($return_array);
		}
	}
	
	/* e-SATA ***********************************************************************/
	function delete_esata(){
		return ext_dlt();
	}
	
	
	//=======================================================//
	// e-SATA : create a folder in NAS
	//=======================================================//
	function create_dir_nas(){
		return nas_crt_dir();
	}
	//=======================================================//
	// e-SATA : create a folder in e-SATA device
	//=======================================================//
	function create_dir_esata(){
		return ext_crt_dir();
		
	}
	
}
function is_copying(){
	$_srclist = '/tmp/esata/esata_srclist';
	$_esata_prog = '/tmp/esata/esata_prog';
	if(file_exists($_srclist)){
		$_lines = file($_esata_prog);
		if(trim($_lines[0]) != '100'){
			exec("sudo ps -w|grep cmscopy",$_matches);
			foreach($_matches as $_val){
				if(eregi('/usr/bin/cmscopy -l', $_val)){
					return true;
				}
			}
		}
	}
	return false;
	
}

function get_currDirectory() {
	$prefix_path=$_SESSION['user_directory'];
	$post_path=$_SESSION['current_dir'];
		

	if ( $_SESSION['current_dir'] == "/" ) 
	{
		exec("sudo echo get_currDirectory : ROOT PATH  >> /home/tmp.txt");
		$current_path = $prefix_path.$post_path;
	}		
        else
	{	

		//linear/
		$_temp_path_str=$_SESSION['current_dir'];
	
		exec("sudo echo get_currDirectory :  >> /home/tmp.txt");
		exec("sudo echo : $_temp_path_str >> /home/tmp.txt");

		$root_path_str = explode("/", $_temp_path_str);
				
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select path from folder_info where folder='$root_path_str[1]'");
			$sth->execute();
			$DB_folder_info1=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
	
		$prefix_path=$DB_folder_info1[0][0];
		$_post_path=$_SESSION['current_dir'];
	
		$position = strpos( $_post_path , "/" );
		$post_path = substr($_post_path, $position+1);
		$position = strpos( $post_path , "/" );
		$post_path = substr($post_path, $position);			
	
		$current_path = $prefix_path.$post_path;

		
	}

	exec("sudo echo path new: $current_path >> /home/tmp.txt");
	$_SESSION['prefix_dir'] = $current_path;

	
	return $current_path;	
}

function get_userAuthority(){

	return false;
	
}

?>
