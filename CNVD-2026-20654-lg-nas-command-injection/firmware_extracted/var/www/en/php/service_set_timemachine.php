<?php
//=======================================================//
// Session check
//=======================================================//
require_once "../session/session_manage.php";

if ( sm_session_check_on_popup() == FALSE )
{
	//include "../php/msg_illegal_access.php";
	echo '-99';
	die();
}

$timemachine = $_POST['timemachinOnOff'];
//$host_new = $_POST['host_name'];
//$mac_addr = $_POST['mac_addr'];
$tm_basename_list = $_POST['afpDirList'];

if($timemachine == 'on')
{
	$res = exec('sudo nas-service enable timemachine on');
	$output = array();
	//$res = exec("sudo nas-service set_timemachine $host_new $mac_addr", $output, $result);
	$res = exec("sudo nas-service set_timemachine $tm_basename_list", $output, $result);
	if ($result != '0') {
		$res = exec('sudo nas-service enable timemachine off');
		echo "ng:tm_err";
		die();
	}

	$afp_enabled = trim(exec('sudo nas-service get_afp enabled'));
	if($afp_enabled == 'off')
	{	
		exec("sudo nas-service enable afp on");
		$res = exec('sudo nas-service control afp restart');
	}
	$res = exec('sudo nas-service control timemachine start');
}
else
{
	$res = exec('sudo nas-service enable timemachine off');
	$res = exec('sudo nas-service control timemachine stop');
}

echo "ok:tm_ok";  //tm_err

?>
