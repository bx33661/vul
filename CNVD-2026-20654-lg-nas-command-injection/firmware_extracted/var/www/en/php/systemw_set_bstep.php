<?php

include "../inc/lcdmsg.php";

// HOSTNAME
$mode		= $_POST["txtMode"];


//echo "$mode:$host_new:$host_desc_new:$bootproto:$ipaddr:$netmask:$gateway:$pridns:$secdns:$mtu:$domain_type:$workgroup:$domain:$domainUser:$domainPass";

if ($mode == 'bstep1'){
	
		//msgjob('add','Setting Host Info...');
		
	$host_new	= $_POST["txtHostName"];
	$host_desc_new	= $_POST["txtHostDesc"];
	
		$host 		= trim(exec('sudo hostname'));
		$current_ipaddr = trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));
		$description 	= trim(exec('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));

	if($host != $host_new) {
		exec("sudo /etc/init.d/services stop");
		exec("sudo sh -c 'echo $host_new > /etc/hostname'");
		exec("sudo hostname -F /etc/hostname");
	
		exec("sudo sh -c 'echo 127.0.0.1 localhost localhost > /etc/hosts'");
		exec("sudo sh -c 'echo $ipaddr $host_new >> /etc/hosts'");
		exec("sudo sh -c '. /etc/sss_script/event/lib_io && SSS_SetServerName'");
		exec("sudo /etc/sss_script/share/query_share.sh");
		exec("sudo /etc/init.d/services start");
	}

	if($description != $host_desc_new) {
		$description=$description.":".$host_desc_new;
		exec("sudo /etc/sss_script/share/desc $description");
		exec("sudo /etc/init.d/samba reload");
	}
	
	
		//msgjob('remove','Setting Host Info...');		
	echo "ok:bstep";
}

else if ($mode == 'bstep2'){
	
	msgjob('add','Setting IP Address...');
	
	$bootproto	= $_POST['rdoDHCP'];
	$ipaddr		= $_POST['txtIPAddr'];
	$netmask	= $_POST['txtSubnet'];
	$gateway	= $_POST['txtGatewayAddr'];
	$pridns		= $_POST['txtDNSAddr1'];
	$secdns		= $_POST['txtDNSAddr2'];
	$mtu	 	= $_POST['txtMTU'];
	
	
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
	
	$bootproto_orig = trim(exec('sudo grep BOOTPROTO /etc/sss_script/network/ifcfg-egiga0 | cut -d "=" -f 2'));
	$mtu_orig	= trim(exec('sudo ifconfig eth0 | grep MTU | awk \'{print $5}\' | cut -d ":" -f2'));

	if($pridns_orig=='') $pridns_orig='0.0.0.0';
	if($secdns_orig=='') $secdns_orig='0.0.0.0';

	if($pridns=='...') $pridns='0.0.0.0';
	if($secdns=='...') $secdns='0.0.0.0';


	if($bootproto == "none") {
	// IP information modified
		
		if($ip_orig != $ipaddr){
			$arp_result = exec("sudo arping -I eth0 -c1 $ipaddr | grep Received | cut -d \" \" -f 2");
			//echo $arp_result;
			if($arp_result == '0'){
				//exec("sudo /etc/init.d/services stop");
				exec("sudo /etc/sss_script/network/IFcfg web none $ipaddr $netmask $gateway $pridns $secdns $mtu");
				//exec("sudo /etc/sss_script/network/ifup-egiga0");
			} else {echo "ok:ipconflict";}
		
		}else if ($netmask != $netmask_orig || $gateway != $gateway_orig || $pridns != $pridns_orig || $secdns != $secdns_orig || $bootproto != $bootproto_orig || $mtu != $mtu_orig){
			//echo "ok:interface";
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
	}else if($bootproto == "dhcp" ) 
	{
		//exec("sudo /etc/init.d/services stop");
		//echo "ok:interface";
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
	msgjob('remove','Setting IP Address...');
	msgjob('once','Complete IP Address Setting');
	echo "ok:bstep";


}

else if ($mode == 'bstep3'){
	
	
			// TIME SETTING
			$year	= $_POST["txtYear"];
			$month	= $_POST["txtMonth"];
			$day		= $_POST["txtDay"];
			$hour	= $_POST["txtHour"];
			$min		= $_POST["txtMin"];
			$sec		= $_POST["txtSec"];
			$timezone	= $_POST["txtTimeZone"];
			
			//debugging//
			
			//echo "-Date: $year-$month-$day\n";
			//echo "-Time: $hour:$min:$sec\n";
			//echo "-Timezone: $timezone\n";
			
			
			if($month<10 && strlen($month)< 2 ) $month = "0".$month;
			if($day<10 && strlen($day)<2 ) $day = "0".$day;
			if($hour<10 && strlen($hour) < 2) $hour = "0".$hour;
			if($min<10 && strlen($min) < 2) $min = "0".$min;
			if($sec<10 && strlen ($sec) < 2 ) $sec = "0".$sec;
			//$time = $month.$day.$hour.$min.$year.".".$sec;
			//date --set="01/29/09 15:42:00"
			$time = $month."/".$day."/".$year." ".$hour.":".$min.":".$sec;			
			
			exec("sudo date -s '$time'");
			
			// Hardware Time Setting
			exec("sudo hwclock --systohc --utc");
			
			exec("sudo rm /etc/localtime");
			exec("sudo ln -s /usr/share/zoneinfo/$timezone /etc/localtime");
			putenv("TZ=".$timezone);
			
			echo "ok:bstep";

}


?>
