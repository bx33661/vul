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
<script type="text/javascript" src="../css/embed.js" charset="utf-8"></script>
<script type="text/javascript" src="../css/lg.js" charset="utf-8"></script>
<script type='text/javascript' src='../css/flash.js' charset="utf-8"></script>
<script type='text/javascript' src='../js/debug.js' charset='utf-8'></script>
<script type='text/javascript' src='../js/jslb_ajax.js' charset='utf-8'></script>
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
<body >
<table width="422" height="211" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="420" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif">
    <span class="popup_text"><?php echo lang_get('schedule_backup_11')?></span>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>--></td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px">
    	<table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="center">
        
        <span id='phpmsg1' style='color:#ADADAD'>1. <?php echo lang_get('schedule_msg_13')?></span><br/>
        <span id='phpmsg2' style='color:#ADADAD'>2. <?php echo lang_get('schedule_msg_14')?></span><br/>
        <span id='phpmsg3' style='color:#ADADAD'>3. <?php echo lang_get('schedule_msg_15')?></span>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">-->
        </td>
      </tr>
      <tr><td height="15"></td></tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" width="370" style="position:absolute;top:165;left:195;"><strong>0 %</strong></div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
  <tr><td height="10"></td></tr>
	<tr>
	<td align="center" style="padding:0 20 0 0px">
		<div id='idButtonBurnNext' style='align:center;visibility:visible;'>
			<!-- swkim 2008-11-16 schedule backup 에서는 취소가 없음.
			<a href="javascript:void(0)" onclick='cancel_burn();'><img src="../images/btn/btn_cancel.gif"  height="22" border="0" /></a> -->
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script type="text/javascript">
<!--
//schedule_backup("<?=$tasknum?>");
//debug(opener.document.getElementById('id_task_number').value);
schedule_backup(opener.document.getElementById('id_task_number').value);

var oldprog = 0;
var cnt_time = 0;
var w = 0;
var prog_max = 370;
var incre = prog_max / 100;
var prog_mode = "";
var timerA = setInterval('show_time()',1000);

