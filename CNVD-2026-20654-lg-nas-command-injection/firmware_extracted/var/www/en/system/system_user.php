<? include "../inc/top.php";  ?>

<?php

//require_once("../multilang/multilang_api.php");

$host 		= trim(exec('sudo hostname'));

$date 		= exec("date -I");

$ftp		= trim(exec('sudo nas-service get enabled ftp'));
if ( $ftp == 'on' ) {
	$ftp_status = lang_get('common_enable');
}
else {
	$ftp_status = lang_get('common_disable');	
}
?>

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

			<td width="646" height="386px" valign="top">
				
	                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
	                     	<tr>
	                        <td class="header" style='height:25px;'><?php echo lang_get('common_name'); ?></td>
	                        <td class="header" style='height:25px;'><?php echo lang_get('common_status'); ?></td>
	                    	</tr>

	                    	<tr>
	                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('network_host_2'); ?></td>
	                        <td class="otherCol_420" style='height:25px;'><?php echo $host ?></td>
	                    	</tr>
	                    
	                    	<tr>
	                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('common_date'); ?></td>
	                        <td class="otherCol_420" style='height:25px;'><?php echo $date ?></td>
	                    	</tr>
	                    
	                    	<tr>
	                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('network_servers_1'); ?></td>
	                        <td class="otherCol_420" style='height:25px;'><?php echo $ftp_status ?></td>
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

