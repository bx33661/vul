<?php
	include ("../session/session_manage.php");
	
  sm_session_check("admin", "../login/login.php");
	
	//require_once ("../multilang/multilang_api.php");
	

	// language information by url start
  		//$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

  		//lang_set_active_language($t_lang_from_url[1]);
	// language information by url end
	
	//lang_set_active_language($_SESSION['lang']);
	
	
	// Get Current Language From URL
$current_url = explode('/',$_SERVER['REQUEST_URI']);
$new_login_language = $current_url[1];

// Define Permitted Language
$permitted_language = array("en","kr","sp","ge","fr","sw","dk","nl","no","fl");

// Check Invalid Value
if(!in_array($new_login_language,$permitted_language)) {
	$new_login_language = "en";
}

// Set Cookie
setcookie("lgnas_language", $new_login_language, time()+31536000, "/");


// Check Torrent enabled => must to do 'window.location.reload();'
$torrent 	= trim(exec('sudo nas-service get_torrent enabled'));

$http_address = $_SERVER['SERVER_ADDR'];   //	http://".$_SERVER['SERVER_ADDR'].":9091"

$odd_type = $_SESSION['odd_type']; 
//if($odd_type == 'BD')
//{
	$odd_img = "\"../images/Top/top_LG_NAS_Storage.gif\""; 
	$odd_bg_img = "\"../images/Top/top_bg_LG_NAS_Storage.gif\"";
//}
//else
//{
//	$odd_img =  "\"../images/Top/top_dvdrewriter.gif\"";
//	$odd_bg_img = "\"../images/Top/top_bg_dvdrewriter.gif\"";
//}
?>

<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" src="../css/embed.js"></script>
<script language='javascript' src="../css/lg.js"></script>
<script language='javascript' src='../css/flash.js'></script>
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/css.css" rel="stylesheet" type="text/css">
<SCRIPT language='javascript' src="../css/selectBox.js"></SCRIPT>
<script language='javascript' src="../js/jslb_ajax.js.php"></script><!-- // Async communication //-->

	<link rel="stylesheet" href="../web_menu/css/web_menu.css" />
    
	
	<script src="../common_js/jquery.min.js" type="text/javascript"></script>
	<script src="../common_js/jquery.cookie.js" type="text/javascript"></script>
	
  <script type="text/javascript">
		jQuery.noConflict();
	</script>


