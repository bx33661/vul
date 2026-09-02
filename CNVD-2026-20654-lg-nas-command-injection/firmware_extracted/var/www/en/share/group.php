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

<script language="javascript1.2" src="../js/group.js.php" charset="utf-8"></script>



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
					
					<!-- Group List -->
					<div id="idTable_Group_List" style="display:block">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_group.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_regi_group.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 
                <table width="670" border="0" cellspacing="0" cellpadding="0">         
									<tr>
										<td width="200px" class="header" ><?php echo lang_get('group_list_1')?></td>
										<td width="470px" class="header" ><?php echo lang_get('group_list_2')?></td>
									</tr>
								</table>
								<div id="groupList" style="overflow-y:scroll; width:668px; height:320px; border:1px solid #bcbcbc;margin-bottom:20px;"><?php echo lang_get('common_loading')?></div>
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_create_newgroup.gif"  border="0" onclick="GetUserList();clearForm('idTable_Group_Create');showTable('idTable_Group_Create');" class="buttons"/>
                                              <img src="../images/btn/btn_delete.gif"  border="0" onclick="Delete_Group_Info()" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	    </div>	
					
					
					<!-- New group -->
					<div id="idTable_Group_Create" style="display:none">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_group.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_create_group.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 								
                <table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
									<tr>
										<td width="200px" class="header" ><?php echo lang_get('group_list_1')?></td>
										<td width="470px" class="header" ><?php echo lang_get('group_list_2')?></td>
									</tr>
									<tr>
										<td class="firstCol_250" style="background-color:#fff;"><input name="txtGroupName" type="text" class="inputtext" id="txtGroupName" value="" size="20" maxlength="20" onblur="FormCheck('txtGroupName');"/></td>
										<td class="otherCol_420"><input name="txtGroupDesc" type="text" class="inputtext" id="txtGroupDesc" value="" size="40" maxlength="40" onblur="FormCheck('txtGroupDesc');"/></td>
									</tr>
								</table>
								
								<div style="margin-bottom:5px;font-weight:bold;"><img src="../images/icon/bullet.gif" / style="margin-right:10px;"><?php echo lang_get('group_create_1')?></div>
								<table width="670" border="0" cellspacing="0" cellpadding="0">         
									<tr>
										<td width="150px" class="header" ><?php echo lang_get('user_list_1')?></td>
										<td width="200px" class="header" ><?php echo lang_get('user_list_2')?></td>
										<td width="320px" class="header" ><?php echo lang_get('user_list_3')?></td>
									</tr>
								</table>
								<div id="userListCreate" style="overflow-y:scroll; width:668px; height:320px; border:1px solid #bcbcbc;margin-bottom:20px;"><?php echo lang_get('common_loading')?></div>
                   
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="Set_Group_Info();" class="buttons"/>
                                              <img src="../images/btn/btn_cancel.gif"  border="0" onclick="showTable('idTable_Group_List');" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	    </div>					
					
					<!-- Edit group -->
					<div id="idTable_Group_Edit" style="display:none">
		 			<!-- 1. headTitle-->	 				 
	 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_group.gif" /></td>
				  		</tr>
				  		<tr>
				  					<td height="30" valign="top"><img src="../images/subtitle/stit_edit_group.gif" ></td>
				  		</tr>
				  	</table>
	 				 				
              
           <!-- 2. Contents -->   
 								
                <table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
									<tr>
										<td width="200px" class="header" ><?php echo lang_get('group_list_1')?></td>
										<td width="470px" class="header" ><?php echo lang_get('group_list_2')?></td>
									</tr>
									<tr>
										<td class="firstCol_250" style="background-color:#fff;"><div id='txtGroupEdit'><?php echo lang_get('common_loading')?></div></td>
										<td class="otherCol_420"><input name="txtGroupDescEdit" type="text" class="inputtext" id="txtGroupDescEdit" value="Loading..." size="40" maxlength="40" onblur="FormCheck('txtGroupDescEdit');"/></td>
									</tr>
								</table>
								
								<div style="margin-bottom:5px;font-weight:bold;"><img src="../images/icon/bullet.gif" / style="margin-right:10px;"><?php echo lang_get('group_edit_1')?></div>
								<table width="670" border="0" cellspacing="0" cellpadding="0">         
									<tr>
										<td width="150px" class="header" ><?php echo lang_get('user_list_1')?></td>
										<td width="200px" class="header" ><?php echo lang_get('user_list_2')?></td>
										<td width="320px" class="header" ><?php echo lang_get('user_list_3')?></td>
									</tr>
								</table>
								<div id="userList" style="overflow-y:scroll; width:668px; height:320px; border:1px solid #bcbcbc;margin-bottom:20px;"><?php echo lang_get('common_loading')?></div>
                   
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_save.gif"  border="0" onclick="Edit_Group_Info();" class="buttons"/>
                                              <img src="../images/btn/btn_cancel.gif"  border="0" onclick="showTable('idTable_Group_List');" class="buttons"/>
                </td>
          			
              </tr>
            </table>
     	    </div>						
					
					
					<!-- Message Table : Start -->	
					<div id="idTable_Group_POPUP" style="width:670px; margin-top:40px; display:none" >
							<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
									<tr>
											<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;">
												 <span class="popup_text"><?php echo lang_get('group_create_2')?></span>
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
			</tr>
		
		</table>
		

		
	</td>
	<!-- �߾ӳ��� ��-->
</tr>
					
</table>
</td>

</tr>












        		  
<!-- ��ücenter ���� ��-->
<!-- bottom �ڸ��� ���� -->
<? include "../inc/bottom.php";  ?>




<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script language='javascript' charset='utf-8'>
init();
function init()
{
	// to do 
	// 1)language text
	// 2)
	Get_Group_Info();		// get timezone list from server
	//ShowGroupEdit('users');
	//GetUserList();

	
}
</script>




