<?php
include "../inc/lcdmsg.php";
$act	 = $_POST['act'];
$tasknum = $_POST['task_number'];

if($act == "sync"){
	
	msgjob('add','Syncing USB...');
	cms_sync($tasknum);
	msgjob('remove','Syncing USB...');
	$cms_msgfile="/etc/cms/~sync_ccl.msg";
	
	$_tmp = file($cms_msgfile);
	if(eregi("msg:cancel",$_tmp[0])){
			msgjob('once','Cancelled USB Sync');
			$_ret = shell_exec("sudo rm $cms_msgfile");
			if($_ret){
				echo "error:".$_ret."\n";
				return;
			}
			echo "ok:cancelled\n";
			return;
		}
	
	
	msgjob('once','Complete USB Sync');
	echo "ok:complete";
	return;
	
}else if($act == "cancel"){
	
	$count=0;
	$_flag = false;
	while(1){
		//메세지 파일에 취소를 입력한다.
		$cms_msgfile="/etc/cms/~sync_ccl.msg";
		if(!file_exists($cms_msgfile)){
			continue;
		}
		$fp = fopen($cms_msgfile, "wt");
		if($fp){	
			$buffer = "msg:cancel\n";
			fwrite($fp, $buffer);
			fclose($fp);			
		}
		
		$fp = fopen($cms_msgfile, "r");
		if($fp){
			$buffer = fread($fp, 256);
			fclose($fp);
			
			// 메세지값이 정상적으로 등록된경우 빠져나간다.
			$ret = strstr($buffer, "msg:cancel");	
			if($ret != ""){
				$_flag = true;
				break;
			}		
		}
		
		sleep(1);
		$count++;
		if($count>20){
			$_flag = false;
			break;
		}
	}
	if($_flag){
		echo "ok:cancelled\n";
	}else{
		echo "ng:cancel timeout\n";
	}
	//echo "ok:cancel\n";
	return;
}

function cms_sync($tasknum)
{
    $cmd = "sudo /usr/bin/cmssync -s /usb -d /mnt/fs/Vol1/system/Backup/USB -p php -o ".$tasknum;
	exec($cmd);
	
}
?>