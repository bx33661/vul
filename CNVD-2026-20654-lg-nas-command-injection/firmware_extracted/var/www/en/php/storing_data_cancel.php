<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
	$ret_str = "{ 'result' : '-100' , 'message' : 'session out' }";
	echo $ret_str;
	return;
}
session_write_close();


$_cclfile = '/etc/sss_script/burn/odd_ccl';


while(!file_exists($_cclfile)){
	shell_exec("sudo touch '$_cclfile' ; sudo chmod 666 '$_cclfile'");
}
shell_exec("sudo echo cancel > '$_cclfile'");

$ret_arr = array('result' => 1 , 'message' => 'complete');
echo json_encode($ret_arr);
?>