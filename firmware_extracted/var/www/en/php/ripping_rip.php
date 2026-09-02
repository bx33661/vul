<?php
include "../inc/lcdmsg.php";
//=======================================================//
// LGE
// Park94
// 10/25/2008
// Blu-ray / Ripping : rip audio, rip dvd
//=======================================================//
//=======================================================//
// INIT ODD TASK
//=======================================================//
$stat_file = '/etc/sss_script/burn/odd_stat';
if(!file_exists($stat_file))
{
	shell_exec("sudo touch '$stat_file'");
	shell_exec("sudo chmod 666 '$stat_file'");
	if(!file_exists($stat_file))
	{
		echo "ERROR:FAIL TO INIT\n";
		exit;
	}	
}/*else if(is_writable($prog_file))
{
	shell_exec("sudo chmod 666 $stat_file");
	if($ret = shell_exec("ls -l '$stat_file'"))
	{
		$tmp = substr($ret, 1, 6);
		echo $tmp;////
	}
}*/
if(!$fd_stat = fopen($stat_file,"w"))
{
	shell_exec("sudo chmod 666 $stat_file");
	if(!$fd_stat = fopen($stat_file,"w"))
	{
		echo "ERROR:FAIL TO OPEN STAT FILE\n";
		exit;
	}
}
$flag = "start:";
write_file($fd_stat, $flag);
//=======================================================//
// ODD CHECK
//=======================================================//
$ret = shell_exec('sudo oddmngst -m chk');
if(eregi('tray status : opened',$ret))
{
	echo "ERROR:TRAY OPENED\n";
	end_task($fd_stat);
	exit;
}else if(eregi('odd status : busy',$ret))
{
	echo "ERROR:BD IS BUSY\n";
	end_task($fd_stat);
	exit;
}
$ret = shell_exec('sudo oddacsrt -u web -a store -p /usr/local/bin/mopilt -i');
if(eregi('no disc in drive',$ret))
{
	echo "ERROR:NO DISC\n";
	end_task($fd_stat);
	exit;
}
//=======================================================//
// INPUT FROM WEB
// op_mode : rip_audio, rip_dvd
//=======================================================//
$op_mode = $_POST['op_mode'];

switch($op_mode)
{
	case 'rip_audio':
		$cd_mode	= $_POST["mode"];
		$bit		= $_POST["bit"];
		$rate	= $_POST["rate"];
		$path	= $_POST["path"];
		$file_name= $_POST["filename"];
		$flag = "rip:";
		write_file($fd_stat, $flag);
		echo rip_audio($cd_mode,$bit,$rate,$path,$file_name);
		break;
	case 'rip_dvd':
		$dvd_mode	=$_POST["mode"];
		$dvd_path	=$_POST["path"];
		$dvd_name=$_POST["titlename"];
		$flag = "rip:";
		write_file($fd_stat, $flag);
		echo rip_dvd($dvd_mode, $dvd_path, $dvd_name);
		break;
	case 'store_data':
		break;
	default:
		echo "ERROR:UNKNOWN OPERATION MODE\n";
		break;
}
end_task($fd_stat);

//=======================================================//
// RIP AUDIO CD (CDA)
//=======================================================//
function rip_audio($cd_mode,$bit,$rate,$path,$file_name) // Convert to shell script for LCD messaging
{
	msgjob('add','Extracting Audio-CD...');
	$file_name = $path."/".$file_name;
	$ret = shell_exec("sudo /usr/local/bin/oddacsrt -u web -a rip -p /usr/local/bin/cdda2wav -D /dev/sr0 -H -B -silent-scsi -$cd_mode -b $bit -r $rate '$file_name'");
	msgjob('remove','Extracting Audio-CD...');
	if(eregi("odd task canceled",$ret))
	{
		msgjob('once','Cancel Audio-CD Extraction');
		return "NG:CANCELED AUDIO RIP\n";
	}else if(eregi('access denied',$ret))
	{
		msgjob('once','Denied Audio-CD Extraction');
		return "NG:ACCESS DENIED\n";
	}else if($ret)
	{
		// Same return value, complete and cancel
		return "EXCEPTION:COMPLETE OR CANCEL\n";
	}else if(!$ret)
	{
		return "WARNING:NO RETURN VALUE\n";
	}
	//return "WARNING:TIMEOUT\n";
}
//=======================================================//
// RIP DVD TITLE
//=======================================================//
function rip_dvd($dvd_mode, $dvd_path, $dvd_name) // Convert to shell script for LCD messaging
{
	msgjob('add','Extracting DVD-Title...');
	$ret = shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -o '$dvd_path' -'$dvd_mode' -n '$dvd_name'");
	msgjob('remove','Extracting DVD-Title...');
	if(eregi("odd task canceled",$ret))
	{
		msgjob('once','Cancel DVD-Title Extraction');
		return "NG:CANCELED AUDIO RIP\n";
	}else if(eregi('access denied',$ret))
	{
		msgjob('once','Denied DVD-Title Extraction');
		return "NG:ACCESS DENIED\n";
	}else if(eregi('return value : 0',$ret)) // Complete message ?
	{
		msgjob('once','Complete DVD-Title Extraction');
		return "OK:COMPLETE\n";
	}else if(!$ret)
	{
		return "WARNING:NO RETURN VALUE\n";
	}
}
//=======================================================//
// WRITE STAT TO FILE
//=======================================================//
function write_file($fd_stat, $flag)
{
	if(!fwrite($fd_stat, $flag))
	{
		echo "ERROR:FAIL TO WRITE STAT FILE\n";
		end_task($fd_stat);
		exit;
	}
}
//=======================================================//
// CLOSE ODD TASK
//=======================================================//
function end_task($fd_stat)
{
	$flag = "end:";
	if(!fwrite($fd_stat, $flag)) echo "ERROR:FAIL TO WRITE STAT FILE\n";
	fclose($fd_stat);
	exit;
}
?>