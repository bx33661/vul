<?php
include "../inc/lcdmsg.php";
//=======================================================//
// ODD CHECK
//=======================================================//
$ret = shell_exec('sudo oddmngst -m chk');
if(eregi('tray status : opened',$ret))
{
	echo "ERROR:TRAY OPENED\n";
	exit;
}else if(eregi('odd status : busy',$ret))
{
	echo "ERROR:BD IS BUSY\n";
	exit;
}
$ret = shell_exec('sudo oddacsrt -u web -a store -p /usr/local/bin/mopilt -i');
if(eregi('no disc in drive',$ret))
{
	echo "ERROR:NO DISC\n";
	exit;
}
//=======================================================//
// INPUT FROM WEB
//=======================================================//
$cd_mode	=$_POST["mode"];
$bit		=$_POST["bit"];
$rate	=$_POST["rate"];
$path	=$_POST["path"];
$file_name=$_POST["filename"];
//echo "mode : $cd_mode, bit : $bit, rate : $rate, path : $path, file : $file_name";

msgjob('add','Extracting Audio-CD...');
//=======================================================//
// RIP AUDIO CD (CDA)
//=======================================================//
$file_name=$path."/".$file_name;
//$cmd = "sudo oddacsrt -u web -a rip -p /usr/local/bin/cdda2wav -D /dev/sr0 -H -B -silent-scsi -$cd_mode -b $bit -r $rate '$file_name'";
$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/cdda2wav -D /dev/sr0 -H -B -silent-scsi -$cd_mode -b $bit -r $rate '$file_name'");
msgjob('remove','Extracting Audio-CD...');
//echo $ret;
$pattern = "ODD Task Canceled";
if(ereg($pattern,$ret))
{
	$out = "Audio ripping was canceled";
	echo $out;
	msgjob('once','Cancel Audio-CD Extraction');
	exit();
}else if(eregi('access denied',$ret))
{
	echo 'Access Denied';
	exit;
}
$out = "Audio ripping was completed";
echo $out;
msgjob('once','Complete Audio-CD Extraction');
?>