<div id='idPopMigrate'  style="width:670px; height:260px;	margin-top:40px; display:none" >
<table width="540" height="260" border="0" cellspacing="0" cellpadding="0" id="idMigrateTable" align="center">
  <tr>
    <td width="540" height="54" background="../images/popup/txt_popup_bg02.gif">
    <span class="popup_text" style="padding-left:20px;"><?php echo lang_get('volume_title_4')?></span></td>
  </tr>
  <tr>
    <td valign="top" style="padding:25 0 0 25px"><!-- 중앙 내용 시작 -->
      <table width="490" border="0" cellspacing="0" cellpadding="0" id="network_table">
       
        <tr>
          <td valign="top" class="red_s2" style="padding:0 0 10 0px"><?php echo lang_get('volume_msg_1')?></td>
        </tr>
        <tr>
          <td><!-- 테이블 영역 시작-->
          	
          		<!-- 타이틀 테이블 시작-->
                      <table width="490" height="25" border="0" cellspacing="0" cellpadding="0" id="title_table">
                        <tr>
									    			<td class="header_center" style="width:110px"><?php echo lang_get('volume_edit_2')?></td>
									    			<td class="header_center" style="width:240px"><?php echo lang_get('status_bluray_2')?></td>
									    			<td class="header_center" style="width:140px"><?php echo lang_get('volume_3')?></td>
                        </tr>
                      </table>
              <!-- 타이틀 테이블 끝-->
          	
          		<!-- 내용 1 시작 -->
					            <table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableMgrBay1" style="display:none">
												<tr>
													<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbMgrBay1"	id="idCbMgrBay1" onclick="check_migrate(this)" value="0";/></td>
					                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_MgrNameBay1"> </span></td>
					                <td class="otherCol" width="240px"><div id="id_MgrModelBay1"></div></td>
					                <td class="thirdCol" width="140px"><div id="id_MgrSizeBay1"></div></td>         			
					          		</tr>
					        		</table>
                    <!-- 내용 1 끝 -->
          	
          	
          	<!-- 내용 2 시작 -->
					            <table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableMgrBay2" style="display:none">
												<tr>
													<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbMgrBay2"	id="idCbMgrBay2" onclick="check_migrate(this)" value="0";/></td>
					                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_MgrNameBay2"> </span></td>
					                <td class="otherCol" width="240px"><div id="id_MgrModelBay2"></div></td>
					                <td class="thirdCol" width="140px"><div id="id_MgrSizeBay2"></div></td>         			
					          		</tr>
					        		</table>
                    <!-- 내용 2 끝 -->
          	
          	<!-- 내용 3 시작 -->
					            <table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableMgrBay3" style="display:none">
												<tr>
													<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbMgrBay3"	id="idCbMgrBay3" onclick="check_migrate(this)" value="0";/></td>
					                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_MgrNameBay3"> </span></td>
					                <td class="otherCol" width="240px"><div id="id_MgrModelBay3"></div></td>
					                <td class="thirdCol" width="140px"><div id="id_MgrSizeBay3"></div></td>         			
					          		</tr>
					        		</table>
                    <!-- 내용 3 끝 -->
          	
          	<!-- 내용 4 시작 -->
					            <table align="center" width="490" border="0" cellspacing="0" cellpadding="0" id="idTableMgrBay4" style="display:none">
												<tr>
													<td class="firstCol" width="30px" style="border-right:none;"><input type="checkbox" name="CbMgrBay4"	id="idCbMgrBay4" onclick="check_migrate(this)" value="0";/></td>
					                <td class="firstCol" width="80px" style="text-align:left;border-left:none;"><span id="id_MgrNameBay4"> </span></td>
					                <td class="otherCol" width="240px"><div id="id_MgrModelBay4"></div></td>
					                <td class="thirdCol" width="140px"><div id="id_MgrSizeBay4"></div></td>         			
					          		</tr>
					        		</table>
                    <!-- 내용 4 끝 -->
           
            <!-- 테이블 영역 끝--></td>
        </tr>
       <tr>
          <td valign="top" style="padding:10 0 20 0px" class="m_gray_04">
          <!-- RAID Level & select 시작 -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="idTableMgrSelect" style="display:block">
              <tr>
                
				<td align="left"> 
				<?php echo lang_get('volume_msg_21')?>
				</td>
				
			  </tr>
			</table>
			<table width="100%" height="25" border="0" cellspacing="0" cellpadding="0">
			  <tr align="center">  
			  	<td width="155"></td>
 				<td align="right" width="10" class="white" 	style="padding:0 0 0 10px" >
				  <input type='radio' value='linear' name="rdoMgrLevel" id="idRdoMgrLinear" style="display:none"/>
				</td>
				<td width="40" align="left">
				  <div id="id_MgrLevelLinear"> 
				  </div>
				</td>
				<td align="right" width="10" class="white" 	style="padding:0 0 0 10px">
				  <input type='radio'	value='raid1'  name="rdoMgrLevel" id="idRdoMgrRaid1" style="display:none"/>
                </td>
				<td width="40" align="left">
				  <div id="id_MgrLevelRaid1"> 
				  </div>
				</td>
				<td align="right" width="10" class="white" 	style="padding:0 0 0 10px">
				  <input type='radio'	value='raid5'  name="rdoMgrLevel" id="idRdoMgrRaid5" style="display:none"/>
				</td>
				<td width="40" align="left" >
				  <div id="id_MgrLevelRaid5"> 
				  </div>
				</td>
			  	<td width="155"></td>
			    <!-- select box 끝 --> 
              </tr>
            </table><!-- RAID Level & select 끝 -->
          </td>
        </tr>
        <tr>
          <td align="center"><!-- 버튼시작-->
              <img src="../images/btn/btn_ok.gif" border="0" onclick='migrate_vol();' class="buttons"/>
              <img src="../images/btn/btn_cancel.gif" border="0" onclick='close_migrate_vol();' class="buttons"/>
              
            <!-- 버튼끝--></td>
        </tr>
        <!-- <tr>
   		   <td style="padding:15 0 0 0px"><img src="../images/icon/tip.gif" width="33" height="23"><img src="../images/network/tx.gif" width="388" height="23"></td>
   		 </tr> -->
      </table>
    <!-- 중앙내용 끝 --></td>
  </tr>
</table>
<? include "volume_pop_progress.php" ?>

</div>