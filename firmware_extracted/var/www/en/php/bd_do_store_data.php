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
	shell_exec("sudo touch '$stat_file'");
	shell_exec("sudo chmod 666 '$stat_file'");
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
$ret = shell_exec('sudo oddmngst -m chk');
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
	case 'store_data':
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
		shell_exec("sudo echo '\t[STORE_DATA] START' >> $_log_file");
		$path = $_POST['path'];
		$flag = 'store:';
		write_file($fd_stat, $flag);
		shell_exec("sudo echo '\t[STORE_DATA] CALL STORE_DATA_TEST : DESTINATION => $path' >> $_log_file");
		echo store_data_test($path);
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
	
	msgjob('add','Backup Image...');
	$_tmp_file = '/etc/sss_script/burn/.tmp';
	shell_exec("sudo touch $_tmp_file; sudo chmod 666 $_tmp_file");
	shell_exec("sudo echo '[STORE_IMAGE] START : MOPILT' >> $_log_file");
	$ret = shell_exec("cd '$path'; sudo nohup oddacsrt -u web -a store -p /usr/local/bin/mopilt 1>/etc/sss_script/burn/.tmp 2>&1 &");
	shell_exec("sudo echo '[STORE_IMAGE] WORKING IN BACKGROUND : MOPILT' >> $_log_file");
	shell_exec("sudo chmod -R 777 '$path'");
	//msgjob('remove','Backup Image...');
	
	// Error code
	// Error number 89 : File Write Error
	preg_match("/error : \d+\b/i",$ret,$matches);
	preg_match("/\d+\b/",$matches[0],$numbers);
	$err_no = intval($numbers[0]);
	switch($err_no){
		case 89:
			msgjob('once','File Write Error');
			shell_exec("sudo echo '[STORE_IMAGE] MOPILT ERROR : FILE WRITE ERROR' >> $_log_file");
			return "NG:FILE WRITE ERROR\n";
			break;
		default:
			break;
	}
	
	
	if(eregi('access denied',$ret))
	{
		msgjob('remove','Backup Image...');
		msgjob('once','Denied Image Backup');
		shell_exec("sudo echo '[STORE_IMAGE] MOPILT ERROR : ODDMANAGER ACCESS DENIED' >> $_log_file");
		return "NG:ACCESS DENIED\n";
	}else if(eregi('odd task canceled',$ret))
	{
		msgjob('remove','Backup Image...');
		msgjob('once','Cancel Image Backup');
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
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] GET_SIZE : START' >> $_log_file");
	//$total_size = get_size($src);
	$total_size = floatval(shell_exec("sudo du -s $src"))*1024;
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] GET_SIZE , $src : $total_size' >> $_log_file");
	
	
	// Init progress file
	$prog_file = '/etc/sss_script/burn/odd_prog_php';
	$handle = fopen($prog_file,'w');
	$str = "start\n";
	$str .= '0/'.$total_size;
	fwrite($handle,$str);
	fclose($handle);
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] SOURCE SIZE : $total_size' >> $_log_file");
	
	
	
	// Copy
	if(substr($src,-1)=='/') $src = substr($src,0,-1);
	if(substr($dst,-1)=='/') $dst = substr($dst,0,-1);
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] DIRCOPY : START' >> $_log_file");
	$res = dircopy($src,$dst,false);
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] DIRCOPY : END , RESULT : $res' >> $_log_file");
	$_result = shell_exec("sudo chmod -R 777 $dst");
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
	exec("sudo ls -ld $srcdir", $_outputs);
	preg_match_all('/(^[\w-]+)/', $_outputs[0], $_exploded);
	if( substr(trim($_exploded[0][0]), 0, 1) == 'l'){
		shell_exec("sudo echo '\t\t\t\t[DIRCOPY] LINKED DIR : $srcdir' >> $_log_file");
		return 0;
	}
	
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
					$_out = shell_exec("sudo ls -l '$srcfile'");
					shell_exec("sudo echo '\t\t\t\t[DIRCOPY] FILE : $srcfile , INFO : $_out ' >> $_log_file");
					if( substr(trim($_out), 0, 1) == 'l'){
						shell_exec("sudo echo '\t\t\t\t[DIRCOPY] LINKED FILE : $srcfile' >> $_log_file");
						continue;
					}
					if(is_file($dstfile)){
						// compare modified time
						$ow = filemtime($srcfile) - filemtime($dstfile); 
						$_srt =  filemtime($srcfile);
						$_dst = filemtime($dstfile);
						shell_exec("sudo echo '\t\t\t\t[DIRCOPY] SRC MTIME : $_srt , DST MTIME : $_dst' >> $_log_file");
						// compare file size
						$srcfile_size = floatval(sprintf("%u",filesize($srcfile)));
						$dstfile_size = floatval(sprintf("%u",filesize($dstfile)));
						$os = $srcfile_size - $dstfile_size;
						shell_exec("sudo echo '\t\t\t\t[DIRCOPY] SRC SIZE : $srcfile_size , DST SIZE : $dstfile_size' >> $_log_file");
					}else{
						$ow = 1;
						$os = 1;
					}
					if($ow > 0 || $os != 0) {
						
						
						
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
							
							fclose($fp_src);
							fclose($fp_dst);
							$sizetotal+= floatval(sprintf("%u",filesize($dstfile)));
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
	shell_exec("sudo echo '\t\t\t[COPY_FOLDER] IN GET_SIZE' >> $_log_file");
	if(!is_dir($path)){
		return sprintf("%u",filesize($path));
		
	}
  if ($handle = opendir($path)) {
    $size = 0;
    while (false !== ($file = readdir($handle))) {
      if($file!='.' && $file!='..') {
        // function filesize has been deleted
        $size += get_size($path.'/'.$file);
      }
    }
    closedir($handle);
    shell_exec("sudo echo '\t\t\t[COPY_FOLDER] OUT GET_SIZE , RETURN : $size' >> $_log_file");
    return $size;
  }
}
?>
 