<style type="text/css">
.style1 {color: #6E6F71;font-size: 10px;}
</style>


</head>

<body leftmargin="0" topmargin="0" onLoad="MM_preloadImages('../images/Top/si_01_on.gif','../images/Top/si_02_on.gif','../images/Top/si_03_on.gif','../images/Top/si_04_on.gif','../images/Top/si_05_on.gif','../images/Top/ico_help_on.gif','../images/btn/btn_burn_01_on.gif','../images/btn/btn_burn_02_on.gif','../images/btn/btn_burn_03_on.gif','../images/btn/btn_burn_04_on.gif','../images/btn/btn_burn_06_on.gif','../images/btn/btn_burn_02.gif')">

<script type="text/javascript">
<!--
function bookmark_show() 
{
  content = document.getElementById("bookmark");
  content.style.visibility = "visible";
}
function bookmark_close() 
{
  content = document.getElementById("bookmark");
  content.style.visibility = "hidden";
}
//=======================================================//
// Login : check session
// 30/10/2008
// Park94
//=======================================================//
var log = {
	"out" : function(){
		var _php = "../php/logout.php";
		sendRequest(on_out,"","post",_php,true,true);
		
		function on_out(oj){
			var res=decodeURIComponent(oj.responseText);
			if(res){
				alert("<?php echo lang_get('login_msg_7')?>");
				location.href = '../login/login.php';
			}else{
				alert("Fail to logout!");
			}
		}
	}
}

function myinfo(){
	window.location.href = '../myinfo/myinfo.php';
}

//=======================================================//
// Help : dummy
//=======================================================//
function show_help()
{
	var _win = window.open('../help/system/help_system.html','Help_system','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
	hPopWin = _win;
}


function getServerIp(){

	var _url = document.URL;
	_temp_url = _url.split('/');
	_temp2_url = _temp_url[2].split(':');
	return _temp2_url[0];	
}

function getProtocol(){

	var _url = document.URL;
	_temp_url = _url.split('/');
	return _temp_url[0];	
}

function getPort(){
	var _url = document.URL;
	_temp2_url = _temp_url[2].split(':');
	return _temp2_url[1];	
}

//=======================================================//
// Open window to FTP server
//=======================================================//
function connect_ftp(){
	

	sendRequest(onLoadDT_FTP,'','post','../php/check_ftp_status.php',true,true);

	return;


	
}

function onLoadDT_FTP(oj)
{
	var ftp = decodeURIComponent(oj.responseText);
	var temp_ftp = ftp.split(':');
	
	var ftp_status = temp_ftp[0];
	var ftp_port = temp_ftp[1];

	// Define Prototype
		String.prototype.replaceAll = function( searchStr, replaceStr )
		{
			var temp = this;
			
			while( temp.indexOf( searchStr ) != -1 )
			{
			temp = temp.replace( searchStr, replaceStr );
			}
			
			return temp;
		}

	
	
	//debug(res);
	  if(ftp_status == "off") {
	  	
	  	<?php if ($_SESSION['username'] == "admin"){ ?>
					_msg = "<?php echo lang_get('wizard_msg_9')?>";
					alert(_msg.replaceAll('<BR />','\n')); 
			<?php }else{?>
					_msg = "<?php echo lang_get('wizard_msg_10')?>";
					alert(_msg.replaceAll('<BR />','\n')); 
			<?php }?>
			return;
		}
	
	else if(ftp_status == "on"){
	
	if(confirm("<?php echo lang_get('wizard_msg_12')?>")){
	
		var _url = document.URL;
		//_url = _url.replace('http','ftp');
		_temp_url = _url.split('/');
		_temp_url = _temp_url[2].split(':');
	
		_target_url = "ftp://" + "<?=$_SESSION['username']?>" + "@" + _temp_url[0]+ ":" + ftp_port;
		window.open(_target_url);
	 }
	}
	else{
		alert("Can't Read Ftp Setting");	
	}
}
//=======================================================//
// webdav
//=======================================================//
function show_webdav()
{						

	_target_url = "http://" + "<?=$_SERVER['SERVER_ADDR']?>" + ":80/dav";	
	var _win = window.open(_target_url);
    _win.focus();
	hPopWin = _win;	
}

//=======================================================//
// torrent
//=======================================================//
function show_torrent()
{		
	sendRequest(onLoadDT,'','post',"../php/service_get_torrent.php",true,true);

	function onLoadDT(oj)
	{
		var res = decodeURIComponent(oj.responseText);
		var torrent = res;
		if(torrent == 'on')
		{
			serverIp = getServerIp();
			_target_url = "http://" + serverIp + ":9091";	
			var _win = window.open(_target_url);
		       _win.focus();
			hPopWin = _win;	

		}
		else
		{
			alert("<?php echo lang_get('torrent_msg_3')?>");
		}		

	}	
	return true;
}


//=======================================================//
// smbclient
//=======================================================//
function show_smbclient()
{						
	var form = document.createElement("form");
	form.style.display = "none";
	form.setAttribute("method", "post");
	form.setAttribute("action", "<?php
		$protocol = isset($_SERVER['HTTPS']) ? "https" : "http";
		$protocol = "http"; // nc1 http 고정
		$server_addr = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_ADDR'];
		$server_addr = preg_replace("/:\d+/", "",$server_addr);
		$request_url = $protocol."://".$server_addr.":9090/".$new_login_language."/ajp/index.php";
		echo $request_url; ?>");
	form.setAttribute("target", "_blank");
	var hiddenField = document.createElement("input");
	hiddenField.setAttribute("name", "remote_session");
	hiddenField.setAttribute("value", "<?php echo session_id(); ?>");
	form.appendChild(hiddenField);
	document.body.appendChild(form);
	form.submit();
}
jQuery(document).ready(function(){
	
	// When Click each main menu
	jQuery('.main_menu').click(function(){
		// Expanded Status
		if(jQuery(this).attr('class') == 'main_menu'){
			jQuery(this).removeClass('main_menu').addClass('main_menu_collapse').next().hide();
			serialize();
			
		}
		// Collapsed Status
		else if(jQuery(this).attr('class') == 'main_menu_collapse'){
			jQuery(this).removeClass('main_menu_collapse').addClass('main_menu').next().show();
			serialize();
		}
	});
	
	
	// Web Menu - Full menu button	
	jQuery('span#full_menu').click(function(){
			jQuery('#web_menu div.main_menu_collapse').removeClass('main_menu_collapse').addClass('main_menu').next().show();
			changeArrow();
			serialize();
	});	
	
	// Web Menu - Short menu button	
	jQuery('span#short_menu').click(function(){
			jQuery('#web_menu div.main_menu').removeClass('main_menu').addClass('main_menu_collapse').next().hide();
			changeArrow();
			serialize();
	});	
	
	
	function changeArrow(){
			$arrow_right = jQuery('#web_menu_control .arrow_right');
			$arrow_down = jQuery('#web_menu_control .arrow_down');
			$arrow_right.removeClass().addClass('arrow_down');
			$arrow_down.removeClass().addClass('arrow_right');
	}
	
	// Prepare branches and find all tree items with child lists
			var branches = jQuery('#web_menu div.main_menu');
			var cookieId = "lgnas_web_menu";
	
			deserialize();		
			
	// Cookie Control
				function serialize() {
					
				var data = [];
				branches.each(function(i, e) {
					
					if(jQuery(e).attr('class') == 'main_menu_collapse'){
						data[i] = 0;
					}
					else if(jQuery(e).attr('class') == 'main_menu'){
						data[i] = 1;
					}
					
				});
				jQuery.cookie(cookieId, data.join(""),{expires: 30, path: '/'} );
			}
			
			function deserialize() {
			
				var stored = jQuery.cookie(cookieId);
			
				if ( stored ) {
			
					var data = stored.split("");
					
					branches.each(function(i, e) {
						
						
						
						if(parseInt(data[i]) == 1){
							jQuery(e).removeClass('main_menu_collapse').addClass('main_menu').next().show();
						}
						else if(parseInt(data[i]) == 0){
							jQuery(e).removeClass('main_menu').addClass('main_menu_collapse').next().hide();
						}
					});
				}
			}
			
			
});

//-->
</script>

<STYLE>
A {behavior:url(#default#AnchorClick);}
</STYLE>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><!-- ??\xFC ?????????-->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><!-- ??\xFC GNB ????????-->
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <!-- Top Area ????-->
                    <td width="965" height="85" align="right" background=<?php echo $odd_bg_img ?>><!-- Top MENU ????-->                    
                        <table width="965" height="75" border="0" cellspacing="0" cellpadding="0">
                          <tr>
			<?php if ($_SESSION['username'] == "admin"){ ?>
                            <!--<td width="219"><a href="../system/system.php"><img src="../images/Top/top_01.gif" width="282" height="85" border="0"></a></td>-->
				<td width="219"><a href="../system/system.php"><img src=<?php echo $odd_img ?> width="282" height="85" border="0"></a></td>
                            <td width="246"></td>

                            <!-- <td width="80"><a href="../wizard/systemw_00.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_01','','../images/Top/si_01_on.gif',1)"><img src="../images/Top/si_01.gif" name="menu_01" width="80" height="75" border="0"></a></td> -->
                            <td width="80"></td>                            
                            <!--<td width="80"><a href="../../Album/index.php"><img id="NC1_new_top0203_03" src="../images/nc1/NC1_new_top0203_03.gif" width="71" height="48" alt="" /></a></td>-->
                            <td width="25"></td>
                            
                            <!-- <td width="80"><a href="../wizard/userw_01.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_02','','../images/Top/si_02_on.gif',1)"><img src="../images/Top/si_02.gif" name="menu_02" width="80" height="75" border="0"></a></td>  -->
                            <td width="80"></td>
                            <td width="25"></td>
                            <!-- <td width="80"><a href="../wizard/schedulew_00.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_03','','../images/Top/si_03_on.gif',1)"><img src="../images/Top/si_03.gif" name="menu_03" width="80" height="75" border="0"></a></td>  -->
                            <td width="80"></td>
                            <td width="25"></td>
		        
                            <td width="80">
                            	<A HREF="<?php echo "http://".$_SERVER['SERVER_ADDR'].":80/dav"  ?>" FOLDER="<?php echo "http://".$_SERVER['SERVER_ADDR'].":80/dav"  ?>" target="_blank" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_04','','../images/Top/si_04_on.gif',1)" id="webDavHttp"><img src="../images/Top/si_04.gif" name="menu_04" width="80" height="75" border="0"></a></td>
				<!--  <td width="80"><a href="javascript:void(0);" onclick="show_webdav();" target="_top" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_04','','../images/Top/si_04_on.gif',1)"><img src="../images/Top/si_04.gif" name="menu_04" width="80" height="75" border="0"></a></td> -->
				<td width="25"></td>

                            <td width="80"><a href="javascript:void(0);" onclick="show_torrent();" target="_top" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_05','','../images/Top/si_10_on.gif',1)"><img src="../images/Top/si_10.gif" name="menu_05" width="80" height="75" border="0"></a></td>
				
				<td width="25"></td>
                            <td width="80"><a href="javascript:void(0);" onclick="show_smbclient();" target="_top" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_06','','../images/Top/si_05_on.gif',1)"><img src="../images/Top/si_05.gif" name="menu_06" width="80" height="75" border="0"></a></td>
                            <!-- <td width="80"><a href="../system/main.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_05','','../images/Top/si_05_on.gif',1)"><img src="../images/Top/si_05.gif" name="menu_06" width="80" height="75" border="0"></a></td> -->
			<?php } else{ ?>

			       <td width="219"><a href="../system/system.php"><img src=<?php echo $odd_img ?> width="282" height="85" border="0"></a></td>
                            <td width="246"></td>

			<td width="80"></td>
                            <td width="25"></td>
                            <td width="80"></td>
                            <td width="25"></td>
                            <td width="80"></td>
                            <td width="25"></td>
                            <!-- <td width="80"><a href="../wizard/mobilew_00.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_04','','../images/Top/si_04_on.gif',1)"><img src="../images/Top/si_04.gif" name="menu_04" width="80" height="75" border="0"></a></td> -->
                            <td width="80"></td>
                            <td width="25"></td>
                            <td width="80"><a href="javascript:void(0);" onclick="show_smbclient();" target="_top" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_06','','../images/Top/si_05_on.gif',1)"><img src="../images/Top/si_05.gif" name="menu_06" width="80" height="75" border="0"></a></td>
                            <!-- <td width="80"><a href="javascript:connect_ftp();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('menu_05','','../images/Top/si_05_on.gif',1)"><img src="../images/Top/si_05.gif" name="menu_05" width="80" height="75" border="0"></a></td> -->
                        <? } ?>
                        
                          </tr>
                        </table>
                      <!--  <div id="bookmark">
                          <div>
                            <span id="bookmark_contents">???u ???T??</span>
                          </div>
                        </div>
                      <!-- Top MENU ??--></td>
                    <td align="right" valign="top" background="../images/Top/top_bg_02.gif">&nbsp;</td>
                    <!-- Top Area ??-->
                  </tr>
                  <tr>
               <!-- Top Utility ????-->     <td height="30" background="../images/Top/utility_bg.gif">
               	
               	<table width="965" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                   <td width="10"></td>
                   <td width="450"><!-- ?a????u ??????-->
                       <table border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td>

			<?php if ($_SESSION['username'] == "admin"){ ?>
                           
                               <a href="../system/system.php" ><img src="../images/btn/btn_home.gif" border="0" /></a>

			<? } else{ ?>
                               
                               <a href="../system/system.php" ><img src="../images/btn/btn_home.gif" border="0" /></a>
                               
                        <? } ?>                               
                               
                               <img src="../images/btn/btn_logout.gif" border="0" onclick="log.out();" style="cursor:pointer"/>
                               <img src="../images/btn/btn_info.gif" border="0"  onClick="myinfo();" style="cursor:pointer;"/>
                          </td>

                         </tr>
                       </table>
                     <!-- ?a????u ??? ??--></td>
                   <!-- ???u ??? ????-->
                       <!--<table width="40%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td align="center"><img src="../images/Top/txt_bookmark.gif" width="62" height="13" /></td>
                           <td><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('bookmark_01','','../images/Top/ico_plus_on.gif',1)"><img src="../images/Top/ico_plus.gif" name="bookmark_01" width="17" height="16" border="0" id="bookmark_01" /></a></td>
                           <td><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('bookmark_02','','../images/Top/ico_minus_on.gif',1)"><img src="../images/Top/ico_minus.gif" name="bookmark_02" width="17" height="16" border="0" id="bookmark_02" /></a></td>
                           <td width="10" align="center"><img src="../images/Top/utility_bar.gif" width="2" height="14" /></td>
                           <td align="center"><a href="javascript:void(0)"><img src="../images/Top/ico_book_01.gif" width="18" height="18" border="0" onMouseOver="bookmark_show();" onMouseOut="bookmark_close();"></a></td>
                           <td width="5"></td>
                           <td align="center"><a href="javascript:void(0)"><img src="../images/Top/ico_book_02.gif" width="18" height="18" border="0"></a></td>
                           <td width="5"></td>
                           <td align="center"><a href="javascript:void(0)"><img src="../images/Top/ico_book_03.gif" alt="" width="18" height="18" border="0"></a></td>
                           <td width="5"></td>
                           <td align="center"><a href="javascript:void(0)"><img src="../images/Top/ico_book_04.gif" alt="" width="18" height="15" border="0"></a></td>
                         
                           <td width="5" align="center"></td>
                           <td align="center"><a href="javascript:void(0)"><img src="../images/Top/ico_book_05.gif" alt="" width="18" height="18" border="0"></a></td>
                         </tr>
                       </table>-->
                     <!-- ???u ??? ??-->
                   <!-- ???? ??? ????-->
                    <td align="right"><a href="javascript:void(0);" onclick="show_help();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('help','','../images/Top/ico_help_on.gif',1)"><img src="../images/Top/ico_help.gif" name="help" border="0" id="help" /></a></td> 
                   <!--<td align="right"><a href="javascript:void(0);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('help','','../images/Top/ico_help_on.gif',1)"><img src="../images/Top/ico_help.gif" name="help" width="50" height="17" border="0" id="help" /></a></td>-->
                   <!-- ???? ??? ??-->
                 </tr>
               </table></td><!-- Top Utility ??-->
                    <td background="../images/Top/utility_bg.gif">&nbsp;</td>
                  </tr>
                  
                </table>
              <!-- ??\xFC GNB ???? ??--></td>
          </tr>
<script type="text/javascript">

function changeLink(){
	
	var httpObj = document.getElementById('webDavHttp');
	if(httpObj == null)
		return false;
	
	serverIp = getServerIp();
  serverProtocol = getProtocol();
	serverPort = getPort();
	
	if(serverProtocol == "http:"){
		
		if(serverPort == "8000"){
			targetAddr = "http://"+ serverIp + ":" + serverPort + "/dav";
		}
		else{
			targetAddr = "http://"+ serverIp + ":80/dav";
		}
		httpObj.setAttribute('href',targetAddr);
		httpObj.setAttribute('FOLDER',targetAddr);
		//alert("1 : "+httpObj.getAttribute('href'));
		//alert("2 : "+httpObj.getAttribute('FOLDER'));
	}
	else if(serverProtocol == "https:"){
		targetAddr = "https://"+ serverIp + "/dav";
		httpObj.setAttribute('href',targetAddr);
		httpObj.setAttribute('FOLDER',targetAddr);
		//alert(httpsObj.href);
		//alert(httpsObj.getAttribute('FOLDER'));
	}
}

changeLink();

</script>
