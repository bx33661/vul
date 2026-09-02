<?php
/**
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
 * Description : main script called at initialisation.
 */
require_once("server/classes/class.Utils.php");
require_once("server/classes/class.HTMLWriter.php");
require_once("server/classes/class.Repository.php");
require_once("server/classes/class.ConfService.php");
require_once("server/classes/class.AuthService.php");
require_once("server/classes/class.AJXP_Logger.php");
require_once("server/classes/class.AbstractDriver.php");
ConfService::init("server/conf/conf.php");
$confStorageDriver = ConfService::getConfStorageImpl();
include_once($confStorageDriver->getUserClassFileName());// include "AJXP_User"
// park94 09/16/09
// desc.:share ns2 session
//include "../session/session_manage.php";
/*if ( sm_session_check_on_popup() == FALSE ){
	include "../php/msg_illegal_access_pop.php";
	exit();
}*/
//desc.:make new session dir

include(INSTALL_PATH."/sess_info.php");

function invalid_access_error_msg_return()
{
	$mess = ConfService::getMessages();
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv='cache-control' content='no-cache' />";
	echo "<meta http-equiv='pragma' content='no-cache' />";
	echo "<script language=\"javascript\">\n";
	echo "alert(\"".$mess["509"]."\");\n";
	echo "self.opener = self;\n";
	echo "self.close();\n";
	echo "</script>\n";
	echo "</head>\n";
	echo "</html>\n";
	exit(1);
}
// Remove session info file if its expired time is over		
		//$min = (trim(exec("sudo cat /etc/php/php.ini | grep session.gc_maxlifetime | grep -v ';' |cut -d '=' -f 2"))/60);
		//$min = 20;
		//exec("sudo find $session_save_dir -type f -mmin +$min -print0 | xargs -r -0 rm");
		$sec = 1200;
		exec("sudo /usr/lib/nas/find_time /var/lock/session/ $sec");
		
// KHJ20090924 invalid access check
if ( !isset($_POST["remote_session"]) )
{
	invalid_access_error_msg_return();
}
else
{
	// remote session file exist check
	$remote_session_file_name = $session_save_dir."/sess_".$_POST["remote_session"];
	
	if ( !file_exists($remote_session_file_name) )
	{
		invalid_access_error_msg_return();
	}
}
session_write_close();
session_save_path($session_save_dir_ajxp);
register_shutdown_function("session_save_path", "$session_save_dir");
session_start();

$outputArray = array();
$testedParams = array();
$passed = true;

$ajp_session_file_name = $session_save_dir."/ajxp/sess_".$_POST["remote_session"];
$_SESSION["SESS_FILE"] = $ajp_session_file_name;
//error_log("1)index.php - SESS_FILE:".$_SESSION["SESS_FILE"]."\n",3,"/tmp/ajaxplorer.log");
// HKJ20090922 SKIP test
/*
if(!is_file(TESTS_RESULT_FILE)){
	$passed = Utils::runTests($outputArray, $testedParams);
	if(!$passed && !isset($_GET["ignore_tests"])){
		die(Utils::testResultsToTable($outputArray, $testedParams));
	}else{
		Utils::testResultsToFile($outputArray, $testedParams);
	}
}
*/

