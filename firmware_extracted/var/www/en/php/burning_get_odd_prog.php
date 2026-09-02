<?php
$file = "/etc/sss_script/burn/odd_prog";
$fd = fopen($file,"rb");
$fread = fgets($fd,256);
echo $fread;
?> 