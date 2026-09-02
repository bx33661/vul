<?php 
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ) exit('-99');

$service	= $_POST['txtService'];
$dyndns		= $_POST['rdoDynDNS'];
$dyndns_user	= $_POST['txtDynDNSUser'];
$dyndns_pass	= $_POST['txtDynDNSPass'];
//$dyndns_alias	= $dyndns_user.".lgnas.com";
$dyndns_alias	= $_POST['txtDomain'];
// NC1
$dyndns_file 		= trim(exec('sudo nas-service get_ddns $service enabled'));
$dyndns_user_file 	= trim(exec('sudo nas-service get_ddns $service username'));
$dyndns_pass_file 	= trim(exec('sudo nas-service get_ddns $service password'));
$dyndns_alias_file 	= trim(exec('sudo nas-service get_ddns $service alias'));
$network_status 	= trim(exec("sudo dig +time=1 +tries=1 ns.lgnas.com | grep timed"));
$mac_address 		= trim(exec('sudo nas-network get macaddress'));

$ddns_result = '';

if ($dyndns == 'on') {
	if($network_status == '') {
		exec("sudo nas-service set_ddns $service username $dyndns_user");
		exec("sudo nas-service set_ddns $service password $dyndns_pass");
		exec("sudo nas-service set_ddns $service alias $dyndns_alias");
		
		if($service == 'lgnas') {
			$ddns_result = trim(exec('sudo nas-service register_ddns add'));
			if( $ddns_result == 'ok') {
				exec('sudo nas-service enable ddns on $service');
				exec('sudo nas-service control ddns restart');			
			}
		}
		else {
			exec('sudo nas-service enable ddns on $service');
			exec('sudo nas-service control ddns start');
			sleep(10);
			$result = trim(exec("sudo cat /var/log/dyndns.log | grep E:"));

			if($result == '') $ddns_result = 'ok';
		}
		
		if ($ddns_result == 'ok') {
			exec('sudo nas-share gen_ftp_conf');
			exec('sudo nas-service control ftp restart');
			echo "ok:ddns_on:";
		}
		else if($ddns_result == 'id_fail'){
			echo "ok:id_fail:";
		} 
		else {
			exec('sudo nas-service control ddns stop');
			echo "ok:ddns_fail:";
		}
	} else {
		echo "ok:network_fail:";
	}
} else {
	if($service = 'lgnas')
		exec('sudo nas-service register_ddns remove');

	exec('sudo nas-service enable ddns off');
	exec('sudo nas-service control ddns stop');
	exec('sudo nas-share gen_ftp_conf');
	exec('sudo nas-service control ftp restart');
	echo "ok:ddns_off:";
}

