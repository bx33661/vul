<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// ODD status check
//	:	Busy//Idle
//=======================================//
$ret = shell_exec('sudo oddmngst -m chk');
//echo preg_match("/(ODD Status : Idle)/",$ret);
if(preg_match("/(ODD Status : Idle)/",$ret))
{
	$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/mopilt -i");
	if(preg_match("/Media Type : cda/",$ret))
	{
		echo "CDA OK";
		exit;
	}
}else if(preg_match("/(ODD Status : Busy)/",$ret))
{
	echo "ODD BUSY";
	exit;
}
echo "NG";
?>