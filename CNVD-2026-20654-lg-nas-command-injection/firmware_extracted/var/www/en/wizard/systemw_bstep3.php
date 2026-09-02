 <div id="systemw_bstep3" style="display:none">
              	 	  <!-- 1. Headtitle + tab image -->
              	 	  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin:40px 0px 0px 0px">
					         <tr>
					              <td height="50" valign="top"><img src="../images/headtitle/htit_system_setting.gif"/></td>
                   </tr>
                   
			             <tr><!--Step1 Body Start-->
				                <td height="30" align="center" valign="top">
                          
	 	                       <table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
	 	                       	         <tr>
																				<td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_01','','../images/wizard/tab_step_01_r.gif',1)"><img src="../images/wizard/tab_step_01.gif" name="step_01" border="0" onClick="showTable('systemw_bstep1');" /></a></td>
																				<td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_02','','../images/wizard/tab_step_02_r.gif',1)"><img src="../images/wizard/tab_step_02.gif" name="step_02" border="0" onClick="showTable('systemw_bstep2');" /></a></td>
																				<td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_03','','../images/wizard/tab_step_03.gif',1)"><img src="../images/wizard/tab_step_03_r.gif" name="step_03" border="0"></a></td>
																				<td width="430" background="../images/wizard/tab_line.gif">&nbsp;</td>
                                     </tr>
                            </table>
				                 </td>
		               </tr>

  					                  <tr><td height="20"></td></tr>
		                          
  					                  <tr><!-- Step information Row Start-->
    					                  <td valign="top" style="padding:0 0 0 0px">
                                  <table width="670" border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                        <td width="91"><img src="../images/icon/img_one_01.gif" width="91" height="83"></td>
                                        <td valign="top" class="red_s2" style="padding:5 0 0 0px">
                                            <img src="../images/wizard/system_setting_step3.gif">
                                        </td>
                                    </tr>
                                  </table>
                                </td>
		                          </tr><!-- Step1 information Row End-->
		                </table>
          
       <!-- 3. Contents -->   
          <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
          	<tr>
          			<td colspan="2" style="padding-bottom:20px"><img src="../images/wizard/stit_date_time.gif" /></td>
          	</tr>
            <tr>                   
                <td class="header" colspan="2"><input type="checkbox" name="useLocalTime" id="useLocalTime" onClick="getTime();"><?php echo lang_get('wizard_5'); ?></td>
            </tr>
            
            <tr>
                <td class="firstCol_250"><?php echo lang_get('time_date_2'); ?></td>
                <td class="otherCol_420"><div id='idTimezone'></div></td>
            </tr>
            <tr>
                <td class="firstCol_250"><?php echo lang_get('common_date'); ?></td>
                <td class="otherCol_420">
                  		 <table width="300" border="0" cellspacing="0" cellpadding="0">
              						<tr>
              							<td width="50"><input name="idYear" type="text" class="inputtext_no_margin" id="idYear" value="" size="5" maxlength="4" onblur="check_input.date('year');"></td>
              							<td width="50"><?php echo lang_get('common_year'); ?></td>
              							<td width="50"><input name="idMonth" type="text" class="inputtext_no_margin" id="idMonth" value="" size="5" maxlength="2" onblur="check_input.date('month');"></td>
              							<td width="50"><?php echo lang_get('common_month'); ?></td>
              							<td width="50"><input name="idDay" type="text" class="inputtext_no_margin" id="idDay" value="" size="5" maxlength="2" onblur="check_input.date('day');"></td>
              							<td width="50"><?php echo lang_get('common_day'); ?></td>
              						</tr>
              					</table> 
                 </td>
            </tr>
             <tr>
                <td class="firstCol_250"><?php echo lang_get('common_time'); ?></td>
                <td class="otherCol_420">
		                    <table width="300" border="0" cellspacing="0" cellpadding="0">
		          						<tr>
		          							<td width="50"><input name="idHour" type="text" class="inputtext_no_margin" id="idHour" value="" size="5" maxlength="2" onblur="check_input.time('hour');"></td>
		          							<td width="50"><?php echo lang_get('common_hour_2'); ?></td>
		          							<td width="50"><input name="idMinute" type="text" class="inputtext_no_margin" id="idMinute" value="" size="5" maxlength="2" onblur="check_input.time('min');"></td>
		          							<td width="50"><?php echo lang_get('common_minute_1'); ?></td>
		          							<td width="50"><input name="idSecond" type="text" class="inputtext_no_margin" id="idSecond" value="" size="5" maxlength="2" onblur="check_input.time('sec');"></td>
		          							<td width="50"><?php echo lang_get('common_second_1'); ?></td>
		          						</tr>
		          					</table>
                 </td>
            </tr>
					</table>
   		<!-- 4. Buttons --> 
   			<table width="670" cellspacing="0" cellpadding="0">
   		 		<tr>
   		   		<td width="500" align="left"><img src="../images/btn/btn_back.gif" border="0" onClick="showTable('systemw_bstep2');" style="cursor:pointer;"/></td>
   		   		<td align="right"><a href="./systemw_00.php"><img src="../images/btn/btn_cancel.gif" border="0" style="cursor:pointer;"/></a>
   		   			               <img src="../images/btn/btn_confirm.gif" border="0"  onClick="check_Bstep();" style="cursor:pointer;margin-left:10px;"/></td>

      			
          </tr>
        </table>
 </div> 
