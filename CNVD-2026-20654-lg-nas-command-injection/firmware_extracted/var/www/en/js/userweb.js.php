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
var gPhp = new Array("../php/service_get_userweb.php","../php/service_set_userweb.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_WWW','id_popup_server');
//========================================================//
// Reserved Port Number
//========================================================//
var gPort_list=new Array('22','23','80','111','443','548','445','3689','139','389','515','9091','3260',
                                     '6881','6882','6883','6884','6885','6886','6887','6888','6889','6969','8000','9090','9091');
//========================================================//
// Data type
//========================================================//
function ServersInfo(WEB,WEB_PORT,WEB_SSL,WEB_SSL_PORT,MYSQL,MYSQL_PASS)
{
	this.WEB = WEB;
	this.WEB_PORT = WEB_PORT;
	this.WEB_SSL = WEB_SSL;
	this.WEB_SSL_PORT = WEB_SSL_PORT;
        this.MYSQL = MYSQL;
        this.MYSQL_PASS = MYSQL_PASS;
}

//========================================================//
// Information variable
//========================================================//
var gServersInfo = new ServersInfo('off','80','off','8080','off','root');

//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	//debug(id);

	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	
	if ( id != ""){
	document.getElementById(id).style.display = "block";
	
	}
}
//========================================================//
// Get server time
//========================================================//
function Get_userweb_Info()
{
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}

function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
	var _item = res.split(':');
	
	gServersInfo.WEB 		= _item[0];
	gServersInfo.WEB_PORT 		= _item[1];
	gServersInfo.WEB_SSL 		= _item[2];
	gServersInfo.WEB_SSL_PORT	= _item[3];
	gServersInfo.MYSQL 		= _item[4];
	gServersInfo.MYSQL_PASS		= _item[5];
	ShowServersInfo(gServersInfo);
        //alert(gServersInfo.MYSQL);
}

//========================================================//
// Show server time
//========================================================//
function ShowServersInfo(gServersInfo)
{
	if (gServersInfo.WEB == 'off') 		{
						document.getElementById('rdoWWW_disable').checked 	= true;
						document.getElementById('txtWWW_PORT').value 		= '80';
						document.getElementById('txtWWW_PORT').disabled		= true;
						document.getElementById('chkWWW_SSL').checked 		= false;
						document.getElementById('chkWWW_SSL').disabled 		= true;
						document.getElementById('txtWWW_SSL_PORT').value	= '443';
						document.getElementById('txtWWW_SSL_PORT').disabled 	= true;
						
	}else {				
						document.getElementById('rdoWWW_enable').checked 	= true;
						document.getElementById('txtWWW_PORT').value 		= gServersInfo.WEB_PORT;
                                                
						if (gServersInfo.WEB_SSL == 'off') {
						      document.getElementById('chkWWW_SSL').checked 		= false;
						      document.getElementById('txtWWW_SSL_PORT').value		= '443';
						      document.getElementById('txtWWW_SSL_PORT').disabled 	= true;
						} else {
						      document.getElementById('chkWWW_SSL').checked 		= true;
						      document.getElementById('txtWWW_SSL_PORT').value		= gServersInfo.WEB_SSL_PORT;
						      document.getElementById('txtWWW_SSL_PORT').disabled 	= false;
                                                }						
	}

	if (gServersInfo.MYSQL == 'off') 	{
						document.getElementById('rdoMYSQL_disable').checked 	= true;
						document.getElementById('txtMYSQL_PASS').value		= '';
						document.getElementById('txtMYSQL_PASS').disabled 	= true;
	}else{
						document.getElementById('rdoMYSQL_enable').checked 	= true;
						document.getElementById('txtMYSQL_PASS').value		= gServersInfo.MYSQL_PASS;
						document.getElementById('txtMYSQL_PASS').disabled 	= false;
	}
}

function form_check()
{
	if (document.getElementById('rdoWWW_disable').checked) {
		document.getElementById('txtWWW_PORT').value 		= '';
		document.getElementById('txtWWW_PORT').disabled		= true;
		document.getElementById('chkWWW_SSL').checked 		= false;
		document.getElementById('chkWWW_SSL').disabled 		= true;
		document.getElementById('txtWWW_SSL_PORT').value	= '';
		document.getElementById('txtWWW_SSL_PORT').disabled 	= true;
        }else{
		document.getElementById('txtWWW_PORT').disabled 	= false;
		//document.getElementById('txtWWW_PORT').value 		= gServersInfo.WEB_PORT;
                document.getElementById('chkWWW_SSL').disabled 		= false;
		if(document.getElementById('chkWWW_SSL').checked) {
		    document.getElementById('txtWWW_SSL_PORT').disabled 	= false;
		  //  document.getElementById('txtWWW_SSL_PORT').value		= gServersInfo.WEB_SSL_PORT;
		}else{
		     document.getElementById('txtWWW_SSL_PORT').disabled 	= true;
		    //document.getElementById('txtWWW_SSL_PORT').value		= '';
		}

	}

	if (document.getElementById('rdoMYSQL_disable').checked) {
		document.getElementById('txtMYSQL_PASS').disabled 	= true;
		document.getElementById('txtMYSQL_PASS').value		= '';
        }else{
		document.getElementById('txtMYSQL_PASS').disabled 	= false;
		document.getElementById('txtMYSQL_PASS').value		= gServersInfo.MYSQL_PASS;
	}
}

