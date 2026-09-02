<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
require_once "../php/msg_illegal_access.php";
die();
}


//=======================================================//
// Information / Status
// (1) Network (2) Volume (3) Hard Disk (4) Blu-ray
// (5) USB (6) e-SATA
// (6) User Access Info.
//=======================================================//
$mode = $_POST["mode"];
echo $mode.";";

switch ($mode)
{
case "network":
	get_network_info();
	break;
case "volume":
	get_volume_info2();
	break;
case "hard_disk":
	get_hard_disk_info();
	break;
case "blu_ray":
	get_blu_ray_info();
	break;
case "usb":
	get_usb_info();
	break;
case "e_sata":
	get_e_sata_info();
	break;
case "user":
	if( $ret = get_user_info() ){
		echo $ret;
	}
	break;
default:
	echo "Mode error!\n";
	break;
}

//=======================================================//
// Functions for Status Information
//=======================================================//
function get_network_info()
{
	//================== New code ===================================//
	$net_device = 'eth0';
	$_net_info_by_dev = array();
	$_net_info_by_dev[$net_device]['dev_name'] = $net_device;
	
	#exec('dmesg |grep eth0: |tail -1 > /tmp/link_status');//NC1_KJS
	
	$_tmp = file('/var/run/link_status');
	foreach($_tmp as $_val){
		if($_res = preg_match_all('/('.$net_device.'):\s+(link)\s+(\w+),\s+(\w+)\s+(\w+),\s+(\w+)\s+(\d+\s+\w+)/', $_val, $_exploded)){
			$_net_info[trim($_exploded[2][0])] = trim($_exploded[3][0]);
			$_net_info[trim($_exploded[5][0])] = trim($_exploded[4][0]);
			$_net_info[trim($_exploded[6][0])] = trim($_exploded[7][0]);
		}
	}
	$_cmd = 'sudo ifconfig '.$net_device;
	$_res = exec($_cmd, $_results);
	if($_res){
		print_r(error_get_last());
		return;
	}
	$_line_num = count($_results);
	for($i=0; $i<$_line_num; $i+=9){
		$_res = preg_match('/^\w+\d*\b/',$_results[$i],$_exploded);
		if($_res){
			$_net_info['dev_name'] = $_exploded[0];
			for($j=0; $j<8; $j++){
				if(preg_match_all('/(HWaddr)\s+([0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2}:[0-9a-fA-F]{2})/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					continue;
				}
				if(preg_match_all('/(inet addr):(\d+\.\d+\.\d+\.\d+)\s+(Bcast):(\d+\.\d+\.\d+\.\d+)\s+(Mask):(\d+\.\d+\.\d+\.\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					$_net_info[trim($_tmp[3][0])] = trim($_tmp[4][0]);
					$_net_info[trim($_tmp[5][0])] = trim($_tmp[6][0]);
					continue;
				}
				if(preg_match_all('/(MTU):(\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					continue;
				}
				if(preg_match_all('/(RX packets):(\d+)\s+(errors):(\d+)\s+(dropped):(\d+)\s+(overruns):(\d+)\s+(frame):(\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					$_net_info['RX '.trim($_tmp[3][0])] = trim($_tmp[4][0]);
					$_net_info['RX '.trim($_tmp[5][0])] = trim($_tmp[6][0]);
					$_net_info['RX '.trim($_tmp[7][0])] = trim($_tmp[8][0]);
					$_net_info['RX '.trim($_tmp[9][0])] = trim($_tmp[10][0]);
					continue;
				}
				if(preg_match_all('/(TX packets):(\d+)\s+(errors):(\d+)\s+(dropped):(\d+)\s+(overruns):(\d+)\s+(carrier):(\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					$_net_info['TX '.trim($_tmp[3][0])] = trim($_tmp[4][0]);
					$_net_info['TX '.trim($_tmp[5][0])] = trim($_tmp[6][0]);
					$_net_info['TX '.trim($_tmp[7][0])] = trim($_tmp[8][0]);
					$_net_info['TX '.trim($_tmp[9][0])] = trim($_tmp[10][0]);
					continue;
				}
				if(preg_match_all('/(collisions):(\d+)\s+(txqueuelen):(\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					$_net_info[trim($_tmp[3][0])] = trim($_tmp[4][0]);
					continue;
				}
				if(preg_match_all('/(RX bytes):(\d+)\s+\(\d+\.\d+\s+\w+\)\s+(TX bytes):(\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					$_net_info[trim($_tmp[3][0])] = trim($_tmp[4][0]);
					continue;
				}
				if(preg_match_all('/(Interrupt):(\d+)/',$_results[$j],$_tmp)){
					$_net_info[trim($_tmp[1][0])] = trim($_tmp[2][0]);
					continue;
				}
			}
		}
	}
	$_net_info_by_dev[$net_device]['info'] = $_net_info;
	//print_r($_net_info_by_dev);
	
	
	//========================= Old code ======================================//
	// get the basic info from egiga0
	$cmd = 'sudo ifconfig eth0 > /etc/sss_script/network/ifconfig-egiga0';
	exec($cmd);

	$mac_addr	= rtrim(exec('sudo ifconfig eth0 | grep HWaddr | awk \'{print $5}\''));
	$ip_addr	= rtrim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f2 | awk \'{print $1}\''));

	// Find Subset in $buffer
	$subnet	= rtrim(exec('sudo ifconfig eth0 | grep \'inet addr:\'|cut -d: -f4'));
	$dns_pri	= rtrim(exec('sudo awk \'NR==1 {print $2}\' /etc/resolv.conf'));
	$dns_sec	= rtrim(exec('sudo awk \'NR==2 {print $2}\' /etc/resolv.conf'));
	$gateway	= rtrim(exec('sudo route -n| grep 0.0.0.0 | awk \'{print $2}\''));
	
	$mtu			= trim(exec('sudo ifconfig eth0 | grep MTU | awk \'{print $5}\' | cut -d ":" -f2'));
	$link_status	= trim(exec('sudo cat /etc/sss_script/network/link_status | grep eth0: | awk \'{print $3}\''));
	
	if ($link_status =="up,") {
		$duplex		= trim(exec('sudo cat /etc/sss_script/network/link_status  | grep eth0: | awk \'{print $4}\''));
		$speed_no		= trim(exec('sudo cat /etc/sss_script/network/link_status  | grep eth0: | awk \'{print $7}\''));
		$speed_scale	= trim(exec('sudo cat /etc/sss_script/network/link_status | grep eth0:| awk \'{print $8}\''));
		$speed 		= $speed_no." ".$speed_scale."  (".$duplex." duplex)";
	} else if($link_status == "eth0:"){
		$link_status	= trim(exec('sudo cat /etc/sss_script/network/link_status | grep eth0: | awk \'{print $5}\''));
		$duplex		= trim(exec('sudo cat /etc/sss_script/network/link_status  | grep eth0: | awk \'{print $6}\''));
		$speed_no		= trim(exec('sudo cat /etc/sss_script/network/link_status  | grep eth0: | awk \'{print $9}\''));
		$speed_scale	= trim(exec('sudo cat /etc/sss_script/network/link_status | grep eth0:| awk \'{print $10}\''));
		$speed 		= $speed_no." ".$speed_scale."  (".$duplex." duplex)";
	}else $speed = "link down";

	
	// Find Packet
	$packet_in	= trim(exec('sudo ifconfig eth0 | grep \'RX packets\' | cut -d: -f2 | awk \'{print $1}\''));
	$packet_in_err	= trim(exec('sudo ifconfig eth0 | grep \'RX packets\' | cut -d: -f3 | awk \'{print $1}\''));
	$packet_out	= trim(exec('sudo ifconfig eth0 | grep \'TX packets\' | cut -d: -f2 | awk \'{print $1}\''));
	$packet_out_err= trim(exec('sudo ifconfig eth0 | grep \'TX packets\' | cut -d: -f3 | awk \'{print $1}\''));
	
	
	//========================= New code ======================================//
	$mac_addr = $_net_info_by_dev[$net_device]['info']['HWaddr'];
	$ip_addr = $_net_info_by_dev[$net_device]['info']['inet addr'];
	$subnet = $_net_info_by_dev[$net_device]['info']['Mask'];
	//$gateway = ;
	//$dns_pri = ;
	//$dns_sec = ;
	$mtu = $_net_info_by_dev[$net_device]['info']['MTU'];
	if(eregi('up',$_net_info_by_dev[$net_device]['info']['link'])){
		$speed = $_net_info_by_dev[$net_device]['info']['speed'].' ('.$_net_info_by_dev[$net_device]['info']['duplex'].' duplex)';
	}else{
		$speed = 'Link Down';
	}
	
	//$speed=trim(exec('sudo nas-network get link_speed'));

	$packet_in = $_net_info_by_dev[$net_device]['info']['RX packets'];
	$packet_in_err = $_net_info_by_dev[$net_device]['info']['RX errors'];
	$packet_out = $_net_info_by_dev[$net_device]['info']['TX packets'];
	$packet_out_err = $_net_info_by_dev[$net_device]['info']['TX errors'];
	
	
	//========================= Old code ======================================//
	$ret = $mac_addr.";".$ip_addr.";".$subnet.";".$gateway.";".$dns_pri.";".$dns_sec.";".$mtu.";".$speed.";".$packet_in.";".$packet_in_err.";".$packet_out.";".$packet_out_err;
	echo $ret;
}
function get_volume_info2()
{
	$cmd_volume1 = "sudo df -h /mnt/disk/volume1";
	$cmd_volume2 = "sudo df -h /mnt/disk/volume2";

	$vol_num = exec("sudo nas-storage get vol_num");
	$vol_list = exec("sudo nas-storage get vol_list");

	$isActive = trim(exec("sudo cat /proc/mdstat | grep -B1 UU | grep md2"));
	if($isActive == '' )
		$active = 'degrade';
	else
		$active = 'active';

	if ( $vol_num == 0 ) {
		$str1_array[0] = lang_get('volume_17');
		$str1_array[1] = "-";
		$str1_array[2] = "-";
		$str1_array[3] = "-";
		$str1_array[4] = "-";
		return;
	}
	else if ( $vol_num == 1 ) { // linear, raid0, raid1
		if($vol_list == 'volume2') 
		{
			$str1 = trim(exec ($cmd_volume2));
			//$md_index = 'md3';
			$str1_array = preg_split("/[\s,]+/", $str1);	
			$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $2}\''));
			$str1_array[5] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $1}\''));
			
			//juny
			if($str1_array[0] == '')
			{
				$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));
				$str1_array[5] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $1}\''));
			}
		}
		else
		{
			$str1 = trim(exec ($cmd_volume1));
			//$md_index = 'md2';
			$str1_array = preg_split("/[\s,]+/", $str1);	
			$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));
			$str1_array[5] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $1}\''));
		}

		if($str1_array[0] == 'raid0' || $str1_array[0] == 'linear')
		{

			//To distinguish raid type between original linear and linear from individual  
			if( $str1_array[0] == 'linear')
			{
				$dev_list = exec("sudo cat /proc/mdstat | grep md2");
				$dev = explode(" ", $dev_list);
				$cnt = count($dev);
				if($cnt >= 6)
					$isPreIndividual = false;
				else
					$isPreIndividual = true;
			}

			$sys_area_destroy = trim(exec("sudo cat /proc/mdstat | grep -B1 UU | grep md1"));
			if($sys_area_destroy == '' && $isPreIndividual == false)
				$active = 'destroy';		
			else
				$active = 'active';								
		}	
				
		$percent1 = (int)$str1_array[4];
		echo "1:"."$vol_list".":".$str1_array[3].":".$active.":".$str1_array[2].":".$str1_array[1].":".$percent1."\n";
		return;		

	}
	else { // individual, linear+raid
		$str1 = trim(exec ($cmd_volume1));
		$str1_array = preg_split("/[\s,]+/", $str1);
		
		$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $2}\''));
		$str1_array[5] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $1}\''));

		$str2 = trim(exec ($cmd_volume2));
		$str2_array = preg_split("/[\s,]+/", $str2);
		
		$str2_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));		
		$str2_array[5] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $1}\''));


		//individual 
		if ( $str2_array[0] != "raid1" ) {
			$str1_array[0] = "No RAID";
			$str2_array[0] = "No RAID";

			$active1 = 'active';	
			$active2 = 'active';
		}
		//linear + raid
		else 
		{
			$sys_area_destroy_md2 = trim(exec("sudo cat /proc/mdstat | grep -B1 UU | grep md2"));
			$sys_area_destroy_md1 = trim(exec("sudo cat /proc/mdstat | grep -B1 UU | grep md1"));
			if($sys_area_destroy_md1 == '' || $sys_area_destroy_md2 == '')
			{
				//juny
				$active2 = 'degrade';
				$active1 = 'destroy';
			}
			else
			{
				$active1 = 'active';	
				$active2 = 'active';
			}
	
		}
		
		$percent1 = (int)$str1_array[4];
		$percent2 = (int)$str2_array[4];
		//echo "1:"."volume1".":".$str1_array[3].":".$active.":".$str1_array[2].":".$str1_array[1].":".$percent1." "."2:"."volume2".":".$str2_array[3].":".$str2_array[5].":".$str2_array[2].":".$str2_array[1].":".$percent2."\n";
		echo "1:"."volume1".":".$str1_array[3].":".$active1.":".$str1_array[2].":".$str1_array[1].":".$percent1." "."2:"."volume2".":".$str2_array[3].":".$active2.":".$str2_array[2].":".$str2_array[1].":".$percent2."\n";
		return;
		
	}

	
}

