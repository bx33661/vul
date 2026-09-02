<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// Media type : dvd
//=======================================//
$ret = shell_exec('sudo oddmngst -m chk');
//echo preg_match("/(ODD Status : Idle)/",$ret);
if(preg_match("/(ODD Status : Idle)/",$ret))
{
	$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/mopilt -i");
	if(preg_match("/Disc Type : DVD/",$ret))
	{
		if(preg_match("/Protected Disc : Yes/",$ret))
		{
			echo "Protected Disc";
			exit;
		}
		$ret=shell_exec("sudo dvdbackup -i /dev/sr0 -I");
		if(preg_match("/DVD-Video information/",$ret))
		{
			echo "DVD OK";
			exit;
		}
	}
}else if(preg_match("/(ODD Status : Busy)/",$ret))
{
	echo "ODD BUSY";
	exit;
}

echo "NG";
?>