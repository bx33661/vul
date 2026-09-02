<?php
// load error handling script, config and browse class
require_once ('../php/comnso_error_handler.php');
require_once ('../php/comnso_resfview.class.php');

// Create new browse object
$browser = new Browse();

//generate response
   $search   = '';
   $rstate   = 'BLANK';
    $response =	'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response><result>--';
	
   if($_POST['opentag'] == "dir_open"){
 		if(!file_exists("/etc/cms/cmsbackup.db"))
 		{
 			$response .= "BLANK_NOSUB";
 		}else
 		{
			$response .= $browser->BrowseAJAX($_POST['inputValue'], $_POST['fieldID']);
		}
	}else if($_POST['opentag'] == "search_open"){
	   $rstate = 'BLANK_SEARCH';
	   $search = $_POST['search'];
   }else{
      $rstate = "BLANK_CLOSE";
   }
   $response .= '</result><filelist>--';
	$response .= $browser->LoadFileList($_POST['inputValue'], $_POST['fieldID'], $search);
	$response .= '</filelist><fieldid>';
	$response .= $_POST['fieldID'];
	$response .= '</fieldid><state>--'.$rstate.'</state></response>';
	
	// generate the response
	if(ob_get_length()) ob_clean();
	header('Content-Type: text/xml; charset=utf-8');
	echo $response;
?>
