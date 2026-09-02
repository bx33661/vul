<? 

header("Expires: -1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');

include "../inc/top.php"; 

?>

<!---------------------------------
// LGE NAS-SSS 
// By oneshot97
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' charset='utf-8'>
	gPage='power';		// set page name for language setting
</script>
<!----------------------------------->

<!-- top 자르는 영역 -->

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

<script language="javascript1.2" src="../js/power.js.php" charset="utf-8"></script>

<tr>
<!-- 전체center 영역 시작-->
	<td valign="top">
	<table width="100%"	border="0" cellspacing="0" cellpadding="0">
		<tr>
		<!-- left 자르는 영역 -->
		<!-- left Navigation 영역 시작-->
			<td width="245" valign="top"><?	 include "../inc/left.php";	 ?></td>
			<!--	left 끝-->
			<td	width="100%" valign="top"><!-- 사이즈 수정 -->
			<table width="100%"	border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td	width="100%" height="7"	background="../images/Top/utility_shadow.gif"></td>
					<!--	사이즈 수정	-->
				</tr>
				<tr>
				<!-- 중앙내용	시작 -->
					<td valign="top" style="padding:0	0 0	50px">
						
						
					<!-- Page Title -->	 				 
 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
	   					<tr>
	    						<td height="50" valign="top"><img src="../images/headtitle/htit_power.gif" /></td>
			  		</tr>
			  	</table>	
			  		
			  	
			  	<!-- Standby Mode Setup -->
			  	<div id="idTable_Power_Hib" style='display:block'>
			  	<!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib01','','../images/tab/tab_standby_on.gif',1)"><img	src="../images/tab/tab_standby_on.gif" name="power_tab_Hib01" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups01','','../images/tab/tab_ups_setting_over.gif',1)"><img src="../images/tab/tab_ups_setting.gif" name="power_tab_Ups01" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche01','','../images/tab/tab_schedule_power_over.gif',1)"><img src="../images/tab/tab_schedule_power.gif" name="power_tab_Sche01" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>               
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown01','','../images/tab/tab_shutdown_over.gif',1)"><img src="../images/tab/tab_shutdown.gif" name="power_tab_Shutdown01" border="0"></a></td>
                </tr>
           </table>

					<!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header"><?php echo lang_get('standby'); ?></td>
                    <td class="header"><div id="id_HibEna"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_hibernation_2'); ?></td>
                    <td class="otherCol_420"><div id="id_HibWait"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
              </table>  
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_edit.gif"  border="0" onclick="editMode_Hib();" class="buttons"/></td>
              </tr>
            </table>
					</div>

					<!-- Standby Mode Edit -->
					
					<div id="idTable_Power_Hib_Edit" style='display:none'>
			  	<!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib02','','../images/tab/tab_standby_on.gif',1)"><img	src="../images/tab/tab_standby_on.gif" name="power_tab_Hib02" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups02','','../images/tab/tab_ups_setting_over.gif',1)"><img src="../images/tab/tab_ups_setting.gif" name="power_tab_Ups02" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche02','','../images/tab/tab_schedule_power_over.gif',1)"><img src="../images/tab/tab_schedule_power.gif" name="power_tab_Sche02" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>                    
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown02','','../images/tab/tab_shutdown_over.gif',1)"><img src="../images/tab/tab_shutdown.gif" name="power_tab_Shutdown02" border="0"></a></td>
                </tr>
           </table>

					<!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header"><?php echo lang_get('standby'); ?></td>
                    <td class="header"><input type="radio" name="rdoHibEna" id="rdoHibEnable"	value="on"  onClick="setEnable();"/><?php echo lang_get('common_enable'); ?>
                    									 <input type="radio" name="rdoHibEna" id="rdoHibDisable"	value="off"  onClick="setEnable();"/><?php echo lang_get('common_disable'); ?>
                    </td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_hibernation_2'); ?></td>
                    <td class="otherCol_420"><select name="time_gubun" class="selectbox"	style="DISPLAY:	block;	WIDTH: 100px;	HEIGHT:	20px;" id="sltHibWait">
																								<option	value="10" selected="selected">10 <?php echo lang_get('common_minute_2')?></option>
																								<option	value="30">30 <?php echo lang_get('common_minute_2')?></option>
																								<option	value="60">60 <?php echo lang_get('common_minute_2')?></option>
																								<option	value="120">120 <?php echo lang_get('common_minute_2')?></option>
																						</select>
                    </td>
                </tr>
              </table>  
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_apply.gif"  border="0" onclick="setHib();" class="buttons"/>
       		   											<img src="../images/btn/btn_cancel.gif"  border="0" onclick="openHib();" class="buttons"/>
       		   		</td>
              </tr>
            </table>
					</div>		
							
					<!-- UPS Power Setting -->
					<div id="idTable_Power_Ups" style='display:none'>
			  	<!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib03','','../images/tab/tab_standby_over.gif',1)"><img src="../images/tab/tab_standby.gif" name="power_tab_Hib03" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups03','','../images/tab/tab_ups_setting_on.gif',1)"><img	src="../images/tab/tab_ups_setting_on.gif" name="power_tab_Ups03" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche03','','../images/tab/tab_schedule_power_over.gif',1)"><img src="../images/tab/tab_schedule_power.gif" name="power_tab_Sche03" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>                       
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown03','','../images/tab/tab_shutdown_over.gif',1)"><img src="../images/tab/tab_shutdown.gif" name="power_tab_Shutdown03" border="0"></a></td>
                </tr>
           </table>

					<!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header"><?php echo lang_get('power_ups_1'); ?></td>
                    <td class="header"><div id="id_UpsEna"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_ups_2'); ?></td>
                    <td class="otherCol_420"><div id="id_UpsCable"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_ups_3'); ?></td>
                    <td class="otherCol_420"><div id="id_UpsShutdown"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_ups_4'); ?></td>
                    <td class="otherCol_420"><div id="id_UpsPowerOff"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
              </table>  
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_edit.gif"  border="0" onclick="editMode_Ups();" class="buttons"/></td>
              </tr>
            </table>
					</div>		
					
					<!-- UPS Power Edit -->
					<div id="idTable_Power_Ups_Edit" style='display:none'>        
			  	<!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib04','','../images/tab/tab_standby_over.gif',1)"><img src="../images/tab/tab_standby.gif" name="power_tab_Hib04" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups04','','../images/tab/tab_ups_setting_on.gif',1)"><img	src="../images/tab/tab_ups_setting_on.gif" name="power_tab_Ups04" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche04','','../images/tab/tab_schedule_power_over.gif',1)"><img	src="../images/tab/tab_schedule_power.gif" name="power_tab_Sche04" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>                    
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown04','','../images/tab/tab_shutdown_over.gif',1)"><img src="../images/tab/tab_shutdown.gif" name="power_tab_Shutdown04" border="0"></a></td>
                </tr>
           </table>

					<!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header"><?php echo lang_get('power_ups_1'); ?></td>
                    <td class="header"><input	type="radio" name="rdoUpsEna" id="rdoUpsEnable"	value="on"	/><?php echo lang_get('common_enable'); ?>
                    								   <input	type="radio" name="rdoUpsEna" id="rdoUpsDisable"	value="off"	/><?php echo lang_get('common_disable'); ?>
                    </td>
                </tr>
                
								<tr>
                    <td class="firstCol_250"><?php echo lang_get('power_ups_2'); ?></td>
                    <td class="otherCol_420"><?php echo lang_get('power_ups_6'); ?></td>
                </tr>
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_ups_3'); ?></td>
                    <td class="otherCol_420">
                    												<table	width="100%" border="0" cellspacing="0" cellpadding="0" id="sh_table_01">
														<tr>
															<td width="20"><input	type="radio" name="rdoUpsShutdown" id="rdoUpsShutdownTime" value="time" /></td>
															<td><table width="100%"	border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td><?php echo lang_get('power_ups_7'); ?></td>
																	<td>
																		<!-- select	box	시작 -->	
																		<select	size="1" name="second_gubun"	class="selectbox"	style="DISPLAY:	block;	WIDTH: 50px; HEIGHT: 20px;" id="sltUpsMinutes">
																			<option	value="5" selected="selected">5</option>
																			<option	value="30">30</option>
																			<option	value="60">60</option>
																		</select>
																		<!-- select	box	끝 -->
																	</td>
																	<td><?php echo lang_get('power_ups_8'); ?></td>
																</tr>
															</table>
															</td>
														</tr>
														<tr>
															<td	width="20"><input	type="radio" name="rdoUpsShutdown" id="rdoUpsShutdownLow"	value="low"	/></td>
															<td><?php echo lang_get('power_ups_9'); ?></td>
														</tr>
													</table>
                    </td>
                </tr>
 
                
                <tr>
                    <td class="firstCol_250"><?php echo lang_get('power_ups_4'); ?></td>
                    <td class="otherCol_420"><input	type="radio" name="rdoUpsPower" id="rdoUpsPowerOffEnable"	value="on"	/><?php echo lang_get('power_ups_11'); ?>
                    												 <input	type="radio" name="rdoUpsPower" id="rdoUpsPowerOffDisable"	value="off"	/><?php echo lang_get('power_ups_10'); ?>	
                    </td>
                </tr>

              </table>  
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_apply.gif"  border="0" onclick="setUps();" class="buttons"/>
       		   											<img src="../images/btn/btn_cancel.gif"  border="0" onclick="openUps();" class="buttons"/>
       		   		</td>
              </tr>
            </table>
            
		</div>		


  	<!-- Schedule Hibernation Setup -->
	  	<div id="idTable_Power_Sche" style='display:none'>
  		  <!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib05','','../images/tab/tab_standby_over.gif',1)"><img	src="../images/tab/tab_standby.gif" name="power_tab_Hib05" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups05','','../images/tab/tab_ups_setting_over.gif',1)"><img src="../images/tab/tab_ups_setting.gif" name="power_tab_Ups05" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche05','','../images/tab/tab_schedule_power_on.gif',1)"><img src="../images/tab/tab_schedule_power_on.gif" name="power_tab_Sche05" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>               
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown05','','../images/tab/tab_shutdown_over.gif',1)"><img src="../images/tab/tab_shutdown.gif" name="power_tab_Shutdown05" border="0"></a></td>
                </tr>
                </table>

	       <!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header"><?php echo lang_get('power_schedule_1'); ?></td>
                    <td class="header"><div id="id_ScheEna"><?php echo lang_get('common_loading'); ?></div></td>
                </tr>
                 
		  <tr>
			<td class="firstCol_250"><?php echo lang_get('power_schedule_2')?></td>
			<td class="otherCol_420">
			        <div id="id_ScheOnTime"><?php echo lang_get('common_loading'); ?>
				</div>
			</td>
		 </tr>
		  <tr>
			<td class="firstCol_250"><?php echo lang_get('power_schedule_3')?></td>
			<td class="otherCol_420">
			        <div id="id_ScheOffTime"><?php echo lang_get('common_loading'); ?>
				</div>
			</td>
		 </tr>		 
               </table>  
                
       	<!-- 3. Buttons --> 
       	<table width="670" cellspacing="0" cellpadding="0">
       	 	<tr>
       	  		<td align="right"><img src="../images/btn/btn_edit.gif"  border="0" onclick="editMode_Sche();" class="buttons"/></td>
              	</tr>
              </table>
	     </div>

	<!-- Schedule Hibernation Edit -->					
	     <div id="idTable_Power_Sche_Edit" style='display:none'>
			  	<!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib06','','../images/tab/tab_standby_over.gif',1)"><img src="../images/tab/tab_standby.gif" name="power_tab_Hib06" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups06','','../images/tab/tab_ups_setting_over.gif',1)"><img src="../images/tab/tab_ups_setting.gif" name="power_tab_Ups06" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche06','','../images/tab/tab_schedule_power_on.gif',1)"><img src="../images/tab/tab_schedule_power_on.gif" name="power_tab_Sche06" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>                    
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown06','','../images/tab/tab_shutdown_over.gif',1)"><img src="../images/tab/tab_shutdown.gif" name="power_tab_Shutdown06" border="0"></a></td>
                </tr>
           </table>

					<!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header"><?php echo lang_get('power_schedule_1'); ?></td>
                    <td class="header"><input type="radio" name="rdoScheEna" id="rdoScheEnable"	value="on"  onClick="setEnable_Sche();"/><?php echo lang_get('common_enable'); ?>
                    									 <input type="radio" name="rdoScheEna" id="rdoScheDisable"	value="off"  onClick="setEnable_Sche();"/><?php echo lang_get('common_disable'); ?>
                    </td>
                </tr>
                

		<tr>
			<td class="firstCol_250"><?php echo lang_get('power_schedule_2')?></td>
			<td class="otherCol_420">
			        <div id="id_ScheOnTime">
					 <span id="cms_time_Shour"></span><?php echo lang_get('common_hour_1'); ?>
			 		 <span id="cms_time_Smin"></span><?php echo lang_get('common_minute_1'); ?>
				</div>
				
			</td>			
		</tr>

		<tr>
			<td class="firstCol_250"><?php echo lang_get('power_schedule_3')?></td>
			<td class="otherCol_420">
			        <div id="id_ScheOffTime">
			 		 <span id="cms_time_Ehour"></span><?php echo lang_get('common_hour_1'); ?>
			 		 <span id="cms_time_Emin"></span><?php echo lang_get('common_minute_1'); ?>
				</div>	 				
			</td>
		</tr>
     
              </table>  
                    
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_apply.gif"  border="0" onclick="setSche();" class="buttons"/>
       		   											<img src="../images/btn/btn_cancel.gif"  border="0" onclick="openSchedule();" class="buttons"/>
       		   		</td>
              </tr>
            </table>
		</div>		

















				
					
					<!-- Shutdown -->
					<div id="idTable_Power_Shutdown" style="display:none">
					
			  	<!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openHib();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Hib07','','../images/tab/tab_standby_over.gif',1)"><img	src="../images/tab/tab_standby.gif" name="power_tab_Hib07" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openUps();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Ups07','','../images/tab/tab_ups_setting_over.gif',1)"><img src="../images/tab/tab_ups_setting.gif" name="power_tab_Ups07" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="javascript:void(0)" onclick="openSchedule();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Sche07','','../images/tab/tab_schedule_power_over.gif',1)"><img	src="../images/tab/tab_schedule_power.gif" name="power_tab_Sche07" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>                    
                    <td class="tab"><a href="javascript:void(0)" onclick="openShutdown();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('power_tab_Shutdown07','','../images/tab/tab_shutdown_on.gif',1)"><img src="../images/tab/tab_shutdown_on.gif" name="power_tab_Shutdown07" border="0"></a></td>

                </tr>
           </table>
					<!-- 2. Contents -->   
					
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>
                	<td valign="top" width="670px" height="170px"><img src="../images/icon/img_system_02.jpg"/>      
                	<span class="red_text_9" style="position:relative;top:-130px;left:150px;"><?php echo lang_get('power_shutdown_1'); ?></span>
                		
                	</td>
                </tr>
              </table>
              		
       		<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_restart.gif"  border="0" onclick="open_power_restart_alert();" class="buttons"/>
       		   											<img src="../images/btn/btn_shutdown.gif"  border="0" onclick="open_power_shutdown_alert();" class="buttons"/>
       		   		</td>
              </tr>
            </table>
            
  
					</div>			
							      

			
							</td>
						</tr>
					</table>
			</td>
					<!-- 중앙내용	끝-->
				</tr>
			</table>
			</td>
</tr>


<!-- bottom 자르는 영역 -->
<? include "../inc/bottom.php";  ?>
<!--popup windows-->

<? include 'power_shutdown.php' ?>
<? include 'power_restart.php' ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script language='javascript' charset='utf-8'>
init();
function init()
{
	debug('init : '+gPage+' menu');		// initialize
	// to do 
	// 1)language text
	// 2)
	Get_Hib_Info();
	//Get_Ups_Info();
}
</script>

