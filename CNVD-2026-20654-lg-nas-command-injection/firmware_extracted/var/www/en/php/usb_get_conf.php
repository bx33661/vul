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
include 'browsing_common_func.php';


$type = $_POST['act'];
$ctrl_num= $_POST['ctrl_num'];

$type = trim($type);
$ctrl_num = trim($ctrl_num);

usb_backup_list($type, $ctrl_num);    

function usb_backup_list($type,$ctrl_num)
{
	if($type == "all")
		$usblist=trim(exec("sudo nas-storage get_usb_list $type"));
       else 
              $usblist=trim(exec("sudo nas-storage get_usb_list $type $ctrl_num"));


	//Encode usb destination
	$_tmp = explode("||",$usblist);
	//$dest = $_tmp[2];
	exec("sudo echo JUNY  $_tmp[2] >> /home/tmp.txt");

	$dest_tmp = explode("/mnt/disk/",$_tmp[2]);
	exec("sudo echo tmp2 : $dest_tmp[1] >> /home/tmp.txt");
	$dest = explode("/",$dest_tmp[1],2);
	$dest[1] = "dest:/".$dest[1];
	exec("sudo echo tmp3 : $dest[1] >> /home/tmp.txt");

	$dest = func_urlencode($dest[1]);

	$usblist = str_replace($_tmp[2], $dest, $usblist); //replace 'dest' by 'encoded dest' 

	echo $usblist;
		
}



?>
