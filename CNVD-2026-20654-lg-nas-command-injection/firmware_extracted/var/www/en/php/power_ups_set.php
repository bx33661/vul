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


// UPS enable/disable
$ups_enable = $_POST["rdoUpsEna"];
$shutdown_time = $_POST["rdoUpsShutdown"];
$ups_poweroff = $_POST["rdoUpsPower"];

echo "1 : $ups_enable\n";
echo "2 : $shutdown_time\n";
echo "3 : $ups_poweroff\n";

// NC1 	 
exec("sudo nas-system ups $ups_enable $shutdown_time $ups_poweroff");

// NS1
//exec("sudo sh -c '. /etc/sss_script/event/lib_sss && UPS_ServiceConfig $ups_enable $shutdown_time $ups_poweroff'");

?>
