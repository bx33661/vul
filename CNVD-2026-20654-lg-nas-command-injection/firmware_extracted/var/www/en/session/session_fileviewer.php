<?php
	function sm_save_pw_for_fileviewer($_pw)
	{
		$_SESSION["AJXP_PW"] = $_pw;
	}
	function sm_save_uid_for_fileviewer($_uid)
	{
		$_SESSION["AJXP_UID"] = $_uid;
	}
	function sm_save_gid_for_fileviewer($_gid)
	{
		$_SESSION["AJXP_GID"] = $_gid;
	}
?>
