<? include "../inc/top.php"; ?> <!-- top 자르는 영역 -->

<!--test-->
<!--<input type='button' value='test' onclick='esataFreeCap.get();' />-->


<!---------------------------------
// LGE NAS-SSS 
// By park94
// Javascript
----------------------------------->
<!--Debugging message-->
<script language='javascript' src='../js/debug.js' ></script>		
<!--Browser lib-->
<script language='javascript' src='../js/prototype.js'></script>
<script language='javascript' src='../js/esata_common.js'></script>
<script language='javascript' src="../js/esata_browsing_display.js.php"></script>
<script language='javascript' src="../js/esata_browsing_action.js.php"></script>
<!--ajax lib-->
<script language='javascript' src='../js/jslb_ajax.js.php' ></script>	
<!-- e-SATA -->
<script language="javascript1.2" src="../js/esata_burning.js" ></script>
<script language="javascript1.2" src="../js/esata.js.php" ></script>
<!----------------------------------->

<tr>
<!-- 전체center 영역 시작-->
	<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<!-- left 자르는 영역 -->
		<!-- left Navigation 영역 시작-->
			<td width="245" valign="top">
			<? include "../inc/left.php"; ?></td>
		<!-- left 끝-->
		
		
<td width="100%" valign="top"><!-- 사이즈 수정 -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%" height="7" background="../images/Top/utility_shadow.gif">
		</td>
    <!-- 사이즈 수정 -->
	</tr>
	<tr>
		<!-- 중앙내용 시작 -->
		<td width="100%" style="padding:0 0 0 50px">
			<table width="670" border="0" cellspacing="0" cellpadding="0" >
			<tr>
				<td height="40">
				</td>
			</tr>
			<tr>
				<td height="50" valign="top">
				<img src="../images/headtitle/htit_e.gif" width="88" height="31" />
				
				</td>
			</tr>
			
			<tr>
				<td height="30">
				</td>
			</tr>
    			<tr>
    
				<!-- 버닝 컨텐츠 시작 -->
				<td align="center" bgcolor="#f5f5f7">
					<table width="650" border="0" cellspacing="0" cellpadding="0" style='display:block;' id='idTableBurn'>
					<tr>
						<td>
							<table width="650" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="300"><!-- 내용 1 시작-->
									<table width="300" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td height="50"><!-- 버닝 탭 시작 -->
											<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												
												<td width="32">
													<a href="javascript:void(0)" onclick='move_up();' onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_03','','../images/btn/btn_burn_03_on.gif',1)"><img src="../images/btn/btn_burn_03.gif" name="burning_tab_03" width="32" height="32" border="0" id="burning_tab_03" TITLE="<?php echo lang_get('burning_msg_32')?>"/></a>
												</td>
												<td width="8">&nbsp;</td>
												<td width="32">
													<a href="javascript:void(0)" onclick="refresh_file_box();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_04','','../images/btn/btn_burn_04_on.gif',1)"><img src="../images/btn/btn_burn_04.gif" name="burning_tab_04" width="32" height="32" border="0" id="burning_tab_04" TITLE="<?php echo lang_get('burning_msg_33')?>"/></a>
												</td>
												<td width="8">&nbsp;</td>
								
												<td width="32">
													<a href="javascript:void(0)" onclick="delete_selected_nas();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_06','','../images/btn/btn_burn_06_on.gif',1)"><img src="../images/btn/btn_burn_06.gif" name="burning_tab_06" width="32" height="32" border="0" id="burning_tab_06" TITLE="<?php echo lang_get('esata_6')?>"/></a>
												</td>
											</tr>
											</table>
										<!-- 버닝 탭 끝 -->
										</td>
									</tr>
									
									<tr>
										<td width="300" height="2" background="../images/b_line_01.gif"></td>
									</tr>
									
									<tr>
										<td valign="middle" style="width:300px;height:30px;">

																	
										<!-- 새 디렉토리 생성 -->
										<form name="new_directory_fm_nas" id="new_directory_fm_nas" method="post" onsubmit="return false;" style="padding:0px margin:0px;">
												<table width="300px" cellspacing="0px" cellpadding="0px">
												<tr>
													<td width="180px">
														<input type="text" name="new_directory_name_nas" id="new_directory_name_nas" maxlength="20" size="28">
													</td>
													<td width="120px" align="right">
														<img src="../images/btn/btn_create_directory.gif" onclick="create_dir_nas();" style="cursor:pointer;" /> 
														<!--<input type="button" value="<?php echo lang_get('esata_1')?>" >-->
													</td>
												</tr>
												</table>
										</form>
										
					
										
										</td>
									</tr>
									
									<tr>
										<td width="300" height="2" background="../images/b_line_01.gif"></td>
									</tr>
									<tr>
										<td height="2"></td>
									</tr>
									<tr>
                          <!-- select 시작-->
                          <td align="center"><table width="299" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="1" bgcolor="#d2d2d2"></td>
                              </tr>
                              <tr>
                                <td background="../images/input_bg_c.gif"><table width="299" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td height="18" align="center" background="../images/input_bg_c.gif"><table width="290" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td height="20" class="gray" id="idPath">&nbsp;</td>
                                            <!--<td width="20" align="center"><img src="../images/input_arrow.gif" width="13" height="12" /></td>-->
                                          </tr>
                                      </table>
                                      </td>
                                    </tr>
                                </table>
                                </td>
                              </tr>
                              <tr>
                                <td height="1" bgcolor="#d2d2d2"></td>
                              </tr>
                            </table>
                            
                              <!-- select 끝-->
                              <table width="299" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td height="9"></td>
                                </tr>
                            </table>
                            
                            </td>
                        </tr>
                        <tr>
                          <td align="center" valign="top">
                          
                          <table width="300" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="300" height="1" background="../images/Burn/b_bg_top_01.gif"></td>
                              </tr>

                                <tr>
                                <td height="247" align="center" valign="top" background="../images/Burn/b_bg_c_01.gif" >
                                <!--<td height="247" align="center" valign="top" background="../images/Sync/img_sync_02.gif" >-->
	
	<!-- File Box Start : browser-->
	<div id="file_box" style="width:300px;height:247px;overflow:auto;"></div>
	<div id="directory_info"></div>
	<!-- File Box End -->
	
			</td></tr>
	
                              <tr>
                                <td width="300" height="1" background="../images/Burn/b_bg_bottom_01.gif"></td>
                              </tr>
                            </table>
                              <!-- 컨텐츠 박스 끝-->
                              </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    <!-- 내용 1 끝--></td>
                    
                    
                  <td width="50" align="center">
                  <!-- 중앙 화살표 시작-->
                      <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <table width="32" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>
                          <a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('arrow1','','../images/btn/btn_burn_02_on.gif',1)"><img onclick='copy_nas_to_esata();' src="../images/btn/btn_burn_02.gif" name="arrow1" width="32" height="32" border="0" id="arrow1" TITLE="<?php echo lang_get('esata_3')?>"/></a>
                          </td>
                        </tr>
                        <tr><td height="7"></td></tr>
                        <tr>
                          <td><a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('a_021','','../images/btn/btn_burn_01_on.gif',1)"><img onclick='copy_esata_to_nas();' src="../images/btn/btn_burn_01.gif" name="a_021" width="32" height="32" border="0" id="a_021" TITLE="<?php echo lang_get('esata_4')?>"/></a></td>
                        </tr>
                      </table>
                    <!-- 중앙 화살표 끝-->
                    </td>
                    
                    
                  <td width="300" valign="top"><!-- 내용 2 시작-->
                      <table width="300" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="50">
                          <table width="300" border="0" cellspacing="0" cellpadding="0">
                              
                              <tr>
                                <td height="50">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    
                                      
									<td width="32">
										<a href="javascript:void(0)" onclick='move_up_esata();' onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_13','','../images/btn/btn_burn_03_on.gif',1)"><img src="../images/btn/btn_burn_03.gif" name="burning_tab_13" width="32" height="32" border="0" id="burning_tab_13" TITLE="<?php echo lang_get('burning_msg_32')?>"/></a>
									</td>
									<td width="8">&nbsp;</td>
									<td width="32">
										<a href="javascript:void(0)" onclick="esata.connect('refr');" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_14','','../images/btn/btn_burn_04_on.gif',1)"><img src="../images/btn/btn_burn_04.gif" name="burning_tab_14" width="32" height="32" border="0" id="burning_tab_14" TITLE="<?php echo lang_get('burning_msg_33')?>"/></a>
										
										
									</td>
									<td width="8">&nbsp;</td>
									
									<td width="32">
										<a href="javascript:void(0)" onclick='delete_selected_esata();' onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_16','','../images/btn/btn_burn_06_on.gif',1)"><img src="../images/btn/btn_burn_06.gif" name="burning_tab_16" width="32" height="32" border="0" id="burning_tab_16" TITLE="<?php echo lang_get('esata_6')?>"/></a>
									</td>
                                      
                                    </tr>
                                </table>
                                </td>
                              </tr>
                          </table>
                          </td>
                        </tr>
                        
                        <tr>
						<td width="300" height="2" background="../images/b_line_01.gif"></td>
					</tr>
					
					<tr>
						<td valign="middle" style="width:300px;height:30px;">
							
							
							
								<!-- 새 디렉토리 생성 -->
								<form name="new_directory_fm_esata" id="new_directory_fm_esata" method="post" onsubmit="return false;" style="padding:0px margin:0px;">
										<table width="300px" cellspacing="0px" cellpadding="0px">
										<tr>
											<td width="180px">
												<input type="text" name="new_directory_name_esata" id="new_directory_name_esata" maxlength="20" size="28">
											</td>
											<td width="120px" align="right">
												<img src="../images/btn/btn_create_directory.gif" onclick="create_dir_esata();" style="cursor:pointer;" /> 
												<!--<input type="button" value="<?php echo lang_get('esata_1')?>" >-->
											</td>
										</tr>
										</table>
								</form>	

						
						</td>
					</tr>
                        
                        
                        <tr>
                          <td width="300" height="2" background="../images/b_line_01.gif"></td>
                        </tr>
                        <tr>
                          <td height="2"></td>
                        </tr>
                        <tr>
                          <!-- select 시작-->
                          <td align="center">
                          <table width="299" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="1" bgcolor="#d2d2d2"></td>
                              </tr>
                              <tr>
                                <td background="../images/input_bg_c.gif"><table width="299" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td height="18" align="center" background="../images/input_bg_c.gif"><table width="290" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td height="20" class="gray" >
                                            	<div id="idPathEsata" width="100" style="display:block;"><?php echo lang_get('esata_msg_9')?></div>	<!-- e-SATA list to select -->
                                            </td>
                                            <!--<td width="20" align="center"><img src="../images/input_arrow.gif" width="13" height="12" /></td>-->
                                          </tr>
                                      </table></td>
                                    </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td height="1" bgcolor="#d2d2d2"></td>
                              </tr>
                            </table>
                              <!-- select 끝-->
                              <table width="299" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td height="9"></td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                          <td align="center" valign="top">
                          
                          <table width="300" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="300" height="1" background="../images/Burn/b_bg_top_01.gif"></td>
                              </tr>
                              <tr>
                                <td height="247" align="center" background="../images/Burn/b_bg_c_01.gif">
                                
     <!-- File Box Start : browser-->
	<div id="file_box_esata" style="width:300px;height:247px;overflow:auto;"></div>
	<div id="directory_info_esata"></div>
	<!-- File Box End -->
                                
	<!--Selected File Box Start : browser-->
	<!--<div>
		<form name="do_for_two_fm" id="do_for_two_fm" action="POST">
			<input type="hidden" value="" id="do_for_two">
		</form>
	</div>
		<div id="selected_file_box" >
	</div>
	<div id="selected_rename_box" style="display:none;">
		<div align="right" style="text-align:right">
			<a href="" onclick="hide_rename_box();return false;">x</a>
		</div>
		<div style="font-size:11px;">NewName</div>
		<form name="rename_fm" id="rename_fm" action="POST" onsubmit="return false;">
		<table class="simple_table">
			<tr><td>
				<input type="text" name="new_name" id="rename_fm_new_name" value="">
				<input type="hidden" name="old_name" id="rename_fm_old_name" value="">
				<input type="hidden" name="type" id="rename_fm_type" value="">
			</td><td>
				<input type="button" value="Submit" onclick="rename_submit();">
			</td></tr>
		</table>
		</form> 
	</div>-->
	<!--Selected File Box End -->
                                
                                
 
                                  
                                  </td>
                              </tr>
                              <tr>
                                <td width="300" height="1" background="../images/Burn/b_bg_bottom_01.gif"></td>
                              </tr>
                            </table>
                              <!-- 컨텐츠 박스 끝-->
                              </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                    <!-- 내용 2 끝--></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="70" valign="top">
            
