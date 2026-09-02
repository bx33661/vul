<?php

/*
function nc1_() {
	
}
*/

function nc1_set_hostname($host_new, $ipaddr) {
	exec("sudo /etc/init.d/services stop");
	exec("sudo sh -c 'echo $host_new > /etc/hostname'");
	exec("sudo hostname -F /etc/hostname");
	
	exec("sudo sh -c 'echo 127.0.0.1 localhost localhost > /etc/hosts'");
	exec("sudo sh -c 'echo $ipaddr $host_new >> /etc/hosts'");
	//NC1_TODO exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetServerName'");
	exec("sudo /etc/sss_script/share/query_share.sh config");
	exec("sudo /etc/init.d/services start");
}

function nc1_set_descriptioin($description) {
	//**** shell script start ****//
	exec("sudo /etc/sss_script/share/desc $description");
	//**** shell script end ****//
	exec("sudo /etc/init.d/samba reload");
}

function nc1_set_network_interface($ipaddr, $netmask, $gateway, $pridns, $secdns, $mtu) {
	//exec("sudo /etc/init.d/services stop");
	exec("sudo /etc/sss_script/network/IFcfg web none $ipaddr $netmask $gateway $pridns $secdns $mtu");
	//exec("sudo /etc/sss_script/network/ifup-egiga0");	
}

function nc1_set_network_interface_dhcp($ipaddr, $netmask, $gateway, $pridns, $secdns, $mtu) {
	exec("sudo /etc/sss_script/network/IFcfg web dhcp $ipaddr $netmask $gateway $pridns $secdns $mtu");
}

function nc1_set_gateway2($gateway_orig, $gateway) {
	exec("sudo route del default gw $gateway_orig");
	exec("sudo route add default gw $gateway");
}

function nc1_set_mtu($mtu) {
	exec("sudo ifconfig eth0 mtu $mtu");
}

function nc1_set_domain_info($workgroup_file,$workgroup,$domain_type_file,$domain_type, $domain_file,$domainUser_file) {
	exec("sudo /etc/sss_script/services/replace.sh workgroup=$workgroup_file workgroup=$workgroup /etc/sss_script/network/domain.conf");
	exec("sudo cp /etc/nsswitch.conf.workgroup /etc/nsswitch.conf");
	exec("sudo /etc/sss_script/services/replace.sh domain_type=$domain_type_file domain_type=$domain_type /etc/sss_script/network/domain.conf");
	exec("sudo /etc/sss_script/services/replace.sh domain=$domain_file domain=NONE /etc/sss_script/network/domain.conf");
	exec("sudo /etc/sss_script/services/replace.sh domain_user=$domainUser_file domain_user=NONE /etc/sss_script/network/domain.conf");
	exec("sudo /etc/sss_script/services/replace.sh domain_pass=$domainPass_file domain_pass=NONE /etc/sss_script/network/domain.conf");
	exec("sudo /etc/init.d/samba stop");
	exec("sudo /etc/sss_script/share/query_share.sh config");
	exec("sudo /etc/init.d/samba start");
}

function nc1_set_time($time,$timezone) {
	exec("sudo date -s '$time'");
	
	// Hardware Time Setting
	//exec("sudo hwclock --systohc --utc");
	exec("sudo hwclock -w");
	//NC1_TODO exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetTime'");
	exec("sudo rm /etc/localtime");
	exec("sudo ln -s /usr/share/zoneinfo/$timezone /etc/localtime");
	putenv("TZ=".$timezone);
}

function nc1_set_ntp($ntp,$ntp_server,$ntp_refresh) {
	exec("sudo /etc/sss_script/network/ntpcfg $ntp $ntp_server $ntp_refresh");	
	
	$network_status = trim(exec("sudo dig +time=1 +tries=1 ns.lgnas.com | grep timed"));
	if($network_status == ''){
		exec("sudo /etc/cron/cron.d/ntptime");
	}
	
	exec("sudo hwclock -w");
	//NC1_TODO exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetTime'");	
}

