<?	 include "../inc/top.php";  ?><!-- top 자르는 영역 -->

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<!-- Debugging setting -->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>
<!-- Ajax lib -->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>
<script language="javascript1.2" src="../js/jquery-1.2.6.pack.js" charset="utf-8"></script>
<!-- Common lib -->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>
<!-- Comnso lib -->
<script language="javascript1.2" src="../js/comnso_restore_view.js" charset="utf-8"></script>
<script language="javascript1.2" src="../js/comnso_resfview.js.php" charset="utf-8"></script>
<!-- Schedule -->
<script language="javascript1.2" src="../js/schedule.js.php" charset="utf-8"></script>
<!----------------------------------->

<SCRIPT LANGUAGE="JavaScript">
function save_SchedulePATH(volIndex)
{
	var	ON_OFF, CYCLE,CYCLE_DAY,BACKUP_METHOD;
	var 	WORKGROUP1,WORKGROUP2,WORKGROUP3,WORKGROUP4,DOMAIN_TYPE;
	
	if(document.getElementById('cms_sch_cycle').value == "none")
	{
		alert("cycle");
		ON_OFF="off";		
	}
	else
	{
		if(document.getElementById('cms_sch_cycle').value == "daily")
			CYCLE = "1d"; 			
		if(document.getElementById('cms_sch_cycle').value == "weekly")
		{
			CYCLE = "1w";
			CYCLE_DAY = document.getElementById('cms_sch_week').value;		
		}
		if(document.getElementById('cms_sch_cycle').value == "monthly")
			CYCLE = "1m";
			
		ON_OFF="on";
	}
	
	//CYCLE	= document.getElementById('cms_sch_cycle').value ;
	
	
	if (document.getElementById('cms_direc_incre').checked)
		 BACKUP_METHOD ='Incremental'
	 else
	 	 BACKUP_METHOD='Full'	
	
	WORKGROUP1   	= "/mnt/disk" + document.getElementById('cms_source1').value;
	WORKGROUP2	= "/mnt/disk" +document.getElementById('cms_source2').value;
	WORKGROUP3	= "/mnt/disk" +document.getElementById('cms_source3').value;
	WORKGROUP4	= "/mnt/disk" +document.getElementById('cms_source4').value;
	WORKGROUP5	= "/mnt/disk" +document.getElementById('cms_source5').value;
	
	var _txText =	'&SCHDULEBACKUP='+ON_OFF
			+'&SCHDULEBACKUP_CYCLE='+CYCLE
			+'&SCHDULEBACKUP_CYCLE_DAY='+CYCLE_DAY
			+'&SCHDULEBACKUP_METHOD='+BACKUP_METHOD
			+'&SCHDULEBACKUP_PATH_1='+WORKGROUP1
			+'&SCHDULEBACKUP_PATH_2='+WORKGROUP2
			+'&SCHDULEBACKUP_PATH_3='+WORKGROUP3
			+'&SCHDULEBACKUP_PATH_4='+WORKGROUP4
			+'&SCHDULEBACKUP_PATH_5='+WORKGROUP5;

	
	alert(_txText);
	
	sendRequest(onLoadST,_txText,'post',"../php/schedule_path.php",true,true);

	if(!confirm('Completed!')) return true;
	
	return true;
}
function onLoadST(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);	
	
	code = res.split(':');
	//alert(code);
	
	if(code[0] == 'ok') {display_POPUP(code[1]);}
	
}

</SCRIPT>








<!-- Style sheets -->
<style type="text/css">
<!--
/* td styles */
.first {
	width:200px;
	height:25px;
	background-color:#f5f5f7;
	padding-left:20px;
	border-left:1px solid #e3e3e3;
	border-right:1px solid #e3e3e3;
	border-bottom:1px solid #e3e3e3;
}
.second {
	width:440px;
	height:25px;
	background-color:white;
	padding-left:20px;
	border-right:1px solid #e3e3e3;
	border-bottom:1px solid #e3e3e3
}
-->
</style>




