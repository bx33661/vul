<?php include "../inc/top.php"; ?>
<!-- top 자르는 영역 -->

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<!--Debugging message-->
<script language='javascript' src='../js/debug.js' ></script>	
<!--ajax lib-->
<script language='javascript' src='../js/jslb_ajax.js.php' ></script>	
<!-- Time -->
<script language="javascript1.2" src="../js/time.js.php" charset="utf-8"></script>
<!----------------------------------->
<script language='javascript' type="text/javascript">

// JUNY : Ignore 'Enter' key event 
if (document.layers)
  document.captureEvents(Event.KEYDOWN);
document.onkeydown =  function (evt) {
    var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
    if (keyCode == 13) {
      return false;
    }
};




<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>




<tr>
<!-- 전체center 영역 시작-->
	<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<!-- left 자르는 영역 -->
		<!-- left Navigation 영역 시작-->
			<td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
			<!-- left 끝-->
			<td width="100%" valign="top"><!-- 사이즈 수정 -->
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
					<!-- 사이즈 수정 -->
				</tr>
				<tr>
				<!-- 중앙내용 시작 -->
					<td valign="top" style="padding:0 0 0 50px">

					<div id="idTable_TIME" style="display:block;">	  
					
					<table width="670" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td height="40"></td>
						</tr>
						<tr>
							<td height="50" valign="top"><img src="../images/headtitle/htit_time.gif" /></td>
						</tr>
						<tr>
							<td height="30" align="center" valign="top">
							<!-- 중앙 테이블 영역 시작-->
							<table width="670" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top">
									<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td height="28">
											<table width="670" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td width="40">
														<div id="idTabDateOn" style='display:block;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_time_01','','../images/tab/tab_time01_on.gif',1)">
														<img src="../images/tab/tab_time01_on.gif" name="tab_time_01" border="0" id="tab_time_01" /></a></div>
														<div id="idTabDateOff" style='display:none;'><a href="javascript:void(0)" onclick="openTime();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_time_11','','../images/tab/tab_time01_over.gif',1)">
														<img src="../images/tab/tab_time01.gif" name="tab_time_11" border="0" id="tab_time_11" /></a></div>
													</td>
													<td width="2"></td>
													<td>
														<div id="idTabNtpOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_time_02','','../images/tab/tab_ntp_on.gif',1)">
														<img src="../images/tab/tab_ntp_on.gif" name="tab_time_02" border="0" id="tab_time_02" /></a></div>
														<div id="idTabNtpOff" style='display:block;'><!--<a href="javascript:void(0)" onclick="openNtp();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_time_12','','../images/tab/tab_ntp_over.gif',1)">
														<img src="../images/tab/tab_ntp.gif" name="tab_time_12" width="106" height="28" border="0" id="tab_time_12" /></a>--><input type="image" onclick="openNtp();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_time_12','','../images/tab/tab_ntp_over.gif',1)" src="../images/tab/tab_ntp.gif" name="tab_time_12" border="0" id="tab_time_12" /></div>
													</td>
													</td>
												</tr>
											</table>
											</td>
										</tr>
										<tr>
											<td height="2" bgcolor="#853e3c"></td>
										</tr>
									</table>
									</td>
								</tr>
								<tr>
									<td height="20"></td>
								</tr>
								<tr>
									<td>
									<!-- 테이블 영역 시작-->
									
									<!--table1 starts : date basic-->
									<div id="idTable">
									<!---->
									<table width="670" border="0" cellspacing="0" cellpadding="0" id='idTable1' style='display:block'>
										<tr>
											<td height="25" bgcolor="#5d5d5d">
											<table width="670" height="25" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td width="20"></td>
													<td width="229" class="white"><?php echo lang_get('time_date_2'); ?></td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="30" height="25"></td>
													<td class="white"><div id="idOutTimezone">Korea/Seoul</div></td>
												</tr>
											</table>
											</td>
										</tr>
										<tr>
											<td>
											<!-- 내용 1 시작 -->
											<table width="670" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td width="20" height="25" bgcolor="#f5f5f7"></td>
													<td width="229" height="25" bgcolor="#f5f5f7" class="m_gray_03"><?php echo lang_get('common_date'); ?></td>
													<!--<img src="../images/txt/txt_17.gif" width="185" height="15" />-->
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="30" height="25"></td>
													<td height="25" class="m_gray_03">
													<!-- 텍스트 박스 시작 -->
														<div id="idOutDate"><?php echo lang_get('common_loading'); ?></div>
													<!-- 텍스트 박스 끝 -->
													</td>
												</tr>
											</table>
											<!-- 내용 1 끝 -->
											</td>
										</tr>
										<tr>
											<td height="1" bgcolor="#e3e3e5"></td>
										</tr>
										<tr>
											<td height="25">
											<table width="670" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td width="20" height="25" bgcolor="#f5f5f7"></td>
													<td width="229" height="25" bgcolor="#f5f5f7" class="m_gray_03"><?php echo lang_get('common_time'); ?></td>
													<!--<img src="../images/txt/txt_18.gif" width="185" height="15" />-->
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="30" height="25"></td>
													<td height="25" class="m_gray_03">
													<!-- tab 시작 -->
														<div id="idOutTime"><?php echo lang_get('common_loading'); ?></div>
													<!-- tab 끝 --></td>
												</tr>
											</table>
											</td>
										</tr>
										<tr>
											<td height="1" bgcolor="#e3e3e5"></td>
										</tr>
									</table>
									<!--table1 ends-->
									</div>
									
									<!--table2 starts : date edit-->
									<table width="670" border="0" cellspacing="0" cellpadding="0" id='idTable2' style='display:none'>
									<tr>
										<td height="25" bgcolor="#5d5d5d">
										<table width="670" height="25" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20"></td>
											<td width="229" class="white"><?php echo lang_get('time_date_2'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25"></td>
											<td class="white">
												<div id='idTimezone'>
												<select name="select" size="1" id="select" class="SELECT">
												<option>Korea/Seoul</option>
												</select>
												</div>
											</td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td><!-- 내용 1 시작 -->
										<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20" height="25" bgcolor="#f5f5f7"></td>
											<td width="229" height="25" bgcolor="#f5f5f7"><?php echo lang_get('common_date'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25"></td>
											<td height="25">
											<!-- 텍스트 박스 시작 -->
											<!-- 인풋&텍스트 내용 시작 -->
											<table width="300" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="50"><input style="padding:0 0 0 0;margin:0 5 0 0;" onblur="check_input.date('year');" name="textfield" type="text" class="inputtext" id='idYear' value="" size="5" maxlength='4'></td>
												<td width="50" class="m_gray_03"><?php echo lang_get('common_year'); ?></td>
												<td width="50"><input style="padding:0 0 0 0;margin:0 5 0 0;" cellspacing="0" cellpadding="0" onblur="check_input.date('month');" name="textfield2" type="text" class="inputtext" id='idMonth' value="" size="5" maxlength='2'></td>
												<td width="50" class="m_gray_03"><?php echo lang_get('common_month'); ?></td>
												<td width="50"><input style="padding:0 0 0 0;margin:0 5 0 0;" cellspacing="0" cellpadding="0" onblur="check_input.date('day');" name="textfield7" type="text" class="inputtext" id='idDay' value="" size="5" maxlength='2'></td>
												<td width="50" class="m_gray_03"><?php echo lang_get('common_day'); ?></td>
											</tr>
											</table>
											<!-- 인풋&텍스트 내용 끝-->
											<!-- 텍스트 박스 끝 -->
											</td>
										</tr>
										</table><!-- 내용 1 끝 -->
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									<tr>
										<td height="25">
										<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20" height="25" bgcolor="#f5f5f7"></td>
											<td width="229" height="25" bgcolor="#f5f5f7"><?php echo lang_get('common_time'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25" class="m_gray_03"></td>
											<td height="25"><!-- tab 시작 -->
											<!-- 인풋&텍스트 내용 시작 -->
											<table width="300" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="50"><input style="padding:0 0 0 0;margin:0 5 0 0;" onblur="check_input.time('hour');" name="textfield3" type="text" class="inputtext" id='idHour' value="" size="5" maxlength='2'></td>
												<td width="50" class="m_gray_03"><?php echo lang_get('common_hour_1'); ?></td>
												<td width="50"><input style="padding:0 0 0 0;margin:0 5 0 0;" onblur="check_input.time('min');" name="textfield3" type="text" class="inputtext" id='idMinute' value="" size="5" maxlength='2'></td>
												<td width="50" class="m_gray_03"><?php echo lang_get('common_minute_1'); ?></td>
												<td width="50"><input style="padding:0 0 0 0;margin:0 5 0 0;" onblur="check_input.time('sec');" name="textfield3" type="text" class="inputtext" id='idSecond' value="" size="5" maxlength='2'></td>
												<td width="50" class="m_gray_03"><?php echo lang_get('common_second_1'); ?></td>
											</tr>
											</table><!-- 인풋&텍스트 내용끝 --> 
											<!-- tab 끝 -->
											</td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									</table>
									<!--table2 ends-->
																		
									
									<!--table3 starts : ntp basic-->
									<table width="670" border="0" cellspacing="0" cellpadding="0" id='idTable3' style='display:none'>
									<tr>
										<td height="25" bgcolor="#5d5d5d">
										<table width="670" height="25" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20"></td>
											<td width="229" class="white"><?php echo lang_get('time_ntp_1'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25"></td>
											<td class="white">
											<table width="200" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="10" align="center"><input type="radio" name="radio" id='idRadioenableOut' value="radio" disabled /></td>
												<td class="white"><?php echo lang_get('common_enable'); ?></td>
												<td width="10" align="center"><input type="radio" name="radio" id='idRadiodisableOut' value="radio" disabled /></td>
												<td class="white"><?php echo lang_get('common_disable'); ?></td>
											</tr>
											</table>
											</td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td><!-- 내용 1 시작 -->
										<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20" height="25" bgcolor="#f5f5f7"></td>
											<td width="229" height="25" bgcolor="#f5f5f7" class="m_gray_03"><?php echo lang_get('time_ntp_2'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25"></td>
											<td height="25" class="m_gray_04"><!-- 텍스트 박스 시작 -->
											<div id='idServeraddrOut'></div>
											<!-- 텍스트 박스 끝 -->
											</td>
										</tr>
										</table>
									<!-- 내용 1 끝 -->
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									<tr>
										<td height="25">
										<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20" height="25" bgcolor="#f5f5f7"></td>
											<td width="229" height="25" bgcolor="#f5f5f7" class="m_gray_03"><?php echo lang_get('time_ntp_3'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25"></td>
											<td height="25" class="m_gray_04"><!-- tab 시작 -->
											<div id='idFrequencyOut'></div>
											<!-- tab 끝 -->
											</td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									</table>
									<!--table3 ends-->
	
	
									<!--table4 starts : ntp edit-->
									<table width="670" border="0" cellspacing="0" cellpadding="0" id='idTable4' style='display:none'>
									<tr>
										<td height="25" bgcolor="#5d5d5d">
										<table width="670" height="25" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20"></td>
											<td width="229" class="white"><?php echo lang_get('time_ntp_1'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25"></td>
											<td class="white">
											<table width="200" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="10" align="center"><input type="radio" name="radio" id="idRadioenableIn" value="radio" onclick="ntp_setting.en();" /></td>
												<td class="white"><?php echo lang_get('common_enable'); ?></td>
												<td width="10" align="center"><input type="radio" name="radio" id="idRadiodisableIn" value="radio" onclick="ntp_setting.dis();" /></td>
												<td class="white"><?php echo lang_get('common_disable'); ?></td>
											</tr>
											</table>
											</td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td><!-- 내용 1 시작 -->
										<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20" height="60" bgcolor="#f5f5f7"></td>
											<td width="229" height="60" valign="middle" bgcolor="#f5f5f7" style="padding:0 0 0 0px" class="m_gray_03"><?php echo lang_get('time_ntp_2'); ?></td>
											<td width="1" height="60" bgcolor="#e3e3e3"></td>
											<td width="30" height="60"></td>
											<td height="60"><!-- 텍스트 박스 시작 -->
												<!-- 인풋&텍스트 내용 시작 -->
												<table width="250" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td><input name="textfield" type="text" class="inputtext" id="idServeraddrIn"  maxlength="30" size="30"></td>
												</tr>
												<tr>
													<td height="2"></td>
												</tr>
												<tr>
													<td width="250" valign="middle" class="m_gray_03">
														<table width="250"><tr width="250">
															<td style="padding:0 0 0 0;margin 0;" width="15"><input style="padding:0 0 0 0;margin 0 0 0 0;" onclick="set_ntp.check_chkbox();" type="checkbox" name="checkbox" id="idChkboxIn"/></td>
															<td width="235"><div id="id_ntp_def_addr"><?php echo lang_get('time_ntp_4'); ?> (pool.ntp.org)</div></td>
														</tr></table>
													</td>
												</tr>
												</table> 
												<!-- 인풋&텍스트 내용 끝-->
											<!-- 텍스트 박스 끝 -->
											</td>
										</tr>
										</table>
										<!-- 내용 1 끝 -->
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									<tr>
										<td height="25">
										<table width="670" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="20" height="25" bgcolor="#f5f5f7"></td>
											<td width="229" height="25" bgcolor="#f5f5f7" class="m_gray_03"><?php echo lang_get('time_ntp_3'); ?></td>
											<td width="1" height="25" bgcolor="#e3e3e3"></td>
											<td width="30" height="25" class="m_gray_03"></td>
											<td height="25"><!-- tab 시작 -->
											<!-- select 내용 시작 -->
											<select name="select" size="1" id="idFrequencyIn" class="selectbox03">
											<option><?php echo lang_get('time_ntp_5'); ?></option>
											<option><?php echo lang_get('time_ntp_6'); ?></option>
											</select>
											<!-- select 내용끝 -->
											<!-- tab 끝 -->
											</td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									</table>
									<!--table4 ends-->
									
									<!-- 테이블 영역 끝-->
									</td>
								</tr>
								<tr>
									<td height="20"></td>
								</tr>
								<tr>
									
									<td>
									<!--Button area starts-->
									
									<!--Time basic button-->
									<table width="100%" border="0" cellspacing="0" cellpadding="0" id="idButtontime" style="display:block;">
                     						<tr width="100%" align="right">
                       							<td width="670" align="right"><a href="javascript:void(0)" onclick="editMode();">
                       							<img src="../images/btn/btn_edit.gif" border="0" /></a></td>
                     						</tr>
                   						</table> 
                   						<!--Time basic button ends-->
                   						
                   						<!--Time edit button-->
									<table width="670px" border="0" cellspacing="0" cellpadding="0" id="idButtontimeedit" style="display:none;">
										<tr>
											<td width="500">
                       							<img src="../images/btn/btn_time.gif" border="0" onclick="getTime();" style="cursor:pointer;"/></td>
											
											<td width="170px" align="right">

											<img id="idImage2" src="../images/btn/btn_apply.gif" border="0" onclick="debug('apply');setTime();" class="buttons"/>

											<img id="idImage3" src="../images/btn/btn_cancel.gif" border="0" onclick="openTime();" class="buttons"/>

											</td>
											
											
										</tr>
									</table>
									<!--Time edit button ends-->
									
									<!--NTP basic button-->
									<table width="100%" border="0" cellspacing="0" cellpadding="0" id="idButtonntp" style="display:none;">
                     						<tr>
                       							<!--<td width="670" align="right"><a href="javascript:void(0)" onclick="ntpEditmode();">
                       							<img src="../images/btn/btn_edit.gif" border="0" /></a></td>-->
                       							<td width="670" align="right"><input type="image" onclick="ntpEditmode();" src="../images/btn/btn_edit.gif" border="0" /></td>
                     						</tr>
                   						</table> 
                   						<!--NTP basic button ends-->
                   						
                   						<!--NTP edit button-->
									<table width="100%" border="0" cellspacing="0" cellpadding="0" id="idButtonntpedit" style="display:none;">
										<tr >
											<td width="670" align="right">
											<a href="javascript:void(0)" onclick="setNtp();">
											<img id="idImage2" src="../images/btn/btn_apply.gif" border="0" />
											</a>
											
											<a href="javascript:void(0)" onclick="ntpBasicmode();">
											<img id="idImage3" src="../images/btn/btn_cancel.gif" border="0" />
											</a>
											</td>
										</tr>
									</table>
									<!--NTPedit button ends-->
									
									<!--Button area ends-->
									</td>
								</tr>
							</table>
							<!-- 중앙 테이블 영역 끝-->
							</td>
						</tr>
					</table>
					</div>

					<!-- Message Table : Start -->	
					<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" >
						<table width="420" height="260" align="center" cellspacing="0" cellpadding="0" >
						<tr>
							<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;"><span class="popup_text"><div id='idMsgTitle'>NTP</div></span></td>
						</tr>
						<tr>
							<td align="center" valign="top" style="padding:0 0 0 0px">
								<div id="system_message"><?php echo lang_get('common_loading')?></div>
							</td>
						</tr>
						</table> 		
					</div>				

					
					</td>
					<div>
					<!-- 중앙내용 끝-->
				</tr>
			</table>
			</td>
			
		</tr>
	</table>
	</td>
	<!-- 전체center 영역 끝-->
	<!--Language test
	<div id='test'></div>-->
</tr>
<!-- bottom 자르는 영역 -->
<?	 include "../inc/bottom.php";  ?>



<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language='javascript' charset='utf-8'>
	page.init();
</script>

