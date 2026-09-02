<?php
$permitted_language = array("en","kr","sp","ge","fr");

$new_login_language = $_COOKIE["lgnas_language"];

if (isset($_COOKIE["lgnas_language"])) { 
	$new_login_language = $_COOKIE["lgnas_language"];
}

//$abc =  $_SERVER['HTTP_USER_AGENT'];
//shell_exec("sudo echo 'get_user_browser: $res $abc' >> /home/phplog.txt");

if(!in_array($new_login_language,$permitted_language)) {
	$new_login_language = trim(exec("sudo cat /var/www/index.html | grep -i 'URL' | cut -d '/' -f 2")); 
				
	if(!in_array($new_login_language,$permitted_language)){
		$new_login_language = "en";
	}
}

?>
<META HTTP-EQUIV="Cache-Control" CONTENT="No-Cache">
<META HTTP-EQUIV="Pragma" CONTENT="No-Cache">

<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./<?php echo $new_login_language ?>/system/system.php">
 
