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

<script language='javascript' type="text/javascript">
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

<script language="javascript1.2" src="../js/user.js.php" charset="utf-8"></script>

<script language='javascript' charset='utf-8'>
function clearpass(){
	if(document.getElementById('txtUserPass2Edit').value == '**********' && document.getElementById('txtUserPass1Edit').value == '**********'){ 
		document.getElementById('txtUserPass2Edit').value = '';
		document.getElementById('txtUserPass1Edit').value = '';
	}
}

function validate_email(form_id) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById(form_id).value;
   if(document.getElementById(form_id).value=='') return true;
   if(reg.test(address) == false) {
      document.getElementById(form_id).value=''; 
      alert("<?php echo lang_get('mail_msg_5')?>");
      return false;
   }
}
</script>



<tr>
	<!-- ��ücenter ���� ����--> 
<td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<!-- left �ڸ��� ���� -->
	<!-- left Navigation ���� ����-->
	<td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
	<!-- left ��-->
	<td width="100%" valign="top"><!-- ������ ��� -->
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
				<!-- ������ ��� -->
			</tr>
			<tr>
			<!-- �߾ӳ��� ���� -->
				<td valign="top" style="padding:0 0 0 50px">
					
					
			<!-- Create User -->
			<div id="idTable_UserCreate" style="display:none">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_user.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_cre.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('user_create_1'); ?></td>
                        </tr>
                        <!-- Step1 table contents rows start --> 
                        <tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_list_1'); ?> *</td>
													<td class="otherCol_420" valing="middle"><input name="txtUserID" type="text" class="inputtext" id="txtUserID" size="40" maxlength="12" onblur="FormCheck('txtUserID');"/></td>
                      	</tr>
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_2'); ?> *</td>
													<td class="otherCol_420"><input name="txtUserPass1" type="password" class="inputtext" id="txtUserPass1" size="40"  maxlength="20" onblur="FormCheck_PW('txtUserPass1');"/></td>
                      	</tr>
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_3'); ?> *</td>
													<td class="otherCol_420"><input name="txtUserPass2" type="password" class="inputtext" id="txtUserPass2" size="40" maxlength="20" onblur="FormCheck_PW('txtUserPass2');"/></td>
                      	</tr>		   
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_list_2'); ?></td>
													<td class="otherCol_420"><input name="txtUserName" type="text" class="inputtext" id="txtUserName" size="40" maxlength="30" onblur="FormCheck('txtUserName');"/></td>
                      	</tr>		                                            	
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_4'); ?></td>
													<td class="otherCol_420"><input name="txtUserDesc" type="text" class="inputtext" id="txtUserDesc" size="40" maxlength="40" onblur="FormCheck('txtUserDesc');"/></td>
                      	</tr>		     
                      	<tr>    
                      		<td class="firstCol_250" style="height:50px"><?php echo lang_get('user_create_5'); ?></td>
													<td class="otherCol_420">
														<table width="300px" cellspacing="0" cellpadding="0">
																<tr><td><input name="txtUserEmail" type="text" class="inputtext" id="txtUserEmail" size="40" maxlength="30" onblur="validate_email('txtUserEmail');" /></td></tr>
																							
														    <tr><td><input type="checkbox" name="checkboxCreate" id="checkboxCreate"><?php echo lang_get('user_create_8'); ?></td></tr>
														 </table>	
												  </td>
                      	</tr>		
                               	                                         	
                   </table>
 
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_create.gif"  border="0" onclick="Set_User_Info();" class="buttons"/ >
                                              <img src="../images/btn/btn_cancel.gif"  border="0" onclick="showTable('idTable_UserList');" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	</div>	 
					
			<!-- Edit User -->
			<div id="idTable_UserEdit" style="display:none">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_user.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_edit.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
					              <tr>
                            <td colspan="2" class="header"><?php echo lang_get('user_create_1'); ?></td>
                        </tr>
                        <!-- Step1 table contents rows start --> 
                        <tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_list_1'); ?> *</td>
													<td class="otherCol_420"><div id="txtUserIDEdit"><?php echo lang_get('common_loading')?></div></td>
                      	</tr>
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_2'); ?> *</td>
													<td class="otherCol_420"><input name="txtUserPass1Edit" type="password" class="inputtext" id="txtUserPass1Edit" size="30" maxlength="20" onclick="clearpass();" onblur="FormCheck_PW('txtUserPass1Edit');"/></td>
                      	</tr>
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_3'); ?> *</td>
													<td class="otherCol_420"><input name="txtUserPass2Edit" type="password" class="inputtext" id="txtUserPass2Edit" size="30" maxlength="20" onclick="clearpass();" onblur="FormCheck_PW('txtUserPass2Edit');"/></td>
                      	</tr>		   
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_list_2'); ?></td>
													<td class="otherCol_420"><input name="txtUserNameEdit" type="text" class="inputtext" id="txtUserNameEdit" size="30" maxlength="30" onblur="FormCheck('txtUserNameEdit');"/></td>
                      	</tr>		                                            	
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_4'); ?></td>
													<td class="otherCol_420"><input name="txtUserDescEdit" type="text" class="inputtext" id="txtUserDescEdit" size="30" maxlength="30" onblur="FormCheck('txtUserDescEdit');"/></td>
                      	</tr>		     
                      	<tr>    
                      		<td class="firstCol_250" style="height:50px"><?php echo lang_get('user_create_5'); ?></td>
													<td class="otherCol_420">
														<table width="300px" cellspacing="0" cellpadding="0">
																<tr><td><input name="txtUserEmailEdit" type="text" class="inputtext" id="txtUserEmailEdit" size="30" maxlength="30" onblur="validate_email('txtUserEmailEdit');" /></tr>
														    <tr><td><input type="checkbox" name="checkboxEmailEdit" id="checkboxEmailEdit"><?php echo lang_get('user_create_8'); ?></td></tr>
														 </table>	
												  </td>
                      	</tr>		
                               	                                         	
                   </table>
 
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="Edit_User_Info();" class="buttons"/ >
                                              <img src="../images/btn/btn_cancel.gif"  border="0" onclick="showTable('idTable_UserList');" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	</div>	 					
					
					
			<!-- User List-->
			<div id="idTable_UserList" style="display:block">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_user.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_reg.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
									<tr>
										<td width="150px" class="header" ><?php echo lang_get('user_list_1')?></td>
										<td width="150px" class="header" ><?php echo lang_get('user_list_2')?></td>
										<td width="170px" class="header" ><?php echo lang_get('user_create_10')?></td>
										<td width="200px" class="header" ><?php echo lang_get('user_list_3')?></td>
									</tr>
								</table>
								<div id="userList" style="overflow-y:scroll; width:668px; height:320px; border:1px solid #bcbcbc;margin-bottom:20px;"><?php echo lang_get('common_loading')?></div>
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_create_user.gif"  border="0" onclick="clearForm('idTable_UserCreate');showTable('idTable_UserCreate');" class="buttons"/>
                                              <img src="../images/btn/btn_delete.gif"  border="0" onclick="Delete_User_Info()" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	</div>	 						
				
				
					<!-- Message Table : Start -->	
					<div id="idTable_USER_POPUP" style="width:670px; margin-top:40px; display:none" >
							<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
									<tr>
											<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;">
												 <span class="popup_text"><?php echo lang_get('user_create_11')?></span>
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
				<!-- �߾ӳ��� ��-->
			</tr>
		</table>
	</td>
	
