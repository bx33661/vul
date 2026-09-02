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
$userweb	= trim(exec('sudo nas-service get_web enabled'));
$userweb_port	= trim(exec('sudo nas-service get_web port'));
$userweb_ssl	= trim(exec('sudo nas-service get_web ssl'));
$userweb_ssl_port= trim(exec('sudo nas-service get_web ssl_port'));
$sql     	= trim(exec('sudo nas-service get_sql enabled'));
$sql_pass     	= trim(exec('sudo nas-service get_sql pass'));
echo "$userweb:$userweb_port:$userweb_ssl:$userweb_ssl_port:$sql:$sql_pass";
?>
