<?php
//==================================================//
// LGE
// Park94
// 10/23/2008
// Check ODD status for Blu-ray popup window
// ODD status : start:rip:end:
//==================================================//
$stat_file = '/etc/sss_script/burn/odd_stat';
if(!$fd_stat = fopen($stat_file,"r"))
{
	echo "ERROR:FAIL TO OPEN STAT FILE\n";
	exit;
}
$read = fgets($fd_stat, 512);
$tmp = explode(":",$read);
//print_r($tmp);
if($tmp[1]) echo "OK:WORKING\n";
fclose($fd_stat);
?>