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
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>
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
    <!--<img src="../images/popup/txt_popup_image_backup.gif" width="272" height="35" border="0">-->
  	<span class="popup_text"><?php echo lang_get('storing_backup_2')?></span>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>--></td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px"><table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="center">
        <span id="popup_msg" style="font-weight:bolder;"><?php echo lang_get('storing_msg_14')?></span>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">-->
        </td>
      </tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" style="position:absolute;width:370px;top:150;left:25;font-weight:bolder;">0 %</div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="center" style="padding:0 20 0 0px">
		<div id='idButtonBurnNext' style='visibility:hidden;'>
			<a href="javascript:void(0)" onclick='cancel_burn();'><img src="../images/btn/btn_cancel.gif"  height="22" border="0" /></a>
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script>
<!--
backup_image();



var cnt_time = 0;
var w = 0;
var prog_max = 370;
var incre = prog_max / 100;
var prog_per = 0;
var timerA = '';
setTimeout('start_show_time()',2000);
function start_show_time(){
	resize_bar(1);
	
	if(is_complete){
		return;
	}
	timerA = setInterval('show_time()',1000);
	document.getElementById('idButtonBurnNext').style.visibility = 'visible';
}
var timerB = setInterval('check_odd_stat()',1000);
var cTimeOdd = 0;
var gOdd_chk_time_mrg = 2;
var fCancel = false;
var fOddStat = false;
//=======================================================//
// Timer function
//=======================================================//
function show_time()
{
	cnt_time++;
	read_image_backup_progress();
	/*if(fOddStat == true)
	{
		cTimeOdd++;
		if(cTimeOdd > gOdd_chk_time_mrg)
		{
			// Do ODD busy check
			var php = '../php/bd_pop_odd_busy_check.php';
			sendRequest(on_show_time,'','post',php,true,true);
		}
	}*/
}
function on_show_time(oj)
{
	if(fCancel==true) return false;
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	res = res.split("\n");
	var tmp = res[0].split(":");
	switch(tmp[0])
	{
		case 'OK': // ODD IDLE
			clearInterval(timerA);
			clearInterval(timerB);
			break;
		case 'NG': // ODD BUSY
			//alert(tmp[1]);
			break;
		case 'ERROR':
			//var Msg = "BD BUSY CHECK : "+tmp[1];
			//alert(tmp[1]);
			break;
		default:
			break;
	}
}
var is_complete = false;

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
	if(w>document.getElementById('prog').width){
		document.getElementById('prog').width = w;
		if(fCancel == false)
		{
			//document.getElementById('progValue').style.left = 195;
			document.getElementById('progValue').innerHTML = prog+" %";
		}
		if(w>0) document.getElementById('idProg_bar').style.visibility = "visible";
		if(prog == 100){
			end_task("<?php echo lang_get('storing_msg_16')?>");	// 'Complete' message
		}
	}
	
}
function on_resize_bar(oj){
	debug(oj.responseText);
}
//=======================================================//
// Cancel function
//=======================================================//
function cancel_burn()
{
	fCancel = true;
	//document.getElementById('progValue').style.left = 120;
	document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('storing_msg_15')?>";
	if(fOddStat == true)
	{
		sendRequest(on_cancel_burn,'','post','../php/bd_pop_cancel.php',true,true);
		
	}else
	{
		setTimeout("cancel_burn()",1000);
	}	
		
}
function on_cancel_msg(oj){
			debug(oj.responseText);
}
	
