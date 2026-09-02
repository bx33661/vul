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

// NC1
$schedule_enabled = trim(exec('sudo nas-system get schedule_power'));
$schedule_Stime = trim(exec('sudo nas-system get schedule_power_Stime'));
$schedule_Etime = trim(exec('sudo nas-system get schedule_power_Etime'));

//test
//$schedule_enabled = 'on';
//$schedule_time = "22:30 ~ 04:30";


echo "$schedule_enabled=>$schedule_Stime ~ $schedule_Etime\n";

?>
