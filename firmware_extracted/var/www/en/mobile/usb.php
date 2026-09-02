<?	 include "../inc/top.php";  ?> <!-- top 자르는 영역 -->
 
 <!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>
<!--<script language="javascript1.2" src="../js/jquery-1.2.6.pack.js" charset="utf-8"></script>-->
<script language="javascript1.2" src="../js/usb.js.php" charset="utf-8"></script>
<!----------------------------------->

<!--Input field ID for selected folder path in the child window-->
<input type="hidden" id="idInputFieldId" value="" />
<input type="hidden" id="idPathMode" value="usb" /><!--For folder browser : rip/store/burn/schedule-->
<!--For folder browser : end : rip/store/burn/schedule-->


<!-- Style sheets -->
<style type="text/css">
<!--
/* table styles */
.tb {
	width:100%;
	display:block;
	table-layout:fixed;
	border : 0;
}
/* td styles */
.first {
	width:130px;
	height:25px;
	background-color:#f5f5f7;
	padding-left:20px;
	border-left:1px solid #e3e3e3;
	border-right:1px solid #e3e3e3;
	border-bottom:1px solid #e3e3e3;
	
	font-family: "Verdana";font-size: 8pt; line-height: 12pt; color: #6e6f71;
	
}
.second {
	width:170px;
	height:25px;
	background-color:white;
	padding-left:20px;
	border-right:1px solid #e3e3e3;
	border-bottom:1px solid #e3e3e3;
	
	font-family: "Verdana";font-size: 8pt; line-height: 12pt; color: #6e6f71;
}
.third {
	width:140px;
	height:25px;
	background-color:#f5f5f7;
	padding:0 20px 0 20px ;
	border-right:1px solid #e3e3e3;
	border-bottom:1px solid #e3e3e3;
	font-family: "Verdana";font-size: 8pt; line-height: 12pt; color: #6e6f71;
	line-height : 100%;
}
.forth {
	width:120px;
	height:25px;
	background-color:white;
	padding-left:20px;
	border-right:1px solid #e3e3e3;
	border-bottom:1px solid #e3e3e3;
	font-family: "Verdana";font-size: 8pt; line-height: 12pt; color: #6e6f71;
}
-->
</style>


<script type="text/javascript">
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





          <tr>
          <!-- 전체center 영역 시작--> <td valign="top">
          	<!-- Task number value for progress popup window -->
						<input type='hidden' id='id_task_number' value="" />
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
 	 	<table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px">
  							  
					 <tr>
						   <td height="50" valign="top"><img src="../images/headtitle/htit_mobile.gif"/></td>
					 </tr>
			 </table>
			                             
                                     
<!-- Tab Start -->                                     
<table width="670" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		
			<!--(1)USB list-->
			<table id="idTab1" style='display:block;' width="670" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="50px" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_usb_01','','../images/tab/tab_usb_list_on.gif',1)"><img src="../images/tab/tab_usb_list_on.gif" name="tab_usb_01" border="0" id="tab_usb_01" /></a></td>
					<td width="2px" class="tab">&nbsp;</td>
					<td width="648px" class="tab"><a href="javascript:void(0)" onclick="open_task_list();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_sync_01','','../images/tab/tab_usb_setting_over.gif',1)"><img src="../images/tab/tab_usb_setting.gif" name="tab_sync_01" border="0" id="tab_sync_01" /></a></td>
				</tr>
			</table>
			<!--(2)USB Sync setting list-->
			<table id="idTab2" style='display:none;' width="670" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="50px" class="tab"><a href="javascript:void(0)" onclick="usbList.open();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_usb_02','','../images/tab/tab_usb_list_over.gif',1)"><img src="../images/tab/tab_usb_list.gif" name="tab_usb_02" border="0" id="tab_usb_02" /></a></td>
					<td width="2px" class="tab">&nbsp;</td>
					<td width="648px" class="tab"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_sync_02','','../images/tab/tab_usb_setting_on.gif',1)"><img src="../images/tab/tab_usb_setting_on.gif" name="tab_sync_02" border="0" id="tab_sync_02" /></a></td>
				</tr>
			</table>
			<!--Tab : end-->
			
		</td>
	</tr>

</table>
                                     
 

<!--Table : USB list-->
<? include "./usb_table_usb.php"; ?>
<!--Table : USB list : end-->

<!--Table : sync setting list-->
<div id="idTableSync" style='display:none;'>
	<!-- 테이블 영역 시작-->
			<table width="670px" border="0" cellspacing="0" cellpadding="0" id="title_table_usb" style="margin-top:30px">
					<tr>
						<td width="170px" class="header"><?php echo lang_get('common_name'); ?></td>
						<td width="230px" class="header"><?php echo lang_get('user_list_3'); ?></td>
						<td width="120px" class="header"><?php echo lang_get('usb_backup_msg_2'); ?></td>
						<td width="100px" class="header"><?php echo lang_get('usb_sync_msg_26'); ?></td>
					</tr>
			</table>
			
			<!-- Setting list-->
			<div id="idListBox" style="width:670;display:block;">&nbsp;</div>


			<!-- Setting list : end-->
			
			<!-- Buttons -->
			<table width="670px" border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="display:block;margin-top:20px">
							<tr>
								<td width="300"><!--<input id="id_btn_sync" type="image" onclick='sync_task();' src="../images/btn/btn_sync.gif" border="0">--></td>
								<td width="370px" align="right">
										<!--<img src="../images/btn/btn_create.gif" border="0" onclick='open_create_table();' class="buttons">-->
								    <img src="../images/btn/btn_edit.gif" border="0" onclick='open_edit_table();' class="buttons">
										<img src="../images/btn/btn_delete.gif" border="0" onclick='delete_task();' class="buttons">
								
							</tr>
			</table>
			
</div>
<!--Table : sync setting list : end-->	
	
<!--Table : create/save setting-->
<? include "./usb_table_setting.php"; ?>
<!--Table : create/save setting : end-->

	
	
</td><!--중앙끝 -->
                  </tr>
			                 
</table>
  
 	 	
 	 		
  
 	 <!-- 중앙내용 끝-->
 </td>
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

<script language='javascript' charset='utf-8'>
page.init();
</script>