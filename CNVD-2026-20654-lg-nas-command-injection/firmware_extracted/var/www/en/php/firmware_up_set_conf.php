<?php

include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
        $ret_str = "{ 'result' : '-99' , 'message' : 'session out' }";
        echo $ret_str;
        return;
}


// Splitted From firmware_up_set.php
// For Process By AJAX

$upgrade_mode = $_POST["txtUpgrade"];
$config_file = $_POST["txtConfig"];
$config_date = $_POST["txtConfig_date"];

if($upgrade_mode == 'conf_backup'){
	// NS1
	//exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemConfBackup'");

	// NC1
	exec("sudo nas-firmware config_backup");
} else if ($upgrade_mode == 'conf_restore') {
	// NS1
	//exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemConfRestore $config_file $config_date'");

	// NC1
	exec("sudo nas-firmware config_restore $config_file NC1");
}
