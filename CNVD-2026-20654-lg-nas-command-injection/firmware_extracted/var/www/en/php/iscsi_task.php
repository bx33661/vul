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


$iscsi_mode = $_POST["ISCSI_SET"];

if ( $iscsi_mode == "ON" ) {
    shell_exec("sudo /etc/init.d/iscsi-target start");
    echo "OK:ISCSI_ON";
}
else {
    shell_exec("sudo /etc/init.d/iscsi-target stop");
    echo "OK:ISCSI_OFF";
}

?>



 