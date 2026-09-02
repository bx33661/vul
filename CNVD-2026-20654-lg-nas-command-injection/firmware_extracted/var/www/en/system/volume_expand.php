<div id='idPopExpand' style="width:670px; height:260px;	margin-top:40px; display:none" >

			<table width="420" border="0" cellspacing="0" cellpadding="0" id="all_table" align="center">
			 	<tr>
			    	<td width="420" height="54" background="../images/popup/txt_popup_bg_01.gif">
			    		<span class="popup_text" style="padding-left:20px"><?php echo lang_get('volume_title_5')?></span>
			    	</td>
			    </tr>
				<tr>
					<td align="center" valign="top">
					<!-- 중앙 내용 시작 -->
						<table width="420" border="0" cellspacing="0" cellpadding="0">
			       			<tr>
								<td height="70" class="red_text_9" style="padding-left:20px">
									<?php echo lang_get('volume_msg_23')?> <br /><br /><span id='idExpVolume'>Vol1(500GB)</span> <?php echo lang_get('volume_msg_29')?> <span id='idExpVolumeToBe'>Vol1(1.4TB)</span> <?php echo lang_get('volume_msg_29_1')?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>	

			<!-- Buttons -->
			<table width="420" border="0" cellspacing="0" cellpadding="0" id="idBtTableExp" align="center" style="margin-top:20px">
				<tr>
					<td align="center">
							<img src="../images/btn/btn_ok.gif" border="0" onclick='expand_vol();' class="buttons"/>
							<img src="../images/btn/btn_cancel.gif" border="0" onclick='close_expand_vol();' class="buttons"/>
					</td>
				</tr>
			</table>
			<!-- 버튼끝-->
	

</div>
