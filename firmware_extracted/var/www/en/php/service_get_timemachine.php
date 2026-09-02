<?php
//=======================================================//
// Session check
//=======================================================//
require_once "../session/session_manage.php";

if ( sm_session_check_on_popup() == FALSE )
{
	//include "../php/msg_illegal_access.php";
	echo '-99';
	die();
}
$res			= trim(exec('sudo nas-service get_timemachine enabled'));
$afp_share_list = trim(exec('sudo nas-service get_timemachine afplist'));
$avahi_conf		= trim(exec('sudo nas-service get_timemachine avahiconf'));

echo "ok=>$res=>$afp_share_list=>$avahi_conf";  //tm_err
?>