function nc1_set_mail_alert($arg1,$SMTP_User,$SMTP_Pass,$subject,$email, $SMTP_server, $mailto, $HDD_report, $HDD_report_term, $CMD) {
	$subject_file = trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SUBJECT="|cut -d "=" -f 2'));	
	$subject=$subject_file.":".$subject;
	
	exec("sudo /etc/sss_script/email/mail-alert-smtp $arg1");
	exec("sudo /etc/sss_script/email/desc $subject");
	exec("sudo /etc/sss_script/email/mail-alert $email $SMTP_server $mailto $HDD_report $HDD_report_term $CMD");
}

function nc1_set_hibernatioin($hib_enable, $hib_minutes) {
	//NC1_TODO exec("sudo sh -c '. /etc/sss_script/event/lib_sss && HIB_ServiceConfig $hib_enable $hib_minutes'");
}

function nc1_set_ups($ups_enable, $shutdown_time, $ups_poweroff) {
	exec("sudo sh -c '. /etc/sss_script/event/lib_sss && UPS_ServiceConfig $ups_enable $shutdown_time $ups_poweroff'");
}


function nc1_set_shutdown($shutdown_mode) {
	if($shutdown_mode == "shutdown") {
		//NC1_TODO exec('sudo /etc/sss_script/event/key_power.sh');
	}
	else if($shutdown_mode == "restart") {
		//NC1_TODO exec('sudo /etc/sss_script/event/key_power.sh reboot');
	}
}

function nc1_set_language($new_client_language) {
	$client_language_file = "/etc/sss_script/share/client_codepage.conf";
	
	exec("sudo chmod 777 /etc/sss_script/share/client_codepage.conf");
	$wfp = fopen($client_language_file, 'w');
	$new_client_language_contents = "codepage=".$new_client_language;
	fwrite($wfp,$new_client_language_contents);
	fclose($wfp);
	exec("sudo chmod 744 /etc/sss_script/share/client_codepage.conf");
	exec("sudo /etc/sss_script/share/query_share.sh config");
}


function nc1_set_config_backup($upgrade_mode) {
	if($upgrade_mode == 'conf_backup'){
		exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemConfBackup'");
	}else if($upgrade_mode == 'conf_restore'){
		exec("sudo sh -c '. /etc/sss_script/event/lib_sss && SSS_SystemConfRestore $config_file $config_date'");
	}
}


function nc1_set_ftp($ftp_file, $ftp, $ftp_port_file, $ftp_port) {
	if ($ftp != $ftp_file ){ 
		exec("sudo /etc/sss_script/services/replace.sh ftp=$ftp_file ftp=$ftp /etc/sss_script/services/service.conf");
		exec("sudo /etc/sss_script/services/replace.sh ftp_port=$ftp_port_file ftp_port=$ftp_port /etc/sss_script/services/service.conf");		
		
		if ($ftp == 'on'){
			exec("sudo /etc/sss_script/share/query_share.sh config");
			exec("sudo /etc/init.d/proftpd start");
		}
		else {
			exec("sudo /etc/sss_script/services/replace.sh ftp_port=$ftp_port_file ftp_port=21 /etc/sss_script/services/service.conf");	
			exec("sudo /etc/init.d/proftpd stop");
		}
	} else if ($ftp == 'on' && ($ftp_port != $ftp_port_file)){
		exec("sudo /etc/sss_script/services/replace.sh ftp_port=$ftp_port_file ftp_port=$ftp_port /etc/sss_script/services/service.conf");		
		exec("sudo /etc/init.d/proftpd stop");
		exec("sudo /etc/sss_script/share/query_share.sh config");
		exec("sudo /etc/init.d/proftpd start");
	}
}

function nc1_set_afp($appletalk_file, $appletalk) {
	if ($appletalk != $appletalk_file ){ 
		exec("sudo /etc/sss_script/services/replace.sh appletalk=$appletalk_file appletalk=$appletalk /etc/sss_script/services/service.conf");
		if ($appletalk == 'on') {
			exec("sudo /etc/sss_script/share/query_share.sh config");
			exec("sudo /etc/init.d/atalk start");
		}
		else exec("sudo /etc/init.d/atalk stop");
	}	
}


