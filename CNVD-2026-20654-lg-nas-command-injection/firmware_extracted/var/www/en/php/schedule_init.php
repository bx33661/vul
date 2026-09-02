<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
	require_once("../multilang/multilang_api.php");

	// language information by url start
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
	
	lang_set_active_language($t_lang_from_url[1]);
	//include "../php/msg_illegal_access.php";
	echo lang_get('login_msg_6');
	die();
}
session_write_close();

include "../inc/lcdmsg.php";

$op_mode = $_POST['mode'];
//echo $op_mode;
switch($op_mode)
{
	case 'format_disc':
		echo format_disc();
		break;
	case 'init_db':
		echo init_db();
		break;
	case 'init_all':
		$ret = init_db();
		if(eregi("ok",$ret))
		{
			$ret = format_disc();
		}
		echo $ret;
		break;
	case 'init_res':
		$_file = '/etc/cms/~resdisccheck.msg';
		$_res = shell_exec("sudo rm -f '$_file' 2>&1");
		exec('sudo touch /etc/cms/test.msg');
		echo json_encode(array( 'result' => 1 , 'message' => $_res));
		return;
		break;
	default:
		break;
}

function format_disc()
{
	
	// Check if tray is ready to restore date
	// Ready : Closed
	// Not ready : Closing/Opened/Opening
	// Check tray status
	exec("sudo oddmngst -m chk", $_results);
	$_tray_stat = array();
	foreach($_results as $_val){
		$_tmp = explode(':',$_val);
		$_tray_stat[trim($_tmp[0])] = trim($_tmp[1]);
	}
	if(@$_tray_stat['Tray Status'] === 'Closed'){
		//echo 'Closed, ready to restore';
	}else{
		$_ret_arr = array('result' => -5, 'message' => 'Not ready to backup (Tray not closed)');
		echo json_encode($_ret_arr);
		return;
	}
	// Check if disc is in tray
	$_res = shell_exec("sudo mopilt -i");
	if(eregi('no disc in drive',$_res)){
		$_ret_arr = array('result' => -6, 'message' => 'No disc in drive');
		echo json_encode($_ret_arr);
		return;
	}
	
	
	msgjob('add','Erase Disc...');
	shell_exec("sudo umount /dev/sr0 -l");
	
	$cmd = "sudo oddacsrt -u web -a schedule -p /usr/local/bin/mosilt -f";
	$ret = shell_exec($cmd);
	
	shell_exec("sudo mount /dev/sr0");
	
	msgjob('remove','Erase Disc...');
	if(eregi('not formattable media',$ret))
	{
		msgjob('once','Not Formattable Disc');
		return "ERROR:NOT FORMATTABLE MEDIA\n";
	}else if(eregi('format success',$ret) || eregi('blank success',$ret))
	{
		msgjob('once','Complete Erase Disc');
		return "OK:COMPLETE ERASE DISC\n";
	}else
	{
		msgjob('once','Denied Erase Disc');
		if(eregi("access denied",$ret)){
			return "BUSY";
		}else{
			preg_match("/error : \d+/i",$ret,$matches);
			//print_r($matches);
			if($matches[0]) return $matches[0];
		}
		return "ERROR:Unknown\n";
	}
}
function init_db()
{
	$db_files = array("/etc/cms/cmsbackup.db","/etc/cms/~discinfo.xml","/etc/cms/~resdisccheck.msg");
	shell_exec("sudo rm '$db_files[0]' '$db_files[1]' '$db_files[2]'");
	if(file_exists($db_files[0]) || file_exists($db_files[1]) || file_exists($db_files[2]))
	{
		return "ERROR:FAIL TO INITIALIZE BACKUP DATABASE\n";
	}
	return "OK:COMPLETE INITIALIZE BACKUP DATABASE\n";
}
?>