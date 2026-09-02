<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
require_once "../php/msg_illegal_access.php";
die();
}


	$md = $_POST['md'];
	$file_name1 = "/sys/block/".$md."/md/sync_completed";
	$fd1 = fopen($file_name1, "r");
	$read1 = fread($fd1,filesize($file_name1));
	fclose($fd1);
	$md = $_POST['md'];
	$file_name2 = "/sys/block/".$md."/md/sync_action";
	$fd2 = fopen($file_name2, "r");
	$read2 = fread($fd2,filesize($file_name2));
	fclose($fd2);
	echo trim($read1)." / ".trim($read2);
?>
 