<?php

//header('Content-Type: text/html; charset=utf-8');
//=======================================================//
// Session Check
//=======================================================//
	include ("../session/session_manage.php");	  
	sm_session_check("admin", "../login/login.php");    
	require_once ("../multilang/multilang_api.php");

	// language information by url start
  $t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
	lang_set_active_language($t_lang_from_url[1]);

// firmware upgrade
// upgrade_mode='system';
// upgrade_mode='odd';
//$upgrade_mode = $_POST["rdoUpgrade"];
$upgrade_mode = $_POST["txtUpgrade"];
//$config_file = $_POST["txtConfig"];
//$config_date = $_POST["txtConfig_date"];
//echo "1 : $upgrade_mode\n";
//$msg_firmware = "Copying firmware...";
if($upgrade_mode == 'system'){
	if($_FILES["system"]["error"] <= 0){
		//echo "Upload: " . $_FILES["system"]["name"] . "\n";
		//echo "Type: " . $_FILES["system"]["type"] . "\n";
		//echo "Size: " . ($_FILES["system"]["size"] / 1024) . " Kb\n";
		//echo "Stored in: " . $_FILES["system"]["tmp_name"]."\n";

		$SAVE_FILE = $_FILES["system"]["tmp_name"];
		$SAVE_NAME = $_FILES["system"]["name"];

		// NS1
		//exec("sudo sh -c 'mv $SAVE_FILE /tmp/$SAVE_NAME'");
		//$status = trim(exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemUpgrade $SAVE_NAME |grep upstat |tail -1'"));

		// NC1
		$return_array = array();
		exec("sudo nas-firmware update $SAVE_FILE 0 $SAVE_NAME", &$return_array, &$return_val);
		if ($return_val == '0') {
			$status = 'ok';
		} else if ($return_val == '80' || $return_val == '81' || $return_val =='84') {
			$status = 'upstat_NG2';
		} else { 
			$status = 'fail';
		}
	}
} else if ($upgrade_mode == 'firmware_url') {
	$url = $_POST["txtUrl"];
	$status = 'fail';
	if ($url != '') {
		$return_array = array();
		exec("sudo nas-firmware update_url $url", &$return_array, &$return_val);
		if ($return_val == '0') {
			$status = 'ok';
		} else if ($return_val == '80' || $return_val == '81' || $return_val =='84') {
			$status = 'upstat_NG2';
		} else { 
			$status = 'fail';
		}
	}
	echo $status."\n";

}else if($upgrade_mode == 'odd'){
	if($_FILES["odd"]["error"] <= 0){
		//echo "Upload: " . $_FILES["odd"]["name"] . "\n";
		//echo "Type: " . $_FILES["odd"]["type"] . "\n";
		//echo "Size: " . ($_FILES["odd"]["size"] / 1024) . " Kb\n";
		//echo "Stored in: " . $_FILES["odd"]["tmp_name"]."\n";
		
		$SAVE_ODD_FILE = $_FILES["odd"]["tmp_name"];
		$SAVE_ODD_NAME = $_FILES["odd"]["name"];
		
		$return_array = array();
		exec("sudo sh -c 'mv $SAVE_ODD_FILE /tmp/$SAVE_ODD_NAME'");
		exec("sudo sh -c 'nas-firmware odd_update /tmp/$SAVE_ODD_NAME'", &$return_array, &$return_val);
		if ($return_val == '0') {
			$status = 'ok_odd';			
		}
		else if($return_val == '95') {
			$status = 'resync_fail';
		}
		else { 
			$status = 'fail';
		}		
	}
}else if($upgrade_mode == 'init'){

	// NC1
	$return_array = array();
	exec("sudo nas-firmware factory_init", &$return_array, &$return_val);
	//if ($return_val == '0') {
		$status = 'ok';
	//} else { 
	//	$status = 'fail_init';
	//}

	//header("Location: ../system/firmware.php");
}else if($upgrade_mode == 'conf_backup'){
	exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemConfBackup'");
}else if($upgrade_mode == 'conf_restore'){
	exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemConfRestore $config_file $config_date'");
}

