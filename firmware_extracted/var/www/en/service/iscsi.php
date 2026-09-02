<?	 include "../inc/top.php";  ?>

<SCRIPT LANGUAGE="JavaScript">
//=======================================================//
// Binary Semaphore
//=======================================================//
var gSemaphore_apply=false;
var iscsi_info;

//function Set_initialization()
//{
//	var initialization = 'on'
//	_txText = '&rdoiscsi_init='+initialization

//	document.getElementById('txtINCOMING_USERPASS').value = "<?php echo lang_get('common_loading'); ?>";
//	document.getElementById('txtOUTGOING_USERPASS').value = "<?php echo lang_get('common_loading'); ?>";

//	sendRequest(onLoadST,_txText,'post',"../php/service_set_iscsi.php",true,true);	
//}




function Set_iSCSI()
{			
	var initialization = 'off'
	var 	iscsi;

	//check whether previous command was done	
	if(gSemaphore_apply == true)
		return false;
		
	if (document.getElementsByName('rdoiscsi')[0].checked)	iscsi='on'
	else iscsi='off'

	if (document.getElementById('checkboxChapEdit').checked)  chap='on';
	else 	chap='off';

	var target_secret = document.getElementById('txtINCOMING_USERPASS').value;
	var initiator_secret = document.getElementById('txtOUTGOING_USERPASS').value;

	if(iscsi == 'on' && chap == 'on')
	{
		if(!PASSCheck()){
			display_POPUP('PASS_err');
			return false;
		}		
	}
	
	var _txText =	'&rdoiscsi='+iscsi
				+'&rdoiscsi_init='+initialization
				+'&rdoiscsi_chap='+chap
				+'&rdoiscsi_target='+target_secret
				+'&rdoiscsi_initiator='+initiator_secret;

	display_POPUP('in_process');		
	//document.getElementById('page_loading').style.display = 'block';

	gSemaphore_apply = true;
	sendRequest(onLoadST,_txText,'post',"../php/service_set_iscsi.php",true,true);	
	
	
	return true;
}

function onLoadST(oj)
{
	var res = new String();
	var code = new Array();
	
	gSemaphore_apply = false;
	res = decodeURIComponent(oj.responseText);
	
	//alert(res);
	
	code = res.split(':');
	if(code[0] == 'NG')  //If disc is burning now through web 
	{
		if(code[1] == "DISC BACKUP")
			alert("<?php echo lang_get('iscsi_warn')?>");
		else	if(code[1] == "DISC BURNING")
			alert("<?php echo lang_get('iscsi_2')?>");
			
		document.getElementById('rdoiscsi_disable').checked 	= true;
		document.getElementById('rdoiscsi_enable').checked 	= false;

		Get_iSCSI_Info();
		showTable('idTable_iSCSI');
	}
	else if(code[0] == 'INIT')
	{
		Get_iSCSI_Info();		
	}
	else
	{
		display_POPUP(code[1]);
	}
	
	//document.getElementById('page_loading').style.display = 'none';

}

function Get_iSCSI_Info()
{
	sendRequest(onLoadDT,'','post',"../php/service_get_iscsi.php",true,true);
	return true;
}

