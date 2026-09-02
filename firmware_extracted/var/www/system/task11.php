<?php
set_time_limit(0);
//$myFile = "../run/abort";
$fh = fopen("../run/abort", 'w');
fwrite($fh, "T");
fclose($fh);
$fr = fopen("../run/abort", 'r');
while(true){
	fseek($fr, SEEK_SET);
	$ch = fread($fr, 1);
	//echo $ch;
	if(strcmp($ch, "F")==0){
		break;
	}
}
fclose($fr);
echo "Message OK\n";
ob_flush();
flush();
?>
