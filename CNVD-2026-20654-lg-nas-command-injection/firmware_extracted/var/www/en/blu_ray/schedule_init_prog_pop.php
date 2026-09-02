<?php
include "../session/session_manage.php";

if ( sm_session_check_on_popup() == FALSE )
{
	//include "../php/msg_illegal_access.php";
	//include "../php/msg_illegal_access_pop.php";
	echo '-99';
	
	return;
}
	
	 	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
 	
?>

<!-- Popup window -->
<!-- Scheduling backup : initialize -->
<html>
<head>
<title>Welcome to LG Electronics</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/debug.js" charset="utf-8"></script>
<script type="text/javascript" src="../js/jslb_ajax.js.php" charset="utf-8"></script><!-- // Async communication //-->

<script type="text/javascript">
<!--
var init = {
	
	mode : "",
	mode_list : ["all","disc","db"],
	"stat" : 0,
	stat_list : ["start","db","disc","end"],
	doing : false,
	all : function(){
		
		this["stat"] = 0;
		this.doing = true;
		document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_27')?>";
		document.getElementById('phpmsg1').style.color = "#925051";
		document.getElementById('phpmsg1').style.fontWeight = "bolder";
		var _mode = "init_db";
		sendRequest(on_db,"&mode="+_mode,"post","../php/schedule_init.php",true,true);
		
		
		function on_db(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
			var _tmp = res.split("\n");
			res = _tmp[0].split(":");
			switch(res[0]){
				case "OK":
					init["stat"]++;
					document.getElementById('phpmsg1').innerHTML = "1. <?php echo lang_get('schedule_msg_28')?>";
					document.getElementById('phpmsg1').style.color = "#ADADAD";
					document.getElementById('phpmsg1').style.fontWeight = "normal";
					document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_37')?>";
					document.getElementById('phpmsg2').style.color = "#925051";
					document.getElementById('phpmsg2').style.fontWeight = "bolder";
					var _mode = "format_disc";
					sendRequest(on_disc,"&mode="+_mode,"post","../php/schedule_init.php",true,true);
					
					//prog.start();
				break;
				case "ERROR":
					alert(res[1]);
					document.getElementById('phpmsg1').innerHTML = "<?php echo lang_get('schedule_msg_33')?>";
					document.getElementById('id_div_conf').style.visibility = "visible";
					init.doing = false;
				break;
				default:
					// Session out message
					//alert(res[0]);
					document.getElementById('id_div_conf').style.visibility = "visible";
					init.doing = false;
				break;
			}
		}
			
			function on_disc(oj){
				var res=decodeURIComponent(oj.responseText);
				debug(res);
				/* New error handling */
				if(res.search('{')>-1){
					/* In case of tray is not closed */
					eval('var _ret = '+res);
					if(_ret.result == '-4'){
						/* Restore is working */
						var _msg = "<?php echo lang_get('storing_msg_2')?>";	// Multi-language conversion
					}else if(_ret.result == '-5'){
						/* Tray is not closed */
						var _msg = "<?php echo lang_get('schedule_msg_17')?>";	// Multi-language conversion
					}else if(_ret.result == '-6'){
						/* No disc in drive */
					 	var _msg = "<?php echo lang_get('schedule_msg_18')?>";	// Multi-language conversion
					}
						alert(_msg);	// Currently message is english
						init.doing = false;
						document.getElementById('id_div_conf').style.visibility = "visible";
						return;
					/************************/
				}
				
				
				var _tmp = res.split("\n");
				res = _tmp[0].split(":");
				switch(res[0]){
					case "BUSY":
						var _msg = "<?php echo lang_get('storing_msg_2')?>";
						alert(_msg);
						document.getElementById('id_div_conf').style.visibility = "visible";
						break;
					case "OK":
						init["stat"]++;
						document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_25')?>";
						document.getElementById('phpmsg2').style.color = "#ADADAD";
						document.getElementById('phpmsg2').style.fontWeight = "normal";
						document.getElementById('id_div_conf').style.visibility = "visible";
					break;
					case "ERROR":
						var _msg = {
								"BUSY" : "<?php echo lang_get('storing_msg_5')?>",
								"NOT FORMATTABLE MEDIA" : "<?php echo lang_get('schedule_msg_34')?>"
							}
						//alert(_msg[res[1]]);
						var _oj = document.getElementById('progValue');
						_oj.innerHTML = _msg[res[1]];
						_oj.style.visibility = "visible";
						document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_35')?>";
						document.getElementById('phpmsg2').style.color = "#ADADAD";
						document.getElementById('phpmsg2').style.fontWeight = "normal";
						document.getElementById('id_div_conf').style.visibility = "visible";
						
					break;
					default:
						
					break;
				}
				init.doing = false;
			}
		}
		/*
		do : function(mode){
		
		if(!mode || mode == "all") return false;
		this.mode = mode;
		sendRequest(on_init,"&mode="+mode,"post","../php/schedule_init.php",true,true);
		
		function on_init(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
		}
	}
	*/

}

