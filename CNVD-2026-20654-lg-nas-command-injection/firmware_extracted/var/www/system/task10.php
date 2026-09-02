<?php
$file=fopen("../run/burn_list","wb") or exit("Unable to open file");

$list=$_POST["list"];
if(empty($list))
{
	echo "Error : $list is either 0, empty, or not set at all\n";
        //echo $_POST[list];
	exit();
}
$file_list=explode(":",$list);
$cnt=count($file_list);
for($i=0;$i<$cnt;$i++)
{
//	echo $file_list[$i]."\n";
	fwrite($file,$file_list[$i]."\n");
}
fclose($file);
$myFile = "/var/www/run/status11";
$fh = fopen($myFile, 'w') or dir ("can't open file");
$stringData = "Burn Start.\n";
fwrite($fh, $stringData);
fclose($fh);
echo "Message OK\n";
ob_flush();
flush();

shell_exec("sudo /usr/bin/growisofs -Z /dev/sr0 -r -udf -graft-points -path-list /var/www/run/burn_list");
shell_exec("sudo /var/www/system/DeleteIsoFile.sh");

echo "Image is done";
?>

