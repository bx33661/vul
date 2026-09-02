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
var gPhp = new Array("../php/service_get_ddns.php","../php/service_set_ddns.php","../php/service_set_upnp_p_fwd.php","../php/service_get_upnp_p_fwd.php");
var gDDNS;
var gDDNS_USER;
var gDDNS_PASS;
var gDDNS_DOMAIN,gDDNS_IP;
var gDDNS_STATUS;
var gDDNS_SERVICE;


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
	
	if(mode == 'id_ddns'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";	
        	popup_button_header = "<tr><td align=\"center\">"
		popup_contents =  "<?php echo lang_get('ddns_in_progress')?>";
		popup_button = 'off';
	}	

	if(mode == 'ddns_on'){
		popup_contents = "<?php echo lang_get('ddns_msg_3')?>";
		popup_button_link = "Get_DDNS_Info();showTable('idTable_DDNS');";
	}

	if(mode == 'ddns_off'){
		popup_contents = "<?php echo lang_get('ddns_msg_4')?>";
		popup_button_link = "Get_DDNS_Info();showTable('idTable_DDNS');";
	}

	if(mode == 'id_fail'){
		popup_contents = "<?php echo lang_get('ddns_msg_5')?>";
		popup_button_link = "Get_DDNS_Info();showTable('idTable_DDNS');";
	}

	if(mode == 'ddns_fail'){
		popup_contents = "<?php echo lang_get('ddns_msg_6')?>";
		popup_button_link = "Get_DDNS_Info();showTable('idTable_DDNS');";
	}	
	if(mode == 'id_error'){
		popup_contents = "<?php echo lang_get('ddns_msg_7')?>";
		popup_button_link = "showTable('idTable_DDNS');";
	}

	if(mode == 'pass_error'){
		popup_contents = "<?php echo lang_get('ddns_msg_9')?>";
		popup_button_link = "showTable('idTable_DDNS');";
	}

	if(mode == 'network_fail'){
		popup_contents = "<?php echo lang_get('ddns_msg_8')?>";
		popup_button_link = "Get_DDNS_Info();showTable('idTable_DDNS');";
	}
	if(mode == 'upnp_p_fwd'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";		
        	popup_button_header = "<tr><td align=\"center\">"
		popup_contents = "<?php echo lang_get('upnp_p_fwd_msg_1')?>";
		popup_button = 'off';
	}
	if(mode == 'upnp_on'){
		popup_contents = "<?php echo lang_get('upnp_p_fwd_msg_2')?>";
		popup_button_link ="showTable('idTable_DDNS');";
	}
	if(mode == 'upnp_off'){
		popup_contents = "<?php echo lang_get('upnp_p_fwd_msg_3')?>";
		popup_button_link ="showTable('idTable_DDNS');";
	}
	

	
	//if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
	//	else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;


	showTable('idTable_POPUP');
	document.getElementById('system_message').innerHTML = popup;


}


function showTable(id)
{
	
	if(id=='idTable_POPUP'){
	document.getElementById('idTable_DDNS').style.display = "none";
	document.getElementById('idTable_UPnP_P_FWD').style.display = "none";
	document.getElementById('idTable_POPUP').style.display = "block";
	}

	if(id=='idTable_DDNS'){
	document.getElementById('idTable_DDNS').style.display = "block";
	document.getElementById('idTable_UPnP_P_FWD').style.display = "block";
	document.getElementById('idTable_POPUP').style.display = "none";
	}


	
}


function onUpdateUser()
{
	if(document.getElementById('service').options[0].selected == true)
	{
		var txt = document.getElementById('txtDDNS_USER').value;
		var str = "";
		
	//	for (var i=0;i<txt.length;i++ )
	//	{
	//			var ch = txt.charAt(i);
	//			str = str+ch.toUpperCase();
	//	}
	
		str = txt;
	
		document.getElementById('txtDDNS_DOMAIN').value=str+".lgnas.com";
	}
 }


