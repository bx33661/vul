<?php


include "../inc/lcdmsg.php";

// firmware upgrade
// upgrade_mode='system';
// upgrade_mode='odd';
$upgrade_mode = $_POST["rdoUpgrade"];

if($upgrade_mode == 'system'){
	$msg_firmware = "F/W Update..";
	msgjob('info',$msg_firmware);
}else if($upgrade_mode == 'odd'){
       $msg_firmware = "ODD Update..";
	msgjob('info',$msg_firmware);
}

?>
