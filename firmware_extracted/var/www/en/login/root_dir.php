<?php
	function sm_session_destroy_redirect($session_already_started, $redirection_file)
	{
		if ( $session_already_started == TRUE )
		{
			$_SESSION = array();
			session_destroy();
		}
		// just redirection
		echo "<html>";
		echo "<head>";
		echo "<meta http-equiv='cache-control' content='no-cache' />";
		echo "<meta http-equiv='pragma' content='no-cache' />";
		echo "<script type='text/javascript'>";
		echo "alert(\"Other user logged in with same ID \or \this session was expired due to security reasons. Please \log in again\");";
		echo "window.location.href='".$redirection_file."';";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv='refresh' content='0;url=".$redirection_file."' />";
		echo "</noscript>";
		echo "</head>";
		echo "</html>";
		die();
	}

	include "../session/session_info.php";
	session_save_path($session_save_dir_mobile);
	session_start();

	//$model = exec("sudo grep NAS_MODEL /etc/nas/config.sh | cut -d'=' -f2");
	//$ROOT_DIR_PAGE = "./root_dir_".$model.".php";
	$ROOT_DIR_PAGE = "./root_dir_nc2.php";

	if( $_SESSION['page_loaded'] == "yes" ){

		if( $_SESSION['page_loaded_refresh'] == "yes" ){
			$ROOT_DIR_PAGE = "./reload.php";
			$_SESSION['page_loaded_refresh'] = "no";
		}
		else if( $_SESSION['page_loaded_refresh'] == "no" ){
			if(preg_match('/Android 1.6/i',$_SERVER['HTTP_USER_AGENT'])){
				$ROOT_DIR_PAGE = "./savedpage_unusehtml5.php";
			}else{
				$ROOT_DIR_PAGE = "./savedpage.php";
			}
			$_SESSION['page_loaded'] = "no";
		}
	}

	$max_lifetime = (trim(exec("sudo cat ./mobile.ini | grep session.maxlifetime | cut -d '=' -f 2")));
	$auto_login = (trim(exec("sudo cat ./mobile.ini | grep autologinmode | cut -d '=' -f 2")));
	$sort_mode = (trim(exec("sudo cat ./mobile.ini | grep sortmode | cut -d '=' -f 2")));
	$redirection_file = "../login/login_mobile.php";

	$sec = $max_lifetime * 60;
	exec("sudo /usr/lib/nas/find_time /var/www/en/login/ $sec symbolic");
	exec("sudo /usr/lib/nas/find_time /var/lock/session_mobile/ $sec");

	if( $_SESSION['id'] === $nas_login_id_test )
	{
		$LinkFileName = "root".$_SESSION['link_ram_val'];

		if( file_exists( $LinkFileName ) == TRUE ){
			exec("sudo rm ./".$LinkFileName);
		}		
		exec("sudo ln -s /mnt/disk ".$LinkFileName);

		if( $auto_login == "true" ){
			setcookie("username", $_SESSION['username'], time()+31536000, "/");
			setcookie("pw", $_SESSION['AJXP_PW'], time()+31536000, "/");
		}
	}else{
		$_SESSION['page_loaded'] = "no";
		sm_session_destroy_redirect(TRUE, $redirection_file);
	}

	include($ROOT_DIR_PAGE);
?>
