<?	 include "../inc/top.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language="javascript1.2" src="../js/common.js.php" charset="utf-8"></script>
<script language="javascript" src="../js/bd_common.js" charset="utf-8"></script>
<script language="javascript1.2" src="../js/extraction.js" charset="utf-8"></script>
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


<!--Input field ID for selected folder path in the child window-->
<input type="hidden" id="idInputFieldId" value="" />
<input type="hidden" id="idPathMode" value="" /><!--For folder browser : rip/store/burn/schedule-->
<!--For folder browser : end : rip/store/burn/schedule-->


<tr>
          <!-- 전체center 영역 시작--> <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          			          <tr>
			
			            <!-- left 자르는 영역 -->
			
             

			            <!-- left Navigation 영역 시작-->
					         <td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
				       <!-- left 끝-->
				       
					        <td width="100%" valign="top">
					        <!-- 사이즈 수정 -->
					        
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" height="7" background="../images/Top/utility_shadow.gif">
	</td>
	<!-- 사이즈 수정 -->
</tr>
<tr>
<!-- 중앙내용 시작 -->
	<td valign="top" style="padding:0 0 0 50px">
		<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_table">
		<tr>
			<td height="40">
			</td>
		</tr>
		<tr>
			<td height="50" valign="top"><img src="../images/headtitle/htit_extraction.gif" width="121" height="30" /></td>
		</tr>
		<tr>
		<td height="30" align="center" valign="top">

<!-- 중앙 테이블 영역 시작-->
<table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_table">
<tr>
	<td valign="top">

<!-- (1)전체 탭 테이블 시작 -->
<table width="670" border="0" cellspacing="0" cellpadding="0" id="idTabAudio" style='display:block;'>
<tr>
<td height="28">
	<table width="211" border="0" cellspacing="0" cellpadding="0" id="ripping_tab_table">
	<tr>
	<td width="101"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ripping_01','','../images/tab/tab_audio_on.gif',1)"><img src="../images/tab/tab_audio_on.gif" name="ripping_01" width="101" height="28" border="0" id='ripping_01'></a></td>
	<td width="2"></td>
	<td width="108"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ripping_02','','../images/tab/tab_dvd_title_over.gif',1)"><img onclick='open_dvd_ready();' src="../images/tab/tab_dvd_title.gif" name="ripping_02" width="108" height="28" border="0" id='ripping_02'></a></td>
	</tr>
	</table>
</td>
</tr>
</table>
<!-- (1)전체 탭 테이블 끝 -->

	
	<!-- (2)전체 탭 테이블 시작 -->
	<table width="670" border="0" cellspacing="0" cellpadding="0" id="idTabDvd" style='display:none;'>
	<tr>
	<td height="28">
		<table width="211" border="0" cellspacing="0" cellpadding="0" id="ripping_tab_table">
		<tr>
		<td width="101"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ripping_03','','../images/tab/tab_audio_over.gif',1)"><img onclick='open_audio_ready();' src="../images/tab/tab_audio.gif" name="ripping_03" width="101" height="28" border="0" id='ripping_03'></a></td>
		<td width="2"></td>
		<td width="108"><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ripping_04','','../images/tab/tab_dvd_title_on.gif',1)"><img src="../images/tab/tab_dvd_title_on.gif" name="ripping_04" width="108" height="28" border="0" id='ripping_04'></a></td>
		</tr>
		</table>
	</td>
	</tr>
	
	</table>
	<!-- (2)전체 탭 테이블 끝 -->
	
	</td>
</tr>
<tr>
	<td height="2" bgcolor="#853e3c">
	</td>
</tr>
<tr><td>

<!--(1)basic table starts-->
<table style='display:block' id='idTableBasic'>
<tr>
	<td height="30"></td>
</tr>
<tr>
	<td></td>
</tr>
<tr>
	<td></td>
</tr>
			<tr>
				<td><!-- 전체내용 시작 -->
					<table width="670" border="0" cellspacing="0" cellpadding="0" id="ripping_tablu_01" style='display:block;'>
					<tr>
						<td width="91">
						<img src="../images/icon/img_one_01.gif" width="91" height="83">
						</td>
						<td valign="top" class="red_s2">
							<div id='idTxtDiscReady'>Please insert your audio CD to the Blu-ray Drive
							</div>
						</td>
					</tr>
					</table>
					<!-- 전체내용 끝 -->
				</td>
			</tr>
<!-- 버튼 내용 시작 -->
			<tr>
				<td align="right" style="padding:20 0 0 0px">
				<div id='idButtonNext' style='visibility:visible;'><a href="javascript:void(0)" onclick='load_disc();'><img  src="../images/btn/btn_next.gif" width="61" height="22" border="0" /></a></div>
				</td>
			</tr>
			</table>
<!--(1)basic table ends-->
			
			</td></tr>
			
			<!-- (2)중앙 테이블 영역 시작-->
			<? include 'extraction_table_audio.php' ?>
			<!-- (2)중앙 테이블 영역 끝-->
			
			<!-- (3)중앙 테이블 영역 시작-->
			<? include 'extraction_table_dvd.php' ?>
			<!-- (3)중앙 테이블 영역 끝-->
			
			<!-- 버튼 내용 끝 -->
			</table>
<!-- 중앙 테이블 영역 끝-->





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

<script language='javascript' charset='utf-8'>
init();
function init()
{
	gPage='ripping';
	debug('init : '+gPage+' menu');		// initialize
	document.getElementById('idPathMode').value = "rip";
}

</script>		