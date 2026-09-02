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

//==================================================//
// Set Volume
//==================================================//

$mode = $_POST["mode"];	
$target = $_POST["target"];
$raid_type = $_POST["raid_type"];

if($raid_type == 'raid1')
	$type = 'RAID1';
else
	$type = 0;	

if( $mode  == "add" ){
	$ret = exec("sudo nas-storage hddsetup addhdd ext3 0 $type");

	//background process
	$ret = shell_exec("sudo nohup nas-storage grow_raid > /dev/null & ");
	
}
else if( $mode == "add_no_format"){
	$ret = exec("sudo nas-storage hddsetup addhdd ext3 OFF 0");
	//background process
	$ret = shell_exec("sudo nohup nas-storage grow_raid > /dev/null & ");
	
}
else if( $mode  == "add_pre_check" ){
	
		$ret = exec("sudo nas-storage check_md1 OFF");
		echo $ret;
		return;
}else if( $mode  == "remove" ){
	if($target == "B1")
		$ret = exec("sudo nas-storage hddsetup removehdd ext3 HDD1 $type");
	else
		$ret = exec("sudo nas-storage hddsetup removehdd ext3 0 $type");	
}


//echo $ret;
echo 'ok';
?>
