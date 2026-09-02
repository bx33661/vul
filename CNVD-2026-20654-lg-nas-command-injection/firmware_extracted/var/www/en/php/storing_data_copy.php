<?php
include "../inc/lcdmsg.php";

//=======================================================//
// LGE
// Park94
// 10/24/2008
// * Considering Timeout of 300 sec
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
if(!shell_exec("sudo mount|grep /mnt/cdrom"))
{
	shell_exec('sudo mount /mnt/cdrom');
}

$path =$_POST['path'];
//echo $path;
msgjob('add','Copying Disc...');
$ret=shell_exec("sudo oddacsrt -u web -a store -p cp -d -r /mnt/cdrom/. '$path'");
msgjob('remove','Copying Disc...');
if(eregi('access denied',$ret))
{
	echo "ERROR:ACCESS DENIED\n";
	exit;
}else if(eregi('odd task canceled',$ret))
{
	echo "COMPLETE:CANCELED\n";
	exit;
}else if(eregi('return value : 0',$ret))
{
	echo "COMPLETE:SUCCESS\n";
	exit;
}else
{
	echo "ERROR:$ret\n";
	exit;
}
/* All procedure can be cancelded by server timeout.
$pattern = "ODD Task Canceled";
if(ereg($pattern,$ret))
{
	$out = "Cancel Disc Copy";
	echo $out;
	msgjob('once','Cancel Disc Copy');
	//shell_exec("sudo rm -rf '$path'");
	exit();
}
shell_exec("sudo chmod 777 -R '$path'");
$out = "Complete Disc Copy";
echo $out;

msgjob('once','Complete Disc Copy');
*/
?>