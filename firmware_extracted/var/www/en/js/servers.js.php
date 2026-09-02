<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	lang_set_active_language($_GET['lang']);
?>


//========================================================//
// System / EMail menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/service_get_servers.php","../php/service_set_servers.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_FTP','idTable_AFP','id_popup_server');
//========================================================//
// Reserved Port Number
//========================================================//
var gPort_list=new Array('22','23','80','111','443','548','445','3689','139','389','515','9091','3260',
                                     '6881','6882','6883','6884','6885','6886','6887','6888','6889','6969','8000');
//========================================================//
// Data type
//========================================================//
function ServersInfo(FTP,AFP,FTP_PORT)
{
	this.FTP = FTP;
	this.FTP_PORT = FTP_PORT;
	this.AFP = AFP;
}

//========================================================//
// Page status
//========================================================//
//var gStat = new Array('time_basic','time_edit','ntp_basic','ntp_edit');
//var fStat = gStat[0];

//========================================================//
// Information variable
//========================================================//
var gServersInfo = new ServersInfo('off','off','21');

//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	//debug(id);

	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	
	if ( id != ""){
	document.getElementById(id).style.display = "block";
	
	}
}
//========================================================//
// Get server time
//========================================================//
function Get_Servers_Info()
{
/*	
	document.getElementById('id_email').innerHTML = "Loading...";
	document.getElementById('id_SMTP_SERVER').innerHTML = "Loading...";
	document.getElementById('id_Subject').innerHTML = "Loading...";
	document.getElementById('id_Mailto').innerHTML = "Loading...";
	document.getElementById('id_HDD_Report').innerHTML = "Loading...";
	document.getElementById('id_HDD_Report_Term').innerHTML = "Loading...";
*/
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
	var _item = res.split(':');
	
	gServersInfo.FTP 		= _item[0];
	gServersInfo.AFP 		= _item[1];
	gServersInfo.FTP_PORT 		= _item[2];
	ShowServersInfo(gServersInfo);

}
//========================================================//
// Show server time
//========================================================//
function ShowServersInfo(gServersInfo)
{
	if (gServersInfo.FTP == 'off') 		{
						document.getElementById('rdoFTP_disable').checked 	= true;
						document.getElementById('txtFTP_PORT').value 		= '21';
						document.getElementById('txtFTP_PORT').disabled 	= true;
		}else {				
						document.getElementById('rdoFTP_enable').checked 	= true;
						document.getElementById('txtFTP_PORT').value 		= gServersInfo.FTP_PORT;
						document.getElementById('txtFTP_PORT').disabled 	= false;
		}

	if (gServersInfo.AFP == 'off') 		document.getElementById('rdoAFP_disable').checked 	= true;
		else 				document.getElementById('rdoAFP_enable').checked 	= true;
}

function form_check()
{
	if (document.getElementById('rdoFTP_disable').checked) document.getElementById('txtFTP_PORT').disabled 	= true;
		else 			document.getElementById('txtFTP_PORT').disabled 	= false;
}

function range_check()
{
	
	if (document.getElementById('txtFTP_PORT').value < 1 || document.getElementById('txtFTP_PORT').value > 65535) {
		alert("<?php echo lang_get('invalid_port_number')?> : 1~65535 ");
		document.getElementById('txtFTP_PORT').value='21';
		return false;
	}
		
}

//========================================================//
// Edit mode
//========================================================//
function FTPMode()
{
	//debug('editMode');
	showTable(gIdTable[0]);
	ShowServersInfo(gServersInfo);	
//	fStat = gStat[1];
}
function AFPMode()
{
	//debug('editMode');
	showTable(gIdTable[1]);
	ShowServersInfo(gServersInfo);	
//	fStat = gStat[1];
}

//========================================================//
// Set time to server
//========================================================//
var flag = "";
function Set_Servers_Info(mode)
{
	//=======================================================//
	// Popup
	//=======================================================//
	if(mode == "afp"){
		var _title = "AFP";
		var _id = "idTable_AFP";
		flag = "afp";
	}else if(mode == "ftp"){
		var _title = "FTP";
		var _id = "idTable_FTP";
		flag = "ftp";
	}else{
		alert("error");
	}

	var 	ftp, afp, ftp_port;
		
	if (document.getElementsByName('rdoFTP')[0].checked) {
		ftp='on';
		ftp_port = document.getElementById('txtFTP_PORT').value;
		//Check whether reserved fort or not
		var port=null;
		for(var i=0;gPort_list[i];i++)
		{
			if(ftp_port == gPort_list[i])
			{
				alert("<?php echo lang_get('invalid_port_number')?>");
				return false;
			}
		}			
	}else {
		ftp='off';
		ftp_port='21';
	}
	document.getElementById("idTable_AFP").style.display = "none";
	document.getElementById("idTable_FTP").style.display = "none";
	
	showTable('id_popup_server');
	document.getElementById('id_popup_title').innerHTML = _title;
	display_POPUP('setting_'+_title);
	if (document.getElementsByName('rdoAFP')[0].checked) afp='on';
	 	else afp='off';
			
	var _txText =	'&txtMode='+mode
			+"&rdoFTP="+ftp
			+"&txtFTP_PORT="+ftp_port
			+"&rdoAFP="+afp;
			

	sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);

	return true;
	
}
function onLoadST(oj)
{
	var res = decodeURIComponent(oj.responseText);
	Get_Servers_Info();
	/*if (res == 'ftp') FTPMode();
		else AFPMode(); */

	//=======================================================//
	// Popup
	//=======================================================//
	
	display_POPUP('complete');
	
}

//=======================================================//
// Popup
//=======================================================//
function close_popup(){

	if(flag == "afp"){
		AFPMode();
	}else if(flag == "ftp"){
		FTPMode();
	}else{
		alert("error");
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
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();
	//alert("this "+mode);
/////////User Create POPUP
	//alert(":"+mode+":");
	
	if(mode == 'setting_FTP'){
		popup_contents = "<?php echo lang_get('network_servers_msg_1')?>";
		popup_button = 'off';
	}	

	if(mode == 'setting_AFP'){
		popup_contents = "<?php echo lang_get('network_servers_msg_2')?>";
		popup_button = 'off';
	}

	if(mode == 'complete'){
		popup_contents = "<?php echo lang_get('network_servers_msg_3')?>";
		popup_button_link = "close_popup()";
	}

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	
	document.getElementById('system_message').innerHTML = popup;


}









//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/service/help_servers.html','Help_servers','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}
