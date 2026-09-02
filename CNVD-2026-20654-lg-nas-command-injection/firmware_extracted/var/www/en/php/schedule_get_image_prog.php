<?php
	header("Expires: -1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: text/html; charset=utf-8');
	
	$mode=$_POST["mode"];
	if($mode == ""){
		$backup_statefile="/etc/cms/~backupburn.msg";
		if(!file_exists($backup_statefile)){
			return;
		}
		
		$fp = fopen($backup_statefile, "r");
		if($fp){
			$buffer = fread($fp, 256);
			$ret = strstr($buffer, "msg:");
			if($ret != ""){
				if(strpos($ret, "odd_check") !== false){
					echo "mode:odd_check\n";
				}else if(strpos($ret, "search_file") !== false){
					echo "mode:checking_files\n";
				}else if(strpos($ret, "motilt_burn") !== false){
					echo "mode:buring_files\n";
				}
			}else{
				$ret = strstr($buffer, "ing:");	
				if($ret != ""){
					echo "mode:checking_files\n";
				}				
			}
			
			fclose($fp);		
		}		
	}else if($mode == "checking_files"){
		$backup_statefile="/etc/cms/~backupburn.msg";
		if(!file_exists($backup_statefile)){
			return;
		}
		
		$fp = fopen($backup_statefile, "r");
		if($fp){
			$buffer = fread($fp, 256);
			$ret = strstr($buffer, "msg:");
			if($ret != ""){
				if(strpos($ret, "motilt_burn") !== false){
					echo "mode:buring_files\n";
				}
			}else{
				$ret = strstr($buffer, "ing:");	
				if($ret != ""){
					echo $ret."\n";
				}
			}
			
			fclose($fp);		
		}
						
	}else if($mode == "buring_files"){
		$odd_progfile="/etc/sss_script/burn/odd_prog";
		if(!file_exists($odd_progfile)){
			return;
		}
		
		$fp = fopen($odd_progfile, "r");
		if($fp){
			$buffer = fread($fp, 5);
			echo "ing:".$buffer."\n";
			
			fclose($fp);		
		}
	}
?>