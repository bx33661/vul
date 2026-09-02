<?php

function nc1_session_check() {
	if ( sm_session_check_on_popup() == FALSE )
	{
		//require_once "../php/msg_illegal_access.php";
		echo '-99';
		die();
	}
}

function nc1_check_ipconflict($ipaddr) {
	return exec("sudo arping -I eth0 -c1 $ipaddr | grep Received | cut -d \" \" -f 2");
}

?>
