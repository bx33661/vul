<?php
$ID = $_POST["uid"];
//echo "sudo nas-share gen_user_folder_list ".$ID."\n";
shell_exec("sudo nas-share gen_user_folder_list ".$ID);
//shell_exec("sudo nas-share gen_user_folder_list "."admin");

echo "MSG OK\n";
ob_flush();
flush();
?>