function get_volume_info()
{	
	$ret=shell_exec(`sudo nice -n -10 /etc/sss_script/disk/vollist.sh`);
	//$ret=shell_exec('sudo sh -c "cat /etc/sss_script/disk/vol_list"');	
	$_file = '/etc/sss_script/disk/vol_list';
	$_res = file($_file);
	$_list = array();
	$res = '';
	foreach($_res as $value){
		if($tmp){
			if($tmp==$value){
				continue;
			}
		}
		if(substr($value,-1) == "\n"){
			$res .= $value;
		}else{
			$res .= $value."\n";
		}
		$tmp = $value;
	}
	echo $res;
}

function get_hard_disk_info()
{
	$vol_num = exec("sudo nas-storage get vol_num");
	$vol_list = exec("sudo nas-storage get vol_list");

	$dev_list = exec("sudo nas-storage get dev_list HDD");
	$dev = explode(" ", $dev_list);
	$cnt = count($dev);
	if($cnt == 2)
	{
		$tmp = explode("/dev/",$dev[0]);
		$hdd = $tmp[1];
		$type = trim(exec("sudo nas-storage get vol_type $hdd"));
		if(eregi('HDD1',$type))
			$hdd1 = $hdd;
		else
			$hdd2 = $hdd;

		$tmp = explode("/dev/",$dev[1]);
		$hdd = $tmp[1];
		$type = trim(exec("sudo nas-storage get vol_type $hdd"));
		if(eregi('HDD2',$type))
			$hdd2 = $hdd;
		else
			$hdd1 = $hdd;
		
		$hdd_index = 'all';	
		$hdd1_size = trim(exec("sudo cat /sys/block/$hdd1/size")) * 512;	
		$hdd2_size = trim(exec("sudo cat /sys/block/$hdd2/size")) * 512;	

		$hdd1_vendor= trim(exec("sudo cat /sys/block/$hdd1/device/vendor"));
		$hdd2_vendor= trim(exec("sudo cat /sys/block/$hdd2/device/vendor"));	
		
		$hdd1_model= trim(exec("sudo cat /sys/block/$hdd1/device/model"));
		$hdd2_model= trim(exec("sudo cat /sys/block/$hdd2/device/model"));	

	}
	else
	{
		$tmp = explode("/dev/",$dev[0]);
		$hdd = $tmp[1];
		$type = trim(exec("sudo nas-storage get vol_type $hdd"));

		if(!eregi('HDD1',$type))
			$hdd_index = 'hdd2';
		else
			$hdd_index = 'hdd1';
			
		$size = trim(exec("sudo cat /sys/block/$hdd/size")) * 512;
		$vendor= trim(exec("sudo cat /sys/block/$hdd/device/vendor"));			
		$model= trim(exec("sudo cat /sys/block/$hdd/device/model"));	

	}


	if ( $vol_num == 0 ) {
		$str1_array[0] = lang_get('volume_17');
	}
	else if ( $vol_num == 1 ) { // linear, raid0, raid1
		if($vol_list == 'volume2') 
	        {
			//juny
			$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $2}\''));
			if($str1_array[0] == '')
				$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));
			
		}
		else
			$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));

	}
	else { // individual, linear+raid
		$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $2}\''));
		$str2_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));		
		
		if ( $str2_array[0] != "raid1" ) {
			$str1_array[0] = "No RAID";
			$str2_array[0] = "No RAID";
		}

		
	}

	if ( $hdd_index != 'all' ) {
		//Individual : Which volume is mounted?
		exec("sudo echo raid only : $str1_array[0], $str2_array[0]  >> /home/tmp.txt");

		if($hdd_index == 'hdd1')
			$Bay = Bay1;
		else
			$Bay = Bay2;
		$active = 'active';

		//exec("sudo echo Here4 >> /home/tmp.txt");	
		
		echo "$Bay $hdd1 $active $size $vendor $model\nBay2 none none 0\nBay3 none none 0 \nBay4 none none 0";
				
	}
	else {	

		//Individual , linear+raid1
		//if($str1_array[0] == "No RAID" || $str2_array[0] == "No RAID")
		if($vol_num == 2)
		{
			$vol1 = trim(exec("sudo df | grep volume1"));
			$vol2 = trim(exec("sudo df | grep volume2"));

			if($vol1 == '' && $vol2 != '')
			{
				$active1 = 'inactive';
				$active2 = 'active';
			}
			else if($vol1 !='' && $vol2 == '')
			{
				$active1 = 'active';
				$active2 = 'inactive';
			}
			else
			{
				$active1 = 'active';
				$active2 = 'active';	
			}
		}
		// Linear, raid0 , raid1  
		else if($vol_num == 1)
		{
			
			// Raid1
			if($str1_array[0] == "raid1")
			{			
				//Check whether raid structure is broken
				$isActive = trim(exec("sudo cat /proc/mdstat | grep -B1 UU | grep md2"));
				if($isActive != '')
				{
					$active1 = 'active';
					$active2 = 'active';	
				}
				else
				{
					$isActive = trim(exec("sudo cat /proc/mdstat | grep -B1 _U | grep md2"));
					if($isActive != '')
					{
						$active1 = 'inactive';
						$active2 = 'active';
					}
					else
					{
						$active1 = 'active';
						$active2 = 'inactive';
					}
				}
			}
			else 
			{
				if( $str1_array[0] == 'linear')
				{
					$dev_list = exec("sudo cat /proc/mdstat | grep md2");
					$dev = explode(" ", $dev_list);
					$cnt = count($dev);
					if($cnt >= 6)
						$isPreIndividual = false;
					else
						$isPreIndividual = true;
				}
				//Check which bay is volume1						
				exec("sudo nas-storage get_disk_info");
				$Disk1=trim(exec("sudo cat /var/run/scsi_list |grep DISK1 |cut -d' ' -f1"));	
				$Disk2=trim(exec("sudo cat /var/run/scsi_list |grep DISK2 |cut -d' ' -f1"));	
				
                                $vol1 = trim(exec("sudo df | grep md2"));
				$vol2 = trim(exec("sudo df | grep md3"));

				if($vol1 !='' && $isPreIndividual == true)
				{
					$tmp = trim(exec("sudo cat /proc/mdstat | grep md2 | grep $Disk1"));
					if($tmp !='')
					{
						$active1 = 'active';
						$active2 = 'inactive';
					}
					else
					{
						$active1 = 'inactive';
						$active2 = 'active';
					}
				}	
				else if($vol2 != '' && $isPreIndividual == true)
				{
					$tmp = trim(exec("sudo cat /proc/mdstat | grep md3 | grep $Disk2"));
					
					if($tmp !='')
					{
						$active2 = 'active';
						$active1 = 'inactive';
					}
					else
					{
						$active2 = 'inactive';
						$active1 = 'active';
					}
					

				}				
				else
				{
					$active1 = 'active';
					$active2 = 'active';
				}
				/*	
				if($vol_list == 'volume2' && $isPreIndividual == true) 
				{
					$active1 = 'inactive';
					$active2 = 'active';		
				}
				else if($vol_list == 'volume1' && $isPreIndividual == true)
				{
					$active1 = 'active';
					$active2 = 'inactive';	
				}
				else
				{
					$active1 = 'active';
					$active2 = 'active';
				}*/
			}
		}	
	
		
		echo "Bay1 $hdd1 $active1 $hdd1_size $hdd1_vendor $hdd1_model\nBay2 $hdd2 $active2 $hdd2_size $hdd2_vendor $hdd2_model\nBay3 none none 0 \nBay4 none none 0";
	}
	
}
function get_blu_ray_info()
{
	$ret=shell_exec("sudo cat /proc/scsi/scsi");
	$ret=explode("Host:",$ret);
	//print_r($ret);
	foreach($ret as $value)
	{
		if(ereg("CD-ROM",$value))
		{
			$cdrom=$value;
			break;
		}
	}
	//echo $cdrom;
	$ret=explode("\n",$cdrom);
	//print_r($ret);
	echo $ret[1];
}
function get_usb_info()
{
}
function get_e_sata_info()
{
/*
	$cmd="sudo /bin/cat /etc/sss_script/disk/scsi_list";
	$ret=shell_exec($cmd);
	//echo $ret;
	$tmp_arr=explode("\n",$ret);
	//print_r($tmp_arr);
	foreach($tmp_arr as $value)
	{
		if(ereg("ESATA",$value))
		{
			$tmp_arr=explode(" ",$value);
			$node=$tmp_arr[0];
			break;
		}
	}
	//echo "$node\n";
	$cmd="sudo /bin/cat /sys/block/".$node."/device/vendor";
	$vendor=trim(shell_exec($cmd));
	$cmd="sudo /bin/cat /sys/block/".$node."/device/model";
	$model=trim(shell_exec($cmd));
	$cmd="sudo /bin/cat /sys/block/".$node."/size";
	$size=(float)floatval(trim(shell_exec($cmd)))*512;
	echo "$vendor;$model;$size";
*/

       $MEM_LIST = "/var/run/nas-usb.list";
	$fd = fopen($MEM_LIST,"r");
	while(!feof($fd)) {
		$str = fgets($fd,256);
		$tmp = trim($str);	
		$tmp = explode(' ',$tmp);
		$USBDEV_INFO = $tmp[0];
		$USBDEV_PORT = $tmp[2];

		if($USBDEV_PORT != 'esata') continue;
		
		$USBDEV=exec("sudo echo $USBDEV_INFO | cut -d ' ' -f 1");
		$USBDEV_PARENT= exec("sudo basename $USBDEV | sed 's/[0-9]*$//'");

		$cmd="sudo cat /sys/block/".$USBDEV_PARENT."/device/vendor";
		$vendor=trim(shell_exec($cmd));
		$cmd="sudo cat /sys/block/".$USBDEV_PARENT."/device/model";
		$model=trim(shell_exec($cmd));
		$cmd="sudo cat /sys/block/".$USBDEV_PARENT."/size";
		$size=(float)floatval(trim(shell_exec($cmd)))*512;

		if($USBDEV_PORT == '') 
		{
			echo "\n";
			break;
		}		
		echo "$vendor;$model;$size";		
		echo "\n";
	}
	fclose($fd);


}


