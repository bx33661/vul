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
    <td width="420" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif">
    <!--<img src="../images/popup/txt_popup_data_copy.gif" width="145" height="35" border="0">-->
    <span class="popup_text"><?php echo lang_get('storing_copy_4')?></span>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>-->
    </td>
  </tr>
  <tr>
    <td height="157" valign="top" style="padding:24 0 0 25px"><table width="370" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="69" valign="center">
        <div id="popup_msg" style="font-weight:bolder;"><?php echo lang_get('storing_msg_10')?></div>
        <!--<img src="../images/Burn/txt_burn.gif" width="191" height="47">-->
        </td>
      </tr>
      <tr>
        <td><!-- Progress bar -->
        <table width="370" border="0" cellspacing="0" cellpadding="0">
            
            <tr><td width="370" height="23" background="../images/Burn/img_burn_bg_middle.gif">
            	<div id="idProg_bar" style="visibility:hidden;"><img id="prog" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/></div>
            	<div id="progValue" align="center" style="position:absolute;top:150;left:25;width:370px;"><strong>0 %</strong></div>
            </td></tr>
        </table>
        <!-- Progress bar : end --></td>
      </tr>
    </table></td>
  </tr>
	<tr>
	<td align="center" style="padding:0 20 0 0px">
		<div id='idButtonBurnNext' style='visibility:hidden;'>
			<a href="javascript:void(0)" onclick='store.cancel();'><img src="../images/btn/btn_cancel.gif"  height="22" border="0" /></a>
		</div>
	</td>
	</tr>
</table>
</body>
</html>

<script type='text/javascript' src='../js/storing_data_prog.js.php' charset='utf-8'></script>
<script>
<!--

store.init();


//-->
</script>
