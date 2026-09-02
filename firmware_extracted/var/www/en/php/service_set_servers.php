<?php 
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
//require_once "../php/msg_illegal_access.php";
echo '-99';
die();
}


$appletalk	= $_POST['rdoAFP'];
$ftp		= $_POST['rdoFTP'];
$ftp_port	= $_POST['txtFTP_PORT'];
$mode		= $_POST['txtMode'];
//$printer	= $_POST['rdoPrinter'];

// NC1
$appletalk_file	= trim(exec('sudo nas-service get_afp enabled'));
$ftp_file 	= trim(exec('sudo nas-service get_ftp enabled'));
$ftp_port_file 	= trim(exec('sudo nas-service get_ftp port'));

if ($mode == 'ftp') {
	if ($ftp != $ftp_file || $ftp_port != $ftp_port_file) { 
		exec("sudo nas-service enable ftp $ftp");
		exec("sudo nas-service set_ftp Port $ftp_port");
		exec("sudo nas-service control ftp restart");
	}
	echo "ftp";
} else if ($mode=='afp') {
	if ($appletalk != $appletalk_file ){ 
		exec("sudo nas-service enable afp $appletalk");
		exec("sudo nas-service control afp restart");
	}
	echo "afp";
}

// NS1
/*
$appletalk_file	= trim(exec('sudo grep appletalk /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
$ftp_file 	= trim(exec('sudo grep ftp= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
$ftp_port_file 	= trim(exec('sudo grep ftp_port= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
//$printer_file 	= trim(exec('sudo grep printer /etc/sss_script/services/service.conf | cut -d "=" -f 2'));


if ($mode == 'ftp' ){

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
   	
	echo "ftp";
}

if ($mode=='afp'){

if ($appletalk != $appletalk_file ){ 
	exec("sudo /etc/sss_script/services/replace.sh appletalk=$appletalk_file appletalk=$appletalk /etc/sss_script/services/service.conf");
	  if ($appletalk == 'on')
		{ 
			exec("sudo /etc/sss_script/share/query_share.sh config");
			exec("sudo /etc/init.d/atalk start");
		}
		else exec("sudo /etc/init.d/atalk stop");
}
	echo "afp";
}
*/
/*
if ($printer != $printer_file ){ 
	exec("sudo /etc/sss_script/services/replace.sh printer=$printer_file printer=$printer /etc/sss_script/services/service.conf");
	  if ($printer == 'on')
		{ 
			exec("sudo cp /etc/sss_script/share/smb_global_printer.conf /etc/sss_script/share/smb_global.conf");
			exec("sudo /usr/sbin/lpd");
			exec("sudo /etc/sss_script/share/query_share.sh");
		}
		else {
			exec("sudo cp /etc/sss_script/share/smb_global_general.conf /etc/sss_script/share/smb_global.conf");
			exec("sudo lprm all");
			exec("sudo killall lpd");
			exec("sudo /etc/sss_script/share/query_share.sh");
		}
}
*/
?>
