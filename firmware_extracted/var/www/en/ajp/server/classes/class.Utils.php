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
 * Description : various utils methods.
 */
class Utils
{
	
	function securePath($path)
	{
		if($path == null) $path = ""; 
		//
		// REMOVE ALL "../" TENTATIVES
		//
		$dirs = explode('/', $path);
		for ($i = 0; $i < count($dirs); $i++)
		{
			if ($dirs[$i] == '.' or $dirs[$i] == '..')
			{
				$dirs[$i] = '';
			}
		}
		// rebuild safe directory string
		$path = implode('/', $dirs);
		
		//
		// REPLACE DOUBLE SLASHES
		//
		$path = str_replace('//', '/', $path);
		return $path;
	}
	
	function parseFileDataErrors($boxData, $errorCodes)
	{
		$mess = ConfService::getMessages();
		$userfile_error = $boxData["error"];		
		$userfile_tmp_name = $boxData["tmp_name"];
		$userfile_size = $boxData["size"];
		if ($userfile_error != UPLOAD_ERR_OK)
		{
			$errorsArray = array();
			$errorsArray[UPLOAD_ERR_FORM_SIZE] = $errorsArray[UPLOAD_ERR_INI_SIZE] = ($errorCodes?"409 ":"")."File is too big! Max is".ini_get("upload_max_filesize");
			$errorsArray[UPLOAD_ERR_NO_FILE] = ($errorCodes?"410 ":"")."No file found on server!";
			$errorsArray[UPLOAD_ERR_PARTIAL] = ($errorCodes?"410 ":"")."File is partial";
			$errorsArray[UPLOAD_ERR_INI_SIZE] = ($errorCodes?"410 ":"")."No file found on server!";
			if($userfile_error == UPLOAD_ERR_NO_FILE)
			{
				// OPERA HACK, do not display "no file found error"
				if(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') === FALSE)
				{
					return $errorsArray[$userfile_error];				
				}
			}
			else
			{
				return $errorsArray[$userfile_error];
			}
		}
		if ($userfile_tmp_name=="none" || $userfile_size == 0)
		{
			return ($errorCodes?"410 ":"").$mess[31];
		}
		return null;
	}
	
	function mergeArrays($t1,$t2)
	{
		$liste = array();
		$tab1=$t1; $tab2=$t2;
		if(is_array($tab1)) {while (list($cle,$val) = each($tab1)) {$liste[$cle]=$val;}}
		if(is_array($tab2)) {while (list($cle,$val) = each($tab2)) {$liste[$cle]=$val;}}
		return $liste;
	}
	
	function removeWinReturn($fileContent)
	{
		$fileContent = str_replace(chr(10), "", $fileContent);
		$fileContent = str_replace(chr(13), "", $fileContent);
		return $fileContent;
	}
	
	function tipsandtricks()
	{
		$tips = array();
		$tips[] = "DoubleClick in the list to directly download a file or to open a folder.";
		$tips[] = "When the 'Edit' button is enabled (on text files), you can directly edit the selected file online.";
		$tips[] = "Type directly a folder URL in the location bar then hit 'ENTER' to go to a given folder.";
		$tips[] = "Use MAJ+Click and CTRL+Click to perform multiple selections in the list.";
		$tips[] = "Use the Bookmark button to save your frequently accessed locations in the bookmark bar.";
		$tips[] = "Use the TAB button to navigate through the main panels (tree, list, location bar).";
		$tips[] = "Use the 'u' key to go to the parent directory.";
		$tips[] = "Use the 'h' key to refresh current listing.";
		$tips[] = "Use the 'b' key to bookmark current location to your bookmark bar.";
		$tips[] = "Use the 'l' key to open Upload Form.";
		$tips[] = "Use the 'd' key to create a new directory in this folder.";
		$tips[] = "Use the 'f' key to create a new file in this folder.";
		$tips[] = "Use the 'r' key to rename a file.";
		$tips[] = "Use the 'c' key to copy one or more file or folders to a different folder.";
		$tips[] = "Use the 'm' key to move one or more file or folders to a different folder.";
		$tips[] = "Use the 's' key to delete one or more file or folders.";
		$tips[] = "Use the 'e' key to edit a file or view an image.";
		$tips[] = "Use the 'o' key to download a file to your hard drive.";
		return $tips[array_rand($tips, 1)];
	}
	
	function processFileName($fileName, &$invalid_char_count)
	{
		$max_caracteres = ConfService::getConf("MAX_CHAR");
		// Don't allow those chars on Windows XP, Vista: \ / : * < > |
		$fileName = trim(SystemTextEncoding::magicDequote($fileName));
		
		$not_allowed_chars_regexp = preg_quote(NOT_ALLOWED_FILENAME_CHAR, "/");
		$not_allowed_chars_regexp = str_replace("\\\\", "\\\\\\\\", $not_allowed_chars_regexp);		
		$fileNameTmp = preg_replace("/[".$not_allowed_chars_regexp."]/", "", $fileName, -1, $invalid_char_count);
		
		$fileNameTmp = preg_replace("/\s*\.$/", "", $fileNameTmp);
		
		return substr($fileNameTmp, 0, $max_caracteres);
	}
	
	function mimetype($fileName,$mode, $isDir)
	{
		$icon_typename = array(
							"mid" => array("midi.png", 9),
							"txt" => array("txt2.png", 10),
							"sql" => array("txt2.png", 10),
							"js" => array("javascript.png", 11),
							"gif" => array("image.png", 12),
							"jpg" => array("image.png", 13),
							"html" => array("html.png", 14),
							"htm" => array("html.png", 15),
							"rar" => array("archive.png", 60),
							"gz" => array("zip.png", 61),
							"tgz" => array("archive.png", 61),
							"z" => array("archive.png", 61),
							"ra" => array("video.png", 16),
							"ram" => array("video.png", 17),
							"pl" => array("source_pl.png", 18),
							"zip" => array("zip.png", 19),
							"wav" => array("sound.png", 20),
							"php" => array("php.png", 21),
							"php3" => array("php.png", 22),
							"phtml" => array("php.png", 22),
							"exe" => array("exe.png", 50),
							"bmp" => array("image.png", 56),
							"png" => array("image.png", 57),
							"css" => array("css.png", 58),
							"mp3" => array("sound.png", 59),
							"xls" => array("spreadsheet.png", 64),
							"doc" => array("document.png", 65),
							"pdf" => array("pdf.png", 79),
							"mov" => array("video.png", 80),
							"avi" => array("video.png", 81),
							"mpg" => array("video.png", 82),
							"mpeg" => array("video.png", 83),
							"wmv" => array("video.png", 81),
							"swf" => array("flash.png", 91),
							"flv" => array("flash.png", 91),
							);
		$mess = ConfService::getMessages();
		$regexp = "/\.(".implode("|", array_keys($icon_typename)).")$/";
		if($isDir){$image="folder.png";$typeName=$mess[8];}
		else if ( preg_match($regexp, strtolower($fileName), $Matches) == 1)
		{
			$image = $icon_typename[$Matches[1]][0];
			$typeName = $mess[$icon_typename[$Matches[1]][1]];
		}
		else {$image="mime_empty.png";$typeName=$mess[23];}
		if($mode=="image"){return $image;} else {return $typeName;}
	}
		
	function getAjxpMimes($keyword){
		if($keyword == "editable"){
			return "txt,sql,php,php3,phtml,htm,html,cgi,pl,js,css,inc,xml,xsl,java";
		}else if($keyword == "image"){
			return "png,bmp,jpg,jpeg,gif";
		}else if($keyword == "audio"){
			return "mp3";
		}else if($keyword == "zip"){
			if(ConfService::zipEnabled()){
				return "zip";
			}else{
				return "none_allowed";
			}
		}
		return "";
	}
		
	function is_image($fileName)
	{
		if(preg_match("/\.png$|\.bmp$|\.jpg$|\.jpeg$|\.gif$/", strtolower($fileName)) == 1){
			return 1;
		}
		return 0;
	}
	
	function is_mp3($fileName)
	{
		if(preg_match("/\.mp3$/", strtolower($fileName)) == 1) return 1;
		return 0;
	}
	
	function getImageMimeType($fileName)
	{
		$mime_type = array(
							"jpg" => "image/jpeg",
							"jpeg" => "image/jpeg",
							"png" => "image/png",
							"bmp" => "image/bmp",
							"gif" => "image/gif"
						  );
		$regexp = "/\.(".implode("|", array_keys($mime_type)).")$/";
		if ( preg_match($regexp, strtolower($fileName), $Matches) == 1)
		{
			return $mime_type[$Matches[1]];
		}
		
		return "";
	}
	
	function roundSize($filesize)
	{
		$mess = ConfService::getMessages();
		$size_unit = $mess["byte_unit_symbol"];
		if($filesize < 0){
			$filesize = sprintf("%u", $filesize);
		}
		if ($filesize >= 1073741824) {$filesize = round($filesize / 1073741824 * 100) / 100 . " G".$size_unit;}
		elseif ($filesize >= 1048576) {$filesize = round($filesize / 1048576 * 100) / 100 . " M".$size_unit;}
		elseif ($filesize >= 1024) {$filesize = round($filesize / 1024 * 100) / 100 . " K".$size_unit;}
		else {$filesize = $filesize . " ".$size_unit;}
		if($filesize==0) {$filesize="-";}
		return $filesize;
	}
		
	function isHidden($fileName){
		return (substr($fileName,0,1) == ".");
	}
	
	/**
	 * Convert a shorthand byte value from a PHP configuration directive to an integer value
	 * @param    string   $value
	 * @return   int
	 */
	function convertBytes( $value ) 
	{
	    if ( is_numeric( $value ) ) 
	    {
	        return $value;
	    } 
	    else 
	    {
	        $value_length = strlen( $value );
	        $qty = substr( $value, 0, $value_length - 1 );
	        $unit = strtolower( substr( $value, $value_length - 1 ) );
	        switch ( $unit ) 
	        {
	            case 'k':
	                $qty *= 1024;
	                break;
	            case 'm':
	                $qty *= 1048576;
	                break;
	            case 'g':
	                $qty *= 1073741824;
	                break;
	        }
	        return $qty;
	    }
	}

	function xmlEntities($string){
		return str_replace(array("&", "<",">"), array("&amp;", "&lt;","&gt;"), $string);
	}
	
	function updateI18nFiles()
	{
		include(INSTALL_PATH."/".CLIENT_RESOURCES_FOLDER."/i18n/en.php");
		$reference = $mess;
		$languages = ConfService::listAvailableLanguages();
		foreach ($languages as $key=>$value){
			$filename = INSTALL_PATH."/".CLIENT_RESOURCES_FOLDER."/i18n/".$key.".php";
			include($filename);
			$missing = array();
			foreach ($reference as $messKey=>$message){
				if(!array_key_exists($messKey, $mess)){
					$missing[] = "\"$messKey\" => \"$message\",";
				}
			}
			//print_r($missing);
			if(count($missing)){
				$header = array();
				$currentMessages = array();
				$footer = array();
				$fileLines = file($filename);
				foreach ($fileLines as $line){
					if(strstr($line, "\"") !== false){
						$currentMessages[] = trim($line);
					}else{
						if(!count($currentMessages)){
							$header[] = trim($line);
						}else{
							$footer[] = trim($line);
						}
					}
				}
				$currentMessages = array_merge($header, $currentMessages, $missing, $footer);
				file_put_contents($filename, join("\n", $currentMessages));
			}
		}
	}

	function testResultsToTable($outputArray, $testedParams, $showSkipLink = true){
		$style = '
		<style>
		body {
		background-color:#e0ecff;
		background-image:url(client/images/GradientBg.gif);
		background-position:center top;
		background-repeat:repeat-x;
		margin:0;
		padding:20;
		}
		* {font-family:arial, sans-serif;font-size:11px;color:#006}
		h1 {font-size: 20px; color:#e0ecff}
		thead tr{background-color: #ccc; font-weight:bold;}
		tr.dump{background-color: #ee9;}
		tr.passed{background-color: #ae9;}
		tr.failed{background-color: #ea9;}
		tr.warning{background-color: #f90;}
		td {padding: 3px 6px;}
		td.col{font-weight: bold;}
		</style>
		';
		$htmlHead = "<html><head><title>AjaXplorer : Diagnostic Tool</title>$style</head><body><h1>AjaXplorer Diagnostic Tool</h1>";
		if($showSkipLink){
			$htmlHead .= "<p>The diagnostic tool detected some errors or warning : you are likely to have problems running AjaXplorer!</p>";
		}
		$html = "<table width='700' border='0' cellpadding='0' cellspacing='1'><thead><tr><td>Name</td><td>Result</td><td>Info</td></tr></thead>"; 
		$dumpRows = "";
		$passedRows = "";
		$warnRows = "";
		$errs = $warns = 0;
		foreach($outputArray as $item)
		{
		    // A test is output only if it hasn't succeeded (doText returned FALSE)
		    $result = $item["result"] ? "passed" : ($item["level"] == "info" ? "dump" : ($item["level"]=="warning"? "warning":"failed"));
		    $success = $result == "passed";    
		    $row = "<tr class='$result'><td class='col'>".$item["name"]."</td><td>".$result."&nbsp;</td><td>".(!$success ? $item["info"] : "")."&nbsp;</td></tr>";
		    if($result == "dump"){
		    	$dumpRows .= $row;
		    }else if($result == "passed"){
		    	$passedRows .= $row;
		    }else if($item["level"] == "warning"){
		    	$warnRows .= $row;
		    	$warns ++;
		    }else{
		    	$html .= $row;
		    	$errs ++;
		    }
		}
		$html .= $warnRows;
		$html .= $passedRows;
		$html .= $dumpRows;
		$html .= "</table>";
		if($showSkipLink){
			if(!$errs){
				$htmlHead .= "<p>STATUS : You have some warning, but no fatal error, AjaXplorer should run ok, <a href='index.php?ignore_tests=true'>click here to continue to AjaXplorer!</a> (this test won't be launched anymore)</p>";
			}else{
				$htmlHead .= "<p>STATUS : You have some errors that may prevent AjaXplorer from running. Please check the red lines to see what action you should do. If you are confident enough and know that your usage of AjaXplorer does not need these errors to fixed, <a href='index.php?ignore_tests=true'>continue here to Ajaxplorer!.</a></p>";
			}
		}
		$html.="</body></html>";
		return $htmlHead.nl2br($html);
	}
	
	function runTests(&$outputArray, &$testedParams){
		// At first, list folder in the tests subfolder
		chdir(INSTALL_PATH.'/server/tests');
		$files = glob('*.php'); 
		
		$outputArray = array();
		$testedParams = array();
		$passed = true;
		foreach($files as $file)
		{
		    require_once($file);
		    // Then create the test class
		    $testName = str_replace(".php", "", substr($file, 5));
		    $class = new $testName();
		    
		    $result = $class->doTest();
		    if(!$result && $class->failedLevel != "info") $passed = false;
		    $outputArray[] = array(
		    	"name"=>$class->name, 
		    	"result"=>$result, 
		    	"level"=>$class->failedLevel, 
		    	"info"=>$class->failedInfo); 
		   	if(count($class->testedParams)){
			    $testedParams = array_merge($testedParams, $class->testedParams);
		   	}
		}
		
        // PREPARE REPOSITORY LISTS
        $repoList = array();
        require_once("../classes/class.ConfService.php");
        require_once("../classes/class.Repository.php");
        include("../conf/conf.php");
        foreach($REPOSITORIES as $index => $repo){
            $repoList[] = ConfService::createRepositoryFromArray($index, $repo);
        }        
        // Try with the serialized repositories
        if(is_file("../conf/repo.ser")){
            $fileLines = file("../conf/repo.ser");
            $repos = unserialize($fileLines[0]);
            $repoList = array_merge($repoList, $repos);
        }
		
		// NOW TRY THE PLUGIN TESTS
		chdir(INSTALL_PATH.'/server/tests/plugins');
		$files = glob('*.php'); 
		foreach($files as $file)
		{
		    require_once($file);
		    // Then create the test class
		    $testName = str_replace(".php", "", substr($file, 5))."Test";
		    $class = new $testName();
		    foreach ($repoList as $repository){
			    $result = $class->doRepositoryTest($repository);
			    if($result === false || $result === true){			    	
				    if(!$result && $class->failedLevel != "info") $passed = false;
				    $outputArray[] = array(
				    	"name"=>$class->name . "\n Testing repository : ".$repository->getDisplay(), 
				    	"result"=>$result, 
				    	"level"=>$class->failedLevel, 
				    	"info"=>$class->failedInfo); 				    
				   	if(count($class->testedParams)){
					    $testedParams = array_merge($testedParams, $class->testedParams);
				   	}
			    }
		    }
		}
		
		return $passed;
	}	
	
	function testResultsToFile($outputArray, $testedParams){
		ob_start();
		echo '$diagResults = ';
		var_export($testedParams);
		echo ';';
		echo '$outputArray = ';
		var_export($outputArray);
		echo ';';
		$content = '<?php '.ob_get_contents().' ?>';
		ob_end_clean();
		//print_r($content);
		file_put_contents(TESTS_RESULT_FILE, $content);		
	}
	
	/**
	 * Load an array stored serialized inside a file.
	 *
	 * @param String $filePath Full path to the file
	 * @return Array
	 */
	function loadSerialFile($filePath){
		$filePath = str_replace("AJXP_INSTALL_PATH", INSTALL_PATH, $filePath);
		$result = array();
		if(is_file($filePath))
		{
			$fileLines = file($filePath);
			$result = unserialize($fileLines[0]);
		}
		return $result;
	}
	
	/**
	 * Stores an Array as a serialized string inside a file.
	 *
	 * @param String $filePath Full path to the file
	 * @param Array $value The value to store
	 * @param Boolean $createDir Whether to create the parent folder or not, if it does not exist.
	 */
	function saveSerialFile($filePath, $value, $createDir=true){
		$filePath = str_replace("AJXP_INSTALL_PATH", INSTALL_PATH, $filePath);
		if($createDir && !is_dir(dirname($filePath))) {			
			if(!is_writeable(dirname(dirname($filePath)))){
				die("Cannot write into ".dirname(dirname($filePath)));
			}
			mkdir(dirname($filePath));
		}
		$fp = fopen($filePath, "w");
		fwrite($fp, serialize($value));
		fclose($fp);
	}
	
	function DetectClientOS()
	{
		$os = 'unsupported';
		$mobile = FALSE;
	
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
	
		if ( strpos($userAgent, 'linux') )
		{
			$os = 'linux';
		}
		else if ( strpos($userAgent, 'macintosh') || strpos($userAgent, 'mac os x') )
		{
			$os = 'mac';
		}
		else if ( strpos($userAgent, 'windows') || strpos($userAgent, 'win32') )
		{
			$os = 'windows';
		}
		else
		{
			$UNIX_NAMES    = "freebsd,openbsd,netbsd,bsd,unixware,solaris,sunos,sun4,sun5,suni86,sun,irix5,irix6,irix,hpux9,hpux10,hpux11,hpux,hp-ux,aix1,aix2,aix3,aix4,aix5,aix,sco,unixware,mpras,reliant,dec,sinix,unix";
			$UNIX_REGX   = "(?:".str_replace(",", ")|(?:", $SUBCLASS_NAMES).")";
			
			$found = preg_match("/".$UNIX_REGX."/", strtolower($userAgent), $matches);
			
			if ( $found == 1 )
			{
				$os = "unix";
			}
		}
		
		if ( strpos($userAgent, 'windows ce') )
		{
			$mobile = TRUE;
		}
		else if ( strpos($userAgent, 'iphone os') || strpos($userAgent, 'ipod') )
		{
			$mobile = TRUE;
		}
	
		return array(
			"os"           => $os,
			"mobile"       => $mobile,
			);
	}
	
	function DetectClientCharset()
	{
		$ClientCharset = "UTF-8";
		
		$ClientOS = Utils::DetectClientOS();
		
		$ClientAcceptCharset = isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : "";
		$ClientAcceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$FirstLanguage = strtolower(substr($ClientAcceptLanguage,0, 2));
		
		$AcceptCharsetSearchNeeded= FALSE;
		
		if ( $ClientOS["os"] == "windows" )
		{
			// 영어, 한국어, 독일어, 스페인어, 프랑스어, 네덜란드어, 스웨덴어, 덴마크어
			$PossibleLanguage = array("en", "ko", "de", "es", "fr", "nl", "sv", "da");
			
			if ( in_array($FirstLanguage, $PossibleLanguage) === TRUE )
			{
				$AcceptCharsetSearchNeeded = TRUE;
			}
		}
		else
		{
			if ( $ClientAcceptCharset == "" )
			{
				$AcceptCharsetSearchNeeded = FALSE; // ClientAcceptCharset이 정의되지 않은 경우 UTF-8을 사용
			}
			else if ( strpos(strtoupper($ClientAcceptCharset), "UTF-8") === FALSE )
			{
				$AcceptCharsetSearchNeeded = TRUE;
			}
		}
		
		$UseDefaultCharset = TRUE;
		
		if ( $AcceptCharsetSearchNeeded == TRUE )
		{
			if ( $ClientAcceptCharset !== "" )
			{
				$FirstAcceptCharset = explode(",", $ClientAcceptCharset);
					
				if ( isset($FirstAcceptCharset[0]) )
				{
					$UpperedCharset = strtoupper($FirstAcceptCharset[0]);
					$UpperedCharset= str_replace("WINDOWS-", "CP", $UpperedCharset);
					
					$PossibleCharset = array("UTF-8", "ASCII", "ISO-8859-1", "ISO-8859-15", "EUC-KR", "UHC", "CP949", "ISO-2022-HR", "CP1252");
					
					if ( in_array($UpperedCharset, $PossibleCharset) )
					{
						$ClientCharset = $UpperedCharset;
						$UseDefaultCharset = FALSE;
					}
					else if ( strpos(strtoupper($ClientAcceptCharset), "UTF-8") !== FALSE )
					{
						// use UTF-8
						$UseDefaultCharset = FALSE;
					}
				}
			}
			
			if ( $UseDefaultCharset == TRUE )
			{
				if ( isset($FirstLanguage) )
				{
					switch ( $FirstLanguage )
					{
						case "en":
							$ClientCharset = "ISO-8859-1";
							break;
						case "ko":
							$ClientCharset = "EUC-KR";
							break;
						case "es":
						case "de":
						case "fr":
						case "da":
						case "sv":
						case "nl":
							//$ClientCharset = "CP1252";
							$ClientCharset = "ISO-8859-1";
							break;
						default:
							$ClientCharset = "ISO-8859-1";
							break;
					}
				}
				else
				{
					$ClientCharset = "ISO-8859-1";
				}
			}
		}
		
		return $ClientCharset;
	}
	
	function DetectLoginLanguage()
	{
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);		
		
		$RequestLanguage = strtolower($t_lang_from_url[1]);
		
		// 영어, 한국어, 스페인어, 독일어, 프랑스어, 네덜란드어, 스웨덴어, 덴마크어
		$PermittedLoginLanguage = array("en", "kr", "sp", "ge", "fr", "nl", "sw", "dk");
		
		$PossibleLanguage = array("en", "ko", "es", "de", "fr", "nl", "sv", "da");
		
		$DetectedIndex = array_search($RequestLanguage, $PermittedLoginLanguage);
		
		if ( $DetectedIndex === FALSE )
		{
			$DetectedIndex = 0;
		}
		
		$SelectedLanguage = $PossibleLanguage[$DetectedIndex];

		return $SelectedLanguage;
	}
	
	function shell_escape_args($shell_cmd)
	{
		$src = array('$'  , '`', ';');
		$dst = array('\\$', '\\`', '\\;');
		return str_replace($src, $dst, $shell_cmd);
	}
		
	function unix_shell_escape_args($shell_cmd)
	{
		$src = array('$'  , '`');
		$dst = array('\\$', '\\`');
		return str_replace($src, $dst, $shell_cmd);
	}
	
	function get_msie_version()
	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if ( strpos($user_agent, 'MSIE 8.0') !== FALSE )
		{
			return "8.0";
		}
		else if ( strpos($user_agent , 'MSIE 7.0') !== FALSE )
		{
			return "7.0";
		}
		else if ( strpos($user_agent, 'MSIE 6.0') !== FALSE )
		{
			return "6.0";
		}
		
		return "";
	}
	
	function get_samba_veto_files()
	{
		$samba_conf_file = "/etc/samba/smb.conf";
		
		$smb_conf = file($samba_conf_file);
		
		$veto_files = array();
		
		$delete_veto_files = TRUE;
		
		foreach($smb_conf as $one_line)
		{
			if ( preg_match("/^\s*delete veto files\s*=\s*(no|yes|1|0)/i", $one_line, $matches) == 1 )
			{
				if ( ($matches[1] == "no") || ($matches[1] == "0") )
				{					
					$delete_veto_files = FALSE;
					$veto_files = array();
					break;
				}
			}
			if ( preg_match("/^\s*veto files\s*=\s*\/(.+)\//i", $one_line, $matches) == 1 )
			{
				$veto_files = explode("/", $matches[1]);
				
				$veto_files = array_diff($veto_files, array(""));
			}

		}
		
		return $veto_files;
	}
		
	function convert_to_utf8($original_string, $is_in_zip = FALSE)
	{
		$detected_encoding = mb_detect_encoding($original_string, MB_DETECT_ENCODING_ORDER);
		
		if ( $detected_encoding != "UTF-8" &&  $detected_encoding != "ASCII" )
		{
			if ( $detected_encoding == null ) $detected_encoding = "EUC-KR";
			
			// if the file is in zip and detected charset is iso-8859-1, the true encoding may be ibm437
			if ( $is_in_zip && $detected_encoding == "ISO-8859-1" )
			{
				return @iconv("IBM437", "UTF-8", $original_string);
			}
			
			return mb_convert_encoding($original_string, "UTF-8", $detected_encoding);
		}
		
		return $original_string;
	}
	
	function convert_from_utf8_to_client_encoding($original_string, $client_encoding, &$is_utf8, $is_zip = FALSE)
	{
		if ( $client_encoding == "UTF-8" )
		{
			$is_utf8 = TRUE;
			return $original_string;
		}
		
		// in case of zip, try ibm 437 encoding. if fail, try client encoding and the next is utf-8
		if ( $is_zip && ($client_encoding == "ISO-8859-1") )
		{
			$ibm437_converted_string = @iconv("UTF-8", "IBM437", $original_string);
			// if re-converted string is equal with original string
			if ( $original_string == @iconv("IBM437", "UTF-8", $ibm437_converted_string) )
			{
				$is_utf8 = FALSE;
				return $ibm437_converted_string;
			}
		}
		
		$mb_converted_string = mb_convert_encoding($original_string, $client_encoding, "UTF-8");
		$iconv_converted_string2 = @iconv("UTF-8", $client_encoding, $original_string);
		
		$is_utf8 = FALSE;
		if ( $mb_converted_string != $iconv_converted_string2 )
		{
			$mb_converted_string = $original_string;
			$is_utf8 = TRUE;
		}
		
		return $mb_converted_string;
	}
}

?>
