<?
//=======================================//
// Data disc check
//=======================================//
$ret = shell_exec('sudo oddmngst -m chk');
if(preg_match("/ODD Status : Idle/",$ret))
{
	//echo "idle\n";
	$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/mopilt -i");
	//echo "$ret\n";
	if(preg_match("/No Disc in Drive/",$ret)||preg_match("/Media Type : cd[ax]/",$ret)||preg_match("/Disc Status : Blank/",$ret))
	{
		//echo "no disc/cda/cdx/blank\n";
		echo "NG";
		exit;
	}else if(preg_match("/Disc Type : DVD/",$ret))
	{
		//echo "dvd\n";
		$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -I");
		if(preg_match("/DVD-Video information/",$ret))
		{
			//echo "dvd movie\n";
			echo "NG";
			exit;
		}
	}
}else
{
	echo "ODD BUSY";
	exit;
}
echo "DATA DISC";
/*
if(preg_match("/(ODD Status : Idle)/",$ret))
{
	$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/mopilt -i");
	echo $ret;
	$pattern="/[(^No\sDisc\sin\sDrive)+(\bcd[ax]+\b)+(\bBlank\b)]+/";
	//$pattern="/(^No\sDisc\sin\sDrive)+/";

	echo preg_match($pattern,$ret);
	if(preg_match($pattern,$ret))
	{
		echo "NG";
		exit;
	}else if(preg_match("/(\bDVD)+/",$ret))
	{
		$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -I");
		echo $ret;
		if(!preg_match("/DVD-Video information/",$ret))
		{
			echo "DATA DISC";
			exit;
		}
	}
}else if(preg_match("/(ODD Status : Busy)/",$ret))
{
	echo "ODD BUSY";
	exit;
}*/
?>