//=======================================================//
// Timer function
//=======================================================//
function show_time()
{
	cnt_time++;
	read_image_backup_progress();
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
function read_image_backup_progress()
{
	var cmd = "&mode="+prog_mode;
	sendRequest(on_read_audio_rip_progress,cmd,"post","../php/schedule_get_image_prog.php",true,true);
}
//var proc_stat = [false,false,false];
	// Flag for Checking Status
var proc = {
	stat : [false,false,false],
	lastStat : "",
	id : ['phpmsg1','phpmsg2','phpmsg3'],
	txt : ["1. <?php echo lang_get('schedule_msg_38')?>","2. <?php echo lang_get('schedule_msg_39')?>","3. <?php echo lang_get('schedule_msg_40')?>"]
}
function on_read_audio_rip_progress(oj)
{
	var res=decodeURIComponent(oj.responseText);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	if(ret[0] == "mode"){
		if(ret[1] == "odd_check"){
			//proc_stat[0] = true;
			proc.stat[0] = true;
			proc.lastStat = 0;
			document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_13')?>";
			document.getElementById('phpmsg1').style.color = "#262626";
			document.getElementById('phpmsg1').style.fontWeight = "bolder";
			//document.getElementById('phpmsg2').style.color = "#AAAAAA";
			//document.getElementById('phpmsg3').style.color = "#AAAAAA";
		}else if(ret[1] == "checking_files"){
			//proc_stat[1] = true;
			proc.stat[1] = true;
			proc.lastStat = 1;
			prog_mode="checking_files";
			if(proc.stat[0]){
				document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_38')?>";
			}else{
				document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_13')?>";
			}
			document.getElementById('phpmsg1').style.color = "#ADADAD";
			document.getElementById('phpmsg1').style.fontWeight = "normal";
			document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_14')?>";
			document.getElementById('phpmsg2').style.color = "#262626";
			document.getElementById('phpmsg2').style.fontWeight = "bolder";
			//document.getElementById('phpmsg3').style.color = "#AAAAAA";
		}else if(ret[1] == "buring_files"){
			//proc_stat[2] = true;
			proc.stat[2] = true;
			proc.lastStat = 2;
			oldprog = 0;
			resize_bar(0);
			prog_mode="buring_files";
			if(proc.stat[0]){
				document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_38')?>";
			}else{
				document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_13')?>";
			}
			document.getElementById('phpmsg1').style.color = "#ADADAD";
			document.getElementById('phpmsg1').style.fontWeight = "normal";
			if(proc.stat[1]){
				document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_39')?>";
			}else{
				document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_14')?>";
			}
			document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_14')?>";
			document.getElementById('phpmsg2').style.color = "#ADADAD";
			document.getElementById('phpmsg2').style.fontWeight = "normal";
			//document.getElementById('phpmsg1').style.color = "#AAAAAA";
			//document.getElementById('phpmsg2').style.color = "#AAAAAA";
			document.getElementById('phpmsg3').innerHTML = "3. <?php echo lang_get('schedule_msg_15')?>";
			document.getElementById('phpmsg3').style.color = "#262626";
			document.getElementById('phpmsg3').style.fontWeight = "bolder";
		}
	}else if(ret[0] == "ing"){
		var val = parseInt(ret[1],10);
		/*if(val==99)
		{
			val = 100;
		}*/
		
		// 이전 프로그바 값보다 클경우..
		if(val>oldprog){
			oldprog=val;
			resize_bar(val);
		}		
	}
}

//=======================================================//
// schedule backup
//=======================================================//
//var test_cnt = 0;
function schedule_backup(tasknum)
{
	//test_cnt++;
	//debug("schedule backup count: "+test_cnt);
	//debug("task no.: "+tasknum);
	var cmd="&task_number="+tasknum;
	var php = '../php/schedule_backup.php';
	sendRequest(on_schedule_backup,cmd,"post",php,true,true);
}

function on_schedule_backup(oj)
{
	var msg="";
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	/* New error handling */
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
			document.getElementById('idButtonBurnNext').innerHTML = "<input type='image' src='../images/btn/btn_confirm.gif' onclick='close_backup();'/>";
			document.getElementById('idButtonBurnNext').style.visibility = "visible";
			clearTimeout(timerA);
			return;
		/************************/
	}
	
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'OK':
			//msg = ret[1];
			// Complete task
			var _oj = document.getElementById(proc.id[proc.lastStat]);
			_oj.innerHTML = proc.txt[proc.lastStat];
			_oj.style.color = "#ADADAD";
			_oj.style.fontWeight = "normal";
			if(proc.lastStat == 1) resize_bar(100);
			clearInterval(timerA);
			alert("<?php echo lang_get('esata_5')?>");
			break;
		case 'err':
			msg = ret[1];
			if(msg){
				var _msg = {
					busy : "<?php echo lang_get('storing_msg_2')?>",
					opening : "<?php echo lang_get('schedule_msg_17')?>",
					nodisc : "<?php echo lang_get('schedule_msg_18')?>",
					db : "<?php echo lang_get('schedule_msg_19')?>",
					sizeover : "<?php echo lang_get('schedule_msg_20')?>",
					wrong_discnum : "<?php echo lang_get('schedule_msg_46')?>",
					noinit : "<?php echo lang_get('schedule_msg_23')?>",
					notproperdisc : "<?php echo lang_get('schedule_msg_21')?>",
					notreadydisc : "not ready disc",
					exsize : "<?php echo lang_get('schedule_msg_47')?>"
				}
				if(_msg[msg]){
					var _tmp_msg = _msg[msg];
				}else{
					var _tmp_msg = 'Unknown error';
				}
				alert(_tmp_msg);
				clearInterval(timerA);
			}
			break;
		case 'WARNING':
			//msg = res;
			
			break;
		case 'ERROR':
			//msg = res;
			break;
		case 'EXCEPTION':
			// complete or canceled
			//debug('D : Exception');
			break;
		default:
			// Timeout or cancel
			//debug('D : No return (Timeout/Cancel)');
			// pch_081204 : retry when null is returned
			var cmd = "";
			var php = '../php/schedule_backup2.php';
			sendRequest(on_schedule_backup,cmd,"post",php,true,true);
			return;
			alert("Fail to backup");
			break;
	}
	document.getElementById('idButtonBurnNext').innerHTML = "<input type='image' src='../images/btn/btn_confirm.gif' onclick='close_backup();'/>";
	document.getElementById('idButtonBurnNext').style.visibility = "visible";
	//this.close();
}
function close_backup(){
	if(opener.document.getElementById('id_back')) opener.document.getElementById('id_back').disabled = false;
	this.close();
}
//-->
</script>
