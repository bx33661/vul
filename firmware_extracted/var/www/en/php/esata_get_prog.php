<?php

$_prog_file = '/tmp/esata/esata_prog';
$_ccl_file = '/tmp/esata/esata_ccl';
$_prog_lines = @file($_prog_file);
$_ccl_lines = @file($_ccl_file);
$_prog = intval($_prog_lines[0]);
$_ccl = trim($_ccl_lines[0]);
if($_prog == 100){
	$_ret_arr = array('result' => '1' , 'message' => 'complete');
}else if($_prog < 0){
	$_ret_arr = array('result' => '-2' , 'message' => $_prog);
}else if($_ccl == 'cancel'){
	$_ret_arr = array('result' => '-1' , 'message' => 'cancel');
}else{
	$_ret_arr = array('result' => '0' , 'message' => $_prog);
}
echo json_encode($_ret_arr);
	
	
?>