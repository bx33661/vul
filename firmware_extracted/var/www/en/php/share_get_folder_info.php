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


	
	$CMD = $_POST["txtMode"];
	$folder = $_POST["folder"];
//	$folder = $_POST["txtMode"];
	//echo $CMD;
if($CMD == 'Domain'){
	$domain = trim(exec('sudo nas-network get domain_type'));
	$domain_name = trim(exec('sudo nas-network get workgroup'));
	echo $domain.";".$domain_name;

}else if($CMD == 'GetDomainUser'){
	$domain = trim(exec('sudo nas-network get domain_type'));
	$domain_name = trim(exec('sudo nas-network get workgroup'));
	if($domain == 'domain'){
		exec("sudo /usr/bin/wbinfo -u --domain=$domain_name",$domain_user);
		
		$num_domain_user = sizeof($domain_user);
		
		$domain_user_list =""; 
		
		for($i=0;$i<$num_domain_user;$i++) {
			$domain_user_list = $domain_user[$i].';'.$domain_user_list;
			//echo $domain_user[$i];
		}
		
		echo $domain_user_list;
		
	} else echo '';
}elseif($CMD == 'GetDomainGroup'){
	$domain = trim(exec('sudo nas-network get domain_type'));
	$domain_name = trim(exec('sudo nas-network get workgroup'));
	if($domain == 'domain'){
		
		exec("sudo /usr/bin/wbinfo -g --domain=$domain_name",$domain_group);
		
		$num_domain_group = sizeof($domain_group);
		
		
		for($i=0;$i<$num_domain_group;$i++) {
			$domain_group_list = $domain_group[$i].';'.$domain_group_list;
		}
		
		echo $domain_group_list;
		
	} else echo '';
}else if($CMD == 'Domain'){
	// NS1
	$domain = trim(exec('sudo nas-network get domain_type'));

	// NC1
	//$domain = trim(exec('sudo grep domain_type= /etc/sss_script/network/domain.conf | cut -d "=" -f 2'));
		echo $domain;
	
}else if($CMD == 'GetMaxVolume'){

	// NC1
	$max_volume = trim(exec('sudo nas-storage get vol_num'));
	$volume_list = trim(exec('sudo nas-storage get vol_list'));
	echo $max_volume." ".$volume_list;

	// NS1
	//$max_volume = trim(exec ('sudo sh -c df | grep /Vol | cut -d "%" -f 2 | cut -d "/" -f 4 | cut -d "l" -f 2 | sort | tail -1'));
	//echo $max_volume;

}else if($CMD == 'FolderFullList'){
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select * from folder_info");
	$sth->execute();
	$folders=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_folder=sizeof($folders);

	$folder_list = $folders[0][0].";".$folders[0][1].";".$folders[0][2].";".$folders[0][3].";".$folders[0][4].";".$folders[0][5].";".$folders[0][6].";".$folders[0][7].";".$folders[0][8].";".$folders[0][9];
	for($i = 1 ; $i < $num_folder; $i++) {
		$folder_list = $folder_list.":".$folders[$i][0].";".$folders[$i][1].";".$folders[$i][2].";".$folders[$i][3].";".$folders[$i][4].";".$folders[$i][5].";".$folders[$i][6].";".$folders[$i][7].";".$folders[$i][8].";".$folders[$i][9];
	}
	$folder_list = $folder_list.":";
	echo $folder_list;


}else if($CMD == 'LocalUserMember'){

	//echo $folder;
	//Get RW User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select rw from folder_member where folder='$folder' and attr='user'");
	$sth->execute();
	$rw_users=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_rw_user=sizeof($rw_users);

	$local_user_rw_member = $rw_users[0][0]; 
	for($i = 1 ; $i < $num_rw_user; $i++) {
		$local_user_rw_member = $local_user_rw_member.":".$rw_users[$i][0]; 
	}
	$local_user_rw_member = $local_user_rw_member.":";
	

	//Get RO User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select ro from folder_member where folder='$folder' and attr='user'");
	$sth->execute();
	$ro_users=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	
	$num_ro_user=sizeof($rw_users);

	$local_user_ro_member = $ro_users[0][0]; 
	for($i = 1 ; $i < $num_ro_user; $i++) {
		$local_user_ro_member = $local_user_ro_member.":".$ro_users[$i][0]; 
	}
	$local_user_ro_member = $local_user_ro_member.":";

	$local_user_member = $local_user_rw_member.";".$local_user_ro_member;
	
	echo $local_user_member;

}else if($CMD == 'LocalGroupMember'){
	
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select rw from folder_member where folder='$folder' and attr='group'");
	$sth->execute();
	$rw_groups=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_rw_group=sizeof($rw_groups);

	$local_group_rw_member = $rw_groups[0][0]; 
	for($i = 1 ; $i < $num_rw_group; $i++) {
		$local_group_rw_member = $local_group_rw_member.":".$rw_groups[$i][0]; 
	}
	$local_group_rw_member = $local_group_rw_member.":";
	

	//Get RO User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select ro from folder_member where folder='$folder' and attr='group'");
	$sth->execute();
	$ro_groups=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	
	$num_ro_group=sizeof($rw_groups);

	$local_group_ro_member = $ro_groups[0][0]; 
	for($i = 1 ; $i < $num_ro_group; $i++) {
		$local_group_ro_member = $local_group_ro_member.":".$ro_groups[$i][0]; 
	}
	$local_group_ro_member = $local_group_ro_member.":";

	$local_group_member = $local_group_rw_member.";".$local_group_ro_member;
	
	echo $local_group_member;
	

}else if($CMD == 'DomainUserMember'){

	//echo $folder;
	//Get RW User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select rw from folder_member where folder='$folder' and attr='Domainuser'");
	$sth->execute();
	$rw_users=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_rw_user=sizeof($rw_users);

	$domain_user_rw_member = $rw_users[0][0]; 
	for($i = 1 ; $i < $num_rw_user; $i++) {
		$domain_user_rw_member = $domain_user_rw_member.":".$rw_users[$i][0]; 
	}
	$domain_user_rw_member = $domain_user_rw_member.":";
	

	//Get RO User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select ro from folder_member where folder='$folder' and attr='Domainuser'");
	$sth->execute();
	$ro_users=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	
	$num_ro_user=sizeof($rw_users);

	$domain_user_ro_member = $ro_users[0][0]; 
	for($i = 1 ; $i < $num_ro_user; $i++) {
		$domain_user_ro_member = $domain_user_ro_member.":".$ro_users[$i][0]; 
	}
	$domain_user_ro_member = $domain_user_ro_member.":";

	$domain_user_member = $domain_user_rw_member.";".$domain_user_ro_member;
	
	echo $domain_user_member;

}else if($CMD == 'DomainGroupMember'){
	
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select rw from folder_member where folder='$folder' and attr='Domaingroup'");
	$sth->execute();
	$rw_groups=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_rw_group=sizeof($rw_groups);

	$domain_group_rw_member = $rw_groups[0][0]; 
	for($i = 1 ; $i < $num_rw_group; $i++) {
		$domain_group_rw_member = $domain_group_rw_member.":".$rw_groups[$i][0]; 
	}
	$domain_group_rw_member = $domain_group_rw_member.":";
	

	//Get RO User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select ro from folder_member where folder='$folder' and attr='Domaingroup'");
	$sth->execute();
	$ro_groups=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	
	$num_ro_group=sizeof($rw_groups);

	$domain_group_ro_member = $ro_groups[0][0]; 
	for($i = 1 ; $i < $num_ro_group; $i++) {
		$domain_group_ro_member = $domain_group_ro_member.":".$ro_groups[$i][0]; 
	}
	$domain_group_ro_member = $domain_group_ro_member.":";

	$domain_group_member = $domain_group_rw_member.";".$domain_group_ro_member;
	
	echo $domain_group_member;
	

}












	


























?>
