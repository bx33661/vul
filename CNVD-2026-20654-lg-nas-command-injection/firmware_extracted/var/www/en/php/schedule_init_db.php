<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
include "../php/msg_illegal_access.php";
die();
}
session_write_close();


include "../inc/lcdmsg.php";

$mode = $_POST['mode'];
//echo $mode;
$db_files = array("/etc/cms/cmsbackup.db","/etc/cms/~discinfo.xml");
shell_exec("sudo rm $db_files[0] $db_files[1]");
switch($mode)
{
case 'init_file':
	echo 'COMPLETE:INIT FILE\n';
	break;
case 'init_all':
	msgjob('add','Initializing media...');
	$cmd = "sudo oddacsrt -u web -a schedule -p /usr/local/bin/mosilt -f";
	$ret = shell_exec($cmd);
	msgjob('remove','Initializing media...');
	if(eregi('not formattable media',$ret))
	{
		msgjob('once','Error Initialize cannot format disc');
		echo 'ERROR:NOT FORMATTABLE MEDIA\n';
	}else
	{
		msgjob('once','Complete Initialize');
		echo 'COMPLETE:INIT ALL\n';
	}
	break;
default:
	break;
}
?>