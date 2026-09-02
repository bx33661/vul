<?php

$odd_type = $_SESSION['odd_type']; 
if($odd_type == 'BD')
{
	$odd_on ="'../images/tab/tab_bluray_on.gif'";
	$odd_over = "'../images/tab/tab_bluray_over.gif'";
	$odd = "\"../images/tab/tab_bluray.gif\"";
}
else
{
	$odd_on ="'../images/tab/tab_dvd_on.gif'";
	$odd_over = "'../images/tab/tab_dvd_over.gif'";
	$odd = "\"../images/tab/tab_dvd.gif\"";
}
?>


<table id="idTab" style='display:block;' width="670" border="0" cellspacing="0" cellpadding="0" >
	<tr>
		<td width="50px">
			<div id="idTabNetworkOn" style='display:block;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_01','','../images/tab/tab_network_on.gif',1)"><img src="../images/tab/tab_network_on.gif" name="status_01" border="0"></a></div>
			<div id="idTabNetworkOff" style='display:none;'><a href="javascript:void(0)" onclick='open_tab_network();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_11','','../images/tab/tab_network_over.gif',1)"><img src="../images/tab/tab_network.gif" name="status_11" border="0"></a></div>
		</td>
		<td width="2"></td>
		<td width="50px">
			<div id="idTabVolumeOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_02','','../images/tab/tab_volume_on.gif',1)"><img src="../images/tab/tab_volume_on.gif" name="status_tab_02" border="0"></a></div>
			<div id="idTabVolumeOff" style='display:block;'><a href="javascript:void(0)" onclick='open_tab_volume();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_12','','../images/tab/tab_volume_over.gif',1)"><img src="../images/tab/tab_volume.gif" name="status_tab_12" border="0"></a></div>
		</td>
		<td width="2"></td>
		<td width="50px">
			<div id="idTabHardOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_03','','../images/tab/tab_harddisk_on.gif',1)"><img src="../images/tab/tab_harddisk_on.gif" name="status_tab_03" border="0"></a></div>
			<div id="idTabHardOff" style='display:block;'><a href="javascript:void(0)" onclick='open_tab_hard();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_13','','../images/tab/tab_harddisk_over.gif',1)"><img src="../images/tab/tab_harddisk.gif" name="status_tab_13" border="0"></a></div>
		</td>
		<td width="2"></td>
		<td width="50px">
			<div id="idTabBlurayOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_04','',<?php echo $odd_on ?>,1)"><img src=<?php echo $odd_on ?> name="status_tab_04" border="0"></a></div>
			<div id="idTabBlurayOff" style='display:block;'><a href="javascript:void(0)" onclick='open_tab_bluray();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_14','',<?php echo $odd_over ?>,1)"><img src=<?php echo $odd ?> name="status_tab_14" border="0"></a></div>
		</td>
		<td width="2"></td>
		<td width="50px">
			<div id="idTabUsbOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_05','','../images/tab/tab_usb_on.gif',1)"><img src="../images/tab/tab_usb_on.gif" name="status_tab_05" border="0"></a></div>
			<div id="idTabUsbOff" style='display:block;'><a href="javascript:void(0)" onclick='open_tab_usb();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_15','','../images/tab/tab_usb_over.gif',1)"><img src="../images/tab/tab_usb.gif" name="status_tab_15" border="0"></a></div>
		</td>
		<td width="2"></td>
		<td width="50px">
			<div id="idTabEsataOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_06','','../images/tab/tab_esata_on.gif',1)"><img src="../images/tab/tab_esata_on.gif" name="status_tab_06" border="0"></a></div>
			<div id="idTabEsataOff" style='display:block;'><a href="javascript:void(0)" onclick='open_tab_esata();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_16','','../images/tab/tab_esata_over.gif',1)"><img src="../images/tab/tab_esata.gif" name="status_tab_16" border="0"></a></div>
		</td>
		
		<!-- User Access Info. Tab -->
		<!-- Change Image File Links!! -->
		<td width="2"></td>
		<td>
			<div id="idTabUserOn" style='display:none;'><a href="javascript:void(0)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_07','','../images/tab/tab_access_on.gif',1)"><img src="../images/tab/tab_access_on.gif" name="status_tab_07" border="0"></a></div>
			<div id="idTabUserOff" style='display:block;'><a href="javascript:void(0)" onclick='user_access.tab_open();' onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('status_tab_17','','../images/tab/tab_access_over.gif',1)"><img src="../images/tab/tab_access.gif" name="status_tab_17" border="0"></a></div>
		</td>
	</tr>
</table>
 