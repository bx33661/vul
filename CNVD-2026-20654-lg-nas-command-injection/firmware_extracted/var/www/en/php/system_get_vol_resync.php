<?
//=======================================================//
// Session Check
//=======================================================//
/*require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
	require_once "../php/msg_illegal_access.php";
	die();
}
*/
$test ="63.5";

//JUNY : Because it is possible that 'md1 : recovery=12%' and also 'md1: resync=DELAYED is in /proc/mdstat at the same time. So seperate the command into 2 part..

$resync_rate = trim(exec('sudo cat /proc/mdstat | grep -y -E \'resync\' | awk \'{print $4}\' | cut -d "%" -f1'));
if($resync_rate == '')
{
	$recover_rate = trim(exec('sudo cat /proc/mdstat | grep -y -E \'recovery\' | awk \'{print $4}\' | cut -d "%" -f1'));
	if($recover_rate == '')
		echo 'no';
	else
	{
		echo "ok:$recover_rate";
		exec("sudo echo  $recover_rate >> /home/tmp.txt");
	}

}
else
{
	echo "ok:$resync_rate";
	exec("sudo echo  $resync_rate >> /home/tmp.txt");
}
	

?>
