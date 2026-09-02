<?php
$mode		= $_POST["txtMode"];

if($mode != 'timeOnly'){
				//////////////////////////////////////////
				//         Host Information             //    
				//////////////////////////////////////////
				
				$host 		= trim(exec('sudo hostname'));
				$description 	= trim(exec ('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));
				
				//////////////////////////////////////////
				//         IP Information               //    
				//////////////////////////////////////////
				
				$bootproto	= trim(exec('sudo grep BOOTPROTO /etc/sss_script/network/ifcfg-egiga0 | cut -d "=" -f 2'));
				$ipaddr 	= trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));
				$netmask 	= trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f4'));
				$gateway	= trim(exec('sudo route -n| grep 0.0.0.0 | awk \'{print $2}\''));
				
				$dns_start = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 | head -1'));
				$dns_stop = trim(exec('sudo cat /etc/resolv.conf | grep -n nameserver | cut -d ":" -f1 |tail -1'));
				
				$dns_entry = file("/etc/resolv.conf");
				
				if($dns_start==''){
					$dns1 = 'NULL';
					$dns2 = 'NULL';
				}else if($dns_start == $dns_stop){
					$DNS = $dns_entry[$dns_start-1];
					$DNS1 = explode(" ",$DNS);
					$dns1 = $DNS1[1];
					$dns2 = 'NULL';
				}else {
					$DNS1 = $dns_entry[$dns_start-1];
					$DNS2 = $dns_entry[$dns_stop-1];
					$DNS1 = explode(" ",$DNS1);
					$DNS2 = explode(" ",$DNS2);
					$dns1 = $DNS1[1];
					$dns2 = $DNS2[1];
				}
				$dns1 = trim($dns1);
				$dns2 = trim($dns2);
				
				$mtu	  	= trim(exec('sudo ifconfig eth0 | grep MTU | awk \'{print $5}\' | cut -d ":" -f2'));
				
				//////////////////////////////////////////
				//      Date & Time & Timezone          //    
				//////////////////////////////////////////
				// Date
				$date 		= exec("date -I");
				$date_entry	= explode("-",$date);
				$year		= $date_entry[0];
				$month		= $date_entry[1];
				$day			= $date_entry[2];
				
				// Time
				$date 		= exec("date -R");
				$time_entry	= explode(" ",$date);
				
				// Timezone
				$tz_temp 	= exec("ls -l /etc/ | grep localtime");
				ereg("/usr/share/zoneinfo/([A-Za-z0-9\/]+)",$tz_temp,$reg);
				$tz=trim($reg[1]);
				
				
				
				//////////////////////////////////////////
				//       Firmware Information           //    
				//////////////////////////////////////////
				
				$sys_version = trim(exec ('sudo cat /etc/sss_script/event/sss_fw_version | grep -i "sss firmware version"|cut -d ":" -f 2'));
				
				
				//////////////////////////////////////////
				//          FTP Information             //    
				//////////////////////////////////////////
				
				$ftp		= trim(exec('sudo grep ftp= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
				
				//////////////////////////////////////////
				//          NTP Information             //    
				//////////////////////////////////////////
				
				$fd		= fopen("/etc/time.conf","r");
				while(!feof($fd)) {
					$buffer	= fgets($fd,4096);
					$reg		= explode("=",$buffer);
					$ntp[]	= trim($reg[1]);
				}
				
				
				//////////////////////////////////////////
				//          Send Information             //    
				//////////////////////////////////////////
				
				echo "$host:$description:$bootproto:$ipaddr:$netmask:$gateway:$dns1:$dns2:$mtu:";
				echo "$year:$month:$day:$time_entry[4]:$tz:";
				echo "$sys_version:$ftp:$ntp[0]:";

}
else{
				//////////////////////////////////////////
				//      Date & Time & Timezone          //    
				//////////////////////////////////////////
				// Date
				$date 		= exec("date -I");
				$date_entry	= explode("-",$date);
				$year		= $date_entry[0];
				$month		= $date_entry[1];
				$day			= $date_entry[2];
				
				// Time
				$date 		= exec("date -R");
				$time_entry	= explode(" ",$date);
				
				// Timezone
				$tz_temp 	= exec("ls -l /etc/ | grep localtime");
				ereg("/usr/share/zoneinfo/([A-Za-z0-9\/]+)",$tz_temp,$reg);
				$tz=trim($reg[1]);
				
				
				
				//////////////////////////////////////////
				//          Send Information             //    
				//////////////////////////////////////////
				
				echo "$year:$month:$day:$time_entry[4]:$tz:";

}


//////////////////////////////////////////
//       Variable Description           //    
//////////////////////////////////////////
// $time_entry[4] : Time
// $tz            : Timezone
// $sys_version   : Current System Firmware Version
// $ntp[0]        : NTP Setting - Enabled or Disabled
// $ftp           : FTP Setting - Enabled or Disabled

?>




