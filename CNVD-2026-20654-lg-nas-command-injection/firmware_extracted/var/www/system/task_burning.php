<?php

$vol_name = $_POST['vol_name'];

$myFile = "/var/www/run/status11";
$fh = fopen($myFile, 'w') or dir ("can't open file");
$stringData = "Burn Start.\n";
fwrite($fh, $stringData);
fclose($fh);
flush();

//shell_exec("sudo /usr/sbin/odd_burning");
$ret = shell_exec("sudo nas-storage odd_burn '$vol_name'");

echo "OK:COMPLETE\n";
?>

