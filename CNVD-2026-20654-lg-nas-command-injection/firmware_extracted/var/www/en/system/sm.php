<?php	 include "../inc/top.php";  ?>
	
  <!-- Require jQuery : Removed because of top include-->
  <!--<script src="../js/jquery.min.js" type="text/javascript"></script>-->

  <!-- Require jQuery UI -->
  <script src="../js/jquery-ui-1.7.1.custom.js" type="text/javascript"></script>

  <script src="../js/selectiveMirror.js.php" type="text/javascript"></script>

	<!--Input field ID for selected folder path in the child window-->
	<input type="hidden" id="idInputFieldId" value="" />
	<input type="hidden" id="idPathMode" value="burn" />
	
<!-- Middle Part :: Start -->
<tr>
  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
			<!-- Left Navigation :: Start-->
			<td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
			<!-- Left Navigation :: End -->
			
			<!-- Right Content :: Start -->
			<td width="100%" valign="top">
  			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  				<tr>
    				<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
  				</tr>
  				
  				<tr>
  					<td style="padding:0 0 0 50px">
									
									
									<!-- 1. Head Title -->
									<table width="670" border="0" cellspacing="0" cellpadding="0" >
								    <tr>
								      <td height="40"></td>
								    </tr>
								    <tr>
								      <td height="50" valign="top"><img src="../images/headtitle/htit_sm.gif"/></td>
								    </tr>
								  </table>
									
									<div id="listDiv">
											<!-- 2. Enable / Disable -->
											<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
												<tr>
													<td class="header">Selective Mirror</td>
													<td class="header"><input type="radio" name="sm" id="smEnable" value="on" onclick=""/><label for="smEnable"><?php echo lang_get('common_enable'); ?></label>
													                 <input type="radio" name="sm" id="smDisable" value="off" onclick=""/><label for="smDisable"><?php echo lang_get('common_disable'); ?></label>
													</td>  
												</tr>    		
											<table>
												
											<table width="670" border="0" cellspacing="0" cellpadding="0">         
												<tr>
													<td style="width:30px;padding-left:10px;background-color:#5d5d5d;"><input type='checkbox' id='smChk'></td>
													<td class="header" style="width:310px" ><?php echo lang_get("common_source")?></td>
													<td class="header" style="width:330px" ><?php echo lang_get("usb_sync_5")?></td>
												</tr>
											</table>
											<div id="smList" style="overflow-y:scroll; width:668px; height:320px; border:1px solid #bcbcbc;margin-bottom:20px;"></div>
		                    
						       		<!-- 3. Buttons --> 
					       			<table width="670" cellspacing="0" cellpadding="0" id="listButtons">
					       		 		<tr>
					       		   		<td align="right"><img src="../images/btn/btn_add.gif"  border="0" id="btnAddDiv" class="buttons"/>
					       		   											<img src="../images/btn/btn_edit.gif"  border="0" id="btnEditDiv" class="buttons"/>		
					                                  <img src="../images/btn/btn_delete.gif"  border="0" id="btnDelDiv" class="buttons"/>
					                </td>
					          			
					              </tr>
					            </table>	
									</div>
							  
									<!-- Div for Add :: Start -->
									<div id="addDiv" style="display:none;">
										<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
												<tr>
													<td width="335px" class="header" colspan="2"><?php echo lang_get("add_sm_list")?></td>
												</tr>
												<tr>
													<td class="firstCol_250"><?php echo lang_get("common_source")?></td>
													<td class="otherCol_420">
															<table cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="40"><img border="0" style="cursor: pointer;" id="btnSrc" src="../images/btn/btn_root.gif"/></td>
																		<td><input type="text" name="srcPath" id="srcPath" class="inputtext" style="width:350px" disabled /></td>
																	</tr>
															</table>
													</td>															
												</tr>
												<tr>
													<td class="firstCol_250"><?php echo lang_get("usb_sync_5")?></td>
													<td class="otherCol_420">
														<table cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="40"><img border="0" style="cursor: pointer;" id="btnDes" src="../images/btn/btn_root.gif"/></td>
																		<td><input type="text" name="desPath" id="desPath"  class="inputtext" style="width:350px" disabled /></td>
																	</tr>
															</table>
													</td>
												</tr>
										</table>
										<!-- Buttons -->
										<table width="670" cellspacing="0" cellpadding="0">
				       		 		<tr>
				       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" class="buttons" id="btnSaveApply"/>
				       		   			                <img src="../images/btn/btn_cancel.gif"  border="0" class="buttons" id="btnCancelApply"/>
				                                  
				                </td>
				          			
				              </tr>
				            </table>
									</div>
									<!-- Div for Add :: End -->
									
									<!-- Div for Edit :: Start -->
									<div id="editDiv" style="display:none;">
										<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
												<tr>
													<td width="335px" class="header" colspan="2"><?php echo lang_get("edit_sm_list")?></td>
												</tr>
												<tr>
													<td class="firstCol_250"><?php echo lang_get("common_source")?></td>
													<td class="otherCol_420">
															<table cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="40"><img border="0" style="cursor: pointer;" id="btnSrcEdit" src="../images/btn/btn_root.gif"/></td>
																		<td><input type="text" name="srcPathEdit" id="srcPathEdit" class="inputtext" style="width:350px" disabled />
																				<input type='hidden' id='srcPathEditOld'>			
																		</td>
																	</tr>
															</table>
													</td>															
												</tr>
												<tr>
													<td class="firstCol_250"><?php echo lang_get("usb_sync_5")?></td>
													<td class="otherCol_420">
														<table cellspacing="0" cellpadding="0" border="0">
																	<tr>
																		<td width="40"><img border="0" style="cursor: pointer;" id="btnDesEdit" src="../images/btn/btn_root.gif"/></td>
																		<td><input type="text" name="desPathEdit" id="desPathEdit"  class="inputtext" style="width:350px" disabled />
																				<input type='hidden' id='desPathEditOld'>			
																		</td>
																	</tr>
															</table>
													</td>
												</tr>
										</table>
										<!-- Buttons -->
										<table width="670" cellspacing="0" cellpadding="0">
				       		 		<tr>
				       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" class="buttons" id="btnEditSaveApply"/>
				       		   			                <img src="../images/btn/btn_cancel.gif"  border="0" class="buttons" id="btnEditCancelApply"/>
				                                  
				                </td>
				          			
				              </tr>
				            </table>
									</div>
									<!-- Div for Edit :: Start -->
									
								  <!-- Page Loading Layer -->
									<div id="page_loading" align="center" style="position:absolute;left:450px;top:330px;width:300px;height:100px;display:none;background-color:#fff;">
			                                
			                            <table border="0" cellspacing="0" cellpadding="0" width="300px">	
																			<tr>
																				<td colspan="2" style="backgRound-color:#742625;color:#fff;height:25px;font-size:15px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_loading')?></td>
																			</tr>
																			<tr>
																			  <td style="border:1px solid #5d5d5d;border-right:none;height:75px;width:100px;" align="center">
																			  	<img Id="img_page_loading" src="../images/Burn/file_box_loading.gif"/>
																			  </td>
																			  <td style="border:1px solid #5d5d5d;border-left:none;height:75px;width:200px;"><?php echo lang_get('common_wait')?></td>
																			  
																			</tr>
																	</table>
			         		</div>    
    



          


            </td>
          </tr>
        </table>
      </td>
      <!-- Right Content :: End --> 
    </tr>
  </table></td>
</tr>
<!-- Middle Part :: End -->          

<?php	 include "../inc/bottom.php";  ?>