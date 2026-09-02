<?php

function getLocalFileMimeType($file_path){
	$file_path = bsh_double_quote($file_path);
	$result_str = exec('file -bi "'.$file_path.'"');
	$mime_str = $result_str;
	if(empty($result_str)){
		$mime_str = 'unknown-type/unknown-subtype';
	}
	return $mime_str;
}

function func_urlencode($str){
	
	// urlencode를 하면 공백은 '+'로 바뀌지만 파일이름안에 들어간 +는 서버측에서 공백으로 인식하지 않고 '+'로 인식
	// 원래 문자열에 들어가있는 '+'는 urlencode를 통해 '%2B'로 변경되므로 손실은 없음
	$str = urlencode($str);
	$str = str_replace('+','%20',$str);
	return $str;
}

function bsh_double_quote($str){
	return str_replace('"','\"',$str);
}

?>