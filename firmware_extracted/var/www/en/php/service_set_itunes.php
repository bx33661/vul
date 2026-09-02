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


$daap		= $_POST['rdoDAAP'];
$daap_update	= $_POST['txtDAAPUpdate'];

// NC1
$daap_file = trim(exec('sudo nas-service get_itunes enabled'));
$rescan = trim(exec('sudo nas-service get_itunes rescan'));
if ($rescan == '0') {
	$daap_update_file = 'force';
} else {
	$daap_update_file = '5min';
}

if ($daap != $daap_file || $daap_update != $daap_update_file) {
	exec("sudo nas-service enable itunes $daap");
	if ($daap_update == 'force') {
		exec("sudo nas-service set_itunes rescan_interval 0");
	} else if ($daap_update == '5min') {
		exec("sudo nas-service set_itunes rescan_interval 300");
	}

	exec("sudo rm -f /var/cache/mt-daapd/songs3.db");

	sleep(1);

	exec("sudo nas-service control itunes restart");
	echo "iTunes Service Started";
} else {
	echo "";
}

// NS1
/*
$daap_file = trim(exec('sudo grep daap= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
$daap_update_file = trim(exec('sudo grep daap_update /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
$rescan = trim(exec('sudo grep rescan_interval= /etc/mt-daapd.conf'));
//echo $rescan;
if ($daap != $daap_file ){ 
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
}else if($daap == 'on'){
	
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
	
}else echo "";
*/

?>

