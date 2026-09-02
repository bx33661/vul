<?php
$files=$_POST["files"];
if(empty($files))
{
	echo "Error : $files are either 0, empty, or not set at all\n";
        //echo $_POST[files];
	exit();
}
$command = 'sudo du -s ';
$file_list=explode(":",$files);
$cnt=count($file_list);
$total_size = 0;
$ret = 0;
for($i=0;$i<$cnt;$i++)
{
//	echo $file_list[$i]."\n";
	$ret = shell_exec($command.$file_list[$i]." | awk '{ print $1 }'");
        $total_size += $ret;
}

$total_size *= 1024;

echo "totalsize:".$total_size."\n";

ob_flush();
flush();
?>

