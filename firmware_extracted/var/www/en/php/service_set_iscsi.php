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

$iscsi	= $_POST['rdoiscsi'];
$chap 	=$_POST['rdoiscsi_chap'];
$target_secret   = $_POST['rdoiscsi_target'];
$initiator_secret = $_POST['rdoiscsi_initiator'];
//$iscsi_chap_init = $_POST['rdoiscsi_init']; 

//if($iscsi_chap_init == 'on')
//{
//	exec("sudo nas-network iscsi_chap init a b");
//	echo "INIT:";
//	return;
//}

//Check whether disc is burning
if($iscsi == 'on')
{
	$backupcheck = exec("sudo nas-service get running odd_backup");
	if($backupcheck =='on')
	{
		echo "NG:DISC BACKUP";
		return;		
	}
	exec("sudo ps ax | grep -i odd | grep -v grep",$isBurning);
	foreach($isBurning as $_val){
		if(eregi('/usr/sbin/odd_burning', $_val)){
			echo "NG:DISC BURNING";
			return;
		}
	}
}

// NC1

$iscsi_file  = trim(exec('sudo nas-service get_iscsi enabled'));
$chap_file = trim(exec('sudo nas-service get_iscsi chap'));
if ( $iscsi != $iscsi_file || ($iscsi == 'on' && $chap != $chap_file) ){
	exec("sudo nas-service enable iscsi $iscsi");
	if ( $chap == 'on' )
		exec("sudo nas-network iscsi_chap $chap $target_secret $initiator_secret");
	else
		exec("sudo nas-network iscsi_chap off");

	exec("sudo odd_eject");	
	exec("sudo nas-service control iscsi restart");
}

echo "OK:$iscsi\n";

?>
