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


$printer	= $_POST['rdoPrinter'];

// NC1
$printer_file 	= trim(exec('sudo nas-service get_printer enabled'));

if ($printer != $printer_file) {
	exec("sudo nas-service enable printer $printer");
	exec("sudo nas-share gen_samba_conf /etc/samba/smb.conf");
	exec("sudo nas-service config printer");
	exec("sudo nas-service control printer restart");
}

// NS1
/*
$printer_file 	= trim(exec('sudo grep printer /etc/sss_script/services/service.conf | cut -d "=" -f 2'));

echo "$printer";

if ($printer != $printer_file ){ 
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
*/
?>
