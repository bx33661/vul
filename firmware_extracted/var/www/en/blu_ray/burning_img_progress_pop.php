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
	

	//language information by url start
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
.red_02 {
	font-family : "verdana";
	font-size : 10pt;
	line-height : 12pt;
	color : #925051;
	font-weight : bold;
}
-->
</style>
</head>
<body>
<table width="422" height="211" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="422" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif">
    <span class="popup_text"><?php echo lang_get('burning_6')?></span>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>-->
    </td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px"><table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="middle">
        	<div id="popup_msg" style="font-weight:bolder;"><?php echo lang_get('burning_msg_26')?></div>
        	<!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">-->
        </td>
      </tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" style="position:absolute;top:150;left:25;width:370px;font-weight:bolder;">0 %</div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="center" style="padding:0 20 0 0px">
		<div id='idButtonBurnNext' style='visibility:visible;'>
			<!--<a href="javascript:void(0)" onclick='cancel_burn();'><img src="../images/btn/btn_cancel.gif"  height="22" border="0" /></a>-->
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script>
<!--
image_burn();
//var timer_canc = setTimeout("make_button('cancel')",1000);
//make_button('cancel');


var cnt_time = 0;
var w = 0;
var w_max = 370;
var prog_max = 370;
var incre = prog_max / 100;
var prog_per = 0;
var timerA = '';
//setInterval('show_time()',1000);
//var timerB = setInterval('check_odd_stat()',1000);
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
	/*
	if(fOddStat == true)
	{
		cTimeOdd++;
		if(cTimeOdd > gOdd_chk_time_mrg)
		{
			// Do ODD busy check
			var php = '../php/bd_pop_odd_busy_check.php';
			sendRequest(on_show_time,'','post',php,true,true);
		}
	}
	*/
}
function on_show_time(oj)
{
	if(fCancel==true) return false;
	var res=decodeURIComponent(oj.responseText);
	res = res.split("\n");
	var tmp = res[0].split(":");
	switch(tmp[0])
	{
		case 'OK': // ODD IDLE
			clearInterval(timerA);
			//clearInterval(timerB);
			var cmd = '&bd_mode=burn_image_complete';
			var php = '../php/bd_lcd_msg.php';
			//sendRequest(on_complete,cmd,'post',php,true,true);
			document.getElementById('prog').width = w_max;
			end_tasks("<?php echo lang_get('burning_msg_38')?>", "<?php echo lang_get('burning_msg_38')?>");
			break;
		case 'NG': // ODD BUSY
			if( tmp[1] == "BD IS OPENED" ){
				on_complete();
			}
			//alert(tmp[1]);
			break;
		case 'ERROR':
			//var Msg = "BD BUSY CHECK : "+tmp[1];
			alert(tmp[1]);
			break;
		default:
			break;
	}
}
function on_complete(oj)
{
	document.getElementById('prog').width = w_max;
	end_tasks("<?php echo lang_get('burning_msg_38')?>", "<?php echo lang_get('burning_msg_38')?>");
	//self.opener.document.getElementById('id_btn_refr_img').style.visibility = "visible";
	//self.opener.document.getElementById('idBurnTitleImage').innerHTML = 'Complete Image Burning';
	//self.opener.document.getElementById('idBurnTitle').innerHTML = 'Complete Image Burning';
	//var wMsgTxt = "Image burn was completed.";
	//alert(wMsgTxt);
	//this.close();
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
	if(w>document.getElementById('prog').width){
		document.getElementById('prog').width = w;
	}
	if(fCancel == false)
	{
		//document.getElementById('progValue').style.left = 195;
		document.getElementById('progValue').innerHTML = "<strong>"+prog+" %</strong>";
	}
	if(w>0) document.getElementById('idProg_bar').style.visibility = "visible";
	
	if(prog == 100){
		var cmd = '&bd_mode=burn_image_complete';
		var php = '../php/bd_lcd_msg.php';
		//sendRequest(on_resize_bar,cmd,'post',php,true,true);
		document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('burning_msg_38')?>";
		end_tasks("<?php echo lang_get('burning_msg_38')?>");
		
	}
}
function on_resize_bar(oj){
	debug(oj.responseText);
	document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('burning_msg_38')?>";
	end_tasks("<?php echo lang_get('burning_msg_38')?>");
}
//=======================================================//
// Cancel function
//=======================================================//
function cancel_burn()
{
	make_button();
	if(fCancel)
	{
		var msg = "<?php echo lang_get('burning_msg_39')?>";
		alert(msg);
		return false;
	}
	fCancel = true;
	//document.getElementById('progValue').style.left = 120;
	document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('burning_msg_39')?>";
	
	sendRequest(on_cancel_burn,'','post','../php/bd_pop_cancel.php',true,true);
	
}
function on_cancel_burn(oj)
{
	var res=decodeURIComponent(oj.responseText);
	var pattern = "Complete";
	if(res.match(pattern))
	{
		clearInterval(timerA);
		//clearInterval(timerB);
		var cmd = '&bd_mode=burn_image_cancel';
		var php = '../php/bd_lcd_msg.php';
		//sendRequest(on_cancel,cmd,'post',php,true,true);
		var wMsgTxt = "<?php echo lang_get('burning_msg_41')?>";
		document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('burning_msg_41')?>";
		end_tasks(wMsgTxt,wMsgTxt);
	}else
	{
		fCancel = false;
		setTimeout('cancel_burn()',1000);
		/*
		var wMsgTxt = "<?php echo lang_get('burning_msg_40')?>";
		document.getElementById('progValue').innerHTML = "<?php echo lang_get('burning_msg_40')?>";
		alert(wMsgTxt);
		fCancel = false;
		*/
	}
}
function on_cancel(oj)
{
	var wMsgTxt = "<?php echo lang_get('burning_msg_41')?>";
	document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('burning_msg_41')?>";
	end_tasks(wMsgTxt,wMsgTxt);
	//alert(wMsgTxt);
	//self.opener.document.getElementById('id_btn_refr_img').style.visibility = "visible";
	//self.opener.document.getElementById('idBurnTitleImage').innerHTML = 'Canceled Image Burning';
	//self.opener.document.getElementById('idBurnTitle').innerHTML = 'Canceled Image Burning';
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
	var res=decodeURIComponent(oj.responseText);
	debug('on_read_audil_rip_progress : '+res);
	
	if(res.length < 3){
		return;
	}
	var tmp = parseInt(res,10);
	if(tmp<0)
	{
		tmp = parseInt(res,16);
		var _err = err_mosil[tmp.toString()];
		if(!_err){
			_err = tmp;
		}
		//sendRequest(on_lcd_msg,'&bd_mode=burn_image_error&message='+'Error : '+_err,'post','../php/bd_lcd_msg.php',true,true);
		end_tasks('Error : '+_err,'Error : '+_err);
		document.getElementById('progValue').innerHTML = 'Error : '+_err;
		return;
	}else if(tmp>100){
		return;
	}
	resize_bar(tmp);
}
function on_lcd_msg(oj){
	debug(oj.responseText);
}
var err_mosil = {
	'-128' : 'SCSI Open Error',    
	'-129' : 'Buffer Open Error',
	'-130' : 'Check Drive',        
	'-131' : 'Not Blank Disc',     
	'-132' : 'Not Enough Space',   
	'-133' : 'Invalid Cue',
	'-134' : 'File Open Error',    
	'-135' : 'Write Error',        
	'-136' : 'File Access Error',  
	'-137' : 'Format Error'        
};