function Get_DDNS_Info(){
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj){
	//alert(oj.responseText);
	eval('var r='+oj.responseText+';');
	if(r) return !ddnsSave(r) || ddnsShow();
	else return false;

	function ddnsSave(oj){
		gDDNS=oj.d;
		gDDNS_USER=oj.du;
		gDDNS_PASS=oj.dp;
		gDDNS_DOMAIN=oj.dd;
		gDDNS_IP=oj.di;
		gDDNS_STATUS=oj.ds;
		gDDNS_SERVICE=oj.dv;
		if(gDDNS_STATUS == 'OK') gDDNS_STATUS = "<?php echo lang_get('ddns_msg_10')?> "+gDDNS_IP;
		else if(gDDNS_STATUS == 'NG') gDDNS_STATUS = "<?php echo lang_get('ddns_msg_11')?>";
		else gDDNS_STATUS = "<?php echo lang_get('ddns_msg_4')?>";
		return true;
	}
	function ddnsShow(){
		showTable('idTable_DDNS');
		if(gDDNS == 'off'){
			document.getElementById('service').disabled 	= true;
			document.getElementById('rdoDDNS_disable').checked 	= true;
			document.getElementById('txtDDNS_USER').value 		= "";
			document.getElementById('txtDDNS_USER').disabled        = true;
			document.getElementById('txtDDNS_PASS').value 		= "";
			document.getElementById('txtDDNS_PASS').disabled        = true;
			document.getElementById('txtDDNS_DOMAIN').value 		= "";
			document.getElementById('txtDDNS_DOMAIN').disabled 		= true;
			document.getElementById('id_Status').innerHTML 		= gDDNS_STATUS;
		}else if(gDDNS == 'on'){
			document.getElementById('service').disabled 	= true;
			document.getElementById('rdoDDNS_enable').checked 	= true;
			document.getElementById('txtDDNS_USER').value 		= gDDNS_USER;
			document.getElementById('txtDDNS_USER').disabled        = true;
			document.getElementById('txtDDNS_PASS').value 		= gDDNS_PASS;
			document.getElementById('txtDDNS_PASS').disabled        = true;
			document.getElementById('id_Status').innerHTML 		= gDDNS_STATUS;
			document.getElementById('txtDDNS_DOMAIN').value 		= gDDNS_DOMAIN;
			document.getElementById('txtDDNS_DOMAIN').disabled 		= true;
		}
		if (gDDNS_SERVICE == 'lgnas'){
			document.getElementById('service').options[0].selected	= true;
			document.getElementById('service').options[1].selected	= false;
		}else{
			document.getElementById('service').options[0].selected	= false;
			document.getElementById('service').options[1].selected	= true;
		}
		return true;
	}

	/*
	var res = decodeURIComponent(oj.responseText);
	var _item = res.split(':');
	gDDNS		 		= _item[0];
	gDDNS_USER	 		= _item[1];
	gDDNS_PASS	 		= _item[2];
	gDDNS_DOMAIN	 		= _item[3];
	gDDNS_IP	 		= _item[4];
	gDDNS_STATUS	 		= _item[5];
	gDDNS_SERVICE			= _item[6];
	if(gDDNS_STATUS == 'OK') gDDNS_STATUS = "<?php echo lang_get('ddns_msg_10')?> "+gDDNS_IP;
		else if(gDDNS_STATUS == 'NG') gDDNS_STATUS = "<?php echo lang_get('ddns_msg_11')?>";
		   else gDDNS_STATUS = "<?php echo lang_get('ddns_msg_4')?>";
	ShowDDNSInfo(gDDNS,gDDNS_USER,gDDNS_PASS,gDDNS_DOMAIN,gDDNS_IP,gDDNS_STATUS,gDDNS_SERVICE);
	*/
}
//========================================================//
// Show server time
//========================================================//
function ShowDDNSInfo(gDDNS,gDDNS_USER,gDDNS_PASS,gDDNS_DOMAIN,gDDNS_IP,gDDNS_STATUS,gDDNS_SERVICE)
{
	//debug(gDDNS_STATUS);
	showTable('idTable_DDNS');
	if (gDDNS == 'off') 	{
				document.getElementById('service').disabled 	= true;
				
				document.getElementById('rdoDDNS_disable').checked 	= true;
				document.getElementById('txtDDNS_USER').value 		= "";
				document.getElementById('txtDDNS_USER').disabled        = true;
				document.getElementById('txtDDNS_PASS').value 		= "";
				document.getElementById('txtDDNS_PASS').disabled        = true;
				
				document.getElementById('txtDDNS_DOMAIN').value 		= "";
				document.getElementById('txtDDNS_DOMAIN').disabled 		= true;
				document.getElementById('id_Status').innerHTML 		= gDDNS_STATUS;
	}
	else if(gDDNS == 'on')		{
				document.getElementById('service').disabled 	= true;
		
				document.getElementById('rdoDDNS_enable').checked 	= true;
				document.getElementById('txtDDNS_USER').value 		= gDDNS_USER;
				document.getElementById('txtDDNS_USER').disabled        = true;
				document.getElementById('txtDDNS_PASS').value 		= gDDNS_PASS;
				document.getElementById('txtDDNS_PASS').disabled        = true;
				document.getElementById('id_Status').innerHTML 		= gDDNS_STATUS;
				
				
				document.getElementById('txtDDNS_DOMAIN').value 		= gDDNS_DOMAIN;
				
				
				document.getElementById('txtDDNS_DOMAIN').disabled 		= true;
				
				
	}
	
	if (gDDNS_SERVICE == 'lgnas')
	{
		document.getElementById('service').options[0].selected	= true;
		document.getElementById('service').options[1].selected	= false;
	}
	else
	{
		document.getElementById('service').options[0].selected	= false;
		document.getElementById('service').options[1].selected	= true;
	}
}

