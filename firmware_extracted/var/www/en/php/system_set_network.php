<?php
//=======================================================//
// Session check
//=======================================================//
require_once "../session/session_manage.php";

if ( sm_session_check_on_popup() == FALSE )
{
	//include "../php/msg_illegal_access.php";
	echo '-99';
	die();
}



include "../inc/lcdmsg.php";
// HOSTNAME
$mode		= $_POST["txtMode"];
$host_new	= $_POST["txtHostName"];
$host_desc_new	= $_POST["txtHostDesc"];

$bootproto	= $_POST['rdoDHCP'];
$ipaddr		= $_POST['txtIPAddr'];
$netmask	= $_POST['txtSubnet'];
$gateway	= $_POST['txtGatewayAddr'];
$pridns		= $_POST['txtDNSAddr1'];
$secdns		= $_POST['txtDNSAddr2'];
$mtu	 	= $_POST['txtMTU'];

$domain_type	= $_POST['rdoDomainType'];
$workgroup	= $_POST['txtWorkgroup'];
$domain		= $_POST['txtDomain'];
$domainUser	= $_POST['txtDomainUser'];
$domainPass	= $_POST['txtDomainPass'];

//echo "$mode:$host_new:$host_desc_new:$bootproto:$ipaddr:$netmask:$gateway:$pridns:$secdns:$mtu:$domain_type:$workgroup:$domain:$domainUser:$domainPass";

