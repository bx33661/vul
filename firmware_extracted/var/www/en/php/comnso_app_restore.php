<?php
/* Check if Schedule Backup is working */
$_file = '/etc/cms/~backupburn.msg';
if(file_exists($_file)){
	$_lines = file($_file);
	$_tmp = trim($_lines[0]);
	if($_tmp == 'burn_completed' || eregi('err:',$_tmp)){
		// O.K.
	}else{
		$_ret_arr = array('result' => -4, 'message' => 'Schedule backup is working');
		echo json_encode($_ret_arr);
		return;
	}
}
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
	$_ret_arr = array('result' => -5, 'message' => 'Not ready to restore (Tray not closed)');
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
include "../inc/lcdmsg.php";

	if($_POST['chkval'] != "cms"){
		echo "error -99;";
		return;
	}
msgjob('add','Restoring BD...');
	
	
	// 복원 프로그램을 호출한다.
	$rtn = exec("/usr/bin/cmsrestore -p php");
	
//shell_exeove','Restoring BD...');
msgjob('remove','Restoring BD...');
msgjob('once','Complete BD Restore');
	echo "ok:restore complete";
?>