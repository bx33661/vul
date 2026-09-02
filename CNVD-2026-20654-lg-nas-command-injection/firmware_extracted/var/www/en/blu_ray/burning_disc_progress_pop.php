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

	//$_SESSION['expired_time'] = 'ignored';
	
?>
<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="../css/embed.js" charset='utf-8'></script>
<script type="text/javascript" src="../css/lg.js" charset='utf-8'></script>
<script type="text/javascript" src='../css/flash.js' charset='utf-8'></script>
<script type="text/javascript" src='../js/debug.js' charset='utf-8'></script>
<script type="text/javascript" src='../js/jslb_ajax.js.php' charset='utf-8'></script>
<link href="../css/styles.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

// JUNY : If 'F5' key is pressed, ignore to prevent that burning windows is closed 
document.onkeydown = function(e) { 
	var evtK = (e) ? e.which : window.event.keyCode; 
	var isCtrl = ((typeof isCtrl != 'undefined' && isCtrl) || ((e && evtK == 17) || (!e && event.ctrlKey))) ? true : false; 

	if ((isCtrl && evtK == 82) || evtK == 116) { 
	if (e) { evtK = 505; } else { event.keyCode = evtK = 505; } 
	} 
	if (evtK == 505) { 
		return false; 
	} 
} 


