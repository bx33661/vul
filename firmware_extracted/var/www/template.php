<?php include "../inc/top.php";  ?>

<?php

$variable  = "data";

exec("sudo mount | grep /mnt/disk/" , $_matches);

$str1 = trim(exec ($cmd_raid));
$str1_array = preg_split("/[\s,]+/", $str1);

if ( $str1_array[0] != "rootfs") {
	
	foreach($_matches as $_val){
		if(eregi($str_raid , $_val)){
			preg_match("/^\/dev\/(.+)\s+on\s+(".str_replace('/' , '\/' , $str_raid).")\s+type\s+(.+)\s+/" , $_val , $_exploded);
			$str1_array[6]=$_exploded[3];
		}
	}	
}
else {

}

?>


<SCRIPT LANGUAGE="JavaScript">
function Format_Volume(volIndex)
{
	if(!confirm('Continue?')) return;
	
	var 	varFOMRAT_TYPE,varFORMAT_VOL;

	var _select = document.getElementById('idSelect'+volIndex);
	varFORMAT_VOL = (volIndex == 1)? <?php echo '\''.$str1_array[5].'\''; ?>:<?php echo '\''.$str2_array[5].'\''; ?>;
	varFORMAT_TYPE = _select[_select.selectedIndex].value;

	var _txText =	'&VOL_TASK_CMD='+"FORMAT"
			+'&FORMAT_TYPE='+varFORMAT_TYPE
			+'&FORMAT_VOL='+varFORMAT_VOL;

	alert(_txText);

	sendRequest(onLoadST,_txText,'post',"../php/volume_task.php",true,true);
	
	return true;
}

function onLoadST(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	alert(res);
	
	code = res.split(':');
	
	alert(code);
}

</SCRIPT>

<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="245" valign="top"><?php include "../inc/left.php";  ?></td>	<!-- left Navigation -->
	<td width="100%" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
		</tr>
		<tr>
		<td style="padding:0 0 0 50px">
	  		<div id="idTable_Mail_Info" style='display:block'>
	  		
                  	<!-- 1. Page Title : Network -->	 				 
			<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
			<tr>
			<td height="50" valign="top"><img src="../images/headtitle/htit_volume.gif" /></td>
			</tr>
			</table>
                  
                  
                  
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
                        <td class="header" style='height:25px;'><?php echo lang_get('volume_9'); ?></td>
                        <td class="header" style='height:25px;'><?php echo $str1_array[0] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_10'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str1_array[1] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_18'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
                        	<select id='idSelect1'>
                        		<option <?php if($str1_array[6] == 'ext2'){ ?> selected <?php }?> value='ext2'>ext2</option>
                        		<option <?php if($str1_array[6] == 'ext3'){ ?> selected <?php }?> value='ext3'>ext3</option>
                        		<option <?php if($str1_array[6] == 'xfs'){ ?> selected <?php }?> value='xfs'>xfs</option>
                        	</select>
                        	<input type='button' value='Format' onclick="Format_Volume('1');"/>
                        </td>
                    	</tr>

                  	</table>   
                  	
                  	<?php if ( $valid_vol_count == 2 ){ ?> 
                  	
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
                        <td class="header" style='height:25px;'><?php echo lang_get('volume_9'); ?></td>
                        <td class="header" style='height:25px;'><?php echo $str2_array[0] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_10'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
                        	<select id='idSelect3'>
                        		<option value='individual'><?php echo lang_get('volume_conf_1'); ?></option>
                        		<option value='linear'><?php echo lang_get('volume_conf_2'); ?></option>
                        		<option value='raid1'><?php echo lang_get('volume_conf_3'); ?></option>
                        		<option value='raid0'><?php echo lang_get('volume_conf_4'); ?></option>
                        		<option value='raid01'><?php echo lang_get('volume_conf_5'); ?></option>
                        		<option value='raidlinear'><?php echo lang_get('volume_conf_6'); ?></option>
                        	</select>
                        	<input type='button' value='Change' onclick="Change_Volume_Configuration();"/>                        
			</td>
                    	</tr>
                                        	
                  	</table>   
                  	
                  	<? } else{ ?>
                  	
                  	
                  	
                  	<? } ?>
                  	
    			</div>
		</td>
		</tr>
  		</table>
  	</td>
  	</tr>
	</table>
</td>
</tr>
          

<?php include "../inc/bottom.php";  ?>







