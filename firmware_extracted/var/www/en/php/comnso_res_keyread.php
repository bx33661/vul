<?php
    header('Content-Type: text/html; charset=utf-8');
	header("Expires: -1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	$cms_msgfile="/etc/cms/~resdisccheck.msg";
	if(!file_exists($cms_msgfile)){
		return;
	}
	
	$key = $_GET['key'];
	if($key){
	
		// 키값 오른쪽에 = 문자를 추가한다.
		if(substr($key, -1) != "="){
			$key .= "=";
		}

		$file = $cms_msgfile;
		$fp = fopen($file, "r+");
		if($fp){
			$bom = fread($fp, 3);
			if ($bom != b"\xEF\xBB\xBF"){
			  rewind($file, 0);
			}

			while (!feof($fp)) {
				$buffer = fgets($fp, 4096);
				if(substr($buffer, 0, strlen($key)) == $key){
					echo "... ";
					echo substr($buffer, strlen($key));
					break;
				}
			}
			fclose($fp);		
		}
	}
?>