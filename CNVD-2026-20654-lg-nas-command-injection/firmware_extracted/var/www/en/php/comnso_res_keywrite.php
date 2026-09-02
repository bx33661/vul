<?php
	$isget = true;
	if($_GET['k0'] == ''){
		if($_POST['k0'] == ''){
			return;
		}
		$isget = false;
	}
	
	$cms_msgfile="/etc/cms/~resdisccheck.msg";
	$fp = fopen($cms_msgfile, "wt+");
	if($fp){	
		
		// UTF-8 파일 헤드..
		$buffer = "\xEF\xBB\xBF";
		fwrite($fp, $buffer);
		
		$cnt = 0;
		if($isget){
			while($key = $_GET['k'.$cnt]){
				$val = $_GET['v'.$cnt];
				if($key){
					$buffer = $key."=".$val."\n";
					fwrite($fp, $buffer);
				}
				$cnt++;
				if($cnt>10) break;
			}
		}else{
			while($key = $_POST['k'.$cnt]){
				$val = $_POST['v'.$cnt];
				if($key){
					$buffer = $key."=".$val."\n";
					fwrite($fp, $buffer);
				}
				$cnt++;
				if($cnt>10) break;
			}		
		}
		fclose($fp);			
	}
?>