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
$mode = $_POST['mode'];
//$file = trim($_POST['file']);


//check whether transcoding is running...
exec("sudo pidof ffmpeg",$isTranscoding);
if(!$isTranscoding)
{
	echo "NG:There is no ffmpeg process\n";
	return;
}


//if($file == '')
	$duration = trim(exec("sudo nas-service get_trans_info $mode"));
//else
//	$duration = trim(exec("sudo nas-service get_trans_info $mode $file"));

if($duration =='')
	echo "NG:invalid";
else
	echo "OK:$duration";



?>
