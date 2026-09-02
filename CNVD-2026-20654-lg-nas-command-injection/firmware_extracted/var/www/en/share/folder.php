<?	 include "../inc/top.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' charset='utf-8'>
	gPage='time';		// set page name for language setting
</script>
<!----------------------------------->

<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
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

<script language="javascript1.2" src="../js/folder.js.php" charset="utf-8"></script>



<tr>
<!-- ??ucenter ???? ????--> 
	<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<!-- left ????? ???? -->
				<!-- left Navigation ???? ????-->
				<td width="245" valign="top">
					<?	 include "../inc/left.php";  ?></td>
				<!-- left ??-->
				<td width="100%" valign="top"><!-- ?????? ??? -->
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
							<!-- ?????? ??? -->
						</tr>
						<tr>
							<!-- ?????? ???? -->  
							<td width="467" valign="top" style="padding:0 0 0 50px">
							
<!--


##################################################################################################################################################



           Create Domain User 



###################################################################################################################################################



-->

		<div id="idTable_FolderCreate_DomainUser" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" />
</td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_create_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_12'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderNameDomainlUser" type="text" class="inputtext" id="txtFolderNameDomainUser" size="40" maxlength="24" onblur="FormCheck('txtFolderNameDomainUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescDomainUser" type="text" class="inputtext" id="txtFolderDescDomainUser" size="40" maxlength="40" onblur="FormCheck('txtFolderDescDomainUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id='VolumeSelectDomainUser'><?php echo lang_get('common_loading'); ?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinDomainUser" id="chkFolderWinDomainUser">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPDomainUser" id="chkFolderAFPDomainUser">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPDomainUser" id="chkFolderFTPDomainUser">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavDomainUser" id="chkFolderWebdavDomainUser" onclick="webdav_folderAccess('DomainUser');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrDomainUser" id="rdoFolderAttrDomainUser_normal" onclick="Check_box('normal_create');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrDomainUser" id="rdoFolderAttrDomainUser_hidden" onclick="Check_box('hidden_create');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleDomainUser" id="rdoFolderRecyleDomainUser_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleDomainUser" id="rdoFolderRecyleDomainUser_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLDomainUser" id="rdoFolderACLDomainUser_enable" value="enable"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLDomainUser" id="rdoFolderACLDomainUser_disable" value="disable"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
		                           <td width="125">
																		<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_DomainUser');showTable('idTable_FolderCreate_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainUser_Local_user_tab_01','','../images/tab/tab_local_user_on.gif',1)">
																		<img src="../images/tab/tab_local_user_off.gif" name="Create_DomainUser_Local_user_tab_01" border="0" /></a>
															 </td>
															 <td width="125">
																		<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_DomainUser');showTable('idTable_FolderCreate_LocalGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainUser_Local_group_tab_01','','../images/tab/tab_local_group_on.gif',1)">
																		<img  src="../images/tab/tab_local_group_off.gif" name="Create_DomainUser_Local_group_tab_01" border="0" /></a>
															 </td>
															<td width="125" id="Create_DomainUser_Domain_user_tab_01">
																		<a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainUser_Domain_user_tab_01','','../images/tab/tab_domain_user_off.gif',1)">
																		<img src="../images/tab/tab_domain_user_on.gif" name="Create_DomainUser_Domain_user_tab_01" border="0" ></a>
															</td>    
								 							<td width="125" id="Create_DomainUser_Domain_group_tab_01">
																		<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_DomainUser');showTable('idTable_FolderCreate_DomainGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainUser_Domain_group_tab_01','','../images/tab/tab_domain_group_on.gif',1)">
																		<img src="../images/tab/tab_domain_group_off.gif" name="Create_DomainUser_Domain_group_tab_01"  border="0" ></a>
		                          </td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local User List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('user_list_2')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Domain_User" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormCreate('idTable_FolderCreate_DomainUser');Set_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>










<!--


##################################################################################################################################################



         Edit Domain User 



###################################################################################################################################################



-->
		<div id="idTable_FolderEdit_DomainUser" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_edit_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_15'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderNameEditDomainUser"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescEditDomainUser" type="text" class="inputtext" id="txtFolderDescEditDomainUser" size="40" maxlength="40" onblur="FormCheck('txtFolderDescEditDomainUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderVolumeEditDomainUser"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinEditDomainUser" id="chkFolderWinEditDomainUser">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPcEditDomainUser" id="chkFolderAFPEditDomainUser">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPEditDomainUser" id="chkFolderFTPEditDomainUser">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavEditDomainUser" id="chkFolderWebdavEditDomainUser" onclick="webdav_edit_folderAccess('EditDomainUser');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrEditDomainUser" id="rdoFolderAttrEditDomainUser_normal" onclick="Check_box('normal_edit');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrEditDomainUser" id="rdoFolderAttrEditDomainUser_hidden" onclick="Check_box('hidden_edit');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleEditDomainUser" id="rdoFolderRecyleEditDomainUser_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleEditDomainUser" id="rdoFolderRecyleEditDomainUser_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLEditDomainUser" id="rdoFolderACLEditDomainUser_enable" value="enable" onclick="Check_box('acl_on_edit');"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLEditDomainUser" id="rdoFolderACLEditDomainUser_disable" value="disable" onclick="Check_box('acl_off_edit');"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
		                           <td width="125" >
		                           			<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_LocalUser');SyncFormEdit('idTable_FolderEdit_DomainUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainUser_Local_user_tab_01','','../images/tab/tab_local_user_on.gif',1)">
																		<img src="../images/tab/tab_local_user_off.gif" name="Edit_DomainUser_Local_user_tab_01" border="0" ></a>
					     								 </td>
                               <td width="125" >
                               			<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_LocalGroup');SyncFormEdit('idTable_FolderEdit_DomainUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainUser_Local_group_tab_01','','../images/tab/tab_local_group_on.gif',1)">
                               			<img  src="../images/tab/tab_local_group_off.gif" name="Edit_DomainUser_Local_group_tab_01" border="0" ></a>
                               </td>


					     								 <td width="125" id="Edit_DomainUser_Domain_user_tab_01">
																			<a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainUser_Domain_user_tab_01','','../images/tab/tab_domain_user_off.gif',1)">
																			<img src="../images/tab/tab_domain_user_on.gif" name="Edit_DomainUser_Domain_user_tab_01" border="0" ></a>
					     								 </td>    
						 									 <td width="125" id="Edit_DomainUser_Domain_group_tab_01">
																			<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_DomainGroup');SyncFormEdit('idTable_FolderEdit_DomainUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainUser_Domain_group_tab_01','','../images/tab/tab_domain_group_on.gif',1)">
																			<img src="../images/tab/tab_domain_group_off.gif" name="Edit_DomainUser_Domain_group_tab_01" border="0" ></a>
                               </td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local User List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('user_list_2')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Edit_Domain_User" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormEdit('idTable_FolderEdit_DomainUser');Edit_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>







<!--


##################################################################################################################################################



         Create Domain Group 



###################################################################################################################################################



-->
		<div id="idTable_FolderCreate_DomainGroup" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_create_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_12'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderNameDomainGroup" type="text" class="inputtext" id="txtFolderNameDomainGroup" size="40" maxlength="24" onblur="FormCheck('txtFolderNameDomainGroup');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescDomainGroup" type="text" class="inputtext" id="txtFolderDescDomainGroup" size="40" maxlength="40" onblur="FormCheck('txtFolderDescDomainGroup');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id="VolumeSelectDomainGroup"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinDomainGroup" id="chkFolderWinDomainGroup">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPDomainGroup" id="chkFolderAFPDomainGroup">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPDomainGroup" id="chkFolderFTPDomainGroup">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavDomainGroup" id="chkFolderWebdavDomainGroup" onclick="webdav_folderAccess('DomainGroup');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrDomainGroup" id="rdoFolderAttrDomainGroup_normal" onclick="Check_box('normal_create');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrDomainGroup" id="rdoFolderAttrDomainGroup_hidden" onclick="Check_box('hidden_create');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleDomainGroup" id="rdoFolderRecyleDomainGroup_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleDomainGroup" id="rdoFolderRecyleDomainGroup_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLDomainGroup" id="rdoFolderACLDomainGroup_enable" value="enable"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLDomainGroup" id="rdoFolderACLDomainGroup_disable" value="disable" /><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" >
                           <tr>
		                           <td width="125">
																	<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_DomainGroup');showTable('idTable_FolderCreate_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainGroup_Local_user_tab_01','','../images/tab/tab_local_user_on.gif',1)">
																	<img src="../images/tab/tab_local_user_off.gif" name="Create_DomainGroup_Local_user_tab_01" border="0" ></a>
					     								</td>
                              <td width="125">
                              		<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_DomainGroup');showTable('idTable_FolderCreate_LocalGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainGroup_Local_group_tab_01','','../images/tab/tab_local_group_on.gif',1)">
                              		<img src="../images/tab/tab_local_group_off.gif" name="Create_DomainGroup_Local_group_tab_01" border="0" ></a>
                              </td>
  
                              <td width="125" id="Create_DomainGroup_Domain_user_tab_01">
																	<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_DomainGroup');showTable('idTable_FolderCreate_DomainUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainGroup_Domain_user_tab_01','','../images/tab/tab_domain_user_on.gif',1)">
																	<img src="../images/tab/tab_domain_user_off.gif" name="Create_DomainGroup_Domain_user_tab_01" border="0" ></a>
					     								</td>    
						 									<td width="125" id="Create_DomainGroup_Domain_group_tab_01">
																	<a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_DomainGroup_Domain_group_tab_01','','../images/tab/tab_domain_group_off.gif',1)">
																	<img src="../images/tab/tab_domain_group_on.gif" name="Create_DomainGroup_Domain_group_tab_01" border="0" ></a>
					     								</td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local User List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('group_list_1')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Domain_Group" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormCreate('idTable_FolderCreate_DomainGroup');Set_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>







<!--


##################################################################################################################################################



         Edit Domain Group 



###################################################################################################################################################



-->


		<div id="idTable_FolderEdit_DomainGroup" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_edit_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_15'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderNameEditDomainGroup"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescEditDomainGroup" type="text" class="inputtext" id="txtFolderDescEditDomainGroup" size="40" maxlength="40" onblur="FormCheck('txtFolderDescEditDomainGroup');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderVolumeEditDomainGroup"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinEditDomainGroup" id="chkFolderWinEditDomainGroup">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPEditDomainGroup" id="chkFolderAFPEditDomainGroup">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPEditDomainGroup" id="chkFolderFTPEditDomainGroup">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavEditDomainGroup" id="chkFolderWebdavEditDomainGroup" onclick="webdav_edit_folderAccess('EditDomainGroup');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrEditDomainGroup" id="rdoFolderAttrEditDomainGroup_normal" onclick="Check_box('normal_edit');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrEditDomainGroup" id="rdoFolderAttrEditDomainGroup_hidden" onclick="Check_box('hidden_edit');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleEditDomainGroup" id="rdoFolderRecyleEditDomainGroup_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleEditDomainGroup" id="rdoFolderRecyleEditDomainGroup_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLEditDomainGroup" id="rdoFolderACLEditDomainGroup_enable" value="enable"  onclick="Check_box('acl_on_edit');"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLEditDomainGroup" id="rdoFolderACLEditDomainGroup_disable" value="disable" onclick="Check_box('acl_off_edit');"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
		                           <td width="125">
																		<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_LocalUser');SyncFormEdit('idTable_FolderEdit_DomainGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainGroup_Local_user_tab_01','','../images/tab/tab_local_user_on.gif',1)">
																		<img src="../images/tab/tab_local_user_off.gif" name="Edit_DomainGroup_Local_user_tab_01" border="0" ></a>
					     								 </td>
                               <td width="125">
                               			<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_LocalGroup');SyncFormEdit('idTable_FolderEdit_DomainGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainGroup_Local_group_tab_01','','../images/tab/tab_local_group_on.gif',1)">
                               			<img src="../images/tab/tab_local_group_off.gif" name="Edit_DomainGroup_Local_group_tab_01" border="0" ></a>
                               </td>
  
                               <td width="125" id="Edit_DomainGroup_Domain_user_tab_01">
																		<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_DomainUser');SyncFormEdit('idTable_FolderEdit_DomainGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainGroup_Domain_user_tab_01','','../images/tab/tab_domain_user_on.gif',1)">
																		<img src="../images/tab/tab_domain_user_off.gif" name="Edit_DomainGroup_Domain_user_tab_01" border="0" ></a>
					     								 </td>    
						 										<td width="125" id="Edit_DomainGroup_Domain_group_tab_01">
																		<a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_DomainGroup_Domain_group_tab_01','','../images/tab/tab_domain_group_off.gif',1)">
																		<img src="../images/tab/tab_domain_group_on.gif" name="Edit_DomainGroup_Domain_group_tab_01" border="0" ></a>
					     									</td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local User List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('group_list_1')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Edit_Domain_Group" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormEdit('idTable_FolderEdit_DomainGroup');Edit_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>










<!--


##################################################################################################################################################



           Create Local User 



###################################################################################################################################################



-->
		<div id="idTable_FolderCreate_LocalUser" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_create_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_12'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderNameLocalUser" type="text" class="inputtext" id="txtFolderNameLocalUser" size="40" maxlength="24" onblur="FormCheck('txtFolderNameLocalUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescLocalUser" type="text" class="inputtext" id="txtFolderDescLocalUser" size="40" maxlength="40" onblur="FormCheck('txtFolderDescLocalUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id='VolumeSelectUser'><?php echo lang_get('common_loading'); ?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinLocalUser" id="chkFolderWinLocalUser">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPLocalUser" id="chkFolderAFPLocalUser">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPLocalUser" id="chkFolderFTPLocalUser">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavLocalUser" id="chkFolderWebdavLocalUser" onclick="webdav_folderAccess('LocalUser');">Webdav</td>

                                             
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrLocalUser" id="rdoFolderAttrLocalUser_normal" onclick="Check_box('normal_create');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrLocalUser" id="rdoFolderAttrLocalUser_hidden" onclick="Check_box('hidden_create');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleLocalUser" id="rdoFolderRecyleLocalUser_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleLocalUser" id="rdoFolderRecyleLocalUser_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLLocalUser" id="rdoFolderACLLocalUser_enable" value="enable" onclick="Check_box('acl_on_create');"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLLocalUser" id="rdoFolderACLLocalUser_disable" value="disable" onclick="Check_box('acl_off_create');"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
                            <td width="125"><img src="../images/tab/tab_local_user_on.gif" name="Create_LocalUser_Local_user_tab_01" border="0" ></td>
                            <td width="125"><a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_LocalUser');showTable('idTable_FolderCreate_LocalGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_LocalUser_Local_group_tab_01','','../images/tab/tab_local_group_on.gif',1)"><img src="../images/tab/tab_local_group_off.gif" name="Create_LocalUser_Local_group_tab_01" border="0" ></a></td>
 


														<div id="test">
			                            <td width="125" id="Create_LocalUser_Domain_user_tab_01">
																			<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_LocalUser');showTable('idTable_FolderCreate_DomainUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_LocalUser_Domain_user_tab_01','','../images/tab/tab_domain_user_on.gif',1)">
																			<img src="../images/tab/tab_domain_user_off.gif" name="Create_LocalUser_Domain_user_tab_01" border="0" ></a>
								     							</td>    
									 								<td width="125" id="Create_LocalUser_Domain_group_tab_01">
																			<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_LocalUser');showTable('idTable_FolderCreate_DomainGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_LocalUser_Domain_group_tab_01','','../images/tab/tab_domain_group_on.gif',1)">
																			<img src="../images/tab/tab_domain_group_off.gif" name="Create_LocalUser_Domain_group_tab_01" border="0" ></a>
								     							</td>
														</div>

                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local User List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('user_list_2')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Local_User" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormCreate('idTable_FolderCreate_LocalUser');Set_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>











<!--


##################################################################################################################################################



         Edit Local User 

Modified : 2008 / 12 / 15
* Min *

###################################################################################################################################################



-->
		<div id="idTable_FolderEdit_LocalUser" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_edit_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_15'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderNameEditLocalUser"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescEditLocalUser" type="text" class="inputtext" id="txtFolderDescEditLocalUser" size="40" maxlength="40" onblur="FormCheck('txtFolderDescEditLocalUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderVolumeEditLocalUser"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinEditLocalUser" id="chkFolderWinEditLocalUser">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPEditLocalUser" id="chkFolderAFPEditLocalUser">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPEditLocalUser" id="chkFolderFTPEditLocalUser">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavEditLocalUser" id="chkFolderWebdavEditLocalUser" onclick="webdav_edit_folderAccess('EditLocalUser');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrEditLocalUser" id="rdoFolderAttrEditLocalUser_normal" onclick="Check_box('normal_edit');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrEditLocalUser" id="rdoFolderAttrEditLocalUser_hidden" onclick="Check_box('hidden_edit');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleEditLocalUser" id="rdoFolderRecyleEditLocalUser_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleEditLocalUser" id="rdoFolderRecyleEditLocalUser_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLEditLocalUser" id="rdoFolderACLEditLocalUser_enable" value="enable" onclick="Check_box('acl_on_edit');"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLEditLocalUser" id="rdoFolderACLEditLocalUser_disable" value="disable" onclick="Check_box('acl_off_edit');"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
                               <td width="125">
																	<a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalUser_Local_user_tab_01','','../images/tab/tab_local_user_off.gif',1)">
																	<img src="../images/tab/tab_local_user_on.gif" name="Edit_LocalUser_Local_user_tab_01" border="0" ></a>
					     								 </td>
                               <td width="125">
                               		<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_LocalGroup');SyncFormEdit('idTable_FolderEdit_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalUser_Local_group_tab_01','','../images/tab/tab_local_group_on.gif',1)">
                               		<img src="../images/tab/tab_local_group_off.gif" name="Edit_LocalUser_Local_group_tab_01" border="0" ></a>
															 </td>
 
                              <td width="125" id="Edit_LocalUser_Domain_user_tab_01">
																	<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_DomainUser');SyncFormEdit('idTable_FolderEdit_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalUser_Domain_user_tab_01','','../images/tab/tab_domain_user_on.gif',1)">
																	<img src="../images/tab/tab_domain_user_off.gif" name="Edit_LocalUser_Domain_user_tab_01" border="0" ></a>
					     								</td>    
						 									<td width="125" id="Edit_LocalUser_Domain_group_tab_01">
																	<a href="javascript:void(0)" onclick="showTable('idTable_FolderEdit_DomainGroup');SyncFormEdit('idTable_FolderEdit_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalUser_Domain_group_tab_01','','../images/tab/tab_domain_group_on.gif',1)">
																	<img src="../images/tab/tab_domain_group_off.gif" name="Edit_LocalUser_Domain_group_tab_01" border="0" ></a>
					     								</td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local User List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('user_list_2')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Edit_Local_User" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormEdit('idTable_FolderEdit_LocalUser');Edit_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>


		




<!--


##################################################################################################################################################

         Create Local Group 

Modified : 2008 / 12 /15 
Min

###################################################################################################################################################



-->

		<div id="idTable_FolderCreate_LocalGroup" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_create_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_12'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderNameLocalGroup" type="text" class="inputtext" id="txtFolderNameLocalGroup" size="40" maxlength="24" onblur="FormCheck('txtFolderNameLocalGroup');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescLocalGroup" type="text" class="inputtext" id="txtFolderDescLocalGroup" size="40" maxlength="40" onblur="FormCheck('txtFolderDescLocalGroup');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id='VolumeSelectGroup'><?php echo lang_get('common_loading'); ?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinLocalGroup" id="chkFolderWinLocalGroup">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPLocalGroup" id="chkFolderAFPLocalGroup">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPLocalGroup" id="chkFolderFTPLocalGroup">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavLocalGroup" id="chkFolderWebdavLocalGroup" onclick="webdav_folderAccess('LocalGroup');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrLocalGroup" id="rdoFolderAttrLocalGroup_normal" onclick="Check_box('normal_create');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrLocalGroup" id="rdoFolderAttrLocalGroup_hidden" onclick="Check_box('hidden_create');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleLocalGroup" id="rdoFolderRecyleLocalGroup_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleLocalGroup" id="rdoFolderRecyleLocalGroup_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLLocalGroup" id="rdoFolderACLLocalGroup_enable" value="enable" onclick="Check_box('acl_on_create');"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLLocalGroup" id="rdoFolderACLLocalGroup_disable" value="disable" onclick="Check_box('acl_off_create');"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
                            <td width="125"><a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_LocalGroup');showTable('idTable_FolderCreate_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_LocalGroup_Local_user_tab_01','','../images/tab/tab_local_user_on.gif',1)">
															<img src="../images/tab/tab_local_user_off.gif" name="Create_LocalGroup_Local_user_tab_01" border="0" ></a></td>
                            <td width="125"><img src="../images/tab/tab_local_group_on.gif" name="Create_LocalGroup_Local_group_tab_01" border="0" ></td>
 

                            <td width="125" id="Create_LocalGroup_Domain_user_tab_01">
																<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_LocalGroup');showTable('idTable_FolderCreate_DomainUser');"   onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_LocalGroup_Domain_user_tab_01','','../images/tab/tab_domain_user_on.gif',1)">
																<img src="../images/tab/tab_domain_user_off.gif" name="Create_LocalGroup_Domain_user_tab_01" border="0" ></a>
														</td>    
														<td width="125" id="Create_LocalGroup_Domain_group_tab_01">
																<a href="javascript:void(0)" onclick="SyncFormCreate('idTable_FolderCreate_LocalGroup');showTable('idTable_FolderCreate_DomainGroup');"  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Create_LocalGroup_Domain_group_tab_01','','../images/tab/tab_domain_group_on.gif',1)">
																<img src="../images/tab/tab_domain_group_off.gif" name="Create_LocalGroup_Domain_group_tab_01" border="0" ></a>
														</td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local Group List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('group_list_1')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Local_Group" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormCreate('idTable_FolderCreate_LocalGroup');Set_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>











<!--


##################################################################################################################################################



         Edit Local Group 



###################################################################################################################################################



-->

		<div id="idTable_FolderEdit_LocalGroup" style='display:none'>
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_edit_folder.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('folder_create_15'); ?></td>
                        </tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_list_1'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderNameEditLocalGroup"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_3'); ?></td>
        										<td class="otherCol_420"><input name="txtFolderDescEditLocalGroup" type="text" class="inputtext" id="txtFolderDescEditLocalGroup" size="40" maxlength="40" onblur="FormCheck('txtFolderDescEditLocalUser');"/></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('common_volume'); ?></td>
        										<td class="otherCol_420"><div id="txtFolderVolumeEditLocalGroup"><?php echo lang_get('common_loading')?></div></td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_4'); ?></td>
        										<td class="otherCol_420">
        														<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                       <tr>
                                             <td width="120"><input type="checkbox" name="chkFolderWinEditLocalGroup" id="chkFolderWinEditLocalGroup">Windows </td>
                                             <td width="120"><input type="checkbox" name="chkFolderAFPEditLocalGroup" id="chkFolderAFPEditLocalGroup">AFP (Mac)</td>
                                             <td width="120"><input type="checkbox" name="chkFolderFTPEditLocalGroup" id="chkFolderFTPEditLocalGroup">FTP</td>
                                             <td width="120"><input type="checkbox" name="chkFolderWebdavEditLocalGroup" id="chkFolderWebdavEditLocalGroup" onclick="webdav_edit_folderAccess('EditLocalGroup');">Webdav</td>
                                           </tr>
                                    </table>
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_5'); ?></td>
        										<td class="otherCol_420">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                     <td width="120"><input type="radio" name="rdoFolderAttrEditLocalGroup" id="rdoFolderAttrEditLocalGroup_normal" onclick="Check_box('normal_edit');"><?php echo lang_get('folder_create_13'); ?></td>
                                     <td width="240"><input type="radio" name="rdoFolderAttrEditLocalGroup" id="rdoFolderAttrEditLocalGroup_hidden" onclick="Check_box('hidden_edit');"><?php echo lang_get('folder_create_14'); ?></td>
                                   </tr>
                               </table>	
        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250"><?php echo lang_get('folder_create_6'); ?></td>
        										<td class="otherCol_420">
        												<table width="360" border="0" cellspacing="0" cellpadding="0" id="win">
                                   <tr>
                                           <td width="120"><input type="radio" name="rdoFolderRecyleEditLocalGroup" id="rdoFolderRecyleEditLocalGroup_enable"><?php echo lang_get('common_enable'); ?></td>
                                           <td width="240"><input type="radio" name="rdoFolderRecyleEditLocalGroup" id="rdoFolderRecyleEditLocalGroup_disable"><?php echo lang_get('common_disable'); ?></td>
																		</tr>
																</table>

        										</td>
        								</tr>
        								<tr>
        										<td class="firstCol_250" style="background-color:#d0d0d0;border:none;"><?php echo lang_get('folder_create_9'); ?></td>
        										<td class="otherCol_420" style="background-color:#d0d0d0;border:none;">
        											<table width="360" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                         <td width="120"><input type="radio" name="rdoFolderACLEditLocalGroup" id="rdoFolderACLEditLocalGroup_enable" value="enable" onclick="Check_box('acl_on_edit');"/><?php echo lang_get('common_enable'); ?></td>
                                         <td width="240"><input type="radio" name="rdoFolderACLEditLocalGroup" id="rdoFolderACLEditLocalGroup_disable" value="disable" onclick="Check_box('acl_off_edit');"/><?php echo lang_get('common_disable'); ?></td>
                                 </tr>
                               </table>
                            </td>
        								</tr>
								</table>	
								
								<!-- Tab -->
								<table width="670px" cellspacing="0px" cellpadding="0px" style="background-color:#d0d0d0;">
									<tr>
										<td style="padding-left:20px;">
												<table width="250" border="0" cellspacing="0" cellpadding="0" id="tab" bgcolor="#5d5d5a">
                           <tr>
                                <td width="125" >
																		<a href="javascript:void(0)" onclick="SyncFormEdit('idTable_FolderEdit_LocalGroup');showTable('idTable_FolderEdit_LocalUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalGroup_Local_user_tab_01','','../images/tab/tab_local_user_on.gif',1)">
																		<img src="../images/tab/tab_local_user_off.gif" name="Edit_LocalGroup_Local_user_tab_01" border="0" ></a>
					   									  </td>
                                <td width="125" >
                                		<a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalGroup_Local_group_tab_01','','../images/tab/tab_local_group_off.gif',1)">
                                		<img src="../images/tab/tab_local_group_on.gif" name="Edit_LocalGroup_Local_group_tab_01" border="0" ></a>
																</td>
  
  															<td width="125" id="Edit_LocalGroup_Domain_user_tab_01">
																			<a href="javascript:void(0)" onclick="SyncFormEdit('idTable_FolderEdit_LocalGroup');showTable('idTable_FolderEdit_DomainUser');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalGroup_Domain_user_tab_01','','../images/tab/tab_domain_user_on.gif',1)">
																			<img src="../images/tab/tab_domain_user_off.gif" name="Edit_LocalGroup_Domain_user_tab_01" border="0" ></a>
																</td>    
																<td width="125" id="Edit_LocalGroup_Domain_group_tab_01">
																			<a href="javascript:void(0)" onclick="SyncFormEdit('idTable_FolderEdit_LocalGroup');showTable('idTable_FolderEdit_DomainGroup');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Edit_LocalGroup_Domain_group_tab_01','','../images/tab/tab_domain_group_on.gif',1)">
																			<img src="../images/tab/tab_domain_group_off.gif" name="Edit_LocalGroup_Domain_group_tab_01" border="0" ></a>
																</td>
                        	</tr>
                     		</table>
										</td>
									</tr>
					     	</table>
					     	
					     	<!-- Local Group List -->
					     	<div style="width:670px;height:370px;background-color:#d0d0d0;">
					     	<table width="630px" cellspacing="0px" cellpadding="0px" style="margin-left:20px;">
					     		<tr>
					     			<td class="header" style="width:200px"><?php echo lang_get('group_list_1')?></td>
					     			<td class="header" style="width:200px"><?php echo lang_get('folder_create_10')?></td>
					     			<td class="header" style="width:230px"><?php echo lang_get('folder_create_11')?></td>
					     		</tr>
					     	</table>
					     	<div id="Permission_Edit_Local_Group" style="overflow-y:scroll; width:630px; height:320px;margin-left:20px;background-color:#fff;">Loading ... </div>
					    </div>
					    
					  <!-- Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0" style="margin:20 0 20 0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="SyncFormEdit('idTable_FolderEdit_LocalGroup');Edit_Folder_Info();" class="buttons"/>
                                  <img src="../images/btn/btn_cancel.gif"  border="0" onclick="Get_Folder_Info(); " class="buttons"/>
                </td>
          			
              </tr>
            </table>
		</div>










