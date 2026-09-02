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

$WEB = $_POST['rdoWWW'];
$WEB_PORT = $_POST['txtWWW_PORT'];
$WEB_SSL = $_POST['rdoSSL'];
$WEB_SSL_PORT = $_POST['txtSSL_PORT'];
$MYSQL = $_POST['rdoMYSQL'];
$MYSQL_PASS = $_POST['txtMYSQL_PASS'];

$userweb	= trim(exec('sudo nas-service get_web enabled'));
$userweb_port	= trim(exec('sudo nas-service get_web port'));
$userweb_ssl	= trim(exec('sudo nas-service get_web ssl'));
$userweb_ssl_port= trim(exec('sudo nas-service get_web ssl_port'));
$sql     	= trim(exec('sudo nas-service get_sql enabled'));
$sql_pass     	= trim(exec('sudo nas-service get_sql pass'));


exec("sudo nas-service set_web enabled $WEB $WEB_PORT $WEB_SSL $WEB_SSL_PORT");
exec("sudo nas-service set_sql enabled $MYSQL $MYSQL_PASS");

echo "ok"
?>