<table width="650" height="46" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" background="../images/Burn/b_bg.gif">
		
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100" align="center">NAS</td>
					<td align="center" >
					<div id="idBurnSize" type="text" name="textfield2" size="50" class="inputtext" value="" style="width:300px;background-color:white;" />
					</td>
					<td width="100" align="center">e-SATA</td>
				</tr>
				
				
			</table>
		
		</td>
	</tr>
</table>

            
            </td>
          </tr>
      </table>
      

      </td>
      <!-- 버닝 컨텐츠 끝 -->
      
      
    </tr>
    
<!--Next button-->  
<tr>
<td align="right" style="padding:20 0 0 0px">
	<div id='idButtonBurnNext' style='visibility:visible;'>
		<a href="javascript:void(0)" onclick="esata.connect('conn');"><img src="../images/btn/btn_connect.gif" border="0" /></a>
	</div>
</td>
</tr>
<!--Next button : end-->  

    <tr>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  <!-- 중앙내용 끝-->
  </tr>

  
</table></td>

            </tr>
          </table></td>
          <!-- 전체center 영역 끝-->
          
          </div>
          <!-- Layer ends -->
          
          
          
          
          </tr>
          <!-- bottom 자르는 영역 -->
          <?	 include "../inc/bottom.php";  ?>
					
					
					<!-- Layer for copy -->
          <div id="l_copy" style="z-index:201;display:none;position:absolute;top:410px;left:548px;width:160px;height:120px;background-color:white;">
          
          <table width="160px" cellpadding="0px" cellspacing="0px">
          	<tr><td style="background-color:#742625;color:#fff;height:25px;font-size:15px;font-weight:bold;text-align:center;">
          			<span id="l_copy_txt"><?php echo lang_get('esata_msg_10')?></span>	
          	</td></tr>
          	<tr>
          		<td style="border:1px solid #5d5d5d;height:95px;" align="center">	
          			<p><img id="l_copy_img" src="../images/Burn/ajax_loader_03.gif" width="128px" height="15px" /></p>
          			
          			<div align="center">
			          	<img src="../images/btn/btn_cancel.gif" id="id_inp_canc" onclick="esata.cancel();" style="display:block;cursor:pointer;margin:10px 0 0 0;" />
			          	<img src="../images/btn/btn_confirm.gif" id="id_inp_clos" onclick="copy_layer.close();" style="display:none;cursor:pointer;margin:10px 0 0 0;" />
			          </div>
          			
          	</td></tr>
          	
			    </table>
          </div>
          <!-- Layer ends -->
					
					
		




<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language='javascript' >
	page.init();
</script>