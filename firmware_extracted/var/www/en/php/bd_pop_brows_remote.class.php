<?php
/*
 * 암 보드에서는 콘솔 명령어의 옵션이 제한적이므로
 * 거기에 맞춰서 수정
 */
require_once 'bd_pop_brows_conf.php';
require_once 'bd_pop_brows_com.php';
require 'nas_brow_common.php';
/* To handle a folder name with special characters */
require_once 'nas_comm.php';

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
	//private function check_dir_permission($full_path,$request_permission){
	//	if(empty($full_path)) return false;
	//	
	//	$cmd = 'ls -ld "'.$full_path.'"';
	//	$cmd_result = array();
	//	exec($cmd,$cmd_result);
	//	
	//	$pattern = "/([^\s]+)/";
	//	$exploded = array();
	//	preg_match_all($pattern,$cmd_result[0],$exploded);
	//	$exploded = $exploded[0];
	//	/*
	//	 * $exploded[0] = permission
	//	 * $exploded[1] = number of links
	//	 * $exploded[2] = owner
	//	 */
	//	 
	//	 //디렉토리 소유자 확인,해당 퍼미션 확인
	//	 $directory_permission = '';
	//	 if($exploded[2] == $_SESSION['username'])
	//	 {
	//	 	$directory_permission = substr($exploded[0],1,3);
	//	}else
	//	{
	//		$directory_permission = substr($exploded[0],7,3);
	//	}
	//	 
	//	 //요구된 퍼미션에 부합하는지 확인
	//	 $request_p_arr = str_split($request_permission);
	//	 $return_val = true;
	//	 foreach($request_p_arr as $p_name){
	//	 	if($p_name != '-'){
	//		 	if(strpos($directory_permission,$p_name)===false){
	//		 		$return_val = false;
	//		 	}
	//	 	}
	//	 }
	//	 return $return_val;
	//}
	
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
		$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
				
		// Preparing for sorting list
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
		//$return_array = array();
		//$files = array();
		$dirs = array();
		//$pattern = "/([^\s]+)/";
		//$cmd = 'sudo du -sh "'.$current_path.'"';
		//$cmd_result = array();
		//exec($cmd,$cmd_result);
		//$exploded = array();
		//preg_match_all($pattern,$cmd_result[0],$exploded);
		//$return_array['total_size'] = $exploded[0][0];
		
		/* To convert a folder name to available form */		
		$current_path = encode_filename($current_path);
		//var_dump($current_path);				
		$cmd = 'sudo ls -le'.$sort_cond_str.' '.$current_path.' ';
	
		$cmd_result = array();
		exec($cmd,$cmd_result);
		
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
			//if($file_row["fd"]=="directory")
			//{
			//	$exp["file_size"]=$this->get_folder_size($exp["file_name"]);
			//	//$exp["file_size"]="100 K";
			//}
			if($file_row['fd'] == 'directory' 
				&& (empty($file_or_dir_only) || $file_or_dir_only == 'directory')){
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
				$file_row['selected'] = '';
				if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['directory'])){
					$file_row['selected'] = $copy_cut_type;
				}
				
				if($display_mode == 'list'){
					//$file_row['size'] = $this->human_readable($exp['file_size']);
					$file_row['date'] = $exp['date'];
					$file_row['time'] = $exp['time'];
				}
				// Permission to select a folder
				if(nas_chk_dir_prms($current_file,'w')){
					$file_row['permission'] = 'w';
				}else{
					$file_row['permission'] = 'r';
				}
				
				$dirs[] = $file_row;
			}
			//elseif(empty($file_or_dir_only) || $file_or_dir_only == 'file'){
			//	
			//	$file_row['file_name'] = $exp['file_name'];
			//	$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
			//
			//	$full_url = $_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name'];
			//	$full_url_exploded = explode('/',$full_url);
			//	foreach($full_url_exploded as $url_cell_key => $url_cell_row){
			//		$full_url_exploded[$url_cell_key] = func_urlencode($url_cell_row);
			//	}
			//	$file_row['url'] = implode('/',$full_url_exploded);
			//	
			//	$file_row['encoded_url'] = func_urlencode($_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name']);
			//	
			//	$mime_type = getLocalFileMimeType($current_path.$file_row['file_name']);
			//	$mime_type_arr = explode('/',$mime_type); //     image/gif
			//	$file_row['type'] = $mime_type_arr[0]; //     image
			//	
			//	if($display_mode == 'list'){
			//		$semi_cl_pos = strpos($mime_type_arr[1],';');
			//		if($semi_cl_pos ===false){
			//			$file_row['subtype'] = substr($mime_type_arr[1],0); //     gif
			//		}else{
			//			$file_row['subtype'] = substr($mime_type_arr[1],0,$semi_cl_pos); //     gif
			//		}
			//		$file_row['size'] = $this->human_readable($exp['file_size']);
			//		$file_row['date'] = $exp['date'];
			//		$file_row['time'] = $exp['time'];
			//	}
			//	
			//	$file_row['selected'] = '';
			//	if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['file'])){
			//		$file_row['selected'] = $copy_cut_type;
			//	}
			//	
			//	$files[] = $file_row;
			//}
		}
		function file_array_cmp($a,$b){
			return strcmp($a['file_name'], $b['file_name']);
		}
				
		if($sort_cond=='name'){
			usort($dirs,'file_array_cmp');
			//usort($files,'file_array_cmp');
		}
		$return_array['dirs'] = $dirs;
		//$return_array['files'] = $files;
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//현재 참조중인 디렉토리의 패스를 리턴
	function get_curr_dir_path(){
		//$return_array = array('curr_url'=>$_SESSION['current_dir']);		
		$current_path=$_SESSION['current_dir'];		
		$return_array = array('curr_url'=>$current_path,'cur_encd_path'=>func_urlencode($current_path));
		
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//현재 디렉토리에 주어진 이름의 디렉토리 생성
	function create_dir(){
		
		return nas_crt_dir('bd_pop');
		
		
		/*$dir_name = trim($_POST['new_directory_name']);
		if(empty($dir_name)){
			$return_array = array('result' => '4'
								 ,'error_msg' => 'Folder name was not entered.');
		}
		$dir_naming_limit = array('\\','/',':','*','?','"','<','>','|');
		foreach($dir_naming_limit as $limit_char){
			if(strpos($dir_name,$limit_char)!==false){
				$return_array = array('result' => '0'
									 ,'error_msg' => '\"\\,/,:,*,?,",<,>,|\" cannot be used in a folder name.');
				$return_str = json_encode($return_array);
				return $return_str;
			}
		}
		$return_array = array();
		$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
		if(!is_dir($current_path.$dir_name) && !is_file($current_path.$dir_name)){
			$mk_path = $current_path.$dir_name;
			//=======================================================//
			// Samba permission check
			//=======================================================//
			$tmp_path = (substr($current_path,-1)=="/") ? substr($current_path,0,strlen($current_path)-1) : $current_path;
			$flag = 0;
			if( in_array($tmp_path,$_SESSION['rw_dir']) )
			{
				$flag = 1;
			}else if( in_array($tmp_path,$_SESSION['ro_dir']) )
			{
				$flag = 0;
			}else if( $this->check_dir_permission($current_path,'w') )
			{
				$flag = 1;
			}
			if($flag == 0) 
			{
				$return_array = array('result' => '2'
									 ,'error_msg' => 'Folder creation was failed. No permission.');
				$return_str = json_encode($return_array);
				return $return_str;
			}
			$ret = shell_exec("sudo mkdir 755 '$mk_path'");
			$temp=error_get_last();
			$tmp_ret=ereg("Permission denied",$temp['message']);
			if($tmp_ret)
			{
				$return_array = array('result' => '2'
									 ,'error_msg' => 'Folder creation was failed.');
			}else{
				$tmp = $_SESSION['username'].'.admin';
				$ret = shell_exec("sudo chown '$tmp' '$mk_path'");
				$return_array = array('result' => '1', 'chown' => $tmp);
			}
		}else{
			$return_array = array('result' => '3'
								 ,'error_msg' => 'There is already same name of folder or file.');
		}
		$return_str = json_encode($return_array);
		return $return_str;*/
	}
	
	//다른 디렉토리로 이동
	function move_dir(){
		$dir_name = trim($_GET['dir_name']);
		$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
		$new_path = $current_path = $_SESSION['user_directory'].$_SESSION['current_dir'].$dir_name;
		//=======================================================//
		// Share folder check
		//=======================================================//
		if( nas_chk_dir_prms($new_path,'r') )
		{
			$_SESSION['current_dir'] = $_SESSION['current_dir'].$dir_name.'/';
			$return_array = array('result' => '1');
		}else
		{
			$return_array = array('result' => '0'
								 ,'error_msg' => 'The folder cannot be accessed. No permission to read.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	//상위 디렉토리로 이동
	function move_up(){
		$current_path = $_SESSION['current_dir'];
		//default error : current directory is user root
		$return_array = array('result' => '2'
							  ,'error_msg' => 'The folder cannot be accessed.');
		if(strlen($current_path)>=3){
			$parent_dir = dirname($current_path);
			if($parent_dir != '\\'){
				if(is_dir($_SESSION['user_directory'].$parent_dir)){
					if($parent_dir!='/'){
						$_SESSION['current_dir'] = $parent_dir.'/';
					}else{
						$_SESSION['current_dir'] = $parent_dir;
					}
					
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
						$cmd = 'cp -r "'.bsh_double_quote($original_path.$row).'" "'.$destination.'"';
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
						$cmd = 'cp "'.bsh_double_quote($original_path.$row).'" "'.$destination.'"';
						$cmd_result_1 = array();
						exec($cmd,$cmd_result_1);
					}else{
						$sources .= ' "'.bsh_double_quote($original_path.$row).'"';
					}
				}
			}
			if($buffer['select_type'] == 'cut'){
				$cmd = 'mv '.$sources.' "'.$destination.'"';
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
					$cmd = 'cp -r "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_dir_name).'"';
				}elseif($buffer['select_type'] == 'cut'){
					$cmd = 'mv "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_dir_name).'"';
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
					$cmd = 'cp "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_file_name).'"';
				}elseif($buffer['select_type'] == 'cut'){
					$cmd = 'mv "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_file_name).'"';
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
	function one_delete(){
		
		return nas_dlt('bd_pop');
		
		$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
		if(substr($current_path,-1) != '/') $current_path .= '/';
		$checked_file = $_GET['file'];
		$del_file = trim($current_path.$checked_file);
		if(!empty($checked_file))
		{
			$tmp_path = (substr($current_path,-1)=="/") ? substr($current_path,0,strlen($current_path)-1) : $current_path;
			$flag = 0;
			if( in_array($tmp_path,$_SESSION['rw_dir']) )
			{
				$flag = 1;
			}else if( in_array($tmp_path,$_SESSION['ro_dir']) )
			{
				$flag = 0;
			}else if( $this->check_dir_permission($current_path,'w') )
			{
				$flag = 1;
			}
			if($flag == 0) 
			{
				$return_array = array('result' => '2'
									 ,'error_msg' => 'Folder delete was failed. No permission.');
				$return_str = json_encode($return_array);
				return $return_str;
			}
			$ret = shell_exec("sudo rm -rf '$del_file'");
			if(empty($ret)){
				$return_array = array('result' => '1');
			}else{
				$return_array = array('result' => '2'
								 ,'error_msg' => 'Problem in deleting. Some files could not be deleted.');
			}
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
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
		}elseif(is_file($current_path.$new_name) || is_dir($current_path.$new_name)){
			$return_array = array('result' => '4'
								 ,'error_msg' => 'Entered name exists in the folder.\nEnter new name.');
		}elseif($new_name == $old_name){
			$return_array = array('result' => '5'
								 ,'error_msg' => 'Enter different name from previous one.');
		}else{
			$dir_naming_limit = array('\\','/',':','*','?','"','<','>','|');
		
			foreach($dir_naming_limit as $limit_char){
				if(strpos($new_name,$limit_char)!==false){
					$return_array = array('result' => '6'
										 ,'error_msg' => '\"\\,/,:,*,?,",<,>,|\" cannot be used in a folder name.');
					$return_str = json_encode($return_array);
					return $return_str;
				}
			}
			
			$cmd = 'mv "'.bsh_double_quote($current_path.$old_name).'" "'.bsh_double_quote($current_path.$new_name).'"';
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
		$target_dir = $_SESSION['user_directory']."/".$path;
		//return $target_dir;////
		
		$pattern = "/([^\s]+)/";
		//calcurate directory total size
		$cmd = 'du -sh "'.$target_dir.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		$exploded = array();
		preg_match_all($pattern,$cmd_result[0],$exploded);
		
		$return_array['size'] = $exploded[0][0];
		
		$return_str = $return_array['size'];
		return $return_str;
	}////park94

	
	//=======================================================//
	// e-SATA
	//=======================================================//
	function show_me_files_esata(){
		//$path_mode = $_GET['path_mode'];
		//echo $path_mode;	//
		
		$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
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
		$pattern = "/([^\s]+)/";
		//calcurate directory total size
		$cmd = 'du -sh "'.$current_path.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		$exploded = array();
		preg_match_all($pattern,$cmd_result[0],$exploded);
		$return_array['total_size'] = $exploded[0][0];
		
		//mime_content_type
		//$cmd = 'ls -lhG --full-time '.$sort_cond_str.'"'.$current_path.'"';
		$cmd = 'ls -le'.$sort_cond_str.' "'.$current_path.'"';
		$cmd_result = array();
		exec($cmd,$cmd_result);
		//unset($cmd_result[0]);
		
		$copy_cut_data = array();
		$copy_cut_type = ''; //copy or cut
		$copy_cut_path = '';
		if(!empty($_SESSION['buffer'])){
			$copy_cut_data = $_SESSION['buffer']['target_data'];
			$copy_cut_type = $_SESSION['buffer']['select_type'];
			$copy_cut_path = $_SESSION['buffer']['origin_path'];
		}
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
			$file_row = array();
			$file_row['fd'] = (substr($exp['permission'],0,1) == 'd')?'directory':'file';
			////park94
			if(substr($exp['permission'],0,1) == 'l')
			{
				//echo $exp['file_name']."\n";
				$tmp = explode("->",$exp['file_name']);
				//print_r($tmp);
				$exp['file_name']=trim($tmp[0]);
				//$tmp[1] = is_dir(trim($tmp[1]));
				//echo "$tmp[0] : $tmp[1]\n";
				if(is_dir(trim($tmp[1])))
				{
					$file_row['fd']="directory";
				}
			}
			if($file_row["fd"]=="directory")
			{
				$exp["file_size"]=$this->get_folder_size($exp["file_name"]);
				//$exp["file_size"]="100 K";
			}
			
			
			if($file_row['fd'] == 'directory' 
				&& (empty($file_or_dir_only) || $file_or_dir_only == 'directory')){
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
				$file_row['selected'] = '';
				if(($copy_cut_path == $_SESSION['current_dir']) && @in_array($file_row['file_name'],$copy_cut_data['directory'])){
					$file_row['selected'] = $copy_cut_type;
				}
				
				if($display_mode == 'list'){
					$file_row['size'] = $this->human_readable($exp['file_size']);
					$file_row['date'] = $exp['date'];
					$file_row['time'] = $exp['time'];
				}
				$dirs[] = $file_row;
			}elseif(empty($file_or_dir_only) || $file_or_dir_only == 'file'){
				
				$file_row['file_name'] = $exp['file_name'];
				$file_row['encoded_file_name'] = func_urlencode($file_row['file_name']);
			
				$full_url = $_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name'];
				$full_url_exploded = explode('/',$full_url);
				foreach($full_url_exploded as $url_cell_key => $url_cell_row){
					$full_url_exploded[$url_cell_key] = func_urlencode($url_cell_row);
				}
				$file_row['url'] = implode('/',$full_url_exploded);
				
				$file_row['encoded_url'] = func_urlencode($_SESSION['storage_url'].$_SESSION['current_dir'].$file_row['file_name']);
				
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
		$return_str = json_encode($return_array);
		return $return_str;
	}
	function move_dir_esata(){
		$dir_name = trim($_GET['dir_name']);
		$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
		$new_path = $current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'].$dir_name.'/';
		$return_array = array();
		if(is_dir($new_path) && $this->check_dir_permission($new_path,'r')){
		//if(is_dir($new_path)){
			$_SESSION['current_dir_esata'] = $_SESSION['current_dir_esata'].$dir_name.'/';
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
		$return_str = json_encode($return_array);
		return $return_str;
	}
	function move_up_esata(){
		$current_path = $_SESSION['current_dir_esata'];
		//default error : current directory is user root
		$return_array = array('result' => '2'
							  ,'error_msg' => 'The folder cannot be accessed.');
		/*$return_array = array('result' => '2'
							  ,'error_msg' => '디렉토리에 접근할 수 없습니다');
							  */
		if(strlen($current_path)>=3){
			$parent_dir = dirname($current_path);
			if($parent_dir != '\\'){
				if(is_dir($_SESSION['esata_dir'].$parent_dir)){
					if($parent_dir!='/'){
						$_SESSION['current_dir_esata'] = $parent_dir.'/';
					}else{
						$_SESSION['current_dir_esata'] = $parent_dir;
					}
					
					//success to move parent directory 
					$return_array = array('result' => '1');
				}else{
					$return_array = array('result' => '0'
										 ,'error_msg' => 'The folder cannot be accessed.');
					/*$return_array = array('result' => '0'
										 ,'error_msg' => '디렉토리에 접근할 수 없습니다');
										 */
				}
			}
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	// Copy a file or a folder from nas or e-sata to the other //
	function copy_esata_n2e(){
		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			$destination = $_SESSION['esata_dir'].$_SESSION['current_dir_esata']; //e-SATA
			$original_path = $_SESSION['user_directory'].$_SESSION['current_dir']; //NAS
			
			$session_copy_data = array('select_type' => 'copy',
										'origin_path' => $original_path,
										'target_data' =>
												array('directory' => $checked_dir,
													  'file' => $checked_files
													)
								);
			$_SESSION['buffer'] = $session_copy_data;
			
			$return_array = $this->paste_esata($original_path,$destination); // Paste
			
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => json_encode($_GET).'No data was selected.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	private function paste_esata($original_path,$destination){
		$buffer = $_SESSION['buffer'];
		//$current_path = $_SESSION['current_dir'];
		if(empty($buffer)){
			$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');

		}else{
			//$destination = $_SESSION['user_directory'].$current_path;
			//$original_path = $_SESSION['user_directory'].$buffer['origin_path'];
			
			$sources = '';
			$new_name_copy = array();
			foreach($buffer['target_data']['directory'] as $row){
				if(is_file($destination.$row) || is_dir($destination.$row)){
					$new_name_copy['dir'][] = $row;
				}else{
					if($buffer['select_type'] == 'copy'){
						$cmd = 'cp -r "'.bsh_double_quote($original_path.$row).'" "'.$destination.'"';
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
						$cmd = 'cp "'.bsh_double_quote($original_path.$row).'" "'.$destination.'"';
						$cmd_result_1 = array();
						exec($cmd,$cmd_result_1);
					}else{
						$sources .= ' "'.bsh_double_quote($original_path.$row).'"';
					}
				}
			}
			if($buffer['select_type'] == 'cut'){
				$cmd = 'mv '.$sources.' "'.$destination.'"';
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
					$cmd = 'cp -r "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_dir_name).'"';
				}elseif($buffer['select_type'] == 'cut'){
					$cmd = 'mv "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_dir_name).'"';
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
					$cmd = 'cp "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_file_name).'"';
				}elseif($buffer['select_type'] == 'cut'){
					$cmd = 'mv "'.bsh_double_quote($original_path.$row).'" "'.bsh_double_quote($destination.$new_file_name).'"';
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
				}elseif($buffer['select_type'] == 'cut'){
					$return_array = array('result' => '3'
								 ,'error_msg' => 'File copy was failed.');
				}
				
			}
		}
		//$return_str = json_encode($return_array);
		return $return_array;
	}
	
	
	/*********************************************************************************************/
	function copy_esata_e2n(){
		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			$original_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata']; //e-SATA
			$destination = $_SESSION['user_directory'].$_SESSION['current_dir']; //NAS
			
			$session_copy_data = array('select_type' => 'copy',
										'origin_path' => $original_path,
										'target_data' =>
												array('directory' => $checked_dir,
													  'file' => $checked_files
													)
								);
			$_SESSION['buffer'] = $session_copy_data;
			
			$return_array = $this->paste_esata($original_path,$destination); // Paste
			
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => json_encode($_GET).'No data was selected.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	/*********************************************************************************************/
	function delete_esata(){
		// e-SATA delete
		$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
		$checked_dir = $_POST['directory'];
		$checked_files = $_POST['file'];
		$return_array = array();
		if(!empty($checked_dir) || !empty($checked_files)){
			
			$sources = '';
			foreach($checked_dir as $data_name){
				$data_name = trim($data_name);
				if(!empty($data_name)){
					$sources .= ' "'.bsh_double_quote($current_path.$data_name).'"';
				}
			}
			foreach($checked_files as $data_name){
				$data_name = trim($data_name);
				if(!empty($data_name)){
					$sources .= ' "'.bsh_double_quote($current_path.$data_name).'"';
				}
			}
			$cmd = 'rm -r '.$sources;
			$cmd_result = array();
			exec($cmd,$cmd_result);
			
			if(empty($cmd_result)){
				$return_array = array('result' => '1');
			}else{
				$return_array = array('result' => '2'
								 ,'error_msg' => 'Problem in deleting. Some files could not be deleted.');
			}
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');
		}
		$return_str = json_encode($return_array);
		return $return_str;
	}
}
?>