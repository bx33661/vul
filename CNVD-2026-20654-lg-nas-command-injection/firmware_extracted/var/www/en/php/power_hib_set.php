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


// Hibernation enable/disable
$hib_enable = $_POST["rdoHibEna"];
$hib_minutes = $_POST["sltHibWait"];

echo "1 : $hib_enable\n";
echo "2 : $hib_minutes\n";

// NS1
//exec("sudo sh -c '. /etc/sss_script/event/lib_sss && HIB_ServiceConfig $hib_enable $hib_minutes'");

// NC1
exec("sudo nas-system hibernation $hib_enable $hib_minutes");

?>
