<?php
//==================================================//
// LGE
// Park94
// 12/11/2008
//==================================================//

$prog_file = '/etc/sss_script/burn/odd_prog_php';
$lines = file($prog_file);
if(!$lines){
	$ret_arr = array('result' => 0, 'message' => 'fail to read');
	echo json_encode($ret_arr);
	return;
	//echo "{ 'result' : 0, 'message' : 'fail to read' }";
}
switch(trim($lines[0])){
	case 'init':
		$ret_arr = array('result' => 1, 'message' => 'init', 'progress' => 0);
		echo json_encode($ret_arr);
		//echo "{ 'result' : 1, 'message' : 'init' , 'progress' : 0 }";
		return;
	break;
	case 'start':
		$ret_arr = array('result' => 1, 'message' => 'start', 'progress' => 0);
		echo json_encode($ret_arr);
		//echo "{ 'result' : 1, 'message' : 'start' , 'progress' : 0 }";
		return;
	break;
	case 'ing':
		$_tmp = explode('/',trim($lines[1]));
		$_prog = floatval($_tmp[0]) / floatval($_tmp[1]) * 100;
		$ret_arr = array('result' => 1, 'message' => 'ing', 'progress' => $_prog);
		//$ret_arr = array('result' => 1, 'message' => 'ing', 'progress' => $_tmp[0].'/'.$_tmp[1]);
		echo json_encode($ret_arr);
		//echo "{ 'result' : 1, 'message' : 'ing' , 'progress' : $_prog }";
		return;
	break;
	case 'complete':
		$ret_arr = array('result' => 1, 'message' => 'complete', 'progress' => 100);
		echo json_encode($ret_arr);
		//echo "{ 'result' : 1, 'message' : 'complete' , 'progress' : 100 }";
		return;
	break;
	case 'cancel':
		$ret_arr = array('result' => 1, 'message' => 'cancel', 'progress' => 100);
		echo json_encode($ret_arr);
		//echo "{ 'result' : 1, 'message' : 'cancel' , 'progress' : 100 }";
		return;
	break;
	default:
		$ret_arr = array('result' => 0, 'message' => 'no status');
		echo json_encode($ret_arr);
		//echo "{ 'result' : 0, 'message' : 'no status' }";
		return;
	break;
}
return;

?> 