//========================================================//
// Set time to server
//========================================================//
function Set_DDNS()
{
	document.getElementById('idMsgTitle').innerHTML="<?=lang_get('ddns_1')?>";
	var SERVICE=document.getElementById('service').value,
		DDNS=document.getElementById('rdoDDNS_disable').checked?'off':'on',
		DDNS_USER=document.getElementById('txtDDNS_USER').value,
		DDNS_PASS=document.getElementById('txtDDNS_PASS').value,
		DDNS_DOMAIN=document.getElementById('txtDDNS_DOMAIN').value,
		ddnsEQ=[];
	if(DDNS=='on'){
		if(!chkForm(DDNS_USER,'ddnsid')) ddnsEQ.push("<?=lang_get('ddns_5')?>");
		if(!chkForm(DDNS_PASS,'pw')) ddnsEQ.push("<?=lang_get('ddns_3')?>");
		if(!chkForm(DDNS_DOMAIN,'ddnsdomain')) ddnsEQ.push("<?=lang_get('ddns_6')?>");
		if(ddnsEQ.length) return alert("<?=lang_get('time_msg_2')?>\n* "+ddnsEQ.join(', '));
	}else DDNS_USER='',DDNS_PASS='',DDNS_DOMAIN='';
	display_POPUP('id_ddns');
	return sendRequest(onLoadST,
		'&rdoDynDNS='+ DDNS
		+'&txtDynDNSUser='+DDNS_USER
		+'&txtDynDNSPass='+DDNS_PASS
		+'&txtDomain='+DDNS_DOMAIN
		+'&txtService='+SERVICE,
		'post',
		'../php/service_set_ddns.php',
		true,
		true
	);
	function onLoadST(oj){
		var r=decodeURIComponent(oj.responseText).match(/(\w+)/g);
		return r[0]=='ok'? display_POPUP(r[1]):'';
	}
	//////////
	//////////
	document.getElementById('idMsgTitle').innerHTML="<?php echo lang_get('ddns_1')?>";
	if(document.getElementById('rdoDDNS_enable').checked	== false ){
		display_POPUP('id_ddns');
		Set_DDNS_Info();
	}else {
		if(chkForm(document.getElementById('txtDDNS_USER').value,'ddnsid')){
			//if(valid_pass(document.getElementById('txtDDNS_PASS'))){
			if(chkForm(document.getElementById('txtDDNS_PASS').value,'pw')){
				display_POPUP('id_ddns');
				Set_DDNS_Info();
			}else display_POPUP('pass_error');
		}else display_POPUP('id_error');
	}
}

function Set_DDNS_Info()
{
	var SERVICE=document.getElementById('service').value,
		DDNS=document.getElementById('rdoDDNS_disable').checked?'off':'on',
		DDNS_USER=document.getElementById('txtDDNS_USER').value,
		DDNS_PASS=document.getElementById('txtDDNS_PASS').value,
		DDNS_DOMAIN=document.getElementById('txtDDNS_DOMAIN').value,
		ddnsEQ=[];
	if(DDNS=='on'){
		if(!chkForm(DDNS_USER)) ddnsEQ.push("<?=lang_get('ddns_5')?>");
		if(!chkForm(DDNS_PASS)) ddnsEQ.push("<?=lang_get('ddns_3')?>");
		if(!chkForm(DDNS_DOMAIN)) ddnsEQ.push("<?=lang_get('ddns_6')?>");
		if(ddnsEQ.length) return alert("<?=lang_get('time_msg_2')?>\n* "+ddnsEQ.join(', '));
	}
	sendRequest(onLoadST,
		'&rdoDynDNS='+ DDNS
			+'&txtDynDNSUser='+DDNS_USER
			+'&txtDynDNSPass='+DDNS_PASS
			+'&txtDomain='+DDNS_DOMAIN
		+'&txtService='+SERVICE,
		'post',
		'../php/service_set_ddns.php',
		true,
		true
	);
	function onLoadST(oj){
		var r=decodeURIComponent(oj.responseText).match(/(?:(\w+):)+/g);
		alert(r);
		return r[1]=='ok'? display_POPUP(r[2]):'';
		//if(r[1]=='ok') display_POPUP(r[2]);
		
	
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') 
		display_POPUP(code[1]);
}
}



