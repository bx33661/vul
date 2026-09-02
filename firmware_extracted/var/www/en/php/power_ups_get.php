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
$ups_enable = trim(exec ('sudo nas-system get ups'));
$ups_shutdown_times = trim(exec ('sudo nas-system get ups_shutdown_time'));
$ups_poweroff = trim(exec ('sudo nas-system get ups_poweroff'));

// NS1
//$ups_enable = trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "ups="|cut -d "=" -f 2'));
//$ups_shutdown_times = trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "ups_shutdown_time="|cut -d "=" -f 2'));
//$ups_poweroff = trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "ups_poweroff="|cut -d "=" -f 2'));

echo "$ups_enable:$ups_shutdown_times:$ups_poweroff:\n";

?>
