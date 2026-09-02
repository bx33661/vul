<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
	$ret_str = "{ 'result' : 0 , 'message' : 'session out' }";
	echo $ret_str;
	return;
}
shell_exec("sudo rm /etc/sss_script/burn/odd_prog");
include "../inc/lcdmsg.php";

//=======================================================//
// LGE
// Park94
// 10/25/2008
// Blu-ray / Ripping : rip audio, rip dvd
//=======================================================//
$op_mode = $_POST['op_mode'];
//echo $op_mode;
//return;
/*if($op_mode=='burn_data_brn'){
	echo burn_data_brn();
	shell_exec("sudo /usr/local/bin/oddmngst -m tray");
	exit;
}*/

//=======================================================//
// INIT ODD TASK
//=======================================================//
$stat_file = '/etc/sss_script/burn/odd_stat';
if(!file_exists($stat_file))
{
	shell_exec("sudo mkdir 777 /etc/sss_script/burn; sudo touch '$stat_file'; sudo chmod 666 '$stat_file'");

	if(!file_exists($stat_file))
	{
		echo "ERROR:FAIL TO INIT\n";
		exit;
	}	
}/*else if(is_writable($prog_file))
{
	shell_exec("sudo chmod 666 $stat_file");
	if($ret = shell_exec("ls -l '$stat_file'"))
	{
		$tmp = substr($ret, 1, 6);
		echo $tmp;////
	}
}*/
if(!$fd_stat = fopen($stat_file,"w"))
{
	shell_exec("sudo chmod 666 $stat_file");
	if(!$fd_stat = fopen($stat_file,"w"))
	{
		echo "ERROR:FAIL TO OPEN STAT FILE\n";
		exit;
	}
}
$flag = "start:";
write_file($fd_stat, $flag);
//=======================================================//
// ODD CHECK
//=======================================================//
/*$ret = shell_exec('sudo oddmngst -m chk');
if(eregi('tray status : opened',$ret))
{
	echo "ERROR:TRAY OPENED\n";
	end_task($fd_stat);
	exit;
}else if(eregi('odd status : busy',$ret))
{
	echo "ERROR:BD IS BUSY\n";
	end_task($fd_stat);
	exit;
}
$ret = shell_exec('sudo oddacsrt -u web -a store -p /usr/local/bin/mopilt -i');
if(eregi('no disc in drive',$ret))
{
	echo "ERROR:NO DISC\n";
	end_task($fd_stat);
	exit;
}
$_tmp = explode("\n",$ret);
$_disc_info = array();
foreach($_tmp as $value){
	$__tmp = explode(':',$value);
	$_disc_info[trim($__tmp[0])] = trim($__tmp[1]);
}
*/
//=======================================================//
// INPUT FROM WEB
// op_mode : rip_audio, rip_dvd
//=======================================================//

