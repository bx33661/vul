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
          <!-- 전체center 영역 시작--> <td valign="top">
          	<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
		  	<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table">
   								 <tr>
                                  <td height="40"></td>
								  </tr>
   								 <tr>
    			     				  <td height="50" valign="top"><img src="../images/headtitle/htit_scheduling.gif" /></td>
				           </tr>
 							      <tr>
   							   <td height="30" align="center" valign="top">
                               <!-- 중앙 테이블 영역 시작-->
        	                     
          					          	 	 <table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
        	 	                       	         <tr>
                                                  <td width="80" background="../images/wizard/tab_line.gif"><img src="../images/wizard/tab_step_01_r.gif" name="step_01" border="0"></td>
						                                      <td width="590" background="../images/wizard/tab_line.gif">&nbsp;</td>
						                                      
                                             </tr>
                                    </table>
                                    
                         
                                    
                     </td>
                    </tr>
          					  <tr>
          					    <td height="20"></td>
   				       	    </tr>
          					  <tr>
            					  <td valign="top" style="padding:0 0 0 0px">
                                  <!-- 중앙내용 시작 -->
                                 <table width="670" border="0" cellspacing="0" cellpadding="0" id="status_table">
                                    <tr>
                                      <td width="91"><img src="../images/icon/img_one_01.gif" width="91" height="83"></td>
                                      <td valign="top"  style="padding:5 0 0 0px">
                                      	<span class="red_text_9"><?php echo lang_get('wizard_msg_11')?></span>
                                    </tr>
                                  </table><!-- 중앙내용 끝 -->
                                  
                                   <? include "../blu_ray/schedule_table3.php" ?>
                                
                                  
                                  
                                  </td>
   					    </tr>
                       
          					  
 				              </tr>
				</table></td>
				<!-- left Navigation 영역 끝-->
   		      </tr>
        		  </table></td>
<!-- 전체center 영역 끝-->
          
        		  </tr>
        		</table>
        	</td>
        </tr>
        			  <!-- bottom 자르는 영역 -->
         			 <?	 include "../inc/bottom.php";  ?>
 
 <!---------------------------------
// LGE NAS-SSS 
// By park94
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

<script language='javascript' charset='utf-8'>
init();
function init()
{
	document.getElementById('idTable3').style.display="block";
	browse('open', 'root');

}
</script>
