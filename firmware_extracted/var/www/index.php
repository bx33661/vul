<?php
$permitted_language = array("en","kr","sp","ge","fr","sw","nl","dk","no","fl","it");

$new_login_language = $_COOKIE["lgnas_language"];

if (isset($_COOKIE["lgnas_language"])) { 
	$new_login_language = $_COOKIE["lgnas_language"];
}

function is_mobile_agent(){
	if( preg_match('/(iPhone|iPad|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone|IEMobile|POLARIS)/i', $_SERVER['HTTP_USER_AGENT']) ){
		return true;
	}
	else{
		return false;
	}
}

function get_user_browser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $ub = ''; 
    if(preg_match('/MSIE/i',$u_agent)){ 
        $ub = "ie"; 
    } 
    else if(preg_match('/Firefox/i',$u_agent)){    
        $ub = "firefox";
    } 
    else if(preg_match('/Safari/i',$u_agent)){ 
        $ub = "safari";
    }
    else if(preg_match('/Chrome/i',$u_agent)){ 
        $ub = "chrome";
    }
    else if(preg_match('/Flock/i',$u_agent)){ 
        $ub = "flock";
    } 
    else if(preg_match('/Opera/i',$u_agent)){ 
        $ub = "opera";
    }
	$ret_val = is_mobile_agent();
	if( $ret_val == 'true' )
		$ub = "mobile";
    
	return $ub;
} 

$res = get_user_browser();
if($res == "mobile")
{
	$login_file = "login_mobile.php";
}
else
{
	$login_file = "login.php";
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

<META HTTP-EQUIV="Refresh" CONTENT="0; URL=./<?php echo $new_login_language ?>/login/<?php echo $login_file ?>">
