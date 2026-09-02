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


//==================================================//
// Volume Bay Information
//==================================================//
	$vol_mode = $_POST["rdoVolBay"];
	
	if( $vol_mode  == "bay" ){
		//$ret=shell_exec(`sudo sh -c "sleep 1"`);
//		$ret=shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_GetBayList"`);
		$ret=shell_exec(`sudo nice -n -10 /etc/sss_script/disk/baylist.sh`);
		$ret=shell_exec('sudo sh -c "cat /etc/sss_script/disk/bay_list"');		
	}else if( $vol_mode  == "vol" ){
		//$ret=shell_exec(`sudo sh -c "sleep 1"`);
//		$ret=shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_GetVolList"`);		
		$ret=shell_exec(`sudo nice -n -10 /etc/sss_script/disk/vollist.sh`);
		$ret=shell_exec('sudo sh -c "cat /etc/sss_script/disk/vol_list"');
	} 
	echo $ret;
?>