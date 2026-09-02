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

// Schedule power on/off
$sche_enable = $_POST["rdoScheEna"];

$sche_StartTime = $_POST["StartTime"];
$sche_EndTime = $_POST["EndTime"];


// NC1
exec("sudo nas-system schedule_on_off $sche_enable $sche_StartTime $sche_EndTime");


echo 'OK';
?>

