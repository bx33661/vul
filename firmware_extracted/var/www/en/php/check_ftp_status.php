<?php
//////////////////////////////////////////
//          FTP Information             //    
//////////////////////////////////////////

// NS1
//$ftp		= trim(exec('sudo grep ftp= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$ftp_port		= trim(exec('sudo grep ftp_port /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

// NS1
$ftp		= trim(exec('sudo nas-service get_ftp enabled'));
$ftp_port	= trim(exec('sudo nas-service get_ftp port'));

echo $ftp.":".$ftp_port;
?>
