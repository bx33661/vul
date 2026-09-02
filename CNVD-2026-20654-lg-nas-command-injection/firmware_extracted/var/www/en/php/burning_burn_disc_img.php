<?php
include "../inc/lcdmsg.php";

	//retunr "test";
	$img_file ='/mnt/fs/Vol1/.burn.raw';
	$list_file = '/mnt/fs/Vol1/.burn_file.lst';
	$ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt '$img_file' /dev/sg4");
	if(eregi('access denied',$ret))
	{
		msgjob('once','Denied Disc Burn');
		echo "NG:ACCESS DENIED\n";
		
	}else if(eregi('odd task canceled',$ret))
	{
		msgjob('once','Cancel Disc Burn');
		echo "NG:CANCELED\n";
	}else if(eregi('return value : 0',$ret))
	{
		msgjob('once','Complete Disc Burn');
		echo "OK:COMPLETE\n";
		// Success, continue
	}else
	{
		msgjob('once','Abnormal End Disc Burn');
		echo "WARNING:NO RETURN VALUE\n";
	}
	
	shell_exec("sudo rm -rf '$list_file' '$img_file'");
	
	shell_exec("sudo /usr/local/bin/oddmngst -m tray");
	
	//echo "OK:COMPLETE\n";

?>