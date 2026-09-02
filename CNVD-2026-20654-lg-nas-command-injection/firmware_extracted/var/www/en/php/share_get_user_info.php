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


	$gid	= $_POST["key"];
	$mode	= $_POST["mode"];
	
	
if($mode=='FullList'){		
/*
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from group_info where gid='$gid'");
		$sth->execute();
		$group_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	
	$group_desc=$group_info[0][1];
*/
	//Get User list
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user");
		$sth->execute();
		$users=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$num_user=sizeof($users);

	$user_list = $users[0][0].";".$users[0][1].";".$users[0][2].";".$users[0][3].";".$users[0][4];
	for($i = 1 ; $i < $num_user; $i++) {
		$user_list = $user_list.":".$users[$i][0].";".$users[$i][1].";".$users[$i][2].";".$users[$i][3].";".$users[$i][4];
	}
	$user_list = $user_list.":";
	echo $user_list;



} else if ($mode == 'GroupMember'){
	//Get Group member list
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from group_user where gid='$gid'");
		$sth->execute();
		$members=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	//var_dump($members);
	$num_member=sizeof($members);
	

	$group_user_list = $members[0][1].";".$members[0][0];
	for($i = 1 ; $i < $num_member; $i++) {
		if ($members[$i][1] != '') $group_user_list = $group_user_list.":".$members[$i][1].";".$members[$i][0];
	}
	
	$group_user_list = $group_user_list.":";
	echo $group_user_list;
}else if ($mode == 'GroupDesc'){

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from group_info where gid='$gid'");
		$sth->execute();
		$group_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	
	$group_desc=$group_info[0][1];

}

?>