function range_check()
{
	if (!isNaN(document.getElementById('txtWWW_PORT').value)){
		if (document.getElementById('txtWWW_PORT').value != 80 && document.getElementById('txtWWW_PORT').value != '' && (document.getElementById('txtWWW_PORT').value < 10000 ||document.getElementById('txtWWW_PORT').value > 50000)) {
			alert("<?php echo lang_get('invalid_port_number')?> : 80,10000~50000");
			document.getElementById('txtWWW_PORT').value='80';
			return false;
		}
	}
	else
	{
		alert("<?php echo lang_get('invalid_port_number')?> : 80,10000~50000");
		document.getElementById('txtWWW_PORT').value='80';
		return false;
	}

	if (!isNaN(document.getElementById('txtWWW_SSL_PORT').value)){
		if (document.getElementById('txtWWW_SSL_PORT').value != 443 && document.getElementById('txtWWW_SSL_PORT').value != '' && ( document.getElementById('txtWWW_SSL_PORT').value < 10000||document.getElementById('txtWWW_SSL_PORT').value > 50000)) {
			alert("<?php echo lang_get('invalid_port_number')?> : 443,10000~50000");
			document.getElementById('txtWWW_SSL_PORT').value='443';
			return false;
		}
	}
	else
	{
		alert("<?php echo lang_get('invalid_port_number')?> : 443,10000~50000");
		document.getElementById('txtWWW_SSL_PORT').value='443';
		return false;
	}
        if ((!isNaN(document.getElementById('txtWWW_PORT').value))&&(!isNaN(document.getElementById('txtWWW_SSL_PORT').value))){
           if (document.getElementById('txtWWW_SSL_PORT').value == document.getElementById('txtWWW_PORT').value){
                alert("<?php echo lang_get('invalid_port_number')?> ") ;
                 document.getElementById('txtWWW_PORT').value='80';
                 document.getElementById('txtWWW_SSL_PORT').value='443';
                 return false;
           }
        }
                
	
}

var flag = "";
function Set_Servers_Info()
{
	//=======================================================//
	// Popup
	//=======================================================//
	
	var WEB,WEB_PORT,WEB_SSL,WEB_SSL_PORT,MYSQL,MYSQL_PASS;

	
	if ( document.getElementById('rdoWWW_enable').checked ) WEB = 'on';
           else WEB = 'off';

	WEB_PORT 	= document.getElementById('txtWWW_PORT').value ;
	
        if (document.getElementById('chkWWW_SSL').checked ) WEB_SSL = 'on';
           else WEB_SSL = 'off';

	WEB_SSL_PORT 	= document.getElementById('txtWWW_SSL_PORT').value;
        
	
	if ( document.getElementById('rdoMYSQL_enable').checked) MYSQL = 'on';
           else MYSQL = 'off';

        MYSQL_PASS 	= document.getElementById('txtMYSQL_PASS').value;  
        

	if ( WEB_PORT == '' ) WEB_PORT = gServersInfo.WEB_PORT;
        if ( WEB_SSL_PORT == '') WEB_SSL_PORT = gServersInfo.WEB_SSL_PORT;
        if ( MYSQL_PASS == '' ) MYSQL_PASS = gServersInfo.MYSQL_PASS;

	showTable('id_popup_server');
        display_POPUP('setting_WWW');

	var _txText =	'&rdoWWW='+WEB
			+"&txtWWW_PORT="+WEB_PORT
			+"&rdoSSL="+WEB_SSL
                        +"&txtSSL_PORT="+WEB_SSL_PORT
			+"&rdoMYSQL="+MYSQL
			+"&txtMYSQL_PASS="+MYSQL_PASS;
			
	//alert(_txText);		

	sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);

	return true;
	
}
function onLoadST(oj)
{
	var res = decodeURIComponent(oj.responseText);
        //alert(res);
	//Get_Servers_Info();

	//=======================================================//
	// Popup
	//=======================================================//
	
	display_POPUP('complete');
	
}

//=======================================================//
// Popup
//=======================================================//
function close_popup(){
        showTable(gIdTable[0]);
        Get_userweb_Info();
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
	
	if(mode == 'setting_WWW'){
		popup_contents = "<?php echo lang_get('network_servers_msg_6')?>";
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
	var _win = window.open('../help/service/help_web_server.html','Help_web_servers','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}