function nc1_set_printer($printer_file,$printer) {
	exec("sudo /etc/sss_script/services/replace.sh printer=$printer_file printer=$printer /etc/sss_script/services/service.conf");
	if ($printer == 'on')
	{ 
		//exec("sudo cp /etc/sss_script/share/smb_global_printer.conf /etc/sss_script/share/smb_global.conf");
		exec("sudo /usr/sbin/lpd");
		exec("sudo /etc/sss_script/share/query_share.sh config");
		exec("sudo /usr/bin/smbcontrol smbd reload-config");
	}
	else {
		//exec("sudo cp /etc/sss_script/share/smb_global_general.conf /etc/sss_script/share/smb_global.conf");
		exec("sudo lprm all");
		exec("sudo killall lpd");
		exec("sudo /etc/sss_script/share/query_share.sh config");
		exec("sudo /usr/bin/smbcontrol smbd reload-config");
	}	
}


function nc1_set_daap($daap_file,$daap,$daap_update_file,$daap_update,$rescan) {
	exec("sudo /etc/sss_script/services/replace.sh daap=$daap_file daap=$daap /etc/sss_script/services/service.conf");
	if ($daap == 'on') {
		
		if ($daap_update != $daap_update_file ){ 
			exec("sudo /etc/sss_script/services/replace.sh daap_update=$daap_update_file daap_update=$daap_update /etc/sss_script/services/service.conf");
		if ($daap_update == 'force'){
			if($rescan == 'rescan_interval=300'){
				exec("sudo /etc/sss_script/services/replace.sh \"$rescan\" \"#rescan_interval=300\" /etc/mt-daapd.conf");
			}
			echo "iTunes Service Started";
		}
		if($daap_update == '5min'){ 
			if($rescan == '#rescan_interval=300'){
			exec("sudo /etc/sss_script/services/replace.sh \"$rescan\" \"rescan_interval=300\" /etc/mt-daapd.conf");
			}
			echo "iTunes Service Started";
		}
		sleep(1);
		}
		exec("sudo /etc/init.d/daap start");
	}else if ($daap == 'off') {
		exec("sudo /etc/init.d/daap stop");
		exec("sudo /etc/sss_script/services/replace.sh \"$rescan\" \"#rescan_interval=300\" /etc/mt-daapd.conf");
		exec("sudo /etc/sss_script/services/replace.sh daap_update=$daap_update_file daap_update=force /etc/sss_script/services/service.conf");
		echo "iTunes Service Stopped";
	}	
	
}


function nc1_set_daap_update($daap_update,$daap_update_file,$rescan) {
	if ($daap_update != $daap_update_file ){ 
		exec("sudo /etc/init.d/daap stop");
  		exec("sudo /etc/sss_script/services/replace.sh daap_update=$daap_update_file daap_update=$daap_update /etc/sss_script/services/service.conf");
	
    		if ($daap_update == 'force'){
			if($rescan == 'rescan_interval=300'){
			exec("sudo /etc/sss_script/services/replace.sh \"$rescan\" \"#rescan_interval=300\" /etc/mt-daapd.conf");
			echo "iTunes Service Music List Updated : Manual Mode";
			}
			
   		}
		if($daap_update == '5min'){ 
			if($rescan == '#rescan_interval=300'){
			exec("sudo /etc/sss_script/services/replace.sh \"$rescan\" \"rescan_interval=300\" /etc/mt-daapd.conf");
			echo "iTunes Service Music List Updated : Auto Mode";
			}
			
		}
		sleep(1);
		exec("sudo /etc/init.d/daap start");	
	}else if($daap_update == 'force'){
		exec("sudo /etc/init.d/daap stop");
		sleep(1);
		exec("sudo /etc/init.d/daap start");	
		echo "iTunes Service Music List Updated : Manual Mode";
	}	
}


?>