<!--Input field ID for selected folder path in the child window-->
<input type="hidden" id="idInputFieldId" value="" />
<input type="hidden" id="idPathMode" value="schedule" /><!--For folder browser : rip/store/burn/schedule-->
<!--For folder browser : end : rip/store/burn/schedule-->

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




<!-- Task number value for progress popup window -->
<input type='hidden' id='id_task_number' value="" />


<tr>
<!-- 전체center 영역 시작-->
<td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	
<!-- left 자르는 영역 -->
<!-- left Navigation 영역 시작-->
<td width="245" valign="top">
	<?	 include "../inc/left.php";  ?></td><!-- left 끝-->
		
				       
<td width="100%" valign="top"><!-- 사이즈 수정 -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td><!-- 사이즈 수정 -->
	</tr>
	<tr>
	<!-- 중앙내용 시작 -->
		<td valign="top" style="padding:0 0 0 50px">
			<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table">
			<tr>
				<td height="40"></td>
			</tr>
			<tr>
				<td height="50" valign="top"><img src="../images/headtitle/htit_scheduling.gif"/></td>
			</tr>
 							   <tr>
   							   <td height="30" align="center" valign="top">
                               <!-- 중앙 테이블 영역 시작-->
        	 	 <table width="670" border="0" cellspacing="0" cellpadding="0" id="network_table">
          					  <tr>
          					    <td valign="top">
          					    <!-- 전체 탭 테이블 시작 -->
          					    <table width="670" border="0" cellspacing="0" cellpadding="0" id="all_network_tab_table">
                                  <tr>
                                    <td height="28">
    
    
 
    
    
                      
<!--Tab (1)--->                             

<!--Tab (2)--->

<!--Tab : end--->  

                                    </td>
                                  </tr>
                                  
                                </table>
                                <!-- 전체 탭 테이블 끝 -->
                                </td>
   					    </tr>
          					  
          					  <tr>
            					  <td height="20"></td>
   					    </tr>
          					  <tr>
           					   <td valign="top"><!-- 테이블 영역 시작-->
           					   
           				
