<?php
/**
 * @package info.ajaxplorer.plugins
 * 
 * Copyright 2007-2009 Charles du Jeu
 * This file is part of AjaXplorer.
 * The latest code can be found at http://www.ajaxplorer.info/
 * 
 * This program is published under the LGPL Gnu Lesser General Public License.
 * You should have received a copy of the license along with AjaXplorer.
 * 
 * The main conditions are as follow : 
 * You must conspicuously and appropriately publish on each copy distributed 
 * an appropriate copyright notice and disclaimer of warranty and keep intact 
 * all the notices that refer to this License and to the absence of any warranty; 
 * and give any other recipients of the Program a copy of the GNU Lesser General 
 * Public License along with the Program. 
 * 
 * If you modify your copy or copies of the library or any portion of it, you may 
 * distribute the resulting library provided you do so under the GNU Lesser 
 * General Public License. However, programs that link to the library may be 
 * licensed under terms of your choice, so long as the library itself can be changed. 
 * Any translation of the GNU Lesser General Public License must be accompanied by the 
 * GNU Lesser General Public License.
 * 
 * If you copy or distribute the program, you must accompany it with the complete 
 * corresponding machine-readable source code or with a written offer, valid for at 
 * least three years, to furnish the complete corresponding machine-readable source code. 
 * 
 * Any of the above conditions can be waived if you get permission from the copyright holder.
 * AjaXplorer is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Description : The most used and standard plugin : smbclient access
 *
 * This file is edited by sonmapsi@lge.com, syhong@lge.com
 */

define("LOCAL_NEW_FILE_TEMPLATE", "plugins/access.smb/files/newfile");
define("TMP_LOG_FILE", "/tmp/ajaxplorer.log");

function temp_log($msg)
{
	error_log($msg."\n", 3, TMP_LOG_FILE);
}

function dump_array($one_array, $depth = 0)
{
	$array_string = "";
	$tab_string = "";
	
	for ( $i = 0; $i < $depth; $i++ )
	{
		$tab_string .= "  "; 
	}
	$array_tab_string = $tab_string;
	$tab_string .= "  ";
	
	$array_string .= ($array_tab_string.'Array');
	if ( count($one_array) == 0 )
	{
		$array_string .= " => NULL";
		return $array_string;
	}
	$array_string .= "\n";
	$array_string .= ($array_tab_string."{\n");
	
	foreach($one_array as $key => $one_element)
	{
		$element_type = gettype($one_element);
		if ( $element_type == "array" )
		{
			$array_string .= dump_array($one_array, $depth + 1);
			$array_string .= "\n";
		}
		else
		{
			$array_string .= ($tab_string."[");
			$array_string .= (is_numeric($key) ? "" : "\"");
			$array_string .= $key;
			$array_string .= (is_numeric($key) ? "" : "\"");
			$array_string .= ("] =>".$one_element." (".$element_type.")\n");
		}
	}
	$array_string .= ($array_tab_string."}\n");
	
	return $array_string;
}

define("ONE_FILE_TRANSFER_CHUNK_SIZE", 10 * 1024 * 1024); // 1 MB = 1 * 1024 * 1024
define("ZIP_FILE_TRANSFER_CHUNK_SIZE", 10 * 1024 * 1024); // 1 MB = 1 * 1024 * 1024

define("ZIP_OPT_REMOVE_ALL_PATH", 0x01);
define("ZIP_OPT_EXTRACT_AS_STRING", 0x02);

//$zip_local_file_header_size = 30; // + file name
//$zip_data_descriptor_size = 16;
//$zip_central_directory_record_size = 46; // + file name
//$zip_end_central_directory_record_size = 22;
define("ZIP_META_DATA_LOCAL_HEADER_LENGTH", 30);
define("ZIP64_META_DATA_LOCAL_HEADER_EXTRA_FIELD_LENGTH", 20);
define("ZIP_META_DATA_DESCRIPTOR_LENGTH", 16);
define("ZIP_META_DATA_CDR_LENGTH", 46);
define("ZIP64_META_DATA_DESCRIPTOR_ADD_LENGTH", 8);
define("ZIP_META_LENGTH_PER_FILE", 22); // end of cdr
define("ZIP64_META_LENGTH_PER_FILE", 76); // zip64 end of cdr, zip64 end of cdl

// PATH convert direction
define("PATH_TO_SMB", 0);
define("PATH_TO_SHELL", 1);

require_once(INSTALL_PATH."/plugins/access.smb/class.smbclientAccessDriver.php");

class smbAccessDriver extends AbstractAccessDriver 
{
	/**
	* @var Repository
	*/
	var $repository;
	
	var $temp_dir;
	
	function smbAccessDriver($driverName, $filePath, $repository, $optOptions = NULL){
		parent::AbstractAccessDriver($driverName, $filePath, $repository);
		
		$this->temp_dir = $this->determine_updown_temp_dir();
	}
	
	function initRepository(){
		$create = $this->repository->getOption("CREATE");
		$path = $this->repository->getOption("PATH");

		if($create == true){
			if(!is_dir($path)) @mkdir($path);
			if(!is_dir($path)){
				return new AJXP_Exception("Cannot create root path for repository. Please check repository configuration or that your folder is writeable!");
			}
		}else{
			if(!is_dir($path)){
				return new AJXP_Exception("Cannot find base path for your repository! Please check the configuration!");
			}
		}
	}
	
