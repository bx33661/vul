<?php 

/*
$iscsi 	= trim(exec('sudo nas-service get_iscsi enabled'));
$chap     = trim(exec('sudo nas-service get_iscsi chap'));
echo "$iscsi $chap";
*/

$command = $_GET["COMMAND"];		
$iscsi_mode = $_GET["ISCSI_MODE"];	
		
if ($command=="GET_SYS_INFO") {		
	$host           = trim(exec('sudo hostname'));	
	$description    = trim(exec ('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));	
	$date           = exec("sudo date -I");	
	$date_entry     = explode("-",$date);	
	$year           = $date_entry[0];	
	$month          = $date_entry[1];	
	$day                    = $date_entry[2];	
	$date           = exec("sudo date -R");	
	$time_entry     = explode(" ",$date);	
	$tz=trim(exec('sudo nas-system get timezone'));	
	$sys_version = trim(exec ('sudo nas-firmware get version'));	
	$vol_fstab = trim(exec('sudo nas-storage get vol_fstab'));	
	$ddns           = trim(exec('sudo nas-service get_ddns enabled'));	
	$ddns_domain    = trim(exec('sudo nas-service get_ddns alias'));	
	$iscsi          = trim(exec('sudo nas-service get_iscsi enabled'));	
	$dlna           =  trim(exec('sudo nas-service get_dlna enabled'));	
	$email                  = trim(exec ('sudo nas-system get email_alert'));	
	$fan    = trim(exec('sudo nas-system get fan'))." RPM";	
	echo "OK:14:$host:$description:";	
	echo "$year:$month:$day:$time_entry[4]:$tz:";	
	echo "$sys_version:$vol_fstab:$ddns:$iscsi:$dlna:$email:$fan:";	
}		
else if ($command=="GET_ISCSI_INFO") {		
	$iscsi 	= trim(exec('sudo nas-service get_iscsi enabled'));
	$chap     = trim(exec('sudo nas-service get_iscsi chap'));	
		
	echo "OK:6:$iscsi:$chap";	
}		
else if ($command=="SET_ISCSI") {		
	if ( $iscsi_mode == "ON" ) {	
		shell_exec("sudo /etc/init.d/iscsi-target start");
		echo "OK:1:ISCSI_ON";
	}	
	else {	
    		shell_exec("sudo /etc/init.d/iscsi-target stop");
		echo "OK:1:ISCSI_OFF";
	}	
}		
else if ($command=="GET_SERVERS_INFO") {		
	$afp = trim(exec('sudo nas-service get_afp enabled'));	
	$ftp = trim(exec('sudo nas-service get_ftp enabled'));	
	$ftp_port = trim(exec('sudo nas-service get_ftp port'));	
		
	echo "OK:4:$ftp:$afp:$ftp_port";	
}			
else if ($command=="FTP") {		
   $ftp_on=trim(exec('sudo nas-service get_ftp enabled'));
   $ftp_port=trim(exec('sudo nas-service get_ftp port'));
   echo "$ftp_on:$ftp_port";	
}		
else {		
	echo "OK";	
}		
		
?>		

