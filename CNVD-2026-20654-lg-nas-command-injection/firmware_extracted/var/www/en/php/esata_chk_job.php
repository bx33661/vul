<?php
// Get user id from session info.
include "../session/session_info.php";
session_save_path($session_save_dir);
session_start();
$_user_id = $_SESSION['username'];
session_write_close();
/* Check if other e-sata/usb copy is working */
//echo json_encode(do_check());
//echo $_user_id;
echo json_encode(do_check($_user_id));
return;

function do_check($userid){
	$_srclist = '/tmp/esata/esata_srclist';
	$_esata_prog = '/tmp/esata/esata_prog';
	if(file_exists($_srclist)){
		$_lines = @file($_esata_prog);
		if(trim($_lines[0]) != '100'){
			exec("sudo ps -w|grep cmscopy",$_matches);
			foreach($_matches as $_val){
				if(eregi('/usr/bin/cmscopy -l', $_val)){
					// Check user
					$_tmp = @file('/tmp/esata/esata_user');
					$_copy_user = trim($_tmp[0]);
					//echo $userid.' '.$_copy_user;
					if($userid === 'admin' || $userid === $_copy_user){
						//echo '0';
						$_ret_arr = array( 'result' => '0' , 'message' => 'copying');
					}else{
						//echo '-1';
						$_ret_arr = array( 'result' => '-1' , 'message' => 'copying');
					}
					return $_ret_arr;
				}
			}
		}
	}
	$_ret_arr = array( 'result' => '1' , 'message' => 'idle');
	return $_ret_arr;
	
}
?> 