<?php

//=======================================================//
// Session Start
//=======================================================//
include "../session/session_info.php";
session_save_path($session_save_dir);
session_start();
//session_write_close();

//=======================================================//
// Directory Permission Setting
// 1. System share directories - rw
// 2. System directories - r
// 3. Mounted Volumes - r
//=======================================================//
$share_dirs = array(
				5 => array('/mnt/fs/Vol1/system/Share','/mnt/fs/Vol1/system/iTunes'),
				6 => array('/mnt/fs/Vol1/system/Backup/COPY',
						'/mnt/fs/Vol1/system/Backup/IMG',
						'/mnt/fs/Vol1/system/Backup/USB')
			);
$ro_dirs = array(
				4 => array('/mnt/fs/Vol1/system'),
				5 => array('/mnt/fs/Vol1/system/Backup')
			);
// Volumes
$ret = shell_exec("sudo mount | grep /mnt/fs/Vol");
$ret = preg_match_all('/\/mnt\/fs\/Vol\d/',$ret,$matches);
$vols = array();
foreach($matches[0] as $index => $value){
	$vols[$index] = $value;
}


//=======================================================//
// Set folder list for login user
// with full path of folder
//=======================================================//
// User
$_usr = $_SESSION['username'];
try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select gid from group_user where uid='$_usr'");
	$sth->execute();
	$_grps=$sth->fetchAll();
	$dbh=null;
}
catch(PDOException $e) {
	print "";
	die();
}
$usr_grps = array();
$_cGrps = 0;
foreach($_grps as $value){
	$usr_grps[] = $value[0];
	$_cGrps++;
}
$_cMax = $_cGrps+1;



// All web shared directories
try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select folder from folder_info");
	$sth->execute();
	$_dirs=$sth->fetchAll();
	$dbh=null;
}
catch(PDOException $e) {
	print "";
	die();
}
$web_dirs = array();
foreach($_dirs as $value){
	$web_dirs[] = trim($value[0]);
}



//permission
// Temp dirs for permission
$_rw_dirs = array();
$_ro_dirs = array();
foreach($web_dirs as $index => $value){
	$_cRW = 0;
	$_cRO = 0;
	$_fRW = false;
	$_fRO = false;
	
	// RW : User
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select folder from folder_member where attr='user' and rw='$_usr' and folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	if($_tmp){
		$_fRW = true;
		$_rw_dirs[] = $value;
		continue;
	}
	
	
	// RW : Group
	foreach($usr_grps as $_grp_value){
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select folder from folder_member where attr='group' and rw='$_grp_value' and folder='$value'");
			$sth->execute();
			$_tmp=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		if($_tmp){
			$_cRW++;
			break;
		}
	}
	/* New decision rule */
	if($_cRW>0){
		$_fRW = true;
		$_rw_dirs[] = $value;
		continue;
	}
	
	// RO : User
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select folder from folder_member where attr='user' and ro='$_usr' and folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	if($_tmp){
		$_fRO = true;
		$_ro_dirs[] = $value;
		continue;
	}
	
	
	// RO : Group
	foreach($usr_grps as $_grp_value){
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select folder from folder_member where attr='group' and ro='$_grp_value' and folder='$value'");
			$sth->execute();
			$_tmp=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		if($_tmp){
			$_cRO++;
			break;
		}
	}
	if($_cRO>0){
		$_fRO = true;
		$_ro_dirs[] = $value;
		continue;
	}	
}


// rw dirs : full path
$web_rw_dirs = array();
$web_ro_dirs = array();
foreach($_rw_dirs as $value){
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select path from folder_info where folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$web_rw_dirs[] = $_tmp[0][0];
}


// ro dirs : full path
foreach($_ro_dirs as $value){
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select path from folder_info where folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$web_ro_dirs[] = $_tmp[0][0];
}

$_SESSION['rw_dir'] = $web_rw_dirs;
$_SESSION['ro_dir'] = $web_ro_dirs;
$_SESSION['mount_dir'] = $vols;
$_SESSION['share_dir'] = array_merge($_SESSION['mount_dir'],$_SESSION['rw_dir'],$_SESSION['ro_dir']);
session_write_close();

header("Location: ../system/system.php");
return;
?> 