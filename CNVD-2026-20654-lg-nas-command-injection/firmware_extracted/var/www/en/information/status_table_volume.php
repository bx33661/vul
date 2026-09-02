<?php
//Check : HDD1 or HDD2 FORCE OUT
$stat_file = '/var/run/hdd_event.log';
if(!file_exists($stat_file))
	exec("sudo touch '$stat_file' ; sudo chmod 777 '$stat_file' ; sudo echo no event > '$stat_file'");
else
	$hdd_force_out = trim(exec("sudo cat $stat_file | grep Removed"));

?>



<div id="idTableVolume" style='display:none;' >
		<!-- 1. Table Header -->
		<table width="670" border="0" cellspacing="0" cellpadding="0" >
		<tr>
				<td class="header_center" style="width:60px;">No</td>
				<td class="header_center" style="width:220px;"><?php echo lang_get('common_name'); ?></td>
				<td class="header_center" style="width:60px;">RAID</td>
				<td class="header_center" style="width:100px;"><?php echo lang_get('common_status'); ?></td>
				<td class="header_center" style="width:230px;"><?php echo lang_get('volume_3'); ?> </td>
		</tr>
		</table>

	
	<!-- 2. Get Infomation -->
	<div id="idTableVolumeBox" style="width:670;display:block;">&nbsp;</div>
	 
	<!-- 3. Menu -->
	
			<table width="670px" cellpadding="0px" cellspacing="0px"  style="margin-top:20px;">
				<tr>
					<td width="30px"><img src="../web_menu/images/icon_volume.gif"></td>
					<td width="640px" style="font-weight:bold;"><a href="../system/volume.php" ><?php echo lang_get('status_menu_2'); ?></a></td>
				</tr>
			</table>	

	<!-- 4. Warning Msg -->
			<?php if( $hdd_force_out != '')  { ?>
			<!--<div id="hard_removed_abnormally" style='display:none'>-->
			<table width="670" border="0" cellspacing="0" cellpadding="0" >
				<tr>
					<td style="vertical-align:middle;" width="80px" height="40px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
					<!--<td style="vertical-align:middle;" width="80px" height="40px" align="center"><img src="../images/comnso/cms_icon_error.gif" border="0"/></td>-->					
					<td height="30" align="left" class="red_s1">
						<?php echo lang_get('volume_msg_force_out'); ?>
					</td>
				<tr>
			</table>
			<!--</div>-->
			<? } ?>

</div>
