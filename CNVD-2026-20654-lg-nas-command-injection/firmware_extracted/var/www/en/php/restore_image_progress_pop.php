<?php
	include "../session/session_manage.php";
	
	if ( sm_session_check_on_popup() == FALSE )
	{
		//include "../php/msg_illegal_access.php";
		include "../php/msg_illegal_access_pop.php";
		die();
	}
	
		require_once ("../multilang/multilang_api.php");
	

	// language information by url start
  		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

  		lang_set_active_language($t_lang_from_url[1]);
	// language information by url end
	
?>

<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="javascript" src="../css/embed.js"></script>
<script language="javascript" src="../css/lg.js"></script>
<script language='javascript' src='../css/flash.js'></script>
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>
<script language='javascript' src='../js/jslb_ajax.js' charset='utf-8'></script>
<script language='javascript' src='../js/comnso_jquery-1.2.6.pack.js' charset='utf-8'></script>
<link href="../css/styles.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
.style1 {
	color: #6E6F71;
	font-size: 10px;
}
-->
	body { margin: 5px 5px 5px 5px; }
</style>
</head>
<body>
<table width="422" height="250" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="420" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif">
    <span class="popup_text"><?php echo lang_get('schedule_restore_2')?></sapn>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>--></td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px">
      <table width="370" border="0" cellspacing="0" cellpadding="0">
      <!--
      <tr>
        <td  colspan="2" height="40" valign="center">
        <font size="3"><strong>Restore...</strong></font><br>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">
        </td>
      </tr>
      -->
      <tr>
      	<td height="50" width="50"><div id='msgimg'><img src='../images/comnso/cms_icon_info.gif'></div></td>
      	<td><span id='phpmsg'><?php echo lang_get('schedule_msg_31')?></span></td>
      </tr>
      <tr>
        <td colspan="2"><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:visible;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" width="370" style="position:absolute;top:135;left:195;"><strong>0 %</strong></div>
        </td>
      </tr>
      </table>
      <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="center">
		<div id='idButtonBurnNext' style='display:block;'>
			<a href="javascript:void(0)" onclick='cancel_restore();'><img src="../images/btn/btn_cancel.gif"  height="22" border="0" /></a>
		</div>
		<div id='idConfirm' style='display:none;'>
			<a href="javascript:void(0)" onclick='close_restore();'><img src="../images/btn/btn_confirm.gif"  height="22" border="0" /></a>
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script>
<!--
window.onbeforeunload = on_unload_cancel;
do_restore();

var iscancel = 0;
var isclose = 1;
var oldprog = 0;
var cnt_time = 0;
var w = 0;
var prog_max = 370;
var incre = prog_max / 100;

var timerA = setInterval('show_time()',1000);

function pause( iMilliseconds )
{
    var sDialogScript = 'window.setTimeout( function () { window.close(); }, ' + iMilliseconds + ');';
    window.showModalDialog('javascript:document.writeln ("<script>' + sDialogScript + '<' + '/script>")');
}

//=======================================================//
// Timer function
//=======================================================//
function show_time()
{
	cnt_time++;
	read_image_restore_progress();
}

//=======================================================//
// Progress bar control function
//=======================================================//
function resize_bar(prog)
{
	w = incre * prog;
	if(w>prog_max)
	{
		w = prog_max;
	}
	document.getElementById('prog').width = w;

	document.getElementById('progValue').style.left = 195;
	document.getElementById('progValue').innerHTML = "<strong>"+prog+" %</strong>";
	
	if(w>0) document.getElementById('idProg_bar').style.visibility = "visible";
}

//=======================================================//
// Get progress information : image backup (mopilt)
//=======================================================//
function read_image_restore_progress()
{
	var cmd = "&mode=sync";
	sendRequest(on_read_audio_rip_progress,cmd,"post","../php/restore_get_image_prog.php",true,true);
}

