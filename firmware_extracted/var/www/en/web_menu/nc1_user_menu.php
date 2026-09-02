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
				
		<!-- ODD (DVD / BD) -->
		<div id="menu_odd" class="main_menu"><span class="main_menu_class icon_odd"><?php echo $odd_type ?></span></div>
				<div id="menu_odd_sub">
				<div><a href="../blu_ray/burning.php"><span class="sub_menu icon_burning"><?php echo lang_get('menu_burning')?></span></a></div>
			</div>
		
	
</div>	
	