<?php
//=======================================================//
// Function Library for File Browsing
//=======================================================//

//=======================================================//
// Configuration for browser
//=======================================================//
require 'nas_brow_conf.php';

//=======================================================//
// Permission
// Input permission : r/w
//=======================================================//
function nas_chk_dir_prms($full_path,$request_permission){
	
	// Input
	if(empty($full_path) || !$request_permission) return false;
	
	// Path
	global $share_dirs, $ro_dirs, $vols;
	
	$_full_path = $full_path;
	if(substr($_full_path,-1)=='/'){
		$_full_path = substr($_full_path,0,-1);
	}
	$_tmp_path = $_full_path;
	if(substr($_full_path,0,1)=='/'){
		$_tmp_path = substr($_full_path,1);
	}
	$_dirs = explode('/',$_tmp_path);
	$_dir_depth = count($_dirs);
	
	// Volumes
	$_vol = $_dirs[$_dir_depth-1];
		
	$vol_list = trim(exec ('sudo nas-storage get vol_list'));
	$lists = explode(" ",$vol_list);
	//exec("sudo echo vol_list0: $lists[0] >> /home/phplog.txt");
	//exec("sudo echo vol_list1: $lists[1] >> /home/phplog.txt");

	if($_dir_depth==3){

		if(!eregi($_vol,$lists[0]))
		{
			if(!eregi($_vol,$lists[1]))
			{
				return false;
			}
		}
	}
	
	if(eregi('lost\+found',$_vol))
	{
		return false;
	}	

	
	if($_dir_depth==2){
		exec("sudo echo enter depth: $_dir_depth >> /home/phplog.txt");
		if($request_permission=='r' && in_array($_full_path,$vols)) 
		{
			return true;
		}
	}
	

	// RW/RO
	if($_dir_depth>2){
		
		// Directories made by WEB
		$_tmp_path = dir_to_path($_dirs,3);
		
		if(in_array($_tmp_path,$_SESSION['rw_dir'])){
				return true;
		}else if($request_permission==='r' && in_array($_tmp_path,$_SESSION['ro_dir']) ){
				return true;
		}
		
		// System default directories : RW
		if($_dir_depth>=3){
			
			$_tmp_path = dir_to_path($_dirs,4);
			exec("sudo echo 10 : $_tmp_path>> /home/phplog.txt");
			if($_dir_depth >= 3)
			{
				return true;
			}
			
			if(in_array($_tmp_path,$share_dirs[5])){
				return true;
			}
			$_tmp_path = dir_to_path($_dirs,5);
			if(in_array($_tmp_path,$share_dirs[6])){
				return true;
			}
		}
		
		// System default directories : RO
		if($request_permission=='r'){
			if($_dir_depth==3 && in_array($_full_path,$ro_dirs[4])) return true;
			if($_dir_depth==4 && in_array($_full_path,$ro_dirs[5])) return true;
		}
	}
	
	
	return false;
}
function dir_to_path($arr,$int){
	$_ret = '';
	for($i=0;$i<$int;$i++){
		$_ret .= '/'.$arr[$i];
	}
	return $_ret;
}
//=======================================================//
// Display
//=======================================================//
/*function nas_dspl(){
	$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
		
	//Listing all dirs/files
	$cmd = 'sudo ls -le "'.$current_path.'"';
	$cmd_result = array();
	exec($cmd,$cmd_result);
	
	
	//Sum total capacity
	
}*/


