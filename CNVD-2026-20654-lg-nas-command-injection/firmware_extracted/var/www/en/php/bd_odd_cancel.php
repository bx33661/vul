<?php
include "../inc/lcdmsg.php";

$mode = $_POST['cancel_mode'];
//echo $mode;
if($mode=='store_data'){
	$_ccl_file = '/etc/sss_script/burn/odd_ccl';
	shell_exec("sudo echo cancel > $_ccl_file");
	//$_ret_arr = array('result' => '1', 'message' => 'sent cancel message');
	//echo json_encode($_ret_arr);
	echo "{ 'result' : 1, 'message' : 'sent cancel message' }";
	return;
}



$cmd = "sudo /usr/local/bin/oddmngst -m chk";
$ret = shell_exec($cmd);
$ret = explode("\n",$ret);
$user_id = "User ID";
$app_id = "Application ID";
foreach($ret as $value)
{
	$tmp = explode(":",$value);
	switch(trim($tmp[0]))
	{
	case $user_id:
		$user_id = trim($tmp[1]);
		break;
	case $app_id:
		$app_id = trim($tmp[1]);
		break;
	default:
		break;
	}
}
$cmd = "sudo /usr/local/bin/oddmngst -u $user_id -a $app_id -m ccl";
$ret = shell_exec($cmd);
$pattern = "Success to cancel process";
if(ereg($pattern,$ret))
{
	$out = "Complete";
	switch($mode)
	{
	case 'rip_dvd':
		msgjob('once','Cancel DVD-Title Extraction');
		break;
	case 'rip_aud':
		break;
	case 'store_data':
		break;
	case 'store_img':
		break;
	case 'burn_data':
		break;
	case 'burn_img':
		break;
	default:
		break;
	}
}else
{
	$out = "Fail";
}
echo $out;
?>