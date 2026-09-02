<!-- Layer for Restart -->
<div id="idPopPowerShutdown" style="position:absolute;left:50%;top:50%;width:400px;height:180px;z-index:250;margin-left:-200px;margin-top:-90px;background-color:#fff;visibility:hidden;'">
		<table border="0" cellspacing="0" cellpadding="0" width="400px">	
				<tr>
					<td colspan="2" style="background-color:#742625;color:#fff;height:45px;font-size:20px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_shutdown')?></td>
				</tr>
				<tr>
					<td style="vertical-align:middle;" width="80px" height="95px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
				  <td style="vertical-align:middle;padding-left:10px;" width="320px">
				  	<div class="red_text_9" id="idPowerShutdown"><?php echo lang_get('power_shutdown_3')?></div>
				  	
				  </td>
				</tr>
				<tr>
					<td colspan="2" height="40px" align="center" style="vertical-align:top;">
									<img src="../images/btn/btn_ok.gif" border="0" onclick='agree_power_shutdown();' class="buttons"/>
								<img src="../images/btn/btn_cancel.gif" border="0" onclick='cancle_power_shutdown();' class="buttons"/>
					</td>
				</tr>
		</table>
</div>