function on_cancel_burn(oj)
{
	debug(oj.responseText);
	var res=decodeURIComponent(oj.responseText);
	var pattern = "Complete";
	if(res.match(pattern))
	{
		
		
		clearInterval(timerA);
		clearInterval(timerB);
		var cmd = '&bd_mode=store_image_cancel';
		var php = '../php/bd_pop_lcd_msg.php';
		//sendRequest(on_cancel,cmd,'post',php,true,true);
		var wMsgTxt = "<?php echo lang_get('storing_msg_17')?>";
		document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('storing_msg_17')?>";
		end_task(wMsgTxt);
	}else
	{
		var wMsgTxt = "<?php echo lang_get('usb_sync_msg_3')?>";
		alert(wMsgTxt);
		fCancel = false;
	}
}
function on_cancel(oj)
{
	debug(oj.responseText);
	var wMsgTxt = "<?php echo lang_get('storing_msg_17')?>";
	//document.getElementById('progValue').style.left = 170;
	document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('storing_msg_17')?>";
	end_task(wMsgTxt);
	//alert(wMsgTxt);
	//self.opener.document.getElementById('id_btn_backup').style.visibility = "visible";
	//this.close();
}
//=======================================================//
// Check ODD status
//=======================================================//
function check_odd_stat()
{
	var cmd = '';
	var php = '../php/bd_pop_chk_odd_stat.php';
	if(fOddStat == false) sendRequest(on_check_odd_stat, cmd, 'post', php, true, true);
}
function on_check_odd_stat(oj)
{
	debug(oj.responseText);
	var res = decodeURIComponent(oj.responseText);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	if(ret[1] == 'WORKING') fOddStat = true;
}
//=======================================================//
// Get progress information : image backup (mopilt)
//=======================================================//
function read_image_backup_progress()
{
	var cmd = "&mode=image_backup";
	sendRequest(on_read_audio_rip_progress,cmd,"post","../php/storing_get_image_prog.php",true,true);
}
function on_read_audio_rip_progress(oj)
{
	debug(oj.responseText);
	var res=decodeURIComponent(oj.responseText);
	
	if(res.length < 3){
		return;
	}
	var tmp = parseInt(res,10);
	if(tmp<0){
		tmp = parseInt(res,16);
		var _err = err_mopil[tmp.toString()];
		if(!_err){
			_err = tmp;
		}
		var _err_msg = 'Error : '+_err;
		//sendRequest(on_lcd_msg,'&bd_mode=store_image_error&message='+_err_msg,'post','../php/bd_lcd_msg.php',true,true);
		end_task(_err_msg);
		document.getElementById('progValue').innerHTML = _err_msg;
		return;
	}else if(tmp>100){
		return;
	}
	resize_bar(tmp);
}
function on_lcd_msg(oj){
	debug(oj.responseText);
}
var err_mopil = {
	'-128' : 'SCSI Open Error',
	'-129' : 'Buffer Open Error',
	'-130' : 'Check Drive',
	'-131' : 'Blank Disc',
	'-132' : 'Protected Disc',
	'-133' : 'Open Session Disc',
	'-134' : 'File Open Error',
	'-135' : 'Read Error',
	'-136' : 'Unknown Profile',
	'-137' : 'File Write Error'
};




//=======================================================//
// Image backup
//=======================================================//
function backup_image()
{
	var op_mode = 'store_image';
	var cmd = '&op_mode='+op_mode+'&path=/mnt/fs'+self.opener.document.getElementById("idInImagePath").value;
	var php = '../php/bd_do_task.php';
	sendRequest(on_backup_image,cmd,"post",php,true,true);
}
function on_backup_image(oj)
{
	debug(oj.responseText);
	var res=decodeURIComponent(oj.responseText);
	debug("response from backup : "+res);
	//return;
	//test
	
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'OK':
			//var msg = ret[1];
			//end_task(msg);
			return;
			break;
		case 'NG':
			var msg = ret[1];
			if(msg == 'BLANK DISC'){
				msg = "<?php echo lang_get('burning_msg_6')?>";
			}else if(msg == 'PROTECTED DISCK'){
				msg = "<?php echo lang_get('burning_msg_42')?>";
			}else if(msg == 'No volume'){
				msg = "<?php echo lang_get('storing_msg_20')?>";
			}else{
				msg = 'Error : '+msg;
			}
			end_task(msg);
			return;
			break;
		case 'WARNING':
			var msg = res;
			break;
		case 'ERROR':
			var msg = ret[1];
			if(msg == 'TRAY OPENED'){
				msg = "<?php echo lang_get('schedule_msg_17')?>";
			}else if(msg == 'BD IS BUSY'){
				msg = "<?php echo lang_get('storing_msg_2')?>";
			}else if(msg == 'NO DISC'){
				msg = "<?php echo lang_get('storing_msg_4')?>";
			}else{
				msg = 'Error : '+msg;
			}
			end_task(msg);
			return;
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
	
	//alert(msg);
	//var id = 'id_btn_backup';
	//self.opener.document.getElementById(id).style.visibility = "visible";
	//this.close();
}
//=======================================================//
// End task : prepare to close window
//=======================================================//
function end_task(msg){
	if(is_complete) return;
	if(msg) alert(msg);
	if(timerA) clearInterval(timerA);
	if(timerB) clearInterval(timerB);
	document.getElementById('idButtonBurnNext').innerHTML = "<input type='image' onclick='close_task();' src='../images/btn/btn_confirm.gif'/>";
	document.getElementById('idButtonBurnNext').style.visibility = 'visible';
	is_complete = true;
}
function close_task(){
	if(self.opener.document.getElementById('id_btn_backup')){
		self.opener.document.getElementById('id_btn_backup').style.visibility = "visible";
		//opener.document.getElementById('idDisableBackground').style.display='none'; 
	}
	this.close();
}
//-->
</script>
