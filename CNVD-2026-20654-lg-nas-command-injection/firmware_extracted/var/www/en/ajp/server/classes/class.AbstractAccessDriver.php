<?php
/**
 * @package info.ajaxplorer
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
 * Description : Abstract representation of an action driver. Must be implemented.
 */
class AbstractAccessDriver extends AbstractDriver {
	
	/**
	* @var Repository
	*/
	var $repository;
	var $driverType = "access";
	var $info_data = array();
	
	function AbstractAccessDriver($driverName, $filePath, $repository) {
		
		parent::AbstractDriver($driverName);
		$this->repository = $repository;
		$this->initXmlActionsFile($filePath);		
		$this->actions["get_driver_info_panels"] = array();
		if(is_object($repository) && $repository->detectStreamWrapper()){
			$this->actions["cross_copy"] = array();
		}
	}
	
	function initRepository(){
		// To be implemented by subclasses
	}
	
	
	function applyAction($actionName, $httpVars, $filesVar)
	{
		if($actionName == "get_ajxp_info_panels" || $actionName == "get_driver_info_panels"){
			$this->sendInfoPanelsDef();
			return;
		}else if($actionName == "cross_copy"){
			$this->crossRepositoryCopy($httpVars);
			return ;
		}
		return parent::applyAction($actionName, $httpVars, $filesVar);
	}
	
	function initXmlActionsFile($filePath){
		parent::initXmlActionsFile($filePath);
		if(isSet($this->actions["public_url"]) && !defined('PUBLIC_DOWNLOAD_FOLDER') || !is_dir(PUBLIC_DOWNLOAD_FOLDER) || !is_writable(PUBLIC_DOWNLOAD_FOLDER)){
			unset($this->actions["public_url"]);
		}		
	}
	
	/**
	 * Print the XML for actions
	 *
	 * @param boolean $filterByRight
	 * @param User $user
	 */
	function sendActionsToClient($filterByRight, $user){
		parent::sendActionsToClient($filterByRight, $user, $this->repository);
	}
		
	function sendInfoPanelsDef(){
		if ( $this->xmlFilePath == INSTALL_PATH."/plugins/access.smb/smbActions.xml" )
		{
			//include(INSTALL_PATH."/plugins/access.smb/smbActions_info.php");
			
			AJXP_XMLWriter::header();
			AJXP_XMLWriter::write('<infoPanels><infoPanel mime="no_selection" attributes=""><messages><message key="folders_string" id="130"/><message key="files_string" id="265"/><message key="totalsize_string" id="259"/></messages><html><![CDATA[<div style="padding:10px;"><big style="font-weight: bold; font-size: 14px; color:#79f;display: block; text-align:center; padding-bottom:20px;"><img width="16" hspace="5" height="16" border="0" align="absmiddle" src="client/images/crystal/mimes/16/folder.png"/>#{current_folder}</big><b>#{folders_string}</b> : #{filelist_folders_count}<br><b>#{files_string}</b> : #{filelist_files_count}<br><b>#{totalsize_string}</b> #{filelist_totalsize}</div>]]></html></infoPanel><infoPanel mime="generic_file" attributes="basename,icon,filesize,mimestring,formated_date"><messages><message key="name_string" id="133"/><message key="size_string" id="127"/><message key="type_string" id="134"/><message key="modif_string" id="138"/></messages><html><![CDATA[<div style="padding:10px;"><div class="folderImage"><img src="client/images/crystal/mimes/64/#{icon}" height="64" width="64"></div><b>#{name_string}</b> : #{basename}<br><b>#{size_string}</b> : #{filesize}<br><b>#{type_string}</b> : #{mimestring}<br><b>#{modif_string}</b> : #{formated_date}</div>]]></html></infoPanel><infoPanel mime="generic_dir" attributes="basename,icon,formated_date"><messages><message key="name_string" id="133"/><message key="modif_string" id="138"/></messages><html><![CDATA[<div style="padding:10px;"><div class="folderImage"><img src="client/images/crystal/mimes/64/#{icon}" height="64" width="64"></div><b>#{name_string}</b> : #{basename}<br><b>#{modif_string}</b> : #{formated_date}</div>]]></html></infoPanel><infoPanel mime="png,bmp,jpg,jpeg,gif" attributes="basename,encoded_filename,compute_image_dimensions,image_width,image_height,image_type,filesize,mimestring,formated_date"><messages><message key="name_string" id="133"/><message key="size_string" id="127"/><message key="type_string" id="134"/><message key="modif_string" id="138"/><message key="dim_string" id="135"/></messages><html><![CDATA[<div style="padding:10px;"><center style="border:1px solid #aaa; margin-bottom: 5px;"><img src="content.php?action=image_proxy&get_thumb=true&file=#{encoded_filename}" #{compute_image_dimensions}></center><b>#{name_string}</b> : #{basename}<br><b>#{dim_string}</b> : #{image_width}px X #{image_height}px<br><b>#{size_string}</b> : #{filesize}<br><b>#{type_string}</b> : #{image_type}<br><b>#{modif_string}</b> : #{formated_date}</div>]]></html></infoPanel><infoPanel mime="mp3" attributes="basename,escaped_filename,icon,filesize,mimestring,formated_date"><messages><message key="name_string" id="133"/><message key="size_string" id="127"/><message key="type_string" id="134"/><message key="modif_string" id="138"/></messages><html><![CDATA[<div style="padding:10px;"><b><font size=3 color=coral> Play Music </font><div id="mp3_container" style="text-align:center; padding:5px; width:160px;margin-bottom: 5px;"><object type="application/x-shockwave-flash" data="client/flash/dewplayer-mini.swf?mp3=content.php?action=mp3_proxy%26file=#{escaped_filename}&amp;bgcolor=FFFFFF&amp;showtime=1" width="150" height="20"><param name="wmode" value="transparent"><param name="movie" value="client/flash/dewplayer-mini.swf?mp3=content.php?action=mp3_proxy%26file=#{escaped_filename}&amp;bgcolor=FFFFFF&amp;showtime=1" /></object></div><b>#{name_string}</b> : #{basename}<br><b>#{size_string}</b> : #{filesize}<br><b>#{type_string}</b> : #{mimestring}<br><b>#{modif_string}</b> : #{formated_date}</div>]]></html></infoPanel></infoPanels>', true);
			AJXP_XMLWriter::close();
			exit(1);
	    
		}
		/*
		//error_log("file path: ".$this->xmlFilePath."\n",3,"/tmp/info_tmp.log");
		$fileData = file_get_contents($this->xmlFilePath);
		$matches = array();
		preg_match('/<infoPanels>.*<\/infoPanels>/', str_replace("\n", "",$fileData), $matches);
		if(count($matches)){
			AJXP_XMLWriter::header();
			AJXP_XMLWriter::write($this->replaceAjxpXmlKeywords(str_replace("\n", "",$matches[0])), true);
			AJXP_XMLWriter::close();
			exit(1);
		}*/		
	}
    
