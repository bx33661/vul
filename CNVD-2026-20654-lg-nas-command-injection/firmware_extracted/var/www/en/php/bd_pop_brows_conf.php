<?php
/*
 * $_SESSION['username'] : 유저 ID (나중에 로그인처리가 되면 로그인한 유저의 ID)
 * $_SESSION['current_dir'] : 각 유저별 디렉토리를 루트로 가정했을때의 현재 디렉토리 위치
 * 								(유저의 디렉토리가 /usr/local/apache/htdocs/test/이고
 * 									/usr/local/apache/htdocs/test/mydir_1 이 현재위치일때,
 * 									/mydir_1이 된다.)
 * $_SESSION['storage_url'] : 유저 디렉토리의 외부에서 접속 가능한 url www.browser.com/test 의 형식이 된다.
 * $_SESSION['user_directory'] : 유저 디렉토리의 실제 시스템상의 Path (/usr/local/apache/htdocs/test)
 */
include "../session/session_info.php";
session_save_path($session_save_dir);
session_start();

$project_url = (substr($_SERVER['DOCUMENT_ROOT'],-1)=='/') ? $_SERVER['DOCUMENT_ROOT']: $_SERVER['DOCUMENT_ROOT'].'/';
$_SESSION['storage_url'] = $project_url.$_SESSION['username'];
$_SESSION['user_directory'] = "/mnt/disk";

// Service path setting
$dlna_path = exec("sudo nas-storage get vol_default");
if(!eregi("/mnt/disk",$dlna_path)){
	$dlna_path = '';
}
else
{
	$tmp = explode("/",$dlna_path);
	//$burn_path = '/'.$tmp[3].'/service/backup/usb';
	//$burn_path = '/'.$tmp[3].'/service/backup';
	$dlna_path = '/'.$tmp[3].'/'.$tmp[3];
}
$usb_path = exec("sudo nas-storage get vol_default");
if(!eregi("/mnt/disk",$usb_path)){
	$usb_path = '';
}
else
{
	$tmp = explode("/",$usb_path);
	$usb_path = '/'.$tmp[3].'/service/backup/usb';

}

$BD_mode = array('rip','store','schedule','data copy','image backup','burn','image burn','usb','dlna');
$BD_path = array(
	'rip' => '/linear/NC1',    /*'/Vol1/system/Backup/RIP',*/ 
	'store' => '/linear/NC1', /*'/Vol1/system/Backup',*/
	'schedule' =>'/linear/NC1',/* '/Vol1/system/Backup',*/ 
	'data copy' =>'/linear/NC1',// '/Vol1/system/Backup/COPY', 
	'image backup' =>'/linear/NC1',// '/Vol1/system/Backup/IMG', 
	'burn' =>'/linear/NC1',// '/Vol1/system/Share/',
	'image burn' =>'/linear/NC1',// '/Vol1/system/Backup/IMG',
	'usb' =>$usb_path, //'/Vol1/system/Backup/USB');
	'dlna'=>$dlna_path);


	
@$path_mode = $_GET['path_mode'];
// Init current_dir
if(substr($_SESSION['current_dir'],-1) != '/'){
	$_SESSION['current_dir'] .= '/';
}
if(in_array($_GET['path_mode'],$BD_mode))
{
	$_SESSION['current_dir'] = $BD_path[$path_mode];
}

//=======================================================//
// Setting available root path and accessible path list
// $mount_path => 'mount_dir'
// $default_path => 'share_dir'
// $rw_list, $ro_list => 'rw_dir', 'ro_dir'
//=======================================================//
$ret = shell_exec("sudo mount | grep /mnt/fs/Vol");
$ret = substr($ret,0,-1);
$tmp = explode("\n",$ret);
$mount_path = array();
foreach($tmp as $value)
{
	$list = explode(" ",$value);
	$mount_path[] = trim($list[2]);
}
$_SESSION['mount_dir'] = $mount_path;

?>