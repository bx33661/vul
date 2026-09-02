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
//$afp		= trim(exec('sudo grep appletalk /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$ftp		= trim(exec('sudo grep ftp= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$ftp_port 	= trim(exec('sudo grep ftp_port= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$dyndns		= trim(exec('sudo grep dyndns /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$daap 		= trim(exec('sudo grep daap= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$daap_update 	= trim(exec('sudo grep daap_update /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

// NC1
$afp		= trim(exec('sudo nas-service get_afp enabled'));
$ftp		= trim(exec('sudo nas-service get_ftp enabled'));
$ftp_port 	= trim(exec('sudo nas-service get_ftp port'));

echo "$ftp:$afp:$ftp_port";

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
