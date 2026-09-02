<?php
/*
//NC1_DEBUG
$file=fopen("/var/www/php_debug","a") or exit("Unable to open file");
fwrite($file,$time);
fwrite($file,"\n");
fclose($file);
*/
//=======================================================//
// Session check
//=======================================================//
require_once "../session/session_manage.php";

if ( sm_session_check_on_popup() == FALSE )
{
	include "../php/msg_illegal_access.php";
	die();
}


//////////////////////////////////////////
//         Host Information             //    
//////////////////////////////////////////

$host 		= trim(exec('sudo hostname'));
$description 	= trim(exec ('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));

//////////////////////////////////////////
//        DOMAIN Information            //    
//////////////////////////////////////////
/*
$domain_type    = trim(exec('sudo grep domain_type= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));

$workgroup 	= trim(exec('sudo grep workgroup= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
$domain 	= trim(exec('sudo grep domain= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
$domain_user 	= trim(exec('sudo grep domain_user= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
$domain_pass	= trim(exec('sudo grep domain_pass= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
*/
//////////////////////////////////////////
//      Date & Time & Timezone          //    
//////////////////////////////////////////
// Date
$date 		= exec("sudo date -I");
$date_entry	= explode("-",$date);
$year		= $date_entry[0];
$month		= $date_entry[1];
$day			= $date_entry[2];

// Time
$date 		= exec("sudo date -R");
$time_entry	= explode(" ",$date);

// Timezone

// NS1
//$tz_temp 	= exec("ls -l /etc/ | grep localtime");
//ereg("/usr/share/zoneinfo/([A-Za-z0-9\/]+)",$tz_temp,$reg);
//$tz=trim($reg[1]);

// NC1
$tz=trim(exec('sudo nas-system get timezone'));


//////////////////////////////////////////
//       Firmware Information           //    
//////////////////////////////////////////

$sys_version = trim(exec ('sudo nas-firmware get version'));


//////////////////////////////////////////
//          FTP Information             //    
//////////////////////////////////////////

// NS1
//$ftp		= trim(exec('sudo grep ftp= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

// NC1
//$ftp		= trim(exec('sudo nas-service get enabled ftp'));

//////////////////////////////////////////
//          NTP Information             //    
//////////////////////////////////////////

// NS1
//$fd		= fopen("/etc/time.conf","r");
//while(!feof($fd)) {
//	$buffer	= fgets($fd,4096);
//	$reg		= explode("=",$buffer);
//	$ntp[]	= trim($reg[1]);
//}

// NC1
//$ntp[0]			= trim(exec('sudo nas-system get ntp'));

//////////////////////////////////////////
//          RAID Information             //    
//////////////////////////////////////////

$vol_fstab = trim(exec('sudo nas-storage get vol_fstab'));

//////////////////////////////////////////
//         DDNS Information             //    
//////////////////////////////////////////

// NS1
//$ftp		= trim(exec('sudo grep ftp= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

// NC1
$ddns		= trim(exec('sudo nas-service get_ddns enabled'));
$ddns_domain	= trim(exec('sudo nas-service get_ddns alias'));

//////////////////////////////////////////
//          iSCSI Information             //    
//////////////////////////////////////////

$iscsi		= trim(exec('sudo nas-service get_iscsi enabled'));

//////////////////////////////////////////
//          DLNA Information             //    
//////////////////////////////////////////

$dlna		=  trim(exec('sudo nas-service get_dlna enabled'));


//////////////////////////////////////////
//          E-mail Information          //    
//////////////////////////////////////////

// NS1
#$email			= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "EMAIL="|cut -d "=" -f 2'));

// NC1
$email			= trim(exec ('sudo nas-system get email_alert'));


//////////////////////////////////////////
//          FAN Information             //    
//////////////////////////////////////////

//$fan		= trim(exec('sudo cat /etc/sss_script/event/fan_info | grep "rpmmode:" |cut -d ":" -f 2'));
//$fan    = $fan." (".trim(exec('sudo cat /etc/sss_script/event/fan_info | grep "current_rpm:"|cut -d ":" -f 2')).")";
$fan	= trim(exec('sudo nas-system get fan'))." RPM";

//////////////////////////////////////////
//          Send Information             //    
//////////////////////////////////////////

echo "$host:$description:";
echo "$year:$month:$day:$time_entry[4]:$tz:";
//echo "$sys_version:$ftp:$ntp[0]:$email:$fan:";
echo "$sys_version:$vol_fstab:$ddns:$iscsi:$dlna:$email:$fan:";
	
/////////////////////////////////////////
//       Variable Description           //    
//////////////////////////////////////////
// $time_entry[4] : Time
// $tz            : Timezone
// $sys_version   : Current System Firmware Version
// $ntp[0]        : NTP Setting - Enabled or Disabled
// $ftp           : FTP Setting - Enabled or Disabled

?>




