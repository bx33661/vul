<?php

require_once 'browsing_configure.php';
require_once 'browsing_common_func.php';
$file_url = $_GET['path'];
$document_root = $_SERVER['DOCUMENT_ROOT'];
if(substr($document_root,-1) == '/'){
	$document_root = substr($document_root,0,-1);
}
$file_local_path = $document_root.$file_url;
if(is_file($file_local_path)){
	$full_url_exploded = explode('/',$file_url);
	foreach($full_url_exploded as $url_cell_key => $url_cell_row){
		$full_url_exploded[$url_cell_key] = func_urlencode($url_cell_row);
	}
	$file_url = implode('/',$full_url_exploded);
	
	
?>
	<a href="<?php echo $file_url;?>">Download!</a>
<?
}else{
	echo 'cannot access file';
}

?>
