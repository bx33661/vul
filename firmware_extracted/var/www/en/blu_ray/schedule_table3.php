 
        	 	 <table width="670" border="0" cellspacing="0" cellpadding="0" id="idTable3" style="display:none;">
          					  <tr>
          					    <td valign="top">
          					    </td>
   					    </tr>
          					  
          					  <tr>
            					  <td height="20"></td>
   					    </tr>
          					  <tr>
           					   <td valign="top"><!-- 테이블 영역 시작-->
           					     
           					     
           					     
           					     
           					     
           					     
           					     <table width="670" border="0" cellspacing="0" cellpadding="0" id="back_table2" class="basicTable">
                                   <tr>
                                     <td valign="top"><!-- 전체 탭 테이블 시작 -->
                                         <!-- 전체 탭 테이블 끝 --></td>
                                   </tr>
                                 
                                   <tr>
                                     <td align="right">
                                     
                                     
                                     <table width="400px" border="0" cellspacing="0" cellpadding="0" id="select_01">
                                       <tr>
                                          <td style="padding-right:5px"> 
									                           <select size="1" name="company_gubun" class="selectbox03" style="WIDTH: 150px; HEIGHT: 20px">
									                              <option value="" selected="selected"><?php echo lang_get('schedule_msg_1')?></option>
									                           </select>
                                          </td>
                                         <td style="padding-right:5px"><input name="textfield2" type="text" class="inputtext" id="cms_search_text" size="25" /></td>
                                         <td><img src="../images/btn/btn_search.gif" onClick="search();" class="buttons"></td>
                                       </tr>
                                     </table>
                                     
                                     </td>
                                   </tr>
                                   <tr>
                                     <td height="10"></td>
                                   </tr>
                                   <tr>
                                   <!--전체 내용 시작 -->  
                                   <td>
                                   
                                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                     <!-- 오른쪽 내용 시작 -->  <td align="center" bgcolor="#f5f5f7" style="padding:10 0 10 10px">
                                     <div id='root_tree' align='left' style="overflow:auto; width:250px; height:250px; border:1px solid #bcbcbc;">
                        <!-- 내용 시작 -->
                        
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="sc_table_01">
                          <tr>
                            <td height="23" background="../images/Burn/archives.gif" >&nbsp;</td>
                          </tr>
                          <tr>
                           <!-- 중앙컨텐츠 시작 --> 
                           <td align="left">
                           <!-- comnso -->
                            <span id='root' title='open' style='display:none'>Root</span>
                            <span id='rootInfo'></span>
                            <!-- /// -->   
                             </td>
                           <!-- 중앙내용 컨테츠 끝-->
                          </tr>
                        </table>
                        
                        <!-- 내용 끝 -->
                      </div>
                                     
                                     
                                     </td><!-- 오른쪽내용 끝 -->
                                    
                                     <!-- 왼쪽내용 시작 -->  <td align="left" bgcolor="#f5f5f7" style="padding:10 0 10 10px">
                                     <div id='root_list' style="overflow:auto; width:385px; height:250px; border:1px solid #bcbcbc;">
                        <!-- 내용 시작 -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="sc_table_01">
                          <tr>
                            <td>
                            <span id='reslist'></span><br>
                            </td>
                          </tr>
                        </table>
                        <!-- 내용 끝 -->
                      </div>
                                     
                                     </td>
                                     <!-- 왼쪽내용 끝 -->
                                     </tr>
                                   </table>
                                   
                                   </td>
                                   
                                   <!-- 전체내용 끝 -->
                                   </tr>
                        </table>
                       <table width="670px" cellpadding="0px" cellspacing="0px" border="0">
                                   <tr>
                                   	<td width="670px" align="right">
                                   			<!--<img id="id_btn_erase_disc" src="../images/btn/btn_erase_disc.gif" onclick="disc.format2();" style="cursor:pointer;margin-right:5px;" /> -->                 
		                                    <img id="id_btn_init" src="../images/btn/btn_initialization.gif" onclick="submit_init_db();" class="buttons"/>	
		                                   	<img id="id_btn_rest" src="../images/btn/btn_restore.gif" onclick="submit_restore();"  border="0" class="buttons"/>
                                   	</td>
                                   </tr>
                       </table>
                                   <!-- Erase Disc Popup : Start 2008/12/04-->
                                   <!--
                                   <div id="restore_erase_disc" style="position:relative;left:185px;top:-200px;width:300px;height:100px;display:none;background-color:#fff;">
																			<table border="0" cellspacing="0" cellpadding="0" width="300px">	
																					<tr>
																						<td style="background-color:#742625;color:#fff;height:25px;font-size:15px;font-weight:bold;padding-left:20px;">Erase Disc</td>
																					</tr>
																					<tr>
																					  <td style="border:1px solid #5d5d5d;height:75px;" align="center">
																					  	<p>Please wait for a while</p>
																					  	<p><img id="restore_erase_disc_loading" src="../images/Burn/loading.gif" /></p>
																					  </td>
																					</tr>
																			</table>
																	</div>-->
                                   <!-- Erase Disc Popup : End -->
                                   </td>
   					    		</tr>
                                  </table>
                                  
                                  
                                  
       					        <!-- 테이블 영역 끝-->