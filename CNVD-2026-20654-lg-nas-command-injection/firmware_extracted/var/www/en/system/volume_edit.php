<div id='idPopEdit'	style="width:670px; height:260px;	margin-top:40px; display:none" >

<table align="center" width="540" height="260"	border="0" cellspacing="0" cellpadding="0" id="all_table">
	<tr>
		<td	width="540"	height="54"	background="../images/popup/txt_popup_bg02.gif">
				<!--<span class="popup_text" style="padding-left:20px"><?php echo lang_get('volume_title_3')?></span>-->
				<span class="popup_text" style="padding-left:20px"><?php echo lang_get('volume_title_add_remove'); ?></span>
		</td>
	</tr>	
	<tr id='idEditBayList' style='display:block'>
		<td	valign="top" style="padding:25 0 0 25px"><!-- 중앙 내용	시작 -->
		<table width="490" border="0"	cellspacing="0"	cellpadding="0"	id="network_table">	
			<tr>
				<td valign="top" class="red_s2" style="padding:0 0 10	0px"><?php echo lang_get('volume_msg_1')?></td>
			</tr>
			<tr>
				<td><!-- 테이블 영역 시작-->
				
				<!-- 타이틀	테이블 시작-->
						<table width="490" height="25" border="0"	cellspacing="0"	cellpadding="0"	id="title_table">
							<tr>
								<td width="120" class="header_center"><?php echo lang_get('volume_edit_2'); ?></td>	
								<!--<td width="200" class="header_center"><?php echo lang_get('status_bluray_2'); ?></td>-->
								<td width="380" class="header_center"><?php echo lang_get('common_status'); ?></td>
								<td width="80" class="header_center"><?php echo lang_get('volume_3'); ?></td>
							</tr>
						</table>
						<!-- 타이틀	테이블 끝-->
						
						<!-- 내용	1 시작 -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableEdtBay1" style="display:none">
							<tr>
								<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbEdtBay" align="center" onclick="check_edit(this)" value="0" id="idCbEdtBay1"></td>
						                <td class="firstCol" width="100px" style="text-align:left;border-left:none;"><div id="id_EdtNameBay1"></div></td>
						                <!--<td class="otherCol" width="200px"><div id="id_EdtModelBay1"></div></td>-->
						                <td class="otherCol" width="300px"><div id="id_EdtStateBay1"></div></td>
						                <td class="thirdCol" width="90px"><div id="id_EdtSizeBay1"></div></td>


          		</tr>
        		</table>					

						<!-- 내용 2 시작 -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableEdtBay2" style="display:none">
							<tr>
								<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbEdtBay" align="center" onclick="check_edit(this)" value="0" id="idCbEdtBay2"></td>
						                <td class="firstCol" width="100px" style="text-align:left;border-left:none;"><div id="id_EdtNameBay2"></div></td>
						                <!--<td class="otherCol" width="200px"><div id="id_EdtModelBay2"></div></td>-->
						                <td class="otherCol" width="300px"><div id="id_EdtStateBay2"></div></td>
						                <td class="thirdCol" width="90px"><div id="id_EdtSizeBay2"></div></td>
						                
          		</tr>
        		</table>
					

						
						
						<!-- Buttons -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px">
							<tr><td align="right">
									<table width="147" border="0"	cellspacing="0"	cellpadding="0"	id="bt_table2">	
											<tr>
												<td width="72"><a href="javascript:void(0)"><img src="../images/btn/btn_remove.gif" border="0" onclick='raid_remove_vol();' id="idVolEditBtnRemove" style='display:block'/></a></td>	
												<td width="8"></td>
												<td width="51"><a href="javascript:void(0)"><img src="../images/btn/btn_add.gif" border="0" onclick='add_pre_check();' id="idVolEditBtnAdd" style='display:block'/></a></td>	
												<td width="8"></td>
												<td width="66"><a href="javascript:void(0)"><img src="../images/btn/btn_cancel.gif" border="0" onclick='close_edit_vol();' id="idVolEditBtnRemove" style='display:block'/></a></td>
											</tr>
									</table>
						</td></tr>
				  	</table>
				<!-- 테이블	영역 끝-->
				</td>	
			</tr>
		</table>
			 </td>
	</tr>
		
	<tr id='idEditWaitRemove' align="center" valign="middle" style='display:none'>
		<td height="200"><div id='idEditWaitRemoveTxt' class="red_s2"></div></td>
	</tr>
	<tr id='idEditWaitAdd' align="center" valign="middle" style='display:none'>
		<td height="200"><div id='idEditWaitAddTxt' class="red_s2"></div></td>
	</tr>
</table>
<p>&nbsp;</p>

</div>