//echo $op_mode;
//exit;
//=== ODD LOG ===//
$_log_file = '/etc/sss_script/burn/log';
$_time = date('Y-M-d H:i:s');
shell_exec("sudo rm $_log_file; sudo touch $_log_file; sudo chmod 666 $_log_file; sudo echo '[ODD_TASK] START , TIME : $_time' >> $_log_file");
switch($op_mode)
{
	case 'rip_audio':
		if($_disc_info['Media Type']!='cda'){
			echo "NG:NOT AUDIO CD\n";
			return;
		}
		shell_exec("sudo echo '[RIP_AUDIO] START' >> $_log_file");
		$cd_mode	= $_POST["mode"];
		$bit		= $_POST["bit"];
		$rate	= $_POST["rate"];
		$path	= $_POST["path"];
		$file_name= $_POST["filename"];
		$flag = "rip:";
		write_file($fd_stat, $flag);
		echo rip_audio($cd_mode,$bit,$rate,$path,$file_name);
		break;
	case 'rip_dvd':
		if(eregi('dvd',$_disc_info['Disc Type']))
		{
			if($_disc_info['Protected Disc']=='Yes')
			{
				echo "NG:PROTECTED DISC\n";
				exit;
			}
			$ret = shell_exec('sudo /usr/local/bin/dvdbackup -i /dev/sr0 -I');
			if(!eregi('dvd-video information',$ret))
			{
				echo "NG:NOT DVD TITLE\n";
				return;
			}
		}else{
			echo "NG:NOT DVD TITLE\n";
			return;
		}
		shell_exec("sudo echo '[RIP_DVD] START' >> $_log_file");
		$dvd_mode	=$_POST["mode"];
		$dvd_path	=$_POST["path"];
		$dvd_name=$_POST["titlename"];
		$flag = "rip:";
		write_file($fd_stat, $flag);
		echo rip_dvd($dvd_mode, $dvd_path, $dvd_name);
		break;
	case 'store_data':
		// Media check //
		if(preg_match("/cd[ax]/",$_disc_info['Media Type']) || eregi('blank',$infos['Disc Status']))
		{
			echo "NG:CDA/CDX/BLANK\n";
			exit;
		}else if(preg_match("/DVD/",$_disc_info['Disc Type']))
		{
			$ret = shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -I");
			if(preg_match("/DVD-Video information/",$ret))
			{
				echo "NG:DVD TITLE\n";
				exit;
			}
		}
		if(!shell_exec('sudo mount | grep /mnt/cdrom')){
			echo "NG:NOT DATA DISC\n";
			return;
		}
		
		// Destination directory check
		$path = $_POST['path'];
		$_result = preg_match('/\/mnt\/fs\/Vol\d/' , $path , $_matches);
		
		$_result = exec("sudo mount | grep '$_matches[0]'" , $_results);
		
		if(count($_results) == 0){
			echo "NG:No volume\n";
			return;
		}	
			
		shell_exec("sudo echo '\t[STORE_DATA] START' >> $_log_file");
		
		$flag = 'store:';
		write_file($fd_stat, $flag);
		shell_exec("sudo echo '\t[STORE_DATA] CALL STORE_DATA_TEST : DESTINATION => $path' >> $_log_file");
		//echo store_data_test($path);
		echo store_data($path);
		break;
	case 'store_image':
		// Check disc media type
		if(eregi('blank',$_disc_info['Disc Status']))
		{
			echo "NG:BLANK DISC\n";
			exit;
		}else if($_disc_info['Protected Disc']=='Yes')
		{
			echo "NG:PROTECTED DISC\n";
			exit;
		}
		
		// Destination directory check
		$path = $_POST['path'];
		$_result = preg_match('/\/mnt\/fs\/Vol\d/' , $path , $_matches);
		
		$_result = exec("sudo mount | grep '$_matches[0]'" , $_results);
		
		if(count($_results) == 0){
			echo "NG:No volume\n";
			return;
		}
		
		shell_exec("sudo echo '[STORE_IMAGE] START' >> $_log_file");
		$flag = 'store:';
		write_file($fd_stat, $flag);
		echo store_image($path);
		break;
	case 'burn_data':
		$pattern="/[+-]R[EWA]M?/";
		echo !eregi("blank",$_disc_info['Disc Status']) && !preg_match($pattern,$_disc_info['Disc Type']);
		if( !eregi("blank",$_disc_info['Disc Status']) && !preg_match($pattern,$_disc_info['Disc Type']))
		{
			echo "NG:NOT A WRITABLE DISC\n";
			return;
		}
		shell_exec("sudo echo '[BURN_DATA] START' >> $_log_file");
		$list = $_POST['list'];
		$vol_name = $_POST['vol_name'];
		echo burn_data($list, $vol_name, $fd_stat);
		shell_exec("sudo /usr/local/bin/oddmngst -m tray");
		break;
	case 'burn_data_img':
		$pattern="/[+-]R[EWA]M?/";

		
	
		/*if( !eregi("blank",$_disc_info['Disc Status']) && !preg_match($pattern,$_disc_info['Disc Type']))
		{
			echo "NG:NOT A WRITABLE DISC\n";
			return;
		}*/
		shell_exec("sudo echo '[BURN_DATA_IMG] START' >> $_log_file");
		$list = $_POST['list'];
		$vol_name = $_POST['vol_name'];
		echo burn_data_img($list, $vol_name, $fd_stat);
		break;
	case 'burn_data_brn':
		$pattern="/[+-]R[EWA]M?/";
		/*if( !eregi("blank",$_disc_info['Disc Status']) && !preg_match($pattern,$_disc_info['Disc Type']))
		{
			echo "NG:NOT A WRITABLE DISC\n";
			return;
		}*/
		shell_exec("sudo echo '[BURN_DATA_BRN] START' >> $_log_file");
		$vol_name = $_POST['vol_name'];
		echo burn_data_brn($vol_name);
		//shell_exec("sudo /usr/local/bin/oddmngst -m tray");
		break;
	case 'burn_image':
		$pattern="/[+-]R[EWA]M?/";
		if( !eregi("blank",$_disc_info['Disc Status']) && !preg_match($pattern,$_disc_info['Disc Type']))
		{
			echo "NG:NOT A WRITABLE DISC\n";
			return;
		}
		shell_exec("sudo echo '[BURN_IMAGE] START' >> $_log_file");
		$file = $_POST['filename'];
		$_file_ext = substr($file,-3);
		if($_file_ext == 'cue'){
			if(!preg_match('/(cd-r)|(cd-rw)/i',$_disc_info['Disc Type'])){
				echo "NG:Disc is not proper for burning a image file(cue, bin)\n";
				break;
			}
		}
		$flag = 'burn:';
		write_file($fd_stat, $flag);
		echo burn_image($file);
		//shell_exec("sudo /usr/local/bin/oddmngst -m tray"); // Once error occurred !
		break;
	case 'format_disc':
		echo format_disc();
		break;
	default:
		echo "ERROR:UNKNOWN OPERATION MODE\n";
		break;
}
end_task($fd_stat);
$_time = date('Y-M-d H:i:s');
shell_exec("sudo echo '[ODD_TASK] END , TIME : $_time' >> $_log_file");
exit;

