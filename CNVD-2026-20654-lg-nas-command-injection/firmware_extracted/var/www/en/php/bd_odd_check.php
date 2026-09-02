<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// ODD status check
//	:	Busy//Idle
//=======================================//
$mode = $_POST['mode'];
//echo "DEBUG:$mode\n";


//juny
$bd_status_path= "/var/www/run/status11";
$bd_status = exec("sudo cat $bd_status_path");//| tail -1");

if(eregi('no disc',$bd_status))
{
	echo "WARNING:NO DISC\n";
	exit;
}
else if(eregi('can\'t burn',$bd_status))
{
	if(eregi('RW',$bd_status))
		echo "OK:REWRITABLE DISC CONTAINING DATA\n";
	else
		echo "NG:NOT A WRITABLE DISC\n";
	exit;
}
else if(eregi('size',$bd_status))
{
	$tmp = explode(":",$bd_status); 
	$size = explode("K",$tmp[1]);

	$disc_cap = floatval($size[0])*1024;
	
	echo "OK:BLANK DISC\n";
	echo "FREE SPACE:".$disc_cap." Bytes\n";
	exit;
}else
{
	echo "ERROR:BD CHECK FAIL\n";
	exit;
}

/*
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

$ret = shell_exec('sudo oddacsrt -u web -a bd -p /usr/local/bin/mopilt -i');
if(eregi('odd access denied at chk',$ret))
{
	echo "WARNING:BD IS BUSY\n";
	exit;
}else if(eregi('success to open scsi device',$ret)||eregi('success to retry to open scsi device',$ret))
{
	$infos = explode("\n",$ret);
	foreach($infos as $value)
	{
		$info = explode(":",$value);
		if($info[1])
		{
			$infos[trim($info[0])] = trim($info[1]);
		}else if(eregi('no disc in drive',$value))
		{
			echo "WARNING:NO DISC\n";
			exit;
		}
	}
}else
{
	echo "ERROR:DISC CHECK FAIL\n";
	exit;
}
//print_r($infos);

switch($mode)
{
	case 'rip_audio':
		echo ($infos['Media Type']=='cda') ? "OK:AUDIO CD\n" : "NG:NOT AUDIO CD\n";
		exit;
		break;
	case 'rip_dvd':
		if(eregi('dvd',$infos['Disc Type']))
		{
			if($infos['Protected Disc']=='Yes')
			{
				echo "NG:PROTECTED DISC\n";
				exit;
			}
			$ret = shell_exec('sudo /usr/local/bin/dvdbackup -i /dev/sr0 -I');
			if(eregi('dvd-video information',$ret))
			{
				echo "OK:DVD TITLE\n";
				exit;
			}
		}
		echo "NG:NOT DVD TITLE\n";
		exit;
		break;
	case 'store_data':
		if(preg_match("/cd[ax]/",$infos['Media Type']) || eregi('blank',$infos['Disc Status']))
		{
			echo "NG:CDA/CDX/BLANK\n";
			exit;
		}else if(preg_match("/DVD/",$infos['Disc Type']))
		{
			$ret = shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -I");
			if(preg_match("/DVD-Video information/",$ret))
			{
				echo "NG:DVD TITLE\n";
				exit;
			}
		}
		if(!shell_exec('sudo mount | grep /mnt/cdrom')){
			echo "NG:NOT DATA DISC";
		}else{
			echo "OK:DATA DISC\n";
		}
		
		exit;
		break;
	case 'store_image':
		if(eregi('blank',$infos['Disc Status']))
		{
			echo "NG:BLANK DISC\n";
			exit;
		}else if($infos['Protected Disc']=='Yes')
		{
			echo "NG:PROTECTED DISC\n";
			exit;
		}
		echo "OK:AVAILABLE DISC\n";
		exit;
		break;
	case 'burn':
		$pattern="/[+-]R[EWA]M?/";
		if( eregi("blank",$infos['Disc Status']) )
		{
			echo "OK:BLANK DISC\n";
		}else if( preg_match($pattern,$infos['Disc Type']) )
		{
			if( eregi("__na__",$infos['Volume name']) )
			{
				echo "OK:REWRITABLE DISC\n";
			}else
			{
				echo "OK:REWRITABLE DISC CONTAINING DATA\n";
			}
		}else{
			echo "NG:NOT A WRITABLE DISC\n";
		}
		echo "FREE SPACE:".$infos['Free Space']."\n";
		exit;
		break;
	default:
		echo "ERROR:WRONG INPUT MODE\n";
		exit;
		break;
}
echo "NG:\n";
exit;
*/
?>