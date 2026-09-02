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

// NS1
/*
$fd		= fopen("/etc/time.conf","r");
while(!feof($fd)) {
	$buffer	= fgets($fd,4096);
	$reg		= explode("=",$buffer);
	$ntp[]	= trim($reg[1]);
}
*/

// NC1
$ntp[0] = trim(exec('sudo nas-system get ntp'));
$ntp[1] = trim(exec('sudo nas-system get ntpserver'));
$ntp[2] = trim(exec('sudo nas-system get default_ntpserver'));
$ntp[3] = trim(exec('sudo nas-system get ntp_update'));

echo "$ntp[0]\n";	// enable
echo "$ntp[1]\n";	// NTP server URL/none
echo "$ntp[3]\n";	// Frequency [1d/1w]
echo "$ntp[2]\n";

?>
