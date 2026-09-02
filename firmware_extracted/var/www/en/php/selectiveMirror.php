<?php

include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ){
	$ret_str = "{ 'result' : 0 , 'message' : 'session out' }";
	echo $ret_str;
	return;
}

/*****************[ HISTORY ]*********************
Selective Mirror UI ver 0.04

Last Modify : 2009. 07. 08

Next Job    : Seesion Process

Ver 0.04 [2009.07.08] : Folder Selection Fix (Add Prefix)
ver 0.03 [2009.06.10] : Bug Fix (Edit & Delete Section)
ver 0.02 [2009.05.21] : Integrate with Current Web UI
ver 0.01 [2009.05.10] : Write Code for Basic function
*************************************************/

/*************************************************
 * Session Check
   - authority : admin only
   - To be applied later
*************************************************/

require_once("../multilang/multilang_api.php");
	
// language information by url
$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
lang_set_active_language($t_lang_from_url[1]);

/*************************************************
* variable  : $mode 
* options
	- enable    : echo 1 to a config file
	- disable   : echo 0 to a config file
	- getStatus : read Status from a config file 
	- getList   : read List from a list file
	- addList   : add Selective Mirror List
	- editList  : edit Selective Mirror List
	- delList   : delete selective Mirror List
**************************************************/

$mode = $_POST['mode'];

$srcPath = $_POST['srcPath'];
$desPath = $_POST['desPath'];

$configFile = '/etc/sm_enable';
$listFileName = "/etc/sm_conf";
$listTempFileName = "/etc/sm_conf_temp";

if(!file_exists($listFileName)){
	shell_exec('sudo touch /etc/sm_conf');
	shell_exec('sudo chmod 777 /etc/sm_conf');
}

if(!file_exists($listTempFileName)){
	shell_exec('sudo touch /etc/sm_conf_temp');
	shell_exec('sudo chmod 777 /etc/sm_conf_temp');
}

