<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
require_once "../php/msg_illegal_access.php";
die();
}


	$groupID	= $_POST["txtName"];
	$groupdesc	= $_POST["txtComment"];
	$MemberList	= $_POST["txtMember"];
	$SetupMode	= $_POST["txtMode"];

	
	$group_member = explode(";",$MemberList);
	$num_member = sizeof($group_member);

	//echo $groupID;
	//echo $groupdesc;
	//echo $num_member;
	//echo $SetupMode;


	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select * from group_info");
	$sth->execute();
	$groups=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_group=sizeof($groups);

if($SetupMode == 'add'){

	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from group_info");
		$sth->execute();
		$DB_group_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$num_DB_group=sizeof($DB_group_info);

	$ID_conflict = 'FALSE';
	for($i=0;$i<$num_DB_group;$i++){
		if(strtolower($groupID) == strtolower($DB_group_info[$i][0])) $ID_conflict = 'TRUE';
	}

	//echo $DB_group_info[0][0];
	if(($ID_conflict == 'TRUE') || (strtolower($groupID) == 'root') || (strtolower($groupID) == 'nobody') || (strtolower($groupID) == 'admin') || (strtolower($groupID) == 'lp')) { 
		echo "ok:ID_conflict";
	} else { 

	//group add
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into group_info values('$groupID','$groupdesc')");
	}
	catch(PDOException $e) {
		echo "DB insert err";
		die();
	}
		
	//group add to system 
	// NC1
	exec("sudo nas-share add_group $groupID");

	// NS1
	//exec("sudo /etc/sss_script/share/passwd.sh group add $groupID");
	
	//add users to group DB
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_member ; $i++){
		if($group_member[$i]!=''){
			$dbh->exec("insert into group_user values('$groupID','$group_member[$i]')");
		//add users to system group
			exec("sudo /bin/addgroup $group_member[$i] $groupID");
		}
		}
	}
	catch(PDOException $e) {
		echo "DB insert err";
		die();
	}
	
	echo "ok:group";
	}


}else if($SetupMode == "edit"){
//group edit
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from group_info where gid='$groupID'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from group_user where gid='$groupID'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	
	//delete group from system 
	// NC1
	exec("sudo nas-share del_group $groupID");

	// NS1
	//exec("sudo /etc/sss_script/share/passwd.sh group del $groupID");
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into group_info values('$groupID','$groupdesc')");
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}
	
	//group add to system 
	// NC1
	exec("sudo nas-share mod_group $groupID");

	// NS1
	//exec("sudo /etc/sss_script/share/passwd.sh group add $groupID");
	
	
	//add users to group
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_member ; $i++){
		if($group_member[$i]!=''){
			$dbh->exec("insert into group_user values('$groupID','$group_member[$i]')");
		//add users to system group
		exec("sudo /bin/addgroup $group_member[$i] $groupID");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	echo "ok:groupedit";

}else {
	
	$groups = explode(";",$groupID);
	$count = sizeof($groups);

	for($i = 0 ; $i < $count ; $i++){
	
		if($groups[$i]!=''){

	//delete group from userDB
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from group_info where gid='$groups[$i]'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	//delete user from group_userDB
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from group_user where gid='$groups[$i]'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	//delete user from folder_memberDB
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from folder_member where attr='group' and (ro='$groups[$i]' or rw='$groups[$i]' or noshare='$groups[$i]')");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}

	//delete group from system 
	// NC1
	exec("sudo nas-share del_group $groupID");

	// NS1
	//exec("sudo /etc/sss_script/share/passwd.sh group del $groups[$i]");
	}
	}
	echo "ok:groupdelete";
}



?>
