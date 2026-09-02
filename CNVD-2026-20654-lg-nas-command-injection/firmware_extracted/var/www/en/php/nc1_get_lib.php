<?php

function nc1_get_hostname() {
	return trim(exec('sudo hostname'));
}

// the first time error
// : server string = %h server
function nc1_get_host_description() {
	return trim(exec('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));
}

function nc1_get_ftp() {
	return trim(exec('sudo cat /etc/sss_script/services/service.conf | grep "ftp=" | cut -d "=" -f 2'));
}

function nc1_get_ftp_port() {
	return trim(exec('sudo cat /etc/sss_script/services/service.conf | grep "ftp_port=" | cut -d "=" -f 2'));
}

function nc1_get_afp() {
	return trim(exec('sudo grep appletalk /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
}

function nc1_get_email_info() {
	return  trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "EMAIL="|cut -d "=" -f 2'));
}

function nc1_get_fan_info() {
	$tmp = "RPM_SILENT";
	return $tmp." (1056)";
	//$tmp = trim(exec('sudo cat /etc/sss_script/event/fan_info | grep "rpmmode:" |cut -d ":" -f 2'));
	//return $tmp." (".trim(exec('sudo cat /etc/sss_script/event/fan_info | grep "current_rpm:"|cut -d ":" -f 2')).")";
}

function nc1_get_bootproto() {
	return "2";
	//return trim(exec('sudo grep BOOTPROTO /etc/sss_script/network/ifcfg-egiga0 | cut -d "=" -f 2'));
}

function nc1_get_ipaddr() {
	return trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));
}

function nc1_get_netmask() {
	return trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f4'));
}

function nc1_get_gateway() {
	return trim(exec('sudo route -n| grep 0.0.0.0 | awk \'{print $2}\''));
}

function nc1_get_mac_address() {
	return trim(exec('sudo ifconfig eth0 | grep HWaddr | cut -d " " -f 9'));
}

function nc1_get_mtu() {
	return trim(exec('sudo ifconfig eth0 | grep MTU | awk \'{print $5}\' | cut -d ":" -f2'));
}

function nc1_get_domain_type() {
	return trim(exec('sudo grep domain_type= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
}

function nc1_get_workgroup() {
	return trim(exec('sudo grep workgroup= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
}

function nc1_get_domain() {
	return trim(exec('sudo grep domain= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
}

function nc1_get_domain_user() {
	return trim(exec('sudo grep domain_user= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
}

function nc1_get_domain_pass() {
	return trim(exec('sudo grep domain_pass= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
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

function nc1_get_timezone() {
	$tz_temp 	= exec("ls -l /etc/ | grep localtime");
	ereg("/usr/share/zoneinfo/([A-Za-z0-9\/]+)",$tz_temp,$reg);
	return trim($reg[1]);
	//return "Asia/Seoul";
}

function nc1_get_ntp_info(&$ntp) {
	$fd = fopen("/etc/time.conf","r");
	while(!feof($fd)) {
		$buffer	= fgets($fd,4096);
		$reg	= explode("=",$buffer);
		$ntp[]	= trim($reg[1]);
	}
}

function nc1_get_smtp_server() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_SERVER="|cut -d "=" -f 2'));
}

function nc1_get_smtp_auth() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_AUTH="|cut -d "=" -f 2'));
}

function nc1_get_smtp_ssl() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_SSL="|cut -d "=" -f 2'));
}

function nc1_get_smtp_user() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_USER="|cut -d "=" -f 2'));
}

function nc1_get_smtp_pass() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_PASS="|cut -d "=" -f 2'));
}

function nc1_get_subject() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SUBJECT="|cut -d "=" -f 2'));
}

function nc1_get_mailto() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "MAILTO="|cut -d "=" -f 2'));
}

function nc1_get_hdd_report() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "HDD_REPORT="|cut -d "=" -f 2'));
}

function nc1_get_hdd_report_term() {
	return trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "REPORT_TERM="|cut -d "=" -f 2'));
}

function nc1_get_hibernation_enable() {
	return trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "hibernation="|cut -d "=" -f 2'));
}

function nc1_get_hibernation_minutes() {
	return trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "hibernation_minutes="|cut -d "=" -f 2'));
}

function nc1_get_ups_enable() {
	return trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "ups="|cut -d "=" -f 2'));
}

function nc1_get_ups_shutdown_times() {
	return trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "ups_shutdown_time="|cut -d "=" -f 2'));
}

function nc1_get_ups_poweroff() { 
	return trim(exec ('sudo cat /etc/sss_script/services/service.conf | grep "ups_poweroff="|cut -d "=" -f 2')); 
}

function nc1_get_sys_version()  {
	return trim(exec ('sudo cat /etc/sss_script/event/sss_fw_version | grep -i "sss firmware version"|cut -d ":" -f 2'));
}

function nc1_get_sys_date() {
	return trim(exec ('sudo cat /etc/sss_script/event/sss_fw_version | grep -i "sss firmware date"|cut -d ":" -f 2'));
}

function nc1_get_odd_ver() {
	return "JB5";
	//return trim(exec ('sudo cat /sys/block/sr0/device/rev'));
}

function nc1_get_odd_date() {
	return trim(exec ('sudo cat /etc/sss_script/event/sss_odd_fw_version | grep -i "ODDFWUPDATE"|cut -d "=" -f 2'));
}

function nc1_get_dyndns() {
	return trim(exec('sudo grep dyndns= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
}

function nc1_get_dyndns_user_file() {
	return trim(exec('sudo grep username /etc/ddnscli.conf | cut -d " " -f 2'));
}

function nc1_get_dyndns_pass_file() {
	return trim(exec('sudo grep password /etc/ddnscli.conf | cut -d " " -f 2'));
}

function nc1_get_myip() {
	return trim(exec('sudo wget -q -O - http://ns.lgnas.com | cut -d " " -f 6 | cut -d "<" -f 0'));
}

function nc1_get_mydomain() {
	return trim(exec('sudo grep alias /etc/ddnscli.conf | cut -d " " -f 2'));
}

function nc1_get_confirm_ip() {
	return trim(exec("sudo dig +time=1 +tries=1 $mydomain | grep $mydomain | tail -1 | awk '{print $5}'"));  
}

function nc1_get_printer() {
	return trim(exec('sudo grep printer /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
}

function nc1_get_daap() {
	return trim(exec('sudo grep daap= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
}

function nc1_get_daap_update() {
	return trim(exec('sudo grep daap_update /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
}

function nc1_get_daap_rescan_interval() {
	return trim(exec('sudo grep rescan_interval= /etc/mt-daapd.conf')); 
}


?>
