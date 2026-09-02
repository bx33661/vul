<?php
set_time_limit(0);
exec("sudo chmod 777 /var/www/run/abort");
if(!$fh = fopen("/var/www/run/abort", 'w'))
{
	echo "Fail";
	exit;
}
fwrite($fh, "T");
fclose($fh);
exec("sudo echo 'cancel..1' >> /home/phplog.txt");

if(!$fr = fopen("/var/www/run/abort", 'r'))
{
	echo "Fail";
	exit;
}
exec("sudo echo 'cancel..2' >> /home/phplog.txt");
while(true){
        fseek($fr, SEEK_SET);
        $ch = fread($fr, 1);
        //echo $ch;
        if(strcmp($ch, "F")==0){
                break;
        }
}
fclose($fr);
exec("sudo eject /dev/sr0");

echo "Complete";
ob_flush();
flush();

/*

$_prog_file = '/etc/sss_script/burn/odd_prog';
$_lines = file($_prog_file);
if(intval(trim($_lines[0])) == 0){
	echo "Fail";
	return;
}
$cmd = "sudo /usr/local/bin/oddmngst -m ccl";
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
*/
?>
