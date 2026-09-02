<?php
//=======================================================//
// Get Free Capacity of Device
// Mode : nas, esata
//=======================================================//
$mode = $_POST['mode'];

switch($mode){
	case 'nas':
		$ret = shell_exec("sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain Vol1'");
		$nas_cap = floatval($ret);
		echo $nas_cap;
	break;
	case 'esata':
		$_file = "/etc/sss_script/disk/scsi_list";
		$_arr = file($_file);
		foreach($_arr as $value){
			if(eregi("esata",$value)){
				//$ret = preg_match("^\w+\b",$value,$matches);
				//$_node = $matches[0];
				$_node = substr($value,0,3);
				break;
			}
		}
		$_cmd = "sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain ".$_node."'";
		$ret = shell_exec($_cmd);		
		$nas_cap = floatval($ret);
		echo $nas_cap;
	break;
	case 'ext':
		$_node = $_POST['node'];
		$_cmd = "sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain ".$_node."'";
		$ret = shell_exec($_cmd);		
		$nas_cap = floatval($ret);
		echo $nas_cap;
	default:
}

return;
?> 