	function switchAction($action, $httpVars, $fileVars)
	{
		if(!isSet($this->actions[$action])) return;
		$xmlBuffer = "";
		// KHJ20090924 system default locale setting
		setlocale(LC_ALL, SYSTEM_DEFAULT_LOCALE);
		foreach($httpVars as $getName=>$getValue){
			$$getName = Utils::securePath(SystemTextEncoding::magicDequote($getValue));
		}
		
		$selection = new UserSelection();
		$selection->initFromHttpVars($httpVars);
		
		if(isset($dir) && $action != "upload") { $safeDir = $dir; $dir = SystemTextEncoding::fromUTF8($dir); }
		if(isset($dest)) $dest = SystemTextEncoding::fromUTF8($dest);
		$mess = ConfService::getMessages();
		
		if ( !isset($dir) ) $dir = "";
		
		$in_trash_box_delete_detected = FALSE;
		$trash_box_delete_detected = FALSE;
		$delete_emulation = FALSE;
		if ( $action == "delete" )
		{
			$trashbox_name = $this->repository->getOption("RECYCLE_BIN");
			
			if ( $trashbox_name != "" )
			{
				$delete_top_dir = explode("/", $dir);
				
				if ( $delete_top_dir[1] != "" && $delete_top_dir[1] === $trashbox_name )
				{
					$in_trash_box_delete_detected = TRUE;
				}
				
				/*
				$selected_files = $selection->getFiles();
				$trashbox_path = "/".$trashbox_name;
				$only_trashbox_delete = FALSE;
				if ( !$selection->isEmpty() && in_array($trashbox_path, $selected_files) )
				{
					$trash_box_delete_detected = TRUE;
					if ( count($selected_files) > 1 )
					{
						$selected_files = array_diff($selected_files, array($trashbox_path));
						$selection->setFiles($selected_files);
					}
					else
					{
						$only_trashbox_delete = TRUE;
					}
				}
				
				if ( $in_trash_box_delete_detected == FALSE && $only_trashbox_delete == FALSE )
				{
					$action = "move";
					$dest = $trashbox_path;
					$delete_emulation = TRUE;
					$dest_node = "AJAXPLORER_RECYCLE_NODE";
					
					if ( !file_exists($trashbox_path) )
					{
						$this->make_directory("/", $trashbox_name);
					}
				}
				*/
			}
		}
		
		// FILTER DIR PAGINATION ANCHOR
		if( isset($dir) && strstr($dir, "#") !== false && !file_exists($this->getPath().$dir) )
		{
			// KHJ20091202 zip file의 경우 pagnation 하지 않는다.
			if ( preg_match("/\.zip($|\/)/", strtolower($dir)) == 0 )
			{
				$parts = explode("#", $dir);
				$parts_count = count($parts);
				$dir = $parts[0];
				for ( $i = 1; $i < ($parts_count - 1); $i++ )
				{
					$dir .= ("#".$parts[$i]);
				}
				$page = $parts[$parts_count - 1];
			}
		}
		
		switch($action)
		{			
			//------------------------------------
			//	DOWNLOAD, IMAGE & MP3 PROXYS
			//------------------------------------
			case "download":
				//AJXP_Logger::logAction("Download", array("files"=>$selection));
				set_error_handler(array("HTMLWriter", "javascriptErrorHandler"), E_ALL & ~ E_NOTICE);
				register_shutdown_function("restore_error_handler");
				
				$selected_files = $selection->getFiles();
				
				if ($fileVars == "PUBLIC_LINK")
				{
					;
				}
				else
				{
					//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
					//$min = 20;
					//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
					$sec = 1200;
					exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
					$remote_session_file_name = $_SESSION["SESS_FILE"];
					$mess = ConfService::getMessages();
					$session_out_msg = $mess[509];
					if ( !file_exists($remote_session_file_name) )
					{
						//AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
						$close_target = "parent";
						print("<html><script language=\"javascript\">\n");
						print("\n alert(\"".$session_out_msg."\");");
						print("\n parent.close();");
						print("</script></html>");
						exit(1);
					}
				}
				
				//temp_log("download:".var_export($selected_files,true));
				
				if ($fileVars == "PUBLIC_LINK")
				{
					;
				}
				else{
					$download_error_msg = $mess[518];
					//권한 설정에 따른 read permission없는 파일/폴더에 대한 다운로드 요청시 에러 메세지 출력
					foreach($selected_files as $selectedfile)
					{
						$selectedfile_realpath = $this->getPath().$selectedfile;
						if(!$this->isReadable($selectedfile_realpath))
						{
							//AJXP_Exception::errorToXml("Not readable!!");
							HTMLWriter::javascriptErrorHandler(0,$download_error_msg);
							//AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
							exit(1);
							//break;
						}
						else
						{
							$fileperm_bak = substr(decoct( fileperms($selectedfile_realpath) ), (is_file($selectedfile_realpath)?2:1));
							$chmodValue = '0777';
							$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($selectedfile_realpath).'"';
							exec($shell_cmd, $shell_output, $shell_result);
							if ( is_dir($selectedfile_realpath) )
							{
								$chk_res = $this->download_file_permission_check($selectedfile_realpath,0);
							}
							
							$shell_cmd = 'sudo chmod '.$fileperm_bak.' "'.Utils::unix_shell_escape_args($selectedfile_realpath).'"';
							exec($shell_cmd, $shell_output, $shell_result);
							if ($chk_res != 0)
							{
								HTMLWriter::javascriptErrorHandler(0,$download_error_msg);
								exit(1);
							}
						}
						
					}
				}
				
				
				if ( count($selected_files) > 0 )
				{
					$test = $selection->detectZip(SystemTextEncoding::fromUTF8(dirname($selected_files[0])));
					if ( $test != false )
					{
						if ( ($fileVars == "PUBLIC_LINK") && (is_file($this->getPath()."/".$test[0]) == TRUE) )
						{
							$selection->set_zip_related_information(true, $test[0], $test[1]);
						}
						else if ( $this->is_file_by_smbclient($test[0]) == 1 )
						{
							$selection->set_zip_related_information(true, $test[0], $test[1]);
						}
						else
						{
							$selection->set_zip_related_information(false);
						}
					}
					else
					{
						$selection->set_zip_related_information(false);
					}
				}
				
				$is_one_file_selected = $selection->isUnique();
				$inzip_file_direct_download = FALSE;
				
				$LocalFilePath = ($selection->inZip) ? $selection->getUniqueFile() : $this->getPath()."/".$selection->getUniqueFile();
				
				if ( $selection->inZip )
				{
					if ( !$is_one_file_selected || ($is_one_file_selected && $this->is_dir_path($LocalFilePath)) )
					{
						$this->CreateDownloadTempDir($tmpDir);
						// KHJ20090922 download cancel인 경우가 있으므로 delete folder를 여기서 shutdown_function으로 등록
						if(isset($tmpDir))
						{
							register_shutdown_function(array($this, "DeleteTempDir"), $tmpDir); // MUST ON
						}
						
						$this->convertSelectionToTmpFiles($tmpDir, $selection);
						$LocalFilePath = ($selection->inZip) ? $selection->getUniqueFile() : $this->getPath()."/".$selection->getUniqueFile();
					}
					else
					{
						$inzip_file_direct_download = TRUE;
					}
				}
				
				$zip = false;
				if ( $is_one_file_selected )
				{
					if ( is_dir($LocalFilePath) )
					{
						$zip = true;
						
						if ( $selection->inZip )
						{
							$dir .= $tmpDir;
						}
						else
						{
							$dir .= "/".$selection->getUniqueFile();
						}
					}
					//temp_log("zip dir: ".$dir);
				}
				else
				{
					if ( $selection->inZip )
					{
						$dir = $tmpDir."/".$selection->getZipLocalPath();
						//temp_log("make zip dir: ".$dir);
					}
					else
					{
						$dir = dirname($selection->getUniqueFile());
					}
					
					$zip = true;
				}
				
				if($zip)
				{
					// Make a temp zip and send it as download
					$loggedUser = AuthService::getLoggedUser();
					//$file = $tmpDir."/".time()."tmpDownload.zip";
					$selected_files = $selection->getFiles();
					
					//foreach ( $selected_files as $one_file )
					//	temp_log("selected tmp file: ".$one_file);
					
					if ( $selection->inZip )
					{
						if ( count($selected_files) > 1 )
						{
							$zip_local_path = basename($selection->getZipLocalPath());
							$localName = (($zip_local_path == "") ? "" : $zip_local_path."_") . "Files.zip";
						}
						else
						{
							$localName = (basename($selected_files[0]).".zip");
						}
					}
					else
					{
						$dir_basename = basename($dir);
						if ( $selection->isUnique() )
						{
							$localName = $dir_basename.".zip";
						}
						else
						{
							$localName = ($dir_basename == "") ? "Files.zip" : $dir_basename."_Files.zip";
						}
					}
					
					$res = $this->makeZipAndSendToClient($selected_files, $dir, $selection->inZip, $localName);
					if ( $res == FALSE )
					{
						AJXP_Exception::errorToXml("Error while compressing");
					}
				}
				else
				{
					if ( $inzip_file_direct_download )
					{
						$filename = $selection->detectZip($selection->getUniqueFile());
						$this->read_file_in_zip($filename[0], $filename[1], ($fileVars == "PUBLIC_LINK" ? TRUE : FALSE));
					}
					else
					{
						$this->readFile2($LocalFilePath, "force-download", "", ($fileVars == "PUBLIC_LINK" ? TRUE : FALSE));
					}
				}

				exit(0);
			break;
		
			case "image_proxy":
				//temp_log("image proxy: thumb ".$get_thumb." ".$file);
				if( ($split = UserSelection::detectZip(SystemTextEncoding::fromUTF8($file)))
				   && ($this->is_file_by_smbclient($split[0]) == 1) )
				{
					$data = $this->zipExtractOneFile($split[0], substr($split[1], 1), ZIP_OPT_EXTRACT_AS_STRING);
					header("Content-Type: ".Utils::getImageMimeType(basename($split[1]))."; name=\"".basename($split[1])."\"");
					header("Content-Length: ".strlen($data));
					header('Cache-Control: public');
					
					print($data);
				}
				else
				{
					
					if(isSet($get_thumb) && $get_thumb == "true" )
					{
						$EmbeddedThumbnail = @exif_thumbnail($this->getPath()."/".SystemTextEncoding::fromUTF8($file), $width, $height, $image_type);
					
						if ( $EmbeddedThumbnail != FALSE )
						{
							header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
							header("Cache-Control: no-store, no-cache, must-revalidate");
							header("Pragma: no-cache"); 
							header("Content-Transfer-Encoding: binary");
							header('Content-type: ' .image_type_to_mime_type($image_type));

							echo $EmbeddedThumbnail;
						}
						// file size가 128K 미만인 경우 그냥 file 전송
						else if ( $this->getTrueSize($this->getPath()."/".SystemTextEncoding::fromUTF8($file)) < (128 * 1024) )
						{
							$this->readFile2($this->getPath()."/".SystemTextEncoding::fromUTF8($file), "image");
						}
						else
						{
							$NoThumbImageTransferNeeded = TRUE;
							
							if ( $this->driverConf["GENERATE_THUMBNAIL"] )
							{
								require_once("server/classes/PThumb.lib.php");
								
								$pThumb = new PThumb($this->driverConf["THUMBNAIL_QUALITY"]);
								
								if(!$pThumb->isError())
								{							
									$pThumb->use_cache = $this->driverConf["USE_THUMBNAIL_CACHE"];
									$pThumb->cache_dir = INSTALL_PATH."/".$this->driverConf["THUMBNAIL_CACHE_DIR"];	
									$pThumb->fit_thumbnail($this->getPath()."/".SystemTextEncoding::fromUTF8($file), 200);
									if($pThumb->isError())
									{
										//print_r($pThumb->error_array);
										//temp_log("thumb error: ");
										//foreach($pThumb->error_array as &$oneError)
										//{
										//	temp_log($oneError[0]);
										//}
									}
									else
									{
										$NoThumbImageTransferNeeded = FALSE;
									}
								}
							}
							
							if ( $NoThumbImageTransferNeeded == TRUE )
							{
								header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
								header("Cache-Control: no-store, no-cache, must-revalidate");
								header("Pragma: no-cache"); 
								header("Content-Transfer-Encoding: binary");
								header('Content-type: image/png');
							
								echo file_get_contents(INSTALL_PATH."/client/images/crystal/mimes/64/image.png", FILE_BINARY);
							}
						}
						
						exit(0);
					}
					
					$this->readFile2($this->getPath()."/".SystemTextEncoding::fromUTF8($file), "image");
					
					/*
					 //Thumbnail view enable
					if(isSet($get_thumb) && $get_thumb == "true" && $this->driverConf["GENERATE_THUMBNAIL"]){
						require_once("server/classes/PThumb.lib.php");
						$pThumb = new PThumb($this->driverConf["THUMBNAIL_QUALITY"]);						
						if(!$pThumb->isError()){							
							$pThumb->use_cache = $this->driverConf["USE_THUMBNAIL_CACHE"];
							$pThumb->cache_dir = INSTALL_PATH."/".$this->driverConf["THUMBNAIL_CACHE_DIR"];	
							$pThumb->fit_thumbnail($this->getPath()."/".SystemTextEncoding::fromUTF8($file), 200);
							if($pThumb->isError()){
								print_r($pThumb->error_array);
							}
							exit(0);
						}
					}
					
					$this->readFile2($this->getPath()."/".SystemTextEncoding::fromUTF8($file), "image");*/
				}
				
				exit(0);
			break;
			
			case "mp3_proxy":
				if( ($split = UserSelection::detectZip(SystemTextEncoding::fromUTF8($file)))
				   && ($this->is_file_by_smbclient($split[0]) == 1) )
				{
					
					$data = $this->zipExtractOneFile($split[0], substr($split[1], 1), ZIP_OPT_EXTRACT_AS_STRING);
					header("Content-Type: audio/mp3; name=\"".basename($split[1])."\"");
					header("Content-Length: ".strlen($data));
					print($data);
				}
				else
				{
					$this->readFile2($this->getPath()."/".SystemTextEncoding::fromUTF8($file), "mp3");
				}
				exit(0);
			break;
			
			//------------------------------------
			//	ONLINE EDIT
			//------------------------------------
			case "edit";	
				if(isset($save) && $save==1 && isSet($code))
				{
					// Reload "code" variable directly from POST array, do not "securePath"...
					$code = $_POST["code"];
					//AJXP_Logger::logAction("Online Edition", array("file"=>SystemTextEncoding::fromUTF8($file)));
					$code=stripslashes($code);
					$code=str_replace("&lt;","<",$code);
					
					$CreateUploadTempDirResult = $this->CreateUploadTempDir($UploadTempDir);
					register_shutdown_function(array($this, "DeleteTempDir"), $UploadTempDir);
					if ( $CreateUploadTempDirResult == TRUE )
					{
						$smb_destination = SystemTextEncoding::fromUTF8("/$file");
						$userfile_name = basename($smb_destination);
						$fp=fopen($UploadTempDir.'/'.$userfile_name, "w");
						fputs ($fp,$code);
						fclose($fp);
						
						$res = smbclientAccessDriver::singleton()->put_smbclient($UploadTempDir."/".$userfile_name,  $smb_destination);
						
						header("Content-Type:text/plain");
						if ( $res < 0 )
						{
							if ( $res == NT_STATUS_DISK_FULL ) // NT_STATUS_DISK_FULL
							{
								echo $mess[503]; // There is no space in the destination volume
							}
							else
							{
								echo $mess[115]; // The file has been saved successfully
							}
						}
						else
						{
							echo $mess[115]; // The file has been saved successfully
						}
					}
					else
					{
						header("Content-Type:text/plain");
						echo $mess[210]; // Upload failed
					}
				}
				else 
				{
					if ( $this->isReadable($this->getPath()."/".SystemTextEncoding::fromUTF8($file)) == TRUE )
					{
						$this->readFile2($this->getPath()."/".SystemTextEncoding::fromUTF8($file), "plain");
					}
					else
					{
						echo "You have no read permission on this file"; // TODO message add
					}
				}
				exit(0);
			break;
		
			//------------------------------------
			//	COPY / MOVE
			//------------------------------------
			case "copy";
			case "move";
				$path_php_ini = "/etc/php/php.ini"; 		
				//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
				//$min = 20;
				//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
				$sec = 1200;
				exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
				$remote_session_file_name = $_SESSION["SESS_FILE"];
				$mess = ConfService::getMessages();
				$session_out_msg = $mess[509];
				if ( !file_exists($remote_session_file_name) )
				{
					AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
					exit(1);
				}
				
				if($selection->isEmpty())
				{
					$errorMessage = $mess[113];
					break;
				}
				
				$selected_files = $selection->getFiles();
				$test = $selection->detectZip(SystemTextEncoding::fromUTF8(dirname($selected_files[0])));
				if ( $test != false && ($this->is_file_by_smbclient($test[0]) == 1) )
				{
					$selection->set_zip_related_information(true, $test[0], $test[1]);
				}
				else
				{
					$selection->set_zip_related_information(false);
				}
				 
				if( $selection->inZip() )
				{
					//temp_log("zip extract");
					if ( $this->CreateUploadTempDir($tmpDir) == TRUE )
					{
						register_shutdown_function(array($this, "DeleteTempDir"), $tmpDir);
						
						$this->convertSelectionToTmpFiles($tmpDir, $selection);
						
						// put extracted files to destnation folder
						//temp_log("extract dest: ".$dest);
						
						//foreach($selected_files as $key => $value)
						//{
						//	temp_log($key." => ".$value);
						//}
						
						$result = smbclientAccessDriver::singleton()->mput_smbclient($tmpDir, $dest);
						
						if ( $result == FALSE )
						{
							$errorMessage = $mess[247]." ".basename($selection->getZipPath())." error!";
						}
						else
						{
							$logMessage = $mess[248]." ".$dest." ".$mess[48];
						}
					}
					else
					{
						$errorMessage = join("\n", $mess[254]);
					}
				}
				else
				{
					$success = $error = array();
					
					$this->copyOrMove($dest, $selection->getFiles(), $error, $success, ($action=="move" ? true : false), $delete_emulation);
					
					if ( count($error) )
					{
						$errorMessage = join("\n", $error);
					}
					else {
						$logMessage = join("\n", $success);
						//AJXP_Logger::logAction(($action=="move"?"Move":"Copy"), array("files"=>$selection, "destination"=>$dest));
						//AJXP_Logger::logAction("Copy or Move", array("files"=>$selection));
					}
				}
				
				if ( $trash_box_delete_detected == TRUE )
				{
					$this->delete_execution(array($trashbox_path), FALSE, $logMessages);
					if(count($logMessages))
					{
						$temp_logMessage = join("\n", $logMessages);
						$logMessage = isset($logMessage) ? ($logMessage.$temp_logMessage) : $temp_logMessage;
					}
				}
				
				$reload_current_node = true;
				//if(isset($dest_node)) $reload_dest_node = $dest_node;
				//if(isset($dest_node_temp)) $reload_dest_node_uuid = Utils::xmlEntities(SystemTextEncoding::toUTF8($dest));
				$reload_file_list = true;
				
			break;
			
			//------------------------------------
			//	SUPPRIMER / DELETE
			//------------------------------------
			case "delete";
				$path_php_ini = "/etc/php/php.ini"; 		
				//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
				//$min = 20;
				//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
				$sec = 1200;
				exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
				$remote_session_file_name = $_SESSION["SESS_FILE"];
				$mess = ConfService::getMessages();
				$session_out_msg = $mess[509];
				if ( !file_exists($remote_session_file_name) )
				{
					AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
					exit(1);
				}
		
				if ( $selection->isEmpty() )
				{
					$errorMessage = $mess[113];
					break;
				}
				
				$logMessages = array();				
				$errorMessage = $this->delete_execution($selection->getFiles(), $in_trash_box_delete_detected, $logMessages);
				
				if(count($logMessages))
				{
					$logMessage = join("\n", $logMessages);
				}
				
				//AJXP_Logger::logAction("Delete", array("files"=>$selection));
				
				$reload_current_node = true;
				$reload_file_list = true;
				
			break;
		
			//------------------------------------
			//	RENOMMER / RENAME
			//------------------------------------
			case "rename";
				$path_php_ini = "/etc/php/php.ini"; 		
				//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
				//$min = 20;
				//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
				$sec = 1200;
				exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
				$remote_session_file_name = $_SESSION["SESS_FILE"];
				$mess = ConfService::getMessages();
				$session_out_msg = $mess[509];
				if ( !file_exists($remote_session_file_name) )
				{
					AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
					exit(1);
				}
			
				$file = SystemTextEncoding::fromUTF8($file);
				$filename_new = SystemTextEncoding::fromUTF8($filename_new);
				//$filename_new = strstr($filename_new, "..", "");
				//$filename_new = str_replace("..", "", $filename_new);
				$str_len = strlen($filename_new);
				$num = 0;
				$loc = 0;
				
				if ($filename_new[$str_len-1] == ".")
				{
					
				    for ($i = $str_len-1; $i > 0 ; $i--)
				    {
					if ($filename_new[$i] == ".")
					{
					    $loc = $loc+1;
					}
					else if ($filename_new[$i] == " ")
					{
					    $loc = $loc+1;
					}
					else
					{
						$i = 0;
					}
				    }
				}
				$num = 0 - $loc;
			
				$filter_name = substr($filename_new, 0, $num);
				//temp_log("filter name:".$filter_name);				    
				    if($loc == 0)
				    {
					$filename_new = SystemTextEncoding::fromUTF8($filename_new);
				    }
				    else
				    {
					$filename_new = SystemTextEncoding::fromUTF8($filter_name);
				    }
				  
				    $error = $this->rename_execute($file, $filename_new);
				    if($error != null) {
					    $errorMessage  = $error;
					    break;
				    }
				    $logMessage= SystemTextEncoding::toUTF8($file)." $mess[41] ".SystemTextEncoding::toUTF8($filename_new);
				    //$reload_current_node = true;
				    $reload_file_list = basename($filename_new);
				    $reload_current_node = true;
				    $reload_file_list = true;
				    //AJXP_Logger::logAction("Rename", array("original"=>$file, "new"=>$filename_new));
				
			break;
		
			//------------------------------------
			//	CREER UN REPERTOIRE / CREATE DIR
			//------------------------------------
			case "mkdir";
				$path_php_ini = "/etc/php/php.ini"; 		
				//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
				//$min = 20;
				//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
				$sec = 1200;
				exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
				$remote_session_file_name = $_SESSION["SESS_FILE"];
				$mess = ConfService::getMessages();
				$session_out_msg = $mess[509];
				if ( !file_exists($remote_session_file_name) )
				{
					AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
					exit(1);
				}
		
				$messtmp = "";
				$dirname = Utils::processFileName(SystemTextEncoding::fromUTF8($dirname), $invalid_char_count);
				
				if ( $invalid_char_count > 0 )
				{
					$not_allowed_chars = chunk_split(NOT_ALLOWED_FILENAME_CHAR, 1, " ");
					$error = "$mess[37]\n";
					$error .= "$mess[501]: $not_allowed_chars";
				}
				else
				{
				    $str_len = strlen($dirname);
				    $num = 0;
				    $loc = 0;
				   
				    if ($dirname[$str_len-1] == ".")
				    {
					    
					for ($i = $str_len-1; $i > 0 ; $i--)
					{
					    if ($dirname[$i] == ".")
					    {
						$loc = $loc+1;
					    }
					    else if ($dirname[$i] == " ")
					    {
						$loc = $loc+1;
					    }
					    else
					    {
						    $i = 0;
					    
					    }
					}
				    }
				    $num = 0 - $loc;
			   
				    $filter_name = substr($dirname, 0, $num);
				    				
					if($loc == 0)
					{
					    $dirname = SystemTextEncoding::fromUTF8($dirname);
					}
					else
					{
					    $dirname = SystemTextEncoding::fromUTF8($filter_name);
					}
					
				    $dirname = Utils::processFileName(SystemTextEncoding::fromUTF8($dirname), $invalid_char_count);
				
				    if ( $invalid_char_count > 0 )
				    {
					    $not_allowed_chars = chunk_split(NOT_ALLOWED_FILENAME_CHAR, 1, " ");
					    $error = "$mess[37]\n";
					    $error .= "$mess[501]: $not_allowed_chars";
				    }
					
				    $error = $this->make_directory($dir, $dirname);
				}
				
				if ( isset($error) )
				{
					$errorMessage = $error;
					break;
				}
				$reload_file_list = $dirname;
				
				$messtmp .= "$mess[38] ".SystemTextEncoding::toUTF8($dirname)." $mess[39] ";
				if ( $dir == "" )
				{
					$messtmp .= "/";
				}
				else
				{
					$messtmp .= SystemTextEncoding::toUTF8($dir);
				}
				$logMessage = $messtmp;
				$reload_current_node = true;
				//AJXP_Logger::logAction("Create Dir", array("dir"=>$dir."/".$dirname));
			break;
		
			//------------------------------------
			//	CREER UN FICHIER / CREATE FILE
			//------------------------------------
			case "mkfile";
				$path_php_ini = "/etc/php/php.ini"; 		
				//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
				//$min = 20;
				//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
				$sec = 1200;
				exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
				$remote_session_file_name = $_SESSION["SESS_FILE"];
				$mess = ConfService::getMessages();
				$session_out_msg = $mess[509];
				if ( !file_exists($remote_session_file_name) )
				{
					AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
					exit(1);
				}
		
				$messtmp = "";
				$filename = Utils::processFileName(SystemTextEncoding::fromUTF8($filename), $invalid_char_count);	
				
				if ( $invalid_char_count > 0 )
				{
					$not_allowed_chars = chunk_split(NOT_ALLOWED_FILENAME_CHAR, 1, " ");
					$error = "$mess[37]\n";
					$error .= "$mess[501]: $not_allowed_chars";
				}
				else
				{
					$error = $this->mkfile_execution($dir, $filename);	
				}
				
				if(isSet($error)){
					$errorMessage = $error; break;
				}
				$messtmp.="$mess[34] ".SystemTextEncoding::toUTF8($filename)." $mess[39] ";
				if($dir=="") {$messtmp.="/";} else {$messtmp.=SystemTextEncoding::toUTF8($dir);}
				$logMessage = $messtmp;
				$reload_file_list = $filename;
				//AJXP_Logger::logAction("Create File", array("file"=>$dir."/".$filename));
		
			break;
			
			//------------------------------------
			//	CHANGE FILE PERMISSION
			//------------------------------------
			case "chmod";
			
				$messtmp="";
				$files = $selection->getFiles();
				
				$mess = ConfService::getMessages();
				$chmod_msg = $mess[519];
				$chmod_ok = $mess[48];
				
				$changedFiles = array();
				foreach ($files as $fileName){
					//$error = $this->chmod($this->getPath().$fileName, $chmod_value, ($recursive=="on"), ($recursive=="on"?$recur_apply_to:"both"), $changedFiles);
					$error = $this->chmod($this->getPath().$fileName, $chmod_value, true, $recur_apply_to, $changedFiles, ($recursive2=="on"));
				}
				
				if($error == 0){
					$logMessage = $chmod_msg;
				}
				else
				{
					$logMessage = $chmod_ok;
					break;
				}
				//$messtmp.="$mess[34] ".SystemTextEncoding::toUTF8($filename)." $mess[39] ";
				//$logMessage="Successfully changed permission to ".$chmod_value." for ".count($changedFiles)." files or folders";
				
				
				//$logMessage="File/Folder permission changed";
				$reload_file_list = $dir;
				$reload_current_node = true;
				$reload_file_list = true;
				//AJXP_Logger::logAction("Chmod", array("dir"=>$dir, "filesCount"=>count($changedFiles)));
		
			break;
			
			//------------------------------------
			//	UPLOAD
			//------------------------------------	
			case "upload":
				
				$flash_uploader = false;
				$uploading = false;
				//temp_log("filevars(filedata): ".$fileVars["Filedata"]);
				//temp_log("dir: ".$dir);				
				//temp_log("dir decode: "."/".base64_decode(str_replace(" ", "+", $dir)));
				
				if (isset($fileVars["Filedata"]) )
				{
					$flash_uploader = true;
					$uploading = true;
					if( $dir != "" )
					{
						$dir = str_replace(" ", "+", $dir);
						$dir = base64_decode($dir);
						if ( $dir[0] != "/" )
						{
							$dir = "/".$dir;
						}
					}
				}
				
				if ( $dir != "" )
				{
					if ( $dir[0] != "/" )
					{
						$rep_source = "/".$dir;
					}
					else
					{
						$rep_source="$dir";
					}
				}
				else
				{
					$rep_source = "";
				}
				
				$fs_destination=SystemTextEncoding::fromUTF8($this->getPath().$rep_source);
				//temp_log("rep_source: ".$rep_source);
				//temp_log("fs destination: ".$fs_destination);
				//temp_log("file path: ".$this->getPath().$rep_source);
				
				if ( !$this->isWritable($fs_destination) )
				{
					$errorMessage = "$mess[38] ".SystemTextEncoding::toUTF8($dir)." $mess[99]";
					if ( $flash_uploader )
					{
						$this->send_upload_result_xml($errorMessage, TRUE);						
					}
					else
					{
						// KHJ20090917 File Upload 시 PHP Warning 제거
						print("<html><script language=\"javascript\">\n");
						print("\n if(parent.ajaxplorer.actionBar.multi_selector)parent.ajaxplorer.actionBar.multi_selector.submitNext('".str_replace("'", "\'", $errorMessage)."');");
						print("</script></html>");
					}
					exit;
				}	
				$logMessage = "";

				foreach ($fileVars as $boxName => $boxData)
				{
					if ( $boxName != "Filedata" && substr($boxName, 0, 9) != "userfile_" ) continue;
					if ( $boxName == "Filedata" ) $flash_uploader = true;
					$err = Utils::parseFileDataErrors($boxData, $flash_uploader);
					if ( $err != null )
					{
						$errorMessage = $err;
						break;
					}
					$userfile_name = $boxData["name"];
					if ( $flash_uploader ) $userfile_name = SystemTextEncoding::fromUTF8($userfile_name);
					$userfile_name = Utils::processFileName($userfile_name, $invalid_char_count);
					
					if ( $invalid_char_count > 0 )
					{
						$not_allowed_chars = chunk_split(NOT_ALLOWED_FILENAME_CHAR, 1, " ");
						$errorMessage = "$mess[37]. "."$mess[501]: $not_allowed_chars";
						break;
					}
					//temp_log("userfile_name: ".$userfile_name);
					
					if ( isset($auto_rename) )
					{
						// TODO KHJ20090915 overwrite auto rename (lowest priority)
						$userfile_name = $this->autoRenameForDest($fs_destination, $userfile_name);
					}
					
					// check destination folder exist
					if ( is_dir($fs_destination) == FALSE )
					{
						$errorMessage = $mess[38]." $rep_source ".$mess[99];
						break;
					}
					
					// KHJ comment: tmp_name has a uploaded file name which is full path
					if ( !is_uploaded_file($boxData["tmp_name"]) )
					{
						$errorMessage = $mess[33]." ".$userfile_name;
						break;
					}
					//경로에 '/' 두번 들어가는 경우 있었음.
					$check = substr($rep_source, -1);
					if ($check == "/")
					{
						$destination = SystemTextEncoding::fromUTF8($rep_source.$userfile_name);
					}
					else
					{
						$destination = SystemTextEncoding::fromUTF8($rep_source."/".$userfile_name);
					}
					$upload_result = $this->exec_move_uploaded_file($boxData["tmp_name"], $fs_destination."/".$userfile_name);
					if ( $upload_result === FALSE )
					//if ( $this->exec_move_uploaded_file($boxData["tmp_name"], $destination) == FALSE )
					{
						$errorMessage = $mess[33]." ".$userfile_name;
						$this->shell_exec_delete($boxData["tmp_name"]);
						break;
					}
					if ($upload_result === CHECK_QUOTA)
					{
						$errorMessage = $mess[503];
						$this->shell_exec_delete($boxData["tmp_name"]);
						break;
					}
					$this->exec_change_mode_own($fs_destination."/".$userfile_name);
					$logMessage .= $mess[34]." ".SystemTextEncoding::toUTF8($userfile_name)." $mess[35] $dir";
					//AJXP_Logger::logAction("Upload File", array("file"=>SystemTextEncoding::fromUTF8($dir)."/".$userfile_name));
				}
				
				if ( $flash_uploader )
				{
					if ( isset($errorMessage) )
					{
						$this->send_upload_result_xml($errorMessage, TRUE);
					}
					else
					{
						$this->send_upload_result_xml("OK", FALSE);
					}
				}
				else
				{
					print("<html><script language=\"javascript\">\n");
					if ( isset($errorMessage) )
					{
						print("\n if(parent.ajaxplorer.actionBar.multi_selector)parent.ajaxplorer.actionBar.multi_selector.submitNext('".str_replace("'", "\'", $errorMessage)."');");		
					}
					else
					{		
						print("\n if(parent.ajaxplorer.actionBar.multi_selector)parent.ajaxplorer.actionBar.multi_selector.submitNext();");
					}
					print("</script></html>");
				}
				if ($uploading == false)
				{
					$path_php_ini = "/etc/php/php.ini"; 		
					//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
					//$min = 20;
					//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
					$sec = 1200;
					exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
					$remote_session_file_name = $_SESSION["SESS_FILE"];
					$mess = ConfService::getMessages();
					$session_out_msg = $mess[509];
					if ( !file_exists($remote_session_file_name) )
					{
						$close_target = "parent";
						print("<html><script language=\"javascript\">\n");
						print("\n alert(\"".$session_out_msg."\");");
						print("\n parent.close();");
						print("</script></html>");
						exit(1);
					}
		
				}
				exit;
				
			break;
            
            //------------------------------------
            // Public URL
            //------------------------------------
            case "public_url":
		$path_php_ini = "/etc/php/php.ini"; 		
		//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
		//$min = 20;
		//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
		$sec = 1200;
		exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
		$remote_session_file_name = $_SESSION["SESS_FILE"];
		$mess = ConfService::getMessages();
		$session_out_msg = $mess[509];
		if ( !file_exists($remote_session_file_name) )
		{
			echo $session_out_msg;
			exit(1);
		}
		
		$file = SystemTextEncoding::fromUTF8($file);
		
		$real_srcfile_path = $this->getPath().$file;
		
		if(!$this->other_AccessRightsCheck($real_srcfile_path,"r"))
		{
			echo $mess[520];
			exit(1);
		}
		
		$pwd = $_POST['password'];
	    //패스워드 입력에 특수문자 걸러내기 - 패스워드 뒤에 '.' 삽입시 다운로드 되는 문제 있었음.
	    if(preg_match("/[!#$%^&*.()?+=\/]/",$password))
	    {
		//$mess = ConfService::getMessages();
		//$session_out_msg = $mess[500];
		echo $mess[521];
	        exit(1);	
	    }
	   
                $url = $this->makePubliclet($file, $password, $expiration);
                header("Content-type:text/plain");
                echo $url;
                exit(1);
            break;
			
			//------------------------------------
			//	XML LISTING
			//------------------------------------
			case "ls":
				$path_php_ini = "/etc/php/php.ini"; 		
				//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
				//$min = 20;
				//exec("sudo find /var/lock/session/ajxp/ -type f -mmin +$min -print0 | xargs -r -0 rm");
				$sec = 1200;
				exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
				$remote_session_file_name = $_SESSION["SESS_FILE"];
				$mess = ConfService::getMessages();
				$session_out_msg = $mess[509];
				if ( !file_exists($remote_session_file_name) )
				{
					AJXP_Exception::cautionToXml(new AJXP_Exception($session_out_msg), TRUE);
					exit(1);
				}
				
				if ( !isset($dir) )
				{
					$dir = "/";
				}
				
				if($dir == "/") $dir = "";
				$searchMode = $fileListMode = $completeMode = false;
				
				if(isset($mode))
				{
					if($mode == "search") $searchMode = true;
					else if($mode == "file_list") $fileListMode = true;
					else if($mode == "complete") $completeMode = true;
				}
				
				if(isset($skipZip) && $skipZip == "true")
				{
					$skipZip = true;
				}
				else
				{
					$skipZip = false;
				}
				
				//temp_log("dir path: ".$dir);
				/////////////////////////////////////////////
				// Zip File Processing
				if (  ($test = UserSelection::detectZip($dir))
					&& ($this->is_file_by_smbclient($test[0]) == 1) )
				{
					// KHJ20090917 Zip File만 Readability Check하도록 변경
					if ( $this->isReadable($this->getPath().$test[0]) == FALSE )
					{
						AJXP_Exception::errorToXml(new AJXP_Exception(208));
					}
					
					$liste = array();
					$zip = $this->zipListing($test[0], $test[1], $liste, $zip_ar);
					AJXP_XMLWriter::header();
					
					foreach ($liste as $zipEntry)
					{
						$atts = array();
						if(!$fileListMode && !$zipEntry["folder"]) continue;
						$atts[] = "is_file=\"".($zipEntry["folder"]?"false":"true")."\"";
						$atts[] = "text=\"".Utils::xmlEntities( basename(SystemTextEncoding::toUTF8($zipEntry["stored_filename"])))."\"";
						//temp_log("filename: ".SystemTextEncoding::toUTF8($zipEntry["filename"]));
						//temp_log("stored filename: ".SystemTextEncoding::toUTF8($zipEntry["stored_filename"]));
						$atts[] = "filename=\"".Utils::xmlEntities( SystemTextEncoding::toUTF8($zipEntry["filename"]))."\"";
						if ( $fileListMode )
						{
							$atts[] = "filesize=\"".Utils::roundSize($zipEntry["size"])."\"";
							$atts[] = "bytesize=\"".$zipEntry["size"]."\"";
							$atts[] = "ajxp_modiftime=\"".$zipEntry["mtime"]."\"";
							$atts[] = "mimestring=\"".Utils::mimetype($zipEntry["stored_filename"], "mime", $zipEntry["folder"])."\"";
							$atts[] = "icon=\"".Utils::mimetype($zipEntry["stored_filename"], "image", $zipEntry["folder"])."\"";
							$is_image = Utils::is_image(basename($zipEntry["stored_filename"]));
							$atts[] = "is_image=\"".$is_image."\"";
							if ( $is_image )
							{
								if( !isset($tmpDir) )
								{
									$this->CreateDownloadTempDir($tmpDir);
									if ( isset($tmpDir) )
									{
										register_shutdown_function(array($this, "DeleteTempDir"), $tmpDir);
									}
								}
								
								$currentFile = $tmpDir."/".basename($zipEntry["stored_filename"]);
								
								$extract_res = $this->zipExtractOneFile($test[0], $zipEntry["stored_filename"], ZIP_OPT_REMOVE_ALL_PATH, $tmpDir, 64 * 1024, $zip_ar);
								
								if ( $extract_res == TRUE )
								{
									list($width, $height, $type, $attr) = @getimagesize($currentFile);
									if ( $type == NULL )
									{
										$extract_res = $this->zipExtractOneFile($test[0], $zipEntry["stored_filename"], ZIP_OPT_REMOVE_ALL_PATH, $tmpDir, $zip_ar);
									}
									if ( $extract_res == TRUE )
									{
										list($width, $height, $type, $attr) = @getimagesize($currentFile);
										$atts[] = "image_type=\"".image_type_to_mime_type($type)."\"";
										$atts[] = "image_width=\"$width\"";
										$atts[] = "image_height=\"$height\"";
									}
								}
							}
						}
						else
						{
							$atts[] = "icon=\"client/images/foldericon.png\"";
							$atts[] = "openicon=\"client/images/foldericon.png\"";
							$atts[] = "src=\"".SERVER_ACCESS."?dir=".urlencode(SystemTextEncoding::toUTF8($zipEntry["filename"]))."\"";
						}						
						print("<tree ".join(" ", $atts)."/>");
					}
					$zip_ar->close();
					AJXP_XMLWriter::close();
					
					exit(0);
				}
				
				/////////////////////////////////////////////
				// Normal Dir Processing
				$nom_rep = $this->initName($dir);
				AJXP_Exception::errorToXml($nom_rep);
				
				$ls_res = smbclientAccessDriver::singleton()->ls_smbclient($dir == "" ? "/" : $dir."/", TRUE, $Files, $DiskUsage);
				if ( $ls_res < 0 )
				{
					// KHJ20090917 TODO smbclient cd, ls error 처리
					if ( $ls_res == NT_STATUS_OBJECT_NAME_NOT_FOUND )
					{
						AJXP_Exception::errorToXml(new AJXP_Exception(103)); // Cannot find folder
					}
					else if ( $ls_res == NT_STATUS_OBJECT_PATH_NOT_FOUND )
					{
						AJXP_Exception::errorToXml(new AJXP_Exception(72)); // Cannot find folder
					}
					else
					{
						AJXP_Exception::errorToXml(new AJXP_Exception(208));
					}
				}
				else
				{
					$threshold = $this->repository->getOption("PAGINATION_THRESHOLD");
					if(!isset($threshold) || intval($threshold) == 0) $threshold = 500;
					$limitPerPage = $this->repository->getOption("PAGINATION_NUMBER");
					if(!isset($limitPerPage) || intval($limitPerPage) == 0) $limitPerPage = 200;
					
					if ( $fileListMode )
					{
						$countFiles = count($Files);
						if ( $countFiles > $threshold )
						{
							$offset = 0;
							$crtPage = 1;
							if ( isset($page) )
							{
								$offset = (intval($page)-1)*$limitPerPage; 
								$crtPage = $page;
							}
							$totalPages = floor($countFiles / $limitPerPage) + 1;
							$reps = $this->listing($Files, false, $offset, $limitPerPage);
						}
						else
						{
							$reps = $this->listing($Files, $searchMode);
						}
					}
					else
					{
						$countFolders = $this->countFolders($Files);
						if ( $countFolders > $threshold )
						{
							AJXP_XMLWriter::header();
							$icon = CLIENT_RESOURCES_FOLDER."/images/foldericon.png";
							$openicon = CLIENT_RESOURCES_FOLDER."/images/openfoldericon.png";
							$attributes = "icon=\"$icon\"  openicon=\"$openicon\"";
							print("<tree text=\"$mess[306] ($countFolders)...\" $attributes></tree>");
							AJXP_XMLWriter::close();
							exit(1) ;
						}
						$reps = $this->listing($Files, !$searchMode);
					}
				}
				
				$trashbox_name = $this->repository->getOption("RECYCLE_BIN");
				
				AJXP_XMLWriter::header();
				if(isset($totalPages) && isset($crtPage)){
					print '<columns switchDisplayMode="list" switchGridMode="filelist"/>';
					print '<pagination count="'.$countFiles.'" total="'.$totalPages.'" current="'.$crtPage.'"/>';
				}

				foreach ($reps as $repIndex => $repName)
				{
					$is_file_element = $this->IsFile($Files, $repIndex);
					if((preg_match("/\.zip$/", strtolower($repName)) == 1 && $skipZip) && $is_file_element ) continue;
					$attributes = "";
					
					if($searchMode)
					{
						if(is_file($nom_rep."/".$repIndex)) {$attributes = "is_file=\"true\" icon=\"$repName\""; $repName = $repIndex;}
					}
					else if($fileListMode)
					{
						$currentFile = $nom_rep."/".$repIndex;			
						$atts = array();
						$atts[] = "is_file=\"".($is_file_element? "1" : "0")."\"";
						$atts[] = "is_image=\"".Utils::is_image($repIndex)."\"";
						// KHJ20090917 Delete group, owner, permission info
						$atts[] = "file_group=\"".filegroup($currentFile)."\"";
						$atts[] = "file_owner=\"".fileowner($currentFile)."\"";
						$atts[] = "file_perms=\"".substr(decoct( fileperms($currentFile) ), (is_file($currentFile)?2:1))."\"";
						if(Utils::is_image($repIndex))
						{
							list($width, $height, $type, $attr) = @getimagesize($currentFile);
							$atts[] = "image_type=\"".image_type_to_mime_type($type)."\"";
							$atts[] = "image_width=\"$width\"";
							$atts[] = "image_height=\"$height\"";
						}
						if ( $repIndex === $trashbox_name )
						{
							$atts[] = "mimestring=\"Trashcan\"";
						}
						else
						{
							$atts[] = "mimestring=\"".Utils::mimetype($repIndex, "type", $this->IsDirectory($Files, $repIndex))."\"";
						}
						$datemodif = $this->date_modif($Files, $repIndex);
						$atts[] = "ajxp_modiftime=\"".($datemodif ? $datemodif : "0")."\"";
						
						$bytesize = $this->GetFileSize($Files, $repIndex);
						if($bytesize < 0) $bytesize = sprintf("%u", $bytesize);
						$atts[] = "filesize=\"".Utils::roundSize($bytesize)."\"";
						$atts[] = "bytesize=\"".$bytesize."\"";						
						$atts[] = "filename=\"".Utils::xmlEntities( SystemTextEncoding::toUTF8($dir."/".$repIndex))."\"";
						if ( $repIndex === $trashbox_name )
						{
							$atts[] = "icon=\"trashcan.png\"";
						}
						else
						{
							$atts[] = "icon=\"".($this->IsFile($Files, $repIndex) ? SystemTextEncoding::toUTF8($repName) : ($this->IsDirectory($Files, $repIndex) ? "folder.png" : "mime-empty.png"))."\"";
						}
						
						$attributes = join(" ", $atts);
						$repName = $repIndex;
					}
					else 
					{
						$folderBaseName = Utils::xmlEntities($repName);
						$link = SystemTextEncoding::toUTF8(SERVER_ACCESS."?dir=".urlencode($dir."/".$repName));
						//$link = urlencode($link);
						$folderFullName = Utils::xmlEntities($dir)."/".$folderBaseName;
						$parentFolderName = $dir;
						if ( !$completeMode )
						{
							$icon = CLIENT_RESOURCES_FOLDER."/images/foldericon.png";
							$openicon = CLIENT_RESOURCES_FOLDER."/images/openfoldericon.png";
							if ( (preg_match("/\.zip$/", strtolower($repName)) == 1) && $is_file_element )
							{
								$icon = $openicon = CLIENT_RESOURCES_FOLDER."/images/crystal/actions/16/accessories-archiver.png";
							}
							
							if ( $repIndex === $trashbox_name )
							{
								$attributes = "icon=\"".CLIENT_RESOURCES_FOLDER."/images/crystal/mimes/16/trashcan.png\"  openIcon=\"".CLIENT_RESOURCES_FOLDER."/images/crystal/mimes/16/trashcan.png\"";
							}
							else
							{
								$attributes = "icon=\"$icon\"  openicon=\"$openicon\"";
							}
							$attributes .= " filename=\"".SystemTextEncoding::toUTF8($folderFullName)."\" src=\"$link\"";
						}
					}
					print("<tree text=\"".Utils::xmlEntities( SystemTextEncoding::toUTF8($repName))."\" $attributes>");
					print("</tree>");
				}
				AJXP_XMLWriter::close();
				exit(1);
				
			break;		
		}

		if(isset($logMessage) || isset($errorMessage))
		{
			$xmlBuffer .= AJXP_XMLWriter::sendMessage((isSet($logMessage)?$logMessage:null), (isSet($errorMessage)?$errorMessage:null), false);			
		}
		
		if(isset($requireAuth))
		{
			$xmlBuffer .= AJXP_XMLWriter::requireAuth(false);
		}
		
		if(isset($reload_current_node) && $reload_current_node == "true")
		{
			$xmlBuffer .= AJXP_XMLWriter::reloadCurrentNode(false);
		}
		
		if(isset($reload_dest_node) && $reload_dest_node != "")
		{
			$xmlBuffer .= AJXP_XMLWriter::reloadNode($reload_dest_node, false);
		}
		
		if(isset($reload_dest_node_uuid) && $reload_dest_node_uuid != "")
		{
			$xmlBuffer .= AJXP_XMLWriter::reloadNodeByUUID($reload_dest_node_uuid, false);
		}
		
		if(isset($reload_file_list))
		{
			$xmlBuffer .= AJXP_XMLWriter::reloadFileList($reload_file_list, false);
		}
		
		return $xmlBuffer;
	}
	
