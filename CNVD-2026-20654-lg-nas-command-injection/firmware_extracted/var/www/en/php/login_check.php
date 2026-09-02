<?php
$mode = $_POST['op_mode'];

switch($mode)
{
	case 'login':
		echo login();
		//echo " test";
		
		exit;
		break;
	default:
		break;
}
//=======================================================//
// Login
//=======================================================//
function login()
{
	//=======================================================//
	// Get user id & password
	//=======================================================//
	$in_id=$_POST['id']; 
	$in_pw=$_POST['password'];
	$in_mobile=$_POST['mobile'];

	$_ajxp_pw = $in_pw;
	
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
	if(!$users)
	{
		return "NG:NO USER\n";
	}
	//print_r($users);
	$_id = $users[0]['uid'];
	$_pw = $users[0]['passwd'];
	//=======================================================//
	// Compare
	//=======================================================//

	//Using encrytion password
	$in_pw = exec("sudo nas-common md5 $in_pw");
	
	
	if($in_id==$_id && $in_pw==$_pw)
	{
		// session establish
		include "../session/session_info.php";	
		
		//mobile
		if ( file_exists($session_save_dir_mobile) == FALSE ){
                	mkdir($session_save_dir_mobile, 0700);
                }
		
		if ( file_exists($session_save_dir) == FALSE ){
               		mkdir($session_save_dir, 0700);
		}
		
		if( $in_mobile == "true" ){
			session_save_path($session_save_dir_mobile);
		}
		else if( $in_mobile == "false" ){
			session_save_path($session_save_dir);
		}
		//mobile
		
		session_start();
		$new_session_id = session_id();
		//session_regenerate_id(TRUE);
		$_SESSION['username']=$in_id; // do not change session register order (KHJ20081111D comment)
		$_SESSION['id'] = $nas_login_id_test;

		//Store odd's type here just once using session variable
		$odd_type = trim(exec('sudo nas-system get odd_type'));
		$_SESSION['odd_type'] = $odd_type;
		$_SESSION['prefix_dir'] = "/mnt/disk";

		exec("sudo echo HERE : $nas_login_id_test >> /home/phplog1.txt");
		
		if (isset($_SESSION['count']) )
		{
			$_SESSION['count']++;
		}
		else
		{
			$_SESSION['count']=1;
		}

		require_once ("../session/session_fileviewer.php");
		sm_save_pw_for_fileviewer($_ajxp_pw);
		sm_save_uid_for_fileviewer(get_uid($in_id));
		sm_save_gid_for_fileviewer(get_gid($in_id));
		
		// double login check start KHJ20081111C
		// mobile directory		
		$d = dir($session_save_dir_mobile);
		while ( false !== ( $entry = $d->read() ) )
		{
			if ( (substr($entry, 0, 1) != '.') && (substr($entry, 0, 5) == 'sess_') )  // except ./.. folder // KHJ20081111E only check sess_* files
			{
				if ( $entry !== "sess_".$new_session_id )
				{
					$session_file = $session_save_dir_mobile.'/'.$entry;
					$oneline = file($session_file);
					$regexp = "/^username\|s:[0-9]{1,}\:\"".$in_id."\";/";

					if ( preg_match($regexp, $oneline[0]) ) 
					{
						//iphone
						$link_value = strstr( $oneline[0], "link_ram_val" );
						$pos = strpos( $link_value, ";" );
						$ram_val_length = $pos - 15;
						$link_value2 = substr( $link_value, 15, $ram_val_length );
						$link_filename = '../login/root'.$link_value2;
						if( file_exists( $link_filename ) == TRUE ){
							exec('sudo rm '.$link_filename );
						}
						//iphone
						unlink($session_file);
					}
				}
			}
		}
		
		// original directory
		$d = dir($session_save_dir); 
		while ( false !== ( $entry = $d->read() ) ) 
		{
			if ( (substr($entry, 0, 1) != '.') && (substr($entry, 0, 5) == 'sess_') )  // except ./.. folder // KHJ20081111E only check sess_* files
			{
				if ( $entry !== "sess_".$new_session_id )
				{
					$session_file = $session_save_dir.'/'.$entry;
					$oneline = file($session_file);
					$regexp = "/^username\|s:[0-9]{1,}\:\"".$in_id."\";/";

					if ( preg_match($regexp, $oneline[0]) )
					{
						unlink($session_file);
					}
				}
			}
		}
		session_regenerate_id(TRUE);
		// double login check end
			
		//=======================================================//
		// Set folder list for login user
		// with full path of folder
		//=======================================================//
		/*
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select folder from folder_member where rw='$in_id' and attr='user'");
			$sth->execute();
			$rw_folders=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		$rw_list = array();
		foreach($rw_folders as $value)
		{
			$folder_name = $value[0];
			try{
				$dbh=new PDO("sqlite:/etc/nas/db/share.db");
				$sth=$dbh->prepare("select path from folder_info where folder='$folder_name'");
				$sth->execute();
				$path_folders=$sth->fetchAll();
				$dbh=null;
			}
			catch(PDOException $e) {
				print "";
				die();
			}
			$rw_list[] = trim($path_folders[0][0]);
		}		
		
		
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select folder from folder_member where ro='$in_id' and attr='user'");
			$sth->execute();
			$ro_folders=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		$ro_list = array(); 
		foreach($ro_folders as $value)
		{
			$folder_name = $value[0];
			try{
				$dbh=new PDO("sqlite:/etc/nas/db/share.db");
				$sth=$dbh->prepare("select path from folder_info where folder='$folder_name'");
				$sth->execute();
				$path_folders=$sth->fetchAll();
				$dbh=null;
			}
			catch(PDOException $e) {
				print "";
				die();
			}
			$ro_list[] = trim($path_folders[0][0]);
		}
		
		$_SESSION['rw_dir'] = $rw_list;
		$_SESSION['ro_dir'] = $ro_list;
		*/
		//print_r($_SESSION['ro_dir']);
		//print_r($_SESSION['rw_dir']);
		//=======================================================//
		// Get Full Path of Dir
		//=======================================================//
		//$cnt = 0;
		//$rw_list_path = array();
		//foreach($rw_list as $folder_name){
		//	echo $folder_name;
		//	try{
		//		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		//		$sth=$dbh->prepare("select path from folder_info where folder='$folder_name'");
		//		$sth->execute();
		//		$path_folders=$sth->fetchAll();
		//		$dbh=null;
		//	}
		//	catch(PDOException $e) {
		//		print "";
		//		die();
		//	}
		//	var_dump($path_folders[0][0]);
		//	$rw_list_path[] = $path_folders[0][0];
		//	//$cnt++;
		//}
		//var_dump($rw_list_path);
		
		/*$folder_name = "aaa";
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select path from folder_info where folder='$folder_name'");
			$sth->execute();
			$path_folders=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		echo "gg";
		var_dump( $path_folders);
		return $path_folders[0][0];*/

		$list=get_rw_ro_dir_list($in_id);
		$_SESSION['rw_dir'] = $list['rwdir'];
		$_SESSION['ro_dir'] = $list['rodir'];
					
		//iphone
		$rootfile = "../login/root".$_SESSION['link_ram_val'];
		if( file_exists( $rootfile ) == TRUE ){
			exec('sudo rm '.$rootfile);
		}
		
		$_SESSION['link_ram_val'] = mt_rand();
		$_SESSION['page_loaded'] = "no";
		$_SESSION['page_loaded_refresh'] = "no";
		//iphone

		$visitorip = $_SERVER['REMOTE_ADDR'];
		$serverip = $_SERVER['SERVER_ADDR'];
		$netmask = trim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f4'));
	
		$visitorip_long = ip2long($visitorip); 
		$serverip_long = ip2long($serverip); 
		$netmask_long = ip2long($netmask); 
	
		if ( ($visitorip_long & $netmask_long) == ($serverip_long & $netmask_long) ) {
			return "OK1:RIGHT USER\n";			
		}
		else {
			return "OK2:RIGHT USER\n";			
		}				
	}else
	{
		return "NG:WRONG PASSWORD\n";
	}
}

