<?php
$mode = $_POST['mode'];
//echo $mode;

$prog_file = "/var/www/run/status11";  //"/etc/sss_script/burn/odd_prog";
if(!file_exists($prog_file)){
	echo '0';
	return;
}
/*
$fd = fopen($prog_file,"r");
$prog = fgets($fd,1024);
$fclose($fd);
$tmp = trim($prog);
*/

$prog = trim(shell_exec("sudo cat $prog_file"));
$lists = explode(".",$prog);

echo $lists[0];
?>