	private function send_upload_result_xml($result_message, $is_error) {
		$xml_data =  "<result><status>";
		$xml_data .= ($is_error ? "ERROR" : "OK");
		$xml_data .= "</status><message>";
		$xml_data .= Utils::xmlEntities($result_message);
		$xml_data .= "</message></result>";
		
		echo $xml_data;
	}
	
	function getPath() {
		return $this->repository->getOption("PATH");
	}
	
	function filterFile($fileName){
		$pathParts = pathinfo($fileName);
		if(array_key_exists("HIDE_FILENAMES", $this->driverConf) && is_array($this->driverConf["HIDE_FILENAMES"])){
			foreach ($this->driverConf["HIDE_FILENAMES"] as $search){
				if(strcasecmp($search, $pathParts["basename"]) == 0) return true;
			}
		}
		if(array_key_exists("HIDE_EXTENSIONS", $this->driverConf) && is_array($this->driverConf["HIDE_EXTENSIONS"])){
			foreach ($this->driverConf["HIDE_EXTENSIONS"] as $search){
				if(strcasecmp($search, $pathParts["extension"]) == 0) return true;
			}
		}
		return false;
	}
	
	function filterFolder($folderName){
		if(array_key_exists("HIDE_FOLDERS", $this->driverConf) && is_array($this->driverConf["HIDE_FOLDERS"])){
			foreach ($this->driverConf["HIDE_FOLDERS"] as $search){
				if(strcasecmp($search, $folderName) == 0) return true;
			}
		}
		return false;		
	}
	
	private function is_dir_path($path)
	{
		return ($path[strlen($path) - 1] == "/") ? TRUE : FALSE;
	}
	
	function initName($dir)
	{
		$racine = $this->getPath();		
		if(!isset($dir) || $dir=="" || $dir == "/")
		{
			$nom_rep = $racine;
		}
		else
		{
			$nom_rep = "$racine/$dir";
		}
		
		// KHJ20091110 delete for mangled file name, process by smbclient return value
		//if ( !file_exists($racine) )
		//{
		//	return new AJXP_Exception(72);
		//}
		//if ( !is_dir($nom_rep) )
		//{
		//	return new AJXP_Exception(103);
		//}
		
		return $nom_rep;
	}

	function getTrueSize($file)
	{
		//temp_log("get file size: ".$file);
		//temp_log("file size dir: ".dirname($file));
		
		$cmd = "LANG=".SYSTEM_DEFAULT_LOCALE." sudo stat -L -c%s \"".Utils::unix_shell_escape_args($file)."\"";
		$val = shell_exec($cmd);

		if (strlen($val) == 0 || floatval($val) == 0){
			// Still not working, get a value at least, not 0...
			$val = sprintf("%u", filesize($file));
		}
			
		return floatval($val);
	}
	
	private function crc32_file($filename)
	{
		//$start = microtime(true);
		$fp = fopen($filename, "rb");
		$crc32 = 0;
		$old_crc = 0;

		if ($fp != FALSE)
		{
			$buffer = '';
        
			while (!feof($fp))
			{
				$buffer = fread($fp, ZIP_FILE_TRANSFER_CHUNK_SIZE); 
				$len = strlen($buffer);       
				$t = crc32($buffer);    
			
				if ($old_crc != 0)
				{
					$crc32 = crc32_combine($old_crc, $t, $len);
					$old_crc = $crc32;
				}
				else
				{
					$crc32 = $old_crc = $t;
				}
			}
			fclose($fp);
		}
		//$end = microtime(true);
		//temp_log("crc32 calculate time: ".$filename." ==> ".($end - $start));
		
		return $crc32;
	}

	private function eight_byte_float_to_little_endian_binary($floatvalue)
	{
		$float_string = (string) $floatvalue;
		
		if ( strpos($float_string, ".") !== FALSE )
		{
			$float_string = substr($float_string, 0, strpos($float_string, "."));
		}
		
		$hex_float_string = base_convert($float_string, 10, 16);
		$zero_pad_count = 16 - strlen($hex_float_string);
		for ( $i = 0; $i < $zero_pad_count; $i++ )
		{
	        $hex_float_string = "0".$hex_float_string;
		}
		
		$byte_array = str_split($hex_float_string, 2);
		$byte_array = array_reverse($byte_array);
		$hex_float_string = implode($byte_array);
		
		$result_binary = pack("H*", $hex_float_string);
		
		return $result_binary;
	}
	
