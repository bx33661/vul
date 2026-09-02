<?php

/************************[ HISTORY ]***************************
Login Page UI ver 0.01
---------------------------------------------------------------

ver 0.01 [2009.10.21] : Apply combobox for language selection

***************************************************************/

// Get Current Language From URL
$current_url = explode('/',$_SERVER['REQUEST_URI']);
$new_login_language = $current_url[1];

// Define Permitted Language
$permitted_language = array("en","kr","sp","ge","fr","sw","nl","dk","no","fl");

// Check Invalid Value
if(!in_array($new_login_language,$permitted_language)) {
	$new_login_language = "en";
}

// Set Cookie
setcookie("lgnas_language", $new_login_language, time()+31536000, "/");
?>

<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<!-------------------------------------------------------------------------------------------------------------
	  * Javascript Section
	-------------------------------------------------------------------------------------------------------------->

	<!-- Debugging Setting : Default -->
	<script language='javascript' src='../js/debug.js' charset='utf-8' type='text/javascript'></script>		
	
	<!-- Ajax library -->
	<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8' type='text/javascript'></script>	
	
	<!-- Common JS -->
	<script language="javascript1.2" src="../js/common.js.php" charset="utf-8" type='text/javascript'></script>
	
	<!-- jquery Library -->
	<script src="../common_js/jquery.min.js" type="text/javascript"></script>
	
	<!-- Login Page JS -->
	<script language="javascript1.2" src="../js/login.js.php" charset="utf-8" type='text/javascript'></script>

	<!-------------------------------------------------------------------------------------------------------------
	  * CSS Section
	-------------------------------------------------------------------------------------------------------------->

	<!-- Include CSS -->
	<link href="../css/styles.css" rel="stylesheet" type="text/css">
	<link href="../css/css.css" rel="stylesheet" type="text/css">

	<script type="text/javascript">    
   jQuery(document).ready(function(){
   		
   		// Set combobox from url
   		var _url = document.URL;
			_url_array = _url.split('/');
			_url_language = _url_array[3];
   		
   		jQuery("#language_select > option[value="+_url_language+"]").attr('selected','true');
   	
   		// Bind Onchange event to Language select DOM
   		jQuery("#language_select").change(languageChange);   	
   		
   		// Set focut to ID input field
   		jQuery('#idInId').focus();
   		
   });
   
   function languageChange(){
   	var $selected = jQuery("#language_select option:selected");
   	
   	var moveUrl = "../../"+$selected.val()+"/login/login.php";
   	
   	location.href = moveUrl;
   }
    </script>
    
    
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="all_table">
  <tr>
    <td width="100%" height="950" align="center" valign="top" background="../images/login/bg.gif" style="padding:230 0 0 0px">
    <table width="570" border="0" cellspacing="0" cellpadding="0">
    			<tr>
    				<td ><img src="../images/login/login_logo.gif" /></td>
        		<td align="right">
        			
        			
        			<!-- Language select -->		
        					<select id="language_select">
        							<option value="ge">Deutsch</option>
											<option value="en">English</option>
											<option value="sp">Español</option>
											<option value="fr">Français</option>
											<option value="kr">한국어 </option>
											<option value="sw">Swedish</option>
											<option value="nl">Dutch</option>
											<option value="dk">Danish</option>
											<option value="no">Norwegian</option>
											<option value="fl">Finnish</option>
        						</select>
        						
    				</td>
    			</tr>
    	</table>

    		
    	<table width="570" height="367px" border="0" cellspacing="0" cellpadding="0" style="background:url('../images/login/form_bg_LG_NAS_Storage.gif'); background-repeat:no-repeat;">
    	
    		<tr height="275px"><td>
    					<table width="340px" height="150px" border="0" style="margin:20px 0 0 145px;">
    						<tr height="80px">
    								<td colspan="2"><img src="../images/login/txt_login.gif"></td>
    								<td rowspan="3" width="80px" style="padding-top:65px"><img src="../images/login/btn_login.gif"  onClick="check_info1();" style="cursor:pointer;"/></td>
    						</tr>
    						<tr height="30px">
    								<td><img src="../images/login/txt_id.gif"></td>
    								<td width="140px"><input onblur="FormCheck('idInId');" name="textfield" type="text" class="inputtext_04" id="idInId" value="" size="18" maxlength="12"></td>
    									
    						</tr>	
    						<tr height="40px">
    								<td><img src="../images/login/txt_pw.gif"></td>
    								<td><input onfocus="this.value='';" onkeydown="return processOnEnter(event);" name="textfield2" type="password" class="inputtext_04" id="idInPw" value="" size="18" maxlength="20"></td>
    						</tr>
    					</table>

    			</td>
    		</tr>
    		<tr><td><img src="../images/login/form_shadow.gif" width="570" height="92" /></td></tr>
   		 </table>
 
    </td>
  </tr>
</table>

</body>
</html>