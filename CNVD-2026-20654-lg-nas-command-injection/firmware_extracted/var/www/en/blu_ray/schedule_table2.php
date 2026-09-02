<table id="idTable2" style='display:none;'>
	<tr>
		<td valign="top">
			<div id="idSTitleCreate" style="display:block;"><img src="../images/subtitle/stit_create_backup.gif"></div>
			<div id="idSTitleEdit" style="display:block;"><img src="../images/subtitle/stit_edit_backup.gif"/></div>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td valign="top">
				
				<!-- First Table -->
				<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
             					<tr>
               					<td class="header" colspan="2"><?php echo lang_get('schedule_backup_0')?></td>
										  </tr>
										  <tr>
												<td class="firstCol_250"><?php echo lang_get('common_name')?> *</td>
												<td class="otherCol_420"><input name="textfield" type="text" class="inputtext" id="cms_name" onblur="FormCheck('cms_name');" size="49"/></td>
											</tr>
											<tr>
												<td class="firstCol_250"><?php echo lang_get('user_list_3')?> *</td>
												<td class="otherCol_420"><input name="textfield2" type="text" class="inputtext" id="cms_description" size="49" onblur="FormCheck('cms_description');"/></td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('common_source')?> *</td>
												<td class="otherCol_420">
														<table border="0" cellspacing="0px" cellpadding="0px">
										      		<tr>
										      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('cms_source');" style="cursor:pointer;"></td>
										      			<td><input name="cms_source" type="text" class="inputtext" id="cms_source" size="40" value="" disabled /></td>
										      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
										      		</tr>
										      	</table>
			      						</td>
											</tr>
											
													
											<tr>
													<td class="subHeader" colspan="2"><?php echo lang_get('schedule_backup_1'); ?></td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('common_cycle')?></td>
												<td class="otherCol_420">		  					 
														<table border="0" cellspacing="0" cellpadding="0" ><tr><td>
										  					 <select class="selectbox_80" id='cms_sch_cycle' onChange="setDetail();">
																	   <option value="none"><?php echo lang_get('common_none'); ?></option>
																	   <option value="daily"><?php echo lang_get('common_daily'); ?></option>
																	   <option value="weekly"><?php echo lang_get('common_weekly'); ?></option>
																	   <option value="monthly"><?php echo lang_get('common_monthly'); ?></option>
															   </select></td>
													   <td>
														   	<span id="cms_date" style="display:none"></span>
														   	<span id="cms_day"  style="display:none">
													 		  	   <select class="selectbox_80" id='cms_sch_week'>
																		   <option value="sun"><?php echo lang_get('common_day_7'); ?></option>
																		   <option value="mon"><?php echo lang_get('common_day_1'); ?></option>
																		   <option value="tue"><?php echo lang_get('common_day_2'); ?></option>
																		   <option value="wed"><?php echo lang_get('common_day_3'); ?></option>
																		   <option value="thu"><?php echo lang_get('common_day_4'); ?></option>
																		   <option value="fri"><?php echo lang_get('common_day_5'); ?></option>
																		   <option value="sat"><?php echo lang_get('common_day_6'); ?></option>
														  	 		</select>
															  </span>
												  	</td>
													</tr>
												</table>
											</td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('common_time')?></td>
												<td class="otherCol_420">
													 <span id="cms_time_hour"></span><?php echo lang_get('common_hour_1'); ?>
		          						 <span id="cms_time_min"></span><?php echo lang_get('common_minute_1'); ?>
												</td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('schedule_backup_3')?></td>
												<td class="otherCol_420">
														<input type="radio" name="radio" id="cms_direc_incre" value="radio" />Incremental
														<input type="radio" name="radio" id="cms_direc_full" value="radio" />Full
												</td>
											</tr>
									
        </table>	
				
				<!-- Advanced Head Table -->
				<table style="display:block;" width="670" cellspacing="0" cellpadding="0">
				<tr>
					<td class="header" width="640">
						<?php echo lang_get('schedule_backup_10'); ?>
					</td>
					<td align="right" class="header" style="padding-right:20px">
						<div id="idBtnOpenAdvSet" style='display:block;'>
							<a href="javascript:void(0)" onclick="open_adv_setting();"><img src="../images/btn/btn_open.gif" border="0"></a>
						</div>
						<div id="idBtnCloseAdvSet" style='display:none;'>
							<a href="javascript:void(0)" onclick="close_adv_setting();"><img src="../images/btn/btn_close.gif" border="0"></a>
						</div>
					</td>
				</tr>
		    </table>	
		    
		    <!-- Advanced Table -->
    		<table style="display:none;" width="670" cellspacing="0" cellpadding="0" id="idAdvSet">


						<tr>
						 <td class="firstCol_250"><?php echo lang_get('schedule_backup_6'); ?></td>
						 <td class="otherCol_420">
						 			<table cellspacing="0" cellpadding="0">
						 					<tr style="height:30px">
						 						<td><img src="../images/btn/btn_format.gif" border="0"></td>
						  					<td style="padding-left:20px"><input type="text" class="inputtext" id="cms_filter_include" size="40" /></td>
											</tr>
									 		<tr style="height:20px">
												<td colspan="2"><input type="checkbox" id="cms_inext_pic" onClick='on_inext_pic()'><?php echo lang_get('schedule_backup_8'); ?></td>                                                    
									  	</tr>
											<tr style="height:20px">
												<td colspan="2"><input type="checkbox" id="cms_inext_doc" onClick='on_inext_doc()'><?php echo lang_get('schedule_backup_9'); ?></td>                                                    
											</tr>
									</table>
							</td>
						</tr>
								
						<tr>
						 <td class="firstCol_250"><?php echo lang_get('schedule_backup_7'); ?></td>
						 <td class="otherCol_420">
										<table cellspacing="0" cellpadding="0"><tr> 
												<td><img src="../images/btn/btn_format.gif" border="0"></td>
                    		<td style="padding-left:20px"><input type="text" class="inputtext" id="cms_filter_exclude" size="40" /></td>
										</tr></table>
							</td>
						</tr>
						
						
				</table>
		    
		    <!-- Tip Table -->
		    <table width="670px" cellspacing="0" cellpadding="0" style="margin-top:20px;">
		    	<tr>
		    		<td width="30px"><img src="../images/icon/tip.gif" border="0"></td>
		    		<td style="font-weight:bold"><?php echo lang_get('schedule_msg_2')?>
		    																 <span style="color:red">BD-RE / BD-R / DVD+-RW / DVD-RAM</span>
		    																 <?php echo lang_get('schedule_msg_2_1')?></td>
		    	</tr>
		    </table>
		    
		    <!-- Buttons -->
		    
				<table width="670px" border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="display:block;margin-top:10px;">
					<tr>
						<td width="670px" align="right">
								<img src="../images/btn/btn_save.gif" border="0" onclick='save_task();' class="buttons">
								<img src="../images/btn/btn_cancel.gif" border="0" onclick='create_cancel();' class="buttons">
						</td>
					</tr>
				</table>

   	</td>
 </tr>
</table>