	function add_zip_entry_info_for_archive($filename_in_hdd, $filename_in_zip, $is_utf8, &$zip_todo_entry, &$localfile_transfer_size, &$cdr_transfer_size)
	{
		
		$cdr_extra_field_size = 0;
		
		//temp_log("file name in hdd: ".$filename_in_hdd);
		//temp_log("file name in zip: ".$filename_in_zip);
		$current_filesize = floatval(trim($this->getTrueSize($filename_in_hdd)));
		//temp_log("file size: ".$current_filesize);
		$current_localheader_offset = $localfile_transfer_size;
		$localfile_transfer_size += (ZIP_META_DATA_LOCAL_HEADER_LENGTH
									 + strlen($filename_in_zip)
									 + $current_filesize
									 + ZIP_META_DATA_DESCRIPTOR_LENGTH);
		
		$filesize_zip64_needed = FALSE;
		if ( $current_filesize > 0xFFFFFFFF )
		{
			$filesize_zip64_needed = TRUE;
			$localfile_transfer_size += ZIP64_META_DATA_DESCRIPTOR_ADD_LENGTH;
			$localfile_transfer_size += ZIP64_META_DATA_LOCAL_HEADER_EXTRA_FIELD_LENGTH;
			$cdr_extra_field_size += (2 + 2 + 16); // 0x0001 (2) + length (2)+ file size (8 * 2)
		}
		
		$offset_zip64_needed = FALSE;
		if ( $current_localheader_offset > 0xFFFFFFFF )
		{
			$offset_zip64_needed = TRUE;
			if ( $filesize_zip64_needed == FALSE )
			{
				$cdr_extra_field_size = 4; // 0x0001 (2) + length (2)
			}
			$cdr_extra_field_size += 8; // offset (8)
		}
		
		$data_descriptor_needed = TRUE;
		$crc = 0;
		
		$cdr_transfer_size += (ZIP_META_DATA_CDR_LENGTH
							   + strlen($filename_in_zip)
							   + $cdr_extra_field_size);
		
		$zip_todo_entry[] = array("filename" => $filename_in_hdd,
								 "stored_filename" => $filename_in_zip,
								 "is_utf8" => $is_utf8,
								 "size" => $current_filesize,
								 "data_descriptor_needed" => $data_descriptor_needed,
								 "crc32" => $crc,
								 "filesize_zip64_needed" => $filesize_zip64_needed,
								 "offset_zip64_needed" => $offset_zip64_needed,
								 "cdr_extra_field_length" => $cdr_extra_field_size);
		
		
	}
	
	function makeZipAndSendToClient($selected_files, $basedir, $inzip, $localName)
	{
		// 1. build file list
		mb_substitute_character("none");
		setlocale(LC_ALL, SYSTEM_DEFAULT_LOCALE);
    	$safeMode =  (@ini_get("safe_mode") == 'On' || @ini_get("safe_mode") === 1) ? TRUE : FALSE;
    	if(!$safeMode){
	    	set_time_limit(7200);
    	}
		
		//temp_log("make zip dest: ".$dest);
		//temp_log("make base dir: ".$basedir);
		$client_os_detect = Utils::DetectClientOS();
		$client_os = $client_os_detect["os"];
		unset($client_os_detect);
		
		$client_charset = $_SESSION['AXJP_CLIENT_CHARSET'];
		
		$filePaths = array();
		
		$localfile_transfer_size = 0;
		$cdr_transfer_size = 0;
		$zip_entry_count = 0;
		
		foreach($selected_files as $item)
		{
			$filename = $inzip ? $item : $this->getPath().$item;
			//temp_log("zip src file: ".$filename);
			
			//20100520 HSY - ADDED for file permission problem when www-data(other) have no permission to read file
			$fileperm_bak = substr(decoct( fileperms($filename) ), (is_file($filename)?2:1));
			$chmodValue = '0777';
			$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($filename).'"';
			exec($shell_cmd, $shell_output, $shell_result);
				
			
			if ( !is_dir($filename) )
			{				
				//temp_log("zip original local name: ".$item);
				//$item = substr($item, strlen(str_replace("//", "/", $basedir)));
				$item = substr($item, strlen($basedir));
				
				//temp_log("zip local name: ".$item);
				if ( $item[0] == "/" ) $item = substr($item, 1);
				$item = Utils::convert_from_utf8_to_client_encoding($item, $client_charset, $is_utf8, TRUE);
				
				$zip_entry_count++;
				$this->add_zip_entry_info_for_archive($filename, $item, $is_utf8, $filePaths, $localfile_transfer_size, $cdr_transfer_size);
			}
			else
			{
				if ( $filename[strlen($filename) - 1] != "/" ) $filename .= "/";
				
				$dirStack = array($filename);
				//temp_log("dir file: ".$filename);
				//Find the index where the last dir starts 
				$cutFrom = strrpos(substr($filename, 0, -1), "/") + 1; 
	
				while (!empty($dirStack))
				{
					
			
					$currentDir = array_pop($dirStack);
					$filesToAdd = array();
					
					//////////////
					$fileperm_bak2 = substr(decoct( fileperms($currentDir) ), (is_file($currentDir)?2:1));
					$chmodValue = '0777';
					$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($currentDir).'"';
					exec($shell_cmd, $shell_output, $shell_result);
					///////////////
	
					$dir = dir($currentDir);
					//temp_log("current dir: ".$currentDir);
					while ( false !== ($node = $dir->read()) )
					{ 
						if ( ($node == "..") || ($node == ".") )
						{
							continue; 
						}
						//temp_log("cur file: ".$currentDir.$node);
						if ( is_dir($currentDir.$node) )
						{
							array_push($dirStack, $currentDir.$node."/");
							//temp_log("dir push: ".$currentDir.$node."/");
							
						} 
						if ( is_file($currentDir.$node) )
						{
							$filesToAdd[] = $node;
							//temp_log("file add: ".$node);
						}
					}
	
					$localDir = substr($currentDir, $cutFrom);
					//temp_log("dir create: ".$localDir);
					
					foreach ($filesToAdd as $file)
					{
						$filename_for_client = $localDir.$file;
						$filename_for_client = Utils::convert_from_utf8_to_client_encoding($filename_for_client, $client_charset, $is_utf8, TRUE);
						
						$zip_entry_count++;
						$this->add_zip_entry_info_for_archive($currentDir.$file, $filename_for_client, $is_utf8, $filePaths, $localfile_transfer_size, $cdr_transfer_size);
					}
					///////
					$shell_cmd = 'sudo chmod '.$fileperm_bak2.' "'.Utils::unix_shell_escape_args($currentDir).'"';
					exec($shell_cmd, $shell_output, $shell_result);
					/////
				}
			}
			
			//20100520 HSY - ADDED for file permission problem when www-data(other) have no permission to read file
			$shell_cmd = 'sudo chmod '.$fileperm_bak.' "'.Utils::unix_shell_escape_args($filename).'"';
			exec($shell_cmd, $shell_output, $shell_result);
			
		}
		
		$total_transfer_size = $localfile_transfer_size + $cdr_transfer_size + ZIP_META_LENGTH_PER_FILE; // add end of central directory entry size
		//temp_log("total transfer size: ".$total_transfer_size);
		
		$zip64_needed = FALSE;
		if ( ($localfile_transfer_size > 0xFFFFFFFF) || ($zip_entry_count > 0xFFFF) || ($cdr_transfer_size > 0xFFFFFFFF) )
		{
			$zip64_needed = TRUE;
			
			$total_transfer_size += ZIP64_META_LENGTH_PER_FILE;
		}
		
		// 2. send http header
		session_write_close();
		@ob_clean();
		
		if ( (strpos($_SERVER['HTTP_USER_AGENT'], ' MSIE ') !== FALSE)
		   || (strpos($_SERVER['HTTP_USER_AGENT'], ' WebKit ') !== FALSE) )
		{
			$localName = str_replace("+", " ", urlencode(SystemTextEncoding::toUTF8($localName)));
		}	
		
		// KHJ20091007 file download 일시 정지를 지원하려면 너무 복잡. 지원 안하기로...
		// 일시 정지를 지원하려면 아래의 none을 bytes로...
		header("Accept-Ranges: none");
		header('Content-Description: File Transfer');
		if ( $client_os == "mac" )
		{
			// mac osx snowleopard에서 확인해본 결과 octet-stream으로 보내야만, safari, firefox에서 file type을 정상적으로 표시해 줌
			header("Content-Type: application/octet-stream");
		}
		else
		{
			header("Content-Type: application/force-download; name=\"".$localName."\"");
		}		
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$total_transfer_size);
		// KHJ20091007 일시 정지 지원 안함
		//if ($total_transfer_size != 0) header("Content-Range: bytes 0-" . ($total_transfer_size - 1) . "/" . $total_transfer_size . ";");
		header("Content-Disposition: attachment; filename=\"".$localName."\"");
		header("Expires: 0");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		if ( Utils::get_msie_version() == "6.0" )
		{
			header("Cache-Control: max_age=0");
			header("Pragma: public");
		}

		// For SSL websites there is a bug with IE see article KB 323308
		// therefore we must reset the Cache-Control and Pragma Header
		if (ConfService::getConf("USE_HTTPS")==1 && strpos($_SERVER['HTTP_USER_AGENT'], ' MSIE ') !== FALSE)
		{
			header("Cache-Control:");
			header("Pragma:");
		}
		
		// 3. send http contents (zip local header, file contents, zip central directory header)
		$local_header_offset = 0;
		$filename_length = 0;
		$central_directory_record = array();
		
		// KHJ20091008
		// Linux Gnome file-roller에서 zip file내부의 한글이 깨짐
		// Linux KDE의 Arc는 정상 표시
		// Info Zip 3.0으로 압축 시 file-roller, arc 둘 다 문제 없음
		// 차이점을 살펴보니, version made by 값이 host os: 0x03 unix, version: 0x1E 3.1 로 setting되어 있음
		// 동일 setting후 windows, linux, mac 모두 확인 결과 한글 문제 없음
		//$version_madeby = 20;
		//$version_madeby = 0x031e;
		$version_madeby = 0x001e;
		
		$version_extracted = 10; // default
		$version_extracted_zip64 = 45; // 4.5 : file uses ZIP64 format extension
		$compression_method = 0; // just store
		
		//temp_log("file count to compress: ".count($filePaths));
		
		foreach ( $filePaths as $one_file )
		{
			//temp_log("compress src file: ".$one_file["filename"]);
			//temp_log("compress stored name: ".$one_file["stored_filename"]);
			//temp_log("compress file size: ".$one_file["size"]);
			
			//20100520 HSY - ADDED for file permission problem when www-data(other) have no permission to read file
			$realSrcFile = $one_file["filename"];
			$fileperm_bak = substr(decoct( fileperms($realSrcFile) ), (is_file($realSrcFile)?2:1));
			$chmodValue = '0777';
			$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($realSrcFile).'"';
			exec($shell_cmd, $shell_output, $shell_result);
						
			if ( connection_status() != 0 )
			{
				return FALSE;
			}
			
			//temp_log("fopen try: ".$one_file["filename"]);
			
			$file_size = $one_file["size"];
			
			$fd = fopen($one_file["filename"], "rb");
			if ( $fd === FALSE )
			{
				temp_log("fopen fail: ".$one_file["filename"]);
				continue;
			}
			
			//temp_log("fopen success");
			
			// 3.1 send local header
			//$mtime = filemtime($one_file["filename"]);
			$mtime = exec('sudo stat -c %Y "'.Utils::unix_shell_escape_args($one_file["filename"]).'"');
			//temp_log("get filemtime".($mtime == "" ? "FAIL" : "OK $mtime"));
			$filename_length = strlen($one_file["stored_filename"]);
			
			$localheader_file_size = 0;
			$crc32 = 0;
			
			// KHJ20091008
			// mac의 archive utility가 CRC를 data descriptior, central directory record로 보낸 것을 인식하지 못하고
			// 잘못된 zip file로 인식함
			// 따라서 mac의 경우 crc를 미리 계산하여 localfile header에 삽입하는 방식을 이용
			// CRC를 미리 계산함으로 인해 client에서 download window가 뜰 떄까지 지연 현상이 생기게 됨.
			$localheader_file_size = ($one_file["filesize_zip64_needed"] == TRUE) ? 0xFFFFFFFF : 0;
			$localheader_extra_field_length = 0;
			
			if ( $one_file["filesize_zip64_needed"] == TRUE )
			{
				$localheader_extra_field_length = ZIP64_META_DATA_LOCAL_HEADER_EXTRA_FIELD_LENGTH;
				$eight_byte_file_size_binary = $this->eight_byte_float_to_little_endian_binary($file_size);
			}
			
			if ( $one_file["is_utf8"] == TRUE )
			{
				$general_flag = 0x0808; // bit 3 on for CRC, bit 11 for UTF-8
			}
			else
			{
				$general_flag = 0x0008; // bit 3 on for CRC
			}
			
			if ( $one_file["data_descriptor_needed"] == FALSE )
			{
				$general_flag &= 0xFFF7; // bit 3 off
				$crc32 = $one_file["crc32"];
				$localheader_file_size = ($one_file["filesize_zip64_needed"] == TRUE) ? 0xFFFFFFFF : $file_size;
			}
			
			// ----- Transform UNIX mtime to DOS format mdate/mtime
			$v_date = getdate($mtime);
			$v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
			$v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];
		
			// ----- Packed data : 30 byte + file name length
			$localfileheader = pack("VvvvvvVVVvv", 0x04034b50,
								  $version_extracted, $general_flag,
								  $compression_method, $v_mtime, $v_mdate,
								  $crc32, // CRC
								  $localheader_file_size, // compressed size
								  $localheader_file_size, // original size
								  $filename_length,
								  $localheader_extra_field_length // extra field length
								);
		
			$localfileheader .= $one_file["stored_filename"];
			
			if ( $localheader_extra_field_length > 0 )
			{
				$localfileheader .= pack("vv", 0x0001, $localheader_extra_field_length - 4);
				$localfileheader .= $eight_byte_file_size_binary;
				$localfileheader .= $eight_byte_file_size_binary;
			}
			
			echo $localfileheader;
			
			//temp_log("send local header");
			
			// 3.2 send file contents & calculate CRC32 value
			if ( $file_size != 0 )
			{
				$old_crc = 0;
				$buffer = "";
				
				@ob_flush();
				@flush();
				
				while (!feof($fd) && connection_status() == 0)
				{
					$buffer = fread($fd, ZIP_FILE_TRANSFER_CHUNK_SIZE);
					echo $buffer;
					@ob_flush();
					@flush();
					
					// KHJ20091008 data descriptor가 없는 경우 CRC를 미리 계산해 두었음
					if ( $one_file["data_descriptor_needed"] == TRUE )
					{
						$cur_crc32 = crc32( $buffer );    
						
						if ( $old_crc != 0 )
						{
							$len = strlen( $buffer );
							$crc32 = crc32_combine($old_crc, $cur_crc32, $len);
							$old_crc = $crc32;
						}
						else
						{
							$crc32 = $old_crc = $cur_crc32;
						}
					}
				}
				fclose($fd);
			}
			
			// 3.3 send Data Descriptor : 12 bytes
			// CRC, compressed filesize, uncompressed filesize
			// KHJ20091008 mac의 경우 data descriptor가 있으면 잘못된 zip file로 인식함		
			if ( $one_file["data_descriptor_needed"] == TRUE )
			{
				if ( $one_file["filesize_zip64_needed"] == TRUE )
				{
					$datadescriptor = pack("VV", 0x08074b50, $crc32);
					
					$datadescriptor .= $eight_byte_file_size_binary;
					$datadescriptor .= $eight_byte_file_size_binary;
				}
				else
				{
					$datadescriptor = pack("VVVV", 0x08074b50, $crc32, $file_size, $file_size);
				}
			
				echo $datadescriptor;
			}
			
			// 3.4 Prepare Central Directory Record : 46 bytes + file name
			$cdrec_extra_field_length = 0;
			$cdrec_extra_field_length = $one_file["cdr_extra_field_length"];
			$cdrec_file_size = $file_size;
			$cdrec_local_header_offset = $local_header_offset;
			if ( $cdrec_extra_field_length > 0 )
			{
				if ( $one_file["filesize_zip64_needed"] == TRUE )
				{
					$cdrec_file_size = 0xFFFFFFFF;
				}
				
				if ( $one_file["offset_zip64_needed"] == TRUE )
				{
					$cdrec_local_header_offset = 0xFFFFFFFF;
				}
			}
			$cdrec = pack("VvvvvvvVVVvvvvvVV", 0x02014b50,
	                      $version_madeby, $version_extracted,
                          $general_flag, $compression_method,
						  $v_mtime, $v_mdate, $crc32,
                          $cdrec_file_size, $cdrec_file_size,
                          $filename_length,
						  $cdrec_extra_field_length,
						  0, 0, 0, 0,
						  $cdrec_local_header_offset);

			$cdrec .= $one_file["stored_filename"];
			if ( $cdrec_extra_field_length > 0 )
			{
				// extra field for ZIP64
				$cdrec .= pack("vv", 0x0001, $cdrec_extra_field_length - 4);
				
				if ( $one_file["filesize_zip64_needed"] == TRUE )
				{
					$cdrec .= $eight_byte_file_size_binary; // original size
					$cdrec .= $eight_byte_file_size_binary; // compressed size
				}
				
				if ( $one_file["offset_zip64_needed"] == TRUE )
				{
					$cdrec .= $this->eight_byte_float_to_little_endian_binary($local_header_offset); // relative header offset
				}
			}
			$central_directory_record[] = $cdrec;
			
