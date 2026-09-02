<?	 include "../inc/top.php";  ?>

          <!-- top 자르는 영역 -->

          <script type="text/javascript">
<!--
//-->
</script>
 <!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>
<script language='javascript' charset='utf-8'>
	gPage='usb';		// set page name for language setting
</script>
<!----------------------------------->




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
<body onLoad="MM_preloadImages('../images/tab_ntp_on.gif','../images/tab/tab_host.gif','../images/tab/tab_workgroup_on.gif','../images/tab/tab_network.gif','../images/tab/tab_volume_on.gif','../images/wizard/tab_step_01.gif','../images/wizard/tab_step_02_r.gif','../images/wizard/tab_step_03_r.gif')">


<!--Input field ID for selected folder path in the child window-->
<input type="hidden" id="idInputFieldId" value="" />
<input type="hidden" id="idPathMode" value="usb" /><!--For folder browser : rip/store/burn/schedule-->
<!--For folder browser : end : rip/store/burn/schedule-->


<tr>
          <!-- 전체center 영역 시작--> <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          			          <tr>
			
			            <!-- left 자르는 영역 -->
			
             

			            <!-- left Navigation 영역 시작-->
					         <td width="245" valign="top"><?	 include "../inc/left.php";  ?>
				       <!-- left 끝-->
					        <td width="100%" valign="top"><!-- 사이즈 수정 -->
 			    <table width="100%" border="0" cellspacing="0" cellpadding="0">
 					        <tr>
   				            <td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
                   <!-- 사이즈 수정 -->
                            </tr>
							  <tr>
		  <!-- 중앙내용 시작 -->  <td valign="top" style="padding:0 0 0 50px">
		  	
		  	
		  	
		  	
		  	<!-- Step 1 : Start -->
		  		<div id="mobilew_step1">
							<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table">
									<tr>
										  <td height="40" colspan="4" ></td>
								  </tr>
									 <tr>
											  <td height="50" valign="top" colspan="4" ><img src="../images/headtitle/htit_mobile.gif"/></td>
								  </tr>
									<tr>
												  
								                              <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_01_r.gif" name="step_01" border="0"></td>
								                              <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_02.gif" name="step_02" border="0"></td>
								                              <td width="80"  background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_03.gif" name="m_step_03" border="0"></td>
								                              <td width="430"  background="../images/wizard/tab_line.gif">&nbsp;</td>
								  </tr>
								  <tr><td height="20"></td></tr>
								   <tr>	
									  		<td colspan="4">
									  			<table><tr>		
									  			<td width="91"><img src="../images/icon/img_one_01.gif" width="91" height="83"></td>
							            <td valign="top" class="red_s2" style="padding:5 0 0 0px"><img src="../images/wizard/mobile_sync_step1.gif"></td>
									  		  </tr></table>
									  		</td>
								  </tr>	
	
							</table>  	
							
							<table width="670" border="0" cellspacing="0" cellpadding="0">	  
	          		
	          			<tr>
	                    <td valign="top"><img src="../images/wizard//img_Nas.gif" id="usbImage"></td>
	                    <td width="420" valign="top" rowspan="2">
	                                    
	                                     <!-- 오른쪽테이블내용시작-->
	                                     <table width="400" border="0" cellspacing="0" cellpadding="0" id="ripping01_table">
																					<tr><td><? include "./mobilew_01_usb_table.php"; ?></td></tr>
	                                     </table>
	                                     <!-- 오른쪽테이블내용 끝-->
	                     </td>
	                </tr>
	                <tr><td><img src="../images/wizard//img_Nas_txt.gif"></td></tr>
	                                    
	            </table>
           </div>                    
       					    
					<!--Step 2 - Detail Setting : Start-->
					<? include "./mobilew_02.php"; ?>
          <!-- Step 2 - Detail Setting : End-->
          
					<!--Step 3 - Confirm Setting : Start-->
					<? include "./mobilew_03.php"; ?>
          <!-- Step 3 - Confirm Setting : End-->
 				 			
 			
 				 </td>
 				 </tr>
					</table>
					
					</td>

				<!-- left Navigation 영역 끝-->
   		      </tr>
        		  </table></td>
      <!-- 전체center 영역 끝-->
          
        		  </tr>
        			  <!-- bottom 자르는 영역 -->
         			 <?	 include "../inc/bottom.php";  ?>
 <!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language="javascript1.2" src="../js/jquery-1.2.6.pack.js" charset="utf-8"></script>
<script language="javascript1.2" src="../js/mobilew.js.php" charset="utf-8"></script>
<script language='javascript' charset='utf-8'>
init();
function init()
{
	//gPage='usb';
	//debug('init : '+gPage+' menu');		// initialize
	//document.getElementById('idPathMode').value = "usb";
	// to do 
	//var res = "<?php echo lang_get('common_loading'); ?>";
	//document.getElementById('idListBox').innerHTML=res;
	//document.getElementById('idListBoxUsb').innerHTML=res;
	get_dev_info("usb");
	read_xml();
}
</script>