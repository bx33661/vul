<? include "../inc/top.php";  ?>

<script language="javascript1.2" src="../js/userw.js.php" charset="utf-8"></script>
<script language="javascript1.2" src="../js/common.js.php" charset="utf-8"></script>
<!----------------------------------->

<!-- top 자르는 영역 -->
<script type="text/javascript">
<!--
//-->
</script>
<script type="text/javascript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

//-->
</script>
<style type="text/css">
<!--
.style2 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>

<tr><!--Center row start--> 
  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      	  <!-- Center Left Navigation Start-->
          <td width="245" valign="top"> <? include "../inc/left.php"; ?> </td>
				  <!-- Center Left Navigation End-->
				  
				  <!-- Center Right Start-->
					<td width="100%" valign="top">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
 					        <tr><td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td></tr>
							    <tr> <!-- 중앙내용 시작 -->  
		                  <td valign="top" style="padding:0 0 0 50px">
		                  	
		                  	
		                  	<div id="idTable_UserCreate" style="display:block;">	
		                  	 	  <!-- 1. Headtitle + tab image -->
		                  	 	  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin:40px 0px 0px 0px">
   								         <tr>
    							              <td height="50" valign="top"><img src="../images/headtitle/htit_create_user.gif" /></td>
				                   </tr>
				                   
 							             <tr><!--Step1 Body Start-->
   							                <td height="30" align="center" valign="top">
                                  
        	 	                       <table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
        	 	                       	         <tr>
                                                  <td width="80" background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_01_r.gif" name="step_01" border="0"></td>
						                                      <td width="80" background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_02.gif" name="step_02" border="0"></td>
						                                      <td width="510" background="../images/wizard/tab_line.gif"></td>
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
                                                    <img src="../images/wizard/register_user_step1.gif">
                                                </td>
                                            </tr>
                                          </table>
                                        </td>
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
													<td class="otherCol_420" valign="middle"><input name="txtUserID" type="text" class="inputtext" id="txtUserID" size="40" maxlength="12" onblur="FormCheck('txtUserID');unCheck();"/>
																																	 <img src="../images/btn/btn_id_check.gif" border="0" onClick="IDCheck();" style="cursor:pointer;" >		
													</td>
                      	</tr>
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_2'); ?> *</td>
													<td class="otherCol_420"><input name="txtUserPass1" type="password" class="inputtext" id="txtUserPass1" size="40"  maxlength="20" onblur="FormCheck('txtUserPass1');"/></td>
                      	</tr>
                      	<tr>    
                      		<td class="firstCol_250"><?php echo lang_get('user_create_3'); ?> *</td>
													<td class="otherCol_420"><input name="txtUserPass2" type="password" class="inputtext" id="txtUserPass2" size="40" maxlength="20" onblur="FormCheck('txtUserPass2');"/></td>
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
																<tr><td><input name="txtUserEmail" type="text" class="inputtext" id="txtUserEmail" size="40" maxlength="30"/></td></tr>
														    <tr><td><input type="checkbox" name="checkboxCreate" id="checkboxCreate"><?php echo lang_get('user_create_8'); ?></td></tr>
														 </table>	
												  </td>
                      	</tr>		
                               	                                         	
                   </table>
        										<!-- 3. Buttons --> 					
      											 <table width="670" cellspacing="0" cellpadding="0">
							           		 		<tr>
							           		   		<td width="600" align="right"></td>
							           		   		<td align="right"><img src="../images/btn/btn_save.gif" border="0" onClick="Set_User_Info();" style="cursor:pointer"/></td>
							                  </tr>
							               </table>
												 </div>
		                  	
		                  	<? include "userw_03.php" ?>
		                  	
		                  	<!-- Progress bar Table - Start -->
		                  	<div id="userw_POPUP" style="width:670px; margin-top:40px; display:none" >
														<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
																<tr height="54px">
																		<td background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px"><span class="popup_text"><?php echo lang_get('wizard_8')?></span></td>
																</tr>
																<tr>
																		<td align="center" valign="top" style="padding:0 0 0 0px">
																			<div id="system_message"><?php echo lang_get('common_loading'); ?></div>
																		</td>
																</tr>
														</table> 		
												</div>
		                  	<!-- Progress bar Table - End -->
		                  	
          								
                                      
                                  
                      </td>
   				        </tr>
 			     	 </table>
 			    </td>
		

 			</tr>
		</table>
													

          		           	
          		           	 
  
              
   </td>
	<!-- Center Right End-->
		                  
 

</tr> <!--Center row End--> 


<!-- bottom row -->
<?	 include "../inc/bottom.php";  ?>