<!-- Jongmin / MW -->
<!-- Layer for Disable Background -->
<div id='idDisableBackground' style="position:absolute;width:100%;height:100%;top:0px;left:0px;z-index:150;border:none;background-color:#000; opacity:0.6;moz-opacity:0.6;filter:alpha(opacity=60); display:none">
</div>

<!-- Layer for System Initialization -->
<div id="idPopSystemInit" style="position:absolute;left:50%;top:50%;width:400px;height:180px;z-index:250;margin-left:-200px;margin-top:-90px;background-color:#fff;visibility:hidden;'">
		<table border="0" cellspacing="0" cellpadding="0" width="400px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('firmware_init_1')?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;" width="80px" height="95px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
				  <td style="vertical-align:middle;padding-left:10px;" width="320px">
				  	<p class="red_text_9"><?php echo lang_get('firmware_msg_9')?></p>
				  	<p class="red_text_9"><?php echo lang_get('schedule_msg_29_1')?></p>	
				  </td>
				</tr>
				<tr>
					<td colspan="2" height="40px" align="center" style="vertical-align:top;">
									<img src="../images/btn/btn_ok.gif" border="0" onclick="agree_system_init();" class="buttons"/>
									<img src="../images/btn/btn_cancel.gif" border="0" onclick="cancel_system_init();" class="buttons"/>
					</td>
				</tr>
		</table>
</div>

<!-- Layer for Firmware Upgrade -->
<div id="l_copy" style="position:absolute;left:50%;top:50%;width:600px;height:170px;z-index:250;margin-left:-300px;margin-top:-85px;background-color:#fff;display:none;'">
		<table border="0" cellspacing="0" cellpadding="0" width="600px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_upgrade')?></td>
				</tr>
				<tr>
				  <td width="80px" style="vertical-align:middle;" align="center"><img src="../images/Burn/file_box_loading.gif" id="l_copy_img"/></td>
				  <td style="vertical-align:middle;height:125px;padding-left:10px;" width="520px">
				  	<p class="red_text_9" id="popup_text_01">- <?php echo lang_get('firmware_msg_7')?></p>
				  	<p class="red_text_9" id="popup_text_02">- <?php echo lang_get('power_shutdown_8')?></p>
				  </td>
				</tr>
		</table>
</div>

<!-- Layer for Firmware Initialization -->
<div id="layer_init" style="position:absolute;left:50%;top:50%;width:600px;height:170px;z-index:250;margin-left:-300px;margin-top:-85px;background-color:#fff;display:none;'">
		<table border="0" cellspacing="0" cellpadding="0" width="600px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('firmware_init_1')?></td>
				</tr>
				<tr>
				  <td width="80px" style="vertical-align:middle;" align="center"><img src="../images/Burn/file_box_loading.gif" id="layer_init_img"/></td>
				  <td style="vertical-align:middle;height:125px;padding-left:10px;" width="520px">
				  	<p class="red_text_9">- <?php echo lang_get('firmware_upgrade_8')?></p>
				  	<p class="red_text_9">- <?php echo lang_get('restart_after_init')?></p>
				  </td>
				</tr>
		</table>
</div>




<!-- park94 --> 
<!-- Layer for copy -->
<!--
<div id="l_copy" style='z-index:201;display:none;position:absolute;top:350px;left:390px;width:455px;height:75px;background-color:white;border:2px solid #EB6464;padding:15px 0 15px;'>

<br />
<div id="l_copy_txt" style="font:12px verdana;position:relative;left:10px;top:6px;"></div>
</div>-->
<!-- Test button : layer open -->
<!--<input type="button" value="layer" onclick="layer.open();" />-->
<!-- Layer ends -->