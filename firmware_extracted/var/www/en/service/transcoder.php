<? include "../inc/top.php"; ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<!--Browser lib-->
<script type='text/javascript' src='../js/prototype.js'></script>
<script type='text/javascript' src='../js/burning_common.js.php' charset="utf-8"></script>
<script type='text/javascript' src='../js/burning_browsing_display.js.php' charset="utf-8"></script>
<script type='text/javascript' src='../js/burning_browsing_action.js.php' charset="utf-8"></script>
<!--Browser lib : end-->
<script type='text/javascript' src='../js/debug.js' charset="utf-8"></script>		<!--debugging setting-->
<script type='text/javascript' src='../js/jslb_ajax.js.php' charset="utf-8"></script>	<!--ajax lib-->
<script type='text/javascript' src='../js/bd_common.js' charset="utf-8"></script>
<script type='text/javascript' src="../js/transcode.js.php" charset="utf-8"></script>
<!----------------------------------->


<?php
$res = "off";
$ffmpeg_log = '/etc/ffmpeg.log';
$ffmpeg_progress = '/etc/ffmpeg.prg';
$transcode_duration = '/var/www/run/trans_duration';
$transcode_files= '/var/www/run/trans_list';
$transcode_options= '/var/www/run/trans_options';

if(!file_exists($ffmpeg_log))
{
	shell_exec("sudo touch '$ffmpeg_log'; sudo chmod 666 '$ffmpeg_log'");
	shell_exec("sudo touch '$ffmpeg_progress'; sudo chmod 666 '$ffmpeg_progress'");
	shell_exec("sudo touch '$transcode_duration'; sudo chmod 666 '$transcode_duration'");
	shell_exec("sudo touch '$transcode_files'; sudo chmod 666 '$transcode_files'");
	shell_exec("sudo touch '$transcode_options'; sudo chmod 666 '$transcode_options'");

	if(!file_exists($ffmpeg_log))
	{
		echo "ERROR:FAIL TO INIT\n";
		exit;
	}	
	
}

?>






<!-- top 자르는 영역 -->

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
				<td height="50" valign="top"><img src="../images/headtitle/transcode.gif"/></td>
			</tr>
			<tr>
				<td height="30">
		
				<!-- 탭 컨텐츠 영역 시작-->
					<table width="670" border="0" cellspacing="0" cellpadding="0" >
					<tr>
						<td height="28">
		
							<table width="670" border="0" cellspacing="0" cellpadding="0" style='display:block;' id='idTabBurn'>
							<tr>
								<td width="50px"><a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('tab_01','','../images/tab/tab_burn_disc_on.gif',1)"><img src="../images/tab/tab_burn_disc_on.gif" name="tab_01" border="0" id="tab_01" /></a></td>
								<td width="2px">&nbsp;</td>
								<!--<td width="618px"><a href="javascript:void(0)" onclick="open_tab_image();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('tab_02','','../images/tab/tab_burn_image_over.gif',1)"><img src="../images/tab/tab_burn_image.gif" name="tab_02" border="0" id="tab_02" /></a></td>-->
							</tr>
							</table>
		
<!--Tab image for image burning-->	
							<? include 'burning_tab_image1.php' ?>
