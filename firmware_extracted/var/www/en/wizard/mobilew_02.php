<!-- Code Optimized : 2008/11/13 -->

<div id="mobilew_step2" style="display:none">
	<form name="cms_sch">
		<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table">
			<tr>
				  <td height="40" colspan="4" ></td>
		  </tr>
			 <tr>
					  <td height="50" valign="top" colspan="4" ><img src="../images/headtitle/htit_mobile.gif" /></td>
		  </tr>
									<tr>
												  
								                              <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_01.gif" name="step_01" border="0"></td>
								                              <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_02_r.gif" name="step_02" border="0"></td>
								                              <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_03.gif" name="m_step_03" border="0"></td>
								                              <td width="430"  background="../images/wizard/tab_line.gif">&nbsp;</td>
								  </tr>
		  <tr><td height="20"></td></tr>
		  <tr>	
		  		<td colspan="4">
		  			<table><tr>		
		  			<td width="91"><img src="../images/icon/img_one_01.gif" width="91" height="83"></td>
            <td valign="top" class="red_s2" style="padding:5 0 0 0px"><img src="../images/wizard/mobile_sync_step2.gif"></td>
		  		  </tr></table>
		  		</td>
		  </tr>			

		</table>  					 				 
		
		<table style="display:block;" width="670" cellspacing="0" cellpadding="0" class="basicTable">
		  <tr>
		  		 <td class="header" colspan="2"><?php echo lang_get('usb_sync_10'); ?></td>
		  </tr>
		  <!-- Name : Start -->
		  <tr>
			   <td class="firstCol_250"><?php echo lang_get('common_name'); ?></td>
			   <td class="otherCol_420">
			     <input type="hidden" class="inputtext" id="cms_user"/>
		  	   <input type="text"   class="inputtext" id="cms_name" onblur="FormCheck('cms_name');" size="49"/></td>
		 	</tr>
		 	<!-- Name : End -->
		
			<!-- Description : Start -->
			<tr>
				<td class="firstCol_250"><?php echo lang_get('user_list_3'); ?></td>
		    <td class="otherCol_420">
		       <input type="text" class="inputtext" id="cms_description" onblur="FormCheck('cms_description');" size="49" /></td>
			</tr>
			<!-- Description : End -->

			<!-- Control No. : Start -->
		  <tr>
		      <td class="firstCol_250"><?php echo lang_get('usb_sync_4'); ?></td>
		      <td class="otherCol_420">
		          <input type="text" class="inputtext" id="cms_ctrlnum" size="49" disabled value="<?php echo lang_get('common_loading'); ?>"/></td>
		   </tr>
			<!-- Control No. : End -->

		    <!-- Source : Start -->                                      
				<tr>
						<td class="firstCol_250"><?php echo lang_get('common_source'); ?></td>
						<td class="otherCol_420"><span id='usb_select_box'><?php echo lang_get('common_loading'); ?></span></td>
				</tr>
				<!-- Source : End -->

		
		    <!-- Destination path : Start -->
				<tr>
					<td class="firstCol_250"><?php echo lang_get('usb_sync_5'); ?></td>
					<td class="otherCol_420">
						<table><tr>
						<td><a href="javascript:void(0)" onclick="popup_file_browser('cms_dest');"><img src="../images/btn/btn_root.gif" border="0"></a></td>
					  <td><input type="text" class="inputtext" id="cms_dest" size="42" disabled value="<?php echo lang_get('common_loading'); ?>"/></td></tr></table>
					</td>	
				</tr>
				<!-- Destination path : End -->
	                                         
		
				<tr>
						<td class="subHeader" colspan="2"><?php echo lang_get('schedule_backup_1'); ?></td>
				</tr>
		

		    <!-- Cycle : Start -->
		    <tr>
		        <td class="firstCol_250"><?php echo lang_get('common_cycle'); ?></td>
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
		    <!-- Cycle : End -->

			   
			    <!-- Time : Start -->
		  	<tr>
		 				<td class="firstCol_250"><?php echo lang_get('common_time'); ?></td>
		 				<td class="otherCol_420">
		          <span id="cms_time_hour"></span><?php echo lang_get('common_hour_1'); ?>
		          <span id="cms_time_min"></span><?php echo lang_get('common_minute_1'); ?>
		        </td>
		   </tr>
		   <!-- Time : End -->

				<tr>
				
				 <td class="firstCol_250"><?php echo lang_get('usb_sync_6'); ?></td>
				 <td class="otherCol_420">
				         <input type="checkbox" id="cms_usbatt" value="usbatt"><?php echo lang_get('usb_sync_6'); ?></td>
				</tr>
		
                                          
		
				<tr>
				
				 <td class="firstCol_250"><?php echo lang_get('usb_sync_7'); ?></td>
				 <td class="otherCol_420">
				     <input type="radio" name="name_crtfld" id="cms_crtfld_filedate" value="file" /><?php echo lang_get('usb_sync_8'); ?>
				     <input type="radio" name="name_crtfld" id="cms_crtfld_backupdate" value="backup" checked/><?php echo lang_get('usb_sync_9'); ?>
				  </td>
				</tr>
                                          
				
				<tr>
				
				 <td class="firstCol_250"><?php echo lang_get('schedule_backup_3'); ?></td>
				 <td class="otherCol_420">
				 
				    <input type="radio" name="name_direc" id="cms_direc_incre" value="incre" onclick="check_incremental();" checked/><label for="cms_direc_incre">Incremental</label><br />
				    <input type="radio" name="name_direc" id="cms_direc_full" value="full" onclick="check_incremental();"/><label for="cms_direc_full">Full</label><br />
				    <input type="radio" name="name_direc" id="cms_direc_copy" value="copy" onclick="check_incremental();"/><label for="cms_direc_copy">Copy</label><br />
				    <input type="radio" name="name_direc" id="cms_direc_one" value="one" onclick="check_incremental();"/><label for="cms_direc_one">One</label>
				  </td>
				</tr>                                                                                                          
		</table>
		
		<table style="display:block;" width="670" cellspacing="0" cellpadding="0">
				<tr>
					<td class="header" width="640">
						<?php echo lang_get('schedule_backup_10'); ?>
					</td>
					<td align="right" class="header" style="padding-right:20px">
						<div id="idAdvSetOpen" style='display:block;'>
							<img src="../images/btn/btn_open.gif" border="0" onclick="open_adv_set();" class="buttons">
						</div>
						<div id="idAdvSetClose" style='display:none;'>
							<img src="../images/btn/btn_close.gif" border="0"  onclick="close_adv_set();" class="buttons">
						</div>
					</td>
				</tr>
		</table>
		
		<table style="display:none;" width="670" cellspacing="0" cellpadding="0" id="idTableAdvSet">
		
		
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
		
		<table width="670" border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="display:block;margin-top:30px;">
			<tr>
				<td width="300px">
			  	<a href="javascript:void(0);" onClick="showTable('mobilew_step1')"><img src="../images/btn/btn_back.gif" border="0" /></a>
			  </td>
				<td width="370px" align="right">
					<a href="./mobilew_00.php"><img src="../images/btn/btn_cancel.gif" border="0"></a>
				
					<a href="javascript:void(0)" onclick="save_task();"><img src="../images/btn/btn_save.gif"border="0"></a>
				</td>
		
			</tr>
		</table>
	</form>
</div>
