<?php

$odd_type = $_SESSION['odd_type']; 
if($odd_type == 'BD')
	$odd_string ="status_menu_3";
else
	$odd_string ="status_menu_3_1";
?>


<div id="idTableBluray" style='display:none;' >
	
	<!-- 1. Table Header -->
	<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'>
		<tr>
				<td class="header_center" style="width:250px;"><?php echo lang_get('status_bluray_1'); ?></td>
				<td class="header_center" style="width:420px;"><?php echo lang_get('status_bluray_3'); ?></td>
		</tr>
	</table>

	<!-- 2. Get Infomation -->
   <div id="idTableBlurayBox"></div>
      
  <!-- 3. Menu -->
			<table width="670px" cellpadding="0px" cellspacing="0px"  style="margin-top:20px;">
				<tr>
					<td width="30px"><img src="../web_menu/images/icon_burning.gif"></td>
					<td width="640px" style="font-weight:bold;">
					<a href="../blu_ray/burning.php" ><?php echo lang_get($odd_string); ?></a></td>

				</tr>
			</table>	
</div>

