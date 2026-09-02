<?php
include "../session/session_manage.php";
if ( sm_session_check_on_popup() == FALSE ){
	$_ret_arr = array( 'result' => '-1' , 'message' => 'session-out');
	echo json_encode($_ret_arr);
	return;
}


$mode = $_POST['mode'];
$file = "/tmp/esata/esata_ccl";
$_log = '/tmp/esata/log';
switch($mode){
	case 'cancel':
		if( do_cancel() ){
			$_ret_arr = array( 'result' => '1' , 'message' => 'success');
		}else{
			$_ret_arr = array( 'result' => '0' , 'message' => 'fail');
		}
		echo json_encode($_ret_arr);
		return;
		break;
	case 'check':
		echo do_check();
		break;
	default:
		break;
}


function do_check(){
	// Detect other's copy
	$_srclist = '/tmp/esata/esata_srclist';
	$_esata_prog = '/tmp/esata/esata_prog';
	if(file_exists($_srclist)){
		$_lines = file($_esata_prog);
		if(trim($_lines[0]) != '100'){
			exec("sudo ps|grep cmscopy",$_matches);
			if(count($_matches)>1){
				return "EsataIsCopying";
			}
		}
	}else{
		return "EsataIsNotCopying";
	}
}
function do_cancel(){
	$file = "/tmp/esata/esata_ccl";
	shell_exec("sudo touch '$file' ; sudo chmod 666 '$file' ; sudo echo cancel > '$file'");
	if( is_canceled() ){
		return true;
	}
	return false;
}


function is_canceled(){
	global $_log;
	$file = "/tmp/esata/esata_ccl";
	if(file_exists($file)){
		$_lines = file($file);
		if(trim($_lines[0]) == 'cancel'){
			return true;
		}
	}
	exec("sudo echo '*[esata copy cancel]fail' >> '$_log'");
	return false;
}
?> 