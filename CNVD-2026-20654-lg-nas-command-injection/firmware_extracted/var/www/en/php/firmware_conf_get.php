<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
//require_once "../php/msg_illegal_access.php";
echo '-99';
die();
}


//=======================================================//
// Get firmware config file
//=======================================================//
//echo $log_mode;
$max_cnt = 5;
$file = "/boot/config/config.list";
// Open file
$fd = @fopen($file,"rb");
//echo $fd;
if(!$fd)
{
	echo "File open error.";
	exit();
}
$ret = "";
$list = array();
for($i=0;!feof($fd);$i++)
{
	$list[$i] = array();
	$buffer = fgets($fd,1024);
	list($list[$i][0],$list[$i][1],$list[$i][2]) = explode(" ",$buffer);
	if($i >= $max_cnt) break;
	if($list[$i][0] == "") break;
	$ret .= $list[$i][0].";".$list[$i][1].";".$list[$i][2];
}
echo $ret;
?>