//=======================================================//
// Burning
//=======================================================//
var burn = {
	timer : "",
	wait_time : 0,
	wait_time_margin : 3,
	start_timer : function(){
		this.wait_time++;
		if(this.wait_time>this.wait_time_margin){
			clearInterval(this.timer);
		}
	},
	ing : false,
	vol_name : '',
	start : function(){
		this.ing = true;
		var _list = self.opener.document.getElementById('file_list_to_pop').value;
		this.vol_name = self.opener.document.getElementById('idBurnVolume').value;
		
		var op_mode = 'burn_data_img';
		var cmd = '&op_mode='+op_mode+'&list='+_list+'&vol_name='+this.vol_name;
		var php = '../php/bd_do_task.php';
		debug("burn start make iso");
		sendRequest(on_start_img,cmd,'post',php,true,true);
		document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('burning_msg_11')?>";
		document.getElementById('phpmsg1').style.color = "#925051";
		document.getElementById('phpmsg1').style.fontWeight = "bold";

		this.wait_time = 0;
		this.timer = setInterval("burn.start_timer()",3000);
		
		function on_start_img(oj){
			if(fCancel){
				return;
			}
			var res=decodeURIComponent(oj.responseText);
			debug("response from making image:"+res);
			if(res==""){
				// Critical error!
			}
			var _tmp = res.split("\n");
			res = _tmp[0].split(":");
			var _tmp = msg.show_img(res);
			if(_tmp == 1){
				var op_mode = 'burn_data_brn';

				//juny : ODD burning 
				var php = '../../system/task_burning.php';
				//var php = '../php/bd_do_task.php';
				var cmd = '&op_mode='+op_mode+'&vol_name='+burn.vol_name;
		
				sendRequest(burn.on_start,cmd,'post',php,true,true);
				document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('burning_msg_14')?>";
				document.getElementById('phpmsg2').style.color = "#925051";
				document.getElementById('phpmsg2').style.fontWeight = "bold";
				
				prog.start_read();
				this.wait_time = 0;
				this.timer = setInterval("burn.start_timer()",3000);
			}else if(_tmp == -1){
				odd_check.start_img();
				
			}else{
				clearTimeout(timer_canc);
				document.getElementById('id_btn_canc').style.display = "none";
				document.getElementById('id_btn_conf').style.display = "block";
				if(opener.document.getElementById('id_btn_refr_data'))	opener.document.getElementById('id_btn_refr_data').style.visibility ="visible";
				if(self.opener.document.getElementById('idBurnTitle')){
					self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_15')?>";
				}
				burn.ing = false;
			}
			
		}
	},
	on_start : function(oj){
		var res=decodeURIComponent(oj.responseText);
		debug("response from burning disc:"+res);
		if(res==""){
			// Critical error!
		}
		var _tmp = res.split("\n");
		res = _tmp[0].split(":");
		//debug("message:"+res[1]);
		var _tmp = msg.show_brn(res);
		debug("result of message:"+_tmp);
		if(_tmp == 1){
			// Complete Burning
			/*
			prog.finish();
			if(self.opener.document.getElementById('idBurnTitle')){
				self.opener.document.getElementById('idBurnTitle').innerHTML = 'Complete Disc Burning';
			}
			*/
		}else if(_tmp == -1){
			return true;
		}else{
			/*
			if(res[1]=="DB IS BUSY"){
				debug("ignore busy message");
				// Keep doing
				// Finish in prog
				prog.fFin = true;
				return true;
			}else{
				
			}
			*/
			// Error
			prog.stop();
			if(self.opener.document.getElementById('idBurnTitle')){
				self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_15')?>";
			}
			document.getElementById('id_btn_canc').style.display = "none";
			document.getElementById('id_btn_conf').style.display = "block";
			burn.ing = false;
		}
	},
	start_burn : function(){
		document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('burning_msg_13')?>";
		document.getElementById('phpmsg1').style.color = "#ADADAD";
		document.getElementById('phpmsg1').style.fontWeight = "normal";
		var cmd = "";
		var php = '../php/burning_burn_disc_img.php';
		sendRequest(burn.on_start,cmd,'post',php,true,true);
		document.getElementById('phpmsg2').innerHTML = "<?php echo lang_get('burning_msg_14')?>";
		document.getElementById('phpmsg2').style.color = "#925051";
		document.getElementById('phpmsg2').style.fontWeight = "bold";
		
		prog.start_read();
		this.wait_time = 0;
		this.timer = setInterval("burn.start_timer()",3000);
	}
}
//=======================================================//
// Progress
//=======================================================//
var prog = {
	per : 0,
	fFin : false,
	c_err : 0,
	c_err_max : 100,
	w_max : 370,
	timer : "",
	start_read : function(){
		this.timer = setInterval('prog.read()',3000);document.getElementById('prog').width = this.w_max / 100;document.getElementById('progValue').innerHTML = "0 %";document.getElementById('idProg_bar').style.visibility = "visible";
	},
	finish : function(){
		clearInterval(prog.timer);
		document.getElementById('idProg_bar').style.visibility = "visible";
		document.getElementById('prog').width = this.w_max;
		document.getElementById('progValue').innerHTML = "100 %";
		
		
	},
	stop : function(){
		if(this.timer) clearInterval(this.timer);
	},
	read : function(){
		var cmd = '&mode=burn_data';
		var php = "../php/storing_get_image_prog.php";
		sendRequest(on_read, cmd, 'post', php,true,true);
		
		function on_read(oj){
			var res=decodeURIComponent(oj.responseText);
			debug('prog.read() : '+res);
			/*if(res.length<3){
				return;
			}*/
			/* Check if the reading value of odd progress file is not wrong
			if(res==""){
				// Critical error!
				prog.c_err++;
				if(prog.c_err>prog.c_err_max){
					// Finish burning
					document.getElementById('progValue').innerHTML = "Reponse Timeout from NAS";
					prog.stop();
					document.getElementById('phpmsg2').innerHTML = "<?php echo lang_get('burning_msg_15')?>";
					document.getElementById('phpmsg2').style.color = "#ADADAD";
					document.getElementById('phpmsg2').style.fontWeight = "normal";
					document.getElementById('id_btn_canc').style.display = "none";
					document.getElementById('id_btn_conf').style.display = "block";
					if(self.opener.document.getElementById('idBurnTitle')){
						self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_15')?>";
					}
				}
			}
			*/
			if(res==""){
				return;
			}
		
			
			var _w = res;//parseInt(res,10);
			if(_w<0){
				var _err = _err_motil[_w.toString()];
				if(!_err){
					_err = _w;
				}
				//sendRequest(on_lcd_msg,'&bd_mode=burn_data_error&message='+'Error : '+_err,'post','../php/bd_lcd_msg.php',true,true);
				document.getElementById('progValue').innerHTML = 'Error : '+_err;
				document.getElementById('id_btn_canc').style.display = "none";
				
				prog.stop();
				alert('Error : '+_err);
				document.getElementById('id_btn_conf').style.display = "block";
				if(self.opener.document.getElementById('idBurnTitle')){
					self.opener.document.getElementById('idBurnTitle').innerHTML = 'Error : '+_err;
				}
				
			}else if(_w<=100){				
				document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('burning_msg_14')?>";
				document.getElementById('phpmsg2').style.color = "#925051";
				document.getElementById('phpmsg2').style.fontWeight = "bold";

				
				document.getElementById('idProg_bar').style.visibility = "visible";
				
				//if(_w>prog.per){
					prog.per = _w;
					document.getElementById('prog').width = prog.w_max/100*_w;
					document.getElementById('progValue').innerHTML = _w+" %";
				//}
				//if(self.opener.document.getElementById('idBurnTitle')){
				//	self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_14')?>";
				//}
				
			}
			//if(_w==100){
			if(_w == 'Burn Completed'){
				prog.finish_lcd();
			}
			else if(_w == 'Burn Failed' || _w == 'Aborted before burning')
			{
				var _err = _w;

		              if(document.getElementById('id_btn_canc').style.display == "none")
		             	       return;
								
				document.getElementById('progValue').innerHTML = 'Error : '+_err;
				document.getElementById('id_btn_canc').style.display = "none";

				prog.stop();				
				//alert('Error : '+_err);
				
				document.getElementById('id_btn_conf').style.display = "block";
				if(self.opener.document.getElementById('idBurnTitle')){
					self.opener.document.getElementById('idBurnTitle').innerHTML = 'Error : '+_err;
				}								
					
			}
			else if(_w == 'Formatting' || _w == 'Format Completed')
			{
				document.getElementById('phpmsg2').innerHTML = "2. Formatting...";
				document.getElementById('phpmsg2').style.color = "#925051";
				document.getElementById('phpmsg2').style.fontWeight = "bold";
				//progress bar : Formatting..
				//if(self.opener.document.getElementById('idBurnTitle')){
				//	self.opener.document.getElementById('idBurnTitle').innerHTML = 'Formatting..';
				//}				
			}

			
		}
	},
	finish_lcd : function(){
		burn.ing = false;
		document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('burning_msg_16')?>";
		document.getElementById('phpmsg2').style.color = "#ADADAD";
		document.getElementById('phpmsg2').style.fontWeight = "normal";
		
		this.finish();
              if(document.getElementById('id_btn_canc').style.display == "none" &&  document.getElementById('id_btn_conf').style.display == "block")
             	       return;
		
		document.getElementById('id_btn_canc').style.display = "none";
		document.getElementById('id_btn_conf').style.display = "block";
		if(self.opener){
			self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_16')?>";
		}
		//sendRequest(on_fin_lcd,"&bd_mode=burn_data_complete","post","../php/bd_lcd_msg.php",true,true);
		alert("<?php echo lang_get('burning_msg_16')?>");
		
		function on_fin_lcd(oj){
			alert('Complete');
			var res=decodeURIComponent(oj.responseText);
			debug("from prog lcd display:"+res);
		}
	}
}
function on_lcd_msg(oj){
//	document.getElementById('progValue').innerHTML = msg;
}

