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


//=======================================================//
// Get log file
// (0) System log : /var/log/notice.log
// (1) Samba log : /var/log/file.smb
// (2) FTP log : /var/log/proftpd/xferlog => when FTP setting is on.
//=======================================================//
$log_mode = $_POST['log_mode'];
//echo $log_mode;
switch($log_mode)
{
	case "system_log":
	//$file = "/var/log/notice.log";
	$file = "/var/log/syslog";
	exec("sudo chmod 644 $file");
	//exec("sudo sed -i 's/%/PERCENT/g' $file");
	break;
	
	case "samba_log":
	$file = "/var/log/samba.log";
	exec("sudo chmod 644 $file");
	break;
	
	case "ftp_log":
	$file = "/var/log/proftpd/xferlog";
	exec("sudo chmod 644 $file");
	break;
	
	case "diag_log":
	exec("sudo /usr/lib/nas/diag_web.sh");
	$file = "/var/log/diag.log";
	$ret_arr = get_diag_msg($file);
	echo json_encode($ret_arr);
	return;
	break;
	
	default:
	break;
}

$fp = @fopen($file,'r');
if(!$fp){
	if($log_mode=="ftp_log") $ret_arr = array('result' => 0, 'message' => "Log file open error\nCheck FTP setting");
	else $ret_arr = array('result' => 0, 'message' => "Log file open error");
	echo json_encode($ret_arr);
	return;
}

if(filesize($file) == 0){
	echo json_encode( array( 'result' => -1 , 'message' => 'No log') );
	return;
}

fseek($fp,-1024*3,SEEK_END);
$ret_arr = array();
for($i=1;!feof($fp);$i++){
	$buffer = fgets($fp,256);
	if($i < 1) continue;
	if(feof($fp)) break;
	$ret_arr[] = trim($buffer);
}
fclose($fp);
$ret_arr['result'] = 1;
$ret_arr['length'] = $i-1;
echo json_encode($ret_arr);
return;


function get_diag_msg($filename){
	$_lines = @file($filename);
	if(!$_lines){
		return array('result' => 0, 'message' => 'Log file open error');
	}
	$_ret_arr = array();
	foreach($_lines as $value){
		if(eregi('serial number',$value)){
			continue;
		}
		$_ret_arr[] = trim($value);
	}	
	$_ret_arr['result'] = 1;
	return $_ret_arr;
}
//=========================================================//
$ret = "";
$list = array();
for($i=0;!feof($fd);$i++)
{
	$list[$i] = array();
	$buffer = fgets($fd,1024);
	if($log_mode =="diag_log"){
		list($list[$i][0],$list[$i][1],$list[$i][2]) = explode(":",$buffer);
	}else{
		$list[$i][0] = trim(substr($buffer,0,15));
		$list[$i][1] = trim(substr($buffer,16));
	}
	
	if($i>$max_cnt) break;
	if($log_mode =="diag_log"){
		if($list[$i][2] == "") break;
		$ret .= $list[$i][0].";".$list[$i][1].";".$list[$i][2];
	}else{
		$ret .= $list[$i][0].";".$list[$i][1]."\n";
	}
}
echo $ret;
//print_r($list);
/*$ret = "";
for($j=($i-1);($i-$j)>$max_cnt;$j--)
{
	$ret .= $list[j][0].";".$list[j][1]."\n";
}
echo $ret;*/
?>