header("Expires: -1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Make Text
// NS1
/*
if($status == "upstat_OK"){
	$text_01 = lang_get('power_shutdown_4');
	$text_02 = lang_get('power_shutdown_5');
	$move_link = "../login/login.php";
}		
else if($status == "upstat_NG1"){
	$text_01 = "upstat_NG1";
	$text_02 = "Not Enough Size";
	$move_link = "../system/firmware.php";
}
else if($status == "upstat_NG2"){
	$text_01 = lang_get('power_shutdown_6');
	$text_02 = lang_get('power_shutdown_7');
	$move_link = "../system/firmware.php";
}
else if($status == "upstat_NG3"){
	$text_01 = lang_get('power_shutdown_9');
	$text_02 = lang_get('power_shutdown_10');
	$move_link = "../system/firmware.php";
}
else{
	$text_01 = "Error in Updating";
	$text_02 = lang_get('power_shutdown_7');
	$move_link = "../system/firmware.php";
}
*/

// NC1
if ($status == "ok") {
	$text_01 = lang_get('power_shutdown_4');
	$text_02 = lang_get('power_shutdown_5');
	$move_link = "../login/login.php";
}
else if($status == "ok_odd") {
	header("Location: ../system/firmware.php");
}
else if($status == "upstat_NG2"){
	$text_01 = lang_get('power_shutdown_6');
	$text_02 = lang_get('power_shutdown_7');
	$move_link = "../system/firmware.php";
}
else if($status == "fail_init"){

	$text_01 = lang_get('firmware_init_error1');
	$text_02 = lang_get('firmware_init_error2');
	$move_link = "../system/firmware.php";
}
else{
	$text_01 = lang_get('firmware_upgrade_error');	
	if($status == "resync_fail")
		$text_02 = lang_get('volume_msg_system_resync');
	else
		$text_02 = lang_get('power_shutdown_7');
	$move_link = "../system/firmware.php";
}

?>
<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="cache-control" content="no-cache"> 
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/css.css" rel="stylesheet" type="text/css">
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8' type='text/javascript'></script>
</head>
<body>
<div id='idDisableBackground' style="position:absolute;width:100%;height:100%;top:0px;left:0px;z-index:0;border:none;background-color:#000;opacity:0.6;moz-opacity:0.6;filter:alpha(opacity=60)">
</div>
	
	
	
<div id="restart_msg_box" style="position:absolute;left:50%;top:50%;width:500px;height:180px;z-index:1;margin-left:-250px;margin-top:-90px;background-color:#fff;">
		<table border="0" cellspacing="0" cellpadding="0" width="500px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_restart')?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;" width="80px" height="95px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
				  <td style="vertical-align:middle;padding-left:10px;" width="420px">
				  	<p class="red_text_9"><?php echo $text_01 ?></p>
				  	<p><?php echo $text_02 ?></p>	
				  </td>
				</tr>
				<tr>
					<td colspan="2" height="40px" align="center" style="vertical-align:top;"><a href="<?php echo $move_link ?>"><img src="../images/btn/btn_connect.gif" /></a></td>
				</tr>
		</table>
</div>
</body>
</html>

<script type="text/javascript" charset='utf-8'>
<!--


var status = "<?=$status?>";

//if(status == 'upstat_OK'){
if(status == 'ok'){
	// Activate start_Shutdown After 2 Seconds
	setTimeout('start_Shutdown()',2000);

}
function start_Shutdown(){
	
	var shutdown_mode;
	shutdown_mode='restart';
	var _txText =	'&rdoShutdown='+shutdown_mode;
	sendRequest(onLoadST_Shutdown,_txText,'post','../php/power_shutdown_set.php',true,true);
	return true;
}
function onLoadST_Shutdown(oj)
{
	var res = decodeURIComponent(oj.responseText);
}
//-->
</script>	