var prog = {
	w : 370/100,
	oj_bar : document.getElementById('prog'),
	oj_txt : document.getElementById('progValue'),
	start : function(){
		var _timer = setInterval('prog.read()',1000);
	},
	read : function(){
		sendRequest(on_read,"","post","../php/burning_get_odd_prog.php",true,true);
		
		function on_read(oj){
			var res=decodeURIComponent(oj.responseText);
			//debug(res);
			var _num = parseInt(res,10);
			if(_num>0){
				prog.oj_txt.innerHTML = _num+" %";
				var _w = prog.w * _num;
				prog.oj_bar.width = _w;
				prog.oj_bar.style.visibility="visible";
				prog.oj_txt.style.visibility="visible";
			}
			
		}
	},
	close : function(){
		document.getElementById('phpmsg2').innerHTML = "2. <?php echo lang_get('schedule_msg_25')?>";
		document.getElementById('phpmsg2').style.color = "#ADADAD";
		document.getElementById('phpmsg2').style.fontWeight = "normal";
		document.getElementById('id_div_conf').style.visibility = "visible";
	}
}
function fin(){
	if(opener.document.getElementById('id_btn_init')) opener.document.getElementById('id_btn_init').disabled = false;
	if(opener.document.getElementById('id_btn_rest')) opener.document.getElementById('id_btn_rest').disabled = false;
	if(opener.document.getElementById('id_btn_erase_disc')) opener.document.getElementById('id_btn_erase_disc').disabled = false;
	this.close();
}
//=======================================================//
// When close window
//=======================================================//
window.onbeforeunload = on_unload_cancel;
function on_unload_cancel(){
	// Check progress
	if(init.doing) alert("<?php echo lang_get('schedule_msg_35')?>");
	fin();
	//if( confirm("Closing window") ) fin();
	//pause(1000);
}
//function pause( iMilliseconds )
//{
//    var sDialogScript = 'window.setTimeout( function () { window.close(); }, ' + iMilliseconds + ');';
//    window.showModalDialog('javascript:document.writeln ("<script>' + sDialogScript + '<' + '/script>")');
//}
//-->
</script>

<style type="text/css">
<!--
.red_02 {
	font-family : "verdana";
	font-size : 9pt;
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
//-->
</style>
</head>

<body style="margin:0;">
<table width="420px" height="250px" border="0" cellspacing="0" cellpadding="0" valign="top">
  <tr>
    <td width="100%" height="54" align="center" valign="middle" background="../images/popup/txt_popup_bg_01.gif">
     <span class="popup_text"><?php echo lang_get('schedule_backup_11')?></span>
    </td>
  </tr>
  <tr>
    <td height="157" valign="top" align="left" style="padding:24 0 0 25px">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td height="69" valign="middle" style="padding:0 10px 0 10px;">
        <font class="red_02"><?php echo lang_get('schedule_msg_29')?></font>
        <br/>
        <br/>
        <span id='phpmsg1' class="line01" >&nbsp;</span><br/>
        <span id='phpmsg2' class="line01" >&nbsp;</span><br/>
        </td>
      </tr>
      <tr><td height="15px"></td></tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
            <td width="370" height="23" >
            	<!--<div id="idProg_bar" style="width:100%;visibility:hidden;">
            	<img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23" />
            	</div>-->
            	<div id="progValue" align="center" class="prog" style="position:relative;left:10px;visibility:hidden;width:300px;color:#925051;">0 %</div>
            </td>
            </tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
  <tr><td height="10"></td></tr>
	<tr>
	<td align="center" style="width:100%;padding:0 20 0 0px">
		<div id='id_div_conf' style='width:100%;align:center;visibility:hidden;'>
		<input id="id_btn_conf" type="image" onclick='fin();' src="../images/btn/btn_confirm.gif" border="0" />
		</div>
	</td>
	</tr>
</table>


</body>
</html>


<script type="text/javascript">
<!--

init.all();
//-->
</script>