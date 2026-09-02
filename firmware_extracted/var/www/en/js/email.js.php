<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

	lang_set_active_language($t_lang_from_url[1]);
	// language information by url end
?>


//========================================================//
// System / EMail menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/email_get_info.php","../php/email_set_info.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_Mail_Info','idTable_Mail_Edit','idTable_POPUP');

//========================================================//
// Data type
//========================================================//
function EmailInfo(email,SMTP_SERVER,SMTP_AUTH,SMTP_SSL,SMTP_USER,SMTP_PASS,SUBJECT,MAILTO,HDD_REPORT,HDD_REPORT_TERM)
{
	this.email = email;
	this.SMTP_SERVER = SMTP_SERVER;
	this.SMTP_AUTH = SMTP_AUTH;
	this.SMTP_SSL = SMTP_SSL;
	this.SMTP_USER = SMTP_USER;
	this.SMTP_PASS = SMTP_PASS;
	this.SUBJECT = SUBJECT;
	this.MAILTO = MAILTO;
	this.HDD_REPORT = HDD_REPORT;
	this.HDD_REPORT_TERM = HDD_REPORT_TERM;
}

//========================================================//
// Page status
//========================================================//
//var gStat = new Array('time_basic','time_edit','ntp_basic','ntp_edit');
//var fStat = gStat[0];

//========================================================//
// Information variable
//========================================================//
var gEmailInfo = new EmailInfo('OFF','NONE','off','off','none','none','NAS Status Report','none','off','day');

//========================================================//
// Show table area
//========================================================//
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
			+"<tr><td height=\"120\" align=\"center\" class=\"red_text_9\">";
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();
	//alert("this "+mode);
/////////User Create POPUP
	//alert(":"+mode+":");
	
	if(mode == 'email_setting'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";
        	popup_button_header = "<tr><td align=\"center\">"
		popup_contents = "<?php echo lang_get('mail_msg_1')?>";
		popup_button = 'OFF';
	}	

	if(mode == 'email_on'){
		popup_contents = "<?php echo lang_get('mail_msg_2')?>";
		popup_button_link = "Get_Email_Info();showTable('idTable_Mail_Info');";
	}

	if(mode == 'email_off'){
		popup_contents = "<?php echo lang_get('mail_msg_3')?>";
		popup_button_link = "Get_Email_Info();showTable('idTable_Mail_Info');";
	}

	if(mode == 'network_fail'){
		popup_contents = "<?php echo lang_get('mail_msg_4')?>";
		popup_button_link = "showTable('idTable_Mail_Edit');";
	}


	//alert(popup_contents);

	//if(popup_button == 'OFF') popup = popup_header + popup_contents + popup_footer + "</table>";
	//	else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	if(popup_button == 'OFF') popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;
		

	showTable('idTable_POPUP');
	document.getElementById('system_message').innerHTML = popup;
}


function showTable(id)
{
	//debug(id);
	if ( id == 'idTable_Mail_Edit'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "block";
	document.getElementById(gIdTable[2]).style.display = "none";
	}
	if ( id == 'idTable_Mail_Info'){
	document.getElementById(gIdTable[0]).style.display = "block";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	}
	if ( id == 'idTable_POPUP'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "block";
	}
}
//========================================================//
// Get server time
//========================================================//
function Get_Email_Info()
{
	
	showTable('idTable_Mail_Info');
	document.getElementById('id_email').innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById('id_SMTP_SERVER').innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById('id_Subject').innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById('id_Mailto').innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById('id_HDD_Report').innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById('id_HDD_Report_Term').innerHTML = "<?php echo lang_get('common_loading')?>";

	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var _item = res.split(';');
	
	gEmailInfo.email 		= _item[0];
	gEmailInfo.SMTP_SERVER 		= _item[1];
	gEmailInfo.SMTP_AUTH 		= _item[2];
	gEmailInfo.SMTP_SSL 		= _item[3];
	gEmailInfo.SMTP_USER 		= _item[4];
	gEmailInfo.SMTP_PASS		= _item[5];
	gEmailInfo.SUBJECT		= _item[6];
	gEmailInfo.MAILTO		= _item[7];
	gEmailInfo.HDD_Report		= _item[8];
	gEmailInfo.HDD_Report_Term	= _item[9];

	ShowEmailInfo(gEmailInfo);
	

}
//========================================================//
// Show server time
//========================================================//
function ShowEmailInfo(EmailInfo)
{
	
	if(gEmailInfo.email == 'OFF'){
				document.getElementById('id_email').innerHTML = "<?php echo lang_get('common_disable')?>";
				document.getElementById('id_SMTP_SERVER').innerHTML = '&nbsp;';
			
				document.getElementById('id_Subject').innerHTML = '&nbsp;';
				document.getElementById('id_Mailto').innerHTML = '&nbsp;';
				
      	document.getElementById('id_HDD_Report').innerHTML = '&nbsp;';
  			document.getElementById('id_HDD_Report_Term').innerHTML = '&nbsp;'; 
  		}
 			else{ 
 				document.getElementById('id_email').innerHTML = "<?php echo lang_get('common_enable')?>";
        document.getElementById('id_SMTP_SERVER').innerHTML = gEmailInfo.SMTP_SERVER;
			
				document.getElementById('id_Subject').innerHTML = gEmailInfo.SUBJECT;
				document.getElementById('id_Mailto').innerHTML = gEmailInfo.MAILTO;
				
				document.getElementById('id_HDD_Report').innerHTML = gEmailInfo.HDD_Report;
				document.getElementById('id_HDD_Report_Term').innerHTML = gEmailInfo.HDD_Report_Term;
	}
}

