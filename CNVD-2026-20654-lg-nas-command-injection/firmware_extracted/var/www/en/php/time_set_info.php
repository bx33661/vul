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


// TIME SETTING
$year	= $_POST["txtYear"];
$month	= $_POST["txtMonth"];
$day		= $_POST["txtDay"];
$hour	= $_POST["txtHour"];
$min		= $_POST["txtMin"];
$sec		= $_POST["txtSec"];
$timezone	= $_POST["txtTimeZone"];
//debugging//
echo "-Date: $year-$month-$day\n";
echo "-Time: $hour:$min:$sec\n";
echo "-Timezone: $timezone\n";

if($month<10 && strlen($month)< 2 ) $month = "0".$month;
if($day<10 && strlen($day)<2 ) $day = "0".$day;
if($hour<10 && strlen($hour) < 2) $hour = "0".$hour;
if($min<10 && strlen($min) < 2) $min = "0".$min;
if($sec<10 && strlen ($sec) < 2 ) $sec = "0".$sec;
//$time = $month.$day.$hour.$min.$year.".".$sec;

//date --set="01/29/09 15:42:00"
$time = $month."/".$day."/".$year." ".$hour.":".$min.":".$sec;

// NS1
//exec("sudo date -s '$time'");
// Hardware Time Setting
//exec("sudo hwclock --systohc --utc");
//exec("sudo hwclock -w");
//exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetTime'");

#exec("sudo rm /etc/localtime");
#exec("sudo ln -s /usr/share/zoneinfo/$timezone /etc/localtime");

// NC1
exec("sudo nas-system time $time");
exec("sudo nas-system timezone $timezone");
putenv("TZ=".$timezone);

echo ":Complete";
?>
