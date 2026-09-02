<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
	require_once ("../multilang/multilang_api.php");
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
	lang_set_active_language($t_lang_from_url[1]);
	
	echo "<script>alert('".lang_get('login_msg_6')."');</script>";
	echo "<meta http-equiv='refresh' content='0; url=../login/login.php'>";
	die();	
}
session_write_close();

$fw_file_name = $_POST['confFile'];
$filename = str_replace(',', '_', $fw_file_name);
$file = '/boot/config/'.$fw_file_name;


if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);error_log("download end",3,"/tmp/a");
    exit;
}


/*
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment;; filename=$filename");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".(string)(filesize($file)));
header("Cache-Control: cache, must-revalidate");
header("Pragma: no-cache");
header("Expires:0");
$fp=fopen($file,"rb");if(!fpassthru($fp)){fclose($fp);}
*/
?>
