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

$host 		= trim(exec('sudo hostname'));
$description 	= trim(exec ('sudo cat /etc/samba/smb.conf | grep "server string ="|cut -d "=" -f 2'));

// NS1
//$bootproto	= trim(exec('sudo grep BOOTPROTO /etc/sss_script/network/ifcfg-egiga0 | cut -d "=" -f 2'));
// NC1
$bootproto	= trim(exec('sudo nas-network get method'));

//$ipaddr 	= trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));
$ipaddr 	= trim(exec('sudo nas-network get ipaddr '));


//$netmask 	= trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f4'));
$netmask 	= trim(exec('sudo nas-network get netmask '));

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
	$dns1 = trim($DNS1[1]);
	$dns2 = 'NULL';
}else {
	$DNS1 = $dns_entry[$dns_start-1];
	$DNS2 = $dns_entry[$dns_stop-1];
	$DNS1 = explode(" ",$DNS1);
	$DNS2 = explode(" ",$DNS2);
	$dns1 = trim($DNS1[1]);
	$dns2 = trim($DNS2[1]);
}

$mtu	  	= trim(exec('sudo ifconfig eth0 | grep MTU | awk \'{print $5}\' | cut -d ":" -f2'));

// NC1
$domain_type    = trim(exec('sudo nas-network get domain_type'));
$workgroup 	= trim(exec('sudo nas-network get workgroup'));
$domain 	= trim(exec('sudo nas-network get domain'));
$domain_user 	= trim(exec('sudo nas-network get domain_user'));
$domain_pass	= trim(exec('sudo nas-network get domain_pass'));

// NS1
//$domain_type    = trim(exec('sudo grep domain_type= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
//$workgroup 	= trim(exec('sudo grep workgroup= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
//$domain 	= trim(exec('sudo grep domain= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
//$domain_user 	= trim(exec('sudo grep domain_user= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
//$domain_pass	= trim(exec('sudo grep domain_pass= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));


echo "$host:$description:$bootproto:$ipaddr:$netmask:$gateway:$dns1:$dns2:$mtu:$domain_type:$workgroup:$domain:$domain_user:$domain_pass:";

?>




