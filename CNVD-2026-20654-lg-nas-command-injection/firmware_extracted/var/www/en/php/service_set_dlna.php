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

$dlna	= $_POST['rdodlna'];
$path1    = $_POST['DLNA_PATH_1'];
$path2    = $_POST['DLNA_PATH_2'];

$curr= $_SESSION['current_dir'];
shell_exec("sudo echo junyjunyjuny : $path1 >> /home/tmp.txt");
if($path1 == '/service/DLNA')
{	
	$pre_path = exec("sudo nas-storage get vol_default");
	if(!eregi("/mnt/disk",$pre_path)){
		echo "NG:\n";
	}
	else
	{
		$tmp = explode("/",$pre_path);
		$full_path = $pre_path.$path1;				
	}
}
else
{
	//If $path1 is [/servce/DLNA] , Get [/mnt/disk/volume1/service]
	$folders = explode("/",$path1);
	$prefix_dir = get_prefix_Directory($folders[1]);		

	if($folders[2] != '')
	{			
		$tmp = explode("/",$path1,2);
		$filename = explode("/",$tmp[1],2);
		$full_path = $prefix_dir.'/'.$filename[1];
	}
	else
	{
		$full_path = $prefix_dir;
	}
}

// NC1
$dlna_file  = trim(exec('sudo nas-service get_dlna enabled'));
$stored_path = trim(exec('sudo nas-service get_dlna dlna_default_path'));

//if ($dlna != $dlna_file || $full_path != $stored_path) {
	exec("sudo nas-service enable dlna $dlna");
	if($dlna == 'on')
	{
		//Path change sequence : 1) dlna stop
		$res =exec("sudo nas-service control dlna stop");
		sleep(3);
		//Path change sequence : 2) set its paht		
		if(path1 != '')
			exec("sudo nas-service set_dlna dlna_user_path $full_path");
		else
			exec("sudo nas-service set_dlna dlna_default_path $full_path");
		sleep(3);
		//Path change sequence : 3) init dlna's db
		$res=exec("sudo nas-service init_dlna_db");
		sleep(3);
		//Path change sequence : 4) dlna restart
		$res =exec("sudo nas-service control dlna restart");
		sleep(1);
	}
	else
	{
		$res =exec("sudo nas-service control dlna stop");
	}
	//echo $res;
//}

echo "OK:$dlna\n";


function get_prefix_Directory($folder) {
	$prefix_path=$_SESSION['user_directory'];
	$post_path=$_SESSION['current_dir'];

	exec("sudo echo user_directory : $prefix_path >> /home/tmp.txt");
	exec("sudo echo current_dir : $post_path >> /home/tmp.txt");

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select path from folder_info where folder='$folder'");
		$sth->execute();
		$DB_folder_info1=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}

	$prefix_path=$DB_folder_info1[0][0];
	$_post_path=$_SESSION['current_dir'];
	//$_SESSION['prefix_dir'] = $prefix_path;

	exec("sudo echo prefix_dir : $prefix_path >> /home/tmp.txt");	
	
	$position = strpos( $_post_path , "/" );
	$post_path = substr($_post_path, $position+1);
	$position = strpos( $post_path , "/" );
	$post_path = substr($post_path, $position);	
	
	$current_path = $prefix_path;
   	
	exec("sudo echo path : $current_path >> /home/tmp.txt");		
	return $current_path;	
}


?>
