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
	
	$group_list = $groups[0][0].";".$groups[0][1];
	for($i = 1 ; $i < $num_group; $i++) {
		$group_list = $group_list.":".$groups[$i][0].";".$groups[$i][1];
	}
	$group_list=$group_list.":";
	echo $group_list;

?>

