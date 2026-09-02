<?php
$session_save_dir = "/var/lock/session";
$session_save_dir_ajxp = $session_save_dir."/ajxp";
if(!is_dir($session_save_dir_ajxp)){
    mkdir($session_save_dir_ajxp);
}
?>
