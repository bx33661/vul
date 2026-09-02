<?php
include "../session/session_info.php";
session_save_path($session_save_dir);
session_start();


$mode = $_POST['mode'];
switch ($mode)
{
case "mount_point":
	echo get_mount_point();
	break;
case "mounted":
	echo get_esata_mounted();
	break;
case "usb_connect":
	
	//echo $_node;
	echo set_usb_to_esata();
	break;
case 'read_prog':
	echo read_prog();
	break;
default:
	break;
}
function read_prog(){
	$_prog_file = '/tmp/esata/esata_prog';
	$_ccl_file = '/tmp/esata/esata_ccl';
	$_prog_lines = @file($_prog_file);
	$_ccl_lines = @file($_ccl_file);
	$_prog = intval($_prog_lines[0]);
	$_ccl = trim($_ccl_lines[0]);
	if($_prog == 100){
		$_ret_arr = array('result' => '1' , 'message' => 'complete');
	}else if($_prog < 0){
		$_ret_arr = array('result' => '-2' , 'message' => $_prog);
	}else if($_ccl == 'cancel'){
		$_ret_arr = array('result' => '-1' , 'message' => 'cancel');
	}else{
		$_ret_arr = array('result' => '0' , 'message' => $_prog);
		return json_encode($_ret_arr);
	}
	//$_ret = shell_exec("sudo echo 0 > '$_prog_file'");
	return json_encode($_ret_arr);
}
function get_mount_point()
{
	//$cmd = "sudo cat /etc/sss_script/disk/scsi_list|grep esata";
	$cmd = 'sudo mount | grep eSATA';
	$ret = shell_exec($cmd);
	
	if(!$ret)
	{
		unset($_SESSION['esata_dir']);
		
		// Check SCSI list if devices are detected
		$_lines = file('/etc/sss_script/disk/scsi_list');
		foreach($_lines as $_val){
			if(preg_match('/(^[\w\d]+)\s+(ESATA)\s/',$_val,$_matches)){
				$_node = trim($_matches[1]);
				break;
			}
		}
		if(!$_node){
			exec("sudo echo 'No e-SATA1' >> /home/phplog.txt");
			return "No e-SATA";
		}else{
			// Check device partitions
			$_lines = file('/proc/partitions');
			foreach($_lines as $_val){
				if(preg_match("/$_node/",$_val,$_matches)){
					return 'Unknown format e-SATA';
				}
			}
			return 'Not formatted e-SATA';
		}
	}
	var_dump($_SESSION['esata_dir']);
	$tmp = explode(" ",$ret);
	preg_match("/\w+\d\b/",$tmp[0],$matches);
	$_node = $matches[0];
	
	$_mnt_point = trim($tmp[2]);
	
	$_SESSION['esata_dir'] = $_mnt_point;
	var_dump($_SESSION['esata_dir']);
	$_SESSION['current_dir_esata'] = "/";
	
	return $_mnt_point;
}	

function get_esata_mounted()
{
	$cmd = 'sudo mount | grep eSATA';
	$ret = shell_exec($cmd);
	
	if(!$ret)
	{
		unset($_SESSION['esata_dir']);
		return "NG:No e-SATA\n";
	}
	return "OK:mounted\n";
}	

function set_usb_to_esata(){
	//$_device = $_POST['device'];
	$_device = "mnt/device/USB";
	//shell_exec("sudo echo '$_SESSION['esata_dir']' >> /home/phplog.txt");
	
	$cmd = "sudo mount|grep $_device";
	$ret = shell_exec($cmd);
	if(!$ret)
	{
		unset($_SESSION['esata_dir']);
		return "No external device";
	}
	$tmp = explode(" ",$ret);
	$mnt_point = $tmp[2];
	$mnt_point = trim($mnt_point);
	
	$_SESSION['esata_dir'] = $mnt_point;
	$_SESSION['current_dir_esata'] = "/";
	
	return $mnt_point;
}
?>