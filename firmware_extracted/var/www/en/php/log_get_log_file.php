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


//include "../../config.php";

$log_mode	= $_POST["log_mode"];
switch($log_mode)
{
	case "system_log":
	$file = "/var/log/syslog";
	$filename = "syslog";
	break;
	
	case "samba_log":
	$file = "/var/log/samba.log";
	$filename = "samba.log";
	break;
	
	case "ftp_log":
	$file = "/var/log/proftpd/xferlog";
	$filename = "xferlog";
	break;
	
	case "diag_log":
	$file = "/var/log/diag.log";
	$filename = "diag.log";
	break;
	
	default:
	echo "Error";
	break;
}
//echo $file;
//$dir 		= "/var/log/";
//echo "1 ".$filename."<br>";

//if ($filename == 'astar.log.tar.bz2') exec("sudo /etc/sss_script/diag/log.sh");

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment;; filename=$filename");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".(string)(filesize($file)));
header("Cache-Control: cache, must-revalidate");
header("Pragma: no-cache");
header("Expires:0");

$fp=fopen($file,"rb");
if(!fpassthru($fp)){
	fclose($fp);
}
?>
 