			// update local header offset
			// 30: local header size + local file name length
			// file size (no compression)
			// data descriptor
			// KHJ20091008 mac의 경우 data descriptor를 보내지 않았음
			$local_header_offset +=( ZIP_META_DATA_LOCAL_HEADER_LENGTH + $filename_length + $localheader_extra_field_length + $file_size);
			if ( $one_file["data_descriptor_needed"] == TRUE )
			{
				$local_header_offset += ZIP_META_DATA_DESCRIPTOR_LENGTH;
				
				if ( $one_file["filesize_zip64_needed"] == TRUE )
				{
					$local_header_offset += ZIP64_META_DATA_DESCRIPTOR_ADD_LENGTH;
				}
			}
			//20100520 HSY - ADDED for file permission problem when www-data(other) have no permission to read file
			$shell_cmd = 'sudo chmod '.$fileperm_bak.' "'.Utils::unix_shell_escape_args($realSrcFile).'"';
			exec($shell_cmd, $shell_output, $shell_result);
			

		}
		
		unset($filePaths);
		
		// 4. send Central Directory Record
		foreach($central_directory_record as $one_cdrecord)
		{
			echo $one_cdrecord;
		}
		
		unset($central_directory_record);
		
		if ( $zip64_needed == TRUE )
		{
			// 5 prev 0. send Zip64 end of central directory record: 56 bytes
			// signature: 4 bytes
			$zip64_endcdrec = pack("V", 0x06064b50);
			// size of zip64 end of central directory record 8 bytes
			$zip64_endcdrec .= $this->eight_byte_float_to_little_endian_binary(44); // no zip64 extensible data sector
			// version made by: 2 bytes
			// version needed to extract: 2 bytes
			// number of this disk: 4 bytes => 0
			// number of the disk with the start of the central directory: 4 bytes => 0
			$zip64_endcdrec .= pack("vvVV", $version_madeby, $version_extracted_zip64, 0, 0);
			// total number of entries in the central directory on this disk: 8 bytes
			// total number of entries in the central directory: 8 bytes
			$eight_byte_cdr_count_binary = $this->eight_byte_float_to_little_endian_binary($zip_entry_count);
			$zip64_endcdrec .= $eight_byte_cdr_count_binary;
			$zip64_endcdrec .= $eight_byte_cdr_count_binary;
			// size of the central directory: 8 bytes
			$zip64_endcdrec .= $this->eight_byte_float_to_little_endian_binary($cdr_transfer_size);
			// offset of start of central directory with respect to the starting disk number: 8 bytes
			$zip64_endcdrec .= $this->eight_byte_float_to_little_endian_binary($local_header_offset);
			
			echo $zip64_endcdrec;
			
			// 5 prev 1. send Zip64 end of central directory locator: 20 bytes
			// signature: 4 bytes
			// number of the disk with the start of the zip64 end of central directory: 4 bytes => 0
			$zip64_endcdloc = pack("VV", 0x07064b50, 0);
			// relative offset of the zip64 end of central directory record: 8 bytes
			$zip64_endcdloc .= $this->eight_byte_float_to_little_endian_binary($local_header_offset + $cdr_transfer_size);
			//  total number of disks: 4 bytes => 1
			$zip64_endcdloc .= pack("V", 1);
			
			echo $zip64_endcdloc;
		}
		
		// 5. send End of central directory record : 22 bytes
		$endcdr_central_directory_record_count = $zip_entry_count;
		$endcdr_central_directory_record_size = $cdr_transfer_size;
		$endcdr_cdr_start_offset = $local_header_offset;
		if ( $zip64_needed == TRUE )
		{
			if ( $zip_entry_count > 0xFFFF )
			{
				$endcdr_central_directory_record_count = 0xFFFF;
			}
			
			if ( $cdr_transfer_size > 0xFFFFFFFF )
			{
				$endcdr_central_directory_record_size = 0xFFFFFFFF;
			}
			
			$endcdr_cdr_start_offset = 0xFFFFFFFF;
		}
		$endcdrec = pack("VvvvvVVv", 0x06054b50, 0, 0, $endcdr_central_directory_record_count,
	                      $endcdr_central_directory_record_count, $endcdr_central_directory_record_size,
						  $endcdr_cdr_start_offset, 0);
		echo $endcdrec;
		
		flush();
		
		return TRUE;
	}
	
	function read_file_in_zip($zip_filename, $inzip_filename, $is_public_link)
	{
		session_write_close();
		@ob_clean();
		setlocale(LC_ALL, SYSTEM_DEFAULT_LOCALE);
		
		$zip_ar = new ZipArchive();
		$res = $zip_ar->open($this->getPath()."/".$zip_filename);
		
		if ( !$res )
		{
			//temp_log("zip file open error: ".$res);
			exit(1);
		}
		
		if ( $inzip_filename[0] == "/" ) $inzip_filename = substr($inzip_filename, 1);
		
		$index = $this->zipFindIndex($zip_ar, $inzip_filename);
		
		$zip_stat = $zip_ar->statIndex($index[0]);
		
		$size = $zip_stat["size"];
		$stored_inzip_filename = $zip_stat["name"];
		//temp_log("size: ".$size);
		
		$client_filename = basename($inzip_filename);
		
		$is_msie_browser = (strpos($_SERVER['HTTP_USER_AGENT'], ' MSIE ') !== FALSE) ? TRUE : FALSE;
		if ( $is_msie_browser || (strpos($_SERVER['HTTP_USER_AGENT'], ' WebKit ') !== FALSE) )
		{
			$client_filename = str_replace("+", " ", urlencode(SystemTextEncoding::toUTF8($client_filename)));
		}
		
		// KHJ20091021 이어받기 web server에서 지원하지 않음
		// 일단 지원할 때 까지 기능 off
		//header("Accept-Ranges: bytes");
		header("Accept-Ranges: none");
			
		//header("Content-Type: application/force-download; name=\"".$client_filename."\"");
		header("Content-Type: application/octet-stream");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$size);
		if ($size != 0) header("Content-Range: bytes 0-" . ($size - 1) . "/" . $size . ";");
		header("Content-Disposition: attachment; filename=\"".$client_filename."\"");
		header("Expires: 0");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		if ( $is_msie_browser && (Utils::get_msie_version() == "6.0" || $is_public_link) )
		{
			header("Cache-Control: max_age=0");
			header("Pragma: public");
		}
		else
		{
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
		}
		
		// For SSL websites there is a bug with IE see article KB 323308
		// therefore we must reset the Cache-Control and Pragma Header
		if (ConfService::getConf("USE_HTTPS")==1 && strpos($_SERVER['HTTP_USER_AGENT'], ' MSIE ') !== FALSE)
		{
			header("Cache-Control:");
			header("Pragma:");
		}
		
		// KHJ20091019 readfile을 chunk단위로 read하는 code로 변경 (5GB file download test시 1GB download에서 중지 됨)
		@ob_flush();
		@flush();
		
		$fp = $zip_ar->getStream($stored_inzip_filename);
		if ( !$fp )
		{
			//temp_log("get stream error: ".$stored_inzip_filename." status: ".$zip_ar->GetStatusString());
			exit(1);
		}
		
		while( !feof($fp) && connection_status() == 0 )
		{
			echo @fread($fp, ONE_FILE_TRANSFER_CHUNK_SIZE);
			@ob_flush();
			@flush();
		}
		
		fclose($fp);
		@ob_flush();
		@flush();
		$zip_ar->close();
	}

	function readFile2($filePath, $headerType="plain", $localName="", $is_public_link = FALSE)
	{
		session_write_close();
		@ob_clean();
		setlocale(LC_ALL, SYSTEM_DEFAULT_LOCALE);
		
		$fileperm_bak = substr(decoct( fileperms($filePath) ), (is_file($filePath)?2:1));
		$chmodValue = '0777';
		$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($filePath).'"';
		exec($shell_cmd, $shell_output, $shell_result);
		
		//temp_log("smb path: ".$smbPath);
		
		//temp_log("name0: ".$localName);
		$localName = ($localName=="" ? basename($filePath) : $localName);
		//temp_log("path: ".$filePath);
		//temp_log("name: ".$localName);
		
		$size = floatval(trim($this->getTrueSize($filePath)));
		//temp_log("size: ".$size);
		
		if($headerType == "plain")
		{
			header("Content-type:text/plain");
		}
		else if($headerType == "image")
		{
			header("Content-Type: ".Utils::getImageMimeType(basename($filePath))."; name=\"".$localName."\"");
			header("Content-Length: ".$size);
			header('Cache-Control: public');
		}
		else if($headerType == "mp3")
		{
			header("Content-Type: audio/mp3; name=\"".$localName."\"");
			header("Content-Length: ".$size);
		}
		else
		{
			$is_msie_browser = (strpos($_SERVER['HTTP_USER_AGENT'], ' MSIE ') !== FALSE) ? TRUE : FALSE;
			if ( $is_msie_browser || (strpos($_SERVER['HTTP_USER_AGENT'], ' WebKit ') !== FALSE) )
			{
				$localName = str_replace("+", " ", urlencode(SystemTextEncoding::toUTF8($localName)));
			}

			// KHJ20091021 이어받기 web server에서 지원하지 않음
			// 일단 지원할 때 까지 기능 off
			//header("Accept-Ranges: bytes");
			header("Accept-Ranges: none");
			
			// Check if we have a range header (we are resuming a transfer)
			if ( isset($_SERVER['HTTP_RANGE']) && $size != 0 )
			{
				// multiple ranges, which can become pretty complex, so ignore it for now
				$ranges = explode('=', $_SERVER['HTTP_RANGE']);
				$offsets = explode('-', $ranges[1]);
				$offset = floatval($offsets[0]);

				$length = floatval($offsets[1]) - $offset;
				if (!$length) $length = $size - $offset;
				if ($length + $offset > $size || $length < 0) $length = $size - $offset;
				header('HTTP/1.1 206 Partial Content');

				header('Content-Range: bytes ' . $offset . '-' . ($offset + $length - 1) . '/' . $size);
				header("Content-Length: ". $length);
				$file = fopen($filePath, 'rb');
				fseek($file, 0);
				$relOffset = $offset;
				while ($relOffset > 2.0E9)
				{
					// seek to the requested offset, this is 0 if it's not a partial content request
					fseek($file, 2000000000, SEEK_CUR);
					$relOffset -= 2000000000;
					// This works because we never overcome the PHP 32 bit limit
				}
				fseek($file, $relOffset, SEEK_CUR);

				$readSize = 0.0;
				while (!feof($file) && $readSize < $length && connection_status() == 0)
				{
					echo fread($file, 2048);
					$readSize += 2048.0;
					flush();
				}
				fclose($file);
				
				return TRUE;
			}
			else
			{
				//header("Content-Type: application/force-download; name=\"".$localName."\"");
				header("Content-Type: application/octet-stream");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".$size);
				if ($size != 0) header("Content-Range: bytes 0-" . ($size - 1) . "/" . $size . ";");
				header("Content-Disposition: attachment; filename=\"".$localName."\"");
				header("Expires: 0");				
				if ( $is_msie_browser && (Utils::get_msie_version() == "6.0" || $is_public_link) )
				{
					header("Cache-Control: max_age=0");
					header("Pragma: public");
				}
				else
				{
					header("Cache-Control: no-cache, must-revalidate");
					header("Pragma: no-cache");
				}

				// For SSL websites there is a bug with IE see article KB 323308
				// therefore we must reset the Cache-Control and Pragma Header
				if (ConfService::getConf("USE_HTTPS")==1 && strpos($_SERVER['HTTP_USER_AGENT'], ' MSIE ') !== FALSE)
				{
					header("Cache-Control:");
					header("Pragma:");
				}
			}
		}
		
		// KHJ20091019 readfile을 chunk단위로 read하는 code로 변경 (5GB file download test시 1GB download에서 중지 됨)
		@ob_flush();
		@flush();
		
		$fp = fopen($filePath, "rb");
		
		if ( $fp === FALSE )
		{
			temp_log("fopen error");
			exit(1);
		}
		
		while( !feof($fp) && connection_status() == 0 )
		{
			echo @fread($fp, ONE_FILE_TRANSFER_CHUNK_SIZE);
			@ob_flush();
			@flush();
		}
		
		fclose($fp);
		@ob_flush();
		@flush();
		
		$shell_cmd = 'sudo chmod '.$fileperm_bak.' "'.Utils::unix_shell_escape_args($filePath).'"';
		exec($shell_cmd, $shell_output, $shell_result);
		
	}
	
	function IsFile($Files, $FileName)
	{
		return (strpos($Files[$FileName]["MODE"], "D") === FALSE) ? TRUE : FALSE;
	}
	
	function IsDirectory($Files, $FileName)
	{
		return (strpos($Files[$FileName]["MODE"], "D") === FALSE) ? FALSE : TRUE;
	}
	
	function GetFileSize($Files, $FileName)
	{
		$FileSize = $Files[$FileName]["SIZE"];
		if ( !isset($FileSize) )
		{
			$FileSize = 0;
		}
		
		return $FileSize;
	}
	
	private function countFolders($files)
	{
		$folder_count = 0;
		foreach($files as $one_file)
		{
			if (strpos($one_file["MODE"], "D") !== FALSE)
			{
				$folder_count++;
			}
		}
		
		return $folder_count;
	}
	
	function listing($Files, $dir_only = false, $offset = 0, $limit = 0)
	{
		$mess = ConfService::getMessages();
		
		$size_unit = $mess["byte_unit_symbol"];
		$orderDir = 0;
		$orderBy = "filename";
		
		foreach ( $Files as &$OneFile )
		{
			$isHiddenFile = (strpos($OneFile["MODE"], "H") === FALSE) ? FALSE : TRUE;
			if ( !($isHiddenFile && !$this->driverConf["SHOW_HIDDEN_FILES"]) )
			{
				$FileName = $OneFile["FILENAME"];
				
				$isDirFile = (strpos($OneFile["MODE"], "D") === FALSE) ? FALSE : TRUE;
				
				if ( $isDirFile )
				{
					// KHJ20091024 folder에 대한 filter는 samba에서 이미 적용된다.
					//if ( $this->filterFolder($FileName) ) continue;					
					$liste_rep[$FileName] = ($orderBy == "mod") ? $OneFile["MTIME"] : $FileName;
				}
				else
				{
					// KHJ20091024 file 대한 filter는 samba에서 이미 적용된다.
					//if ( $this->filterFile($FileName) ) continue;					
					if ( !$dir_only )
					{
						if ( $orderBy == "filename" )
						{
							//temp_log("name: ".$OneFile["FILENAME"]);
							$liste_fic[$FileName] = Utils::mimetype($FileName, "image", $isDirFile);
						}
						else if ( $orderBy == "filesize" )
						{
							$liste_fic[$FileName] = ($OneFile["SIZE"] == "") ? 0 : $OneFile["SIZE"] + 0;
						}
						else if ( $orderBy == "mod" )
						{
							$liste_fic[$FileName] = $OneFile["MTIME"];
						}
						else if ( $orderBy == "filetype" )
						{
							$liste_fic[$FileName] = Utils::mimetype($FileName, "type", $isDirFile);
						}
						else
						{
							$liste_fic[$FileName] = Utils::mimetype($FileName, "image", $isDirFile);
						}
					}
					else if ( preg_match("/\.zip$/", strtolower($FileName)) == 1 && ConfService::zipEnabled() )
					{
						if( !isset($liste_zip) )
						{
							$liste_zip = array();
						}
							
						$liste_zip[$FileName] = $FileName;
					}
				}
			}
		}
	
		if ( isset($liste_fic) && is_array($liste_fic) )
		{
			if ( $orderBy == "filename" )
			{
				($orderDir == 0) ? ksort($liste_fic, SORT_STRING) : krsort($liste_fic, SORT_STRING);
			}
			else if ( $orderBy == "mod" )
			{
				($orderDir == 0) ? arsort($liste_fic) : asort($liste_fic);
			}
			else if ( $orderBy == "filesize" || $orderBy == "filetype" )
			{
				($orderDir == 0) ? asort($liste_fic) : arsort($liste_fic);
			}
			else
			{
				($orderDir == 0) ? ksort($liste_fic, SORT_STRING) : krsort($liste_fic, SORT_STRING);
			}
			
			if ( $orderBy != "filename" )
			{
				foreach ( $liste_fic as $index=>$value )
				{
					$liste_fic[$index] = Utils::mimetype($index, "image", false);
				}
			}
		}
		else
		{
			$liste_fic = array();
		}
		
		if ( isset($liste_rep) && is_array($liste_rep) )
		{
			if ( $orderBy == "mod")
			{
				($orderDir == 0) ? arsort($liste_rep) : asort($liste_rep);
			}
			else
			{
				($orderDir == 0) ? ksort($liste_rep, SORT_STRING) : krsort($liste_rep, SORT_STRING);
			}
			
			if ($orderBy != "filename" )
			{
				foreach ( $liste_rep as $index=>$value )
				{
					$liste_rep[$index] = $index;
				}
			}
		}
		else
		{
			$liste_rep = array();
		}

		$liste = Utils::mergeArrays($liste_rep, $liste_fic);
		
		if ( isset($liste_zip) )
		{
			$liste = Utils::mergeArrays($liste, $liste_zip);
		}
		
		// KHJ20091117 paganation code move after sorting list
		$result_list = array();
		if ( $offset != 0 || $limit != 0 )
		{
			$cursor = 0;
			foreach ( $liste as $key => $data )
			{
				if ( $offset > 0 && $cursor < $offset )
				{
					$cursor++;
					continue;
				}
				if ( $limit > 0 && ($cursor - $offset) >= $limit )
				{
					break;
				}
				$cursor ++;
				$result_list[$key] = $data;
			}
		}
		else
		{
			$result_list = $liste;
		}
		
		return $result_list;
	}
	
	function date_modif($Files, $FileName)
	{
		if ( isset($Files[$FileName]["MTIME"]) )
		{
			$timevalue = strtotime($Files[$FileName]["MTIME"]);
		}
		else
		{
			$timevalue = 0;
		}
		
		return $timevalue;
	}
	
	function copyOrMove($destDir, $selectedFiles, &$error, &$success, $move = false, $delete_emulation = false)
	{
		//templog("1) in copyOrMove");
		//temp_log("1) destDir: ".$destDir." ,selectedFiles : ".dump_array($selectedFiles));
		$mess = ConfService::getMessages();
		
		//by soo. for cross repo copy
		
		$httpVars = array_merge($_GET,$_POST);
		if ($httpVars["dest_repository_id"]){
			$destRepoId = $httpVars["dest_repository_id"];
		}
		else
		{
			$destRepoId = $_SESSION["REPO_ID"];
		}
		$destRepoObject = ConfService::getRepositoryById($destRepoId);
		$destRepoPath = $destRepoObject->getOption("PATH");	//dest repo의 절대 경로를 받아옴		temp_log("destRepoAccess:".$destRepoPath);
		$realDstRepo = $destRepoPath.$destDir;
		//if(!$this->isWritable($this->getPath().$destDir))
		if(!$this->isWritable($destRepoPath.$destDir))
		{
			$error[] = $mess[38]." ".$destDir." ".$mess[99];
			return ;
		}
		
		foreach ($selectedFiles as $selectedFile)
		{
				
			//if($move && !$this->isWritable($this->getPath().$destDir))
			/*if($move && !$this->isWritable($destRepoPath.$destDir))
			{
				$error[] = "\n".$mess[38]." ".dirname($selectedFile)." ".$mess[99];
				continue;
			}*/
			$this->copyOrMoveFile($destDir, $selectedFile, $error, $success, $move, $delete_emulation, $realDstRepo);
		}
		return null;
	}
	
	private function rename_execute($filePath, $filename_new)
	{
		// KHJ20090910 Convert File by smbclient command
		$mess = ConfService::getMessages();
		
		$filename_new = Utils::processFileName($filename_new, $invalid_char_count);
		
		if ( $invalid_char_count > 0 )
		{
			$not_allowed_chars = chunk_split(NOT_ALLOWED_FILENAME_CHAR, 1, " ");
			return "$mess[37]\n"."$mess[501]: $not_allowed_chars";
		}
		
		if ( $filename_new == "" )
		{
			return $mess[37];
		}
		$old_full_path = $this->getPath()."/".$filePath;
		$old_filename = basename($filePath);
		if ( !$this->isWritable($old_full_path) )
		{
			if ( !$this->is_mangled_name($old_filename) )
			{
				return $mess[34]." ".$old_filename." ".$mess[99];
			}
		}
		
		$old_dirname = dirname($filePath);
		if ( $old_dirname == "/" )
		{
			$old_dirname = "";
		}
		$new = $old_dirname."/".$filename_new;
		
		// KHJ20090910 Convert To smbclient
		$res = smbclientAccessDriver::singleton()->rename_smbclient($filePath, $new);
		
		if ( $res < 0 )
		{
			switch( $res )
			{
				case NT_STATUS_OBJECT_NAME_COLLISION: // NT_STATUS_OBJECT_NAME_COLLISION
					$ret_msg = $filename_new." ".$mess[43];
					break;
				case NT_STATUS_OBJECT_NAME_NOT_FOUND:
				case NT_STATUS_NO_SUCH_FILE:
					$ret_msg = $mess[100]." ".$old_filename;
					break;
				case NT_STATUS_OBJECT_PATH_NOT_FOUND:
					$ret_msg = $mess[72]." ".$old;
					break;
				default:
					$ret_msg = $mess[34]." ".$old." ".$mess[99];
					break;
			}
			
			return $ret_msg;
		}
		
		return null;
	}
	
	function autoRenameForDest($destination, $fileName){
		if(!is_file($destination."/".$fileName)) return $fileName;
		$i = 1;
		$ext = "";
		$name = "";
		$split = explode(".", $fileName);
		if(count($split) > 1){
			$ext = ".".$split[count($split)-1];
			array_pop($split);
			$name = implode(".", $split);
		}else{
			$name = $fileName;
		}
		while (is_file($destination."/".$name."-$i".$ext)) {
			$i++; // increment i until finding a non existing file.
		}
		return $name."-$i".$ext;
	}
	
	private function make_directory($crtDir, $newDirName)
	{
		$mess = ConfService::getMessages();
		
		if ( $crtDir == "/" )
		{
			$crtDir = "";
		}
		
		if ( $newDirName == "" )
		{
			return "$mess[37]"; // You must write a valid name
		}
		if( !$this->isWritable($this->getPath().$crtDir) )
		{
			return $mess[38]." $crtDir ".$mess[99]; // The directory dir_name is not writeable. There might be a permission problem, please check your administrator.
		}
		
		//by soo
		$res = smbclientAccessDriver::singleton()->mkdir_smbclient($crtDir."/".$newDirName);
		if ( $res < 0 )
		{
			if ( $res == NT_STATUS_OBJECT_NAME_COLLISION ) // NT_STATUS_OBJECT_NAME_COLLISION
			{
				$ret_msg = $mess[125]; // This directory already exists (names are case insensitive)
			}
			else
			{
				$ret_msg = $mess[38]." $crtDir ".$mess[99];
			}
			
			return $ret_msg;
		}
		
		return null;
	}
	
	private function cp_make_directory($crtDir, $newDirName, $realDstRepo)
	{
		$mess = ConfService::getMessages();
		
		if ( $crtDir == "/" )
		{
			$crtDir = "";
		}
		
		if ( $newDirName == "" )
		{
			return "$mess[37]"; // You must write a valid name
		}
		if( !$this->isWritable($realDstRepo) )
		{
			return $mess[38]." $crtDir ".$mess[99]; // The directory dir_name is not writeable. There might be a permission problem, please check your administrator.
		}
		
		//by soo
		$res = smbclientAccessDriver::singleton()->cp_mkdir_smbclient($crtDir."/".$newDirName);
		if ( $res !== 0 )
		{
			if ( $res == NT_STATUS_OBJECT_NAME_COLLISION ) // NT_STATUS_OBJECT_NAME_COLLISION
			{
				$ret_msg = $mess[125]; // This directory already exists (names are case insensitive)
			}
			else
			{
				$ret_msg = $mess[38]." $crtDir ".$mess[99];
			}
			
			return $ret_msg;
		}
		
		return 0;
	}
	
	function mkfile_execution($crtDir, $newFileName)
	{
		$mess = ConfService::getMessages();
		
		if ( $newFileName == "" )
		{
			return "$mess[37]";
		}
		
		if ( $crtDir == "/" ) $crtDir = "";
		
		$ext = explode(".", $newFileName);
		if (($ext[1] == 'txt') || ($ext[1] == 'doc'))
		{
			$exists_check_res = $this->file_exists_by_smbclient($crtDir."/".$newFileName);
		}
		else{
			$exists_check_res = $this->file_exists_by_smbclient($crtDir."/".$newFileName.".txt");
		}
		//$exists_check_res = $this->file_exists_by_smbclient($crtDir."/".$newFileName);
		
		if ( $exists_check_res != NT_STATUS_NO_SUCH_FILE )
		{
			if ( $exists_check_res == NT_STATUS_OBJECT_PATH_NOT_FOUND )
			{
				$ret_msg = "$mess[72] $crtDir/$newFileName";
			}
			else
			{
				$ret_msg = "$mess[71] $crtDir/$newFileName";
			}
			
			return $ret_msg;
		}
		
		$local_new_file = INSTALL_PATH."/".LOCAL_NEW_FILE_TEMPLATE;
		$ext = explode(".", $newFileName);
		if (($ext[1] == 'txt') || ($ext[1] == 'doc'))
		{
			$Result = smbclientAccessDriver::singleton()->put_smbclient($local_new_file, $crtDir."/".$newFileName);
		}
		else{
			$Result = smbclientAccessDriver::singleton()->put_smbclient($local_new_file, $crtDir."/".$newFileName.".txt");
		}
		
		if ( $Result < 0 )
		{
			if ( $Result == NT_STATUS_DISK_FULL ) // NT_STATUS_DISK_FULL
			{
				$ret_msg = $mess[503];
			}
			else
			{
				$ret_msg = "$mess[102] $crtDir/$newFileName";
			}
			return $ret_msg;
		}
		else
		{
			return null;
		}
	}
	
	function delete_execution($selectedFiles, $in_trash_box_delete_detected, &$logMessages)
	{
		$mess = ConfService::getMessages();
		
		$trashbox_name = $this->repository->getOption("RECYCLE_BIN");
		
		foreach ($selectedFiles as $selectedFile)
		{	
			if($selectedFile == "" || $selectedFile == DIRECTORY_SEPARATOR)
			{
				return $mess[120];
			}
			$fileToDelete = $selectedFile;	//by soo
			$fs_fileToDelete=$this->getPath().$selectedFile;
			//temp_log("0) fileToDelete: ".$fileToDelete.", "."fs_fileToDelete: ".$fs_fileToDelete);
			//if ( $this->file_exists_by_smbclient($fileToDelete) <= 0 )
			//if ( !file_exists($fs_fileToDelete) )
			//{
			//	$logMessages[] = $mess[100]." ".SystemTextEncoding::toUTF8($selectedFile);
			//	continue;
			//}
			
			if(!$this->isWritable($fs_fileToDelete))
			{
				$error = $fileToDelete." ".$mess[99];
				return $error;
			}
				
			$is_directory = ($this->is_dir_by_smbclient($fileToDelete) == 1 ) ? TRUE : FALSE;
			/* service>trashbox 삭제되는 문제 때문에 주석처리  
			if ( ($in_trash_box_delete_detected == TRUE)
				|| ($trashbox_name != "" && $fileToDelete == "/".$trashbox_name) )
			{
				//by soo. trashbox --> delete permission check!!
				if(!$this->isWritable($fs_fileToDelete))
				{
					$error = $mess[38]." ".$trashbox_name." ".$mess[99];
					return $error;
				}
				else{
					$this->delete_in_trashbox($fs_fileToDelete);
				}				
			}
			else
			{*/
				$this->deldir($fs_fileToDelete, $fileToDelete, $is_directory);	//by soo
			//}
			
			if ( $is_directory == TRUE )
			{
				$logMessages[] = "$mess[38] ".SystemTextEncoding::toUTF8($selectedFile)." $mess[44].";
			}
			else 
			{
				$logMessages[] = "$mess[34] ".SystemTextEncoding::toUTF8($selectedFile)." $mess[44].";
			}
		}
		return null;
	}	
	
	function copyOrMoveFile($destDir, $srcFile, &$error, &$success, $move = false, $delete_emulation = false, $realDstRepo)
	{
		//error_log("2) in copyOrMoveFile\n", 3, "/tmp/ajaxplorer.log");
		$dirRes = 0;
		$check = 0;
		$mess = ConfService::getMessages();
		if ( $destDir == "/" )
		{
			$destDir = "";
		}
		//$destFile = $this->repository->getOption("PATH").$destDir."/".basename($srcFile);
		$destFile = $realDstRepo."/".basename($srcFile);
		$realSrcFile = $this->repository->getOption("PATH")."$srcFile";
		
		$src_dir_path = dirname($realSrcFile);
		
		if(!$this->isReadable($realSrcFile))
		{
			$error[] = $mess[208];
			return;
		}
		if($move && !$this->isWritable($src_dir_path))
		{
			$error[] = $mess[38]." ".dirname($realSrcFile)." ".$mess[99];
			return;
		}
		
		
		//temp_log("2) destDir: ".$destDir." ,srcFile : ".$srcFile);
		//temp_log("2) destFile: ".$destFile." ,realsrcFile : ".$realSrcFile);
		
		$smbclientAccessDriverInstance = smbclientAccessDriver::singleton();
		
		if(!file_exists($realSrcFile))
		{
			$error[] = $mess[100].$srcFile;
			return ;
		}
		if($realSrcFile==$destFile)
		{
			$error[] = $mess[101];
			return ;
		}
		if ( strpos($destFile, $realSrcFile."/") === 0 )
		{
			$error[] = $mess[504];
			return ;
		}
		
		if ( $move && $delete_emulation == TRUE )
		{
			$folder_tree = explode("/", $srcFile);
			$folder_count = count($folder_tree) - 1;

			for ( $i = 0; $i < $folder_count; $i++ )
			{
				$cur_folder = "";
				if ( $folder_tree[$i] != "" )
				{
					for ( $j = 0; $j <= $i; $j++ )
					{
						if ( $folder_tree[$j] != "" )
						{
							$cur_folder .= "/";
							$cur_folder .= $folder_tree[$j];
						}
					}
				}
				
				if ( $cur_folder != "" )
				{
					//temp_log("cur_folder: ".$cur_folder);
					//$fs_cur_folder = $this->repository->getOption("PATH").$destDir.$cur_folder;
					$fs_cur_folder = $realDstRepo.$cur_folder;	//by soo.
					if ( file_exists($fs_cur_folder) )
					{
						if ( !is_dir($fs_cur_folder) )
						{
							if ( $this->shell_exec_delete($fs_cur_folder) == FALSE )
							{
								return;
							}
							
							$this->cp_make_directory($destDir, $cur_folder, $realDstRepo);
						}
					}
					else
					{
						$this->cp_make_directory($destDir, $cur_folder, $realDstRepo);
					}
				}
			}
		}
		else
		{
			$check = file_exists($destFile);
			//error_log("2) check: ".$check."\n", 3, "/tmp/ajaxplorer.log");
			if($check)
			{
				$error[] = $mess[125];
				return;
			}
		}
		
		if ( is_dir($realSrcFile) )
		{
			$errors = array();
			$succFiles = array();
			$res = 0;
			$is_directory = 1;
			/*if ( $move ) //////////////////////////start
			{
				//error_log("2) dest is not file & move dir \n", 3, "/tmp/ajaxplorer.log");
				$base = basename($srcFile);
				if ( $delete_emulation == TRUE )
				{
					$new = $destDir."/".$srcFile;
				}
				else
				{
					$new = $destDir."/".$base;
				}
				$this->cp_make_directory($destDir, $base, $realDstRepo);	//make dir
				
			///////////////////////	rename files in directory
				$samba_veto_files = $_SESSION["SMB_CONF_VETO_FILES"];
				$samba_veto_files[] = ".";
				$samba_veto_files[] = "..";
				$all = opendir($realSrcFile);
				while ( $file = readdir($all) )
				{
					if ( !in_array($file, $samba_veto_files) )
					{
						$tmp_old = $srcFile."/".$file;
						$tmp_new = $new."/".$file;
						
						$res = $smbclientAccessDriverInstance->rename_smbclient($tmp_old, $tmp_new);
								
						if ( $res < 0 )
						{
							if ( $res == NT_STATUS_SHARING_VIOLATION ) // NT_STATUS_SHARING_VIOLATION
							{
								$error[] = $mess[505]." ".$tmp_old;
							}
							else
							{
								//error_log("!!! rename error !!! output[0]: ".$output[0]."\n", 3, "/tmp/ajaxplorer.log");
								$res = 1;
								$error[] = "Check \""." $tmp_old "."\" ".$mess[287];
							}
							return; 
						}							
					}
					
				}
				closedir($all);
				
			//////////////////////////
				if ($res == 0) //delete src dir 
				{
					$res = $smbclientAccessDriverInstance->rm_smbclient($srcFile, TRUE);
					
					if ( $res < 0 )
					{
						$error[] = $mess[114];
						return;
					}
				}
			///////////////////////////////end
			}
			else
			{*/
				//error_log("2) dir & copy\n", 3, "/tmp/ajaxplorer.log");
				//$dirRes = $this->dircopy($realSrcFile, $destFile, $errors, $succFiles);
				$dirRes = $this->dircopy($srcFile, $destDir, $errors, $succFiles, $mess, $realDstRepo);
				
			//}
			
			if(count($errors) || ($res!== 0))	//if(count($errors) || (isSet($res) && $res!==true))
			{
				if ( $dirRes === NT_STATUS_DISK_FULL ) // NT_STATUS_DISK_FULL
				{
					$error[] = $errors[0];
				}
				else if ( $dirRes === NT_STATUS_ACCESS_DENIED ) // NT_STATUS_ACCESS_DENIED
				{
					$error[] = $errors[0];
				}
				else
				{
					$error[] = $mess[114];
				}
				return ;
			}
			
			//by soo..
			if ($move)
			{
				//TODO: delete source file
				//temp_log("in move delete!! - folder dirRes:".$dirRes);
				//if ($dirRes > 0)
				//{
					$this->deldir($realSrcFile, $srcFile, $is_directory);	//by soo
					//$res = $smbclientAccessDriverInstance->rm_smbclient($srcFile, TRUE);
					
				//}
			}
		}
		else 
		{
			$is_directory = 0;
			//////////////////////////////////////////////////// start
			//error_log("2) not dir \n", 3, "/tmp/ajaxplorer.log");
			/*if ( $move )
			{
				//error_log("2) dest is not file & move\n", 3, "/tmp/ajaxplorer.log");
				if ( $delete_emulation == TRUE )
				{
					$new = $destDir.$srcFile;					
					
					$fs_new = $this->repository->getOption("PATH").$new;
					if ( file_exists($fs_new) )
					{
						if ( $this->shell_exec_delete($fs_new) == FALSE )
						{
							return;
						}
					}
				}
				else
				{
					$base = basename($srcFile);
					$new = $destDir."/".$base;
				}
				
				$res = $smbclientAccessDriverInstance->rename_smbclient($srcFile, $new);
				
				if ( $res < 0 )
				{
					if ( $res == NT_STATUS_SHARING_VIOLATION ) // NT_STATUS_SHARING_VIOLATION
					{
						$error[] = $mess[505]." ".$srcFile;
					}
					else
					{
						$error[] = "Check \""." $srcFile "."\" ".$mess[287];
					}
					return; 
				}
				unset($dirRes);
			///////////////////////////////////////////////////// end
			}
			else
			{*/
				//error_log("2) not dir & copy\n", 3, "/tmp/ajaxplorer.log");
				$tmpSmbDst = $destDir."/".basename($srcFile);
								
				if ( $this->AccessRightsCheck($realSrcFile , "r") == FALSE )
				{
					$error[] = $mess[208];
					return ;
				}
				else
				{
					$res2 = $smbclientAccessDriverInstance->put_smbclient($realSrcFile, $tmpSmbDst);
				}
				//error 메세지 처리 
				if ($res2 < 0)
				{
					$res = 1;
				}
				else
				{			
					$res = 0;
				}
				if ( $res2 === NT_STATUS_DISK_FULL ) // NT_STATUS_DISK_FULL
				{
					$error[] = $mess[503];
					return ;
				}
				if ( $res2 === NT_STATUS_ACCESS_DENIED )
				{
					$error[] = $mess[207];
					return ;
				}
				
				
			//}
				if($res !== 0)
				{
					$error[] = $mess[114];
					return ;
				}
				if($move)
				{
					//TODO:delete soure file
					$this->deldir($realSrcFile, $srcFile, $is_directory);	//by soo
					//$res = $smbclientAccessDriverInstance->rm_smbclient($srcFile, FALSE);
					
				}
		}
		
		if($move)
		{
			// Now delete original
			// $this->deldir($realSrcFile); // both file and dir
			$messagePart = $mess[74]." ".SystemTextEncoding::toUTF8($destDir);
			
			if(isset($dirRes))
			{
				$success[] = $mess[117]." ".SystemTextEncoding::toUTF8(basename($srcFile))." ".$messagePart;
			}
			else 
			{
				$success[] = $mess[34]." ".SystemTextEncoding::toUTF8(basename($srcFile))." ".$messagePart;
			}
		}
		else
		{
		
			if($dirRes !== 0)
			{
				$success[] = $mess[117]." ".SystemTextEncoding::toUTF8(basename($srcFile))." ".$mess[73]." ".SystemTextEncoding::toUTF8($destDir)." (".SystemTextEncoding::toUTF8($dirRes)." ".$mess[116].")";
				return ;
			
			}
			else 
			{
				$success[] = $mess[34]." ".SystemTextEncoding::toUTF8(basename($srcFile))." ".$mess[73]." ".SystemTextEncoding::toUTF8($destDir);
				return ;
			}
			
		}
		return null;
	}

	// A function to copy files from one directory to another one, including subdirectories and
	// nonexisting or newer files. Function returns number of files copied.
	// This function is PHP implementation of Windows xcopy  A:\dir1\* B:\dir2 /D /E /F /H /R /Y
	// Syntaxis: [$number =] dircopy($sourcedirectory, $destinationdirectory );
	// Example: $num = dircopy('A:\dir1', 'B:\dir2', 1);

	function dircopy($srcdir, $dstdir, &$errors, &$success, $mess, $realDstRepo) 
	{
		$num = 0;
		$realSrcFile = $this->repository->getOption("PATH").$srcdir;
		$realDstDir = $this->repository->getOption("PATH").$dstdir;
		//error_log("in dircopy.. srcdir: ".$srcdir.", dstdir: ".$dstdir."\n", 3, "/tmp/ajaxplorer.log");
		$smbclientAccessDriverInstance = smbclientAccessDriver::singleton();
		
		$base = basename($srcdir);
		if(!is_dir($dstdir))
		{
			$dir_res = $this->cp_make_directory($dstdir, $base, $realDstRepo);	//make dir mkdir($dstdir);
			//temp_log("in dircopy cp make res!!".$dir_res);
			if ($dir_res !== 0)
			{
				//temp_log("in dircopy error!!");
				array_splice($errors, 0, count($errors), $mess[207]);
				$num = NT_STATUS_ACCESS_DENIED;
				return $num;
			}
		}
		
		
		$fileperm_bak = substr(decoct( fileperms($realSrcFile) ), (is_file($realSrcFile)?2:1));
		$chmodValue = '0777';
		$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($realSrcFile).'"';
		exec($shell_cmd, $shell_output, $shell_result);
		
		
		if($curdir = opendir($realSrcFile)) 
		{
			while($file = readdir($curdir)) 
			{
				if($file != '.' && $file != '..') 
				{
					$srcfile = $realSrcFile.DIRECTORY_SEPARATOR.$file;
					$dstfile = $realDstDir."/".$base."/".$file;
					//error_log("in dircopy.. realsrcfile: ".$realSrcFile.", realdstfile: ".$realDstDir."\n", 3, "/tmp/ajaxplorer.log");
					//error_log("in dircopy.. srcfile: ".$srcfile.", dstfile: ".$dstfile."\n", 3, "/tmp/ajaxplorer.log");
					$smbsrcfile = $srcdir.DIRECTORY_SEPARATOR.$file;
					$smbdstfile = $dstdir."/".$base."/".$file;
					$smbdstdir = $dstdir."/".$base;
					//error_log("in dircopy.. smbsrcfile: ".$smbsrcfile.", smbdstfile: ".$smbdstfile."\n", 3, "/tmp/ajaxplorer.log");
										//////////////
					if(is_file($srcfile)) 
					{
						//error_log("in dircopy.. is_file_srcfile??\n", 3, "/tmp/ajaxplorer.log");
						$tmpSmbDst = $dstdir."/".basename($srcfile);
						
						if ( $this->AccessRightsCheck($srcfile , "r") == FALSE )
						{
							$error[] = $mess[208];
						}
						else
						{
							$res2 = $smbclientAccessDriverInstance->put_smbclient($srcfile, $smbdstfile);
						}
						/////
						
						if ( $res2 === NT_STATUS_DISK_FULL ) // NT_STATUS_DISK_FULL
						{
							array_splice($errors, 0, count($errors), $mess[503]);
							$num = NT_STATUS_DISK_FULL;
							break;
						}
						if ( $res2 === NT_STATUS_ACCESS_DENIED )
						{
							array_splice($errors, 0, count($errors), $mess[207]);
							$num = NT_STATUS_ACCESS_DENIED;
							break;
						}
						/////
						
						if($res == 0) 
						{
							//touch($dstfile, filemtime($srcfile));
							$num++;
							$success[] = $smbsrcfile;
							
						}
						else 
						{
							$errors[] = $smbsrcfile." : ".$mess[114];
						}
					}
					else if(is_dir($srcfile)) 
					{
						$copied_num = $this->dircopy($smbsrcfile, $smbdstdir, $errors, $success, $mess, $realDstRepo);
						if ( $copied_num === NT_STATUS_DISK_FULL )
						{
							$num = NT_STATUS_DISK_FULL;
							break;
						}
						else if ( $copied_num === NT_STATUS_ACCESS_DENIED )
						{
							$num = NT_STATUS_ACCESS_DENIED;
							break;
						}
						else
						{
							$num += $copied_num;
						}
					}
				}
			}
			closedir($curdir);
			
			$shell_cmd = 'sudo chmod '.$fileperm_bak.' "'.Utils::unix_shell_escape_args($realSrcFile).'"';
			exec($shell_cmd, $shell_output, $shell_result);
				
		}
		return $num;
	}
	
	function download_file_permission_check($path,$res)
	{
		$samba_veto_files = $_SESSION["SMB_CONF_VETO_FILES"];
		$samba_veto_files[] = ".";
		$samba_veto_files[] = "..";
		$realSrcFile = $this->repository->getOption("PATH");
		
		//$full_path = $realSrcFile.$path;
		//temp_log("===full path ".$full_path);
		if ($this->isReadable($path))
		{
			
			$fileperm_bak = substr(decoct( fileperms($path) ), (is_file($path)?2:1));
			$chmodValue = '0777';
			$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
			exec($shell_cmd, $shell_output, $shell_result);
			
			$all = opendir($path);
			while ( $file = readdir($all) )
			{
								
				if ( !in_array($file, $samba_veto_files) )
				{
					$realpath = $path."/".$file;
										
					if(!$this->isReadable($realpath))
					{
						$res = -1;
					}
					else
					{
												
						$fileperm_bak2 = substr(decoct( fileperms($realpath) ), (is_file($realpath)?2:1));
						$chmodValue = '0777';
						$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($realpath).'"';
						exec($shell_cmd, $shell_output, $shell_result);
			
						if(is_dir($realpath))
						{
							
							$res = $this->download_file_permission_check($realpath,$res);
						}
						
						$shell_cmd = 'sudo chmod '.$fileperm_bak2.' "'.Utils::unix_shell_escape_args($realpath).'"';
						exec($shell_cmd, $shell_output, $shell_result);
					}
					
				}
				
			}
			closedir($all);
			
			$shell_cmd = 'sudo chmod '.$fileperm_bak.' "'.Utils::unix_shell_escape_args($path).'"';
			exec($shell_cmd, $shell_output, $shell_result);
		}
		else
		{
			$res = -1;
		}
		return $res;
		
	}
	
	function simpleCopy($origFile, $destFile)
	{
		return copy($origFile, $destFile);
	}
	
	private function shell_exec_delete($fs_file_name)
	{
		$shell_cmd = 'sudo rm -rf "'.Utils::unix_shell_escape_args($fs_file_name).'"';
		exec($shell_cmd, $output, $shell_res);
		//temp_log("cmd: $shell_cmd, res: $shell_res");
		//temp_log("output");
		//temp_log(dump_array($output));
		
		if ( $shell_res !== 0 )
		{
			return FALSE;
		}
		
		return TRUE;
	}
	private function delete_in_trashbox($fs_file_name)
	{
		if ( $this->shell_exec_delete($fs_file_name) == FALSE )
		{
			AJXP_Exception::errorToXml(new AJXP_Exception(120));
		}
		
		return;
	}
	
	/**
	 * convert path from unix shell to smbclient or vice verse.
	 *
	 * @param String $path file or folder path
	 * @param String $direction 0: unix shell path -> smbclient path, 1: smbclient path -> unix shell path
	 */
	private function convert_file_path($path, $direction)
	{
		if ( $direction == PATH_TO_SMB ) //
		{
			$result_path = str_replace("/", "\\", $path); // smbclient needs \ seperated path
		}
		else if ( $direction == PATH_TO_SHELL )
		{
			$result_path = str_replace("\\", "/", $path);
			$result_path = str_replace("//", "/", $result_path);
		}
		else
		{
			$result_path = $path;
		}
		
		return $result_path;
	}
	
	private function report_delete_error($error_code, $error_string, $precess_error_code_list, $report_unknown_error)
	{
		foreach($precess_error_code_list as $one_error_code => $msg_code)
		{
			if ( $one_error_code == $error_code )
			{
				switch ($error_code)
				{
					case NT_STATUS_SHARING_VIOLATION:
						$error_array = explode("deleting remote file", $error_string);
						break;
					case NT_STATUS_OBJECT_NAME_NOT_FOUND:
					case NT_STATUS_NO_SUCH_FILE:
					case NT_STATUS_OBJECT_PATH_NOT_FOUND:
						$error_array = explode("listing", $error_string);
						break;
				}
				$message_string_code = $msg_code;
				$error_file = $this->convert_file_path($error_array[1], PATH_TO_SHELL);
				AJXP_Exception::errorToXml(new AJXP_Exception($message_string_code, " ".$error_file));
			}
			// program exited at this point
		}
		
		if ( $report_unknown_error )
		{
			AJXP_Exception::errorToXml(new AJXP_Exception(120));
		}
	}
	
	function deldir($location, $smblocation, $is_directory = FLASE)
	{
		$smbclientAccessDriverInstance = smbclientAccessDriver::singleton();
		
		$smblocation = $this->convert_file_path($smblocation, PATH_TO_SMB);
		
		if ( $is_directory )
		{
			// 1. delete all files in current directory
			$rm_res = $smbclientAccessDriverInstance->rm_smbclient($smblocation, FALSE, "*"); // rm *
			//temp_log("delete all files in current dir: $smblocation res: $rm_res");
			
			if ( ($rm_res < 0) && ($rm_res != NT_STATUS_NO_SUCH_FILE) )
			{
				//temp_log("delete all files error: $smblocation res: $rm_res");
				$this->report_delete_error($rm_res, $smbclientAccessDriverInstance->get_last_error_string(),
										   array(NT_STATUS_SHARING_VIOLATION => 505,
												 NT_STATUS_OBJECT_PATH_NOT_FOUND => 103)
										   , FALSE);
				// program exited at this point
			}
			
			// 2. ls to find directory list
			$ls_res = smbclientAccessDriver::singleton()->ls_smbclient($smblocation == "" ? "/" : $smblocation."/", TRUE, $file_list, $disk_usage);
			if ( $ls_res < 0 )
			{
				// smbclient cd, ls error 처리
				//temp_log("delete ls error: $smblocation res: $ls_res");
				switch ($ls_res)
				{
					case NT_STATUS_OBJECT_NAME_NOT_FOUND:
						$message_string_code = 103;
						break;
					case NT_STATUS_OBJECT_PATH_NOT_FOUND:
						$message_string_code = 72;					
						break;
					default:
						$message_string_code = 208;
						break;
				}
				AJXP_Exception::errorToXml(new AJXP_Exception($message_string_code));
				// program exited at this point
			}
			
			if ( count($file_list) > 0 )
			{
				// 3. delete sub directory
				foreach ($file_list as $file_name => $meta_data)
				{
					if ( strpos($meta_data["MODE"], "D") !== FALSE ) // directory
					{
						//temp_log("delete a dir request: $smblocation\\$file_name");
						$this->deldir($location, $smblocation."/".$file_name, TRUE);
					}
					else // file: 이 경우는 있을 수 없지만.... 안전을 위하여
					{
						//temp_log("delete a file request: $smblocation\\$file_name");
						$this->deldir($location, $smblocation."/".$file_name, FALSE);
					}
				}
			}
			
			// 4. delete current directory and return
			$rm_res = $smbclientAccessDriverInstance->rm_smbclient($smblocation, TRUE); // rmdir
			//temp_log("delete current dir: $smblocation res: $rm_res");
			
			if ( $rm_res < 0 )
			{
				//temp_log("delete top dir error: $smblocation res: $rm_res");
				$this->report_delete_error($rm_res, $smbclientAccessDriverInstance->get_last_error_string(),
										   array(NT_STATUS_SHARING_VIOLATION => 505,
												 NT_STATUS_OBJECT_PATH_NOT_FOUND => 103,
												 NT_STATUS_OBJECT_NAME_NOT_FOUND => 100,
												 NT_STATUS_NO_SUCH_FILE => 100)
										   , TRUE);
				// program exited at this point
			}
			return;
		}
		else
		{
			$rm_res = $smbclientAccessDriverInstance->rm_smbclient($smblocation); // one file delete
			//temp_log("delete one file: $smblocation res: $rm_res");
			
			if ( $rm_res < 0 )
			{
				//temp_log("delete one file error: $smblocation res: $rm_res");
				$this->report_delete_error($rm_res, $smbclientAccessDriverInstance->get_last_error_string(),
										   array(NT_STATUS_SHARING_VIOLATION => 505,
												 NT_STATUS_OBJECT_PATH_NOT_FOUND => 103,
												 NT_STATUS_OBJECT_NAME_NOT_FOUND => 100)
										   , TRUE);
				// program exited at this point
			}
		}
		
	}
	
	/**
	 * Change file permissions 
	 *
	 * @param String $path
	 * @param String $chmodValue
	 * @param Boolean $recursive
	 * @param String $nodeType "both", "file", "dir"
	 */
	function chmod($path, $chmodValue, $recursive, $nodeType, &$changedFiles, $recursive2)
	{
		/*
		 //by soo.
		$loggedUser = AuthService::getLoggedUser();
		$loginUserId = $loggedUser->getId();
		
		if (is_file($path))
		{
			//sudo -u admin chmod 777 file
			//sudo -u user1 chmod 777 file
		}
		else if(is_dir($path))
		{
			//sudo -u admin chmod 777 folder -R :하위 디렉토리, 폴더에도 적용할때
		}
		
		// error message처리 필요
		fileowner($path);
		 */
		$own = fileowner($path);
		//temp_log("fileowner:".$own);
		if($own == '0')		//root 소유의 파일/폴더에 대한 필터링
		{
			return 1;
		}
		
		$chmodValue = ltrim($nodeType, "0");
		
		$loggedUser = AuthService::getLoggedUser();
		$loginUserId = $loggedUser->getId();
		
		if (is_file($path))
		{
			//sudo -u admin chmod 777 file
			//sudo -u user1 chmod 777 file
			//$shell_cmd = 'sudo -u '.$loginUserId.' chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
			if($loginUserId == 'admin')
			{
				$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
				exec($shell_cmd, $shell_output, $shell_result);
				return $shell_result;
			}
			else
			{
				$shell_cmd = 'sudo -u '.$loginUserId.' chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
				exec($shell_cmd, $shell_output, $shell_result);
				return $shell_result;
			}
			
			//temp_log("shell_output\n".dump_array($shell_output));
		}
		else if(is_dir($path))
		{
			if ($recursive2 == 1)
			{
				//sudo -u admin chmod 777 folder -R :하위 디렉토리, 폴더에도 적용할때
				//$shell_cmd = 'sudo -u '.$loginUserId.' chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'/" -R';
				if($loginUserId == 'admin')
				{
					$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'/" -R';
					exec($shell_cmd, $shell_output, $shell_result);
					return $shell_result;
				}
				else
				{
					$shell_cmd = 'sudo -u '.$loginUserId.' chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'/" -R';
					exec($shell_cmd, $shell_output, $shell_result);
					return $shell_result;
				}
					
			}
			else
			{
				//$shell_cmd = 'sudo -u '.$loginUserId.' chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
				/*$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
				exec($shell_cmd, $shell_output, $shell_result);
				temp_log("cmd: ".$shell_cmd." result: ".$shell_result);
				temp_log("shell_output\n".dump_array($shell_output));*/
				if($loginUserId == 'admin')
				{
					$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
					exec($shell_cmd, $shell_output, $shell_result);
					return $shell_result;
				}
				else
				{
					$shell_cmd = 'sudo -u '.$loginUserId.' chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
					exec($shell_cmd, $shell_output, $shell_result);
					return $shell_result;
				}
			}
			
		}
	/*	
	   // temp_log("Before) chmodValue:".$chmodValue." path:".$path." recursive:".$recursive." nodeType:".$nodeType);
	    $chmodValue = ltrim($chmodValue, "0");
	   // temp_log("After) chmodValue:".$chmodValue);
	    $res1 = is_file($path);
	    $res2 = is_dir($path);
	   // temp_log("is_file:".$res1." is_dir:".$res2);
		if(is_file($path) && ($nodeType=="both" || $nodeType=="file")){
			//chmod($path, $chmodValue);
			//this->change_mode($path, $chmodValue);
			$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
			exec($shell_cmd, $shell_output, $shell_result);
			//temp_log("cmd: ".$shell_cmd." result: ".$shell_result);
			//temp_log("shell_output\n".dump_array($shell_output));

			$changedFiles[] = $path;
		}else if(is_dir($path)){
			if($nodeType=="both" || $nodeType=="dir"){
				//chmod($path, $chmodValue);
				//this->change_mode($path, $chmodValue);
				$shell_cmd = 'sudo chmod '.$chmodValue.' "'.Utils::unix_shell_escape_args($path).'"';
				exec($shell_cmd, $shell_output, $shell_result);
				//temp_log("cmd: ".$shell_cmd." result: ".$shell_result);
				//temp_log("shell_output\n".dump_array($shell_output));

				$changedFiles[] = $path;
			}
			if($recursive){
				$handler = opendir($path);
				while ($child=readdir($handler)) {
					if($child == "." || $child == "..") continue;
					$this->chmod($path."/".$child, $chmodValue, $recursive, $nodeType, $changedFiles);
				}
				closedir($handler);
			}
		}
		*/
	}
    
    /** The publiclet URL making */
    function makePubliclet($filePath, $password, $expire)
    {
        $data = array("DRIVER"=>"smb", "OPTIONS"=>NULL, "FILE_PATH"=>$filePath, "ACTION"=>"download", "EXPIRE_TIME"=>$expire ? (time() + $expire * 86400) : 0, "PASSWORD"=>$password);
        return $this->writePubliclet($data);
    }
	
	private function AccessRightsCheck($FolderFilePath, $AccessPattern)
	{
		//error_log("=====Access check!! :".$FolderFilePath."\n",3,"/tmp/ajaxplorer.log");
		//$FolderFilePath = preg_replace("/\//", "\\", $FolderFilePath);
		$Cmd = 'LANG='.SYSTEM_DEFAULT_LANG.' sudo stat -c"%a %u %g" "'.Utils::unix_shell_escape_args($FolderFilePath).'"';

		exec($Cmd, $Output, $Result);
		//temp_log("cmd: ".$Cmd." result: ".$Result);

		if ( $Result != 0 )
		{
			return FALSE;
		}
		
		//temp_log("result:");
		//foreach($Output as &$OneLine)
		//{
		//	temp_log($OneLine);
		//}
		$MatchedCount = preg_match("/(\d)(\d)(\d) (\d+) (\d+)/", $Output[0], $Matches);
		
		if ( $MatchedCount == 1 )
		{
			//temp_log("my access right: ".$Matches[1]);
			//temp_log("group access right: ".$Matches[2]);
			//temp_log("other access right: ".$Matches[3]);
			//temp_log("uid: ".$Matches[4]);
			//temp_log("gid: ".$Matches[5]);

			$uid = $_SESSION["AJXP_REMOTE_UID"];
			$gid_array = $_SESSION["AJXP_REMOTE_GID"];
			
			//temp_log("loging uid: ".$uid);
			//foreach($gid_array as $gid)
			//	temp_log("loging gid: ".$gid);
			
			$mask = 0x4; // r--
			if ( $AccessPattern == "w" )
			{
				$mask = 0x2; //-w- 
			}
			
			if ( ($Matches[3] & $mask) == $mask ) // others check
			{
				return TRUE;
			}
			else if ( $Matches[4] == $uid && (($Matches[1] & $mask) == $mask) ) // my option check
			{
				return TRUE;
			}
			else if ( in_array($Matches[5], $gid_array) && (($Matches[2] & $mask) == $mask) ) // group option check
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	private function other_AccessRightsCheck($FolderFilePath, $AccessPattern)
	{
		//$FolderFilePath = preg_replace("/\//", "\\", $FolderFilePath);
		$Cmd = 'LANG='.SYSTEM_DEFAULT_LANG.' sudo stat -c"%a %u %g" "'.Utils::unix_shell_escape_args($FolderFilePath).'"';

		exec($Cmd, $Output, $Result);
		//temp_log("cmd: ".$Cmd." result: ".$Result);

		if ( $Result != 0 )
		{
			return FALSE;
		}
		
		//temp_log("result:");
		//foreach($Output as &$OneLine)
		//{
		//	temp_log($OneLine);
		//}
		$MatchedCount = preg_match("/(\d)(\d)(\d) (\d+) (\d+)/", $Output[0], $Matches);
		
		if ( $MatchedCount == 1 )
		{
			//temp_log("my access right: ".$Matches[1]);
			//temp_log("group access right: ".$Matches[2]);
			//temp_log("other access right: ".$Matches[3]);
			$other_right = $Matches[3];
			//temp_log("uid: ".$Matches[4]);
			//temp_log("gid: ".$Matches[5]);

			$uid = $_SESSION["AJXP_REMOTE_UID"];
			$gid_array = $_SESSION["AJXP_REMOTE_GID"];
			
			//temp_log("loging uid: ".$uid);
			//foreach($gid_array as $gid)
			//	temp_log("loging gid: ".$gid);
			
			if($other_right >= 4)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	 
	private function isWritable($Path)
	{
		return $this->AccessRightsCheck($Path, "w");
	}
	 
	private function isReadable($Path)
	{
		return $this->AccessRightsCheck($Path, "r");
	}
	
	private function determine_updown_temp_dir()
	{			
		$default_temp_dir = ini_get('upload_tmp_dir');
		
		if ( !file_exists($default_temp_dir) )
		{
			$shell_cmd = "sudo mkdir ".$default_temp_dir;
			$shell_cmd .= ("; sudo chown ".$this->driverConf["WEB_USER_USER_ID"]." ".$default_temp_dir);
			$shell_cmd .= ("; sudo chgrp ".$this->driverConf["WEB_USER_GROUP_ID"]." ".$default_temp_dir);
			$shell_cmd .= ("; sudo chmod 700 ".$default_temp_dir);
			exec($shell_cmd, $output, $result);
			
			if ( $result !== 0 ) // if fail
			{
				$default_temp_dir = sys_get_temp_dir();
			}
		}
		
		return $default_temp_dir;
	}
	
	private function CreateUploadTempDir(&$UploadTempDir)
	{
		$loggedUser = AuthService::getLoggedUser();
		$logon_userid = $loggedUser->getId();
		$UploadTempDir = $this->temp_dir."/ajaxplorer_upload_".$logon_userid."_".session_id()."_".time(); // TODO: logon user name, session id

		$Result = TRUE;
		if ( !file_exists($UploadTempDir) )
		{
			$Result = mkdir($UploadTempDir, 0700, TRUE);
		}
		
		if ( $Result == FALSE )
		{
			$UploadTempDir = "";
		}
		
		return $Result;
	}
	
	private function CreateDownloadTempDir(&$DownloadTempDir)
	{
		$loggedUser = AuthService::getLoggedUser();
		$logon_userid = $loggedUser->getId();
		$DownloadTempDir = $this->temp_dir."/ajaxplorer_download_".$logon_userid."_".session_id()."_".time(); // TODO: logon user name, session id
		
		$Result = TRUE;
		if ( !file_exists($DownloadTempDir) )
		{
			$Result = mkdir($DownloadTempDir, 0700);
		}
		
		if ( $Result == FALSE )
		{
			$DownloadTempDir = "";
		}
		
		return $Result;
	}
	
	// KHJ20090922 register_shutdown_function의 handler로 사용하기 위해 private에서 public으로 속성 변경
	function DeleteTempDir($TempDir)
	{
		if ( file_exists($TempDir) )
		{
			shell_exec("sudo rm -rf ".$TempDir);
		}
	}
	
	function ZipFileFolderSortingByName($a, $b)
	{
		$a_base = strtolower(basename($a["filename"]));
		$b_base = strtolower(basename($b["filename"]));
		
		//temp_log("A: ".$a_base);
		//temp_log("B: ".$b_base);
		//temp_log("R: ".strcmp($a_base, $b_base));
		return strcmp($a_base, $b_base);
	}
	
	function zipListing($zipPath, $localPath, &$filteredList, &$zip_ar)
	{
		// ZIP Method 2
		$zip_ar = new ZipArchive();
		$res = $zip_ar->open($this->getPath()."/".$zipPath);
		
		if ( $res !== TRUE )
		{
			$filteredList = array();
			//temp_log("zip file open error: ".$res);
			
			return $res; // error code return
		}
		
		$files = array();
		if ( $localPath[strlen($localPath)-1] != "/" ) $localPath .= "/";
		
		$zipPath = Utils::convert_to_utf8($zipPath);
		$localPath = Utils::convert_to_utf8($localPath, TRUE);
		//temp_log("zip path: ".$zipPath);
		//temp_log("local path: ".$localPath);
		
		$FirstDirNameList = array();
		
		for ( $i = 0; $i < $zip_ar->numFiles; $i++ )
		{
			$zip_stat = $zip_ar->statIndex($i);

			$stored_name = Utils::convert_to_utf8($zip_stat["name"], TRUE);
			//temp_log("in zip file: ".$stored_name);
			if($stored_name[0] != "/") $stored_name = "/".$stored_name;
			
			$pathPos = strpos($stored_name, $localPath);
			
			if( ($pathPos !== FALSE) && ($pathPos == 0) )
			{
				$afterPath = substr($stored_name, $pathPos + strlen($localPath));
				if($afterPath[0] != "/") $afterPath = "/".$afterPath;
				
				//temp_log("file: ".$afterPath);
				
				$PathNames = explode("/", $afterPath);
				
				$count = count($PathNames);
				
				if ( $count == 2 && $PathNames[1] !== "" ) // file ex. /filname...
				{
					$afterPath = substr($afterPath, 1);
				
					$item['filename'] = $zipPath.$localPath.$PathNames[1];
					//$item['stored_filename'] = $zipPath.$localPath.$PathNames[1];
					$item['stored_filename'] = $localPath.$PathNames[1];
					$item['mtime'] = $zip_stat["mtime"];
					$item['folder'] = FALSE;
					$item['size'] = $zip_stat["size"];
					//temp_log("remain file: ".$item['filename']);
						
					$files[] = $item;
				}
				else if ( $count == 3 && $PathNames[2] == "" ) // exact folder ex. /foldername.../
				{
					//temp_log("exact folder: ".$afterPath);
					$FirstDirNameList[] = $PathNames[1];
					$item['filename'] = $zipPath.$localPath.$PathNames[1]."/";
					//$item['stored_filename'] = $zipPath.$localPath.$PathNames[1];
					$item['stored_filename'] = $localPath.$PathNames[1]."/";
					$item['mtime'] = $zip_stat["mtime"];
					$item['folder'] = TRUE;
					$item['size'] = $zip_stat["size"];
							
					$filteredList[] = $item;
				}
				else if ( $count >= 3 && $PathNames[2] !== "") // remain folder ex. /foldername.../nextfoldername.../
				{
					if ( in_array($PathNames[1], $FirstDirNameList, true) == FALSE )
					{					
						$FirstDirNameList[] = $PathNames[1];
						$item['filename'] = $zipPath.$localPath.$PathNames[1]."/";
						//$item['stored_filename'] = $zipPath.$localPath.$PathNames[1];
						$item['stored_filename'] = $localPath.$PathNames[1]."/";
						$item['mtime'] = "0";
						$item['folder'] = TRUE;
						$item['size'] = NULL;
						
						//temp_log("reamin folder: ".$item['filename']." [".$PathNames[1]."]");
						
						$filteredList[] = $item;
					}
				}
			}
		}
		
		unset($FirstDirNameList);
		
		//$zip_ar->close();
		
		if ( count($filteredList) > 0 )
		{
			uasort($filteredList, array($this, "ZipFileFolderSortingByName"));
		}
		
		if ( count($files) > 0 )
		{
			uasort($files, array($this, "ZipFileFolderSortingByName"));
		}
		
		$filteredList = array_merge($filteredList, $files);
		
		return 1;
	}
	
	// ZIP Method 2
	function zipFindLikeIndex($zip_ar, $zip_entry)
	{
		$zip_like_index_list = array();
		
		for ( $i = 0; $i < $zip_ar->numFiles ; $i++ )
		{
			$zip_stat = $zip_ar->statIndex($i);
			
			$stored_name = Utils::convert_to_utf8($zip_stat["name"], TRUE);
			//temp_log("like entry search: ".$stored_name);
			if ( $stored_name[0] == "/" ) $stored_name = substr($stored_name, 1); // if starts with /, delete it			
			
			$searched_pos = strpos($stored_name, $zip_entry);
			if ( ($searched_pos !== FALSE) && ($searched_pos == 0) )
			{
				//temp_log("like entry found: ".$stored_name);
				$zip_like_index_list[] = $i;
			}
		}
		
		return $zip_like_index_list;
	}
	
	// Method 2
	function zipFindIndex($zip_ar, $zip_search_entry_list)
	{
		$zip_index_list = array();
		$input_is_array = is_array($zip_search_entry_list);
		$input_zip_search_entry_count = count($zip_search_entry_list);
		$detected_count = 0;
		
		for ( $i = 0; $i < $zip_ar->numFiles ; $i++ )
		{
			$zip_stat = $zip_ar->statIndex($i);
			
			$stored_name = Utils::convert_to_utf8($zip_stat["name"], TRUE);
			if ( $stored_name[0] == "/" ) $stored_name = substr($stored_name, 1); // if starts with /, delete it
			
			if ( $input_is_array == TRUE )
			{
				$key = array_search($stored_name, $zip_search_entry_list);
				
				if ( $key !== FALSE )
				{
					$zip_index_list[$key] = $i;
					$detected_count++;
					
					//temp_log("in zip file detected for array input: ".$stored_name." [".$i."]");
					
					if ( $input_zip_search_entry_count == $detected_count )
					{
						// Exact Search Done
						break;
					}
				}
			}
			else // OneEntry Search의 경우는 Like Search를 사용할 일이 없다.
			{
				if ( $stored_name == $zip_search_entry_list )
				{
					$zip_index_list[0] = $i;
					//temp_log("in zip file detected: ".$stored_name." ".$i);
					break;
				}
			}
		}
		
		return $zip_index_list;
	}
	
	// Extract One Normal File
	function zipExtractOneFile($zipPath, $SelectedFile, $Options, $TargetDir = NULL, $limit_byte_count = 0, $zip_ar = NULL)
	{
		// ZIP Method 2
		if ( $zip_ar == NULL )
		{
			$zip_ar = new ZipArchive();
			$res = $zip_ar->open($this->getPath()."/".$zipPath);
			
			if ( $res !== TRUE )
			{
				//temp_log("zip open error: ".$res);
				return null;
			}
		}
		
		//temp_log("zip file: ".$this->getPath()."/".$zipPath);
		//temp_log("dest dir: ".$TargetDir);		
		
		//temp_log("selected file: ".$SelectedFile);
		if ( $SelectedFile[0] == "/" ) $SelectedFile = substr($SelectedFile, 1);
			
		$ZipIndex = $this->zipFindIndex($zip_ar, $SelectedFile);
		if ( $limit_byte_count != 0 )
		{
			$Contents = $zip_ar->getFromIndex($ZipIndex[0], $limit_byte_count);
		}
		else
		{
			$Contents = $zip_ar->getFromIndex($ZipIndex[0]);
		}		
		
		if ( $Options & ZIP_OPT_REMOVE_ALL_PATH )
		{
			file_put_contents($TargetDir."/".basename($SelectedFile), $Contents);				
		}
		else if ( $Options & ZIP_OPT_EXTRACT_AS_STRING )
		{
			$Contents = $zip_ar->getFromIndex($ZipIndex[0]);
			if ( $zip_ar == NULL )
			{
				$zip_ar->close();
			}
			
			return $Contents;
		}
		else
		{				
			file_put_contents($TargetDir."/".$SelectedFile, $Contents);
		}
		
		if ( $zip_ar == NULL )
		{
			$zip_ar->close();
		}
		
		return TRUE;
	}
	
	private function extract_unsupported_format_by_unzip($zip_file_name, $zip_entry_name, $dest_dir)
	{
		if ( $this->driverConf["USE_UNZIP_UTILITY"] )
		{
			$zip_file_fullname = $this->getPath()."/".$zip_file_name;
			
			$shell_command = "sudo unzip -o \"".Utils::unix_shell_escape_args($zip_file_fullname);
			$shell_command .= "\" \"".Utils::unix_shell_escape_args($zip_entry_name);
			$shell_command .= "\" -d \"".Utils::unix_shell_escape_args($dest_dir)."\"";
			exec($shell_command, $output, $shell_res);
			
			$extracted_file_fullname = $dest_dir."/".$zip_entry_name;
			$shell_command = "sudo mv \"".Utils::unix_shell_escape_args($extracted_file_fullname)."\" \"";
			$shell_command .= Utils::convert_to_utf8($extracted_file_fullname, TRUE)."\"";
			exec($shell_command, $output, $shell_res);
		}
		
		return TRUE;
	}

    /**
     * @param $selection UserSelection
     */
    function convertSelectionToTmpFiles($tmpDir, &$selection)
	{
		// ZIP Method 2
    	$zipPath = $selection->getZipPath();
    	$localDir = $selection->getZipLocalPath();
    	$files = $selection->getFiles();
		
		$zip_ar = new ZipArchive();
		$res = $zip_ar->open($this->getPath()."/".$zipPath);
		
		if ( $res !== TRUE )
		{
			//temp_log("zip open msg: ".$zip_ar->GetStatusString());
		}
		
		$selected_zip_entry = array();
		$selected_zip_file_entry = array();
		$selected_zip_folder_entry = array();
		$zipPathLen = strlen($zipPath);
		$select_zip_file_entry_count = 0;
		$select_zip_folder_entry_count = 0;
		foreach($files as $one_file)
		{
			$zip_entry_string = substr($one_file, $zipPathLen + 1);
			
			$selected_zip_entry[] = $zip_entry_string;
			
			if ( $zip_entry_string[strlen($zip_entry_string) - 1] == "/"  )
			{
				$selected_zip_folder_entry[] = $zip_entry_string;
				$select_zip_folder_entry_count++;
			}
			else
			{
				$selected_zip_file_entry[] = $zip_entry_string;
				$select_zip_file_entry_count++;
				
			}
		}

		$selected_zip_file_index = array();
		$selected_zip_folder_index = array();
		if ( $select_zip_file_entry_count > 0 )
		{
			$selected_zip_file_index = $this->zipFindIndex($zip_ar, $selected_zip_file_entry);
		}
		
		if ( $select_zip_folder_entry_count > 0 )
		{
			foreach ( $selected_zip_folder_entry as $folder_entry )
			{
				$selected_zip_folder_index = array_merge($selected_zip_folder_index,
														 $this->zipFindLikeIndex($zip_ar, $folder_entry));
			}
		}
		
		unset($selected_zip_file_entry);
		unset($selected_zip_folder_entry);
		
		$selected_zip_index = array_merge($selected_zip_folder_index, $selected_zip_file_index);
		
		unset($selected_zip_file_index);
		unset($selected_zip_folder_index);
		
		$extractable_selected_zip_index = array();
		foreach($selected_zip_index as  $one_index)
		{
			$zip_stat = $zip_ar->statIndex($one_index);
			$zip_entry_name = Utils::convert_to_utf8($zip_stat["name"], TRUE);
			$compress_method = $zip_stat["comp_method"];
			if ( $compress_method != 0 && $compress_method != 8 ) // store, deflate
			{
				// not supported zip compressed algorithm
				$this->extract_unsupported_format_by_unzip($zipPath, $zip_entry_name, $tmpDir);
				continue;
			}
			
			$extractable_selected_zip_index[] = $one_index;
			$zip_ar->renameIndex($one_index, $zip_entry_name);
		}
		
		unset($selected_zip_index);
		
		if ( count($extractable_selected_zip_index) > 0 )
		{
			$extract_res = $zip_ar->extractTo($tmpDir, $extractable_selected_zip_index);
			
			if ( $extract_res == FALSE )
			{
				//temp_log("zip msg: ".$zip_ar->GetStatusString());
			}
			$zip_ar->unchangeAll();
			$zip_ar->close();
		}
		
		$i = 0;
		foreach ($files as $key => $item){// Removed path
			$files[$key] = $tmpDir.($selected_zip_entry[$i][0] == "/" ? "" : "/").$selected_zip_entry[$i];
			$i++;
		}
		
    	$selection->setFiles($files);
    }
	
	private function is_mangled_name($name)
	{
		$filename = pathinfo($name, PATHINFO_FILENAME);
		
		if ( strlen($filename) == 8 )
		{
			$match_res = preg_match("/[A-Z0-9_]{6}~[A-Z0-9_]{1}/", $filename); // smb.conf : mangle prefix = 6
		
			if ( $match_res !== FALSE )
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	private function file_exists_by_smbclient($smb_filepath)
	{
		$ls_res = smbclientAccessDriver::singleton()->ls_smbclient($smb_filepath, FALSE, $Files, $DiskUsage);
		if ( $ls_res < 0 )
		{
			return $ls_res;
		}
		
		if ( count($Files) == 0 )
		{
			return NT_STATUS_NO_SUCH_FILE;
		}
		
		return 1;
	}
	
	private function is_file_by_smbclient($smb_filepath)
	{
		$ls_res = smbclientAccessDriver::singleton()->ls_smbclient($smb_filepath, FALSE, $Files, $DiskUsage);
		if ( $ls_res < 0 )
		{
			return $ls_res;
		}
		
		if ( count($Files) == 0 )
		{
			return NT_STATUS_NO_SUCH_FILE;
		}
		
		return ($this->IsFile($Files, basename($smb_filepath)) ? 1 : 0);
	}
	
	private function is_dir_by_smbclient($smb_dirpath)
	{
		$ls_res = smbclientAccessDriver::singleton()->ls_smbclient($smb_dirpath, FALSE, $Files, $DiskUsage);
		if ( $ls_res < 0 )
		{
			return $ls_res;
		}
		
		if ( count($Files) == 0 )
		{
			return NT_STATUS_NO_SUCH_FILE;
		}
		
		return ($this->IsDirectory($Files, basename($smb_dirpath)) ? 1 : 0);
	}
	
	//////////////////////////////////////////////
	// Shell Exec Functions Start
	//////////////////////////////////////////////
	private function exec_move_uploaded_file($tmp_file, $target_full_name)
	{
		/* for NS2 - quota check
		// 1. get logged user
		$loggedUser = AuthService::getLoggedUser();
		$user_name = $loggedUser->getId();
		//temp_log("userid: ".$user_name);
		
		// 2. get target volume
		$volume = explode("/", $target_full_name);
		$target_volume = $volume[3];	// '/mnt/disk/volume ...'
		//temp_log("target volume: ".$target_volume);
		
		// 3. get file size
		$get_size_cmd = 'sudo ls -s "'.Utils::unix_shell_escape_args($tmp_file).'"';
		exec($get_size_cmd, $get_size_output, $get_size_result);
		$file_size = explode(" ", $get_size_output[0]);
		//temp_log("tmp file size: ".$file_size[0]);
		
		// 4. check quota
		$quota_check_cmd = 'sudo nas-share get_available_user_space '.$user_name.' '.$target_volume;
		exec($quota_check_cmd, $quota_check_output, $quota_check_result);
		//temp_log("quota check: ".$quota_check_output[0]);
		
		// 4.1 (-1 : quota free)
		if ($quota_check_output[0] == -1)
		{
			;
		}
		else
		{
			if ($file_size[0] > $quota_check_output[0]) //file size is bigger than available user quota
			{
				return CHECK_QUOTA;
			}
		}
		*/
		
		// 5. upload
		$shell_cmd = 'sudo mv "'.Utils::unix_shell_escape_args($tmp_file).'" "'.Utils::unix_shell_escape_args($target_full_name).'"';
		exec($shell_cmd, $shell_output, $shell_result);
		//temp_log("temp_file:".$tmp_file." target_full_name:".$target_full_name);
		//temp_log("cmd: ".$shell_cmd." result: ".$shell_result);
		//temp_log("shell_output\n".dump_array($shell_output));
		
		if ( $shell_result != 0 )
		{
			return FALSE;
		}
		return TRUE;
		// user quota check를 위하여 smbclient사용 
		/*$shell_result1 = smbclientAccessDriver::singleton()->put_smbclient($tmp_file, $target_full_name);
		
		if ( $shell_result1 != 0 )
		{
			return FALSE;
		}
		return TRUE;*/
	}
	
	private function exec_change_mode_own($filePath)
	{
		$uid = $_SESSION["AJXP_REMOTE_UID"];
		$gid = $_SESSION["AJXP_REMOTE_GID"][0];
		if (!isset($uid))
		{
			$uid = "admin";	
		}
		if (!isset($gid))
		{
			$gid = "admin";	
		}
		
		$chmod_value = $this->repository->getOption("CHMOD_VALUE");
		if ( !isset($chmod_value) )
		{
			$chmod_value = "0766";
		}
		
		$shell_cmd = 'sudo chown '.$uid.':'.$gid.' "'.Utils::unix_shell_escape_args($filePath).'"'.
					'; sudo chmod '.$chmod_value.' "'.Utils::unix_shell_escape_args($filePath).'"';
		exec($shell_cmd, $shell_output, $shell_result);
		//temp_log("cmd: ".$shell_cmd." result: ".$shell_result);
		//temp_log("shell_output\n".dump_array($shell_output));

		if ( ($shell_result != 0) )
		{
			return FALSE;
		}
		
		return TRUE;
	}
	//////////////////////////////////////////////
	// Shell Exec Functions End
	//////////////////////////////////////////////
}
?>
