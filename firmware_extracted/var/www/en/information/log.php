<?	 include "../inc/top.php";  ?> <!-- top 자르는 영역 -->
<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' ></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' ></script>	<!--ajax lib-->
<script language='javascript' charset='utf-8'>
	gPage='log';		// set page name for language setting
</script>
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

<script language="javascript1.2" src="../js/log.js.php?lang=<?=$t_lang_from_url[1]?>"></script>



<tr>
<!-- 전체center 영역 시작--> 
	<td valign="top">
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
							<td height="50" valign="top"><img src="../images/headtitle/htit_log.gif" /></td>
						</tr>
						<tr>
							<td height="30" align="center" valign="top">
							<!-- 중앙 테이블 영역 시작-->
							<table width="670" border="0" cellspacing="0" cellpadding="0" id="network_table">
								<tr>
									<td valign="top"><!-- 전체 탭 테이블 시작 -->
									<table width="670" border="0" cellspacing="0" cellpadding="0" id="all_network_tab_table">
										<tr>
											<td height="28">
											<table width="429" border="0" cellspacing="0" cellpadding="0" id="network_tab_table">
												<tr>
													<td width="50px">
													<div id="idTabSysLogOn" style='display:block;'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_01','','../images/tab/tab_system_log_on.gif',1)"><img src="../images/tab/tab_system_log_on.gif" name="log_01" border="0"></a></div>
													<div id="idTabSysLogOff" style='display:none;' onclick='open_tab_sys();'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_11','','../images/tab/tab_system_log_over.gif',1)"><img src="../images/tab/tab_system_log.gif" name="log_11" border="0"></a></div>
													</td>
													<td width="2"></td>
													<td width="50px">
													<div id="idTabSmbLogOn" style='display:none;'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_02','','../images/tab/tab_samba_log_on.gif',1)"><img src="../images/tab/tab_samba_log_on.gif" name="log_02"  border="0"></a></div>
													<div id="idTabSmbLogOff" style='display:block;' onclick='open_tab_smb();'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_12','','../images/tab/tab_samba_log_over.gif',1)"><img src="../images/tab/tab_samba_log.gif" name="log_12" " border="0"></a></div>
													</td>
													<td width="2"></td>
													<td width="50px">
													<div id="idTabFtpLogOn" style='display:none;'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_03','','../images/tab/tab_ftp_log_on.gif',1)"><img src="../images/tab/tab_ftp_log_on.gif" name="log_03"  border="0"></a></div>
													<div id="idTabFtpLogOff" style='display:block;' onclick='open_tab_ftp();'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_13','','../images/tab/tab_ftp_log_over.gif',1)"><img src="../images/tab/tab_ftp_log.gif" name="log_13"  border="0"></a></div>
													</td>
													<td width="2"></td>
													<td>
													<div id="idTabDiagOn" style='display:none;'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_04','','../images/tab/tab_diag_log_on.gif',1)"><img src="../images/tab/tab_diag_log_on.gif" name="log_04"  border="0"></a></div>
													<div id="idTabDiagOff" style='display:block;' onclick='open_tab_diag();'><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('log_14','','../images/tab/tab_diag_log_over.gif',1)"><img src="../images/tab/tab_diag_log.gif" name="log_14"  border="0"></a></div>
													</td>
												</tr>
											</table>
											</td>
										</tr>
										<tr>
											<td height="2" bgcolor="#853e3c"></td>
										</tr>
									</table><!-- 전체 탭 테이블 끝 --></td>
								</tr>
								<tr>
									<td height="20"></td>
								</tr>
								<tr>
									<td valign="top"><!-- 테이블 영역 시작-->
									<table width="670" border="0" cellspacing="0" cellpadding="0" id="network_table2">
										<tr>
											<td valign="top">
											<!-- 전체 탭 테이블 시작 -->
											<!-- 전체 탭 테이블 끝 -->
											</td>
										</tr>
										<tr>
											<td>
												<!-- Log list box -->
												<div id="idTableBox" style="width:670px;height:247px;overflow-y:scroll;display:none;"></div>
												<!-- Log list box : end -->
											</td>
										</tr>
										<tr>
											<td height="20"></td>
										</tr>
										<tr><!--Save info button-->
											<td align="right" style="padding:20 0 0 0px">
											<form id="idForm" name="frmTS" method="post" action="../php/log_get_log_file.php" >
											<img src="../images/btn/btn_save.gif" border="0" onclick='save_log("system_log");' class="buttons"/>
											<input type="hidden" id="idInputLogMode" name="log_mode" value="system_log" />
											</form>
											</td>
										</tr>
									</table>
									<!-- 테이블 영역 끝--></td>
								</tr>
							</table>
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
<script language='javascript' >
function init()
{
	gPage='log';
	debug('init : '+gPage+' menu');		// initialize
	// to do 
	open_tab_sys();
}

init();
</script>