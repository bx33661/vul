<?php
	include "../session/session_manage.php";
	
	if ( sm_session_check_on_popup() == FALSE )
	{
		//include "../php/msg_illegal_access.php";
		//include "../php/msg_illegal_access_pop.php";
		echo '-99';
		die();
	}
	
		require_once ("../multilang/multilang_api.php");
	

	// language information by url start
  		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

  		lang_set_active_language($t_lang_from_url[1]);
	// language information by url end
	
	// 메세지를 주고받는 파일을 제거한다.
	$cms_msgfile="/etc/cms/~sync.msg";
 	if(file_exists($cms_msgfile))
 	{
 		@unlink($cms_msgfile);
 	}
	
	
?>

<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="../css/embed.js" charset='utf-8'></script>
<script type="text/javascript" src="../css/lg.js" charset='utf-8'></script>
<script type="text/javascript" src='../css/flash.js' charset='utf-8'></script>
<script type="text/javascript" src='../js/debug.js' charset='utf-8'></script>
<script type="text/javascript" src='../js/jslb_ajax.js' charset='utf-8'></script>
<script type="text/javascript" src='../js/comnso_jquery-1.2.6.pack.js' charset='utf-8'></script>
<link href="../css/styles.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
.style1 {
	color: #6E6F71;
	font-size: 10px;
}
-->

</style>
</head>
<body>
<table width="422" height="211" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="420" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif" style="color:white;">
    <span class="popup_text"><?php echo lang_get('usb_sync_10')?></span>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>--></td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px"><table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="center">
        <div id="id_txt" style="font-weight:bolder;"><?php echo lang_get('usb_sync_msg_18')?><div>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">-->
        </td>
      </tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" width="370" style="position:absolute;top:150;left:195;"><strong>0 %</strong></div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="center">
		<div id='idButtonBurnNext' style='display:block;'>
			<a href="javascript:void(0)" onclick='cancel_sync();'><img src="../images/btn/btn_cancel.gif"  border="0" /></a>
		</div>
		<div id='idButtonBurnClose' style='display:none;'>
			<a href="javascript:void(0)" onclick='close_pop();'><img src="../images/btn/btn_confirm.gif"  border="0" /></a>
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script>
<!--
//window.onbeforeunload = on_unload_cancel;
//alert(opener.document.getElementById('id_task_number').value);
usb_sync(opener.document.getElementById('id_task_number').value);

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
	read_image_sync_progress();
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
	
	if(prog == 100){
		clearInterval(timerA);
	}
}

//=======================================================//
// Get progress information : image backup (mopilt)
//=======================================================//
function read_image_sync_progress()
{
	var cmd = "&mode=sync";
	sendRequest(on_read_audio_rip_progress,cmd,"post","../php/usb_get_image_prog.php",false,true);
}

function on_read_audio_rip_progress(oj)
{
	var res=decodeURIComponent(oj.responseText);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	if(ret[0] == "ing"){
		var val = parseInt(ret[1],10);
		if(val<0){
			clearInterval(timerA);
			
			// Error message //
			
			switch(val){
				
				case -1:
					var _msg = "<?php echo lang_get('usb_sync_msg_22')?>";
					alert(_msg);
					break;
				case -2:
					var _msg = "<?php echo lang_get('usb_sync_msg_23')?>";
					alert(_msg);
					break;
				case -3:
					//var _msg = "Cancel";
					//alert('Error : '+val);
					break;
				case -4:
					var _msg = "<?php echo lang_get('usb_sync_msg_24')?>";
					alert(_msg);
					break;
				case -5:
					var _msg = "<?php echo lang_get('usb_sync_msg_21')?>";
					alert(_msg);
					break;
				default:
					alert('Error : Unknown');
					break;
			}
			document.getElementById('idButtonBurnNext').style.display = 'none';
			document.getElementById('idButtonBurnClose').style.display = 'block';
		}
		/*
		if(val==99)
		{
			val = 100;
		}
		*/
		// 이전 프로그바 값보다 클경우..
		if(val>oldprog){
			oldprog=val;
			resize_bar(val);
		}		
	}
}

//=======================================================//
// usb sync
//=======================================================//
function usb_sync(tasknum)
{
	
	var cmd="&act=sync&task_number="+tasknum;
	var php = '../php/usb_sync.php';
	sendRequest(on_usb_sync,cmd,"post",php,true,true);
}

