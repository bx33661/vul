<?php
require_once 'burning_brows_remote.class.php';
//require_once 'bd_pop_brows_remote.class.php';
ini_set('display_errors',1);
$method_name = urldecode($_GET['action']);

if(method_exists('Remote_func',$method_name)){
	$remote_func = new Remote_func();
	$result = '';
	eval('$result=$remote_func->'.$method_name.'();');
	echo $result;
}else{
	echo 'not found';
	exit;
}
?>