//========================================================//
// Image burn
//========================================================//
var fTimerA = 'false';
function start_show_time(){
	timerA = setInterval('show_time()',1000);
	fTimerA = 'true';
}
var timerB = '';
function image_burn()
{
	//timerB = setTimeout("make_button('cancel')",2000);
	//timerA = setTimeout('start_show_time()',2000);
	
	
	var op_mode = 'burn_image';
	var _data=self.opener.document.getElementById('file_list_to_pop').value;
	var _cap = self.opener.document.getElementById('file_cap_to_pop').value;
		
	var cmd = '&op_mode='+op_mode+'&filename='+_data+'&capacity='+_cap;
	var php = '../php/bd_do_task.php';
	sendRequest(on_image_burn,cmd,"post",php,true,true);
	start_task();
	return;
}
function on_image_burn(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug("on image burn : "+res);
	//test
	
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	var _msg = ret[1];
	var _result = "";
	switch(ret[0])
	{
		case 'OK':
			_result = ret[1];
			timerB = setTimeout("make_button('cancel')",2000);
			timerA = setTimeout('start_show_time()',2000);
			return;
			break;
		case 'NG':
			_result = ret[1];
			break;
		case 'WARNING':
			var msg = res;
			break;
		case 'ERROR':
			var msg = res;
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
	end_tasks(_msg,_result);
	//alert(msg);
	//self.opener.document.getElementById('idBurnTitleImage').innerHTML = res;
	//self.opener.document.getElementById('idButtonBurnNext').style.visibility= 'visible';
	//this.close();
}

//=======================================================//
// Start/End All Works
//=======================================================//
var task_stat = "init";
function start_task(){
	task_stat = "start";
	if(self.opener){
		self.opener.document.getElementById('id_btn_frmt_disc').disabled = true;
		self.opener.document.getElementById('id_btn_burn_data').disabled = true;
		self.opener.document.getElementById('id_btn_refr_img').style.visibility = "hidden";
	}
}
function end_tasks(msg,result){
	if(task_stat == "end") return;
	task_stat = "end";
	var _result = "<?php echo lang_get('burning_msg_38')?>";
	
	if(result) _result = result;
	if(fTimerA=='true'){
		clearInterval(timerA);
	}else if(fTimerA=='false'){
		clearTimeout(timerA);
	}
	if(timerB) clearTimeout(timerB);
	if(msg){
		alert(msg);
	}
	if(self.opener.document.getElementById('id_btn_frmt_disc')){
		self.opener.document.getElementById('id_btn_frmt_disc').disabled = false;
		self.opener.document.getElementById('id_btn_burn_data').disabled = false;
		self.opener.document.getElementById('id_btn_refr_img').style.visibility = "visible";
		self.opener.document.getElementById('idBurnTitleImage').innerHTML = _result;
	}
	make_button('confirm');
	
}
//=======================================================//
// Make button
//=======================================================//
function make_button(btn){
	switch(btn){
		case "cancel":
			var _button = "<input type='image' onclick='cancel_burn();' src='../images/btn/btn_cancel.gif'  border='0' />";
			break;
		case "confirm":
			var _button = "<input type='image' onclick='close_window();' src='../images/btn/btn_confirm.gif' border='0' />";
			break;
		default:
			break;
	}
	if(_button) document.getElementById('idButtonBurnNext').innerHTML = _button;
	else document.getElementById('idButtonBurnNext').innerHTML = '';
}
//=======================================================//
// Close this window
//=======================================================//
function close_window(){
	this.close();
}
//-->
</script>
