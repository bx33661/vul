
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
    <img src="../images/popup/txt_popup_dvd_extraction.gif" width="218" height="35" border="0"></td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px"><table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="center">
        <font size="3"><strong>Extracting DVD Title...</strong></font>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">--></td>
      </tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" width="370" style="position:absolute;top:150;left:195;"><strong>0 sec</strong></div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="right" style="padding:0 20 0 0px">
		<div id='idButtonBurnNext' style='visibility:visible;'>
			<a href="javascript:void(0)" onclick='cancel_burn();'><img src="../images/btn/btn_cancel.gif"  height="22" border="0" /></a>
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script>
<!--
rip_dvd();


var cnt_time = 0;
var w = 0;
var prog_max = 370;
var incre = prog_max / 100;
var prog_per = 0;
var timerA = setInterval('show_time()',1000);
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
	resize_bar();
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
			clearInterval(timerB);
			var cmd = '&bd_mode=rip_dvd_complete';
			var php = '../php/bd_pop_lcd_msg.php';
			sendRequest(on_complete,cmd,'post',php,true,true);
			break;
		case 'NG': // ODD BUSY
			//debug(tmp[1]);
			break;
		case 'ERROR':
			//var Msg = "BD BUSY CHECK : "+tmp[1];
			//debug(tmp[1]);
			break;
		default:
			break;
	}
}
function on_complete(oj)
{
	self.opener.document.getElementById('id_btn_rip_dvd').style.visibility = "visible";
	var wMsgTxt = "DVD Extraction was completed.";
	alert(wMsgTxt);
	this.close();
}
//=======================================================//
// Progress bar control function
//=======================================================//
function resize_bar()
{
	w += incre;
	if(w>prog_max)
	{
		w -= prog_max;
	}
	document.getElementById('prog').width = w;
	if(fCancel == false)
	{
		document.getElementById('progValue').style.left = 195;
		document.getElementById('progValue').innerHTML = "<strong>"+cnt_time+" sec</strong>";
	}
	if(w>0) document.getElementById('idProg_bar').style.visibility = "visible";
}
//=======================================================//
// Cancel function
//=======================================================//
function cancel_burn()
{
	fCancel = true;
	document.getElementById('progValue').style.left = 120;
	document.getElementById('progValue').innerHTML = "<strong> Cancelling DVD Extraction...</strong>";
	if(fOddStat == true)
	{
		sendRequest(on_cancel_burn,'','post','../php/bd_pop_cancel.php',true,true);
	}else
	{
		setTimeout("cancel_burn()",1000);
	}
}
function on_cancel_burn(oj)
{
	var res=decodeURIComponent(oj.responseText);
	var pattern = "Complete";
	if(res.match(pattern))
	{
		clearInterval(timerA);
		clearInterval(timerB);
		var cmd = '&bd_mode=rip_dvd_cancel';
		var php = '../php/bd_pop_lcd_msg.php';
		sendRequest(on_cancel,cmd,'post',php,true,true);
	}else
	{
		var wMsgTxt = "Error in cancelling DVD Extraction.";
		alert(wMsgTxt);
		fCancel = false;
	}
}
function on_cancel(oj)
{
	var wMsgTxt = "DVD Extraction was canceled.";
	alert(wMsgTxt);
	self.opener.document.getElementById('id_btn_rip_dvd').style.visibility = "visible";
	this.close();
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
// DVD ripping
//=======================================================//
function rip_dvd()
{
	var _tmp=read_dvd_setting();
	var op_mode = 'rip_dvd';
	var cmd = "&op_mode="+op_mode+"&mode="+_tmp[0]+"&path=/mnt/fs"+_tmp[1]+"&titlename="+_tmp[2];
	//debug(cmd);
	var php = '../php/bd_do_task.php';
	sendRequest(on_rip_dvd, cmd,"post",php,true,true);	 
}
function on_rip_dvd(oj)
{
	var res=decodeURIComponent(oj.responseText);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'OK':
			var msg = ret[1];
			break;
		case 'NG':
			var msg = ret[1];
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
			return false;
			break;
		default:
			// Timeout or cancel
			debug('D : No return (Timeout/Cancel)');
			return false;
			break;
	}
	//alert('RESULT : '+msg);
	//self.opener.document.getElementById('id_btn_rip_dvd').style.visibility = "visible"; 
	//this.close();
}
function read_dvd_setting()
{
	var _oj = new Array(self.opener.document.getElementById('idModeDvd'),
	self.opener.document.getElementById('idDvdSavePath'),
	self.opener.document.getElementById('idDvdFilename'));
	var _tmp=new Array();
	_tmp[0]=_oj[0].selectedIndex;
	_tmp[0]=_oj[0].options[_tmp[0]].value;
	_tmp[1]=_oj[1].value;
	_tmp[2]=_oj[2].value;
	return _tmp;
}
//-->
</script>