</tr>

</table>
</td>
<!-- ��ücenter ���� ��-->
</tr>

<!-- bottom �ڸ��� ���� -->
<?	 include "../inc/bottom.php";  ?>





<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script language='javascript' charset='utf-8'>
init();
function init()
{
	
/*
	var i=0;
	var user = new Array('userID','userName','email','desc');
	var user_table_entry = new String();
	for(i=0;i<30;i++){
	 	user_table_entry=user_table_entry
				+"<table width=\"650\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >"
                                +"<tr><td width=\"150\" bgcolor=\"#f5f5f7\" class=\"m_gray_12\" style=\"padding:0 0 0 20px\">"
                                +"<input type=\"checkbox\" name=\"checkbox"+i+"\" id=\"chkUserList"+i+"\">"+user[0]+"</td>"
                                +"<td width=\"1\" height=\"25\" bgcolor=\"#e3e3e3\"></td>"
                                +"<td width=\"130\" style=\"padding:0 0 0 20px\" class=\"m_gray_12\">"+user[1]+"</td>"
                                +"<td width=\"1\" height=\"25\" bgcolor=\"#e3e3e3\"></td>"
                                +"<td width=\"200\" bgcolor=\"#f5f5f7\" class=\"m_gray_12\" style=\"padding:0 0 0 20px\">"+user[2]+"</td>"
                                +"<td width=\"1\" height=\"25\" bgcolor=\"#e3e3e3\"></td>"
                                +"<td width=\"167\" class=\"m_gray_12\" style=\"padding:0 0 0 20px\">"+user[3]+"</td>"
				+"</tr></table></td></tr><tr><td height=\"1\" bgcolor=\"#e3e3e5\"></td></tr>";
	}
	document.getElementById('userList').innerHTML = user_table_entry;
*/
	GetUserList();	
}
</script>



