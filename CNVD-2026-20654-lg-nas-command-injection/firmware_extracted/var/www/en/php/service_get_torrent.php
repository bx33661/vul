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
$torrent 	= trim(exec('sudo nas-service get_torrent enabled'));

//$dyndns		= trim(exec('sudo grep dyndns /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$daap 		= trim(exec('sudo grep daap= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$daap_update 	= trim(exec('sudo grep daap_update /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

echo "$torrent";

/*
if($dyndns=='on') {
	$myip		= trim(exec('sudo wget -q -O - http://checkip.dyndns.org | cut -d ":" -f 2 | cut -d "<" -f 1'));
	$mydomain	= trim(exec('sudo grep alias /etc/inadyn.conf |cut -d " " -f 2'));
	$confirm_ip	= trim(exec("sudo nslookup $mydomain | tail -1 | cut -d \":\" -f 2"));

	if ($myip==$confirm_ip && $confirm_ip != '' ) $status = "Dyndns is working My IP address is : ".$confirm_ip;
		else $status="Dyndns is not working correctly";
			} else $status="Dyndns is not enabled";
*/
?>