function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
			
	//gPrinter 			= res;	
	//ShowPrinterInfo();

	iscsi_info = res.split(' ');


	if (iscsi_info[0] == 'off') 	
	{
		show_chap('off');
	}
	else 
	{
		show_chap('on');
	}
		
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

	popup_header_small = "<table width=\"420\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >"
			+"<tr><td height=\"40\" align=\"center\" class=\"red_s2\">";

	
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	

	var popup = new String();
	if(mode == 'in_process'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";
		popup_button_header = "<tr><td align=\"center\">"
		popup_contents = "<?php echo lang_get('iscsi_1')?>"+" "+"<?php echo lang_get('common_msg_progress')?>";
		popup_button = 'off';
	}	
	else if(mode.match('on')){	
		popup_contents1 = "<?php echo lang_get('iscsi_1')?>"+" "+"<?php echo lang_get('common_msg_service_start')?>";
		popup_contents2 =  "<?php echo lang_get('iscsi_4')?>"; 
		popup_contents3 = "<?php echo lang_get('iscsi_5')?>"; 
		popup_button_link = "Get_iSCSI_Info();showTable('idTable_iSCSI');";
	}
	else if(mode.match('off')){
		popup_contents = "<?php echo lang_get('iscsi_1')?>"+" "+"<?php echo lang_get('common_msg_service_stop')?>";
		popup_button_link = "Get_iSCSI_Info();showTable('idTable_iSCSI');";
	}
	else	if(mode == 'PASS_err'){
		popup_contents = "<?php echo lang_get('iscsi_error_password')?>";
		popup_button_link =  "Get_iSCSI_Info();showTable('idTable_iSCSI');";
	}
	/*if(mode == 'id_fail'){
		popup_contents = "<?php echo lang_get('iscsi_1')?>";
		popup_button_link = "showTable('idTable_iSCSI');";
	}*/

	if(popup_button == 'off') 
		popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
	else if(mode.match('on'))
		popup = popup_header_small + popup_contents1 + popup_footer +popup_header_small+ popup_contents2 + popup_footer+ popup_header_small + popup_contents3 + popup_footer + popup_button_header +popup_button_link+ popup_button_footer;
	else 
		popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('idTable_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}

function showTable(id)
{
	
	if(id=='idTable_POPUP'){
	document.getElementById('idTable_iSCSI').style.display = "none";
	document.getElementById('idTable_POPUP').style.display = "block";
	}

	if(id=='idTable_iSCSI'){
	document.getElementById('idTable_iSCSI').style.display = "block";
	document.getElementById('idTable_POPUP').style.display = "none";
	}
}

function PASSCheck() {
	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
	var in_pw = document.getElementById('txtINCOMING_USERPASS');
	var out_pw = document.getElementById('txtOUTGOING_USERPASS');
	
	if(!(valid_passwd(in_pw))) return false;
	if(!(valid_passwd(out_pw))) return false;
	
	var res;
	res = containsCharsOnly(in_pw,chars);
	if(!res) return false;
	res = containsCharsOnly(out_pw,chars);
	if(!res) return false;
	
	return true; 
}

function valid_passwd(input) {
    	if(input.value.length!=12) return false;	
	return true;
}

function containsCharsOnly(input,chars) {
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           	return false;
    	}
    	return true;
}



function show_chap(enabled)
{
	if(iscsi_info == null)
	 	return;

	if(enabled == 'on')
	{
		
		document.getElementById('txtINCOMING_USERPASS').disabled = false;
		document.getElementById('txtOUTGOING_USERPASS').disabled = false;
		
		document.getElementById('rdoiscsi_enable').checked = true;	
		document.getElementById('rdoiscsi_disable').checked = false;

		document.getElementById('txtINCOMING_USERNAME').innerHTML= iscsi_info[2];
		document.getElementById('txtOUTGOING_USERNAME').innerHTML = iscsi_info[4];

		if( iscsi_info[0] == 'on' ) {
			if( iscsi_info[1] == 'on') {
				document.getElementById('checkboxChapEdit').checked = true;
				document.getElementById('txtINCOMING_USERPASS').value =  iscsi_info[3];
				document.getElementById('txtOUTGOING_USERPASS').value =  iscsi_info[5];
			}
			else {
				document.getElementById('checkboxChapEdit').checked = false;
				document.getElementById('txtINCOMING_USERPASS').value = ''; 
				document.getElementById('txtOUTGOING_USERPASS').value =  '';
			}
		}
		else {
			document.getElementById('checkboxChapEdit').checked = true;
			document.getElementById('txtINCOMING_USERPASS').value = ''; 
			document.getElementById('txtOUTGOING_USERPASS').value =  '';
		}
	}
	else
	{
		document.getElementById('txtINCOMING_USERPASS').disabled = true;	
		document.getElementById('txtOUTGOING_USERPASS').disabled = true;
		
		document.getElementById('rdoiscsi_disable').checked = true;
		document.getElementById('rdoiscsi_enable').checked = false;
		document.getElementById('txtINCOMING_USERNAME').innerHTML = '-';
		document.getElementById('txtINCOMING_USERPASS').value = '';
	
		document.getElementById('txtOUTGOING_USERNAME').innerHTML = '-';
		document.getElementById('txtOUTGOING_USERPASS').value = '';

		document.getElementById('checkboxChapEdit').checked = false;
	}
}