//=======================================================//
// RIP AUDIO CD (CDA)
//=======================================================//
function rip_audio($cd_mode,$bit,$rate,$path,$file_name) // Convert to shell script for LCD messaging
{
	msgjob('add','Extracting Audio-CD...');
	$file_name = $path."/".$file_name;
	$ret = shell_exec("sudo /usr/local/bin/oddacsrt -u web -a rip -p /usr/local/bin/cdda2wav -D /dev/sr0 -H -B -silent-scsi -$cd_mode -b $bit -r $rate '$file_name'");
	msgjob('remove','Extracting Audio-CD...');
	shell_exec("sudo chmod -R 777 '$path'");
	if(eregi("odd task canceled",$ret))
	{
		msgjob('once','Cancel Audio-CD Extraction');
		return "NG:CANCELED AUDIO RIP\n";
	}else if(eregi('access denied',$ret))
	{
		msgjob('once','Denied Audio-CD Extraction');
		return "NG:ACCESS DENIED\n";
	}else if($ret)
	{
		// Same return value, complete and cancel
		return "EXCEPTION:COMPLETE OR CANCEL\n";
	}else if(!$ret)
	{
		return "WARNING:NO RETURN VALUE\n";
	}
	//return "WARNING:TIMEOUT\n";
}
//=======================================================//
// RIP DVD TITLE
//=======================================================//
function rip_dvd($dvd_mode, $dvd_path, $dvd_name) // Convert to shell script for LCD messaging
{
	msgjob('add','Extracting DVD-Title...');
	$ret = shell_exec("sudo oddacsrt -u web -a rip -p /usr/local/bin/dvdbackup -i /dev/sr0 -o '$dvd_path' -'$dvd_mode' -n '$dvd_name'");
	shell_exec("sudo chmod -R 777 '$dvd_path'");
	msgjob('remove','Extracting DVD-Title...');
	if(eregi("odd task canceled",$ret))
	{
		msgjob('once','Cancel DVD-Title Extraction');
		return "NG:CANCELED AUDIO RIP\n";
	}else if(eregi('access denied',$ret))
	{
		msgjob('once','Denied DVD-Title Extraction');
		return "NG:ACCESS DENIED\n";
	}else if(eregi('return value : 0',$ret)) // Complete message ?
	{
		msgjob('once','Complete DVD-Title Extraction');
		return "OK:COMPLETE\n";
	}else if(!$ret)
	{
		msgjob('once','Abnormal End DVD-Title Extraction');
		return "WARNING:NO RETURN VALUE\n";
	}
}
//=======================================================//
// STORE DATA IN DISC TO NAS
//=======================================================//
function store_data_test($path)
{
	global $_log_file;
	shell_exec("sudo echo '\t\t[STORE_DATA_TEST] START' >> $_log_file");
	if(chk_capacity_over("data_disc",$path)){
		return "NG:Disc capacity is larger than free capacity of NAS\n";
	}
	msgjob('add','Copying Disc...');
	// Limits the maximum execution time
	set_time_limit(0);
	
	$_file = '/mnt/fs/Vol1/system/Share/.tmp';
	$_cmd = "sudo nohup oddacsrt -u web -a store -p sleep 6000 1>'$_file' & echo $!";
	shell_exec("sudo rm '$_file'");
	$_pid = shell_exec($_cmd);
	
	$src = '/mnt/cdrom';
	shell_exec("sudo echo '\t\t[STORE_DATA_TEST] CALL COPY_FOLDER : SOURCE => $src , DESTINATION => $path' >> $_log_file");
	$ret = copy_folder($src,$path);
	$ret_str = $ret;	
	

	$cmd = "sudo /usr/local/bin/oddmngst -u web -a store -m ccl";
	$ret = shell_exec($cmd);
	if(!eregi('success to cancel process',$ret)){
		$ret_str .= "fail to cancel\npid:$_pid\n";
	}

	shell_exec("sudo echo '\t\t[STORE_DATA_TEST] END : RETURN => $ret_str' >> $_log_file");
	return $ret_str;
}
function store_data($path)
{
	/* New version 
	*/
	if(chk_capacity_over("data_disc",$path)){
		//return "NG:Disc capacity is larger than free capacity of NAS\n";
		return "NG:Disc size over\n";
	}
	$_src = '/mnt/cdrom';
	$_dst = $path;
	$_progfile = '/etc/sss_script/burn/odd_prog';
	$_cclfile = '/etc/sss_script/burn/odd_ccl';
	if(file_exists($_cclfile)){
		$_lines = file($_cclfile);
		if(trim($_lines[0]) == 'cancel'){
			return "NG:cancel\n";
		}
	}
	$_tmpfile = '/etc/sss_script/esata/.tmp';
	while(!file_exists($_tmpfile)){
		shell_exec("sudo touch '$_tmpfile' ; sudo chmod 666 '$_tmpfile'");
	}
	
	//msgjob('add','Copying Disc...');
	
	//$_srcrp = str_replace(" ","\ ",$_src);
	//$_dstrp = str_replace(" ","\ ",$_dst);
	shell_exec("sudo nohup /etc/sss_script/burn/store_data.sh '$_src' '$_dst' 1>'$_tmpfile' 2>&1 &");
	//shell_exec("sudo nohup oddacsrt -u web -a store -p /usr/bin/cmscopy -s '$_src' -d '$_dst' -p '$_progfile' -m '$_cclfile' 1>'$_tmpfile' 2>&1 &");
	//shell_exec("sudo nohup /usr/bin/cmscopy -s '$_src' -d '$_dst' -p '$_progfile' -m '$_cclfile' 1>'$_tmpfile' 2>&1 &");
	while(file_exists($_tmpfile)){
		shell_exec("sudo rm -f '$_tmpfile'");
	}
	return "OK:COMPLETE\n";
	
	/* Old version
	if(chk_capacity_over("data_disc",$path)){
		return "NG:Disc capacity is larger than free capacity of NAS\n";
	}
	msgjob('add','Copying Disc...');
	$_cmd = "sudo oddacsrt -u web -a store -p cp -d -r /mnt/cdrom/. '$path'";
	$ret=shell_exec($_cmd);
	$_ret = shell_exec("sudo chmod -R 777 '$path'");
	msgjob('remove','Copying Disc...');
	if(eregi('access denied',$ret))
	{
		msgjob('once','Denied Disc Copy');
		return "NG:ACCESS DENIED\n";
	}else if(eregi('odd task canceled',$ret))
	{
		msgjob('once','Cancel Disc Copy');
		return "NG:CANCELED\n";
	}else if(eregi('return value : 0',$ret))
	{
		msgjob('once','Complete Disc Copy');
		return "OK:COMPLETE\n";
	}else
	{
		msgjob('once','Abnormal End Disc Copy');
		return "WARNING:NO RETURN VALUE\n";
	}
	*/
}
//=======================================================//
// STORE IMAGE
//=======================================================//
function store_image($path)
{
	global $_log_file;
	shell_exec("sudo echo '[STORE_IMAGE] SAVE PATH : $path' >> $_log_file");
	shell_exec("sudo rm /etc/sss_script/burn/odd_prog");
	if(chk_capacity_over("disc",$path)){
		shell_exec("sudo echo '[STORE_IMAGE] NOT ENOUGH CAPACITY IN NAS : IMAGE SIZE OVER' >> $_log_file");
		return "NG:Disc capacity is larger than free capacity of NAS\n";
	}
	
	//msgjob('add','Backup Image...');
	$_tmp_file = '/etc/sss_script/burn/.tmp';
	while(!file_exists($_tmp_file)){
		shell_exec("sudo touch $_tmp_file; sudo chmod 666 $_tmp_file");
	}
	shell_exec("sudo echo '[STORE_IMAGE] START : MOPILT' >> $_log_file");
	//$ret = shell_exec("cd '$path'; sudo nohup oddacsrt -u web -a store -p /usr/local/bin/mopilt 1>/etc/sss_script/burn/.tmp 2>&1 &");
	$ret = shell_exec("sudo nohup /etc/sss_script/burn/store_img.sh '$path' 1>/etc/sss_script/burn/.tmp 2>&1 &");
	shell_exec("sudo echo '[STORE_IMAGE] WORKING IN BACKGROUND : MOPILT' >> $_log_file");
	//shell_exec("sudo chmod -R 777 '$path'");
	//msgjob('remove','Backup Image...');
	
	// Error code
	// Error number 89 : File Write Error
	preg_match("/error : \d+\b/i",$ret,$matches);
	preg_match("/\d+\b/",$matches[0],$numbers);
	/*$err_no = intval($numbers[0]);
	switch($err_no){
		case 89:
			msgjob('once','File Write Error');
			shell_exec("sudo echo '[STORE_IMAGE] MOPILT ERROR : FILE WRITE ERROR' >> $_log_file");
			return "NG:FILE WRITE ERROR\n";
			break;
		default:
			break;
	}*/
	
	
	if(eregi('access denied',$ret))
	{
		//msgjob('remove','Backup Image...');
		//msgjob('once','Denied Image Backup');
		shell_exec("sudo echo '[STORE_IMAGE] MOPILT ERROR : ODDMANAGER ACCESS DENIED' >> $_log_file");
		return "NG:ACCESS DENIED\n";
	}else if(eregi('odd task canceled',$ret))
	{
		//msgjob('remove','Backup Image...');
		//msgjob('once','Cancel Image Backup');
		shell_exec("sudo echo '[STORE_IMAGE] MOPILT ERROR : CANCELLED' >> $_log_file");
		return "NG:CANCELED\n";
	}else
	{
		//msgjob('once','Complete Image Backup');
		shell_exec("sudo echo '[STORE_IMAGE] MOPILT : SUCCESS' >> $_log_file");
		return "OK:COMPLETE\n";
	}
}
//=======================================================//
// Burn data disc
//=======================================================//
function burn_data_img($list, $vol_name, $fd_stat){
	
	// Make a list file
	if(empty($list))
	{
		return "ERROR:NO SELECTED FILE OR FOLDER\n";
	}
	exec("sudo echo 'list : $list' >> /home/phplog.txt");
	
	$root_path = '/mnt/disk';
	$list_file = '/var/www/run/burn_list';
	
	while(!file_exists($list_file)){
		shell_exec("sudo touch '$list_file'");
	}
	shell_exec("sudo chmod 666 '$list_file'");

	if(!$handle = fopen($list_file,'w+')) return "ERROR:FAIL TO MAKE BURN LIST\n";
	
	$lists = explode(":",$list);
	$cnt = count($lists);
	for($i=0;$i<$cnt;$i++)
	{

		$lists[$i] = urldecode($lists[$i]); //It's only for NC1

		$root_smb = explode("/",$lists[$i]);
		$current_path = get_currDirectory($root_smb[1]);

		if($root_smb[2] == '')
			$fullpath = $current_path;
		else
		{
			//$current_path = str_replace($root_smb[1], "", $current_path);			
			$name = $lists[$i];

			$position = strpos( $name , "/" );
			$post_path = substr($name, $position+1);
			
			$position = strpos( $post_path , "/" );
			$post_path = substr($post_path, $position);	

			exec("sudo echo juny2 : $post_path , $current_path >> /home/tmp.txt");

			$fullpath = $current_path.$post_path;			
		}
		
		exec("sudo echo last : $fullpath , $current_path >> /home/tmp.txt");
		if(is_dir($fullpath))
		{
			$tmp = explode("/",$lists[$i]);
			//var_dump($tmp);
			if($lists[$i][0]=="/"){
				$lists[$i] = substr($lists[$i],1);
			}
			//$tmp = "/".$tmp[count($tmp)-1]."/"."=".$root_path."/".$lists[$i]."\n";
			$tmp = "/".$tmp[count($tmp)-1]."/"."=".$fullpath."\n";		
			
		}
		else
		{
			$tmp = $fullpath."\n";	
		}
				
		fwrite($handle,$tmp);
	}
	fclose($handle);

	// Make a iso file
	$flag = 'burn:';
	write_file($fd_stat, $flag);
	return "OK:COMPLETE\n";
	
}

