<?php
include "../inc/lcdmsg.php";

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

$dvd_mode	=$_POST["mode"];
$dvd_path	=$_POST["path"];
$dvd_name=$_POST["titlename"];

msgjob('add','Extracting DVD-Title...');
$ret = shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -o '$dvd_path' -'$dvd_mode' -n '$dvd_name'");
msgjob('remove','Extracting DVD-Title...');
if(eregi('access denied',$ret))
{
	echo 'Access Denied';
	msgjob('once','Access Denied');
	exit;
}
echo $ret;

/* All procedure can be cancelded by server timeout.
$pattern = "ODD Task Canceled";
if(ereg($pattern,$ret))
{
	$out = "DVD ripping was canceled";
	echo $out;
	msgjob('once','Cancel DVD-Title Extraction');
	exit();
}
$out = "DVD ripping was completed";
echo $out;

msgjob('once','Complete DVD-Title Extraction');

$cmd = "sudo chmod 777 -R $dvd_path";
shell_exec($cmd);*/
?>