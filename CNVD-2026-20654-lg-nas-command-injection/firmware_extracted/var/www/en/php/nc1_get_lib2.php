<?php

function nc1_get_data($func) {
	return trim(shell_exec("sudo sh -c '. /etc/sss_script/event/lib_sss && $func'"));
}

function nc1_get_dns_info(&$dns1, &$dns2) {
	$dns_start = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 | head -1'));
	$dns_stop = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 |tail -1'));
	
	$dns_entry = file("/etc/resolv.conf");
	
	if($dns_start==''){
		$dns1 = 'NULL';
		$dns2 = 'NULL';
	}else if($dns_start == $dns_stop){
		$DNS = $dns_entry[$dns_start-1];
		$DNS1 = explode(" ",$DNS);
		$dns1 = trim($DNS1[1]);
		$dns2 = 'NULL';
	}else {
		$DNS1 = $dns_entry[$dns_start-1];
		$DNS2 = $dns_entry[$dns_stop-1];
		$DNS1 = explode(" ",$DNS1);
		$DNS2 = explode(" ",$DNS2);
		$dns1 = trim($DNS1[1]);
		$dns2 = trim($DNS2[1]);
	}
}

function nc1_get_date(&$year, &$month, &$day) {
	$date 		= exec("date -I");
	$date_entry	= explode("-",$date);
	$year		= $date_entry[0];
	$month		= $date_entry[1];
	$day		= $date_entry[2];
}

function nc1_get_time(&$time_entry) {	
	$date 		= exec("date -R");
	$time_entry	= explode(" ",$date);
}

function nc1_get_ntp_info(&$ntp) {
	$fd = fopen("/etc/time.conf","r");
	while(!feof($fd)) {
		$buffer	= fgets($fd,4096);
		$reg	= explode("=",$buffer);
		$ntp[]	= trim($reg[1]);
	}
}


?>
