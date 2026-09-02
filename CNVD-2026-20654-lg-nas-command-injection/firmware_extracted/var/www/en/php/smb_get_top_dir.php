<?php
require_once "../session/session_manage.php";

if ( sm_session_check_on_popup() == FALSE )
{
	include "../php/msg_illegal_access.php";
	die();
}

$host	= trim(exec('sudo hostname'));
$user = stripslashes(@$_SERVER['PHP_AUTH_USER']);
$pw = stripslashes(@$_SERVER['PHP_AUTH_PW']);

exec("sudo smbclient -L $host -U $user%$pw -d 0 | grep -i Disk", $_matches);
#exec("sudo smbclient -L DSS-NC1 -U admin%123456 -d 0 | grep -i Disk", $_matches);

$list='list';
$count=2;

foreach($_matches as $_val){
	#exec("sudo echo $_val >> /var/www/smb/smb.txt");
	$dir_name = preg_split("/[\s,]+/",$_val);
	if ( strcmp($dir_name[1], 'cdrom')!=0 ) {
		$list = $list.":".$dir_name[1];
		$count++;
	}
}
	
#exec("sudo echo $list >> /var/www/smb/smb.txt");
$count_list = $count.":".$list;
echo $count_list;

?>