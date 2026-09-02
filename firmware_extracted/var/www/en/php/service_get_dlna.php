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

// NC1

$dlna_enabled = trim(exec('sudo nas-service get_dlna enabled'));
$dlna_path = trim(exec('sudo nas-service get_dlna user_path'));
if($dlna_path == '')
	$dlna_path = trim(exec('sudo nas-service get_dlna default_path'));

$tmp = explode("/mnt/disk/",$dlna_path);
$short_path = explode("/",$tmp[1],2);


echo $dlna_enabled.":"."/".$short_path[1];


?>
