<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
//require_once "../php/msg_illegal_access.php";
echo '-99';
die();
}
require_once 'burning_brows_remote.class.php';
ini_set('display_errors',1);
$method_name = urldecode($_GET['action']);

// Check whether device is connected or not
//$tmp = $_GET['device'];

//juny
if($method_name == "show_me_files_esata")
{
	$device = $_GET['device'];
	       
	if($device){
		//juny
		if($device != 'esata')
		{
			$device = "/mnt/device/USB";
		}
		else 
		{
			$device = "/mnt/device/eSATA";
		}

		/*
		$_ret = shell_exec("sudo mount | grep ".'eSATA');//$device);
	
		if($_ret){
			if(!eregi('eSATA',$_ret)){
				echo 'Not connected';
				return;
			}
		}else{
			echo 'Not connected';
			return;
		}
		*/
	}	
}


if(method_exists('Remote_func',$method_name)){
	$remote_func = new Remote_func();
	$result = '';
	eval('$result=$remote_func->'.$method_name.'();');
	echo $result;
}else{
	echo 'not found';
	exit;
}
?>
