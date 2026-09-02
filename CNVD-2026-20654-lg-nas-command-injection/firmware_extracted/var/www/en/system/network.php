<?	 include "../inc/top.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<!--Debugging message-->
<script language='javascript' src='../js/debug.js' ></script>
<!--ajax lib-->
<script language='javascript' src='../js/jslb_ajax.js.php' ></script>
<!---->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>
<script language="javascript1.2" src="../js/network.js.php" charset="utf-8"></script>
<!----------------------------------->

<script language='javascript' type="text/javascript">
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
      <!-- ??ucenter ???? ????--> <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      			          <tr>
	
	            <!-- left ????? ???? -->
	
         

	            <!-- left Navigation ???? ????-->
			         <td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
		       <!-- left ??-->
			        <td width="100%" valign="top"><!-- ?????? ??? -->
	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
			        <tr>
			            <td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
               <!-- ?????? ??? -->
                        </tr>
					  <tr>
  <!-- ?????? ???? -->  <td valign="top" style="padding:0 0 0 50px">
  						
		 
			
		 <!-- Modify : 2008/11/14  -->  
		
		 <!-- 1st tab - Host : Start -->
		 <div id="idTable_HOST_EDIT" style="display:block">
		 			<!-- 1. Page Title : Network -->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_network.gif" /></td>
				  		</tr>
				  	</table>
	 				 				
	         <!-- 2. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_host_HOST_EDIT','','../images/tab/tab_host_on.gif',1)"><img src="../images/tab/tab_host_on.gif" name="tab_host_HOST_EDIT"  border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_interface_HOST_EDIT','','../images/tab/tab_inter_over.gif',1)"><img src="../images/tab/tab_inter.gif" name="tab_interface_HOST_EDIT"  border="0" onclick="showTable('idTable_INTERFACE_EDIT');"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_work_HOST_EDIT','','../images/tab/tab_workgroup_over.gif',1)"><img src="../images/tab/tab_workgroup.gif" name="tab_work_HOST_EDIT"  border="0" onclick="showTable('idTable_DOMAIN_EDIT');"></a></td>
                </tr>
           </table>
              
           <!-- 3. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header" colspan="2"><?php echo lang_get('network_host_1'); ?></td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_host_2'); ?></td>
                     <td class="otherCol_420"><input name="txtHOSTNAME" type="text" class="inputtext" id="txtHOSTNAME" value="<?php echo lang_get('common_loading')?>" size="30" maxlength="12"></td>
                </tr>
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_host_3'); ?></td>
                     <td class="otherCol_420"><input name="txtHOSTDESC" type="text" class="inputtext" id="txtHOSTDESC" value="<?php echo lang_get('common_loading')?>" size="30" maxlength="24"></td>
                </tr>
              </table>  
                    
       		<!-- 4. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_apply.gif"  border="0" onclick="Set_Host_Info();" class="buttons"/>
                                              <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Network_Info();showTable('idTable_HOST_EDIT');" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	</div>	   
       		
     <!-- 2nd tab - Interface : Start -->
		 <div id="idTable_INTERFACE_EDIT" style="display:none">
		 			<!-- 1. Page Title : Network -->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0"  style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_network.gif"/></td>
				  		</tr>
				  	</table>
	 				 				
	         <!-- 2. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_host_INTERFACE_EDIT','','../images/tab/tab_host_over.gif',1)"><img src="../images/tab/tab_host.gif" name="tab_host_INTERFACE_EDIT" border="0"  onclick="showTable('idTable_HOST_EDIT');"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_interface_INTERFACE_EDIT','','../images/tab/tab_inter_on.gif',1)"><img src="../images/tab/tab_inter_on.gif" name="tab_interface_INTERFACE_EDIT" border="0""></a></td>
                    <td width="2" class="tab">&nbsp;</td>                        
                    <td class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_work_INTERFACE_EDIT','','../images/tab/tab_workgroup_over.gif',1)"><img src="../images/tab/tab_workgroup.gif" name="tab_work_INTERFACE_EDIT" border="0" onclick="showTable('idTable_DOMAIN_EDIT');"></a></td>
                </tr>
           </table>
              
           <!-- 3. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
              	<tr>
              			<td colspan="2" style="padding-bottom:20px"><img src="../images/subtitle/stit_ip_address.gif"/></td>
              	</tr>
                <tr>                   
                    <td class="header"><input type="radio" name="rdoDHCP" id="rdoDHCP_disable" value="none" onclick="FormDHCP();"/><?php echo lang_get('network_interface_0'); ?></td>
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
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
              	<tr>
              			<td colspan="2" style="padding-bottom:20px;border-bottom:1px solid #e3e3e5;"><img src="../images/subtitle/stit_ethernet.gif" /></td>
              	</tr>
               
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_interface_8'); ?></td>
                    <td class="otherCol_420">
                      		<select name="Ethnernet_Frame"  class="inputtext" id="Ethernet_Frame" style="width:200px" onchange="onMtuChange();">
		                            <option value="1500">1500 <?php echo lang_get('network_interface_10'); ?> (<?php echo lang_get('network_interface_9'); ?>)</option>
		                            <option value="4084">4000 <?php echo lang_get('network_interface_10'); ?> (Jumbo Frame)</option>
		                            <option value="7404">7000 <?php echo lang_get('network_interface_10'); ?> (Jumbo Frame)</option>
					   									  <option value="9676">9000 <?php echo lang_get('network_interface_10'); ?> (Jumbo Frame)</option>
                      		</select>
                     </td>
                </tr>
              </table>      
       		<!-- 4. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_Interface_Info();" class="buttons"/>
       		   		                  <img src="../images/btn/btn_cancel.gif" border="0" onclick="Get_Network_Info();showTable('idTable_INTERFACE_EDIT');" class="buttons"/>
       		   		</td>
          			
              </tr>
            </table>
     	</div>  		
		 
     <!-- 3rd tab - Workgroup/Domain : Start -->
		 <div id="idTable_DOMAIN_EDIT" style="display:none">
		 			<!-- 1. Page Title : Network -->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0"  style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_network.gif" /></td>
				  		</tr>
				  	</table>
	 				 				
	         <!-- 2. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_host_DOMAIN_EDIT','','../images/tab/tab_host_over.gif',1)"><img src="../images/tab/tab_host.gif" name="tab_host_DOMAIN_EDIT" border="0" onclick="showTable('idTable_HOST_EDIT');"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_interface_DOMAIN_EDIT','','../images/tab/tab_inter_over.gif',1)"><img src="../images/tab/tab_inter.gif" name="tab_interface_DOMAIN_EDIT" border="0" onclick="showTable('idTable_INTERFACE_EDIT');"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_work_DOMAIN_EDIT','','../images/tab/tab_workgroup_on.gif',1)"><img src="../images/tab/tab_workgroup_on.gif" name="tab_work_DOMAIN_EDIT" border="0"></a></td>
                </tr>
           </table>
              
           <!-- 3. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
           
               <tr>                   
                    <td class="header"><?php echo lang_get('network_domain_1'); ?></td>
                    <td class="header">
                    		<input type="radio" name="rdoDOMAIN_TYPE" id="rdoDOMAIN_TYPE_W" value="workgroup" onclick="FormDOMAIN();"/><?php echo lang_get('network_domain_2'); ?>
                    	 <input type="radio" name="rdoDOMAIN_TYPE" id="rdoDOMAIN_TYPE_D" value="domain" onclick="FormDOMAIN();"/><?php echo lang_get('network_domain_3'); ?>
                    </td>
                    
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_domain_2'); ?></td>
                    <td class="otherCol_420"><input name="txtWorkgroup" type="text" class="inputtext" id="txtWorkgroup" value="Workgroup" size="30" maxlength="15" onKeyPress="changeWG()" onKeyUp="changeWG()"></td>
                </tr>
                 <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_domain_3'); ?></td>
                    <td class="otherCol_420"><input name="txtDomain" type="text" class="inputtext" id="txtDomain" value="Domain" size="30" maxlength="24" onKeyPress="changeDM()" onKeyUp="changeDM()"></td>
                </tr>
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_domain_4'); ?></td>
                    <td class="otherCol_420"><input name="txtDomainAdmin" type="text" class="inputtext" id="txtDomainAdmin" value="DomainAdmin" size="30" maxlength="30" onblur="FormCheck('txtDomainAdmin');"></td>
                </tr>
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('network_domain_5'); ?></td>
                    <td class="otherCol_420"><input name="txtDomainAdminPass" type="password" class="inputtext" id="txtDomainAdminPass" value="AdminPass" size="30" maxlength="30" onblur="FormCheck('txtDomainAdminPass');"></td>
                </tr>                   
              </table>  
                    
       		<!-- 4. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_Domain_Info();"/ class="buttons">
       		   										  <img src="../images/btn/btn_cancel.gif" border="0" onclick="Get_Network_Info();showTable('idTable_DOMAIN_EDIT');" class="buttons"/>
       		   		</td>
          			
              </tr>
            </table>
     	</div>  		
		 
		 <!-- Message Table : Start -->	
			<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" >
					<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
							<tr>
									<td height="54px"  background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px"><span class="popup_text"><?php echo lang_get('network_popup_1')?></span></td>
							</tr>
							<tr>
									<td align="center" valign="top" style="padding:0 0 0 0px">
										<div id="system_message"><?php echo lang_get('common_loading')?></div>
									</td>
							</tr>
					</table> 		
			</div>









		                    </td>
  <!-- ?????? ??-->
		 </tr>
			</table></td>
	
		<!-- left Navigation ???? ??-->
    		             </tr>
    		  </table></td>
     			 <!-- ??ucenter ???? ??-->
      
    	</tr>
        			  <!-- bottom ????? ???? -->
<?	 include "../inc/bottom.php";  ?>


<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language='javascript' charset='utf-8'>
	page.init();
</script>
