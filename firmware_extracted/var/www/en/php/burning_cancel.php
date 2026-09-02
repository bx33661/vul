<?php
$cmd = "sudo /usr/local/bin/oddmngst -m chk";
$ret = shell_exec($cmd);
$ret = explode("\n",$ret);
$user_id = "User ID";
$app_id = "Application ID";
foreach($ret as $value)
{
	$tmp = explode(":",$value);
	switch(trim($tmp[0]))
	{
	case $user_id:
		$user_id = trim($tmp[1]);
		break;
	case $app_id:
		$app_id = trim($tmp[1]);
		break;
	default:
		break;
	}
}
$cmd = "sudo /usr/local/bin/oddmngst -u $user_id -a $app_id -m ccl";
$ret = shell_exec($cmd);
$pattern = "Success to cancel process";
if(ereg($pattern,$ret))
{
	$out = "Complete";
}else
{
	$out = "Fail";
}
echo $out;
?>