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

// Check if tray is ready to restore date
// Ready : Closed
// Not ready : Closing/Opened/Opening
// Check tray status
//$bd_status_path= "/var/www/run/status11";
//$bd_status = exec("sudo cat $bd_status_path");//| tail -1");

$disc_type = shell_exec('sudo /usr/bin/cdrecord -minfo | grep \'Mounted media type\' | cut -d: -f2 | awk \'{print $1}\'');

if(eregi('CD-RW',$disc_type))
{
	shell_exec("sudo /usr/bin/cdrecord blank=fast");
	echo "OK:COMPLETE ERASE DISC\n";
}
else if(eregi('DVD-RW',$disc_type))
{
	shell_exec("sudo /usr/bin/dvd+rw-format -force /dev/sr0");
	echo "OK:COMPLETE ERASE DISC\n";
}
else if(eregi('DVD-RAM',$disc_type))
{
	shell_exec("sudo /usr/bin/cdrecord -format");
	echo "OK:COMPLETE ERASE DISC\n";
}
else if(eregi('DVD+RW',$disc_type))
{
	shell_exec("sudo /usr/bin/cdrecord -format -force");
	echo "OK:COMPLETE ERASE DISC\n";
}else
{
	echo "ERROR:NOT ERASABLE DISC\n";
}

	
?>