function burn_data_brn($vol_name){
	//juny
	global $_log_file;
	shell_exec("sudo echo '[BURN_DATA] BURN_DATA_BRN : START , VOLUME : $vol_name' >> $_log_file");
	
	//$list_file = '/mnt/fs/Vol1/.burn_list';
	$list_file = '/var/www/run/burn_list';

	//msgjob('add','Burning Disc...');
	shell_exec("sudo rm /etc/sss_script/burn/odd_prog");
	//$ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt '$img_file' /dev/sg4");	
	
	// Burning shell script
	//$ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt '$img_file' /dev/sg4");	
	$_tmp_file = '/etc/sss_script/burn/.tmp';
	while(!file_exists($_tmp_file)){
		shell_exec("sudo touch $_tmp_file; sudo chmod 666 $_tmp_file");
	}
	//$ret = shell_exec("sudo nohup oddacsrt -u web -a burn -p /usr/local/bin/motilt -o /dev/sg4 -vid '$vol_name' -gp -pl '$list_file' 1>/etc/sss_script/burn/.tmp 2>&1 &");

	$ret = shell_exec("sudo nas-storage odd_burn '$vol_name' '$list_file' 1>/etc/sss_script/burn/.tmp 2>&1 &");
	
	
	shell_exec("sudo echo '[BURN_DATA] MOTILT : BACKGROUND WORKING' >> $_log_file");
	//shell_exec("sudo rm -rf '$list_file' '$img_file'");
	
	if(eregi('access denied',$ret))
	{
		//msgjob('remove','Burning Disc...');
		//msgjob('once','Denied Disc Burn');
		return "NG:ACCESS DENIED\n";
	}/*else if(eregi('odd task canceled',$ret))
	{
		//msgjob('remove','Burning Disc...');
		//msgjob('once','Cancel Disc Burn');
		return "NG:CANCELED\n";
	}*/
	shell_exec("sudo echo '[BURN_DATA] BURN_DATA_BRN : END' >> $_log_file");
	return "OK:COMPLETE\n";
}
function burn_data($list, $vol_name, $fd_stat)
{
	if(empty($list))
	{
		return "ERROR:NO SELECTED FILE OR FOLDER\n";
	}
	
	$root_path = '/mnt/fs';
	$list_file = '/mnt/fs/Vol1/.burn_file.lst';
	if(!file_exists($list_file))
	{
		shell_exec("sudo touch '$list_file'");
		shell_exec("sudo chmod 666 '$list_file'");
	}else
	{
		shell_exec("sudo chmod 666 '$list_file'");
	}
	if(!$handle = fopen($list_file,'w+')) return "ERROR:FAIL TO MAKE BURN LIST\n";
	
	$lists = explode(":",$list);
	$cnt = count($lists);
	for($i=0;$i<$cnt;$i++)
	{
		$tmp = explode("/",$lists[$i]);
		//var_dump($tmp);
		$tmp = "/".$tmp[count($tmp)-1]."=".$root_path.$lists[$i]."\n";
		//echo $tmp;
		//exit;
		fwrite($handle,$tmp);
	}
	fclose($handle);
	
	if(shell_exec("sudo mount|grep /dev/sr0"))
	{
		$ret = shell_exec("sudo umount /dev/sr0");
	}
	$flag = 'burn:';
	write_file($fd_stat, $flag);
	
	//msgjob('add','Start Disc Burn');
	//$img_file = '/mnt/fs/Vol1/.burn.raw';
	//$ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/genisoimage -J -R -o '$img_file' -graft-points -path-list '$list_file' -V '$vol_name'");
	//msgjob('remove','Start Disc Burn');
	//if(eregi('access denied',$ret))
	//{
	//	//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsg 'Denied Disc Burn'"`);
	//	msgjob('once','Denied Disc Burn');
	//	return "NG:ACCESS DENIED(ISO)\n";
	//}else if(eregi('odd task canceled',$ret))
	//{
	//	//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsg 'Cancel Disc Burn'"`);
	//	msgjob('once','Cancel Disc Burn');
	//	return "NG:CANCELED(ISO)\n";
	//}else if(eregi('return value : 0',$ret))
	//{
	//	// Success, continue
	//}else
	//{
	//	//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsg 'Abnormal End Disc Burn'"`);
	//	msgjob('once','Abnormal End Disc Burn');
	//	return "WARNING:NO RETURN VALUE(ISO)\n";
	//}
	
	//$ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt '$img_file' /dev/sg4");
	msgjob('add','Start Disc Burn');
	$ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/motilt -o /dev/sg4 -gp -pl '$list_file' -vid '$vol_name'");
	msgjob('remove','Start Disc Burn');
	if(eregi('access denied',$ret))
	{
		msgjob('once','Denied Disc Burn');
		return "NG:ACCESS DENIED\n";
	}else if(eregi('odd task canceled',$ret))
	{
		msgjob('once','Cancel Disc Burn');
		return "NG:CANCELED\n";
	}else if(eregi('return value : 0',$ret))
	{
		msgjob('once','Complete Disc Burn');
		//return "OK:COMPLETE\n";
		// Success, continue
	}else
	{
		msgjob('once','Abnormal End Disc Burn');
		return "WARNING:NO RETURN VALUE\n";
	}
	
	shell_exec("sudo rm -rf '$list_file' '$img_file'");
	return "OK:COMPLETE\n";
}
//=======================================================//
// BURN IMAGE
//=======================================================//
function burn_image($file)
{
	global $_log_file;
	shell_exec("sudo echo '[BURN_IMAGE] BURN_IMAGE : START , IMAGE FILE : $file' >> $_log_file");
	
	$root_path = "/mnt/fs";
	$file = $root_path.$file;
	//msgjob('add','Burning Image...');
	shell_exec("sudo rm /etc/sss_script/burn/odd_prog");
	if(shell_exec("sudo mount|grep /dev/sr0"))
	{
		shell_exec("sudo umount /dev/sr0");
	}
	
	$_tmp_file = '/etc/sss_script/burn/.tmp';
	while(!file_exists($_tmp_file)){
		shell_exec("sudo touch $_tmp_file; sudo chmod 666 $_tmp_file");
	}
	
	
	//$ret = shell_exec("sudo nohup oddacsrt -u web -a burn -p /usr/local/bin/mosilt '$file' /dev/sg4 1>/etc/sss_script/burn/.tmp 2>&1 &");
	$ret = shell_exec("sudo nohup /etc/sss_script/burn/burn_img.sh '$file' 1>/etc/sss_script/burn/.tmp 2>&1 &");
	//$ret = shell_exec("sudo nohup /usr/local/apache/htdocs/en/php/burn_img.sh '$file' 1>/etc/sss_script/burn/.tmp 2>&1 &");
	shell_exec("sudo echo '[BURN_IMAGE] MOSILT : BACKGROUND WORKING' >> $_log_file");
	
	//msgjob('remove','Burning Image...');
	if(eregi('access denied',$ret))
	{
		//msgjob('remove','Burning Image...');
		//msgjob('once','Denied Image Burn');
		shell_exec("sudo echo '[BURN_IMAGE] MOSILT : ACCESS DENIED' >> $_log_file");
		return "NG:ACCESS DENIED\n";
	}else if(eregi('odd task canceled',$ret))
	{
		//msgjob('remove','Burning Image...');
		//msgjob('once','Cancel Image Burn');
		shell_exec("sudo echo '[BURN_IMAGE] MOSILT : CANCELLED' >> $_log_file");
		return "NG:CANCELED\n";
	}else
	{
		//msgjob('once','Complete Image Burn');
		return "OK:COMPLETE\n";
	}
}
//=======================================================//
// WRITE STAT TO FILE
//=======================================================//
function write_file($fd_stat, $flag)
{
	if(!fwrite($fd_stat, $flag))
	{
		echo "ERROR:FAIL TO WRITE STAT FILE\n";
		end_task($fd_stat);
		exit;
	}
}
//=======================================================//
// CLOSE ODD TASK
//=======================================================//
function end_task($fd_stat)
{
	$flag = "end:";
	if(!fwrite($fd_stat, $flag)) echo "ERROR:FAIL TO WRITE STAT FILE\n";
	fclose($fd_stat);
}

