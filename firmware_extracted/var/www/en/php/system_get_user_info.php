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


	$uid	= $_POST["key"];
	
	//Get User list
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user where uid='$uid'");
		$sth->execute();
		$users=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$num_user=sizeof($users);

	$user_list = $users[0][1].";";
	
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select folder from folder_member where rw='$uid' and attr='user'");
	$sth->execute();
	$rw_folders=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_rw_folder=sizeof($rw_folders);

	$rw_folder = $rw_folders[0][0]; 
	for($i = 1 ; $i < $num_rw_folder; $i++) {
		$rw_folder = $rw_folder.",".$rw_folders[$i][0]; 
	}
	$rw_folder = $rw_folder.";";
	

	//Get RO User lists
	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select folder from folder_member where ro='$uid' and attr='user'");
	$sth->execute();
	$ro_folders=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	
	$num_ro_folder=sizeof($ro_folders);

	$ro_folder = $ro_folders[0][0]; 
	for($i = 1 ; $i < $ro_folder; $i++) {
		$ro_folder = $ro_folder.",".$ro_folders[$i][0]; 
	}
	$ro_folder = $ro_folder.";";
	
	echo $user_list.$rw_folder.$ro_folder;
?>

