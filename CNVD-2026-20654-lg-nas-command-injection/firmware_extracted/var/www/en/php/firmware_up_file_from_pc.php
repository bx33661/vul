<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
	$upgrade_mode = $_POST["txtUpgrade"];
	switch($upgrade_mode){
		case 'init':
			echo '-99';
		break;
		default:
			require_once ("../multilang/multilang_api.php");
			// language information by url start
			$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
	
			lang_set_active_language($t_lang_from_url[1]);
	  		// language information by url end
		
			echo "<script>alert('".lang_get('login_msg_6')."');</script>";
			echo "<meta http-equiv='refresh' content='0; url=../login/login.php'>";
		break;
	}
	
	die();
	
	
}
session_write_close();

if( $_FILES['upFileFromPc']['error'] <= 0 ) {
	$tmpfile = $_FILES['upFileFromPc']['tmp_name'];
	$filename = $_FILES['upFileFromPc']['name'];
	$result = shell_exec("sudo sh -c 'mv $tmpfile /tmp/$filename'");
}
//var_dump($filename);
$tmpfile = '/tmp/'.$filename;

// To do
// Check config file
$return_array = array();
exec("sudo nas-firmware config_restore $filename PC",&$return_array, &$return_val);
sleep(1);

if($return_val == '0')
	echo "<div id='resultRestore'>complete</div>";
else
	echo "<div id='resultRestore'>fail</div>";
 
?> 
