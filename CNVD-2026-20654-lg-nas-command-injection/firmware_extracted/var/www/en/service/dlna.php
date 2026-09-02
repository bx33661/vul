<?	 include "../inc/top.php";  ?>

<!-- Debugging setting -->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>
<!-- Ajax lib -->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>
<!--<script language="javascript1.2" src="../js/jquery-1.2.6.pack.js" charset="utf-8"></script>-->
<!-- Common lib -->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>
<!-- Comnso lib -->
<script language="javascript1.2" src="../js/comnso_restore_view.js" charset="utf-8"></script>
<script language="javascript1.2" src="../js/comnso_resfview.js.php" charset="utf-8"></script>
<!-- Dlna -->
<script language="javascript1.2" src="../js/dlna.js.php" charset="utf-8"></script>
<!----------------------------------->

<SCRIPT LANGUAGE="JavaScript">
/*function Set_iSCSI2()
{	
	
	if (document.getElementsByName('rdoiscsi')[0].checked) {	
		var _txText =	'&ISCSI_SET='+"ON";

		document.getElementById('page_loading').style.display = 'block';
		sendRequest(onLoadST,_txText,'post',"../php/idlnatask.php",true,true);
	}
	else if (document.getElementsByName('rdoiscsi')[1].checked) {
		var _txText =	'&ISCSI_SET='+"OFF";

		document.getElementById('page_loading').style.display = 'block';
		sendRequest(onLoadST,_txText,'post',"../php/iscsi_task.php",true,true);
	}

	return true;

}
*/

function onLoadST(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);

	code = res.split(':');

	if(code[0] == 'NG')  //If disc is burning now through web 
	{
		alert("<?php echo lang_get('volume_conf_10')?>");
		document.getElementById('rdoiscsi_disable').checked 	= true;
		document.getElementById('rdoiscsi_enable').checked 	= false;
	}
	else
	{
		display_POPUP(code[1]);
	}


	
	
	//document.getElementById('page_loading').style.display = 'none';

}


function Set_DLNA()
{
	var 	dlna;
	var 	WORKGROUP1,WORKGROUP2;
	var _txText;

	if (document.getElementsByName('rdodlna')[0].checked) dlna='on'
	 	else dlna='off'

	WORKGROUP1   	= document.getElementById('dlna_source1').value;
	//WORKGROUP2	= "/mnt/disk" + document.getElementById('dlna_source2').value;

	//if(WORKGROUP1 != "/mnt/disk")
	//{	
		_txText = '&rdodlna='+dlna
				+'&DLNA_PATH_1='+WORKGROUP1;
				//+'&DLNA_PATH_2='+WORKGROUP2;
	//}
	//else
	//{
	//	_txText = '&rdodlna='+dlna;
	//}

	display_POPUP('in_process');			
	//document.getElementById('page_loading').style.display = 'block';
	sendRequest(onLoadST,_txText,'post',"../php/service_set_dlna.php",true,true);	


	return true;
}