//=======================================================//
// Create Directory
//=======================================================//
function nas_crt_dir($mode){
	// Directory Name
	$return_array = array();
	if($mode=='bd_pop'){
		$_new_dir_name = 'new_directory_name';
	}else{
		$_new_dir_name = 'new_directory_name_nas';
	}
	$dir_name = trim($_POST[$_new_dir_name]);
	if(empty($dir_name)){
			$return_array = array('result' => '0'
								 ,'error_msg' => 'Folder name was not entered.');
	}
	// Permission
	$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
	session_write_close();

	$dir = $_SESSION['current_dir'];
	$tmp = explode("/",$dir);
	$folder = explode("/",$tmp[1]);
	exec("sudo echo temp : $tmp[0] >> /home/tmp.txt"); 

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
			
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select folder from folder_member where rw='$_user' or rw='$_group'");
		//$sth=$dbh->prepare("select folder from folder_member where (rw='$_user' or rw='$_group') and folder='$folder[0]' ");
		$sth->execute();
		$DB_folder_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	
	$folder_count=count($DB_folder_info);
	$isPermission = false;
	for($index=0;$index<$folder_count;$index++) {
		$test_val = $DB_folder_info[$index][0];
		if(eregi($test_val, $folder[0]))
		{			
			$isPermission = true;
			break;
		}
	}
	if($isPermission == false)
	{
		$return_array = array('result' => '0'
								 ,'error_msg' => 'No permission to create a folder.');
		$return_str = json_encode($return_array);
		return $return_str;
	}

	if(!nas_chk_dir_prms($current_path,'w')){
		$return_array = array('result' => '0'
								 ,'error_msg' => 'No permission to create a folder.');
		$return_str = json_encode($return_array);
		return $return_str;
	}
	
	// Directory name charater
	$dir_naming_limit = array('\\','/',':','*','?','"','<','>','|');
	foreach($dir_naming_limit as $limit_char){
		if(strpos($dir_name,$limit_char)!==false){
			$return_array = array('result' => '0'
								 ,'error_msg' => '\"\\,/,:,*,?,",<,>,|\" cannot be used in a folder name.');
			$return_str = json_encode($return_array);
			return $return_str;
		}
	}
	
	// Create
	if(is_dir($current_path.$dir_name) || is_file($current_path.$dir_name)){		
		$return_array = array('result' => '0'								
								,'error_msg' => 'There is already same name of folder or file.');	
	}else{		
		$_full_path = $current_path.$dir_name;		
		$_full_path = encode_filename($_full_path);
		//var_dump($_full_path);				
		//$_ret = shell_exec("sudo mkdir '$_full_path' 2>&1");		
		$_ret = shell_exec('sudo mkdir '.$_full_path.' 2>&1');		
		if(empty($_ret)){			
			//$ret = shell_exec("sudo chmod 777 '$_full_path' ;sudo sync");			
			$ret = shell_exec('sudo chmod 777 '.$_full_path.' ;sudo sync');			
			$return_array = array('result' => '1');		
		}else{			
			$return_array = array('result' => '0'								
								,'error_msg' => "Folder creation was failed.\n");		
		}	
	}

	
	// Return String
	$return_str = json_encode($return_array);
	return $return_str;
}
//=======================================================//
// Delete directory/file
//=======================================================//
function nas_dlt($mode){
	session_write_close();
	// Selected directory/file
	$checked_dir = $_POST['directory'];
	if($mode=='bd_pop'){
		$checked_files = array( $_GET['file'] );
	}else{
		$checked_files = $_POST['file'];
	}
	$return_array = array();
	if(empty($checked_dir) && empty($checked_files)){
		$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');
		$return_str = json_encode($return_array);
		return $return_str;
	}
	// Permission
	$current_path = $_SESSION['user_directory'].$_SESSION['current_dir'];
	if(!nas_chk_dir_prms($current_path,'w')){
		$return_array = array('result' => '0'
								 ,'error_msg' => 'No permission to delete.');
		$return_str = json_encode($return_array);
		return $return_str;
	}
	// Delete
	$sources = '';
	foreach($checked_dir as $data_name){
		$data_name = trim($data_name);
		if(!empty($data_name)){
			//$sources .= ' "'.bsh_double_quote($current_path.$data_name).'"';
			$sources .= " ".encode_filename($current_path.$data_name);
		}
	}
	foreach($checked_files as $data_name){
		$data_name = trim($data_name);
		if(!empty($data_name)){
			//$sources .= ' "'.bsh_double_quote($current_path.$data_name).'"';
			$sources .= " ".encode_filename($current_path.$data_name);
		}
	}
	//$sources = str_replace('$','\$',$sources); // delete folder with '$'
	//$cmd = 'sudo rm -r '.$sources.' 2>&1';
	//$_ret = shell_exec($cmd);
	$_ret = shell_exec('sudo rm -rf '.$sources.' 2>&1');
	if(empty($_ret)){
		$return_array = array('result' => '1');
		shell_exec('sudo sync');
	}else{
		$return_array = array('result' => '0'
						 ,'error_msg' => "Problem in deleting. Some files could not be deleted.\n");
	}
	$return_str = json_encode($return_array);
	return $return_str;
}
//=======================================================//
// Copy
//=======================================================//
function nas_cp(){
	
}
//=======================================================//
// Paste
//=======================================================//
function nas_pst(){
	
}