    /** Cypher the publiclet object data and write to disk.
        @param $data The publiclet data array to write 
                     The data array must have the following keys:
                     - DRIVER      The driver used to get the file's content      
                     - OPTIONS     The driver options to be successfully constructed (usually, the user and password)
                     - FILE_PATH   The path to the file's content
                     - PASSWORD    If set, the written publiclet will ask for this password before sending the content
                     - ACTION      If set, action to perform
                     - EXPIRE_TIME If set, the publiclet will deny downloading after this time, and probably self destruct.
        @return the URL to the downloaded file
    */
    function writePubliclet($data)
    {
    	if(!defined('PUBLIC_DOWNLOAD_FOLDER') || !is_dir(PUBLIC_DOWNLOAD_FOLDER)){
    		return "Public URL folder does not exist!";
    	}
        $data["DRIVER_NAME"] = $this->driverName;
        $data["XML_FILE_PATH"] = $this->xmlFilePath;
        $data["REPOSITORY"] = $this->repository;
        if ($data["ACTION"] == "") $data["ACTION"] = "download";
        // Create a random key
        $data["FINAL_KEY"] = md5(mt_rand().time());
        // Cypher the data with a random key
        $outputData = serialize($data);
        // Hash the data to make sure it wasn't modified
        $hash = md5($outputData);
        // The initialisation vector is only required to avoid a warning, as ECB ignore IV
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        // We have encoded as base64 so if we need to store the result in a database, it can be stored in text column
        $outputData = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $hash, $outputData, MCRYPT_MODE_ECB, $iv));
        // Okay, write the file:
        $fileData = "<"."?"."php \n".
        '   require_once("'.str_replace("\\", "/", INSTALL_PATH).'/publicLet.inc.php"); '."\n".
        '   $id = str_replace(".php", "", basename(__FILE__)); '."\n". // Not using "" as php would replace $ inside
        '   $cypheredData = base64_decode("'.$outputData.'"); '."\n".
        '   $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND); '."\n".
        '   $inputData = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $id, $cypheredData, MCRYPT_MODE_ECB, $iv));  '."\n".
        '   if (md5($inputData) != $id) { header("HTTP/1.0 401 Not allowed, script was modified"); exit(); } '."\n".
        '   // Ok extract the data '."\n".
        '   $data = unserialize($inputData); AbstractAccessDriver::loadPubliclet($data); ?'.'>';
        if (@file_put_contents(PUBLIC_DOWNLOAD_FOLDER."/".$hash.".php", $fileData) === FALSE){
            return "Can't write to PUBLIC URL";
        }
        if(defined('PUBLIC_DOWNLOAD_URL') && PUBLIC_DOWNLOAD_URL != ""){
        	return PUBLIC_DOWNLOAD_URL."/".$hash.".php";
        }else{
	        $http_mode = (!empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
	        $fullUrl = $http_mode . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);    
	        return str_replace("\\", "/", $fullUrl.str_replace(INSTALL_PATH, "", PUBLIC_DOWNLOAD_FOLDER)."/".$hash.".php");
        }
    }

    /** Load a uncyphered publiclet */
    function loadPubliclet($data)
    {
        // create driver from $data
        $className = $data["DRIVER"]."AccessDriver";
        if ($data["EXPIRE_TIME"] && time() > $data["EXPIRE_TIME"])
        {
            // Remove the publiclet, it's done
            if (strstr(PUBLIC_DOWNLOAD_FOLDER, $_SERVER["SCRIPT_FILENAME"]) !== FALSE)
                unlink($_SERVER["SCRIPT_FILENAME"]);
            
            echo "Link is expired, sorry.";
            exit();
        }
        // Check password
        if (strlen($data["PASSWORD"]))
        {
	    $pwd = $_POST['password'];
	    //패스워드 입력에 특수문자 걸러내기 - 패스워드 뒤에 '.' 삽입시 다운로드 되는 문제 있었음.
	    if(preg_match("/[!#$%^&*.()?+=\/]/",$pwd))
	    {
		//$mess = ConfService::getMessages();
		//$session_out_msg = $mess[500];
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv='cache-control' content='no-cache' />\n";
		echo "<meta http-equiv='pragma' content='no-cache' />\n";
		echo "<script language=\"javascript\">\n";
		//echo "alert(\"".$session_out_msg."\");\n";
		echo "alert(\"Special characters are not allowed.\");\n";
		echo "</script>\n";
		echo "</head>\n";
		echo "<body><form method='post'>This file requires a password<br><input type='password' name='password'><input type='submit' value='download'></form></body></html>";
                exit();
		
	    }
	    else
	    {
		;
	    }
	    
            if ($_POST['password'] != $data["PASSWORD"])
            {
                echo "<html><body><form method='post'>This file requires a password<br><input type='password' name='password'><input type='submit' value='download'></form></body></html>";
                exit();
            }
        }
        $filePath = INSTALL_PATH."/plugins/access.".$data["DRIVER"]."/class.".$className.".php";
        if(!is_file($filePath)){
                die("Warning, cannot find driver for conf storage! ($name, $filePath)");
        }
        require_once($filePath);
        $driver = new $className( $data["DRIVER_NAME"], $data["XML_FILE_PATH"], $data["REPOSITORY"], $data["OPTIONS"]);
        $driver->initRepository();
        $driver->switchAction($data["ACTION"], array("file"=>$data["FILE_PATH"]), "PUBLIC_LINK");
    }

    /** Create a publiclet object, that will be saved in PUBLIC_DOWNLOAD_FOLDER
        Typically, the class will simply create a data array, and call return writePubliclet($data)
        @param $filePath The path to the file to share
        @return The full public URL to the publiclet.
    */
    function makePubliclet($filePath) {}
    
    function crossRepositoryCopy($httpVars){
    	
    	ConfService::detectRepositoryStreams(true);
    	$mess = ConfService::getMessages();
		$selection = new UserSelection();
		$selection->initFromHttpVars($httpVars);
    	$files = $selection->getFiles();
    	
    	$accessType = $this->repository->getAccessType();    	
    	$repositoryId = $this->repository->getId();
    	$origStreamURL = "ajxp.$accessType://$repositoryId";    	
    	
    	$destRepoId = $httpVars["dest_repository_id"];
    	$destRepoObject = ConfService::getRepositoryById($destRepoId);
    	$destRepoAccess = $destRepoObject->getAccessType();
    	$destStreamURL = "ajxp.$destRepoAccess://$destRepoId";
    	
    	// Check rights
    	if(AuthService::usersEnabled()){
	    	$loggedUser = AuthService::getLoggedUser();
	    	if(!$loggedUser->canRead($repositoryId) || !$loggedUser->canWrite($destRepoId)){
	    		AJXP_XMLWriter::header();
	    		AJXP_XMLWriter::sendMessage(null, "You do not have the right to access one of the repositories!");
	    		AJXP_XMLWriter::close();
	    		exit(1);
	    	}
    	}
    	
    	$messages = array();
    	foreach ($files as $file){
    		$origFile = $origStreamURL.$file;
    		$destFile = $destStreamURL.$httpVars["dest"]."/".basename($file);    		
			$origHandler = fopen($origFile, "r");
			$destHandler = fopen($destFile, "w");
			if($origHandler === false || $destHandler === false) {
				$errorMessages[] = AJXP_XMLWriter::sendMessage(null, $mess[114]." ($origFile to $destFile)", false);
				continue;
			}
			while(!feof($origHandler)){
				fwrite($destHandler, fread($origHandler, 4096));
			}
			fflush($destHandler);
			fclose($origHandler); 
			fclose($destHandler);			
			$messages[] = $mess[34]." ".SystemTextEncoding::toUTF8(basename($origFile))." ".$mess[73]." ".SystemTextEncoding::toUTF8($destFile);
    	}
    	AJXP_XMLWriter::header();    	
    	if(count($errorMessages)){
    		AJXP_XMLWriter::sendMessage(null, join("\n", $errorMessages), true);
    	}
    	AJXP_XMLWriter::sendMessage(join("\n", $messages), null, true);
    	AJXP_XMLWriter::close();
    	exit(0);
    }

}

?>