function Get_DLNA_Info()
{
	sendRequest(onLoadDT,'','post',"../php/service_get_dlna.php",true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
	var _item = res.split(':');
	
	if (_item[0] == 'off') 	document.getElementById('rdodlna_disable').checked 	= true;
		else 	document.getElementById('rdodlna_enable').checked 	= true;		

	document.getElementById('dlna_source1').value = _item[1];	


}

function display_POPUP(mode)
{
	
	var popup_header = new String();
	var popup_footer = new String();
	var popup_contents = new String();
	var popup_button = new String();
	var popup_button_header = new String();
	var popup_button_link = new String();
	var popup_button_footer = new String();

	popup_header 	= "<table width=\"420\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >"
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">";
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();

	if(mode == 'in_process'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";	
		popup_button_header = "<tr><td align=\"center\">"
		popup_contents =  "<?php echo lang_get('dlna_1')?>"+" "+"<?php echo lang_get('common_msg_progress')?>";
		popup_button = 'off';
	}	
	else if(mode.match('on')){
		popup_contents = "<?php echo lang_get('dlna_1')?>"+" "+"<?php echo lang_get('common_msg_service_start')?>";
		popup_button_link = "Get_DLNA_Info();showTable('idTable_DLNA');";
	}
	else if(mode.match('off')){
		popup_contents = "<?php echo lang_get('dlna_1')?>"+" "+"<?php echo lang_get('common_msg_service_stop')?>";
		popup_button_link = "Get_DLNA_Info();showTable('idTable_DLNA');";
	}
	/*if(mode == 'id_fail'){
		popup_contents = "<?php echo lang_get('dlna_1')?>"+" "+"";
		popup_button_link = "showTable('idTable_DLNA');";
	}*/

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('idTable_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}

function showTable(id)
{
	
	if(id=='idTable_POPUP'){
	document.getElementById('idTable_DLNA').style.display = "none";
	document.getElementById('idTable_POPUP').style.display = "block";
	}

	if(id=='idTable_DLNA'){
	document.getElementById('idTable_DLNA').style.display = "block";
	document.getElementById('idTable_POPUP').style.display = "none";
	}
}


</SCRIPT>

<!--Input field ID for selected folder path in the child window-->
<input type="hidden" id="idInputFieldId" value="" />
<input type="hidden" id="idPathMode" value="dlna" /><!--For folder browser : rip/store/burn/schedule-->
<!--For folder browser : end : rip/store/burn/schedule-->

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

		<div id="idTable_DLNA" style="display:block;">	  	
	  		
                  	<!-- 1. Page Title : Network -->	 				 
			<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
                    <tr>
                      <td height="50" valign="top"><img src="../images/headtitle/dlna.gif"/></td>
                    </tr>
			</table>
                                
                                
<?php if ($_SESSION['username'] == "admin"){ ?>                               
                                
                                    
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
			<td class="header" width="250px"><?php echo 'DLNA'?></td>
			<td class="header" width="420px">
			<input type="radio" name="rdodlna" id="rdodlna_enable" value="on" /><label for="rdodlna_enable"><?php echo lang_get('common_enable')?></label>	
			<input type="radio" name="rdodlna" id="rdodlna_disable" value="off"  /><label for="rdodlna_disable"><?php echo lang_get('common_disable')?></label>
			</td>
                    	</tr>
			  		<tr>
					<td class="firstCol_250"><?php echo lang_get('common_source')?></td>
					<td class="otherCol_420">
						<table border="0" cellspacing="0px" cellpadding="0px">
			      		<tr>
			      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('dlna_source1');" style="cursor:pointer;"></td>
			      			<td><input name="dlna_source1" type="text" class="inputtext" id="dlna_source1" size="40" value="" disabled /></td>
			      			<td><input type="hidden" id="popup_mode" value="sch_backup"></td>
			      		</tr>
			      			</table>												
					</tr>                    	
                    	<tr>
                    		<td></td>
			<td colspan="2" align="right" style="padding:20 0 0 0px"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_DLNA();" class="buttons"/></td>
                    	</tr>
                    	</table>  
                    	                    	
<? } else{ ?>  
<? } ?>
		</div>
		
			<!-- Message Table : Start -->	
		<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" >
			<table width="420" height="260" align="center" cellspacing="0" cellpadding="0" >
			<tr>
				<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;"><span class="popup_text"><div id='idMsgTitle'>DLNA</div></span></td>
			</tr>
			<tr>
				<td align="center" valign="top" style="padding:0 0 0 0px">
					<div id="system_message"><?php echo lang_get('common_loading')?></div>
				</td>
			</tr>
			</table> 		
		</div>

<!-- 
                  	<table width="670" valign="center" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
                     	
<script language="javascript">
			var _info = navigator.userAgent;
			var ie = (_info.indexOf("MSIE") > 0);
			var win = (_info.indexOf("Win") > 0);
	
if(win)
{
  document.writeln('<object classid = "clsid:CAFEEFAC-0015-0000-0017-ABCDEFFEDCBA" width="400" height="110" align = "middle" ');
  document.writeln('codebase = "http://java.sun.com/update/1.5.0/jinstall-1_5_0_17-windows-i586.cab#Version=5,0,170,4">');
  document.writeln('<param name = "code" value = "IscsiJApplet.class">');
  document.writeln('<param name = "archive" value = "IscsiApplet.jar">');
  document.writeln('<param name = "type" value = "application/x-java-applet;jpi-version=1.5.0_17">');
  document.writeln('<param name = "scriptable" value = "true">');
  document.writeln('</object>');
}else
{
     /* mac and linux */
     document.writeln('<applet ');
     document.writeln('archive = "IscsiApplet.jar"');
     document.writeln('code = "IscsiJApplet"');
     document.writeln('name = "IscsiJApplet"');
     document.writeln('hspace = "0"');
     document.writeln('vspace = "0" MAYSCRIPT="yes"');
     document.writeln('width = "800"');
     document.writeln('height = "550"');
     document.writeln('align = "middle"');
     document.writeln('</applet>');
}
</script>

                    	</tr>
                    	</table>
-->                    	                    	
            

                    	
                    	
                    	
                    	
                    
		</td>
		</tr>
		
		<tr>
		
		</tr>		
  		</table>
  	</td>
  	</tr>
	</table>
</td>
</tr>        

<?php include "../inc/bottom.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language='javascript' charset='utf-8'>
function init()
{
	Get_DLNA_Info();
}
init();
</script>
