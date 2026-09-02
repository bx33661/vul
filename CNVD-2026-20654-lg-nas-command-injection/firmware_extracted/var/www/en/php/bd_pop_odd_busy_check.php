<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// ODD status check
//	:	Busy//Idle
//=======================================//
$ret = shell_exec('sudo /usr/local/bin/oddmngst -m chk');
if(eregi('success to check resources',$ret))
{
	if(eregi('odd status : idle',$ret)){
		echo "OK:BD IS IDLE\n";
		exit;
	}else if(eregi('tray status : opened',$ret)){
		echo "NG:BD IS OPENED\n";
		exit;
	}else if(eregi('odd status : busy',$ret))
	{
		echo "NG:BD IS BUSY\n";
	}
	echo "ERROR:STATUS NOT DETECTED\n";
}else
{
	echo "ERROR:BD CHECK FAIL\n";
	exit;
}
echo "ERROR:WRONG PHP LOGIC\n";
exit;
?>