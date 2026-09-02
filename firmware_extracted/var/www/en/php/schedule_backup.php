<?php
/* Check if Restore is working */
$_file = '/etc/cms/~resdisccheck.msg';
if(file_exists($_file)){
	$_fh = fopen($_file,'r');
	$_contents = fread($_fh,filesize($_file));
	if(eregi('restore is completed',$_contents) || eregi('qus:',$_contents) || eregi('appmsg:cancel',$_contents)){
		// O.K.
	}else{
		$_ret_arr = array('result' => '-4', 'message' => 'Restore is working');
		echo json_encode($_ret_arr);
		return;
	}
}



// Check if tray is ready to restore date
// Ready : Closed
// Not ready : Closing/Opened/Opening
// Check tray status
exec("sudo oddmngst -m chk", $_results);
$_tray_stat = array();
foreach($_results as $_val){
	$_tmp = explode(':',$_val);
	$_tray_stat[trim($_tmp[0])] = trim($_tmp[1]);
}
if(@$_tray_stat['Tray Status'] === 'Closed'){
	//echo 'Closed, ready to restore';
}else{
	$_ret_arr = array('result' => -5, 'message' => 'Not ready to backup (Tray not closed)');
	echo json_encode($_ret_arr);
	return;
}
// Check if disc is in tray
$_res = shell_exec("sudo mopilt -i");
if(eregi('no disc in drive',$_res)){
	$_ret_arr = array('result' => -6, 'message' => 'No disc in drive');
	echo json_encode($_ret_arr);
	return;
}


//exec('sudo oddacsrt -u web -a schedule -p /usr/local/bin/mopilt -i',$_results);
//$_disc_sts = array();
//foreach($_results as $_val){
//	$_tmp = explode(":",$_val);
//	$_disc_sts[trim($_tmp[0])] = trim($_tmp[1]);
//}
//if($_disc_sts['Disc Type']){
//	$_tmp = $_disc_sts['Disc Type'];
//	//if(preg_match('/bd-re*||dvd[+-]rw||dvd-ram/i',$_tmp)){
//	if(preg_match('/bd-re*/i',$_tmp)||preg_match('/dvd[+-]rw/i',$_tmp)||preg_match('/dvd-ram/i',$_tmp)){
//		//correct disc type
//	}else{
//		echo "err:notproperdisc\n";
//		return;
//	}
//}else{
//	echo "err:notreadydisc\n";
//	return;
//}


include "../inc/lcdmsg.php";
$msg_file="/etc/cms/~backupburn.msg";
$odd_msg_file="/etc/sss_script/burn/odd_prog";


$tasknum=$_POST['task_number'];
//echo $tasknum;
//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsgKeep 'Start BD Schedule Backup'"`);
msgjob('add','Start BD Schedule Backup');
$ret=cms_backup($tasknum);
msgjob('remove','Start BD Schedule Backup');
if($ret==1)
{
	$ret="OK:burn_completed";
//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsg 'Complete BD Schedule Backup'"`);
msgjob('once','Complete BD Schedule Backup');
}else
{
	//$ret=" Fail";
	$ret="";
	//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsg 'Fail BD Schedule Backup'"`);
	if(check_sizeover()){
		msgjob('once','Disc Is Full. Insert New Disc');
		
	}else{
		msgjob('once','Fail BD Schedule Backup');
		
	}
	
	
}
//shell_exec(`sudo sh -c ". /etc/sss_script/event/lib_sss && SSS_SetEventMsg 'Complete BD Schedule Backup'"`);

echo $ret;

function cms_backup($tasknum)
{
    $file="/etc/cms/~backupburn.msg";
	$recnt=0;
	while(!go_backupburn($file, $tasknum)){
		unlink($file);
		
		$recnt++;
		if($recnt>60){
			return 0;
		}
		sleep(1);
	}

	$recnt=0;
	$revchk=0;
	do
	{
		$rtn = "";
		if(file_exists($file)){
			$fp = fopen($file, "r+");
			if($fp){
				$rtn = fread($fp, 256);
				fclose($fp);
			}
		}
		
		$msglist = split("\n", $rtn);
		
		foreach($msglist as $msg){
			if( strpos($msg, "err:") !== false ){
				if( strpos($msg, "err:busy") !== false ){
					echo "err:busy\n";
					return 0;
				}
				else if( strpos($msg, "err:opening") !== false ){
					echo "err:opening\n";
					return 0;
				}
				else if( strpos($msg, "err:nodisc") !== false ){
					echo "err:nodisc\n";
					return 0;
				}
				else if( strpos($msg, "err:db") !== false ){
					echo "err:db\n";
					return 0;
				}
				else if( strpos($msg, "err:sizeover") !== false ){
					echo "err:sizeover\n";
					return 0;
				}
				else if( strpos($msg, "err:exsize") !== false ){
					echo "err:exsize\n";
					return 0;
				}
				else if( strpos($msg, "err:wrong_discnum") !== false ){
					echo "err:wrong_discnum\n";
					return 0;
				}
				else if( strpos($msg, "err:noinit") !== false ){
					echo "err:noinit\n";
					return 0;
				}
				echo $msg;
				return 0;
			}
		}			
		
		if(!$revchk){
			if( strpos($rtn, "burning") !== false){
				$revchk = 1;
			}
			
			$recnt++;
			if($recnt>10800){
				return 0;
			}
		}
		sleep(1);
		
	}while( strpos($rtn, "burn_completed") === false);
	
	// \xEC??xECê¸?è«›ê¹†ë¾?.
	return 1;
}

//////////////////////////////	
function go_backupburn($file, $tasknum)
{
	$fp = fopen($file, "wt+");
	if($fp){	
		$buffer = "burn_start = -p php -o ".$tasknum;
		fwrite($fp, $buffer);
		fclose($fp);
		return 1;
	}
	return 0;
}
//=======================================================//
// Check status
//=======================================================//
function check_sizeover(){
	global $msg_file, $odd_msg_file;
	
	$ret_cms = file($msg_file);
	$ret_odd = file($odd_msg_file);
	
	if(eregi("err:sizeover",$ret_cms[0]) && eregi("100",$ret_odd[0])){
		return true;
	}
	return false;
}
?>