//park94 09/22/09
//Desc.:get system uid & gid for Ajaxplorer
function get_uid($_usr_id){
	$_SYSTEM_PASSWD_FILE = "/etc/passwd";
	$_file_lines = file($_SYSTEM_PASSWD_FILE);
	foreach($_file_lines as $_lines){
		if ( strpos($_lines, $_usr_id) !== FALSE )
		{
			if ( preg_match("/^".$_usr_id.":\w+:(\d+):\d+:/",$_lines,$_matches) == 1)
			{
				$_uid = $_matches[1];
				return $_uid;
			}
		}
	}
	return false;
}
function get_gid($_usr_id){
	$_SYSTEM_GROUP_FILE = "/etc/group";
	$_SYSTEM_PASSWD_FILE = "/etc/passwd";
	$_gids = array();
	$_file_lines = file($_SYSTEM_PASSWD_FILE);
	foreach($_file_lines as $_lines){
		if ( strpos($_lines, $_usr_id) !== FALSE )
		{
			if ( preg_match("/^".$_usr_id.":\w+:\d+:(\d+):/",$_lines,$_matches) == 1)
			{
				$_gids[] = $_matches[1];
				break;
			}
		}
		
	}
	$_file_lines = file($_SYSTEM_GROUP_FILE);
	foreach($_file_lines as $_lines){
		if ( strpos($_lines, $_usr_id) !== FALSE )
		{
			if ( preg_match("/^(\w+):\w+:(\d+):(.+)/",$_lines,$_matches) == 1 )
			{
				$_usrs = explode(",",$_matches[3]);
				if(in_array($_usr_id,$_usrs))
				{
					$_gids[] = $_matches[2];
				}
			}
		}
	}
	$_gids = array_unique($_gids);
	return (count($_gids))? $_gids:false;
}

function get_rw_ro_dir_list($usr_id){
	$rw_list=array();
	$ro_list = array();

	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare( "select path from folder_info where folder in(select distinct(folder) from folder_member where rw in(select gid from group_user where uid='".$usr_id."') or rw='".$usr_id."') or acl='NO' order by folder" );
	$sth->execute();
	$tmp=$sth->fetchAll();
	//error_log("\n[log in]rw result=>".var_export($tmp,true),3,'/tmp/a');
	foreach($tmp as $val){
		$rw_list[]=$val['path'];
	}
	//error_log("\n[log in]rw list=>".var_export($rw_list,true),3,'/tmp/a');
	$sth=$dbh->prepare( "select path from folder_info where folder in(select distinct(folder) from folder_member where ro in(select gid from group_user where uid='".$usr_id."') or ro='".$usr_id."') order by folder" );
	$sth->execute();
	$tmp=$sth->fetchAll();
	//error_log("\n[log in]ro result=>".var_export($tmp,true),3,'/tmp/a');
	foreach($tmp as $val){
		$ro_list[]=$val['path'];
	}
	$ro_list=array_diff($ro_list,$rw_list);
	//error_log("\n[log in]ro list=>".var_export($ro_list,true),3,'/tmp/a');
	return(array('rwdir'=>$rw_list,'rodir'=>$ro_list));
}

?>
