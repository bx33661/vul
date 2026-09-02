<?php
//=======================================================//
// Get external device list via USB port
//=======================================================//
$dev = array("usb" => "Removable_USB");
$cmd_type = $_POST['dev_type'];
$dev_cnt = 0;

include 'nas_comm.php';
include 'browsing_common_func.php';

if($cmd_type=='usb_path')
{
	$usb_pre_path = exec("sudo nas-storage get vol_default");

	if(!eregi("/mnt/disk",$usb_pre_path)){

		$usb_path = '';
	}
	else
	{
		$tmp = explode("/",$usb_pre_path);
		$usb_path = '/service/backup/usb';

		$full_path = $usb_pre_path.'/service/backup/usb';		
		exec("sudo echo JUNY : $full_path >> /home/tmp.txt");

		//if(!is_dir($full_path)) mkdir($full_path);	
		if(!file_exists($full_path))
			shell_exec("sudo mkdir $full_path; sudo chmod -R 777 $full_path");
		
	}

	//Encode usb path
	$usb_path = func_urlencode($usb_path);
	
	echo $usb_path;
	return;
}

if($cmd_type == 'usb_new'){
	$_ret_arr = get_usb_dev();
	echo json_encode($_ret_arr);
	return;
}
if($cmd_type=="usb")
{
        $dev_cnt = 0;

	$usblist_folder = "/proc/scsi/";
	exec("sudo ls /proc/scsi/",$searchfolder);
	foreach($searchfolder as $value)
	{
		if(ereg("usb-storage",$value))
		{	
			exec("sudo echo here $value >> /home/phplog.txt");
			$folderExist = 1;
		}	
	}
	if($folderExist != 1) 
	{		
		echo "No USB device";
		return;
	}

       $USB_LIST = "/var/run/nas-usb.list";
	$fd = fopen($USB_LIST,"r");
	while(!feof($fd)) {
		$str = fgets($fd,256);
		$tmp = trim($str);	
		$tmp = explode(' ',$tmp);
		$USBDEV_INFO = $tmp[0];
		$USBDEV_PORT = $tmp[2];
		
		$USBDEV=exec("sudo echo $USBDEV_INFO | cut -d ' ' -f 1");
		$USBDEV_PARENT= exec("sudo basename $USBDEV | sed 's/[0-9]*$//'");

		$USBSERIAL = trim(shell_exec("sudo udevinfo --query=all -p /sys/block/$USBDEV_PARENT | grep 'ID_SERIAL=' | cut -d '=' -f 2"));// > /home/tmp.txt"));
		$USBVENDOR = trim(shell_exec("sudo udevinfo --query=all -p /sys/block/$USBDEV_PARENT | grep 'ID_VENDOR=' | cut -d '=' -f 2"));
		$USBMODEL = trim(shell_exec("sudo udevinfo --query=all -p /sys/block/$USBDEV_PARENT | grep 'ID_MODEL=' | cut -d '=' -f 2"));

		if($USBDEV_PORT == 'esata') continue;
		else if($USBDEV_PORT == '') 
		{
			echo "\n";
			break;
		}
		

		echo $USBDEV_PORT."|".$USBVENDOR."|".$USBMODEL;
		echo "\n";
	}
	fclose($fd);	

	
}

//=======================================================//
// Memory card list
//=======================================================//
//Check SCSI list
/*
$cmd = "sudo cat /etc/sss_script/disk/scsi_list";
$ret = shell_exec($cmd);
$test = explode("\n",$ret);
//print_r($test);
preg_match_all("/\bsd\w MemCard\b/",$ret,$matches);
//print_r($lists);
$lists = $matches[0];
foreach($lists as $key => $value)
{
	$lists[$key] = trim(substr($value,0,3));
	echo "$value|";
	//$cmd = "sudo /usr/bin/udevinfo -a -n /dev/".$lists[$key];
	$cmd = "sudo /usr/bin/udevinfo -a -p /block/".$lists[$key];
	$ret = shell_exec($cmd);
	$ret = explode("\n\n",$ret);
	//print_r($ret);
	//echo $ret[6];
	
	$pattern = array("/\bATTRS{product}==\"\w*\b/",
	"/\bATTRS{manufacturer}==\"(\w*\s?)+/");
	preg_match($pattern[0],$ret[6],$matches);
	//print_r($matches);
	$tmp = explode("==\"",$matches[0]);
	//print_r($tmp);
	$product = $tmp[1];
	echo $product."|";
	
	preg_match($pattern[1],$ret[6],$matches);
	//print_r($matches);
	$tmp = explode("==\"",$matches[0]);
	//print_r($tmp);
	$manufacturer = $tmp[1];
	echo trim($manufacturer);
	echo "\n";
	$dev_cnt++;
}
if(!$dev_cnt) echo "No USB device";
*/

function get_usb_dev(){
	//juny : Get USB PATH
	$_key = "mnt/device/USB";//'Removable'; //juny : 090323
	exec("sudo mount | grep '$_key'",$_matches);
	exec("sudo echo 'get_usb_dev'>> /home/phplog.txt");
	$_dev_list = array();
	foreach($_matches as $_val){
		$_res = preg_match_all("/^\/dev\/(.+)\s+on\s+\/mnt\/(.+)\s+type/",$_val,$_exploded);
		$_node = $_exploded[1][0];
		$_path = $_exploded[2][0];
		//$_res = preg_match_all("/^Removable_(.+)_Vol\d*/",trim($_path),$_exploded);
		//$_res = preg_match_all("/^usb\d*/",trim($_path),$_exploded);

		//$_dev = $_exploded[1][0];
		$_dev = "USB1";
		exec("sudo echo 'node => $_node  path => $_path'>> /home/phplog.txt");		
		//$_dev_list[$_dev] = array('node' => $_node , 'path' => $_path , 'device' => $_dev);
		if($_dev == 'esata') continue;
		$_dev_list[] = array('node' => $_node , 'path' => $_path , 'device' => $_dev);
	}
	//$_dev_list['count'] = count($_dev_list);
	return $_dev_list;
	
}
?>
