<?php
include "../inc/lcdmsg.php";

$root_path="/mnt/fs";
$file=$_POST['filename'];
$file=$root_path.$file;
if(shell_exec("mount|grep /dev/sr0"))
{
	shell_exec("sudo umount /dev/sr0");
}
msgjob('add','Burning Image...');
$cmd="sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt '$file' /dev/sg4";
$ret=shell_exec($cmd);
msgjob('remove','Burning Image...');
$pattern = "ODD Task Canceled";
if(ereg($pattern,$ret))
{
	$out = "Burning was canceled";
	msgjob('once','Cancel Image Burn');
}else
{
	$out = "Burning was completed";
	msgjob('once','Complete Image Burn');
}
echo $out;
$cmd="sudo oddmngst -m tray";
shell_exec($cmd);
?> 