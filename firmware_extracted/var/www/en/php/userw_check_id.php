<?php
$txtUserID = $_POST['txtUserID'];

	//=======================================================//
	// Access DB for user information
	//=======================================================//
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user where uid='$txtUserID'");
		$sth->execute();
		$users=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	if(!$users)
	{
		echo "Not Duplicated";
	}
	else echo "Duplicated";
	