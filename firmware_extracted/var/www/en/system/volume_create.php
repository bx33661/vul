<div id='idPopCreate' style="width:670px; height:260px;	margin-top:40px; display:none" >
		<!-- POPUP Title -->
		<table width="540" align="center" border="0" cellspacing="0" cellpadding="0" id="create_table">
		<tr>
			<td	width="540"	height="54"	background="../images/popup/txt_popup_bg02.gif">
				<span class="popup_text" style="padding-left:20px"><?php echo lang_get('volume_title_1')?></span>	
			</td>
		</tr>
		</table>
		

		<!-- 중앙 내용	시작 -->
		<div id='idCreateBayList' style='display:block;margin-top:25px;'>

		<table align="center" width="490" border="0"	cellspacing="0"	cellpadding="0"	>
			<tr>
				<td class="red_text_9" style="padding-bottom:10px"><?php echo lang_get('volume_create_1')?></td>
			</tr>
		</table>
		
		  <!-- 타이틀 테이블 시작-->
			<table align="center" width="490px" border="0" cellspacing="0" cellpadding="0" id="title_table">
	  		<tr>
	    			<td class="header_center" style="width:110px"><?php echo lang_get('volume_edit_2')?></td>
	    			<td class="header_center" style="width:240px"><?php echo lang_get('status_bluray_2')?></td>
	    			<td class="header_center" style="width:140px"><?php echo lang_get('volume_3')?></td>
	  		</tr>
			</table>
		  <!-- 타이틀 테이블 끝-->
                 
                 
            <!-- 내용 1 시작 -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableCrtBay1" style="display:none">
							<tr>
								<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbCrtBay1"	id="idCbCrtBay1" onclick="check_create(this)" value="0";/></td>
                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_CrtNameBay1"> </span></td>
                <td class="otherCol" width="240px"><div id="id_CrtModelBay1"></div></td>
                <td class="thirdCol" width="140px"><div id="id_CrtSizeBay1"></div></td>         			
          		</tr>
        		</table>
        		<!-- 내용 1 끝 -->	
        		
            <!-- 내용 2 시작 -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableCrtBay2" style="display:none">
							<tr>
								<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbCrtBay2"	id="idCbCrtBay2" onclick="check_create(this)" value="0";/></td>
                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_CrtNameBay2"> </span></td>
                <td class="otherCol" width="240px"><div id="id_CrtModelBay2"></div></td>
                <td class="thirdCol" width="140px"><div id="id_CrtSizeBay2"></div></td>         			
          		</tr>
        		</table>
        		<!-- 내용 2 끝 -->	
                
                
            <!-- 내용 3 시작 -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableCrtBay3" style="display:none">
							<tr>
								<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbCrtBay3"	id="idCbCrtBay3" onclick="check_create(this)" value="0";/></td>
                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_CrtNameBay3"> </span></td>
                <td class="otherCol" width="240px"><div id="id_CrtModelBay3"></div></td>
                <td class="thirdCol" width="140px"><div id="id_CrtSizeBay3"></div></td>         			
          		</tr>
        		</table>
        		<!-- 내용 3 끝 -->
                
            <!-- 내용 4 시작 -->
						<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableCrtBay4" style="display:none">
							<tr>
								<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbCrtBay4"	id="idCbCrtBay4" onclick="check_create(this)" value="0";/></td>
                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_CrtNameBay4"> </span></td>
                <td class="otherCol" width="240px"><div id="id_CrtModelBay4"></div></td>
                <td class="thirdCol" width="140px"><div id="id_CrtSizeBay4"></div></td>         			
          		</tr>
        		</table>
        		<!-- 내용 4 끝 -->                
          	
          	<!-- RAID Level & select 시작 -->  
          	    
            <table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableCrtSelect" style="display:block;margin-top:10px;">
							<tr>
								<td colspan="2"><?php echo lang_get('volume_msg_21')?></td>
								
							</tr>
							<tr>
								<td></td>
								<td>
										 <table width="100%" height="25" border="0" cellspacing="0" cellpadding="0">
									 		 <tr>  
										  		<td width="65"></td>
													<td width="10" class="white" 	style="padding:0 0 0 10px">
													<input type='radio'	value='NONE'   name="rdoLevel" id="idRdoCrtNone">
													</td>
													<td width="40" ><div id="id_CrtLevelNone"> </div></td>
							
													<td width="10" class="white" 	style="padding:0 0 0 10px">
													<input type='radio'	value='JBOD'   name="rdoLevel" id="idRdoCrtLinear">
													</td>
													<td width="40" ><div id="id_CrtLevelLinear"> </div></td>
													
													<td width="10" class="white" 	style="padding:0 0 0 10px">
													<input type='radio'	value='RAID0'  name="rdoLevel" id="idRdoCrtRaid0">
													</td>
													<td width="40" ><div id="id_CrtLevelRaid0"> </div></td>
							
													<td width="10" class="white" 	style="padding:0 0 0 10px">
													<input type='radio'	value='RAID1'  name="rdoLevel" id="idRdoCrtRaid1">
													</td>
													<td width="40" ><div id="id_CrtLevelRaid1"> </div></td>
							
													<td width="10" class="white" 	style="padding:0 0 0 10px">
													<input type='radio'	value='RAID5'  name="rdoLevel" id="idRdoCrtRaid5">
													</td>
													<td width="40" ><div id="id_CrtLevelRaid5"> </div></td>
							
													<td width="10" class="white" 	style="padding:0 0 0 10px">
													<input type='radio'	value='RAID10' name="rdoLevel" id="idRdoCrtRaid10">
													</td>
													<td width="40" ><div id="id_CrtLevelRaid10"> </div></td>
										  			<td width="65"></td>
											</tr>	
										</table>
									
								</td>
							</tr>
						</table>
          
          	<table align="center" width="490" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;"> 
							<tr>
								<td align="center"><!-- 버튼시작-->
										<img src="../images/btn/btn_ok.gif" border="0" onclick='create_vol();' class="buttons"/>
										<img src="../images/btn/btn_cancel.gif" border="0" onclick='close_cre_vol();' class="buttons"/>
								</td>
						</tr>
					  </table>
				    <!-- 버튼끝-->
			
		
	  </div>
		<!-- 중앙내용 끝 -->
		<table align="center" width="490" border="0" cellspacing="0" cellpadding="0">
				<tr id='idCreateWait' align="center" valign="middle" style='display:none'>
					<td height="200"><div id='idCreateWaitTxt' class="red_s2"></div></td>
				</tr>
		</table>


</div>