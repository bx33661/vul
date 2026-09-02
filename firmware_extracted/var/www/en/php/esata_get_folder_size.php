<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
	echo json_encode(array('result' => -99 , 'message' => 'session out'));
	return;
}
session_write_close();



$_folder_str = $_POST['folders'];
$_device = @$_POST['device'];
$_folder_list = explode(':',$_folder_str);


exec("sudo echo 'juny : 1' >> /home/phplog.txt");

//juny
if($_device == 'esata')
{
	$_device_tmp ='mnt/device/eSATA';
	//$_device = 'eSATA';
	exec("sudo echo 'juny : 2-esata' >> /home/phplog.txt");
}
else
{
	$_device_tmp ='mnt/device/USB';
	//$_device = 'USB';
	exec("sudo echo 'juny : 2-usb' >> /home/phplog.txt");
}	
exec("sudo mount | grep '$_device_tmp'",$_matches);
if(!$_matches){
	echo json_encode(array('result' => -1 , 'message' => 'disconnected'));
	return;
}
exec("sudo echo 'juny : 3' >> /home/phplog.txt");

$_dev_list = array();
foreach($_matches as $_val){
	$_res = preg_match_all("/^\/dev\/(.+)\s+on\s+\/mnt\/(.+)\s+type/",$_val,$_exploded);
	$_node = $_exploded[1][0];
	$_path = $_exploded[2][0];
	$_res = preg_match_all("/^Removable_(.+)_Vol\d/",trim($_path),$_exploded);
	$_dev = $_exploded[1][0];
	
	
	//if($_device == $_dev){
		$_selected_dev = array('node' => $_node , 'path' => $_path , 'device' => $_dev);
	//}
}



exec("sudo echo 'selective path = $_selected_dev' >> /home/phplog.txt");
exec("sudo mount | grep '$_device'",$_matches);
if(!$_matches){
	echo json_encode(array('result' => -1 , 'message' => 'disconnected'));
	return;
}

exec("sudo echo 'path = bypass' >> /home/phplog.txt");
$_def_path = '/mnt/'.$_selected_dev['path'];
exec("sudo echo 'target path = $_def_path' >> /home/phplog.txt");

$_ret_arr = array();

foreach($_folder_list as $_val){
	if(!trim($_val)){
		continue;
	}
	$_tmp_path = $_def_path.trim($_val);
	
	$_tmp_info = shell_exec("sudo du -s '$_tmp_path'");
		
	preg_match('/^\d+\b/',$_tmp_info,$_matches);
	
	preg_match_all("/(.*\/)+(.+)$/",$_val,$_tmp);
	
	if(eregi('no such file or directory',$_tmp_info)){
		$_ret_arr[$_tmp[2][0]] = array('path' => $_tmp[1][0] , 'size' => -1);
	}else{
		$_ret_arr[$_tmp[2][0]] = array('path' => $_tmp[1][0] , 'size' => $_matches[0]*1024);
	
	}
}



echo json_encode($_ret_arr);

return;

?> 