function get_user_info(){
	// No connected user case
	//$ret = "[]";
	//return $ret;
	
	$buffer	= array();
	// NC1
	exec("sudo nas-service get_samba access",$buffer);
	// NS1
	//exec("sudo /etc/sss_script/share/conn_status.sh samba",$buffer);
	$ret = "[ ";
	if($buffer==""){
		//$ret .= "]";
	}else{
		foreach($buffer as $value){
			$entry = explode(" ",$value);
			$entry[1] = str_replace('\\','\\\\',$entry[1]);
			$ret .= "{ service : 'samba' , id : '".$entry[1]."' , "."com_name : '".$entry[3]."' , "." ip : '".$entry[4]."' },";
		}
	}

	$ftp_entry = array();
	// NC1
	exec("sudo nas-service get_ftp access",$ftp_entry);
	// NS1
	//exec("sudo /etc/sss_script/share/conn_status.sh ftp",$ftp_entry);
	if($ftp_entry==""){
		$ret .= "]";
	}else{
		//print_r($ftp_entry);
		foreach($ftp_entry as $value){
			$entry = explode(" ",$value);
			$ret .= "{ service : 'ftp' , id : '".$entry[0]."' , "." ip : '".$entry[1]."' },";
		}
		$ret = substr($ret,0,-1);
		$ret .= "]";
	}
	
	//print_r($ftp_entry);
	return $ret;
}
?>
