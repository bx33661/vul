<!-- Add Backup Schedule / Edit Backup Schedule -->
<div id="idTableSetting" style="display:none;" >
	
		<!-- Subtitle -->
		<table width="670px" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px">
				<tr>
				<td valign="top">
						<div id="idSTitleCreate" style="display:block;"><img src="../images/subtitle/stit_create_backup.gif" /></div>
						<div id="idSTitleEdit" style="display:block;"><img src="../images/subtitle/stit_edit_backup.gif" /></div>
				</td>
				</tr>
		</table>
		
		<!-- Contents -->
		<table width="670px" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px" class="basicTable">		
		  <tr>
		  		 <td class="header" colspan="2"><?php echo lang_get('usb_sync_10'); ?></td>
		  </tr>
		  <!-- Name : Start -->
		  <tr>
			   <td class="firstCol_250"><?php echo lang_get('common_name'); ?></td>
			   <td class="otherCol_420">
			    <input name="textfield" type="hidden" class="inputtext" id="cms_user" size="49"/>
		      <input name="textfield" type="text" class="inputtext" id="cms_name" onblur="FormCheck('cms_name');" size="49"/></td>
		 	</tr>
		 	<!-- Name : End -->

			<!-- Description : Start -->
			<tr>
				<td class="firstCol_250"><?php echo lang_get('user_list_3'); ?></td>
		    <td class="otherCol_420">
		        <input name="textfield2" type="text" class="inputtext" id="cms_description" onblur="FormCheck('cms_description');" size="49" /></td>
			</tr>
			<!-- Description : End -->

			<!-- Control No. : Start -->
		  <tr>
		      <td class="firstCol_250"><?php echo lang_get('usb_sync_4'); ?></td>
		      <td class="otherCol_420">
		           <input name="textfield2" type="text" class="inputtext" id="cms_ctrlnum" size="49" disabled /></td>
		   </tr>
			<!-- Control No. : End -->

	    <!-- Source : Start -->                                                            
			<!--<tr>
					<td class="firstCol_250"><?php echo lang_get('common_source'); ?>
					</td>
					<td class="otherCol_420" id="idSrcpath_01">
					<div id='usb_select_box'></div>
					</td>
			</tr>-->
			<!-- Source : End -->		
			
			<!-- Destination path : Start -->
			<tr>
				<td class="firstCol_250"><?php echo lang_get('usb_sync_5'); ?></td>
				<td class="otherCol_420">
					<table><tr>
					<td><a href="javascript:void(0)" onclick="popup_file_browser('cms_dest');"><img src="../images/btn/btn_root.gif" border="0"></a></td>
				  <td><input type="text" class="inputtext" id="cms_dest" size="40" disabled value="<?php echo lang_get('common_loading'); ?>"/></td></tr></table>
				</td>	
			</tr>
			<!-- Destination path : End -->
		       <!-- USB Auto Sync : start -->
				<tr>
				
				 <td class="firstCol_250"><?php echo lang_get('usb_sync_6'); ?></td>
				 <td class="otherCol_420">
				         <input type="checkbox" id="cms_usbatt" value="usbatt"><?php echo lang_get('usb_sync_6'); ?></td>
				</tr>
 		       <!-- USB Auto Sync : End -->
			<!-- Backup Method : Start -->
				<tr>
				 <td class="firstCol_250"><?php echo lang_get('schedule_backup_3'); ?></td>
				 <td class="otherCol_420">
				 
				    <input type="radio" name="name_direc" id="cms_direc_incre" value="incre" onclick="check_incremental();" checked/><label for="cms_direc_incre"><?php echo lang_get('schedule_backup_4'); ?></label><br />
				    <input type="radio" name="name_direc" id="cms_direc_full" value="full" onclick="check_incremental();"/><label for="cms_direc_full"><?php echo lang_get('schedule_backup_5'); ?></label><br />
				    <!--<input type="radio" name="name_direc" id="cms_direc_copy" value="copy" onclick="check_incremental();"/><label for="cms_direc_copy">Sync</label><br />-->
				  </td>
				</tr>     
			<!-- Backup Method : End -->



    </table>                           
		
		<!-- Advanced Tab Title : Start-->
		<table style="display:none;" width="670px" cellspacing="0" cellpadding="0">
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
		<!-- Advanced Tab Title : End-->
	
		<!-- Advanced Tab -->
		<table style="display:none;" width="670px" cellspacing="0" cellpadding="0" id="idTableAdvSet">
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
		 	<table cellspacing="0" cellpadding="0">
		 	<tr> 
		 		<td><img src="../images/btn/btn_format.gif" border="0"></td>
				<td style="padding-left:20px"><input type="text" class="inputtext" id="cms_filter_exclude" size="40" /></td>
			</tr>
			</table>
		</td>
		</tr>

		</table>
		
		<!-- Buttons : Start -->
		<table width="670px" border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="display:block;margin:20px 0 10 0px;">
			<tr >
				<td width="670px" align="right">
					<img src="../images/btn/btn_save.gif" border="0" onclick="save_task();" class="buttons">
					<img src="../images/btn/btn_cancel.gif" border="0" onclick="create_cancel();" class="buttons">
				</td>
			</tr>
		</table>
		<!-- Buttons : End -->
</div>
