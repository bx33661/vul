<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// ODD status check
//	:	Busy//Idle
//=======================================//
exec('oddmngst -m chk', $ret);
//print_r($ret);

$success = 'success';
$status = 'ODD Status';
$flag = FALSE;

foreach($ret as $key => $value)
{
	if(eregi($success, $value) )
	{
		$flag = TRUE;
	}else if(eregi($status, $value) )
	{
		$tmp = explode(':', $value);
		echo trim($tmp[0]).':'.trim($tmp[1])."\n";
	}
}
if($flag == FALSE)
{
	echo "Error:\n";
}
?>