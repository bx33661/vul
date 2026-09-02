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


$ntp			= $_POST["rdoNTP"];
$ntp_server	= $_POST["txtNTPServer"];
$ntp_default	= $_POST["chkNTPDefaultServer"];
$ntp_refresh	= $_POST["txtNTPRefresh"];

$network_status = trim(exec("sudo dig +time=1 +tries=1 ns.lgnas.com | grep timed"));
//debugging//
echo "-NTP: $ntp";
//echo "-NTP server: $ntp_server\n";
//echo "-Check default server: $ntp_default\n";
//echo "-NTP refresh: $ntp_refresh\n";

// NS1
/*
if($ntp_default) {
	$default_ntp = "time.bora.net";
}

if ($ntp =="on" ){
	if($ntp_default == "1") 	
		exec("sudo /etc/sss_script/network/ntpcfg $ntp none $ntp_refresh");
	else{
		if ( $ntp_server=="") $ntp_server="none";
		exec("sudo /etc/sss_script/network/ntpcfg $ntp $ntp_server $ntp_refresh");
	}
	if($network_status == ''){
		exec("sudo /etc/cron/cron.d/ntptime");
	}
}else {
	exec("sudo /etc/sss_script/network/ntpcfg off none $ntp_refresh");
}
exec("sudo hwclock -w");
exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetTime'");
*/

// NC1
if ($ntp == "on") {
	if ($ntp_default == "1" || $ntp_server == '') {
		$ntp_server = "none";
	}
	exec("sudo nas-system ntp on $ntp_server $ntp_refresh");
} else {
	exec("sudo nas-system ntp off none $ntp_refresh");
}

//echo "\nNTP Setup changed!";
?>
 
