<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
//include "../php/msg_illegal_access.php";
echo '-99';
die();
}
session_write_close();

include 'nas_comm.php';

$conf_file = "/tmp/usb_backup_selected_item";
if(!file_exists($conf_file))
{
	shell_exec("sudo touch '$conf_file'");
	shell_exec("sudo chmod 666 '$conf_file'");
	if(!file_exists($conf_file))
	{
		echo "ERROR:FAIL TO CREATE\n";
		exit;
	}
}

$act = $_POST['act'];
if($act == "delete")
	usb_backup_delete();
else
	usb_backup_save($conf_file);  

function usb_backup_save($conf_file)
{
	$act = $_POST['act'];
	
	// USB configuration items 	
	$name = $_POST['name'];
	$desc = $_POST['desc'];	
	$ctrlnum = $_POST['ctrlnum'];
	$dstpath = $_POST['dstpath'];

	$dstpath = urldecode($dstpath); //It's only for NC1
	//$desc = encode_filename($dstpath);	

	$usbatt = $_POST['usbatt'];
	$direc = $_POST['direc'];
	
	$curr= $_SESSION['current_dir'];

	shell_exec("sudo echo junyjunyjuny : $dstpath >> /home/tmp.txt");
	if($dstpath == '/service/backup/usb')
	{	
		$usb_pre_path = exec("sudo nas-storage get vol_default");
		if(!eregi("/mnt/disk",$usb_pre_path)){
			echo "NG:\n";
		}
		else
		{
			$tmp = explode("/",$usb_pre_path);
			$full_path = $usb_pre_path.$dstpath;				
		}
	}
	else
	{
		//If $dstpath is [/servce/DLNA] , Get [/mnt/disk/volume1/service]
		$folders = explode("/",$dstpath);
		$prefix_dir = get_prefix_Directory($folders[1]);		

		if($folders[2] != '')
		{			
			$tmp = explode("/",$dstpath,2);
			$filename = explode("/",$tmp[1],2);
			$full_path = $prefix_dir.'/'.$filename[1];
		}
		else
		{
			$full_path = $prefix_dir;
		}
	}

	shell_exec("sudo echo full : $full_path >> /home/tmp.txt");
	$fp = fopen($conf_file, "w");
	if($fp){
		$tmp = "name:".$name."\n"."description:".$desc."\n"."control_num:".$ctrlnum."\n"."dest:".$full_path."\n"."auto_sync:".$usbatt."\n"."method:".$direc."\n";
		fwrite($fp, $tmp);				
		fclose($fp);
	}
		
	if($act == "new")
		shell_exec("sudo nas-storage gen_usb_info create $ctrlnum $conf_file");
	else if($act == "modify")
		shell_exec("sudo nas-storage gen_usb_info edit $ctrlnum $conf_file");		

}


function usb_backup_delete()
{
	$ctrlnum = $_POST['ctrlnum'];
	shell_exec("sudo nas-storage gen_usb_info delete '$ctrlnum'");
}


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
