	
		  	<table width="670" border="0" cellspacing="0" cellpadding="0" id="detail_table" style='display:none'>
   								 <tr>
                                  <td height="40"></td>
				  </tr>
   								 <tr>
    							  <td height="50" valign="top"><img src="../images/headtitle/htit_scheduling.gif" /></td>
				  </tr>
 							   <tr>
   							   <td height="30" align="center" valign="top">
                               <!-- 중앙 테이블 영역 시작-->
        	 	 <table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
          					  <tr>
          					    <td valign="top"><!-- 전체 탭 테이블 시작 -->
          					      <table width="670" border="0" cellspacing="0" cellpadding="0">
        	 	                       	         <tr>
                                                  <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_01.gif" name="step_01" border="0"></td>
						                                      <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_02_r.gif" name="step_02" border="0"></td>
						                                      <td width="510"  background="../images/wizard/tab_line.gif">&nbsp;</td>
                                             </tr>
                                  </table>
       					        <!-- 전체 탭 테이블 끝 --></td>
   					          </tr>
          					  <tr>
          					    <td height="20">	<input type="hidden" id="cms_source" value="none" />
																					<input type="hidden" id="cms_sch_date" value="none" />
																					<input type="hidden" id="cms_sch_week" value="none" /></td>
   					          </tr>
          					  <tr>
            					  <td valign="top" style="padding:0 0 0 0px">
                                  
                                  <!-- 중앙내용 시작 -->
                                  <table width="670" border="0" cellspacing="0" cellpadding="0" id="status_table">
                                    <tr>
                                      <td width="91"><img src="../images/icon/img_one_01.gif" width="91" height="83"></td>
                                      <td valign="top" class="red_s2" style="padding:5 0 0 0px">
                                      <img src="../images/wizard/sch_backup_step2.gif"></td>
                                    </tr>
                                  </table>
                                  <!-- 중앙내용 끝 -->
                                  
                                  <!-- 타이틀 테이블 시작-->
                                     <table width="670" height="25" border="0" cellspacing="0" cellpadding="0" id="title_table">
                                       <tr>
                                         <td class="header">
                                   			    <div id='sch_Txt03'><?php echo lang_get('schedule_backup_0')?></div>
																					</td> 
                                       </tr>
                                     </table>
                                   <!-- 타이틀 테이블 끝-->
                                  
                                  <!-- Contents -->
                                   <table width="670px" height="150px" border="0" cellspacing="0" cellpadding="0" id="scroll_01">
                                         <tr>
                                              <td width="200" height="150" align="center" bgcolor="#eeeeee">
                                            	
		                                            	<!-- Monthly Start -->
		                                              <table width="178" border="0" cellspacing="0" cellpadding="0" id="monthly_table" style="display:none">
		                                                <tr>
		                                                  <td height="26" align="center">
		                                                  <!-- Calendar Head : Start-->
		                                                  <div id="calendar_head"><?php echo lang_get('common_loading'); ?></div>
		                                                  <!-- Calendar Head : End-->
		                                                  
		                                                  </td>
		                                                </tr>
		                                                <tr>
		                                                  <td><img src="../images/wizard/sch_t_01.gif" width="178" height="21"></td>
		                                                </tr>
		                                                <tr>
		                                                  <td align="center" background="../images/wizard/sch_c_bg.gif"  style="padding:0 0 10 0px">
		                                                 <!-- 달력내용 시작 -->
		                                                 <div id="calendar_body" ><?php echo lang_get('common_loading'); ?></div> 
		                                                 <!-- 달력내용 끝 --></td>
		                                                </tr>
		                                                <tr>
		                                                  <td height="1" background="../images/wizard/sch_bg.gif"></td>
		                                                </tr>
		                                              </table>
		                                              
		                                              <!-- Weekly Start -->
		                                              <table width="180" border="0" cellspacing="0" cellpadding="0" id="weekly_table" style="display:none">
		                                           
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_sunday.gif" id="weekly_sunday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_sunday','','../images/wizard/weekly_sunday_over.gif',1)" onClick="setDay('sun');"/></td>
		                                                </tr>
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_monday.gif" id="weekly_monday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_monday','','../images/wizard/weekly_monday_over.gif',1)" onClick="setDay('mon');"/></td>
		                                                </tr>
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_tuesday.gif" id="weekly_tuesday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_tuesday','','../images/wizard/weekly_tuesday_over.gif',1)" onClick="setDay('tue');"/></td>
		                                                </tr>
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_wednesday.gif" id="weekly_wednesday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_wednesday','','../images/wizard/weekly_wednesday_over.gif',1)" onClick="setDay('wed');"/></td>
		                                                </tr>
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_thursday.gif" id="weekly_thursday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_thursday','','../images/wizard/weekly_thursday_over.gif',1)" onClick="setDay('thu');"/></td>
		                                                </tr>
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_friday.gif" id="weekly_friday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_friday','','../images/wizard/weekly_friday_over.gif',1)" onClick="setDay('fri');"/></td>
		                                                </tr>	
		                                                <tr>
		                                                	<td><img src="../images/wizard/weekly_saturday.gif" id="weekly_saturday" style="cursor:pointer;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('weekly_saturday','','../images/wizard/weekly_saturday_over.gif',1)" onClick="setDay('sat');"/></td>
		                                                </tr>  		
		                                            
		                                                
		                                              </table>
		                                              
		                                              <!-- Daily Start -->
		                                              <table width="180" height="150" border="0" cellspacing="0" cellpadding="0" id="daily_table" style="display:block">
		                                                <tr>
		                                                  <td align="center"><img src="../images/wizard/backup_main.gif" width="180" height="150"></td>
		                                                </tr>
		
		                                              </table>      
                                              
                                              </td>
                                              <td width="30px"></td>
                                              <form name="cms_sch">
                                              <td width="440px">
                                              
								                                         
								                                         <!-- 스케줄내용 시작 -->
								                                         <table width="100%" border="0" cellspacing="0" cellpadding="0" id="schdu">
								                                         	 <!-- First Row : Cycle -->
								                                         	 <tr> 
								                                             <td width="155" height="30"><?php echo lang_get('common_cycle'); ?></td>
								                                             <td class="m_gray_04">     
										                                             	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="input">
										                                               <tr>
										                                               	 <td width="33%"><input type="radio" name="cms_sch_cycle" id="cms_sch_cycle" value="daily" onClick="showCalTable('daily_table');" checked/><?php echo lang_get('common_daily'); ?></td>
										                                                 <td width="33%"><input type="radio" name="cms_sch_cycle" id="cms_sch_cycle" value="weekly" onClick="showCalTable('weekly_table');" /><?php echo lang_get('common_weekly'); ?></td>
										                                                 <td width="33%"><input type="radio" name="cms_sch_cycle" id="cms_sch_cycle" value="monthly"  onClick="showCalTable('monthly_table');"/><?php echo lang_get('common_monthly'); ?></td>
										                                                 
										                                               </tr>
										                                             </table>
										                                          </td>
								                                           </tr>
								                                           <tr>
								                                             <td height="1" colspan="2" bgcolor="#e3e3e3"></td>
								                                             </tr>
								                                         	 <!-- Second Row : time -->
								                                         	 <tr>
								                                             <td width="155" height="30"><?php echo lang_get('common_time'); ?></td>
								                                             <td>
								                                            <!-- 인풋내용시작--> <table width="60%" border="0" cellspacing="0" cellpadding="0" id="input">
								                                               <tr>
								                                                 
								                                                 <td width="60">
									                                                  <!-- Hour select  시작 --> 
									                         												  <div id="cms_time_hour"></div>
										  																							<!-- Hour select 끝 -->  
								                                                 </td>
								                                                 <td width="15">:</td>
								                                                  <td>
									                                                  <!-- Minute select  시작 --> 
									                         												  <div id="cms_time_min"></div>
										  																							<!-- Minute select 끝 -->  
								                                                 </td>                                                
								                                              
								                                               </tr>
								                                             </table><!-- 인풋내용끝--></td>
								                                           </tr>
								                                           <tr>
								                                             <td height="1" colspan="2" bgcolor="#e3e3e3"></td>
								                                             </tr>
								                                           <!-- Third Row : Backup Method -->  
								                                           <tr>
								                                             <td width="155" height="30" valign="top" style="padding:5 0 0 0px"><?php echo lang_get('schedule_backup_3'); ?></td>
								                                             <td>
								                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="radio_1">
								                                               <tr>
								                                                 <td> <input type="radio" name="cms_direc" id="cms_direc" value="incre" checked/>Incremental</td>
								                                                 
								                                                 
								
								                                              <td><input type="radio" name="cms_direc" id="cms_direc" value="full" />Full</td>
								                               
								                                               </tr>
								                                             </table></td>
								                                           </tr>  
								                                            <tr>
								                                             <td height="5" colspan="2" bgcolor="#e3e3e3"></td>
								                                             </tr>                                            
								                                           <tr>
								                                             <td width="155" height="35"><?php echo lang_get('common_source'); ?></td>
								                                             <td class="m_gray_04"><span id='target_folder2'>&nbsp</span></td>														
								                                           </tr>
																														<tr>
								                                             <td height="1" colspan="2" bgcolor="#e3e3e3"></td>
								                                             </tr>
								                                 
								                                           <tr>
								                                             <td width="155" height="35"><?php echo lang_get('wizard_6'); ?></td>
								                                             <td class="m_gray_04">
								                                             	
								                                             	<span id='backupOccur'><?php echo lang_get('common_daily')?></span>
								                                             	<span id='backupTime'>@ 00:00</span>
								                                             	
																															</td>
								                                           </tr>
								                                           <tr>
								                                             <td height="4" colspan="2" bgcolor="#e3e3e3"></td>
								                                             </tr>
																													
																														
																														
																														
																														
																															
								                                         </table>
								                                         <!-- 스케줄내용 시작 -->
								                                      	
                                              
                                              
                                            	</td>
                                            		</form>
                                         		</tr>
                                   </table>
                                          
                                   <!-- Buttons -->       
                                  <table width="670px" border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="display:block;margin-top:20px;">
										                  <tr>
										                    <td width="400"><img src="../images/btn/btn_back.gif" border="0" onClick="showTable('browser_table');" class="buttons"/></td>
										                    <td width="370px" align="right">
										                    		<a href="./schedulew_00.php"><img src="../images/btn/btn_cancel.gif" border="0" /></a>
										                    		<img src="../images/btn/btn_confirm.gif" border="0" onClick="setSchedule();" class="buttons"/>
										                  	</td>
										                  </tr>
                									</table>        
                                          
                  
                        </td>
     
   					          </tr>
                       

 
             </table>
     
                   </td>
                 </tr>
        </table>
                                      
                              