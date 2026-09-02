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


	/*
	this.sys_version = sys_ver;
	this.sys_date = sys_date;
	this.odd_ver = odd_ver;
	this.odd_date = odd_date;
	*/
//$sys_updating = exec ('sudo ps -w |grep "SSS_SystemUpgrade" | grep -v grep');
//$odd_updating = exec ('sudo ps -w |grep "SSS_OddUpgrade" | grep -v grep');
$sys_version = trim(exec('sudo nas-firmware get version'));
$sys_date = trim(exec ('sudo nas-firmware get date'));
$odd_ver = trim(exec ('sudo nas-firmware get odd-version'));
$odd_date = trim(exec ('sudo nas-firmware get odd-date'));

echo "$sys_version:$sys_date:$odd_ver:$odd_date:\n";

?>

