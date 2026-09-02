<?PHP
	$res_list = "\xEF\xBB\xBFresdir=/mnt/fs/Vol1/system/Share/Restore/";
	$res_list .= date('ymd_His');
	$res_list .= "\n";
	$res_list .= "dirin=";
	$res_list .=$_POST['dirin'];
	$res_list .= "\n";
	$res_list .= "direx=";
	$res_list .=$_POST['direx'];
	$res_list .= "\n";

	$res_list .= "filelist=";
	$res_list .=$_POST['filelist'];
	$res_list .= "\n";
	

    $cms_resfile="/etc/cms/~restoreinfo.lst";
	$file = $cms_resfile;
	
 	if(file_exists($file))
 	{
 		@unlink($file);
 	}
 		
	if (!($fp = fopen($file, "xr+"))) 
	{
	    die("could not create ~restoreinfo.lst file");
	    return;
	}	

	fwrite($fp, $res_list);
	fclose($fp);
	echo " ...";
	echo "save ok!";
?>
