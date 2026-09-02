<?	 include "../inc/top.php";  ?> <!-- top 자르는 영역 -->
<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<!--Browser lib : end-->
<script type='text/javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script type='text/javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script type='text/javascript' src='../js/common.js.php' charset='utf-8'></script>
<script type="text/javascript" src="../js/status.js.php?lang=<?=$t_lang_from_url[1]?>" ></script>
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
		  <!-- 중앙내용 시작 -->  <td valign="top" style="padding:0 0 0 50px"><table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table">
   								 <tr>
                                  <td height="40"></td>
				  </tr>
   								 <tr>
    							  <td height="50" valign="top"><img src="../images/headtitle/htit_status.gif" /></td>
				  </tr>
 							   <tr>
   							   <td height="30" align="center" valign="top">
                               <!-- 중앙 테이블 영역 시작-->
        	 	 <table width="670" border="0" cellspacing="0" cellpadding="0" id="network_table">
          					  <tr>
          					    <td valign="top">
            					    <table width="670" border="0" cellspacing="0" cellpadding="0" id="all_network_tab_table">
                                  <tr>
                                    <td height="28">
                                    
                                    <!-- Tab buttons : start -->
                                    	<? include "status_tab_all.php" ?>
                                    <!-- Tab buttons : end -->
                                    
                                    </td>
                                  </tr>
                                  <tr>
                                    <td height="2" bgcolor="#853e3c"></td>
                                  </tr>
                                </table>
                                <!-- Tab : end -->
                                
                                </td>
   					    </tr>
          			<tr><td height="20px"></td></tr>		 
            					  
   					  
          					  

                
                
 
           
          
           
           		 
           		
          		</table>
          		       					   
			<!-- Table : (1) Network -->
				<? include "status_table_network.php" ?>
			<!-- Table : (2) Volume -->
				<? include "status_table_volume.php" ?>
			<!-- Table : (3) Hard Disk -->
				<? include "status_table_hard.php" ?>
			<!-- Table : (4) Blu-ray -->
				<? include "status_table_bluray.php" ?>
			<!-- Table : (5) USB -->
				<? include "status_table_usb.php" ?>
			<!-- Table : (6) e-SATA -->
				<? include "status_table_esata.php" ?>
			<!-- Table : (7) User Access Info. -->
				<? include "status_table_user.php" ?>
			<!-- Table : end -->
			
			<div id="page_loading" align="center" style="position:absolute;left:450px;top:330px;width:300px;height:100px;display:none;background-color:#fff;">
			                                
			                            <table border="0" cellspacing="0" cellpadding="0" width="300px">	
																			<tr>
																				<td colspan="2" style="backgRound-color:#742625;color:#fff;height:25px;font-size:15px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_loading')?></td>
																			</tr>
																			<tr>
																			  <td style="border:1px solid #5d5d5d;border-right:none;height:75px;width:100px;" align="center">
																			  	<img Id="img_page_loading" src="../images/Burn/file_box_loading.gif"/>
																			  </td>
																			  <td style="border:1px solid #5d5d5d;border-left:none;height:75px;width:200px;"><?php echo lang_get('common_wait')?></td>
																			  
																			</tr>
																	</table>
			         </div>    
       			 <!-- 중앙 테이블 영역 끝--></td>
   				 </tr>
 				 </table></td>
		  <!-- 중앙내용 끝-->
 				 </tr>
					</table></td>
				
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
<script type='text/javascript' >

page.init();
</script> 