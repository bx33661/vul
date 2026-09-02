<?php
/*
 * Created on 2008. 8. 26
 */
require_once 'browsing_configure.php';
$session_id = session_id();
ini_set('display_errors',1);

$user_path = $_POST['upload_path'];

$upload_destination = $_SESSION['user_directory'].$user_path;
if(substr($upload_destination,-1) != '/'){
	$upload_destination .= '/';
}
if(is_dir($upload_destination)){
	foreach($_FILES as $key=>$val){
		$tmp_name = $val['tmp_name'];
		$last_dot_pos = strrpos($val['name'],'.');
		if(!is_numeric($last_dot_pos))$last_dot_pos = strlen($val['name']);
		$file_name_without_extention = substr($val['name'],0,$last_dot_pos);
		$file_extension =  substr($val['name'],$last_dot_pos);
		$file_name = $file_name_without_extention.$file_extension;
		$cnt = 2;
		while(is_file($upload_destination.$file_name) || is_dir($upload_destination.$file_name)){
			$file_name = $file_name_without_extention.'('.$cnt.')'.$file_extension;
			$cnt++;
		}
		$result = move_uploaded_file($tmp_name,$upload_destination.$file_name);
	}
}
?>
<script>
parent.refresh_file_box();
</script>