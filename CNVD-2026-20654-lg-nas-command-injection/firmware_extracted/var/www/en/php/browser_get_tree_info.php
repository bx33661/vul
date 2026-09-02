<?
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";

$path = $_GET['path'];


function getTree($path){
	$dir = opendir($path);
	while (false !== ($handle = readdir($dir) ) ) { 
		
		if($handle != "." && $handle != ".."){
			$buff[] = $handle;
		}
		
	}
	sort($buff);
	closedir($dir);
	
	for($i=0;$i<sizeof($buff);$i++) {
		
		if(is_dir($path."/".$buff[$i])) {
			echo "<folder title='$buff[$i]' url='$path/$buff[$i]'>";
			getTree($path."/".$buff[$i]);
			echo "</folder>";
		}/*else{ //파일까지 같이 나오게 할때
			echo "<doc title='$buff[$i]' url='$path/$buff[$i]' />";
		}*/
		
	}
	
}
?>

<tree>
<?
getTree($path);
?>
</tree>