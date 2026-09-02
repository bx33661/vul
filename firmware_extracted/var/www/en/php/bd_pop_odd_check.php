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
		echo "WARNING:BD OPENED\n";
		exit;
	}else if(eregi('odd status : busy',$ret))
	{
		echo "WARNING:BD IS BUSY\n";
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
}else if(eregi('success to open scsi device',$ret))
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
		if($infos['Media Type']=='cda')
		{
			echo "OK:CDA\n";
			exit;
		}
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
		echo "OK:DATA DISC\n";
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
		if(eregi("blank",$infos['Disc Status']) || preg_match($pattern,$infos['Disc Type']))
		{
			echo "OK:WRITABLE DISC\n";
			exit;
		}
		break;
	default:
		echo "ERROR:WRONG INPUT MODE\n";
		exit;
		break;
}
echo "NG:\n";
exit;
?>