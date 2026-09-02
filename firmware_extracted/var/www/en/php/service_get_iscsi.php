<?
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
//$printer 	= trim(exec('sudo grep printer /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

// NC1

$iscsi 	= trim(exec('sudo nas-service get_iscsi enabled'));
echo "$iscsi ";

$chap     = trim(exec('sudo nas-service get_iscsi chap'));
echo "$chap";

?>
