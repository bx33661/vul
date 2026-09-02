<tr>
	<td>
		<table id="idTableAudio" style='display:none'>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
           			<table width="670" border="0" cellspacing="0" cellpadding="0" >
                    	<tr>
                        	<td valign="top">
                        		<img src="../images/login/img_audio_cd.gif" width="140" height="145"/>
                        	</td>
                            <td width="490" valign="top">
                            	<!-- 오른쪽테이블내용시작-->
                                <table width="490" border="0" cellspacing="0" cellpadding="0" id="ripping01_table">
                                	<tr>
                                    	<td height="25" bgcolor="#5d5d5d"><!-- 타이틀 테이블 시작-->
                                        	<table width="490" height="25" border="0" cellspacing="0" cellpadding="0" id="title_table">
                                            	<tr>
                                                	<td width="470" class="white_s1" style="padding:0 0 0 20px">
                                                 		<div id='ripping_Txt01'> <strong>My Audio Extraction</strong> </div>
                                                 	</td>
                                                </tr>
											</table>
											<!-- 타이틀 테이블 끝-->
										</td>
									</tr>
									<tr>
										<td><!-- 내용 1 시작 -->
											<table width="490" border="0" cellspacing="0" cellpadding="0" id="odd_table1">
												<tr>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="124" bgcolor="#f5f5f7" class="m_gray_04" style="padding:0 0 0 20px">
														<div id='ripping_Ti02'> ODD Media Type </div>
													</td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="324" class="m_gray_12" style="padding:0 0 0 20px">
														<div id='ripping_Txt03'> Audio CD </div> 
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
										<td><!-- 내용 2 시작 -->
											<table width="490" border="0" cellspacing="0" cellpadding="0" id="odd_table2">
												<tr>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="124" bgcolor="#f5f5f7" class="m_gray_04" style="padding:0 0 0 20px">
														<div id='ripping_Txt04'> File Name  </div> 
													</td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="324" style="padding:0 0 0 20px" class="m_gray_12">
														<input name="textfield" type="text" class="inputtext" id="idAudioFilename" size="49" />
													</td>
												</tr>
											</table><!-- 내용 2 끝 -->
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									<tr>
										<td><!-- 내용 3 시작 -->
											<table width="490" border="0" cellspacing="0" cellpadding="0" id="odd_table3">
												<tr>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="124" bgcolor="#f5f5f7" class="m_gray_04" style="padding:0 0 0 20px">
														<div id='ripping_Txt05'> Path to Save </div>
													</td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="324" style="padding:0 0 0 20px" class="m_gray_12">
														<!-- 입력테이블내용 시작 -->
														<table width="100%" border="0" cellspacing="0" cellpadding="0" id="input_01">
															<tr>
																<td width="52" width="25" valign="center">
																	<a href="javascript:void(0)" onclick="popup_file_browser('idAudioSavePath');">
																		<img  src="../images/btn/btn_root.gif" width="52" height="19" border="0" style="padding:5 0 0 0px;"/>
																	</a>
																</td>
																<td>
																	<input name="textfield2" type="text" class="inputtext" style="padding:0 0 0 1px;" id="idAudioSavePath" size="40" disabled />
																</td>
															</tr>
														</table><!-- 입력테이블내용 끝 -->
													</td>
												</tr>
											</table><!-- 내용 3끝 -->
										</td>
									</tr>
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr>
									<tr>
										<td height="40"></td>
									</tr>
									
									<tr>
										<td height="25" bgcolor="#5d5d5d"><!-- 타이틀 테이블2 시작-->
											<table width="490" height="25" border="0" cellspacing="0" cellpadding="0" id="title_table">
												<tr>
													<td width="149" class="white_s1" style="padding:0 0 0 20px">
														<div id='title_Txt01'> <strong> Advanced Setting </strong> </div>
													</td>
													<td align="right" class="white_s1" style="padding:0 0 0 20px">
														<div id='idButtonAdvOpen' style='display:block;'>
															<a href="javascript:void(0)" onclick='open_adv_set();'>
																<img src="../images/btn/btn_open.gif" width="60" height="19" border="0" />
															</a>
														</div>
														<div id='idButtonAdvClose' style='display:none;'>
															<a href="javascript:void(0)" onclick="close_adv_set();">
																<img src="../images/btn/btn_close.gif" width="60" height="19" border="0" />
															</a>
														</div>
													</td>
												</tr>
											</table>
										</td><!-- 타이틀 테이블2 끝-->
									</tr>
									
									<tr><!-- Advanced setting -->
										<td>
											<table width="490" border="0" cellspacing="0" cellpadding="0" id="idAudioAdv" style='display:none;'>
												<tr>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="149" bgcolor="#f5f5f7" class="m_gray_04" style="padding:0 0 0 20px">
														<div id='mode_Txt01'>  Mode </div>
													</td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="299" style="padding:0 0 0 20px" class="m_gray_12">
														<!-- select box 시작 -->  
														<select name="select" size="1" id="idMode" class="selectbox03">
															<option value='s' selected>Stereo(Recommended)</option>
															<option value='m'>Mono</option>
														</select><!-- select box 끝 -->
													</td>
												</tr>
												<tr>
													<td height="1" bgcolor="#e3e3e3"></td>
												</tr>
												<tr>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="490" bgcolor="#f5f5f7" class="m_gray_04" style="padding:0 0 0 20px">
														<div id='mode_Txt01'> Bits </div>
													</td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="299" style="padding:0 0 0 20px" class="m_gray_12">
														<!-- select box 시작 -->  
														<select name="select" size="1" id="idBit" class="selectbox03">
															<option value='16' selected>16 bits(Recommended)</option>
															<option value='8'>8 bits</option><option value='4'>4 bits</option>
														</select>
													</td>
												</tr>
												<tr>
													<td height="1" bgcolor="#e3e3e3"></td>
												</tr>
												<tr>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="149" bgcolor="#f5f5f7" class="m_gray_04" style="padding:0 0 0 20px">
														<div id='mode_Txt01'> Rate </div>
													</td>
													<td width="1" height="25" bgcolor="#e3e3e3"></td>
													<td width="299" style="padding:0 0 0 20px" class="m_gray_12">
														<!-- select box 시작 -->  
														<select name="select" size="1" id="idRate" class="selectbox03">
															<option value='44100' selected> 44.1 kHz(Recommended)</option>
															<option value='22050'> 22.05 kHz</option>
															<option value='11025'> 11.025 kHz</option>
															<option value='5512.5'> 5512.5 Hz</option>
														</select>
													</td>
												</tr>	
											</table>
										</td>
									</tr>									
									<tr>
										<td height="1" bgcolor="#e3e3e5"></td>
									</tr><!-- Advanced setting ends -->
								</table><!-- 오른쪽테이블내용 끝-->
							</td>
						</tr>
					</table><!-- 테이블 영역 끝-->
				</td>
			</tr>
			<tr>
				<td align="right" style="padding:20 0 0 0px">
					<div id='id_btn_rip_aud' style='visibility:visible;'>
						<a href="javascript:void(0)" onclick="rip_audio();">
							<img  src="../images/btn/btn_extraction.gif" width="83" height="22" border="0" />
						</a>
					</div>
				</td>
			</tr>
		</table>
	</td>
</tr>
