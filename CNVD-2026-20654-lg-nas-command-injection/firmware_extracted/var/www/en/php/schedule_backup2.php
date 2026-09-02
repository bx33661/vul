<?php
include "../inc/lcdmsg.php";

$tasknum=$_POST['task_number'];
//echo $tasknum;
//msgjob('add','Start BD Schedule Backup');
$ret=cms_backup($tasknum);
msgjob('remove','Schedule Backup BD...');
if($ret==1)
{
	$ret="OK:burn_completed";
	msgjob('once','Complete BD Schedule Backup');
}else
{
	//$ret=" Fail";
	$ret="";
	msgjob('once','Fail BD Schedule Backup');
}

echo $ret;

function cms_backup($tasknum)
{
  $file="/etc/cms/~backupburn.msg";
	
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
				else if( strpos($msg, "err:wrong_discnum") !== false ){
					echo "err:wrong_discnum\n";
					return 0;
				}
				else if( strpos($msg, "err:noinit") !== false ){
					echo "err:noinit\n";
					return 0;
				}
				//echo $msg;
				//return 0;
			}
		}			
		
		sleep(1);
		
	}while( strpos($rtn, "burn_completed") === false);
	
	// \xEC젙\xEC긽 諛깆뾽..
	return 1;
}

?>