// NS1
/*
$dyndns_file = trim(exec('sudo grep dyndns= /etc/sss_script/services/service.conf | cut -d "=" -f 2'));
$dyndns_user_file = trim(exec('sudo grep username /etc/ddnscli.conf | cut -d " " -f 2'));
$dyndns_pass_file = trim(exec('sudo grep password /etc/ddnscli.conf | cut -d " " -f 2'));
$dyndns_alias_file = trim(exec('sudo grep alias /etc/ddnscli.conf | cut -d " " -f 2'));
$network_status = trim(exec("sudo dig +time=1 +tries=1 ns.lgnas.com | grep timed"));
$mac_address = trim(exec('sudo ifconfig eth0 | grep HWaddr | cut -d " " -f 9'));

if($dyndns == 'on'){
	if($network_status == '')
		{
		//echo $network_status;
		$id_test = trim(exec("sudo /usr/local/bin/membercli --command=isuser --hostname=$dyndns_user.lgnas.com | tail -1"));
		//echo "INITIAL IDTEST $id_test";
		if($id_test == 'bad'){
			//id is not registered
			//if($dyndns_user_file != 'none') exec("sudo /usr/local/bin/membercli --command=userdel --userid=$dyndns_user_file --passwd=$dyndns_pass_file --hostname=$dyndns_user_file.lgnas.com");
 	
			$id_check = trim(exec("sudo /usr/local/bin/membercli --command=useradd --userid=$dyndns_user --passwd=$dyndns_pass --hostname=$dyndns_user.lgnas.com --devicetype=NS1 --model=N4B1 --ttl=300 --httpport=80 --myip=0.0.0.0 --mymac=$mac_address --ver=1.0 --desc=- | tail -1"));
			//echo "INITIAL ID CHECK $id_check";
			if($id_check != 'good'){
				//id create fail
				echo "ok:ddns_fail:";
			}else{
				//echo "ok:id_created";

				exec("sudo /etc/sss_script/services/replace.sh dyndns=$dyndns_file dyndns=$dyndns /etc/sss_script/services/service.conf"); 

				$dyndns_pass = "password ".$dyndns_pass_file.":password ".$dyndns_pass;
				exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_pass"); 
			
				$dyndns_user = "username ".$dyndns_user_file.":username ".$dyndns_user;
				exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_user"); 
			
				$dyndns_alias = "alias ".$dyndns_alias_file.":alias ".$dyndns_alias;
				exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_alias"); 
			
				exec("sudo /etc/init.d/ddns start"); 
				echo "ok:ddns_on:";
			}
	
		}else if($id_test == 'good')
		{
			//id is registered 
			$id_test = trim(exec("sudo /usr/local/bin/membercli --command=userdel --userid=$dyndns_user --passwd=$dyndns_pass --hostname=$dyndns_user.lgnas.com | tail -1"));
			//echo $id_test;
			if($id_test =='bad') echo "ok:id_fail:";
				else {
					$id_check = trim(exec("sudo /usr/local/bin/membercli --command=useradd --userid=$dyndns_user --passwd=$dyndns_pass --hostname=$dyndns_user.lgnas.com --devicetype=NS1 --model=N4B1 --ttl=300 --httpport=80 --myip=0.0.0.0 --mymac=$mac_address --ver=1.0 --desc=- | tail -1" ));
					//echo $id_check;
				
					exec("sudo /etc/sss_script/services/replace.sh dyndns=$dyndns_file dyndns=$dyndns /etc/sss_script/services/service.conf"); 

					$dyndns_pass = "password ".$dyndns_pass_file.":password ".$dyndns_pass;
					exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_pass"); 
			
					$dyndns_user = "username ".$dyndns_user_file.":username ".$dyndns_user;
					exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_user"); 
			
					$dyndns_alias = "alias ".$dyndns_alias_file.":alias ".$dyndns_alias;
					exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_alias"); 
			
					exec("sudo /etc/init.d/ddns start"); 
					echo "ok:ddns_on:";
					

				}
		}else echo "ok:ddns_fail:";
	}else echo "ok:network_fail:";

}else{

		$id_test = trim(exec("sudo /usr/local/bin/membercli --command=userdel --userid=$dyndns_user_file --passwd=$dyndns_pass_file --hostname=$dyndns_user_file.lgnas.com | tail -1"));
		
		exec("sudo /etc/sss_script/services/replace.sh dyndns=$dyndns_file dyndns=$dyndns /etc/sss_script/services/service.conf"); 

		$dyndns_user = "username ".$dyndns_user_file.":username none";
		$dyndns_pass = "password ".$dyndns_pass_file.":password none";
		$dyndns_alias = "alias ".$dyndns_alias_file.":alias none.lgnas.com";

		//echo "$dyndns_user;$dyndns_pass;$dyndns_alias";

		exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_user"); 
		exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_pass"); 
		exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_alias"); 

		exec("sudo /etc/init.d/ddns stop"); 
		echo "ok:ddns_off:";
}
*/

/*

if($ddns_id_status == 1){
	echo "here";
	if ($dyndns_user != $dyndns_user_file || $dyndns_pass != $dyndns_pass_file || $dyndns_alias != $dyndns_alias_file){
		
		exec("sudo /etc/sss_script/services/replace.sh dyndns_user=$dyndns_user_file dyndns_user=$dyndns_user /etc/sss_script/services/service.conf"); 

		$dyndns_user = "username ".$dyndns_user_file.":username ".$dyndns_user;
		$dyndns_pass = "password ".$dyndns_pass_file.":password ".$dyndns_pass;
		$dyndns_alias = "alias ".$dyndns_alias_file.":alias ".$dyndns_alias;

		exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_user"); 
		exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_pass"); 
		exec("sudo /etc/sss_script/services/chgcfg.sh $dyndns_alias"); 

		$dnsinfo_changed = 1;
	}

}

if ($dyndns != $dyndns_file ){  
		exec("sudo /etc/sss_script/services/replace.sh dyndns=$dyndns_file dyndns=$dyndns /etc/sss_script/services/service.conf"); 
		if ($dyndns == 'on'){
			exec("sudo /etc/init.d/ddns start"); 
		}else exec("sudo /etc/init.d/ddns stop");
	} else if ($dnsinfo_changed == 1) exec("sudo /etc/init.d/ddns start"); 
		else echo "ok_nochg";
	
	echo "\nDDNS is $dyndns.";
*/
?>