<!--Tab image for image burning : end-->	

				<!-- 탭 컨텐츠 영역 끝-->
		
						</td>
					</tr>
					<tr>
						<td height="2" bgcolor="#853e3c">
						</td>
					</tr>
					</table>
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
											<table width="232" border="0" cellspacing="0" cellpadding="0">
											<tr style="align:left;">
												<!--<td width="32">
													<a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_01','','../images/btn/btn_burn_01_on.gif',1)"><img src="../images/btn/btn_burn_01.gif" name="burning_tab_01" width="32" height="32" border="0" id="burning_tab_01" /></a>
												</td>
												<td width="8">&nbsp;</td>
												<td width="32">
													<a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_02','','../images/btn/btn_burn_02_on.gif',1)"><img src="../images/btn/btn_burn_02.gif" name="burning_tab_02" width="32" height="32" border="0" id="burning_tab_02" /></a>
												</td>
												<td width="8">&nbsp;</td>-->
												<td width="32">
													<a href="javascript:void(0)" onclick='move_up();' onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_03','','../images/btn/btn_burn_03_on.gif',1)"><img src="../images/btn/btn_burn_03.gif" name="burning_tab_03" width="32" height="32" border="0" id="burning_tab_03" TITLE="<?php echo lang_get('burning_msg_32')?>"/></a>
												</td>
												<td width="8">&nbsp;</td>
												<td width="32">
													<a href="javascript:void(0)" onclick="refresh_file_box();" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_04','','../images/btn/btn_burn_04_on.gif',1)"><img src="../images/btn/btn_burn_04.gif" name="burning_tab_04" width="32" height="32" border="0" id="burning_tab_04" title="<?php echo lang_get('burning_msg_33')?>"/></a>
												</td>
												<!--<td width="8">&nbsp;</td>
												<td width="32">
													<a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_05','','../images/btn/btn_burn_05_on.gif',1)"><img src="../images/btn/btn_burn_05.gif" name="burning_tab_05" width="32" height="32" border="0" id="burning_tab_05" /></a>
												</td>
												<td width="8">&nbsp;</td>
												<td width="32">
													<a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_06','','../images/btn/btn_burn_06_on.gif',1)"><img src="../images/btn/btn_burn_06.gif" name="burning_tab_06" width="32" height="32" border="0" id="burning_tab_06" /></a>
												</td>-->
												<td>&nbsp;</td>
											</tr>
											</table>
										<!-- 버닝 탭 끝 -->
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
                                          <!-- Burning path -->
                                            <td height="20" class="gray" id="idPath">&nbsp;</td>
                                            <td width="20" align="center"><!--<img src="../images/input_arrow.gif" width="13" height="12" />--></td>
                                          <!-- Burning path end -->
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
	
	<!-- File Box Start : browser-->
	<div id="file_box" style="width:300px;height:267px;overflow:auto;visibility:visible;"></div>
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
                          <a href="javascript:void(0)" onclick='send_selected_dir_names();' onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('arrow1','','../images/btn/btn_burn_02.gif',1)"><img src="../images/btn/btn_burn_02_on.gif" name="arrow1" width="32" height="32" border="0" id="arrow1" TITLE="<?php echo lang_get('burning_msg_34')?>"/></a>
                          </td>
                        </tr>
                        <tr>
                          <td height="7"></td>
                        </tr>
                        <tr>
                          <td><a href="javascript:void(0)" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('a_021','','../images/btn/btn_burn_06.gif',1)"><img onclick='delete_selected_dir_names();' src="../images/btn/btn_burn_06_on.gif" name="a_021" width="32" height="32" border="0" id="a_021" TITLE="<?php echo lang_get('burning_msg_35')?>"/></a></td>
                        </tr>
                      </table>
                    <!-- 중앙 화살표 끝-->
                    
                    </td>
                  <td width="300" valign="top"><!-- 내용 2 시작-->
                      <table width="300" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="50"><table width="300" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="20"></td>
                              </tr>
                              <tr>
                                <td>
                                <table width="300" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td width="100"><img src="../images/Burn/txt_name.gif" /></td>




                                      
                                      <td width="200px">
                                      
                      		<select name="Ethnernet_Frame"  class="inputtext" id="File_Format" style="width:200px" onchange="display_target_format();">
		                            <option value="1500">[AVI] PC, PMP <?php echo lang_get('network_interface_10'); ?> (<?php echo lang_get('network_interface_9'); ?>)</option>
		                            <option value="4084">[MP4] PSP, iPod <?php echo lang_get('network_interface_10'); ?> </option>
		                            <option value="7404">[FLV] Flash Video <?php echo lang_get('network_interface_10'); ?> </option>
					   	<option value="9676"> 320x240 <?php echo lang_get('network_interface_10'); ?> </option>
					   	<option value="9676"> 480x320 <?php echo lang_get('network_interface_10'); ?> </option>
                      		</select>


                                      </td>

                                      
                                    </tr>
                                </table>
                                </td>
                              </tr>
                          </table></td>
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
                                            <td height="20" class="gray"  >                                            
                                            <?php  if ($res == "off") { ?>
                                            <div id="idBurnTitle">
                                            <?php echo "Please move your files from left to right for transcoding" ?>
						  <? } ?>
						  <?php  if ($res == "on") { ?> 
						  <div id="idBurnTitle" style="font-weight:bolder;">
                                            <?php if($iscsicheck == "on") echo lang_get('iscsi_3'); else echo lang_get('storing_backup_warn');?>
						  <? } ?>
						  </div>
						  </td>
                                            <td width="20" align="center"><!--<img src="../images/input_arrow.gif" width="13" height="12" />--></td>
                                            
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
                            </table></td>
                        </tr>
                        <tr>
                          <td align="center" valign="top"><table width="300" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="300" height="1" background="../images/Burn/b_bg_bottom_01.gif"></td>
                              </tr>
                              <tr>
                                <td height="247" align="center" valign="top" background="../images/Burn/b_bg_c_01.gif">
                                
	<!--Selected File Box Start : browser-->
	<!--<div>
		<form name="do_for_two_fm" id="do_for_two_fm" action="POST">
			<input type="hidden" value="" id="do_for_two">
		</form>
	</div>-->
	<div id="selected_file_box" style="width:300px;height:267px;overflow:auto;"></div>
	<div id="directory_info_selected"></div>
	<!--<div id="selected_rename_box" style="display:none;">
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
                                
                                
                                <!-- 컨텐츠 박스 시작--><!--Pictures for status
                                    <table width="164" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td align="center"><img src="../images/icon/img_info_02.gif" width="52" height="63" /></td>
                                      </tr>
                                      <tr>
                                        <td><img src="../images/Burn/txt_ready_02.gif" width="162" height="30" /></td>
                                      </tr>
                                      <tr>
                                        <td height="30"><img src="../images/Burn/txt_burning_01_01.gif" width="162" height="19" /></td>
                                      </tr>
                                      <tr>
                                        <td height="53" align="center"></td>
                                      </tr>
                                      <tr>
                                        <td align="center"><a href="javascript:void(0)"><img src="../images/Burn/btn_info_02.gif" width="138" height="22" border="0" /></a></td>
                                      </tr>
                                  </table>-->
                                  
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
            <td height="70" valign="top"><table width="650" height="46" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" background="../images/Burn/b_bg.gif">
                  <table width="600" border="0" cellspacing="0" cellpadding="0">
                  
                      <tr>
                        <td width="200"><?php echo "Target Video" ?><!--<img src="../images/txt/b_txt_01.gif" width="100" height="18" />--></td>
                        <td width="20"></td>
                        <td>
                        <input id="idVideoFormat" type="text" name="textfield2" size="70" class="inputtext" value="-" disabled/>
                        </td>
                        <td width="20"></td>
                      </tr>
                      
                      <tr>
                        <td width="200"><?php echo "Target Audio" ?><!--<img src="../images/txt/b_txt_01.gif" width="100" height="18" />--></td>
                        <td width="20"></td>
                        <td>
                        <input id="idAudioFormat" type="text" name="textfield2" size="70" class="inputtext" value="-" disabled/>
                        </td>
                        <td width="20"></td>
                     
                      </tr>	
               	  <tr>
                        <td width="200"><?php echo "Progress bar" ?><!--<img src="../images/txt/b_txt_01.gif" width="100" height="18" />--></td>
                        <td width="20"></td>
                        
  				<td >
  				<div id="idVolProg_bar0" style="display:block;width:97%;background:url('../images/Burn/img_burn_bg_middle.gif');">
  				<img id="idVolProg_width0" src="../images/Burn/img_burn_bar_middle.gif" width="1" height="17"/>							
				</div>
				</td>
				<td align="left" height="17" width="100" style="position:absolute;top:700;left:470;">
					<strong><div id="idVolCapap0" ></div></strong>
				</td>

 				
                        <td width="20"></td>                     
                      </tr>	                     

                      
                  </table>
                  
                  </td>
                </tr>
            </table>
            </td>
          </tr>
      </table>
      
