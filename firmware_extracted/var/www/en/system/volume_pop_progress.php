<div id='idMigProgress'  style="width:670px; height:260px; display:none" >
	<table align="center" width="540" cellspacing="0px" cellpadding="0px" >
	  <tr>
	    <td width="540" height="54" background="../images/popup/txt_popup_bg02.gif">
	    <span class="popup_text" style="padding-left:20px;"><?php echo lang_get('volume_title_4')?></span>	
	    </td>
	  </tr>
	  <tr>
	    <td height="130" valign="top" style="padding:24 0 0 25px">
	      <table width="488" border="0" cellspacing="0" cellpadding="0">
	        <tr>
	          <td height="50" class="red_s2">
	          	<div id="idTxtMigProgress" align="center" width="488"><?php echo lang_get('volume_msg_28')?>
	          	</div>
	          </td>
	        </tr>
	        <tr>
	          <td><!-- 진행속도 -->
	            <table width="488" border="0" cellspacing="0" cellpadding="0" id="sp_table">
	              	<tr>
	              		<td width="488" height="23" background="../images/Burn/img_burn_bg_middle.gif">
	            			<div id="idMigProg_bar" style="display:none;">
	            				<img id="idMigProg" src="../images/Burn/img_burn_bar_middle.gif" width="0px" height="23"/>
	            			</div>
	            			
	            		</td>
	            	</tr>
	            	               
	            </table>
	            <!-- 진행속도-->
	            <strong><div id="idProgressPer" align="center" style="position:absolute;top:295;left:386;width:488px;">
	            			&nbsp;</div></strong>
	          </td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	  <tr>
	    <td width="540" align="center">
	      <a href="javascript:void(0)">
	      	<img border="0" onclick="migration_confirm();" id="idMigConfirm" style="display:none;" src="../images/btn/btn_ok.gif"/>
	      </a>
	    </td>
	  </tr>
	  <tr><td height="20"></td></tr>
	</table>
</div>