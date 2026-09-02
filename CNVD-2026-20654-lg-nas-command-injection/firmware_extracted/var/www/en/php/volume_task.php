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
$_tmpfile="/tmp/tmp";
$vol_task_cmd = $_POST["VOL_TASK_CMD"];
//$_SESSION['expired_time'] = 'ignored';

if ( $vol_task_cmd == "FORMAT" ) {
	$format_type = $_POST["FORMAT_TYPE"];
	$format_vol  = $_POST["FORMAT_VOL"];	
	$_node = '/dev/'.$format_vol;
	//shell_exec("sudo nohup mkfs.ext3 '$_node' 1>/tmp/tmp 2>&1 &");
	//shell_exec("sudo nohup /etc/sss_script/burn/store_data.sh '$_src' '$_dst' 1>'$_tmpfile' 2>&1 &");
	echo "OK".$format_type.$format_vol;		
}
else if ( $vol_task_cmd == "CHANGE" ) {
	//create,delete,add,remove,expand,migrate
	$vol_config_type = $_POST["VOL_CONFIG_TYPE"];
	$vol_format = $_POST["VOL_VOL1_FORMAT"];	
	#$vol_raid_size  = $_POST["VOL_RAID1_SIZE"];
	#$vol_raid_size  = $vol_raid_size * 1024;//$_POST["VOL_RAID1_SIZE"];
	#$vol_linear_size = $_POST["VOL_RAID0_SIZE"];	
	#$vol_linear_size  = $vol_linear_size * 1024;//$_POST["VOL_RAID1_SIZE"];
	
	$vol_linear_percent = $_POST["VOL_LINEAR_PERCENT"];
	
	$vol_raid_size = 0;
	$vol_linear_size = 0;

	$dev_list = exec("sudo nas-storage get dev_list HDD");
	$dev = explode(" ", $dev_list);
	$cnt = count($dev);
	if($cnt == 2)
	{
		$tmp1 = explode("/dev/",$dev[0]);
		$hdd1 = $tmp1[1];
		$tmp2 = explode("/dev/",$dev[1]);
		$hdd2 = $tmp2[1];
		$hdd_index = 'all';	
	}
	else
	{
		$tmp1 = explode("/dev/",$dev[0]);
		$hdd1 = $tmp1[1];
		$hdd2 = '';
		$hdd_index = '';
	}
	
  // kjs start
/*	$hdd1=0;
	$hdd2=0;
	
	$type = trim(exec('sudo nas-storage get vol_type sda'));
	if($type == 'ESATA')
	{
		$hdd1 = 'sdb';
		$hdd2 = 'sdc';
	}
	else
	{
		$hdd1 = 'sda';
		$hdd2 = 'sdb';	
	}
*/		
	$hdd1_size = trim(exec("sudo cat /sys/block/$hdd1/size"))/(1024*1024*2);
	$hdd2_size = trim(exec("sudo cat /sys/block/$hdd2/size"))/(1024*1024*2);

	if ( ($hdd1_size == 0)&&($hdd2_size == 0) ) {
		echo "fail1:Not_Two_HDD";
		return;
	}
	if( $vol_config_type != 'individual' ) {
		if ( $hdd1_size == 0 ) {
			echo "fail1:Not_Two_HDD";
			return;
		}
		else if ( $hdd2_size == 0 ) {
			echo "fail2:Not_Two_HDD";
			return;
		}	

		if ( $hdd1_size <= $hdd2_size ) 
			$hdd_size = $hdd1_size;
		else 
			$hdd_size = $hdd2_size;		

		
		# linear_size = (linear_rate*small_hdd_size)/(2-linear_rate)
		$vol_linear_size = (((float)((float)$vol_linear_percent/100))*$hdd_size)/(float)((float)2-((float)((float)$vol_linear_percent/100)));
		$vol_raid_size = (int)$hdd_size-(int)$vol_linear_size;
		$vol_raid_size = $vol_raid_size * 1024;	
		// kjs end
	}
	
	//shell_exec("sudo rm '$_tmp_file'");
	//juny
	$return_array = array();
	exec("sudo nohup nas-storage hddsetup '$vol_config_type' '$vol_format' '$vol_raid_size' '$vol_linear_size' 1>'$_tmpfile' 2>&1 ",&$return_array, &$return_val);

	exec("sudo echo 'volume return : $return_val' >> /home/phplog.txt");
        if ($return_val == '1') {
              $status = 'ok:Volume_Change:'.$vol_raid_size;;
        } else if ($return_val == '2' || $return_val =='3'|| $return_val =='4') {
        	$status = 'fail:Volume_Change';
	} 
	echo $status;

	//~juny
	//echo "OK".$vol_config_type.$vol_raid1_size.$vol_vol1_format.$vol_vol2_format;	
}
else if($vol_task_cmd == "VOLNAME") {  //juny
	$vol_name = $_POST["VOL_NAME"];
	
	$return_array = array();
	exec("sudo nas-share del_trashbox '$vol_name' ",&$return_array, &$return_val);
	
        if ($return_val == '0') {
        	echo "ok:del_trashbox";
        } else {
			echo "ng:del_trashbox";
	} 
	
}


?>
