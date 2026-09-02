<?php
//=======================================================//
// Session information
//=======================================================//
include "../session/session_info.php";

session_save_path($session_save_dir);
session_start();
$user_id = $_SESSION['username'];
//echo "user id:".$user_id;
$mode = $_POST['mode'];
switch($mode){
	case "user_info":
		$users = user_info($user_id);
		//print_r($users);
		$msg = "{ 'name':'".$users[0]['full_name']."' , 'id':'".$users[0]['uid']."' , 'email':'".$users[0]['email']."' , 'desc':'".$users[0]['desc']."' }";
		echo $msg;
		exit;
		break;
	case "set_info":
		// TO DO
		$name = $_POST['name'];
		$id = $_POST['id'];
		$pw = $_POST['pw'];
		$email = $_POST['email'];
		$desc = $_POST['desc'];
		echo set_user_info($id,$pw,$name,$email,$desc);
		exit;
		break;
	default:
		break;
}

function user_info($in_id){
	//=======================================================//
	// Access DB for user information
	//=======================================================//
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user where uid='$in_id'");
		$sth->execute();
		$users=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	return $users;
}
function set_user_info($userID,$userpasswd,$username,$email,$desc){
	//Using encryption passwd
	$userpasswd_plain = $userpasswd;
	$userpasswd = trim(shell_exec("sudo nas-common md5 $userpasswd"));

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

		//NC1
		//echo "ID:".$userID.", PW:".$userpasswd;
		exec("sudo nas-share mod_user $userID $userpasswd_plain");

		//NS1
		//modify system passwd 
		//exec("sudo /etc/sss_script/share/passwd.sh user add $userID $userpasswd");
		
		//modify samba passwd 
		//exec("echo -e '$userpasswd\n$userpasswd' | sudo /usr/bin/smbpasswd -a $userID");

		//modify afp passwd
		//exec("echo -e '$userpasswd\n$userpasswd' | sudo /usr/local/netatalk/bin/afppasswd $userID");
	}
	//if($notification == 'on') {
	//	exec("sudo /etc/sss_script/email/user-notification.sh user $email $userID $userpasswd");
	//}

	require_once ("../session/session_fileviewer.php");
	sm_save_pw_for_fileviewer($userpasswd_plain);
	return "ok:edit_done";
}
?>
