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

// NS1
//$hibernation_enable = trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "hibernation="|cut -d "=" -f 2'));
//$hibernation_minutes = trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "hibernation_minutes="|cut -d "=" -f 2'));

// NC1
$hibernation_enable = trim(exec('sudo nas-system get hibernation'));
$hibernation_minutes = trim(exec('sudo nas-system get hibernation_time'));

echo "$hibernation_enable:$hibernation_minutes:\n";

?>