function containsCharsOnly(input,chars) {

    	var non_start_char = "-_0123456789";
    	if(!(non_start_char.indexOf(input.value.charAt(0)) == -1)) return false;

    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           	return false;
    	}
    	return true;
}

function valid_name(input) {
	var name = input.value.split('.');
	var domainName = name[0];
	
	return chkForm(domainName,"ddnsid");
//    var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-.";
//    if(domainName<3) return false;	
//	if(domainName>12) return false;	
//    return true;
//    return containsCharsOnly(domainName,chars);
    	
    	
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-";
    	if(input.value.length<3) return false;	
	if(input.value.length>12) return false;	
    	return containsCharsOnly(input,chars);
}
function valid_pass(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    	if(input.value.length<3) return false;	
	if(input.value.length>20) return false;	
    	return containsCharsOnly(input,chars);
}


//========================================================//
// Enable/Disable UPnP port forwarding
//========================================================//
function Set_UPnP_P_FWD()
{
	document.getElementById('idMsgTitle').innerHTML="<?php echo lang_get('upnp_p_fwd_name')?>";
	//if(document.getElementById('rdoUPnP_P_FWD_enable').checked == false ){
		display_POPUP('upnp_p_fwd');
		Set_UPnP_P_FWD_Info();
	//}else {

	//}
}

function Set_UPnP_P_FWD_Info()
{
	var 	UPnP_P_FWD, UPnP_PORT;
		
	if (document.getElementById('rdoUPnP_P_FWD_disable').checked) UPnP_P_FWD='off'
	 	else UPnP_P_FWD='on'
	
	//UPnP_PORT = document.getElementById('txtUPnP_PORT').value;
	
	
	var _txText =	'&rdoUPnP_P_FWD='+ UPnP_P_FWD;
			//+'&txtUPnP_Port='+UPnP_PORT
		
 	//alert(_txText);
	sendRequest(onLoadST_UPnP,_txText,'post',gPhp[2],true,true);
	return true;
	
}


function onLoadST_UPnP(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);
	

}

function Get_UPnP_P_FWD_Info()
{
	
	sendRequest(onLoadDT_UPnP,'','post',gPhp[3],true,true);
	return true;

}

function onLoadDT_UPnP(oj)
{
	var res = decodeURIComponent(oj.responseText);
	var _item = res.split('*');
	var PortForwarding = _item[1];
	if(_item[2]){
		var PortForwarding_progress = _item[2].split(":");
	}
	if(_item[0] =='No IGD'){
		document.getElementById('id_IGD').innerHTML="<?php echo lang_get('upnp_p_fwd_no_IGD')?>";
		if(PortForwarding == 'on'){
			document.getElementById('rdoUPnP_P_FWD_enable').checked 	= true;
			document.getElementById('rdoUPnP_P_FWD_disable').checked	= false;	
		}
		else{
			document.getElementById('rdoUPnP_P_FWD_disable').checked	= true;
			document.getElementById('rdoUPnP_P_FWD_enable').checked 	= false;
		}	
	}

	else{
		var IGD = _item[0].split(":");
		document.getElementById('id_IGD').innerHTML=IGD[0]+':'+IGD[1]; //_item[0];

		if(PortForwarding == 'off'){
			document.getElementById('rdoUPnP_P_FWD_disable').checked	= true;
			document.getElementById('rdoUPnP_P_FWD_enable').checked 	= false;
		}
		else if(PortForwarding == 'on'){
			document.getElementById('rdoUPnP_P_FWD_enable').checked 	= true;
			document.getElementById('rdoUPnP_P_FWD_disable').checked	= false;
				
			if( PortForwarding_progress[0] == 'off' ){
				var _txText =	'&rdoUPnP_P_FWD=on';
				sendRequest(onLoadUPnP,_txText,'post',gPhp[2],true,true);
			}
		}
	}
}

function onLoadUPnP()
{
}






//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/service/help_ddns.html','Help_ddns','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}

function clearDomain()
{
	document.getElementById('txtDDNS_DOMAIN').value = "";
	
	if(document.getElementById('service').options[0].selected == true)
	{
		document.getElementById('txtDDNS_DOMAIN').disabled = true;
		document.getElementById('txtDDNS_DOMAIN').value = document.getElementById('txtDDNS_USER').value + '.lgnas.com';
	}
	else
	{
		document.getElementById('txtDDNS_DOMAIN').disabled = false;
		document.getElementById('txtDDNS_DOMAIN').value = "";
	}
}
