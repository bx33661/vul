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


$tz_list 	= DateTimeZone::listIdentifiers();
for($i; $i<count($tz_list); $i++)
{
	echo "$tz_list[$i]:";
}
?>
