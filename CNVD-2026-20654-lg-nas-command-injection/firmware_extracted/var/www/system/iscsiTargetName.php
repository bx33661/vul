<?php
//echo "ISCSI service off\n";
$ret = shell_exec("sudo cat /etc/iscsi-scstd.conf | grep \"Target iqn\" | awk '{ print $2}'");

echo $ret."\n";
ob_flush();
flush();
?>

