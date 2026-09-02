<?php
//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
//require_once "../php/msg_illegal_access.php";
echo '-99';
die();
}
	
	$new_login_language = $_POST['language'];
	$new_client_language = $_POST['client_language'];
	
	// get login window language
	$ml_index_html_file = "../../index.html";
	$rfp = fopen($ml_index_html_file, 'r');

	$index_html_contents = fread($rfp, 256);
	
	fclose($rfp);
	exec("sudo chmod 777 $ml_index_html_file");
	$wfp = fopen($ml_index_html_file, 'w');
	$lang_pattern = array("URL=./en", "URL=./kr", "URL=./ge", "URL=./sp", "URL=./fr","URL=./sw","URL=./dk","URL=./nl","URL=./no","URL=./fl");
	$new_lang_pattern = "URL=./".$new_login_language;
	$new_index_html_contents = str_replace($lang_pattern, $new_lang_pattern, $index_html_contents);
	fwrite($wfp, $new_index_html_contents);
	fclose($wfp);

	exec("sudo chmod 644 $ml_index_html_file");
	// NS1
	/*	
	$client_language_file = "/etc/sss_script/share/client_codepage.conf";
	
	exec("sudo chmod 777 /etc/sss_script/share/client_codepage.conf");
	$wfp = fopen($client_language_file, 'w');
	$new_client_language_contents = "codepage=".$new_client_language;
	fwrite($wfp,$new_client_language_contents);
	fclose($wfp);
	exec("sudo chmod 744 /etc/sss_script/share/client_codepage.conf");
	exec("sudo /etc/sss_script/share/query_share.sh config");
	*/
	
	// NC1
	exec("sudo nas-system language $new_client_language");
	
	//$_SESSION['lang'] = $new_login_language;
	echo $new_login_language;
	//echo $_SESSION['lang'];
?>