function on_usb_sync(oj)
{
	var msg="";
	var res=decodeURIComponent(oj.responseText);
	debug("usb_sync:"+res);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	debug(ret[0]);
	switch(ret[0])
	{
		case 'ok':
			msg = ret[1];
			//debug("message:"+msg);
			if(msg.search("cancel")>-1){
				debug("cancel");
				var _tmp = "<input type='image' onclick='close_pop()' src='../images/btn/btn_confirm.gif'  border='0' />";
				document.getElementById('idButtonBurnNext').innerHTML = _tmp;
				document.getElementById('id_txt').innerHTML = "<?php echo lang_get('usb_sync_msg_19')?>";
				alert("<?php echo lang_get('usb_sync_msg_19')?>");
				
				document.getElementById('idButtonBurnNext').style.display = 'none';
				document.getElementById('idButtonBurnClose').style.display = 'block';
				//document.getElementById('id_txt').style.fontWeight = "bolder";
				return;
			}else if(msg.search("complete")>-1){
				var _tmp = "<input type='image' onclick='close_pop()' src='../images/btn/btn_confirm.gif'  border='0' />";
				document.getElementById('idButtonBurnNext').innerHTML = _tmp;
				document.getElementById('id_txt').innerHTML = "<?php echo lang_get('usb_sync_msg_20')?>";
				document.getElementById('idButtonBurnNext').style.display = 'none';
				document.getElementById('idButtonBurnClose').style.display = 'block';
				//document.getElementById('id_txt').style.fontWeight = "bolder";
				return;
			}
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
		case "ng":
			break;
		default:
			// Timeout or cancel
			debug('D : No return (Timeout/Cancel)');
			break;
	}
	
	// 이미 취소된경우는 메세지를 뛰우지 않는다.
	if(isclose == 1){	
		// 메세지가 있을 경우 메세지를 출력하고 창을 닫는다.
		if(msg){
			alert(msg);
		}
		
		// 프로그래스 체크 타이머를 멈추게 한다.
		clearTimeout(timerA);		
		
		isclose = 0;
	}
	
	//while(iscancel == 1){
	//	pause(10);
	//}
	//opener.document.getElementById('id_btn_sync').disabled = false;
	//this.close();
	var _tmp = "<input type='image' onclick='close_pop()' src='../images/btn/btn_confirm.gif' height='22px' width='71px' border='0' />";
	document.getElementById('idButtonBurnNext').innerHTML = _tmp;
	document.getElementById('id_txt').innerHTML = "<?php echo lang_get('usb_sync_msg_20')?>";
	
	//document.getElementById('id_txt').style.fontWeight = "bolder";
	//close_pop();
}

/*function on_unload_cancel()
{
	if(isclose != 1){
		return;
	}
	cancel_sync();
	pause(1000);
	//alert("동기화 작업을 취소중 입니다.\n확인을 누르신 후 잠시 기다려 주십시오.");	
	alert("Now cancelling synchronizing\nPush \'Confirm\' button and wait a moment");
}*/

//=======================================================//
// cancel sync
//=======================================================//
function cancel_sync()
{
	if(isclose != 1){
		return;
	}
	isclose = 0;
		
	// 프로그래스 체크 타이머를 멈추게 한다.
	//clearTimeout(timerA);

	iscancel = 1;
	var cmd="&act=cancel";
	var php = '../php/usb_sync.php';
	sendRequest(on_cancel_sync,cmd,"post",php,true,true);
	
	document.getElementById('idButtonBurnNext').style.display = 'none';
}

function on_cancel_sync(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	if(res.search(/ok/)>=0){
		debug("success cancel");
		iscancel = 0;
		clearTimeout(timerA);
	}else{
		alert("Fail to cancel\nTry again");
		iscancel = 1;
		
		document.getElementById('idButtonBurnNext').style.display = 'block';
		document.getElementById('idButtonBurnClose').style.display = 'none';
	}
	
}
//=======================================================//
// Close window
//=======================================================//
function close_pop(){
	//isclose = 0;
	if(opener.document.getElementById('id_btn_sync'))	opener.document.getElementById('id_btn_sync').disabled = false;
	this.close();
}
//-->
</script>
