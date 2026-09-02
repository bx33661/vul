<?
//=======================================//
// Blank disc check
//=======================================//
$ret = shell_exec('sudo oddmngst -m chk');
if(preg_match("/ODD Status : Idle/",$ret))
{
	$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/mopilt -i");
	//echo $ret;
	$pattern="/[+-]R[EWA]M?/";
	if(preg_match("/Disc Status : Blank/",$ret) || preg_match($pattern,$ret))
	{
		echo "OK";
		exit;
	}else
	{
		echo "NG";
		exit;
	}
}else
{
	echo "ODD BUSY";
	exit;
}

?>