if ($mode == 'host'){
	$host 		= trim(exec('sudo hostname'));
	$current_ipaddr = trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));
	$description 	= trim(exec('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));

	if($description != $host_desc_new) {
		$description=$description.":".$host_desc_new;
		// NS1
		//exec("sudo /etc/sss_script/share/desc $description");
		//exec("sudo /etc/init.d/samba reload");
		
		// NC1
		exec("sudo /usr/sbin/nas-service config description $host_desc_new");
	}

	if($host != $host_new) {
		// NS1
		//exec("sudo /etc/init.d/services stop");
		//exec("sudo sh -c 'echo $host_new > /etc/hostname'");
		//exec("sudo hostname -F /etc/hostname");
		//exec("sudo sh -c 'echo 127.0.0.1 localhost localhost > /etc/hosts'");
		//exec("sudo sh -c 'echo $ipaddr $host_new >> /etc/hosts'");
		//exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetServerName'");
		//exec("sudo /etc/sss_script/share/query_share.sh config");
		//exec("sudo /etc/init.d/services start");
		
		// NC1
		exec("sudo /usr/sbin/nas-network hostname $host_new");
	}

	echo "ok:host";
}

else if ($mode == 'interface'){
	
	$ip_orig 	= trim(exec ('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));
	$netmask_orig	= trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f4'));
	$gateway_orig	= trim(exec('sudo route -n| grep 0.0.0.0 | awk \'{print $2}\''));
	
	$dns_start = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 | head -1'));
	$dns_stop = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 |tail -1'));

	$dns_entry = file("/etc/resolv.conf");

	if($dns_start==''){
		$pridns_orig = '';
		$secdns_orig = '';
	}else if($dns_start == $dns_stop){
		$DNS = $dns_entry[$dns_start-1];
		$DNS1 = explode(" ",$DNS);
		$pridns_orig = trim($DNS1[1]);
		$secdns_orig = '';
	}else {
		$DNS1 = $dns_entry[$dns_start-1];
		$DNS2 = $dns_entry[$dns_stop-1];
		$DNS1 = explode(" ",$DNS1);
		$DNS2 = explode(" ",$DNS2);
		$pridns_orig = trim($DNS1[1]);
		$secdns_orig = trim($DNS2[1]);
	}	
	
	// NS1
	//$bootproto_orig = trim(exec('sudo grep BOOTPROTO /etc/sss_script/network/ifcfg-egiga0 | cut -d "=" -f 2'));
	// NC1
	$bootproto_orig = trim(exec('sudo nas-network get method')); 	
	$mtu_orig	= trim(exec('sudo ifconfig eth0 | grep MTU | awk \'{print $5}\' | cut -d ":" -f2'));

	if($pridns_orig=='') $pridns_orig='0.0.0.0';
	if($secdns_orig=='') $secdns_orig='0.0.0.0';

	if($pridns=='...') $pridns='0.0.0.0';
	if($secdns=='...') $secdns='0.0.0.0';

	// NC1 --------------------------------------------------------------------
	if ($bootproto == "static") {
		$arp_result = '0';
		if ($ip_orig != $ipaddr) {
			#$arp_result = trim(exec("sudo arping -I eth0 -c1 $ipaddr | grep Received | cut -d \" \" -f 2 > /tmp/arping"));
			$arp_result = trim(exec("sudo nas-network check_conflict $ipaddr"));
			if ($arp_result != '0') {
				echo "ok:ipconflict";
			}
		}
		if ($arp_result == '0' &&
			($ipaddr != $ip_orig 
			|| $netmask != $netmask_orig 
			|| $gateway != $gateway_orig 
			|| $pridns != $pridns_orig 
			|| $secdns != $secdns_orig 
			|| $bootproto != $bootproto_orig 
			|| $mtu != $mtu_orig)) {
			//exec("sudo nas-network static");
			//exec("sudo nas-network address $ipaddr");
			//exec("sudo nas-network netmask $netmask");
			//exec("sudo nas-network gateway $gateway");
			//exec("sudo nas-network dns1 $pridns");
			//exec("sudo nas-network dns2 $secdns");
			//exec("sudo nas-network mtu $mtu");
			exec("sudo nas-network interface static $ipaddr $netmask $gateway $pridns $secdns $mtu");
			exec("sudo nas-network apply");
			echo "ok:interface";
		}
	} else if ($bootproto == "dhcp") {
		exec("sudo nas-network dhcp");
		exec("sudo nas-network apply");
		echo "ok:interface";
	}
	// ------------------------------------------------------------------------
			
// NS1
/*
	if($bootproto == "none") {
	// IP information modified
		msgjob('add','Setting IP Address...');
		if($ip_orig != $ipaddr){
			$arp_result = exec("sudo arping -I eth0 -c1 $ipaddr | grep Received | cut -d \" \" -f 2");
			//echo $arp_result;
			if($arp_result == '0'){
				//exec("sudo /etc/init.d/services stop");
				exec("sudo /etc/sss_script/network/IFcfg web none $ipaddr $netmask $gateway $pridns $secdns $mtu");
				//exec("sudo /etc/sss_script/network/ifup-egiga0");
			} else {echo "ok:ipconflict";}
		
		}else if ($netmask != $netmask_orig || $gateway != $gateway_orig || $pridns != $pridns_orig || $secdns != $secdns_orig || $bootproto != $bootproto_orig || $mtu != $mtu_orig){
			echo "ok:interface";
			//exec("sudo /etc/init.d/services stop");
			exec("sudo /etc/sss_script/network/IFcfg web none $ipaddr $netmask $gateway $pridns $secdns $mtu");
			if ( $gateway != $gateway_orig ) {
				exec("sudo route del default gw $gateway_orig");
				exec("sudo route add default gw $gateway");
			}
			if($mtu_orig != $mtu) {
				exec("sudo ifconfig eth0 mtu $mtu");
			}
			
		}
		msgjob('remove','Setting IP Address...');
		msgjob('once','Complete IP Address Setting');
	}else if($bootproto == "dhcp" ) 
	{
		//exec("sudo /etc/init.d/services stop");
		echo "ok:interface";
		if($mtu != "" && $mtu != $mtu_orig) 
		{
		exec("sudo /etc/sss_script/network/IFcfg web dhcp $ipaddr $netmask $gateway $pridns $secdns $mtu");
		//exec("sudo ifconfig eth0 mtu $mtu");
		}
		else{ 
		exec("sudo /etc/sss_script/network/IFcfg web dhcp $ip_orig $netmask_orig $gateway_orig $pridns_orig $secdns_orig $mtu_orig");
		//exec("sudo /etc/sss_script/network/ifup-egiga0");
		
		}
		if($bootproto_orig == "none" ) exec("sudo /etc/sss_script/network/ifup-egiga0");
	}
	//exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetIpAddr'");
	//exec("sudo /etc/init.d/services start");
*/	
}
else if ($mode == 'domain'){

	// NC1
	$domain_type_file 	= trim(exec('sudo nas-network get domain_type'));	
	$workgroup_file   	= trim(exec('sudo nas-network get workgroup'));	
	$domain_file 	  	= trim(exec('sudo nas-network get domain'));	
	$domainUser_file  	= trim(exec('sudo nas-network get domain_user'));	
	$domainPass_file 	= trim(exec('sudo nas-network get domain_pass'));		
	$dns_start 		= trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 | head -1'));
	$dns_stop 		= trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 |tail -1'));
	$dns_entry 		= file("/etc/resolv.conf");

	if($dns_start==''){
		$pridns_orig = '';
		$secdns_orig = '';
	}else if($dns_start == $dns_stop){
		$DNS = $dns_entry[$dns_start-1];
		$DNS1 = explode(" ",$DNS);
		$pridns_orig = trim($DNS1[1]);
		$secdns_orig = '';
	}else {
		$DNS1 = $dns_entry[$dns_start-1];
		$DNS2 = $dns_entry[$dns_stop-1];
		$DNS1 = explode(" ",$DNS1);
		$DNS2 = explode(" ",$DNS2);
		$pridns_orig = trim($DNS1[1]);
		$secdns_orig = trim($DNS2[1]);
	}		

	if ($domain_type == 'workgroup') {
		$domain_result = trim(exec("sudo nas-network workgroup $workgroup"));
		echo "ok:domain";
	} else if ($domain_type == 'domain') {
		$domain_result = trim(exec("sudo nas-network domain $domain $domainUser $domainPass"));
		if ($domain_result == 'ok') {
			echo "ok:domain";
		} else if ($domain_result == 'join_fail') {
			echo "ok:join_err";
		} else if ($domain_result == 'ns_fail') {
			echo "ok:ns_err";
		} 
	}

	// NS1
/*
	$domain_type_file = trim(exec('sudo grep domain_type= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));	
	$workgroup_file   = trim(exec('sudo grep workgroup= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));	
	$domain_file 	  = trim(exec('sudo grep domain= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));	
	$domainUser_file  = trim(exec('sudo grep domain_user= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));	
	$domainPass_file  = trim(exec('sudo grep domain_pass= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));		

	$dns_start = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 | head -1'));
	$dns_stop = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 |tail -1'));

	$dns_entry = file("/etc/resolv.conf");
	

	if($dns_start==''){
		$pridns_orig = '';
		$secdns_orig = '';
	}else if($dns_start == $dns_stop){
		$DNS = $dns_entry[$dns_start-1];
		$DNS1 = explode(" ",$DNS);
		$pridns_orig = trim($DNS1[1]);
		$secdns_orig = '';
	}else {
		$DNS1 = $dns_entry[$dns_start-1];
		$DNS2 = $dns_entry[$dns_stop-1];
		$DNS1 = explode(" ",$DNS1);
		$DNS2 = explode(" ",$DNS2);
		$pridns_orig = trim($DNS1[1]);
		$secdns_orig = trim($DNS2[1]);
	}		
	

	//if ($domain_type != $domain_type_file ) {
	// chg between workgroup <=> domain	 
		
	
		if ($domain_type == 'workgroup'){
			exec("sudo /etc/sss_script/services/replace.sh workgroup=$workgroup_file workgroup=$workgroup /etc/sss_script/network/domain.conf");
			exec("sudo cp /etc/nsswitch.conf.workgroup /etc/nsswitch.conf");
			exec("sudo /etc/sss_script/services/replace.sh domain_type=$domain_type_file domain_type=$domain_type /etc/sss_script/network/domain.conf");
			exec("sudo /etc/sss_script/services/replace.sh domain=$domain_file domain=NONE /etc/sss_script/network/domain.conf");
			exec("sudo /etc/sss_script/services/replace.sh domain_user=$domainUser_file domain_user=NONE /etc/sss_script/network/domain.conf");
			exec("sudo /etc/sss_script/services/replace.sh domain_pass=$domainPass_file domain_pass=NONE /etc/sss_script/network/domain.conf");
			exec("sudo /etc/init.d/samba stop");
			exec("sudo /etc/sss_script/share/query_share.sh config");
			exec("sudo /etc/init.d/samba start");
			echo "ok:domain";
		}
		if ($domain_type == 'domain'){
			
			if($pridns_orig !='') {
				//echo "test pridns";
				$result_pridns=trim(exec("sudo /usr/bin/dig +time=2 +tries=1 $domain | grep $pridns_orig"));
			}
			//echo "pridns_orig : $pridns_orig";
			//echo "secdns_orig : $secdns_orig";
			if($secdns_orig !='') {
				//echo "test secdns";
				$result_secdns=trim(exec("sudo /usr/bin/dig +time=2 +tries=1 $domain | grep $secdns_orig"));
			}
			//echo "$result_pridns : $result_secdns";
			sleep(5);
			if($result_pridns =='' && $result_secdns =='') {
				echo "ok:ns_err";
			} else {
					
					exec("sudo ntpdate $domain");	
					exec("sudo /etc/sss_script/services/replace.sh domain_type=$domain_type_file domain_type=$domain_type /etc/sss_script/network/domain.conf");
					exec("sudo /etc/sss_script/services/replace.sh domain=$domain_file domain=$domain /etc/sss_script/network/domain.conf");
					exec("sudo /etc/sss_script/services/replace.sh domain_user=$domainUser_file domain_user=$domainUser /etc/sss_script/network/domain.conf");
					exec("sudo /etc/sss_script/services/replace.sh domain_pass=$domainPass_file domain_pass=$domainPass /etc/sss_script/network/domain.conf");
					
					exec("sudo /etc/sss_script/network/krb5conf.sh");
					sleep(5);
					exec("sudo /etc/sss_script/network/AD_login.sh kinit 2>&1",$result_kinit);
					sleep(5);
					//echo $result_kinit[0];
					$result_kinit_temp = explode(" ",$result_kinit[0]);
					$result_kinit_temp1 = explode(" ",$result_kinit[1]);
					//echo "$result_kinit_temp[0] :: $result_kinit_temp1[0]";
					//echo $result_kinit_temp[0][0];
					if($result_kinit_temp[0][0] == 'k' || ($result_kinit_temp[0][0] == 'P' && $result_kinit_temp1[0][0] == 'k')){
						exec("sudo /etc/sss_script/services/replace.sh domain_user=$domainUser domain_user=$domainUser_file /etc/sss_script/network/domain.conf");
						exec("sudo /etc/sss_script/services/replace.sh domain_pass=$domainPass domain_pass=$domainPass_file /etc/sss_script/network/domain.conf");
						exec("sudo /etc/sss_script/services/replace.sh domain_type=$domain_type domain_type=$domain_type_file /etc/sss_script/network/domain.conf");
						exec("sudo /etc/sss_script/services/replace.sh domain=$domain domain=$domain_file /etc/sss_script/network/domain.conf");
						exec("sudo rm /etc/krb5.conf");
						echo "ok:join_err";
					}else {
												
						$temp_workgroup = explode('.',$domain);
						exec("sudo /etc/sss_script/services/replace.sh workgroup=$workgroup_file workgroup=$temp_workgroup[0] /etc/sss_script/network/domain.conf");
						exec("sudo cp /etc/nsswitch.conf.domain /etc/nsswitch.conf");
						exec("sudo /etc/init.d/samba stop");
						exec("sudo /etc/sss_script/share/query_share.sh config");
						exec("sudo /etc/init.d/samba start");
						exec("sudo /etc/sss_script/network/AD_login.sh ads");
						
						echo "ok:domain";
					}
				
			}
		}
	//}
*/
}





?>
