<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// ODD status check
//	:	Busy//Idle
//=======================================//
$mode = $_POST['mode'];
//echo "DEBUG:$mode\n";

$ret = shell_exec('sudo /usr/local/bin/oddmngst -m chk');
if(eregi('success to check resources',$ret))
{
	if(eregi('tray status : opened',$ret))
	{
		echo "WARNING:TRAY OPENED\n";
		exit;
	}else if(eregi('odd status : busy',$ret))
	{
		//echo preg_match("/User ID : \w*\b/",$ret,$matches);
		if( preg_match("/User ID : \w*\b/",$ret,$matches)=="1" ){
			$tmp =  explode(" : ",$matches[0]);
			$user_id = $tmp[1];
			//echo $user_id;
		}
		if( preg_match("/Application ID : \w*\b/",$ret,$matches)=="1" ){
			$tmp =  explode(" : ",$matches[0]);
			$app_id = $tmp[1];
			//echo $user_id;
		}
		
		echo "WARNING:DRIVE IS BUSY\nUSER ID:$user_id\nAPPLICATION ID:$app_id\n";
		exit;
	}
}else
{
	echo "ERROR:BD CHECK FAIL\n";
	exit;
}
echo "OK:\n";
?> 