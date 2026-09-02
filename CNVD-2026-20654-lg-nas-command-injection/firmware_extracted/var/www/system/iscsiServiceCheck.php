<?php
//echo "ISCSI service off\n";
$ret = shell_exec("sudo pidof iscsi-scstd");

if($ret == null)
	echo "ISCSI OFF\n";
else
	echo "ISCSI ON\n";
ob_flush();
flush();
?>

