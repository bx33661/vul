<?php
	include "../session/session_info.php";
	session_save_path($session_save_dir_mobile);
	session_start();
	$_SESSION['page_loaded'] = "yes";
	$_SESSION['page_loaded_refresh'] = "yes";

	if( $_POST['mode'] == "unusehtml5" ){
		$_SESSION['page_content'] = $_POST['page_Content'];
		$_SESSION['page_history'] = $_POST['page_History'];
		$_SESSION['page_count'] = $_POST['page_Count'];
		$_SESSION['page_yscoroll'] = $_POST['page_YScoroll'];
	}
?>
