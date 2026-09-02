<!--     Summary For Z-index         -->
<!-- Gray Background : z-index : 150 -->
<!-- Restart Alert   : z-index : 250 -->
<!-- While Restart   : z-index : 300 -->

<!-- Jongmin / Min-->
<!-- Layer for Disable Background -->
<div id='idDisableBackground' style="position:absolute;width:100%;height:100%;top:0px;left:0px;z-index:150;border:none;background-color:#000; opacity:0.6;moz-opacity:0.6;filter:alpha(opacity=60); display:none">
</div>


<!-- Layer for Restart -->
<div id="idPopPowerRestart" style="position:absolute;left:50%;top:50%;width:400px;height:180px;z-index:250;margin-left:-200px;margin-top:-90px;background-color:#fff;visibility:hidden;'">
		<table border="0" cellspacing="0" cellpadding="0" width="400px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_restart')?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;" width="80px" height="95px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
				  <td style="vertical-align:middle;padding-left:10px;" width="320px">
				  	<div class="red_text_9" id="idPowerRestart"><?php echo lang_get('power_shutdown_2')?></div>
				  	
				  </td>
				</tr>
				<tr>
					<td colspan="2" height="40px" align="center" style="vertical-align:top;">
									<img src="../images/btn/btn_ok.gif" border="0" onclick='agree_power_restart();' class="buttons"/>
									<img src="../images/btn/btn_cancel.gif" border="0" onclick='cancle_power_restart();' class="buttons"/>
					</td>
				</tr>
		</table>
</div>

<!-- After Start Restarting...-->
<div id="restart_msg_box" style="position:absolute;left:50%;top:50%;width:500px;height:180px;z-index:300;margin-left:-250px;margin-top:-90px;background-color:#fff;visibility:hidden">
		<table border="0" cellspacing="0" cellpadding="0" width="500px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_restart')?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;" width="80px" height="95px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
				  <td style="vertical-align:middle;padding-left:10px;" width="420px">
				  	<p class="red_text_9"><?php echo lang_get('power_shutdown_4') ?></p>
				  	<p class="red_text_9"><?php echo lang_get('power_shutdown_5') ?></p>	
				  </td>
				</tr>
				<tr>
					<td colspan="2" height="40px" align="center" style="vertical-align:top;"><a href="../login/login.php"><img src="../images/btn/btn_connect.gif" /></a></td>
				</tr>
		</table>
</div>



