 <div id="systemw_bstep2" style="display:none">
              	 	  <!-- 1. Headtitle + tab image -->
              	 	  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin:40px 0px 0px 0px">
					         <tr>
					              <td height="50" valign="top"><img src="../images/headtitle/htit_system_setting.gif" /></td>
                   </tr>
                   
			             <tr><!--Step1 Body Start-->
				                <td height="30" align="center" valign="top">
                          
	 	                       <table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
	 	                       	         <tr>
                                         <td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_01','','../images/wizard/tab_step_01_r.gif',1)"><img src="../images/wizard/tab_step_01.gif" name="step_01" border="0" onClick="showTable('systemw_bstep1');" /></a></td>
													  							<td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_02','','../images/wizard/tab_step_02.gif',1)"><img src="../images/wizard/tab_step_02_r.gif" name="step_02" border="0"></a></td>
													  							<td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_03','','../images/wizard/tab_step_03_r.gif',1)"><img src="../images/wizard/tab_step_03.gif" name="step_03" border="0" onClick="showTable('systemw_bstep3');" /></a></td>
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
                                            <img src="../images/wizard/system_setting_step2.gif">
                                        </td>
                                    </tr>
                                  </table>
                                </td>
		                          </tr><!-- Step1 information Row End-->
		                </table>
          
       <!-- 3. Contents -->   
          <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
          	<tr>
          			<td colspan="2" style="padding-bottom:20px"><img src="../images/wizard/stit_network_ip.gif" /></td>
          	</tr>
            <tr>                   
                <td class="header"><input type="radio" name="rdoDHCP" id="rdoDHCP_disable" value="block" onclick="FormDHCP();"/><?php echo lang_get('network_interface_0'); ?></td>
                <td class="header"><input type="radio" name="rdoDHCP" id="rdoDHCP_enable" value="dhcp" onclick="FormDHCP();"/><?php echo lang_get('network_interface_1'); ?> (DHCP)</td>
                
            </tr>
            
            <tr>
                <td class="firstCol_250"><?php echo lang_get('network_interface_3'); ?></td>
                <td class="otherCol_420">
                  		 <input name="txtIPAddr1" type="text" class="inputtext" id="txtIPAddr1" value="0" size="3" maxlength="3" onblur="CheckRange('txtIPAddr1');"/>
                  	   <input name="txtIPAddr2" type="text" class="inputtext" id="txtIPAddr2" value="0" size="3" maxlength="3" onblur="CheckRange('txtIPAddr2');"/>
                    	 <input name="txtIPAddr3" type="text" class="inputtext" id="txtIPAddr3" value="0" size="3" maxlength="3" onblur="CheckRange('txtIPAddr3');"/>
                     	 <input name="txtIPAddr4" type="text" class="inputtext" id="txtIPAddr4" value="0" size="3" maxlength="3" onblur="CheckRange('txtIPAddr4');"/>
                 </td>
            </tr>
            <tr>
                <td class="firstCol_250"><?php echo lang_get('network_interface_4'); ?></td>
                <td class="otherCol_420">
												<input name="txtSubnet1" type="text" class="inputtext" id="txtSubnet1" value="0" size="3" maxlength="3" onblur="CheckRange('txtSubnet1');"/>
												<input name="txtSubnet2" type="text" class="inputtext" id="txtSubnet2" value="0" size="3" maxlength="3" onblur="CheckRange('txtSubnet2');"/>
												<input name="txtSubnet3" type="text" class="inputtext" id="txtSubnet3" value="0" size="3" maxlength="3" onblur="CheckRange('txtSubnet3');"/>
												<input name="txtSubnet4" type="text" class="inputtext" id="txtSubnet4" value="0" size="3" maxlength="3" onblur="CheckRange('txtSubnet4');"/>
                 </td>
            </tr>
            <tr>
                <td class="firstCol_250"><?php echo lang_get('network_interface_5'); ?></td>
                <td class="otherCol_420">
                 				<input name="txtGatewayAddr1" type="text" class="inputtext" id="txtGatewayAddr1" value="0" size="3" maxlength="3" onblur="CheckRange('txtGatewayAddr1');"/>
                        <input name="txtGatewayAddr2" type="text" class="inputtext" id="txtGatewayAddr2" value="0" size="3" maxlength="3" onblur="CheckRange('txtGatewayAddr2');"/>
                        <input name="txtGatewayAddr3" type="text" class="inputtext" id="txtGatewayAddr3" value="0" size="3" maxlength="3" onblur="CheckRange('txtGatewayAddr3');"/>
                        <input name="txtGatewayAddr4" type="text" class="inputtext" id="txtGatewayAddr4" value="0" size="3" maxlength="3" onblur="CheckRange('txtGatewayAddr4');"/>
                 </td>
            </tr>
            <tr>
                <td class="firstCol_250"><?php echo lang_get('network_interface_6'); ?></td>
                <td class="otherCol_420">
                 				<input name="txtDNSAddr1_1" type="text" class="inputtext" id="txtDNSAddr1_1" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr1_1');"/>
                 				<input name="txtDNSAddr1_2" type="text" class="inputtext" id="txtDNSAddr1_2" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr1_2');"/>
                 				<input name="txtDNSAddr1_3" type="text" class="inputtext" id="txtDNSAddr1_3" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr1_3');"/>
                 				<input name="txtDNSAddr1_4" type="text" class="inputtext" id="txtDNSAddr1_4" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr1_4');"/>
                </td>
            </tr>
            <tr>
                <td class="firstCol_250"><?php echo lang_get('network_interface_7'); ?></td>
                <td class="otherCol_420">
                 				<input name="txtDNSAddr2_1" type="text" class="inputtext" id="txtDNSAddr2_1" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr2_1');"/>
                 				<input name="txtDNSAddr2_2" type="text" class="inputtext" id="txtDNSAddr2_2" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr2_2');"/>
                 				<input name="txtDNSAddr2_3" type="text" class="inputtext" id="txtDNSAddr2_3" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr2_3');"/>
                 				<input name="txtDNSAddr2_4" type="text" class="inputtext" id="txtDNSAddr2_4" value="0" size="3" maxlength="3" onblur="CheckRange('txtDNSAddr2_4');"/>
                </td>
            </tr>
          </table>  

   		<!-- 4. Buttons --> 
   			<table width="670" cellspacing="0" cellpadding="0">
   		 		<tr>
   		   		<td width="600" align="left"><img src="../images/btn/btn_back.gif" border="0" onClick="showTable('systemw_bstep1');" style="cursor:pointer;"/></td>
   		   		<td align="right"><img src="../images/btn/btn_next.gif" border="0"  onClick="showTable('systemw_bstep3');" style="cursor:pointer;"/></td>
      			
          </tr>
        </table>
 </div> 
  