// Data Disc Buring
var _err_motil = {
			'-90': 'Disc Format Error',
			'-91': 'Disc Write Error' ,
			'-92': 'Disc Full'        ,
			'-93': 'Scsi Open Error'  ,
			'-94': 'Disc Read Error'
		};
//=======================================================//
// Message
//=======================================================//
var msg = {
	show_img : function(arr){
		if(!arr) return false;
		var _op = arr[0];
		var _msg = arr[1];
		
		if(_op=="OK"){
			document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('burning_msg_13')?>";
			document.getElementById('phpmsg1').style.color = "#ADADAD";
			document.getElementById('phpmsg1').style.fontWeight = "normal";
			return 1;
		}else if(burn.wait_time>burn.wait_time_margin){
			debug("ignore message from making image file");
			
			return -1;
		}else{
			//to do
			switch(_op){
				case "ERROR":
					break;
				case "NG":
					break;
				case "WARNING":
					break;
				default:
					break;
			}
			document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('burning_msg_12')?>";
			document.getElementById('phpmsg1').style.color = "#ADADAD";
			document.getElementById('phpmsg1').style.fontWeight = "normal";
			document.getElementById('progValue').innerHTML = _msg;
			document.getElementById('progValue').style.fontWeight = "bold";
			//document.getElementById('progValue').style.left = "160px";
			return 0;
		}
		
	},
	show_brn : function(arr){
		if(!arr) return false;
		var _op = arr[0];
		var _msg = arr[1];
		if(_op=="OK"){
			/*
			document.getElementById('phpmsg2').innerHTML = "2. Complete Burning Disc";
			document.getElementById('phpmsg2').style.color = "#ADADAD";
			document.getElementById('phpmsg2').style.fontWeight = "normal";
			*/
			return 1;
		}else if(burn.wait_time>burn.wait_time_margin){
			debug("ignore message");
			prog.fFin = true;
			return -1;
		
		}else{
			
			switch(_op){
				case "ERROR":
				break;
				case "NG":
				break;
				case "WARNING":
				break;
				default:
				break;
			}
			document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('burning_msg_15')?>";
			document.getElementById('phpmsg2').style.color = "#ADADAD";
			document.getElementById('phpmsg2').style.fontWeight = "normal";
			document.getElementById('progValue').innerHTML = _msg;
			document.getElementById('progValue').style.fontWeight = "bold";
			//document.getElementById('progValue').style.left = "160px";
			return 0;
		}
		
	}
}
//=======================================================//
// When close window
//=======================================================//
window.onbeforeunload = on_unload_cancel;
function on_unload_cancel(){
	// Check progress
	//if(burn.ing) alert("Burning was not completed.");
	fin();
}
function fin(){
	if(opener.document.getElementById('id_btn_burn_data')) opener.document.getElementById('id_btn_burn_data').disabled =false;
	if(opener.document.getElementById('id_btn_refr_data'))	opener.document.getElementById('id_btn_refr_data').style.visibility ="visible";
		//opener.document.getElementById('idDisableBackground').style.display='none'; 
	//clearInterval(_kTimer);
	this.close();
}
//=======================================================//
// Cancel function
//=======================================================//
var fCancel = false;
function cancel_burn()
{
	document.getElementById('id_btn_canc').style.display = "none";
	if(fCancel)
	{
		var msg = "<?php echo lang_get('burning_msg_18')?>";
		alert(msg);
		return false;
	}
	fCancel = true;
	//document.getElementById('progValue').style.left = 120;
	document.getElementById('progValue').innerHTML = "<?php echo lang_get('burning_msg_18')?>";

	clearInterval(prog.timer);
	sendRequest(on_cancel_burn,'','post','../php/bd_pop_cancel.php',true,true);
	
}
function on_cancel_burn(oj)
{
	
	var res = decodeURIComponent(oj.responseText);
	var pattern = "Complete";
	if(res.match(pattern))
	{
		prog.stop();
		odd_check.stop();
		var cmd = '&bd_mode=burn_data_cancel';
		var php = '../php/bd_lcd_msg.php';
		//sendRequest(on_cancel,cmd,'post',php,true,true);
		var wMsgTxt = "<?php echo lang_get('burning_msg_21')?>";
		alert(wMsgTxt);
		fCancel = false;
		if(self.opener.document.getElementById('id_btn_refr_data')){
			self.opener.document.getElementById('id_btn_refr_data').style.visibility = "visible";
			self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_21')?>";
		}
		//self.opener.document.getElementById('idBurnTitleImage').innerHTML = 'Canceled Disc Burning';
		document.getElementById('id_btn_canc').style.display = "none";
		document.getElementById('id_btn_conf').style.display = "block";
		document.getElementById('progValue').innerHTML = "<?php echo lang_get('burning_msg_21')?>";
	}else
	{
		fCancel = false;
		setTimeout('cancel_burn()',3000);
		/*
		var wMsgTxt = "<?php echo lang_get('burning_msg_17')?>";
		alert(wMsgTxt);
		fCancel = false;
		document.getElementById('id_btn_canc').style.display = "block";
		document.getElementById('id_btn_conf').style.display = "none";
		*/
	}
	
}
function on_cancel(oj)
{
	var wMsgTxt = "<?php echo lang_get('burning_msg_21')?>";
	alert(wMsgTxt);
	if(self.opener.document.getElementById('id_btn_refr_data')){
		self.opener.document.getElementById('id_btn_refr_data').style.visibility = "visible";
		self.opener.document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('burning_msg_21')?>";
	}
	//self.opener.document.getElementById('idBurnTitleImage').innerHTML = 'Canceled Disc Burning';
	document.getElementById('id_btn_canc').style.display = "none";
	document.getElementById('id_btn_conf').style.display = "block";
	document.getElementById('progValue').innerHTML = "<?php echo lang_get('burning_msg_21')?>";
}
//=======================================================//
// ODD check
//=======================================================//
var odd_check = {
	timer : "",
	start : function(){
		this.timer = setInterval("odd_check.check()",100000);
	},
	check : function(){
		sendRequest(on_check,"","post","../php/bd_odd_check.php",true,true);
		
		function on_check(oj){
			var res=decodeURIComponent(oj.responseText);
			debug("odd check=>"+res);
			if(res==""){
				debug("odd check error : check oddmngrt");
			}else{
				debug("odd working");
			}
		}
	},
	stop : function(){
		if(this.timer)	clearInterval(this.timer);
	},
	start_img : function(){
		//if(this.timer) clearInterval(this.timer);
		this.timer = setInterval("odd_check.check_img()",3000);
	},
	check_img : function(){
		sendRequest(on_check_img,"","post","../php/bd_pop_odd_busy_check.php",true,true);
		
		function on_check_img(oj){
			var res=decodeURIComponent(oj.responseText);
			//debug("img)odd check=>"+res);
			if(res.search("BD IS IDLE")!=-1){
				// to do burn iso
				debug("start burn:"+res);
				setTimeout('burn.start_burn()',2000);
				clearInterval(odd_check.timer);
			}
		}
	}
}
/*var _kTimer = setInterval('keep_connect()',100000);
function keep_connect(){
	sendRequest(on_keep,"&tmp=tmp","post","../php/connecting.php",true,true);
	
	function on_keep(oj){
		var res=decodeURIComponent(oj.responseText);
		debug(res);
	}
	
}*/
//-->
</script>

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
.line01 {
	font-family : verdana;
	font-size : 10pt;
	line-height : 15pt;
	color : #ADADAD;
	font-weight : normal;
	/*height : 40px;
	width : 200px;
	padding : 10px 30px 10px 30px;*/
}
.prog {
	font-family : italic;
	font-size : 10pt;
	line-height : 15pt;
	color : #ADADAD;
	font-weight : bolder;
	align : "center";
}
-->
</style>
</head>
<body>
<table width="422" height="211" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="422" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif">
    	<span class="popup_text"><?php echo lang_get('burning_msg_14')?></span>
    </td>
  </tr>
  
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px"><table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="center">
        <font class="red_02"><?php echo lang_get('burning_msg_10')?></font>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">-->
        <br/>
        <br/>
        <span id='phpmsg1' class="line01" >1. <?php echo lang_get('burning_msg_11')?></span><br/>
        <span id='phpmsg2' class="line01" >2. <?php echo lang_get('burning_msg_14')?></span><br/>
        </td>
      </tr>
      <tr><td height="20px"></td></tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center"  style="position:absolute;top:175;left:25;width:370px;font-weight:bolder;">0 %</div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="center" style="padding:0 20 0 0px">
		<div id='idButtonBurnNext' style='visibility:visible;padding-top:10px;'>
			<input id="id_btn_canc" style="display:none;" type="image" onclick='cancel_burn();' src="../images/btn/btn_cancel.gif" border="0" />
			<input id="id_btn_conf" style="display:none;" type="image" onclick='fin();' src="../images/btn/btn_confirm.gif"  border="0" />
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script language="javascript">
<!--
burn.start();
//odd_check.start();
var timer_canc = setTimeout("show_btn_cancel()",2000);
function show_btn_cancel(){
	document.getElementById('id_btn_canc').style.display = "block";
}

//-->
</script>