<!--
##################################################################################################################################################



         Folder List 



###################################################################################################################################################
-->
			<div id="idTable_FolderList" style="display:block">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_folder.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_registered_folder_list.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
									<tr>
										<td width="200px" class="header" ><?php echo lang_get('folder_list_1')?></td>
										<td width="470px" class="header" ><?php echo lang_get('folder_list_2')?></td>
									</tr>
								</table>
								<div id="FolderList" style="overflow-y:scroll; width:668px; height:320px; border:1px solid #bcbcbc;margin-bottom:20px;"><?php echo lang_get('common_loading')?></div>
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_add_folder.gif"  border="0" onclick="check_volume_config();" class="buttons"/>
                                              <img src="../images/btn/btn_delete.gif"  border="0" onclick="Delete_Folder_Info();" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	</div>	 	


<!--
##################################################################################################################################################



         Popup Message 



###################################################################################################################################################
-->
 				
					<!-- Message Table : Start -->	
					<div id="idTable_Folder_POPUP" style="width:670px; margin-top:40px; display:none" >
							<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
									<tr>
											<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;">
												 <span class="popup_text"><?php echo lang_get('folder_create_1')?></span>
											</td>
									</tr>
									<tr>
											<td align="center" valign="top" style="padding:0 0 0 0px">
												<div id="system_message">&nbsp;</div>
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
init();
function init()
{
	Get_Folder_Info();
//	GetDomainType();
//	GetMaxVolume();
//	GetLocalUserList();
//	GetLocalGroupList();
//	GetDomainUserList();
//	GetDomainGroupList();


//	GetFolderLocalUserMember('test');
//	GetFolderLocalGroupMember('test');
//	GetFolderDomainUserMember('test');
//	GetFolderDomainGroupMember('test');


/*	document.getElementById('Create_DomainUser_Domain_user_tab_01').style.display = "none";
	document.getElementById('Create_DomainUser_Domain_group_tab_01').style.display = "none";
	document.getElementById('Edit_DomainUser_Domain_user_tab_01').style.display = "none";
	document.getElementById('Edit_DomainUser_Domain_group_tab_01').style.display = "none";
	
	document.getElementById('Create_DomainGroup_Domain_user_tab_01').style.display = "none";
	document.getElementById('Create_DomainGroup_Domain_group_tab_01').style.display = "none";
	document.getElementById('Edit_DomainGroup_Domain_user_tab_01').style.display = "none";
	document.getElementById('Edit_DomainGroup_Domain_group_tab_01').style.display = "none";

	document.getElementById('Create_LocalUser_Domain_user_tab_01').style.display = "none";
	document.getElementById('Create_LocalUser_Domain_group_tab_01').style.display = "none";
	document.getElementById('Edit_LocalUser_Domain_user_tab_01').style.display = "none";
	document.getElementById('Edit_LocalUser_Domain_group_tab_01').style.display = "none";
	
	document.getElementById('Create_LocalGroup_Domain_user_tab_01').style.display = "none";
	document.getElementById('Create_LocalGroup_Domain_group_tab_01').style.display = "none";
	document.getElementById('Edit_LocalGroup_Domain_user_tab_01').style.display = "none";
	document.getElementById('Edit_LocalGroup_Domain_group_tab_01').style.display = "none";
	
*/	
	
}
</script>