//=======================================================//
// Format disc
//=======================================================//
function format_disc(){
	$_ret = shell_exec("sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt -f");
	if(eregi("not formattable media",$_ret)){
		return "ng:not formattable media\n";
	}else if(eregi("format success",$_ret)){
		return "ok:success\n";
	}
	return $_ret;
}

//=======================================================//
// Capacity check : nas capacity and disc capacity
//=======================================================//


function chk_capacity_over($mode, $path){
	return false;
	switch($mode){
		case "disc":
			return chk_cap_disc($path);
			break;
		case "data_disc":
			return chk_cap_disc_data($path);
			break;
		case "capacity":
			return chk_cap($capacity);
			break;
		default:
			break;
	}
}
function chk_cap($capacity){
	
	$disc_cap = to_byte($capacity);
	
	$ret = shell_exec("sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain Vol1'");
	$nas_cap = floatval($ret);
	
	
	
	if($disc_cap < $nas_cap) return false;
	return true;
}
function chk_cap_disc_data($path){
	
	
	$ret = shell_exec('sudo du -s /mnt/cdrom');
	preg_match("/^\d+\b/",$ret,$matches);
	
	$disc_cap = floatval($matches[0])*1024;
	//$disc_cap = to_byte($_tmp);
	
	$ret = preg_match("/Vol\d/",$path,$matches);
	$vol = $matches[0];
	
	$ret = shell_exec("sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain ".$vol."'");
	$nas_cap = floatval($ret);

	
	return false;
	if($disc_cap < $nas_cap) return false;
	return true;
}

