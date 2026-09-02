<?
	// Get ODD Type
	if($_SESSION['odd_type'] == "BD")
	  		$odd_type = "Blu-ray";
		else{
				$odd_type = "DVD";  	
	}
?>
	<div id="web_menu_control">
	<table width="240px" border="0"><tr>
		<td align="center"><span id="full_menu"><?php echo lang_get('full_menu')?></span><span class="arrow_right">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
		<td align="center"><span id="short_menu"><?php echo lang_get('short_menu')?></span><span class="arrow_down">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
	</tr></table>
		
	</div>
	
	<div id="web_menu" class="filetree">
				<div id="menu_system" class="main_menu"><span class="main_menu_class icon_system"><?php echo lang_get('menu_system')?></span></div>
				<div id="menu_system_sub">
						<div><a href="../system/network.php"><span class="sub_menu icon_network"><?php echo lang_get('menu_network')?></span></a></div>
						<div><a href="../service/ddns.php"><span class="sub_menu icon_remote_access"><?php echo lang_get('menu_remote_access')?></span></a></div>
						<div><a href="../system/time.php"><span class="sub_menu icon_time"><?php echo lang_get('menu_time')?></span></a></div>
						<div><a href="../system/email.php"><span class="sub_menu icon_mail_notification"><?php echo lang_get('menu_mail_notification')?></span></a></div>
						<div><a href="../system/volume.php"><span class="sub_menu icon_volume"><?php echo lang_get('menu_volume')?></span></a></div>
						<div><a href="../system/sm.php"><span class="sub_menu icon_sm"><?php echo lang_get('menu_sm')?></span></a></div>
						<div><a href="../system/power.php"><span class="sub_menu icon_power"><?php echo lang_get('menu_power')?></span></a></div>
						<div><a href="../system/language.php"><span class="sub_menu icon_language"><?php echo lang_get('menu_language')?></span></a></div>
						<div><a href="../system/firmware.php"><span class="sub_menu icon_firmware"><?php echo lang_get('menu_firmware')?></span></a></div>
			  </div>
		<!-- SHARE -->
		<div id="menu_share" class="main_menu"><span class="main_menu_class icon_share"><?php echo lang_get('menu_share')?></span></div>
				<div id="menu_share_sub">
				<div><a href="../share/user.php"><span class="sub_menu icon_user"><?php echo lang_get('menu_user')?></span></a></div>
				<div><a href="../share/group.php"><span class="sub_menu icon_group"><?php echo lang_get('menu_group')?></span></a></div>
				<div><a href="../share/folder.php"><span class="sub_menu icon_folder"><?php echo lang_get('menu_folder')?></span></a></div>
			</div>
		<!-- ODD (DVD / BD) -->
		<div id="menu_odd" class="main_menu"><span class="main_menu_class icon_odd"><?php echo $odd_type ?></span></div>
				<div id="menu_odd_sub">
				<div><a href="../blu_ray/burning.php"><span class="sub_menu icon_burning"><?php echo lang_get('menu_burning')?></span></a></div>
			</div>
		
		
		<!-- SERVICE -->
		<div id="menu_service" class="main_menu"><span class="main_menu_class icon_service"><?php echo lang_get('menu_service')?></span></div>
				<div id="menu_service_sub">
				<div><a href="../service/dlna.php"><span class="sub_menu icon_dlna"><?php echo lang_get('menu_dlna')?></span></a></div>
				<div><a href="../service/servers.php"><span class="sub_menu icon_network_server"><?php echo lang_get('menu_network_server')?></span></a></div>
                                <div><a href="../service/userweb.php"><span class="sub_menu icon_network_server"><?php echo lang_get('menu_userweb')?></span></a></div>
				<div><a href="../service/printer.php"><span class="sub_menu icon_printer"><?php echo lang_get('menu_printer')?></span></a></div>
				<div><a href="../service/itunes.php"><span class="sub_menu icon_itunes"><?php echo lang_get('menu_itunes')?></span></a></div>
				<div><a href="../service/time_machine.php"><span class="sub_menu icon_time_machine"><?php echo lang_get('menu_time_machine')?></span></a></div>
				<div><a href="../service/iscsi.php"><span class="sub_menu icon_iscsi"><?php echo lang_get('menu_iscsi')?></span></a></div>
				<div><a href="../service/torrent.php"><span class="sub_menu icon_torrent"><?php echo lang_get('menu_torrent')?></span></a></div>
				</div>
		<!-- MOBILE DEVICE -->
		<div id="menu_mobile_device" class="main_menu"><span class="main_menu_class icon_mobile_device"><?php echo lang_get('menu_mobile_device')?></span></div>
				<div id="menu_mobile_device_sub">
				<div><a href="../mobile/usb.php"><span class="sub_menu icon_usb_backup"><?php echo lang_get('menu_usb_backup')?></span></a></div>
				</div>
		<!-- INFORMATION -->
		<div id="menu_information" class="main_menu"><span class="main_menu_class icon_information"><?php echo lang_get('menu_information')?></span></div>
				<div id="menu_information_sub">
				<div><a href="../information/status.php"><span class="sub_menu icon_status"><?php echo lang_get('menu_status')?></span></a></div>
				<div><a href="../information/log.php"><span class="sub_menu icon_log"><?php echo lang_get('menu_log')?></span></a></div>
			</div>
	
</div>	
	