function ShowEmailEdit(EmailInfo)
{
	//debug(gEmailInfo.email);
	if (gEmailInfo.email == 'OFF') 	{
			document.getElementById('rdo_Email_Disable').checked 	= true;
			check_email_form();
			/*
			document.getElementById('SMTP_SERVER').disabled = true;
			document.getElementById('chkSMTP_AUTH').disabled = true;	
			document.getElementById('chkSMTP_SSL').disabled = true;
			document.getElementById('chkHDD_Report').disabled = true;
			document.getElementById('SMTP_USER').disabled = true;
			document.getElementById('SMTP_PASS').disabled = true;
			//document.getElementById('SUBJECT').disabled = true;
			document.getElementById('SMTP_MAILTO').disabled = true;
			document.getElementById('HDD_Report_Term').disabled = true;
			*/
			
	}else 				document.getElementById('rdo_Email_Enable').checked 	= true;
	
	if (gEmailInfo.SMTP_AUTH == 'OFF') 	document.getElementById('chkSMTP_AUTH').checked 	= false;
		else 				document.getElementById('chkSMTP_AUTH').checked 	= true;
	if (gEmailInfo.SMTP_SSL == 'OFF') 	document.getElementById('chkSMTP_SSL').checked 		= false;
		else 				document.getElementById('chkSMTP_SSL').checked 		= true;
	if (gEmailInfo.HDD_Report == 'OFF') 	document.getElementById('chkHDD_Report').checked 	= false;
		else 				document.getElementById('chkHDD_Report').checked 	= true;

	if (gEmailInfo.HDD_Report_Term == 'Daily') 	document.getElementById('HDD_Report_Term').options[0].selected 	= true;
		else if (gEmailInfo.HDD_Report_Term == 'Weekly') document.getElementById('HDD_Report_Term').options[1].selected = true;				else if (gEmailInfo.HDD_Report_Term == 'Monthly') document.getElementById('HDD_Report_Term').options[2].selected = true;			

	if(gEmailInfo.SMTP_SERVER.toUpperCase()!='NONE') document.getElementById('SMTP_SERVER').value 	= gEmailInfo.SMTP_SERVER;	
		else document.getElementById('SMTP_SERVER').value 	= '';
	if(gEmailInfo.SMTP_USER.toUpperCase()!='NONE') document.getElementById('SMTP_USER').value 	= gEmailInfo.SMTP_USER;
		else document.getElementById('SMTP_USER').value = '';
	if(gEmailInfo.SMTP_PASS.toUpperCase()!='NONE') document.getElementById('SMTP_PASS').value 	= gEmailInfo.SMTP_PASS;
		else document.getElementById('SMTP_PASS').value = '';
	document.getElementById('SUBJECT').value 	= gEmailInfo.SUBJECT;
	if(gEmailInfo.MAILTO.toUpperCase()!='NONE') document.getElementById('SMTP_MAILTO').value 	= gEmailInfo.MAILTO;
		else document.getElementById('SMTP_MAILTO').value =''; 
}

function check_email_form()
{

	if ( document.getElementById('rdo_Email_Enable').checked == true ){
			document.getElementById('SMTP_SERVER').disabled = false;
			//document.getElementById('SUBJECT').disabled = false;
			document.getElementById('SMTP_MAILTO').disabled = false;
			document.getElementById('chkSMTP_AUTH').disabled = false;
			//document.getElementById('chkSMTP_SSL').disabled = false;
			document.getElementById('chkHDD_Report').disabled = false;
	
		if(document.getElementById('chkSMTP_AUTH').checked == true ){
				document.getElementById('SMTP_USER').disabled = false;
				document.getElementById('SMTP_PASS').disabled = false;
				document.getElementById('chkSMTP_SSL').disabled = false;		
		}else 
		{
				document.getElementById('SMTP_USER').disabled = true;
				document.getElementById('SMTP_PASS').disabled = true;
				document.getElementById('chkSMTP_SSL').disabled = true;
				document.getElementById('chkSMTP_SSL').checked = false;
				document.getElementById('SMTP_USER').value = '';
				document.getElementById('SMTP_PASS').value = '';
		}
		
		if(document.getElementById('chkHDD_Report').checked == true){
			document.getElementById('HDD_Report_Term').disabled = false;
		}else
		{
			document.getElementById('HDD_Report_Term').disabled = true;
			document.getElementById('HDD_Report_Term').options[0].selected 	= true;
		}
	
	}else{
			document.getElementById('SMTP_SERVER').disabled = true;
			document.getElementById('chkSMTP_AUTH').disabled = true;	
			document.getElementById('chkSMTP_SSL').disabled = true;
			document.getElementById('chkHDD_Report').disabled = true;
			document.getElementById('SMTP_USER').disabled = true;
			document.getElementById('SMTP_PASS').disabled = true;
			//document.getElementById('SUBJECT').disabled = true;
			document.getElementById('SMTP_MAILTO').disabled = true;
			document.getElementById('HDD_Report_Term').disabled = true;
			
			document.getElementById('SMTP_SERVER').value = '';
			document.getElementById('chkSMTP_AUTH').checked = false;	
			document.getElementById('chkSMTP_SSL').checked = false;
			document.getElementById('chkHDD_Report').checked = false;
			document.getElementById('SMTP_USER').value = '';
			document.getElementById('SMTP_PASS').value = '';
			document.getElementById('SUBJECT').value = "<?php echo lang_get('mail_edit_7')?>";
			document.getElementById('SMTP_MAILTO').value = '';
			document.getElementById('HDD_Report_Term').options[0].selected 	= true;
	}

}