if($mode == 'enable'){
	shell_exec("sudo /etc/init.d/s_mirror enable");
	$return_arr = array("result" =>  "401", "ml_string"   => "change_completed");
	echo json_encode($return_arr);
	exit;		
}
else if($mode == 'disable'){
	shell_exec("sudo /etc/init.d/s_mirror disable");
	$return_arr = array("result" =>  "401", "ml_string"   => "change_completed");
	echo json_encode($return_arr);
	exit;		
}
else if($mode == 'getStatus'){
	if(!file_exists($configFile)){
		shell_exec('echo 0 > '.$configFile);
	}
	$result = trim(shell_exec('cat '.$configFile));
	
	echo $result;
	exit;		
}
else if($mode == 'getList'){
	
	$fileContents = file($listFileName);
	$return_arr = array();

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
	header("Cache-Control: no-cache, must-revalidate" );
	header("Pragma: no-cache" );
	header("Content-type: text/x-json");
	
	$json = "";
	$json .= "{\n";
	$json .= "rows: [";
	$rc = false;
	
	foreach($fileContents as $index => $value){
		
		
		$temp = explode("\t",$value);
	  $temp2 =  str_replace("\n",'',$temp[1]);
	  
	  // Eliminate Default Path of Source
	  if(eregi("^/mnt/disk/volume1/",$temp[0])){
	  	$temp[0] = eregi_replace('^/mnt/disk/volume1','',$temp[0]);
	  }
		else if(eregi("^/mnt/disk/volume2/",$temp[0])){
			$temp[0] = eregi_replace('^/mnt/disk/volume2','',$temp[0]);
		}
		
		// Eliminate Default Path of Destination
		if(eregi("^/mnt/disk/volume1/",$temp[1])){
	  	$temp[1] = eregi_replace('^/mnt/disk/volume1','',$temp[1]);
	  }
		else if(eregi("^/mnt/disk/volume2/",$temp[1])){
			$temp[1] = eregi_replace('^/mnt/disk/volume2','',$temp[1]);
		}
		
	  if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$index."',";
		$json .= "cell:['".addslashes(trim($temp[0]))."'";
		$json .= ",'".addslashes(trim($temp[1]))."']";
		$json .= "}";
		$rc = true;		
	  
	}
	$json .= "]\n";
	$json .= "}";
	
	
	echo $json;
	exit;	
}
else if($mode == 'addList'){
	
	if (!$fp = fopen($listFileName, "a")) {
         echo "Cannot open file ($filename)";
         exit;
    }
    $srcPrefix = getPrefix($srcPath);
    $desPrefix = getPrefix($desPath);	
    
 	
    $newSrcPath = "/mnt/disk".$srcPrefix.$srcPath;
    $newDesPath = "/mnt/disk".$desPrefix.$desPath;
    	
		validatePath($newSrcPath,$newDesPath);	
		
		
    // Write $somecontent to our opened file.
    if (fwrite($fp, $newSrcPath."\t".$newDesPath."\n") === FALSE) {
        echo "Cannot write to file ($listFileName)";
        exit;
    }
		
		fclose($fp);
		
		// Sync with '/proc/fs/s_mirror/config'
		shell_exec("sudo /etc/init.d/s_mirror copyConfig");
		
		$msg = lang_get("added");
		$return_arr = array('number' => 5, 'ml_string' => $msg);
		echo json_encode($return_arr);
		exit;
		
}
else if($mode == 'delList' || $mode == 'editList'){
		
		$fileContents = file($listFileName);
		$newContents = '';
		
		$srcPath_arr = explode('|',$srcPath);
		$desPath_arr = explode('|',$desPath);
		
		$newSrcPrefix = getPrefix($srcPath_arr[0]);
		$newDesPrefix = getPrefix($desPath_arr[0]);
		
		$newSrcPath = "/mnt/disk".$newSrcPrefix.$srcPath_arr[0];
		$newDesPath = "/mnt/disk".$newDesPrefix.$desPath_arr[0];
		
		$path_arr = array();
		
		foreach($srcPath_arr as $index => $value){
			
				
				$srcPathPrefix = getPrefix($srcPath_arr[$index]);
				$desPathPrefix = getPrefix($desPath_arr[$index]);

			$path_arr[$index] = "/mnt/disk".$srcPathPrefix.$srcPath_arr[$index]."\t"."/mnt/disk".$desPathPrefix.$desPath_arr[$index]."\n";
		
		}
		
		// To support Multiple Delete
		if($mode == 'delList'){
				for ($i=0; $i<count($fileContents); $i++)
				{
						if (!in_array($fileContents[$i],$path_arr))
						{
						$newContents .= $fileContents[$i];
						}
				}
		}
		
		
		else if($mode == 'editList'){
			
					for ($i=0; $i<count($fileContents); $i++)
					{
						if ($fileContents[$i] != $path_arr[1])
						
							$newContents .= $fileContents[$i];		
					}
					
					//Delete the original temp file
					if (file_exists($listTempFileName))
					{
					unlink($listTempFileName);
					}
					//and write the new data
					$fp = fopen($listTempFileName,"w");
					if ($fp)
					{
						fwrite($fp,$newContents);
						fclose($fp);
					}
			
			
					validatePath($newSrcPath,$newDesPath);	
					
					$newContents = "";
					for ($i=0; $i<count($fileContents); $i++)
					{
						if ($fileContents[$i] == $path_arr[1])
						{
						$newContents .= $path_arr[0];
						}
						else{
							$newContents .= $fileContents[$i];
							
						}
					}
		}
		
		//Delete the original file
		if (file_exists($listFileName))
		{
		unlink($listFileName);
		}
		//and write the new data
		$fp = fopen($listFileName,"w");
		if ($fp)
		{
			fwrite($fp,$newContents);
			fclose($fp);
		}
		
		// Sync with '/proc/fs/s_mirror/config'
		shell_exec("sudo /etc/init.d/s_mirror copyConfig");
		
		if($mode == 'editList'){
			$msg = lang_get("edited");
			$return_arr = array('number' => 6, 'ml_string' => $msg);
			echo json_encode($return_arr);
			exit;
		}
		else if($mode == 'delList'){
			$msg = lang_get("deleted");
			$return_arr = array('number' => 7, 'ml_string' => $msg);
			echo json_encode($return_arr);
			exit;
		}

} 


