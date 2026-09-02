<?php
//==================================================//
// LGE
// Park94
// 12/11/2008
//==================================================//
/* Session Check
 */
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
	$ret_str = "{ 'result' : 0 , 'message' : 'session out' }";
	echo $ret_str;
	return;
}
session_write_close();
$ccl_file = '/etc/sss_script/burn/odd_ccl';
$prog_file = '/etc/sss_script/burn/odd_prog_php';

$mode = $_POST['mode'];
switch($mode){
	case 'store_data':
		echo store_data_init();
		return;
	break;
	default:
	break;
}
return;


function store_data_init(){
	global $ccl_file, $prog_file;
	while(!file_exists($ccl_file) || !file_exists($prog_file)){
		shell_exec("sudo rm -f $ccl_file $prog_file; sudo touch $ccl_file $prog_file; sudo chmod 666 $ccl_file $prog_file");
	}
	shell_exec("sudo echo init > $ccl_file; sudo echo init > $prog_file");
	//$ret_arr = array('result' => '1', 'message' => 'complete');
	//$ret_str = json_encode($ret_arr);
	$ret_str = "{ 'result' : 1 , 'message' : 'complete' }";
	return $ret_str;
}
?> 