//========================================================//
// Edit mode
//========================================================//
function editMode()
{
	//debug('editMode');
	
	ShowEmailEdit(gEmailInfo);
	showTable(gIdTable[1]);
//	fStat = gStat[1];
}
function InfoMode()
{
	//debug('editMode');
	showTable(gIdTable[0]);
	ShowEmailInfo(gEmailInfo);	
//	fStat = gStat[1];
}

//========================================================//
// Set time to server
//========================================================//
function Set_Email_Info(mode)
{
	
		var 	email,SMTP_SERVER,SMTP_AUTH,SMTP_SSL,SMTP_USER,
			SMTP_PASS,SMTP_SUBJECT,SMTP_MAILTO,HDD_Report,HDD_Report_Term;
		
		if (document.getElementsByName('rdo_Email')[0].checked) email='ON';
		 	else email='OFF';

			SMTP_SERVER 	= document.getElementById('SMTP_SERVER').value;
			SMTP_USER 	= document.getElementById('SMTP_USER').value;
			SMTP_PASS 	= document.getElementById('SMTP_PASS').value;
			SMTP_SUBJECT 	= document.getElementById('SUBJECT').value;
			SMTP_MAILTO 	= document.getElementById('SMTP_MAILTO').value;
	  	/*
	  	alert(SMTP_SERVER);
	  	if(SMTP_SERVER == '') SMTP_SERVER=gEmailInfo.SMTP_SERVER;
	  	if(SMTP_USER == '') SMTP_SERVER=gEmailInfo.SMTP_USER;
	  	if(SMTP_PASS == '') SMTP_SERVER=gEmailInfo.SMTP_PASS	;
	  	if(SMTP_SUBJECT == '') SMTP_SERVER=gEmailInfo.SUBJECT;
	  	if(SMTP_MAILTO == '') SMTP_SERVER=gEmailInfo.MAILTO;
			alert(SMTP_SERVER);
			*/
			if (document.getElementById('chkSMTP_AUTH').checked) SMTP_AUTH = 'ON';
				else SMTP_AUTH = 'OFF';
			if (document.getElementById('chkSMTP_SSL').checked) SMTP_SSL = 'ON';
				else SMTP_SSL = 'OFF';
			if (document.getElementById('chkHDD_Report').checked) HDD_Report = 'ON';
				else HDD_Report = 'OFF';
		
			if (document.getElementById('HDD_Report_Term').options[0].selected) HDD_Report_Term ='Daily';
			if (document.getElementById('HDD_Report_Term').options[1].selected) HDD_Report_Term ='Weekly';
			if (document.getElementById('HDD_Report_Term').options[2].selected) HDD_Report_Term ='Monthly';
		
			var _txText =	'&rdoMail='+email
				+"&txtSMTP="+SMTP_SERVER
				+"&chkSMTPAuth="+SMTP_AUTH
				+"&txtSMTP_User="+SMTP_USER
				+"&txtSMTP_Pass="+SMTP_PASS
				+"&chkSMTPAuthSSL="+SMTP_SSL
				+"&txtSubject="+SMTP_SUBJECT
				+"&txtMailTo="+SMTP_MAILTO
				+"&chkMailSendHDDReport="+HDD_Report
				+"&txtMailHDDStatusTime="+HDD_Report_Term
				+"&txtClickButton="+mode;
		
			//alert(_txText);
			display_POPUP('email_setting');			
			sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);

		return true;
	
}
function onLoadST(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);
	
}

//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2','3');
function show_help()
{
	debug(help);
	switch(help)
	{
		case 1:
		var _win = window.open('../help/system/help_mail.html','Help_mail','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_mail.html','Help_mail','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		case 3:
		
		var _win = window.open('../help/system/help_mail.html','Help_mail','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		default:
		break;
	}

}