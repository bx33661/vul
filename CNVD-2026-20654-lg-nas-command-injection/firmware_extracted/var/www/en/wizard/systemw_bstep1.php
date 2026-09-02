<? include "../inc/top.php";  ?>
<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' charset='utf-8'>
	gPage='time';		// set page name for language setting
</script>
<script language="javascript1.2" src="../js/systemw_bstep.js.php" charset="utf-8"></script>
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
		                  		
		                  	<!-- Basic Step 1 : Start -->	
		                  	 <div id="systemw_bstep1" style="display:block;">	
		                  	 	  <!-- 1. Headtitle + tab image -->
		                  	 	  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin:40px 0px 0px 0px">
   								         <tr>
    							              <td height="50" valign="top"><img src="../images/headtitle/htit_system_setting.gif" /></td>
				                   </tr>
				                   
 							             <tr><!--Step1 Body Start-->
   							                <td height="30" align="center" valign="top">
                                  
        	 	                       <table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
	 	                       	         <tr>
                                         <td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_01','','../images/wizard/tab_step_01.gif',1)"><img src="../images/wizard/tab_step_01_r.gif" name="step_01" border="0"/></a></td>
													  							<td width="80" background="../images/wizard/tab_line.gif"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('step_02','','../images/wizard/tab_step_02_r.gif',1)"><img src="../images/wizard/tab_step_02.gif" name="step_02" border="0" onClick="showTable('systemw_bstep2');"></a></td>
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
                                                    <img src="../images/wizard/system_setting_step1.gif">
                                                </td>
                                            </tr>
                                          </table>
                                        </td>
   					                          </tr><!-- Step1 information Row End-->
   					                </table>
   					                <!-- 2. Contents -->
          					         <table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">         
          					              <tr>
                                      <td colspan="2" class="header"><?php echo lang_get('wizard_1'); ?></td>
                                  </tr>
                                  <!-- Step1 table contents rows start --> 
                                  <tr>    
                                		<td class="firstCol_250"><?php echo lang_get('wizard_2'); ?></td>
         														<td class="otherCol_420"><input name="txtHOSTNAME" type="text" class="inputtext" id="txtHOSTNAME" value="<?php echo lang_get('common_loading'); ?>" size="30" maxlength="12"></td>
                                	</tr>
                                	<tr>    
                                		<td class="firstCol_250"><?php echo lang_get('wizard_3'); ?></td>
         														<td class="otherCol_420"><input name="txtHOSTDESC" type="text" class="inputtext" id="txtHOSTDESC" value="<?php echo lang_get('common_loading'); ?>" size="30" maxlength="24"></td>
                                	</tr>
                                	<tr>    
                                		<td class="firstCol_250"><?php echo lang_get('firmware_backup_2'); ?></td>
         														<td class="otherCol_420"><div id='id_FirmUpVer'><?php echo lang_get('common_loading'); ?></div></td>
                                	</tr>		   
                                	<tr>    
                                		<td class="firstCol_250"><?php echo lang_get('wizard_4'); ?></td>
         														<td class="otherCol_420"><div id='currentTime'><?php echo lang_get('common_loading'); ?></div></td>
                                	</tr>		                                            	
                                	<tr>    
                                		<td class="firstCol_250"><?php echo lang_get('time_date_2'); ?></td>
         														<td class="otherCol_420"><div id='timeZone'><?php echo lang_get('common_loading'); ?></div> </td>
                                	</tr>		     
                                	<tr>    
                                		<td class="firstCol_250"><?php echo lang_get('network_servers_1'); ?></td>
         														<td class="otherCol_420"><div id='FTPstatus'><?php echo lang_get('common_loading'); ?></div> </td>
                                	</tr>		
                                	<tr>    
                                		<td class="firstCol_250"><?php echo lang_get('time_ntp_1'); ?></td>
         														<td class="otherCol_420"><div id='NTPstatus'><?php echo lang_get('common_loading'); ?></div> </td>
                                	</tr>		                                       	                                         	
                             </table>
        										<!-- 3. Buttons --> 					
      											 <table width="670" cellspacing="0" cellpadding="0">
							           		 		<tr>
							           		   		<td width="600" align="right"></td>
							           		   		<td align="right"><img src="../images/btn/btn_next.gif" border="0"  onClick="showTable('systemw_bstep2');" style="cursor:pointer"/></td>
							                  </tr>
							               </table>
												 </div>
          		           <? include "systemw_bstep2.php" ?>
          		           <? include "systemw_bstep3.php" ?>
          		           
          		           
          								
          						  <!-- Message Table : Start -->	
												<div id="systemw_bstep_POPUP" style="width:670px; margin-top:40px; display:none" >
														<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
																<tr height="54px">
																		<td background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px"><span class="popup_text"><?php echo lang_get('wizard_7')?></span></td>
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
   				        </tr><!-- 중앙내용 끝-->
              </table>
              
          </td>
		      <!-- Center Right End-->
		                  
 			</tr></table>
  </td>
</tr> <!--Center row End--> 


<!-- bottom row -->
<?	 include "../inc/bottom.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script language='javascript' charset='utf-8'>
init();
function init()
{
	//debug('init : '+gPage+' menu');		// initialize
	// to do 
	// 1)language text
	// 2)
	Get_Bstep_Info();
	
	

	

}

</script>