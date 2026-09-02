<?php
include "../session/session_info.php";
if( $_POST[mode] == 'mobile' )
{	
	session_save_path($session_save_dir_mobile);
	session_start();
	unset($_SESSION['id']);
	unset($_SESSION['count']);
	unset($_SESSION['username']);
	exec('sudo rm ../login/root'.$_SESSION['link_ram_val']);
	echo session_destroy();
}
else
{
	session_save_path($session_save_dir);
	session_start();
	unset($_SESSION['id']);
	unset($_SESSION['count']);
	unset($_SESSION['username']);
	echo session_destroy();
}
?> 