function chk_cap_disc($path){
	$ret = shell_exec('sudo oddacsrt -u web -a bd -p /usr/local/bin/mopilt -i');
	if(eregi('odd access denied at chk',$ret))
	{
		return "WARNING:BD IS BUSY\n";
	}else if(eregi('success to open scsi device',$ret))
	{
		$infos = explode("\n",$ret);
		foreach($infos as $value)
		{
			$info = explode(":",$value);
			if($info[1])
			{
				$infos[trim($info[0])] = trim($info[1]);
			}else if(eregi('no disc in drive',$value))
			{
				return "WARNING:NO DISC\n";
			}
		}
	}else
	{
		return "ERROR:DISC CHECK FAIL\n";
	}
	$_tmp = $infos['Disc Capacity'];
	$disc_cap = floatval($_tmp).substr($_tmp,-2,1);
	$disc_cap = to_byte($disc_cap);
	
	$ret = preg_match("/Vol\d/",$path,$matches);
	$vol = $matches[0];
	$ret = shell_exec("sudo sh -c '. /etc/sss_script/event/lib_vol && VOL_Remain ".$vol."'");
	$nas_cap = floatval($ret);
	
	if($disc_cap < $nas_cap) return false;
	return true;
}

//=======================================================//
// Convert unit
//=======================================================//
function human_readable($byte){
	$K = 1024;
	$M = 1048576;
	$G = 1073741824;
	$T = 1099511627776;
	$ret = '';
	
	if($byte<$K){
		$ret = $byte;
	}elseif($byte < $M){
		$ret = round($byte/$K,2).'K';
	}elseif($byte < $G){
		$ret = round($byte/$M,2).'M';
	}elseif($byte < $T){
		$ret = round($byte/$G,2).'G';
	}else{
		$ret = round($byte/$T,2).'T';
	}
	return $ret;
}
function to_byte($cap){
	$_unit = substr($cap,-1);
	$_cap = floatval($cap);
	$K = 1024;
	$M = 1048576;
	$G = 1073741824;
	$T = 1099511627776;
	if(eregi("k",$_unit)){
		$ret = $_cap * $K;
	}else if($_unit == "M"){
		$ret = $_cap * $M;
	}else if($_unit == "G"){
		$ret = $_cap * $G;
	}else if($_unit == "T"){
		$ret = $_cap * $T;
	}else{
		$ret = $_cap;
	}
	return $ret;
}

