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



	$userID		= $_POST["txtUserID"];
	$userpasswd	= $_POST["txtUserPassword"];
    $userpw_changed  = $_POST["txtUserPasswordChanged"];
	$username	= $_POST["txtUserName"];
	$email		= $_POST["txtUserMail"];
	$desc		= $_POST["txtUserDesc"];
	$notification	= $_POST["chkMailNotification"];

	$SetupMode	= $_POST["txtMode"];

	//Using encryption passwd
	$userpasswd_plain = $userpasswd;
	if($userpw_changed == 'true')
		$userpasswd = trim(shell_exec("sudo nas-common md5 $userpasswd"));

/*
echo $userID	."</br>";
echo $userpasswd."</br>";
echo $username."</br>";
echo $email."</br>";
echo $desc."</br>";
echo $notification."</br>";
echo $SetupMode."</br>";
*/
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


if($SetupMode == "add"){
//user add
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user");
		$sth->execute();
		$DB_user_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$num_DB_user=sizeof($DB_user_info);

	//echo $DB_user_info[0][0];
	$ID_conflict = 'FALSE';
	for($i=0;$i<$num_DB_user;$i++){
		if(strtolower($userID) == strtolower($DB_user_info[$i][0])) $ID_conflict = 'TRUE';
	}

	// NS1
	//if(($ID_conflict == 'TRUE') || (strtolower($userID) == 'root') || (strtolower($userID) == 'nobody')) { 

	// NC1
	$check = trim(exec("sudo nas-share check_user $userID"));
	if ($check != 'ok') {
		$ID_conflict = 'TRUE';
	}

	if(($ID_conflict == 'TRUE') 
		|| (strtolower($userID) == 'root') 
		|| (strtolower($userID) == 'nobody')) { 
		echo "ok:ID_conflict";
	} else { 
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into user values('$userID','$userpasswd','$username','$email','$desc')");
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into group_user values('users','$userID')");
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}


	//register user on system 
	// NC1
	exec("sudo nas-share add_user $userID $userpasswd_plain");

	// NS1
	//exec("sudo /etc/sss_script/share/passwd.sh user add $userID $userpasswd");
	
	// user add to Samba
	//exec("sudo /usr/bin/smbpasswd -a $userID");
	//exec("echo -e '$userpasswd\n$userpasswd' | sudo /usr/bin/smbpasswd -a $userID");

	// user add to AFP
	//exec("sudo /usr/local/netatalk/bin/afppasswd -a $userID");
	//exec("echo -e '$userpasswd\n$userpasswd' | sudo /usr/local/netatalk/bin/afppasswd $userID");

	if($notification == 'on') {
		exec("sudo nas-system email_alert user $email $userID $userpasswd_plain");
	}
	echo "ok:user";
	}
	

}else if($SetupMode == "edit"){
//user edit
	//check DB to find out any information has been changed
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user where uid='$userID'");
		$sth->execute();
		$DB_user_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	
	$DB_passwd=$DB_user_info[0][1];
	$DB_full_name=$DB_user_info[0][2];
	$DB_email=$DB_user_info[0][3];
	$DB_user_desc=$DB_user_info[0][4];;

	if(($DB_full_name!=$username)||($DB_email!=$email)||($DB_user_desc!=$desc)||(($userpasswd!='**********')&&($userpasswd!=$DB_passwd)))
	{

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from user where uid='$userID'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into user values('$userID','$userpasswd','$username','$email','$desc')");
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	// NC1
	if(($userpasswd!='**********')&&($userpasswd!=$DB_passwd))
	{
		exec("sudo nas-share mod_user $userID $userpasswd_plain");
	}
	
	// NS1
	//modify system passwd 
	//exec("sudo /etc/sss_script/share/passwd.sh user add $userID $userpasswd");
	
	//modify samba passwd 
	//exec("echo -e '$userpasswd\n$userpasswd' | sudo /usr/bin/smbpasswd -a $userID");

	//modify afp passwd
	//exec("echo -e '$userpasswd\n$userpasswd' | sudo /usr/local/netatalk/bin/afppasswd $userID");
	}
	if($notification == 'on') {
		exec("sudo nas-system email_alert user $email $userID $userpasswd_plain");
	}
	
	// KHJ20091014
	// if admin user changes password in user info page of share category,
	// re-register the password string in the session
	if ( $userID == 'admin' && ($userpasswd != $DB_passwd) )
	{
		require_once ("../session/session_fileviewer.php");
		sm_save_pw_for_fileviewer($userpasswd_plain);
	}

	echo "ok:edit_done";

}else {
//delete user 
	
	$users = explode(";",$userID);
	$count = sizeof($users);

		
	for($i = 0 ; $i < $count ; $i++){
	//	echo $users[0];
	//delete user from userDB
	if($users[$i]!=''){
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from user where uid='$users[$i]'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}

	//delete user from group_userDB
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from group_user where uid='$users[$i]'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	//delete user from folder_memberDB
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from folder_member where attr='user' and (ro='$users[$i]' or rw='$users[$i]' or noshare='$users[$i]')");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	
	// NC1
	exec("sudo nas-share del_user $users[$i]");

	// NS1
	//delete user from samba
	//exec("sudo /usr/bin/smbpasswd -x $users[$i]");

	//delete user from afp and system 
	//exec("sudo /etc/sss_script/share/passwd.sh user del $users[$i]");
	}
		
	}
	echo "ok:delete_done";
}

	
?>