//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/service/help_iscsi.html','Help_ddns','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
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
		
		<div id="idTable_iSCSI" style="display:block;">	  		
                  	<!-- 1. Page Title : Network -->	 				 
			<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
                    <tr>
                      <td height="50" valign="top"><img src="../images/headtitle/htit_iscsi.gif"/></td>
                    </tr>
			</table>
                                
                                
<?php if ($_SESSION['username'] == "admin"){ ?>                               
                                
                                    
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
			<td class="header" width="250px"><?php echo lang_get('iscsi_1')?></td>
			<td class="header" width="420px">
			<input type="radio" name="rdoiscsi" id="rdoiscsi_enable" value="on" onclick="show_chap('on');" /><label for="rdoiscsi_enable"><?php echo lang_get('common_enable')?></label>	
			<input type="radio" name="rdoiscsi" id="rdoiscsi_disable" value="off" onclick="show_chap('off');" /><label for="rdoiscsi_disable"><?php echo lang_get('common_disable')?></label>
			</td>
                    	</tr>

                     <tr>
                     <td class="firstCol_250"> CHAP </td>
                     <td class="otherCol_420">
                     <input type="checkbox" name="checkboxChapEdit" id="checkboxChapEdit"><?php echo lang_get('iscsi_chap_enable'); ?> 
                     </tr>
                    	
                     <tr>                     
                     <td class="firstCol_250"><?php echo lang_get('iscsi_user_name'); ?></td>
                     <td class="otherCol_420"><div id="txtINCOMING_USERNAME"><?php echo lang_get('common_loading'); ?></div>
                     </td>
                     </tr>
                     <tr>                     
                     <td class="firstCol_250"><?php echo lang_get('iscsi_target_secret'); ?></td>
                     <td class="otherCol_420">
                     <input name="txtINCOMING_USERPASS" type="text" class="inputtext" id="txtINCOMING_USERPASS" value="" size="15" maxlength="25" /></td>
                     </tr>
                     
                     <tr>
                     <td class="firstCol_250"><?php echo lang_get('iscsi_initiator_name'); ?></td>
                     <td class="otherCol_420"><div id="txtOUTGOING_USERNAME"><?php echo lang_get('common_loading'); ?></div>
                     </td>
                     </tr>
                     <tr>
                     <td class="firstCol_250"><?php echo lang_get('iscsi_initiator_secret'); ?></td>
                     <td class="otherCol_420">
                     <input name="txtOUTGOING_USERPASS" type="text" class="inputtext" id="txtOUTGOING_USERPASS" value="" size="15" maxlength="25" /></td>
                     </tr>
                     		                    	
                    	<tr>
      			<td colspan="2" align="right" style="padding:20 0 0 0px">
                        <!-- <img src="../images/btn/btn_initialization.gif" border="0" onclick="Set_initialization();" class="buttons"/> -->
			<img src="../images/btn/btn_apply.gif" border="0" onclick="Set_iSCSI();" class="buttons"/></td>
                    	</tr>
                    	</table>


       	
<? } else{ ?>                    	


<? } ?>
		</div>
			<!-- Message Table : Start -->	
				<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" >
					<table width="420" height="260" align="center" cellspacing="0" cellpadding="0" >
					<tr>
						<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;"><span class="popup_text"><div id='idMsgTitle'>iSCSI</div></span></td>
						
						
					</tr>
					<tr>
					
						<td align="center" valign="top" style="padding:0 0 0 0px">
							<div id="system_message"><?php echo lang_get('common_loading')?></div>
							<!--<img Id="img_page_loading" src="../images/Burn/file_box_loading.gif"/>-->
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
init();
function init()
{
	Get_iSCSI_Info();		// get timezone list from server
}
</script>
