<?php
//=======================================================//
// Session Check
//=======================================================//
/*
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
	echo json_encode(array('result' => -99 , 'message' => 'session out'));
	return;
}
//juny
session_write_close();
$_folder_str = $_POST['folders'];

$_folder_list = explode(':',$_folder_str);

$_def_path = '/mnt/disk';

$_ret_arr = array();

foreach($_folder_list as $_val){
	if(!trim($_val)){
		continue;
	}
	$_tmp_path = $_def_path.trim($_val);
	
	$_tmp_info = shell_exec("sudo du -s '$_tmp_path'");
	
	preg_match('/^\d+\b/',$_tmp_info,$_matches);
	
	preg_match_all("/(.*\/)+(.+)$/",$_val,$_tmp);
	
	if(!$_tmp_info){		
		$_ret_arr[$_tmp[2][0]] = array('path' => $_tmp[1][0] , 'size' => -1);
	}else{
		$_ret_arr[$_tmp[2][0]] = array('path' => $_tmp[1][0] , 'size' => $_matches[0]*1024);
	}
}



echo json_encode($_ret_arr);

return;
*/
require_once 'burning_brows_common_func.php';
require_once 'nas_comm.php';
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
	echo '-99';
	die();
}


$_folder_str = $_POST['folders'];
exec("sudo echo folder : $_folder_str >> /home/tmp.txt");
$_folder_list = explode(':',$_folder_str);

//$_def_path = '/mnt/fs';
$_ret_arr = array();



foreach($_folder_list as $_val){
	if(!trim($_val)){		
		continue;	
	}


	$current_path = get_selectedDirectory($_val);

	if($_SESSION['current_dir'] != '/')
	{
		exec("sudo echo BEFORE: name : $_val >> /home/tmp.txt");	
		
		$_val = urldecode($_val); 
		$_tmp_path = $current_path.trim($_val);	
		exec("sudo echo AFTER: _tmp_path : $_tmp_path >> /home/tmp.txt");
		
		$_tmp_path = encode_filename($_tmp_path);
		exec("sudo echo encoded_tmp_path : $_tmp_path >> /home/tmp.txt");	
	}
	else
	{
		$_tmp_path = $current_path;
	}

	$isdirectory = array();
	$cmd = $isdirectory = shell_exec("sudo ls -del $_tmp_path");

	//Check whether 'directory' or 'file'
	//if(!is_dir($_tmp_path)) /
	if(!($isdirectory[0] =='d'))
	{
		//skip space charater : ' ' ,'\t' ...
		//$cmd = shell_exec("sudo ls -del $_tmp_path");
		$_tmp = preg_split("/[\s,]+/", $cmd);

		if($_tmp[4] > 1024)
     			$_tmp_info = round(($_tmp[4] / 1024),1);
		else if($_tmp[4] >0)
			$_tmp_info = 4; //4Kbyte
		else
			$_tmp_info = 0; 

	}else
		$_tmp_info = shell_exec('sudo du -s '.$_tmp_path);

	
	preg_match('/^\d+\b/',$_tmp_info,$_matches);				
	//if(!$_tmp_info){				
	//	$_ret_arr[func_urlencode($_val)] = array( 'path' => $current_path , 'size' => -1);	
	//}else{		
		$_ret_arr[func_urlencode($_val)] = array( 'path' => $current_path , 'size' => $_matches[0]*1024);	
	//}
}

echo json_encode($_ret_arr);
return;


function get_selectedDirectory($folder) {
	$prefix_path=$_SESSION['user_directory'];
	$post_path=$_SESSION['current_dir'];

	exec("sudo echo user_directory : $prefix_path >> /home/tmp.txt");
	exec("sudo echo current_dir : $post_path >> /home/tmp.txt");

	if ( $_SESSION['current_dir'] == "/" ) 
	{
		//$current_path = $prefix_path.$post_path;
		//exec("sudo echo post_path : $post_path >> /home/tmp.txt");

		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select path from folder_info where folder='$folder'");
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
		//$_SESSION['prefix_dir'] = $prefix_path;

		exec("sudo echo prefix_dir : $prefix_path >> /home/tmp.txt");	
		
		$position = strpos( $_post_path , "/" );
		$post_path = substr($_post_path, $position+1);
		$position = strpos( $post_path , "/" );
		$post_path = substr($post_path, $position);	
		
		$current_path = $prefix_path;
		
	}
	else
	{
		$prefix_path=$_SESSION['prefix_dir'];		
		//$post_path='$folder;
		$current_path = $prefix_path;
		exec("sudo echo important prefix_path : $prefix_path >> /home/tmp.txt");		
	}
     	
	exec("sudo echo path : $current_path >> /home/tmp.txt");		
	return $current_path;	
}
session_write_close();


?> 