//=======================================================//
// Copy folder
//=======================================================//
function copy_folder($src,$dst){
	global $_log_file;
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] START' >> $_log_file");
	if(!chk_ccl()){
		shell_exec("sudo echo '\t\t\t[COPY_FOLDER] END : RETURN => CANCEL (CANCEL CHECK)' >> $_log_file");
		return "NG:cancel\n1";
	}
	
	// Total size of source [byte]
	$total_size = get_size($src);
	
	
	
	// Init progress file
	$prog_file = '/etc/sss_script/burn/odd_prog_php';
	$handle = fopen($prog_file,'w');
	$str = "start\n";
	$str .= '0/'.$total_size;
	fwrite($handle,$str);
	fclose($handle);
	
	
	
	// Copy
	if(substr($src,-1)=='/') $src = substr($src,0,-1);
	if(substr($dst,-1)=='/') $dst = substr($dst,0,-1);
	$res = dircopy($src,$dst,false);
	$_result = shell_exec("sudo chmod 777 $dst");
	if($res === 'cancel'){
		shell_exec("sudo echo '\t\t\t[COPY_FOLDER] END : RETURN => CANCEL (DIRCOPY CANCEL)' >> $_log_file");
		return "NG:cancel\n".$res;
	}
	if($total_size == $res){
		// Complete
		$handle = fopen($prog_file,'w');
		$str = "complete";
		fwrite($handle,$str);
		fclose($handle);
		
		msgjob('remove','Copying Disc...');
		msgjob('once','Complete Disc Copy');
		shell_exec("sudo echo '\t\t\t[COPY_FOLDER] END : RETURN => OK (SAME TOTAL SIZE)' >> $_log_file");
		return "OK:Complete\n";
	}
	// Complete
	$handle = fopen($prog_file,'w');
	$str = "complete";
	fwrite($handle,$str);
	fclose($handle);
	
	msgjob('remove','Copying Disc...');
	msgjob('once','Complete Disc Copy');
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] END : RETURN => OK (SMALLER SIZE THAN SOURCE)' >> $_log_file");
	return "OK:Complete\n";
	//return "OK:Partially copied\n".$res;
	
	
	
	
	
	
	
	//$src = '/mnt/cdrom';
	$src_len = strlen($src);
	//$dst = '/mnt/fs/Vol1/web/000';
	
	if(!exec("ls -lR $src",$output)){
		msgjob('remove','Copying Disc...');
		msgjob('once','Fail Disc Copy');
		return "NG:fail to listing\n";
	}
	$total_size = 0;
	$file_list = array();
	$pattern = "/([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)([^\s]+)(\s+)(.*)/";
	foreach($output as $value){
		// Directory
		if(eregi("/mnt/cdrom",$value)){
			$value = substr($value,$src_len,-1);
		
			if(substr($value,-1)!='/'){
				$value .= '/';
			}
			$tmp_path = $value;
			
			if(is_dir($dst.$tmp_path)){
				continue;
			}
			if(!mkdir($dst.$tmp_path)){
				msgjob('remove','Copying Disc...');
				msgjob('once','Fail Disc Copy');
				return "NG:fail to make dir\n";
			}
			continue;
		}
	
		// File
		preg_match_all($pattern,$value,$matches);
		
		$filename = $tmp_path.$matches[17][0];
		$filesize = floatval($matches[9][0]);
		
		if(substr($filename,-1)=='/') continue;
		$total_size += $filesize;
		$filelist[$filename] = $filesize;
	}

	$_lines = file($_ccl_file);
	if(eregi('cancel',$_lines[0])){
		msgjob('remove','Copying Disc...');
		msgjob('once','Cancel Disc Copy');
		return "NG:cancel\n";
	}
	
	
	$handle = fopen($prog_file,'w');
	$str = "start\n";
	$str .= '0/'.$total_size;
	fwrite($handle,$str);
	fclose($handle);
	
	$copied_size = 0;
	foreach($filelist as $_index => $_value){
		$_lines = file($_ccl_file);
		if(eregi('cancel',$_lines[0])){
			msgjob('remove','Copying Disc...');
			msgjob('once','Cancel Disc Copy');
			return "NG:cancel\n";
		}
		
		
		if(file_exists($dst.$_index)){
			$copied_size += floatval($_value);
			continue;
		}
		
		if(!copy($src.$_index,$dst.$_index)){
			msgjob('remove','Copying Disc...');
			msgjob('once','Fail Disc Copy');
			return "NG:fail to copy file\n";
		}
		$copied_size += floatval($_value);
		
		$handle = fopen($prog_file,'w');
	
		$str = "ing\n";
		$str .= $copied_size.'/'.$total_size;
		fwrite($handle,$str);
		fclose($handle);
		
		continue;
	}

	$handle = fopen($prog_file,'w');
	$str = "complete";
	fwrite($handle,$str);
	fclose($handle);
	
	msgjob('remove','Copying Disc...');
	msgjob('once','Complete Disc Copy');
	return "OK:Complete\n";
}