/*
if(AUTH_MODE == "wordpress"){
	require_once("../../../wp-config.php");
	require_once("../../../wp-includes/capabilities.php");
	require_once("../../../wp-includes/user.php");
	require_once("../../../wp-includes/plugin.php");
	require_once("../../../wp-includes/pluggable.php");
}
*/
$USERS_ENABLED = "false";
$LOGGED_USER = "false";
$LOGGED_USER = "true";
$BEGIN_MESSAGE = "";
if(AuthService::usersEnabled())
{
	AuthService::preLogUser((isSet($_POST["remote_session"])?$_POST["remote_session"]:""));//Bypass log-in to preLogUser without password

	// KHJ20090922 /server/users folder is not required in LG NAS	
	/*
	if(!is_readable(USERS_DIR)) $BEGIN_MESSAGE = "Warning, the users directory is not readable!";
	else if(!is_writeable(USERS_DIR)) $BEGIN_MESSAGE = "Warning, the users directory is not writeable!";
	*/

	if(AuthService::countAdminUsers() == 0){
		//park94 09/18/09
		//make firstly admin account
		/*
		$authDriver = ConfService::getAuthDriverImpl();
		$adminPass = ADMIN_PASSWORD;
		if($authDriver->getOption("TRANSMIT_CLEAR_PASS") !== true){
			$adminPass = md5(ADMIN_PASSWORD);
		}
		 AuthService::createUser("admin", $adminPass, true);
		 if(ADMIN_PASSWORD == INITIAL_ADMIN_PASSWORD)
		 {
			 $BEGIN_MESSAGE .= "Warning! User 'admin' was created with the initial common password 'admin'. \\nPlease log in as admin and change the password now!";
		 }
		*/
	}else if(AuthService::countAdminUsers() == -1){
		// Here we may come from a previous version! Check the "admin" user and set its right as admin.
		$confStorage = ConfService::getConfStorageImpl();
		$adminUser = $confStorage->createUserObject("admin"); 
		$adminUser->setAdmin(true);
		$adminUser->save();
		$BEGIN_MESSAGE .= "You may come from a previous version. Now any user can have the administration rights, \\n your 'admin' user was set with the admin rights. Please check that this suits your security configuration.";
	}

	$USERS_ENABLED = "true";	
	if(AuthService::getLoggedUser() != null || AuthService::logUser(null, null) == 1)
	{
		$LOGGED_USER = "true";
		$loggedUser = AuthService::getLoggedUser();
		if(!$loggedUser->canRead(ConfService::getCurrentRootDirIndex()) 
				&& AuthService::getDefaultRootId() != ConfService::getCurrentRootDirIndex())
		{
			ConfService::switchRootDir(AuthService::getDefaultRootId());
		}
	}
	$ROOT_DIR_NAME = "null";
	$ROOT_DIR_ID = "null";
	$ROOT_DIR_XML = "";
}
else 
{
	$ROOT_DIR_NAME = ConfService::getCurrentRootDirDisplay();
	$ROOT_DIR_ID = ConfService::getCurrentRootDirIndex();
	$ROOT_DIR_XML = HTMLWriter::repositoryDataAsJS();
}
$EXT_REP = "/";
if(isSet($_GET["folder"])) $EXT_REP = urldecode($_GET["folder"]);
$CRT_USER = "shared_bookmarks";
if(isSet($_GET["user"])) $CRT_USER = $_GET["user"];

$ZIP_ENABLED = (ConfService::zipEnabled()?"true":"false");

$loggedUser = AuthService::getLoggedUser();
$DEFAULT_DISPLAY = "list";
if($loggedUser != null && $loggedUser->getId() != "guest")
{
	if($loggedUser->getPref("lang") != "") ConfService::setLanguage($loggedUser->getPref("lang"));
	if($loggedUser->getPref("display") != "") $DEFAULT_DISPLAY = $loggedUser->getPref("display");
}
else
{	
	if(isSet($_COOKIE["AJXP_lang"])) ConfService::setLanguage($_COOKIE["AJXP_lang"]);
	if(isSet($_COOKIE["AJXP_display"]) 
	&& ($_COOKIE["AJXP_display"]=="list" || $_COOKIE["AJXP_display"]=="thumb")) $DEFAULT_DISPLAY = $_COOKIE["AJXP_display"];
}

if(isset($_GET["compile"]))
{
	require_once(SERVER_RESOURCES_FOLDER."/class.AJXP_JSPacker.php");
	AJXP_JSPacker::pack();
}

if(isset($_GET["update_i18n"]))
{
	Utils::updateI18nFiles();
}

$JS_DEBUG = false;
//$JS_DEBUG = true;// do not use packed JS & CSS
$mess = ConfService::getMessages();
include_once(CLIENT_RESOURCES_FOLDER."/html/gui.html");
HTMLWriter::writeI18nMessagesClass($mess);
HTMLWriter::closeBodyAndPage();
?>
