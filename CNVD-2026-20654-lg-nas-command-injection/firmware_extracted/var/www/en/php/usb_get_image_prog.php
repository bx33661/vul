<?php
	header("Expires: -1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: text/html; charset=utf-8');
	
	$sync_statefile="/etc/cms/~sync.msg";
	if(!file_exists($sync_statefile)){
		return;
	}
	
	$fp = fopen($sync_statefile, "r");
	if($fp){
		$buffer = fread($fp, 256);
		$ret = strstr($buffer, "ing:");	
		if($ret != ""){
			echo $ret."\n";
		}
		
		fclose($fp);		
	}
?>