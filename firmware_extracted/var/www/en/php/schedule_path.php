<?php
//=======================================================//
// Store Backup-Folder Path
//=======================================================//

$_tmpfolder="/mnt/disk/linear/";

$schedule              = $_POST["SCHDULEBACKUP"];
$schedule_cycle      = $_POST["SCHDULEBACKUP_CYCLE"];
$schedule_cycle_day  = $_POST["SCHDULEBACKUP_CYCLE_DAY"];
$schedule_method = $_POST["SCHDULEBACKUP_METHOD"];
$schedule_path[0] = $_POST["SCHDULEBACKUP_PATH_1"];
$schedule_path[1] = $_POST["SCHDULEBACKUP_PATH_2"];
$schedule_path[2] = $_POST["SCHDULEBACKUP_PATH_3"];
$schedule_path[3] = $_POST["SCHDULEBACKUP_PATH_4"];
$schedule_path[4] = $_POST["SCHDULEBACKUP_PATH_5"];


$file = "schedule_path.conf";
$srcfile = $_tmpfolder . '/' . $file;

$fp_dst = fopen($srcfile,'wb');
if(!$fp_dst){
	return 'errOpen';
}
$_tmp_res = fwrite($fp_dst, "[SET]\n");
$_tmp_res = fwrite($fp_dst, $schedule_cycle."\n");
$_tmp_res = fwrite($fp_dst, $schedule_cycle_day."\n");
if(!$_tmp_res){
	fclose($fp_dst);
	return 'errWrite';
}

$_tmp_res = fwrite($fp_dst, "[MODE]\n");
$_tmp_res = fwrite($fp_dst,$schedule_method."\n");
if(!$_tmp_res){
	fclose($fp_dst);
	return 'errWrite';
}
$_tmp_res = fwrite($fp_dst, "[PATH]\n");
for($var = 0; $var < 5; $var++) { 	
	if(strcmp($schedule_path[$var], "/mnt/disk\n") && 
	    strcmp($schedule_path[$var], "/mnt/disk"))
	{
		$_tmp_res = fwrite($fp_dst,$schedule_path[$var]."\n");	   
		if(!$_tmp_res){
			fclose($fp_dst);
			return 'errWrite';
		}
	}
}

fclose($fp_dst);
//chmod($dstfile,0777);
shell_exec("sudo chmod 777 $srcfile");

// NC1
if ($schedule == "on")
	exec("sudo nas-system schedule_backup $schedule $schedule_method $schedule_cycle");
else
	exec("sudo nas-system schedule_backup $schedule none $schedule_cycle");



?>