function dircopy($srcdir, $dstdir, $verbose) {
	global $_log_file;
	shell_exec("sudo echo '\t\t\t\t[DIRCOPY] START : SOURCE => $srcdir , DESTINATION => $dstdir' >> $_log_file");
	$prog_file = '/etc/sss_script/burn/odd_prog_php';
	
	if(!isset($offset)) $offset=0;
	$num = 0;
	$fail = 0;
	$sizetotal = 0;
	$fifail = '';
	if(!is_dir($dstdir)) mkdir($dstdir);
	if($curdir = opendir($srcdir)) {
		if(!chk_ccl()){
			shell_exec("sudo echo '\t\t\t\t[DIRCOPY] CANCEL A (OPENDIR)' >> $_log_file");
			if($verbose) echo "0\n";
			return 'cancel';
		}
		
		while($file = readdir($curdir)) {
			if($file != '.' && $file != '..') {
				$srcfile = $srcdir . '/' . $file;
				$dstfile = $dstdir . '/' . $file;
				if(is_file($srcfile)) {
					if(is_file($dstfile)){
						// compare modified time
						$ow = filemtime($srcfile) - filemtime($dstfile); 
						// compare file size
						$srcfile_size = floatval(sprintf("%u",filesize($srcfile)));
						$dstfile_size = floatval(sprintf("%u",filesize($dstfile)));
						$os = $srcfile_size - $dstfile_size;
					}else{
						$ow = 1;
						$os = 1;
					}
					if($ow > 0 && $os != 0) {
						
						
						
						if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
	
						// Detect file size & select to use copy or fwrite
						// Copy by spliting a file larger than 10MB
						if(filesize($srcfile)>10*1024*1024){
							if(!chk_ccl()){
								shell_exec("sudo echo '\t\t\t\t[DIRCOPY] CANCEL B (FILE LARGER THAN 10M)' >> $_log_file");
								if($verbose) echo "1\n";
								return 'cancel';
							}
							
							$fp_src = fopen($srcfile,'rb');
							$fp_dst = fopen($dstfile,'wb');
							
							while(!feof($fp_src)){
								if(!chk_ccl()){
									shell_exec("sudo echo '\t\t\t\t[DIRCOPY] CANCEL C (COPYING LARGER FILE)' >> $_log_file");
									if($verbose) echo "2\n";
									return 'cancel';
								}
								
								// Copy file
								fwrite($fp_dst, fread($fp_src,8192));
								
								// Write progress
								$tmp = file($prog_file);
								$tmp = $tmp[1];
								
								$arr = explode('/',$tmp);
								$tmp_total_size = floatval(trim($arr[1]));
								$tmp_cur_size = floatval(trim($arr[0]));
								$cur_size = $tmp_cur_size + 8192;
								
								$handle = fopen($prog_file,'w');
								$str = "ing\n";
								$str .= $cur_size.'/'.$tmp_total_size."\n";
								fwrite($handle,$str);
								fclose($handle);
								
							}
							$sizetotal+= floatval(sprintf("%u",filesize($dstfile)));
							fclose($fp_src);
							fclose($fp_dst);
						}else{
							if(!chk_ccl()){
								shell_exec("sudo echo '\t\t\t\t[DIRCOPY] CANCEL D (NORMAL FILE)' >> $_log_file");
								if($verbose) echo "3\n";
								return 'cancel';
							}
							
							
							if(copy($srcfile, $dstfile)) {
								touch($dstfile, filemtime($srcfile)); $num++;
								$sizetotal = $sizetotal + filesize($dstfile);
								
								// Write progress
								$tmp = file($prog_file);
								$tmp = $tmp[1];
								
								$arr = explode('/',$tmp);
								$tmp_total_size = floatval(trim($arr[1]));
								$tmp_cur_size = floatval(trim($arr[0]));
								$cur_size = $tmp_cur_size + filesize($dstfile);
								
								$handle = fopen($prog_file,'w');
								$str = "ing\n";
								$str .= $cur_size.'/'.$tmp_total_size."\n";
								fwrite($handle,$str);
								fclose($handle);
								
								$sizetotal += filesize($dstfile);
							}
							
							
							if(!chk_ccl()){
								shell_exec("sudo echo '\t\t\t\t[DIRCOPY] CANCEL E (AFTER COPY NORMAL FILE)' >> $_log_file");
								if($verbose) echo "4\n";
								return 'cancel';
							}
						}
						
	
						if($verbose) echo "OK\n";
					}
					else {
						shell_exec("sudo echo '\t\t\t\t[DIRCOPY] EXISTING SAME FILE : SOURCE FILE => $srcfile , DESTINATION FILE => $dstfile' >> $_log_file");
						if($verbose) echo "Warning: File '$srcfile' exists already!\nSame size, same modified time\n";
						$fail++;
						$fifail = $fifail.$srcfile."|";
						
						// Write progress
						$tmp = file($prog_file);
						$tmp = $tmp[1];
						
						$arr = explode('/',$tmp);
						$tmp_total_size = floatval(trim($arr[1]));
						$tmp_cur_size = floatval(trim($arr[0]));
						$cur_size = $tmp_cur_size + floatval(sprintf("%u",filesize($dstfile)));
						
						$handle = fopen($prog_file,'w');
						$str = "ing\n";
						$str .= $cur_size.'/'.$tmp_total_size."\n";
						fwrite($handle,$str);
						fclose($handle);
						
						$sizetotal += floatval(sprintf("%u",filesize($dstfile)));
					}
				} 
				else if(is_dir($srcfile)) {
					
					$ret = dircopy($srcfile, $dstfile, $verbose);
					if(!chk_ccl()){
						if($verbose) echo "5\n";
						return 'cancel';
					}
					$sizetotal += $ret;
				}    
			} 
			         
		}
		closedir($curdir);
	}
	
	shell_exec("sudo echo '\t\t\t\t[DIRCOPY] END : RETURN => $sizetotal' >> $_log_file");
	return $sizetotal;
}

function chk_ccl(){
	$_ccl_file = '/etc/sss_script/burn/odd_ccl';
	$_lines = file($_ccl_file);
	if(eregi('cancel',$_lines[0])){
		msgjob('remove','Copying Disc...');
		msgjob('once','Cancel Disc Copy');
		return false;
	}
	return true;
}


function get_size($path)
{
  if(!is_dir($path)) return sprintf("%u",filesize($path));
  if ($handle = opendir($path)) {
    $size = 0;
    while (false !== ($file = readdir($handle))) {
      if($file!='.' && $file!='..') {
        // function filesize has been deleted
        $size += get_size($path.'/'.$file);
      }
    }
    closedir($handle);
    return $size;
  }
}

function get_currDirectory($folder) {

		exec("sudo echo folder : $folder >> /home/tmp.txt");	

	//if ( $_SESSION['current_dir'] == "/" ) 
	//{
		//$current_path = $prefix_path.$post_path;
		//exec("sudo echo post_path : $post_path >> /home/tmp.txt");

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

		exec("sudo echo prefix_dir : $prefix_path >> /home/tmp.txt");	
		
		$position = strpos( $_post_path , "/" );
		$post_path = substr($_post_path, $position+1);
		$position = strpos( $post_path , "/" );
		$post_path = substr($post_path, $position);	
		
		$current_path = $prefix_path;
		
	//}
	return $current_path;	
}

?>
