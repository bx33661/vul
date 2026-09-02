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


// shutdown or reboot
$shutdown_mode = $_POST["rdoShutdown"];

echo "1 : $shutdown_mode\n";
if($shutdown_mode == "shutdown") {
	// NS1
	//exec('sudo /etc/sss_script/event/key_power.sh');
	// NC1
	exec("sudo nas-system shutdown");
}
else if($shutdown_mode == "restart") {
	// NS1
	//exec('sudo /etc/sss_script/event/key_power.sh reboot');
	// NC1
	exec("sudo nas-system restart");
}
?>
