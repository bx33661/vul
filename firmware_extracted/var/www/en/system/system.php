<? include "../inc/top.php";  ?>

<script language="javascript1.2" src="../js/system_info.js.php" charset="utf-8"></script>

<!----------------------------------->

<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>

	<td width="245" valign="top"><?php include "../inc/left.php";  ?></td>
	<td width="100%" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>

		</tr>
		<tr>


		<td style="padding:40 0 0 50px">
			<table width="670" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="50" valign="top"><img src="../images/headtitle/htit_system.gif"/></td>
			</tr>
			<tr>

			
			<td width="646" height="386px" valign="top" style="background: url('../images/system_main.gif');background-repeat:no-repeat;"}>			
			
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
				
				<table width="300" border="0" cellspacing="0" cellpadding="0" style="margin-left:220px" >
				<tr>
				<td height="24">
				</td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01" ><div id="txtHOSTNAME"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01"><div id="txtHOSTDESC"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01"><div id="id_FirmUpVer"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01"><div id="txtCurrentTime"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01"><div id="txtTimeZone"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<!--<tr>
				<td height="25" class="m_gray_01"><div id="FTPstatus"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="26" class="m_gray_01"><div id="NTPstatus"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>-->
				<tr>
				<td height="26" class="m_gray_01"><div id="VOLfstab"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>				
				<tr>
				<td height="25" class="m_gray_01"><div id="DDNShostname"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01"><div id="iSCSIstatus"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="26" class="m_gray_01"><div id="DLNAstatus"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>				
				<tr>
				<td height="26" class="m_gray_01"><div id="EMAILstatus"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				<tr>
				<td height="25" class="m_gray_01"><div id="FANstatus"><?php echo lang_get('common_loading'); ?></div></td>
				</tr>
				</table>
								
			</td>
			</tr>
			</table>
		</td>
		</tr>		
	</tr>
	</table>

</td>
</tr>
</table>




</td>
</tr>
 					
<!-- bottom -->
<?php include "../inc/bottom.php";  ?>

<script language='javascript' charset='utf-8'>
init();
function init()
{
	Get_System_Info();	
}
</script>

