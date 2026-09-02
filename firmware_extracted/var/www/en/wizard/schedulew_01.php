<?	 include "../inc/top.php";  ?>

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

ul{ list-style:none; margin:0px; padding:0px; }
li{ list-style:none; margin:0px; padding:0px; }

a:link { text-decoration: none; color:#777777; }
a:visited { text-decoration: none; color:#777777; }
a:active { text-decoration: none; }
a:hover { text-decoration: underline; }
.folderDiv{ margin-top:2px; }
.pageDiv{ margin-top:2px; }
.cursor{ cursor:pointer; }

.saturday a:link { text-decoration: none; color:#3300cc; }
.sunday a:link { text-decoration: none; color:#cc0000; }

.recMenuTotal{
	overflow:auto;
	width:670px;
	height:380px;
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
<body onLoad="MM_preloadImages('../images/tab_ntp_on.gif','../images/tab/tab_host.gif','../images/tab/tab_workgroup_on.gif','../images/tab/tab_network.gif','../images/tab/tab_volume_on.gif','../images/tab/tab_usblist.gif','../images/tab/tab_saved_on.gif','../images/tab/tab_rsny.gif','../images/tab/tab_rsnylist_on.gif','../images/wizard/tab_step_01.gif','../images/wizard/tab_step_02_r.gif','../images/wizard/tab_step_03_r.gif')">

<tr>
          <!-- 전체center 영역 시작--> <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          			          <tr>
			
			            <!-- left 자르는 영역 -->
			
             

			            <!-- left Navigation 영역 시작-->
					         <td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
				       <!-- left 끝-->
					        <td width="100%" valign="top"><!-- 사이즈 수정 -->
 			    <table width="100%" border="0" cellspacing="0" cellpadding="0">
 					        <tr>
   				            <td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
                   <!-- 사이즈 수정 -->
                  </tr>
							  <tr>
		  <!-- 중앙내용 시작 -->  <td valign="top" style="padding:0 0 0 50px">
		  	
		  	<div id="browser_table">
		  	    	<!-- Head Title -->
		  				<table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;">
   								<tr>
    							  <td height="50" valign="top"><img src="../images/headtitle/htit_scheduling.gif"/></td>
				  				</tr>
				  		</table>
				  		
				  		<!-- Tab -->
  				     <table width="670" border="0" cellspacing="0" cellpadding="0">

                       	         <tr>
                                      <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_01_r.gif" name="step_01" border="0"></td>
                                      <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_02.gif" name="step_02" border="0"></td>
                                      <td width="510"  background="../images/wizard/tab_line.gif"></td>
                                 </tr>
               </table>
               
               <!-- Step title -->
               <table width="670" border="0" cellspacing="0" cellpadding="0"  style="margin-top:20px;">
                  <tr>
                    <td width="91"><img src="../images/icon/img_one_01.gif" width="91" height="83"></td>
                    <td valign="top" class="red_s2" style="padding:5 0 0 0px">
                    <img src="../images/wizard/sch_backup_step1.gif"></td>
                  </tr>
                </table>
                                  
                                         
							 <!-- File Box Header -->
               <table width="670" border="0" cellspacing="0" cellpadding="0">
                 <!-- Browser Top : Start-->
                 <tr>
                   <td height="25" class="header"><span id="idPath"><?php echo lang_get('common_loading'); ?></span></td>
                 </tr>
                </table>
            
               <!-- 스크롤 감싸는 테이블 시작 -->
               <div style="width:670px; height:350px;">
               	
										<table cellpadding="0" cellspacing="0" border="0" width="670px" height="30px">
											<tbody>
												<tr height="30" >					
													<td width="400px" align="center" bgcolor="#E3E3E3"><?php echo lang_get('common_name'); ?></td>
													<td width="100px" align="center" bgcolor="#E3E3E3"><?php echo lang_get('schedule_restore_1'); ?></td>
													<td width="170px" align="center" bgcolor="#E3E3E3"><?php echo lang_get('common_date'); ?></td>
						
												</tr>
											</tbody>
										</table>				
										<div id="file_box_loading" align="center" style="overflow-y:scroll; width:669px; height:320px;background-color:#fff;border-left:1px solid #e5e5e5;display:none;"><img src="../images/Burn/file_box_loading.gif" style="margin-top:130px;"></div>
							      <div id="file_box" style="overflow-y:scroll; width:669px; height:320px;background-color:#fff;border-left:1px solid #e5e5e5;"></div>
										
                    
                    
                    
							 </div>
							 
                 <!-- Source -->
								<table width="670" height="25px" Border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td width="670px" class="header">
                           <?php echo lang_get('common_source'); ?> : <span id="target_folder"><?php echo lang_get('common_none'); ?></span></td>
                           
                         </tr>
                </table>
								
								<!-- Buttons -->
								<table width="670px" border="0" style="margin-top:20px">
                 <tr>
										<td width="670px" align="right">
    										<a href="javascript:void(0)"><img src="../images/btn/btn_next.gif" border="0" onClick="showTable('detail_table');"/></a>
        						</td>
        					</tr>
        				</table>
	  		</div>
	
				<? include "schedulew_02.php" ?>
 				 
 				 </td>
		  <!-- 중앙내용 끝-->
 				       </tr>
					</table></td>
				
				<!-- left Navigation 영역 끝-->
   		      </tr>
        		  </table></td>
<!-- 전체center 영역 끝-->
          
        		  </tr>
        			  <!-- bottom 자르는 영역 -->
         			 <?	 include "../inc/bottom.php";  ?>

<script language="javascript1.2" src="../js/schedulew.js.php" charset="utf-8"></script>     
<script language="javascript1.2" src="../js/zxml.js" charset="utf-8"></script>      			 
<script language='javascript' src="../js/jslb_ajax.js.php"></script>
<script language='javascript' src='../js/prototype.js'></script>
<script language='javascript' charset='utf-8'>
init();
function init()
{
  startLoad('schedule');
  
	//var start_path = "/mnt/fs/Vol1/system/Backup";
  //showBrowser(start_path);
  showCal();
  init_select();
}

</script>