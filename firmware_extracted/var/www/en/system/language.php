<?	 include "../inc/top.php";  ?>

          <!-- top 자르는 영역 -->

<script language="javascript" type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
         			 
<script	language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>
<script language="javascript" charset="utf-8" src="../js/language.js.php?lang=<?=$t_lang_from_url[1]?>"></script>

          <body onLoad="MM_preloadImages('../images/tab_ntp_on.gif','../images/tab/tab_host.gif','../images/tab/tab_workgroup_on.gif')">

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
		  	
		  	<!-- 1. Head Title -->
		  	<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table" style="margin-top:40px;">
   				 <tr>
    							  <td height="50" valign="top"><img src="../images/headtitle/htit_language.gif" /></td>
				  </tr>
				</table>
 				
 				<!-- 2. Contents -->			  
        <table border="0" cellspacing="0" cellpadding="0" style="width:670px;">
          	<tr>
          		<td class="firstCol_250" style="border-top:1px solid #e3e3e5;width:200px;"><?php echo lang_get('lang_1'); ?></td>
          		<td class="otherCol_420" style="border-top:1px solid #e3e3e5;width:470px;"><div id='idTxt_lang'>&nbsp;</div></td>
          	</tr>
            <tr>
          		<td class="firstCol_250" style="width:200px;"><?php echo lang_get('lang_2'); ?></td>
          		<td class="otherCol_420" style="width:470px;"><div id='idTxt_client_lang'>&nbsp;</div></td>
          	</tr>
       
           
        </table>
        <!-- 3. Buttons : Edit-->       
        <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px" id="table_btn_edit">                
       		 <tr>
       		   <td width="670px" align="right">
       		     <a href="javascript:void(0)" onclick="edit.make_select();"><img src="../images/btn/btn_edit.gif" border="0" /></a></td>
       		 </tr>
       	
      		</table>
        <!-- 3. Buttons : Edit-->       
        <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px;display:none;" id="table_btn_apply">                
       		 <tr>
       		   <td width="670px" align="right">
       		     <a href="javascript:void(0)" onclick="edit.select_language();"><img src="../images/btn/btn_apply.gif" border="0" /></a></td>
       		 </tr>
       	
      		</table>        		
						<div id="page_loading" align="center" style="position:absolute;left:450px;top:300px;width:300px;height:100px;display:none;background-color:#ffffff;">
                          
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

<script language='javascript' charset='utf-8'>
init.get_lang();
</script>