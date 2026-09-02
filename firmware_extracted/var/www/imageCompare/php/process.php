<?
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";

$path = $_GET['path'];
?>

<menu>
<?
$dir = opendir($path);
while (false !== ($handle = readdir($dir) ) ) { 
	
	if($handle != "." && $handle != ".svn"){
		
		  // $size = round((filesize($path."/".$handle) / 1024),1);  -> Make Errors when file size is larger than 2GB
      // So use the 'ls' command via shell_exec
      // by chunmw
			/*
      $size = @filesize($path."/".$handle);
      
     	if($size == FALSE){
     		$temp_info = shell_exec('sudo ls -leh '.$path."/".$handle." | awk '{print $5,$7,$8,$10}'");
		  	
		  	$file_info = explode(" ",$temp_info);
		  	$size = $file_info[0];
		  	
		  	$month = array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12);
		  	$time = trim($file_info[3])."-".$month[$file_info[1]]."-".$file_info[2];
		  }
     	else{
     		
     		// Convert File size -> Kilo byte / Mega byte / Giga byte
     		if($size > pow(1024,3)) $size = round(($size / pow(1024,3)),1)."G";
     		else if($size > pow(1024,2)) $size = round(($size / pow(1024,2)),1)."M";
     		else $size = round(($size / 1024),1)."k";
     		
     		
     		$time = filectime($path."/".$handle);
				$time = date("Y-m-d",$time);

     	}*/
		
		if(is_dir($path."/".$handle)){
			$result[] = "<menu_a title='$handle' url='$path/$handle'/>";
		}else{
			$result[] = "<menu_b title='$handle' url='$path/$handle'/>";
		}
	}
	
}

sort($result);
for($i=0; $i<count($result); $i++){
	echo $result[$i];
}

closedir($dir);
?>
</menu>
