<?php
	header("Expires: -1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: text/html; charset=utf-8');
	
	$cms_msgfile="/etc/cms/~resdisccheck.msg";
	if(!file_exists($cms_msgfile)){
		return;
	}
	
	$fp = fopen($cms_msgfile, "r");
	if($fp){
		$buffer = fread($fp, 512);
		echo $buffer;
		fclose($fp);		
	}
?>