<!--Image burning table-->
      <? include 'burning_tab_image2.php' ?>      
<!--Image burning table : end-->

      </td>
      <!-- 버닝 컨텐츠 끝 -->
      
      
    </tr>
    <tr height="20px"><td></td></tr>



<?php if ($res != "on") { ?>
<!--Burn button-->  
<tr>
<td>
		<table>
			<tr>
				<td width="400px">
							<div id="burning_erase_disc" style="width:500px;height:25px;display:none;">
								<table border="0" cellspacing="0" cellpadding="0" width="500px" height="25px">	
										<tr>
											<td style="background-color:#742625;color:#fff;font-size:12px;font-weight:bold;padding-left:20px;;width:120px"><?php echo lang_get('schedule_msg_37')?></td>
								
										  <td style="border:1px solid #e3e3e3;width:280px" align="center">
										  	<?php echo lang_get('common_wait')?>
										  </td>
										</tr>
								</table>
						</div>
				</td>
				<td width="270px" align="right">
						<!--<input id='id_btn_frmt_disc' type='image' src="../images/btn/btn_erase_disc.gif" onclick="disc.format();" style="padding-right:5px;"/>-->
						<!--<img src="../images/btn/btn_erase_disc.gif" onclick="disc.format();" style="cursor:pointer;padding-right:5px;"/>-->
						<input id="id_btn_burn_data" type="image" onclick="start_transcoding();" src="../images/btn/btn_burn.gif" border="0" />
					</td>
				</tr>
		</table>
</td>
</tr>
<!--Burn button : end-->
<? } ?>





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
          
          </tr>
          <!-- bottom 자르는 영역 -->
          <?	 include "../inc/bottom.php";  ?>

<!--<div id='idDisableBackground' style="position:absolute;width:100%;height:100%;top:0px;left:0px;z-index:200;border:none;background-color:#FFFFFF; opacity:0.2;moz-opacity:0.2;filter:alpha(opacity=20); display:none">
<table width="100%" height="100%" ><tr><td width="100%" height="100%" align="center" valign="center" >
	
</td></tr></table>
</div>-->
<!-- Burning Information : file list & total capacity-->
<input type='hidden' id='file_list_to_pop' value=''/>
<input type='hidden' id='file_cap_to_pop' value=''/>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script typee="text/javascript" >
var warnMsg = document.getElementById('idBurnTitle').innerHTML; 

display_target_format();
page.init();

trans_progress.start_read();
	
</script>