function on_read_audio_rip_progress(oj)
{
	var res=decodeURIComponent(oj.responseText);
	var tmp = res.split("\n");
	var _flag = 0;
	for(var i=0; i<tmp.length; i++){
		var ret = tmp[i].split(":");
		if(ret[0] == "runmsg=ing"){
			if(_flag>0) continue;
			var val = parseInt(ret[1]);
			/*if(val==99)
			{
				val = 100;
			}*/
			
			// L| ȁ·α׹נ°ªº¸´נŬ°瀬..
			if(val>oldprog){
				oldprog=val;
				resize_bar(val);
			}		
		}else if(ret[0] == "phpmsg=qus"){
			document.getElementById("phpmsg").innerHTML = "<?php echo lang_get('schedule_restore_5')?>" + ret[1] +"<?php echo lang_get('schedule_restore_5_1')?>";
			document.getElementById("msgimg").innerHTML = "<img src='../images/comnso/cms_icon_qus.gif'>";
			document.getElementById("progValue").style.top = '140px';
			//document.getElementById('idButtonBurnNext').style.display = 'none';
			//document.getElementById('idConfirm').style.display = 'block';
			//clearTimeout(timerA);
		}else if(ret[0] == "phpmsg=msg"){
			
			if(ret[1].match("Restoring...")){
				document.getElementById("phpmsg").innerHTML = ret[1].replace("Restoring...","<?php echo lang_get('schedule_restore_3')?>");	
				//_flag ++;
			}
			else {
				document.getElementById("phpmsg").innerHTML = "<?php echo lang_get('schedule_restore_4')?>";
				document.getElementById('idButtonBurnNext').style.display = 'none';
				document.getElementById('idConfirm').style.display = 'block';
				clearTimeout(timerA);
				//resize_bar(100);
			}
			document.getElementById("msgimg").innerHTML = "<img src='../images/comnso/cms_icon_info.gif'>";			
		}
	}
}

//=======================================================//
// do restore
//=======================================================//
function do_restore()
{
	var cmd="&chkval=cms";
	var php = '../php/comnso_app_restore.php';
	sendRequest(on_do_restore,cmd,"post",php,true,true);
}
function close_restore(){
	isclose = 0;
	this.close();
}
function on_do_restore(oj)
{
	var msg="";
	var res=decodeURIComponent(oj.responseText);
	if(res.search('{')>-1){
		/* In case of tray is not closed */
		eval('var _ret = '+res);
		if(_ret.result == '-4'){
			/* Restore is working */
			//var _msg = _ret.message;
			var _msg = "<?php echo lang_get('storing_msg_2')?>";	// Multi-language conversion
			//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
		}else if(_ret.result == '-5'){
			/* Tray is not closed */
			//var _msg = _ret.message;
			var _msg = "<?php echo lang_get('schedule_msg_17')?>";	// Multi-language conversion
			//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
		}else if(_ret.result == '-6'){
			/* No disc in drive */
			//var _msg = _ret.message;
			var _msg = "<?php echo lang_get('schedule_msg_18')?>";	// Multi-language conversion
			//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
		}
			alert(_msg);	// Currently message is english
			document.getElementById('idButtonBurnNext').style.display = 'none';
			document.getElementById('idConfirm').style.display = 'block';
			clearTimeout(timerA);
			return;
		/************************/
	}
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'ok':
			msg = ret[1];
			if(msg == 'restore complete') msg = "<?php echo lang_get('schedule_restore_4')?>";
			break;
		case 'err':
			msg = ret[1];
			break;
		case 'WARNING':
			msg = res;
			break;
		case 'ERROR':
			msg = res;
			break;
		case 'EXCEPTION':
			// complete or canceled
			debug('D : Exception');
			break;
		default:
			// Timeout or cancel
			debug('D : No return (Timeout/Cancel)');
			break;
	}
	
	// L¹ʠī¼ҵȰ瀬´ ¸޼¼¶¸¦ ¶ٿ킶 ¾ʴ´׮
	if(isclose == 1){
		// ¸޼¼¶°¡ V; °瀬 ¸޼¼¶¸¦ Ģ·Çϰ롃¢; ´ݴ´׮
		if(msg){
			//alert(msg);
		}
		
		// ȁ·α׷¡½º üũ ŸL¸Ӹ¦ ¸ك߰Ҡȑ´׮
		//clearTimeout(timerA);
		isclose = 0;
	}
	
	while(iscancel == 1){
		pause(10);
	}
	document.getElementById('idButtonBurnNext').style.display = 'none';
	document.getElementById('idConfirm').style.display = 'block';
	//this.close();
}
//-->

function on_unload_cancel()
{
	if(isclose != 1){
		return;
	}
	
	cancel_restore();
	pause(1000);
	_msg = "<?php echo lang_get('schedule_msg_45')?>"; 
	alert(_msg.replace('<BR />','\n'));	
}

//=======================================================//
// cancel restore
//=======================================================//
function cancel_restore()
{
	if(isclose != 1){
		return;
	}
	isclose = 0;
	
	// ȁ·α׷¡½º üũ ŸL¸Ӹ¦ ¸ك߰Ҡȑ´׮
	clearTimeout(timerA);

	iscancel = 1;
	var cmd="&k0=phpmsg&v0=&k1=appmsg&v1=cancel";
	var php = '../php/comnso_res_keywrite.php';
	sendRequest(on_cancel_restore,cmd,"post",php,true,true);	
}

function on_cancel_restore(oj)
{
	var res=decodeURIComponent(oj.responseText);
	document.getElementById('idButtonBurnNext').style.display = 'none';
	document.getElementById('idConfirm').style.display = 'block';
	iscancel = 0;
}
//-->
</script>