<!--Table 1-->           	
  				   
          		<!-- JUNY : First Table -->
			<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
             					<tr>
               					<td class="header" colspan="2"><?php echo lang_get('schedule_backup_0')?></td>
										  		</tr>
										  		<tr>
												<td class="firstCol_250"><?php echo lang_get('common_source')?> 1</td>
												<td class="otherCol_420">
													<table border="0" cellspacing="0px" cellpadding="0px">
										      		<tr>
										      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('cms_source1');" style="cursor:pointer;"></td>
										      			<td><input name="cms_source1" type="text" class="inputtext" id="cms_source1" size="40" value="" disabled /></td>
										      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
										      		</tr>
										      			</table>												
												</tr>
												
												<tr>
												<td class="firstCol_250"><?php echo lang_get('common_source')?> 2</td>
												<td class="otherCol_420">
													<table border="0" cellspacing="0px" cellpadding="0px">
										      		<tr>
										      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('cms_source2');" style="cursor:pointer;"></td>
										      			<td><input name="cms_source2" type="text" class="inputtext" id="cms_source2" size="40" value="" disabled /></td>
										      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
										      		</tr>
										      			</table>		
												</tr>
																			<tr>
												<td class="firstCol_250"><?php echo lang_get('common_source')?> 3</td>
												<td class="otherCol_420">
													<table border="0" cellspacing="0px" cellpadding="0px">
										      		<tr>
										      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('cms_source3');" style="cursor:pointer;"></td>
										      			<td><input name="cms_source3" type="text" class="inputtext" id="cms_source3" size="40" value="" disabled /></td>
										      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
										      		</tr>
										      			</table>		
												</tr>
																			<tr>
												<td class="firstCol_250"><?php echo lang_get('common_source')?> 4</td>
												<td class="otherCol_420">
													<table border="0" cellspacing="0px" cellpadding="0px">
										      		<tr>
										      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('cms_source4');" style="cursor:pointer;"></td>
										      			<td><input name="cms_source4" type="text" class="inputtext" id="cms_source4" size="40" value="" disabled /></td>
										      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
										      		</tr>
										      			</table>		
												</tr>
											
												<tr>
												<td class="firstCol_250"><?php echo lang_get('common_source')?> 5</td>
												<td class="otherCol_420">
													<table border="0" cellspacing="0px" cellpadding="0px">
										      		<tr>
										      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('cms_source5');" style="cursor:pointer;"></td>
										      			<td><input name="cms_source5" type="text" class="inputtext" id="cms_source5" size="40" value="" disabled /></td>
										      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
										      		</tr>
										      			</table>
			      									</td>
			      									
												</tr>
											
																								
											<tr>
													<td class="subHeader" colspan="2"><?php echo lang_get('schedule_backup_1'); ?></td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('common_cycle')?></td>
												<td class="otherCol_420">		  					 
														<table border="0" cellspacing="0" cellpadding="0" ><tr><td>
										  					 <select class="selectbox_80" id='cms_sch_cycle' onChange="setDetail();">
																	   <option value="none"><?php echo lang_get('common_none'); ?></option>
																	   <option value="daily"><?php echo lang_get('common_daily'); ?></option>
																	   <option value="weekly"><?php echo lang_get('common_weekly'); ?></option>
																	   <option value="monthly"><?php echo lang_get('common_monthly'); ?></option>
															   </select></td>
													   <td>
														   	<span id="cms_date" style="display:none"></span>
														   	<span id="cms_day"  style="display:none">
													 		  	   <select class="selectbox_80" id='cms_sch_week'>
																		   <option value="sun"><?php echo lang_get('common_day_7'); ?></option>
																		   <option value="mon"><?php echo lang_get('common_day_1'); ?></option>
																		   <option value="tue"><?php echo lang_get('common_day_2'); ?></option>
																		   <option value="wed"><?php echo lang_get('common_day_3'); ?></option>
																		   <option value="thu"><?php echo lang_get('common_day_4'); ?></option>
																		   <option value="fri"><?php echo lang_get('common_day_5'); ?></option>
																		   <option value="sat"><?php echo lang_get('common_day_6'); ?></option>
														  	 		</select>
															  </span>
												  	</td>
													</tr>
												</table>
											</td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('common_time')?></td>
												<td class="otherCol_420">
													 <span id="cms_time_hour"></span><?php echo lang_get('common_hour_1'); ?>
		          						 <span id="cms_time_min"></span><?php echo lang_get('common_minute_1'); ?>
												</td>
											</tr>
											
											<tr>
												<td class="firstCol_250"><?php echo lang_get('schedule_backup_3')?></td>
												<td class="otherCol_420">
														<input type="radio" name="radio" id="cms_direc_incre" value="radio" />Incremental
														<input type="radio" name="radio" id="cms_direc_full" value="radio" />Full
												</td>
											</tr>
									
        		</table>


<!-- Table 1 : end-->

<!--Table 2-->
<? include "schedule_table2.php" ?>
<!--Table 2 : end-->

<!--Table 3-->
<? include "schedule_table3.php" ?>
<!--Table 3 : end-->

		    <!-- Buttons -->
		    
				<table width="670px" border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="display:block;margin-top:10px;">
					<tr>
						<td width="670px" align="right">
								<img src="../images/btn/btn_save.gif" border="0" onclick='save_SchedulePATH();' class="buttons">
						</td>
					</tr>
				</table>



</td>
</tr>
</table>
<!-- 중앙 테이블 영역 끝-->
</td>


   				 </tr>
 				 </table>
 				 </td>
		  <!-- 중앙내용 끝-->
 				 </tr>
					</table>
				</td>
				
				
   		      </tr>
        		  </table>
        		  </td>
      <!-- 전체center 영역 끝-->
          
        		  </tr>
        			  <!-- bottom 자르는 영역 -->
         			 <?	 include "../inc/bottom.php";  ?>
         			 
         			 
         			 
<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script type="text/javascript" charset='utf-8'>
<!--
function Init_Method()
{
	document.getElementById('cms_direc_incre').checked = true;	
	
}
Init_Method();
page.init();
//-->
</script>

