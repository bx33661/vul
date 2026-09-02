<?php

	require_once ("../multilang/multilang_api.php");
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
  lang_set_active_language($t_lang_from_url[1]);
	
	include "session_info.php";

	function sm_session_destroy_redirect($session_already_started, $redirection_file)
	{
		if ( $session_already_started == TRUE )
		{
			//unset($_SESSION['id']);
			//unset($_SESSION['count']);
			//unset($_SESSION['username']);
			$_SESSION = array();
			
			session_destroy();
		}
		
		// just redirection
		//header("Location: ".$redirection_file); 
		$expire_msg = lang_get('login_msg_6');
		// redirection after alert message
		echo "<html>";
          echo "<head>";
          echo "<meta http-equiv='cache-control' content='no-cache' />";
          echo "<meta http-equiv='pragma' content='no-cache' />";
		echo "<script type='text/javascript'>";
		//echo "alert('다른 PC에서 동일 ID로 로그인을 했거나 session이 만료 되었습니다.');";
		echo "alert(\"".$expire_msg."\");";
		echo "window.location.href='".$redirection_file."';";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv='refresh' content='0;url=".$redirection_file."' />";
		echo "</noscript>";
          echo "</head>";
		echo "</html>";
		die();
	}
	
	function sm_session_check($access_only_category, $redirection_file)
	{
		global $session_save_dir;
		global $nas_login_id_test;
		global $user_access_menu;

		session_save_path($session_save_dir);		
		session_start();

		//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
		//exec("sudo find /var/lock/session/ -type f -mmin +$min -print0 | xargs -r -0 rm");
		//$min = 20;
		// Remove session info file if its expired time is over
		$sec = 1200;	//default값
		exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
		
		if ( file_exists($session_save_dir) == FALSE )
			sm_session_destroy_redirect(FALSE, $redirection_file);
		session_write_close();


		session_save_path($session_save_dir);		
		session_start();
//		$_SESSION['expired_time'] = $min;
		
		if( $_SESSION['id'] === $nas_login_id_test )
		{
			if($_SESSION['username']!="admin"){
				$tmp=explode('/', trim($_SERVER['REQUEST_URI']));
				$current_menu=$tmp[3];
				if(!in_array($current_menu,$user_access_menu)){
					sm_session_destroy_redirect(TRUE, $redirection_file);
				}
			}
			/*
			if ( ($access_only_category == "admin") && ($_SESSION['username'] !== "admin") )
			{
				sm_session_destroy_redirect(TRUE, $redirection_file);
			}
			
			if ( ($access_only_category === "user") && ($_SESSION['username'] === "admin") )
			{
				sm_session_destroy_redirect(TRUE, $redirection_file);
			}*/
		}
		else
		{
			sm_session_destroy_redirect(TRUE, $redirection_file);
		}
	}
	
	function sm_session_check_on_popup()
	{
		global $session_save_dir;
		global $nas_login_id_test;
		
		if ( file_exists($session_save_dir) == FALSE )
		{
			return FALSE;
		}
		
		// Remove session info file if its expired time is over		
		//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
		//exec("sudo find $session_save_dir -type f -mmin +$min -print0 | xargs -r -0 rm");
		$sec = 1200;	//default값
		exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
		
		session_save_path($session_save_dir);
		session_start();
		
		if( $_SESSION['id'] !== $nas_login_id_test )
		{
			session_destroy();
			
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
?>
