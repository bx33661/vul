<?php
include "../inc/lcdmsg.php";
//==================================================//
// LGE
// Park94
// 10/23/2008
//==================================================//
// Display BD message in LCD
//==================================================//
$mode = $_POST['bd_mode'];
//echo $mode;
switch($mode)
{
case 'rip_dvd_complete':
	msgjob('once','Complete DVD-Title Extraction');
	break;
case 'burn_data_complete':
	$list_file = '/mnt/fs/Vol1/.burn_file.lst';
	shell_exec("sudo rm -rf '$list_file'");
	msgjob('remove','Burning Disc...');
	msgjob('once','Complete Disc Burn');
	shell_exec("sudo /usr/local/bin/oddmngst -m tray");
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'burn_data_cancel':
	$list_file = '/mnt/fs/Vol1/.burn_file.lst';
	shell_exec("sudo rm -rf '$list_file' ");
	msgjob('remove','Burning Disc...');
	msgjob('once','Cancel Disc Burn');
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'burn_data_error':
	$list_file = '/mnt/fs/Vol1/.burn_file.lst';
	shell_exec("sudo rm -rf '$list_file' ");
	$_err = $_POST['message'];
	msgjob('remove','Burning Disc...');
	msgjob('once',$_err);
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'burn_image_complete':
	msgjob('remove','Burning Image...');
	msgjob('once','Complete Image Burn');
	shell_exec("sudo /usr/local/bin/oddmngst -m tray");
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'burn_image_cancel':
	msgjob('remove','Burning Image...');
	msgjob('once','Cancel Image Burn');
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'burn_image_error':
	$_err = $_POST['message'];
	msgjob('remove','Burning Image...');
	msgjob('once',$_err);
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'store_image_complete':
	msgjob('remove','Backup Image...');
	msgjob('once','Complete Image Backup');
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'store_image_cancel':
	msgjob('remove','Backup Image...');
	msgjob('once','Cancel Image Backup');
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'store_image_error':
	$_err = $_POST['message'];
	msgjob('remove','Backup Image...');
	msgjob('once',$_err);
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'store_data_complete':
	msgjob('remove','Copying Disc...');
	msgjob('once','Complete Disc Copy');
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'store_data_cancel':
	msgjob('remove','Copying Disc...');
	msgjob('once','Cancel Disc Copy');
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
case 'store_data_error':
	$_err = $_POST['message'];
	msgjob('remove','Copying Disc...');
	msgjob('once',$_err);
	$_ret_str = array( 'result' => 1);
	echo json_encode($_ret_str);
	return;
default:
	break;
}
?>