//=======================================================//
// External device
// Permission check
//=======================================================//
function ext_chk_dir_prms($path,$permission){
	return true;
}

//=======================================================//
// Create / Delete
//=======================================================//
function ext_crt_dir(){
	$return_array = array();
	$dir_name = trim($_POST['new_directory_name_esata']);
	if(empty($dir_name)){
			$return_array = array('result' => '4'
								 ,'error_msg' => 'Folder name was not entered.');
			$return_str = json_encode($return_array);
			return $return_str;
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
	$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
	session_write_close();
	
	$_tmp_dstdir = $current_path.$dir_name;
	if(!is_dir($_tmp_dstdir) && !is_file($_tmp_dstdir)){
		// Create dir
		//$_ret = shell_exec("sudo mkdir '$_tmp_dstdir' 2>&1");
		$_ret = shell_exec("sudo mkdir ".encode_filename($_tmp_dstdir)." 2>&1");
		if(empty($_ret)){
			$ret = shell_exec("sudo chmod 777 '$_tmp_dstdir' ; sudo sync");
			$return_array = array('result' => '1');
		}else{
			$return_array = array('result' => '0'
								 ,'error_msg' => "Folder creation was failed.\n");
		}
		
		/* PHP create dir
		mkdir($current_path.$dir_name, 0777);
		$_full_path = $current_path.$dir_name;
		$ret = shell_exec("sudo mkdir '$_full_path' ; sudo chmod 777 '$_full_path' ; sudo sync");
		$temp=error_get_last();
		$tmp_ret=ereg("Permission denied",$temp['message']);
		if($tmp_ret)
		{
			$return_array = array('result' => '2'
								 ,'error_msg' => 'Folder creation was failed. [PHP]Permission denied.');
		}else if(empty($cmd_result)){
			$cmd = $current_path.$dir_name;
			$return_array = array('result' => '1');
		}else{
			$return_array = array('result' => '2'
								 ,'error_msg' => 'Folder creation was failed.');
		}
		*/
	}else{
		$return_array = array('result' => '3'
							 ,'error_msg' => 'There is already same name of folder or file.');
	}
	$return_str = json_encode($return_array);
	return $return_str;
}

function ext_dlt(){
	session_write_close();
	// Selected directory/file
	$checked_dir = $_POST['directory'];
	$checked_files = $_POST['file'];
	$return_array = array();
	if(empty($checked_dir) && empty($checked_files)){
		$return_array = array('result' => '0'
								 ,'error_msg' => 'No data was selected.');
		$return_str = json_encode($return_array);
		return $return_str;
	}
	$current_path = $_SESSION['esata_dir'].$_SESSION['current_dir_esata'];
	
	
	// Delete
	$sources = '';
	foreach($checked_dir as $data_name){
		$data_name = trim($data_name);
		if(!empty($data_name)){
			//$sources .= ' "'.bsh_double_quote($current_path.$data_name).'"';
			$sources .= " ".encode_filename($current_path.$data_name);
		}
	}
	foreach($checked_files as $data_name){
		$data_name = trim($data_name);
		if(!empty($data_name)){
			//$sources .= ' "'.bsh_double_quote($current_path.$data_name).'"';
			$sources .= " ".encode_filename($current_path.$data_name);
		}
	}
	
	// Linux command
	$cmd = 'sudo rm -r '.$sources.' 2>&1';
	$_ret = shell_exec($cmd);
	if(empty($_ret)){
		$return_array = array('result' => '1');
		shell_exec('sudo sync');
	}else{
		$return_array = array('result' => '0'
						 ,'error_msg' => "Problem in deleting. Some files could not be deleted.\n");
	}
	$return_str = json_encode($return_array);
	return $return_str;
	
	
	// PHP function
	foreach($checked_dir as $dirs){
		if(!unlinkRecursive($current_path.$dirs,true)){
			$_err = error_get_last();
			$return_array = array('result' => '0'
									, 'error_msg' => 'Fail to remove dir. '.$_err['message']);
			return json_encode($return_array);
		}
	}
	foreach($checked_files as $files){
		if(!unlink($current_path.$files)){
			$_err = error_get_last();
			$return_array = array('result' => '0'
									, 'error_msg' => 'Fail to remove file. '.$_err['message']);
			return json_encode($return_array);
		}
	}
	$return_array = array('result' => '1');
	return json_encode($return_array);
}


/** 
 * Recursively delete a directory 
 * 
 * @param string $dir Directory name 
 * @param boolean $deleteRootToo Delete specified top-level directory as well 
 */ 
function unlinkRecursive($dir, $deleteRootToo) 
{ 
    if(!$dh = @opendir($dir)) 
    { 
        return; 
    } 
    while (false !== ($obj = readdir($dh))) 
    { 
        if($obj == '.' || $obj == '..') 
        { 
            continue; 
        } 

        if (!@unlink($dir . '/' . $obj)) 
        { 
            unlinkRecursive($dir.'/'.$obj, true); 
        } 
    } 

    closedir($dh); 
    
    if ($deleteRootToo) 
    { 
        @rmdir($dir); 
    } 
    
    return true; 
}
/*
// e-SATA & external device / copy : configuration files
// /etc/sss_script/esata/
// 					esata_ccl : cancel copy
// 					esata_prog : copy progress
// 					esata_sts : copy status
*/
//$esata_ccl_file = '/etc/sss_script/esata/esata_ccl';
//$esata_prg_file = '/etc/sss_script/esata/esata_prog';
//$esata_sts_file = '/etc/sss_script/esata/esata_sts';


/*
// Copy dir in NAS
// $mode : operation mode for permission check
//		'ext' : copy with external device including usb, e-sata, ...
//				permission is always rw in external devices
*/
function nas_copy_dir($src_dir,$dst_dir,$mode){
	// 1 check cancel
	global $esata_ccl_file;
	if(!chk_ccl($esata_ccl_file)){
		$return_array = array('result' => '0'
								,'error_msg' => 'Copy was cancelled');
		return $return_array;
	}
	// 2 check destination permission
	if($mode=='ext'){
		// copy to external device
		if(!ext_chk_dir_prms($dst_dir,'w')){
			$return_array = array('result' => '0'
							,'error_msg' => 'No permission to copy');
			return $return_array;
		}
	}else{
		// copy to nas 
		if(!nas_chk_dir_prms($dst_dir,'w')){
			$return_array = array('result' => '0'
							,'error_msg' => 'No permission to copy');
			return $return_array;
		}
	}
	// 3 get total src size
	$total_size = get_size($src_dir);
	// 4 copy src to dst
	// 5 write complete
	// 6 end
}


function chk_ccl(){
	$ccl_file = '/tmp/esata/esata_job';
	if(!file_exists($ccl_file)){
		/* handle LCD message */
		//msgjob('remove','Copying Disc...');
		//msgjob('once','Cancel Disc Copy');
		return false;
	}
	return true;
}

/* Using this
// in burning_brows_remote.class.php
*/
function get_size($path)
{
  if(!is_dir($path)) return sprintf("%u",filesize($path));
  if ($handle = opendir($path)) {
    $size = 0;
    while (false !== ($file = readdir($handle))) {
      if($file!='.' && $file!='..') {
        // function filesize has been deleted
        $size += get_size($path.'/'.$file);
      }
    }
    closedir($handle);
    return $size;
  }
}
/* File copy */
function nas_filecopy($srcfile, $dstfile, $verbose, $prog_file, $total_size, $cur_size){
	//echo $total_size.':'.$cur_size;
	shell_exec("sudo echo $cur_size >> /tmp/esata/log");
	//var_dump($total_size);
	//var_dump($cur_size);
	
	
	if(file_exists($dstfile)){
		// compare modified time
		$ow = filemtime($srcfile) - filemtime($dstfile); 
		// compare file size
		$srcfile_size = floatval(sprintf("%u",filesize($srcfile)));
		$dstfile_size = floatval(sprintf("%u",filesize($dstfile)));
		$os = $srcfile_size - $dstfile_size;
	}else{
		$ow = 1;
		$os = 1;
	}
	
	if($ow > 0 && $os != 0) {
		
		// Detect file size & select to use copy or fwrite
		// Copy by spliting a file larger than 10MB
		if(filesize($srcfile)>10*1024*1024){
			if(!chk_ccl()){
				if($verbose) echo "1\n";
				return 'cancel';
			}
			
			$fp_src = fopen($srcfile,'rb');
			$fp_dst = fopen($dstfile,'wb');
			if(!$fp_dst){
				// Error
				fclose($fp_src);
				return 'errOpen';
			}
			
			
			while(!feof($fp_src)){
				if(!chk_ccl()){
					if($verbose) echo "2\n";
					shell_exec("sudo chmod 777 '$dstfile'");
					return 'cancel';
				}
				
				// Copy file
				$_tmp_res = fwrite($fp_dst, fread($fp_src,8192));
				if(!$_tmp_res){
					// Error
					fclose($fp_src);
					fclose($fp_dst);
					return 'errWrite';
				}
				
				
				// Write progress
				$cur_size += 8192;
				$_prog = $cur_size / $total_size * 100;
				
				$handle = fopen($prog_file,'w');
				fwrite($handle,intval($_prog));
				fwrite($handle,"\n");
				fclose($handle);
				
			}
			
			fclose($fp_src);
			fclose($fp_dst);
			//chmod($dstfile,0777);
			shell_exec("sudo chmod 777 '$dstfile'");
		}else{
			if(!chk_ccl()){
				if($verbose) echo "3\n";
				return 'cancel';
			}
			
			
			if(copy($srcfile, $dstfile)) {
				touch($dstfile, filemtime($srcfile)); $num++;
				
				// Write progress
				$cur_size += (float)filesize($dstfile);
				$_prog = $cur_size / $total_size * 100;
				$handle = fopen($prog_file,'w');
				
				//var_dump($total_size);
				//var_dump($cur_size);
	
				fwrite($handle,intval($_prog));
				fwrite($handle,"\n");
				fclose($handle);
				shell_exec("sudo chmod 777 '$dstfile'");
				
				if($verbose) echo "$dstfile\n";
			}else{
				return 'failCopy';
			}
			
			
			if(!chk_ccl()){
				if($verbose) echo "4\n";
				return 'cancel';
			}
		}
		
	}else {
						
		if($verbose) echo "Warning: File '$srcfile' exists already!\nSame size, same modified time\n";
		$fail++;
		$fifail = $fifail.$srcfile."|";
		
		// Write progress
		$cur_size += floatval(sprintf("%u",filesize($dstfile)));
		$_prog = $cur_size / $total_size * 100;
		$handle = fopen($prog_file,'w');
		
		fwrite($handle,intval($_prog));
		fwrite($handle,"\n");
		fclose($handle);
		
		
	}
	return (float)$cur_size;
}


/* Directory copy */
function nas_dircopy($srcdir, $dstdir, $verbose, $prog_file, $total_size, $cur_size) {
	
	//$prog_file = '/etc/sss_script/burn/odd_prog_php';
	$esata_copy_file_list = '/tmp/esata/esata_copy';
	
	if(!isset($offset)) $offset=0;
	$num = 0;
	$fail = 0;
	$sizetotal = 0;
	$fifail = '';
	if(!is_dir($dstdir)){
		$_tmp_res = mkdir($dstdir);
		if(!$_tmp_res){
			// Failure
			return 'failMkdir';
		}
		shell_exec("sudo chmod 777 '$dstdir'");
	}
	if($curdir = opendir($srcdir)) {
		if(!chk_ccl()){
			if($verbose) echo "0\n";
			return 'cancel';
		}
		while($file = readdir($curdir)) {
			
			if($file != '.' && $file != '..') {
				$srcfile = $srcdir . '/' . $file;
				$dstfile = $dstdir . '/' . $file;
				
				
				if(is_file($srcfile)) {
					$_ret = nas_filecopy($srcfile,$dstfile,$verbose,$prog_file,$total_size,$cur_size);
					if(is_string($_res)){
						if($_res === 'cancel'){
							$return_array = array('result' => '10'
								 			,'error_msg' => 'Cancelled copy.');
							//$_SESSION['buffer'] = null;
							shell_exec("sudo rm '$esata_copy_file_list'");
							return $return_array;
						}
					}
					$cur_size = floatval($_ret);
					
					/*
					if(is_file($dstfile)){
						// compare modified time
						$ow = filemtime($srcfile) - filemtime($dstfile); 
						// compare file size
						$srcfile_size = floatval(sprintf("%u",filesize($srcfile)));
						$dstfile_size = floatval(sprintf("%u",filesize($dstfile)));
						$os = $srcfile_size - $dstfile_size;
					}else{
						$ow = 1;
						$os = 1;
					}
					if($ow > 0 && $os != 0) {
						
						
						
						if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
	
						// Detect file size & select to use copy or fwrite
						// Copy by spliting a file larger than 10MB
						if(filesize($srcfile)>10*1024*1024){
							if(!chk_ccl()){
								if($verbose) echo "1\n";
								return 'cancel';
							}
							
							$fp_src = fopen($srcfile,'rb');
							$fp_dst = fopen($dstfile,'wb');
							
							while(!feof($fp_src)){
								if(!chk_ccl()){
									if($verbose) echo "2\n";
									return 'cancel';
								}
								
								// Copy file
								fwrite($fp_dst, fread($fp_src,8192));
								
								// Write progress
								$cur_size += 8192;
								$_prog = $cur_size / $total_size * 100;
								
								$handle = fopen($prog_file,'w');
								fwrite($handle,intval($_prog));
								fclose($handle);
								
							}
							
							fclose($fp_src);
							fclose($fp_dst);
							$sizetotal+= floatval(sprintf("%u",filesize($dstfile)));
							//chmod($dstfile,0777);
						}else{
							if(!chk_ccl()){
								if($verbose) echo "3\n";
								return 'cancel';
							}
							
							
							if(copy($srcfile, $dstfile)) {
								touch($dstfile, filemtime($srcfile)); $num++;
								$sizetotal += filesize($dstfile);
								
								// Write progress
								$cur_size += filesize($dstfile);
								$_prog = $cur_size / $total_size * 100;
								$handle = fopen($prog_file,'w');
								
								fwrite($handle,intval($_prog));
								fclose($handle);
								
								$sizetotal += filesize($dstfile);
							}
							
							
							if(!chk_ccl()){
								if($verbose) echo "4\n";
								return 'cancel';
							}
						}
						
	
						if($verbose) echo "OK\n";
					}else {
						
						if($verbose) echo "Warning: File '$srcfile' exists already!\nSame size, same modified time\n";
						$fail++;
						$fifail = $fifail.$srcfile."|";
						
						// Write progress
						$cur_size += floatval(sprintf("%u",filesize($dstfile)));
						$_prog = $cur_size / $total_size * 100;
						$handle = fopen($prog_file,'w');
						
						fwrite($handle,intval($_prog));
						fclose($handle);
						
						$sizetotal += floatval(sprintf("%u",filesize($dstfile)));
						
					}
					*/
				} 
				else if(is_dir($srcfile)) {
					
					$_ret = nas_dircopy($srcfile, $dstfile, $verbose, $prog_file, $total_size, $cur_size);
					if(is_string($_ret)){
						if($_ret == 'cancel'){
							if($verbose) echo "5\n$_ret";
							return 'cancel';
						}
					}
					$cur_size = floatval($_ret);
				}    
			} 
			         
		}
		closedir($curdir);
	}
	$cur_size += floatval(filesize($dstdir));
	return $cur_size;
}
?> 