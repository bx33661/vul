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
//$daap 	= trim(exec('sudo grep daap= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$daap_update 	= trim(exec('sudo grep daap_update /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

// NS1
$daap 		= trim(exec('sudo nas-service get_itunes enabled'));
$rescan 	= trim(exec('sudo nas-service get_itunes rescan'));
if ($rescan == '0') {
	$daap_update = 'force';
} else {
	$daap_update = '5min';
}

echo "$daap:$daap_update";

?>
