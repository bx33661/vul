<?
//=======================================//
// Data disc check
//=======================================//
$ret = shell_exec('sudo oddmngst -m chk');
if(preg_match("/ODD Status : Idle/",$ret))
{
	$ret=shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/mopilt -i");
	if(preg_match("/No Disc in Drive/",$ret))
	{
		echo "NG";
	}else
	{
		echo "OK";
	}
}else
{
	echo "ODD BUSY";
}
?>