<?php
$mode = $_POST['mode'];
//echo $mode;
switch($mode)
{
case "before_burn":
	$prog_file = "/etc/sss_script/burn/burn_disc_prog";
	$fd = fopen($prog_file,"r");
	$prog = fgets($fd,1024);
	$tmp = trim($prog);
	echo $tmp;
	break;
case "burn":
	$prog_file = "/etc/sss_script/burn/odd_prog";
	$fd = fopen($prog_file,"r");
	$prog = fgets($fd,1024);
	$tmp = (int)trim($prog);
	echo $tmp;
	break;
default:
	$ret = "Error : input mode is wrong\n";
	echo $ret;
	break;
}
?>