else {
	echo "wrong parameter";
}

function validatePath($newSrcPath,$newDesPath){
		
		global $mode;
		global $listFileName;
		global $listTempFileName;
		
		
		if($mode == 'editList'){
			$targetFile = $listTempFileName;
			
		}
		else if($mode == 'addList'){
			$targetFile = $listFileName;
		}
    /**************************************************************************
      Validate Process :: Start
    **************************************************************************/
		$fileContents = file($targetFile);
		
		$newPathArr = $newSrcPath."\t".$newDesPath."\n";
		
		$tmpSrcPath = $newSrcPath."/";
		
		// Validate #1 - Compare Source folder with Destination folder
		if ($newSrcPath == $newDesPath){
				$msg = lang_get("src_des_cannot_be_same");
				$return_arr = array('number' => -901, 'ml_string' => $msg);
				echo json_encode($return_arr);
				exit;
		}
		// Validate #2 - find duplicated tuples
		else if (in_array($newPathArr,$fileContents))
		{		
				//$sm_duplicate = lang_get(sm_duplicate);
				$msg = lang_get("setting_duplicated");
				$return_arr = array('number' => -902, 'ml_string' => $msg);
				echo json_encode($return_arr);
				exit;
		}
		
		// Validate #3 - Destination folder cannot be source's child folder
		// Need to Fix later -Incomplete
		else if(eregi($tmpSrcPath,$newDesPath)){
			//$sm_duplicate = lang_get(sm_duplicate);
				$msg = lang_get("child_folder_restriction");
				$return_arr = array('number' => -903, 'ml_string' => $msg);
				echo json_encode($return_arr);
				exit;
		}		
		
		// srcCount Initialization
		$srcCount = 0;
		
		foreach($fileContents as $index => $value){
		
			$temp = explode("\t",$value);
			$temp2 =  str_replace("\n",'',$temp[1]);
			
			// Validate #6_pre_check - Source folder can have 4 destination folders.
	  	if($newSrcPath == $temp[0]){
	  			$srcCount++;
	  	}
	  	
			// Validate #4 - destination folder can not be other setting's source folder.                  
	  	if($newDesPath == $temp[0]){
		  		$msg = lang_get("des_cannot_be_src");
					$return_arr = array('number' => -904, 'ml_string' => $msg);
					echo json_encode($return_arr);
					exit;
		  }
		  
		  // Validate #5 - Source folder can not be other setting's Destination.	
	  	else if($newSrcPath == $temp2){
		  		$msg = lang_get("src_cannot_be_des");
					$return_arr = array('number' => -905, 'ml_string' => $msg);
					echo json_encode($return_arr);
					exit;
		  }
		}
		// Validate #6 - Source folder can have 4 destination folders.
		// ML Apply Needed
		if($srcCount >= 4){
					$msg = lang_get("sm_multicopy_restriction");
					$return_arr = array('number' => -906, 'ml_string' => $msg);
					echo json_encode($return_arr);
					exit;
		}
	  // Validate Process :: End
}

function getPrefix($path){
	
	  // Add Prefix
    if(eregi("^/volume1/",$path) || eregi("^/service/",$path) || file_exists("/mnt/disk/volume1".$path)){
    	$prefix = "/volume1";
    }else if(eregi("^/volume2/",$path) || file_exists("/mnt/disk/volume2".$path)){
    	$prefix = "/volume2";
    }
    	
    return $prefix;
}
?>