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
// Date, time, & timezone
//==================================================//
// Date
$date 		= exec("sudo date -I");
$date_entry	= explode("-",$date);
$year		= $date_entry[0];
$month		= $date_entry[1];
$day			= $date_entry[2];
echo "$year:$month:$day:\n";

// Time
$date 		= exec("sudo date -R");
$time_entry	= explode(" ",$date);
echo "$time_entry[4]:\n";

// Timezone
// NS1
//$tz_temp 	= exec("ls -l /etc/ | grep localtime");
//ereg("/usr/share/zoneinfo/([A-Za-z0-9\/]+)",$tz_temp,$reg);
//$tz=trim($reg[1]);

// NC1
$tz=trim(exec('sudo nas-system get timezone'));
echo "$tz:\n";
?>
