<?php
	include "../session/session_info.php";

	$max_lifetime = (trim(exec("sudo cat ./mobile.ini | grep session.maxlifetime | cut -d '=' -f 2")));
	$sec = $max_lifetime * 60;
	exec("sudo /usr/lib/nas/find_time /var/www/en/login/ $sec symbolic");
	exec("sudo /usr/lib/nas/find_time /var/lock/session_mobile/ $sec");

	session_save_path($session_save_dir_mobile);
	session_start();

	$LinkFileName = "root".$_SESSION['link_ram_val'];

	if( $_SESSION['id'] === $nas_login_id_test ){
		exec("sudo rm ./".$LinkFileName);
		exec("sudo ln -s ".$_SESSION['prefix_dir']." ./".$LinkFileName);
	}else{
		$_SESSION = array();
		session_destroy();
		echo "redir";
	}
?>
