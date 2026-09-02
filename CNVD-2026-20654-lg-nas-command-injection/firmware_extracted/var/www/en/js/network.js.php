<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//=======================================================//
// Init : page information
//=======================================================//
var page = {
	"name" : "network",
	"init" : function(){
		// To do
		Get_Network_Info();
		FormDHCP();
	}
}
//=======================================================//
// Added
//=======================================================//
function changeWG()
{
	var txt = document.getElementById('txtWorkgroup').value;
	var str = "";
	for (var i=0;i<txt.length;i++ )
	{
			var ch = txt.charAt(i);
			str = str+ch.toUpperCase();
	}
	document.getElementById('txtWorkgroup').value=str;
 }

function changeDM()
{
	var txt = document.getElementById('txtDomain').value;
	var str = "";
	for (var i=0;i<txt.length;i++ )
	{
			var ch = txt.charAt(i);
			str = str+ch.toUpperCase();
	}
	document.getElementById('txtDomain').value=str;
 }


function CheckRange(id){
	if(document.getElementById(id).value<0||document.getElementById(id).value>255){
		//alert("<?php echo lang_get('network_msg_25')?>");
		//document.getElementById(id).value='0';
		document.getElementById(id).value='';
		return false;
	}else if((!(IP_FormCheck(id)))&&(document.getElementById(id).value!='')){
		alert("<?php echo lang_get('network_msg_25')?>");
		document.getElementById(id).value='';
	}else return true;
}
//========================================================//
// System / EMail menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/system_get_network.php","../php/system_set_network.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_HOST_EDIT','idTable_INTERFACE_EDIT','idTable_DOMAIN_EDIT','idTable_POPUP');

//========================================================//
// Data type
//========================================================//
function NetworkInfo(HOSTNAME,HOSTDESC,IP_TYPE,IPADDR,NETMASK,GATEWAY,DNS1,DNS2,MTU,DOMAIN_TYPE,WORKGROUP,DOMAIN,DOMAINUSER,DOMAINPASS)
{
	this.HOSTNAME 		= HOSTNAME;
	this.HOSTDESC 		= HOSTDESC;
	this.IP_TYPE  		= IP_TYPE;
	this.IPADDR 		= IPADDR;
	this.NETMASK 		= NETMASK;
	this.GATEWAY 		= GATEWAY;
	this.DNS1 		= DNS1;
	this.DNS2 		= DNS2;
	this.DOMAIN_TYPE 	= DOMAIN_TYPE;
	this.WORKGROUP 		= WORKGROUP;
	this.DOMAIN 		= DOMAIN;
	this.DOMAINUSER 	= DOMAINUSER;
	this.DOMAINPASS 	= DOMAINPASS;
}

//========================================================//
// Page status
//========================================================//
//var gStat = new Array('time_basic','time_edit','ntp_basic','ntp_edit');
//var fStat = gStat[0];

//========================================================//
// Information variable
//========================================================//
var gNetworkInfo = new NetworkInfo('0','0','0','0','0','0','0','0','0','0','0','0','0','0');

//========================================================//
// Show table area
//========================================================//
var gprevious_mode = new String();
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
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">"
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();
	

/////////HOST POPUP
	if(mode == 'samehost'){

		popup_contents = "<?php echo lang_get('network_msg_2')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";
	}
        
	if(mode == 'host'){

		popup_contents = "<?php echo lang_get('network_msg_4')?>"
		popup_button_link = "Get_Network_Info();showTable('idTable_HOST_EDIT');";
	}

	if(mode == 'host_err'){

		popup_contents = "<?php echo lang_get('host_name_rule')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";
	}

	if(mode == 'desc_err'){

		popup_contents = "<?php echo lang_get('network_msg_6')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";
	}

	if(mode == 'apply_host'){

		popup_contents = "<?php echo lang_get('network_msg_3')?>"
		popup_button = 'off';
	}


////////END OF HOST POPUP

////////Interface POPUP
	if(mode == 'same_interface'){

		popup_contents = "<?php echo lang_get('network_msg_2')?>"
		popup_button_link = "showTable('idTable_INTERFACE_EDIT');";
	}

	if(mode == 'ip_err'){

		popup_contents = "<?php echo lang_get('network_msg_9')?>"
		popup_button_link = "showTable('idTable_INTERFACE_EDIT');";
	}
	if(mode == 'mask_err'){

		popup_contents = "<?php echo lang_get('network_msg_10')?>"
		popup_button_link = "showTable('idTable_INTERFACE_EDIT');";
	}	
	if(mode == 'gateway_err'){

		popup_contents = "<?php echo lang_get('network_msg_11')?>"
		popup_button_link = "showTable('idTable_INTERFACE_EDIT');";
	}	
	if(mode == 'dns_err'){

		popup_contents = "<?php echo lang_get('network_msg_12')?>"
		popup_button_link = "showTable('idTable_INTERFACE_EDIT');";
	}
	if(mode == 'validity_err'){

		popup_contents = "<?php echo lang_get('network_msg_13')?>"
		popup_button_link = "showTable('idTable_INTERFACE_EDIT');";
	}
	if(mode == 'apply_interface'){

		popup_contents = "<?php echo lang_get('network_msg_7')?>"
		popup_button = 'off';
	}

	if(mode == 'interface'){

		popup_contents = "<?php echo lang_get('network_msg_8')?>"
		popup_button_link = "Get_Network_Info();showTable('idTable_INTERFACE_EDIT');";
	}

	if(mode == 'ip_chg_auto'){

		popup_contents = "<?php echo lang_get('network_msg_14')?>"
		popup_button = 'off';
	}

	if(mode == 'ip_chg_manual' && gprevious_mode != 'ipconflict'){
		
		var IPADDR 	= document.getElementById('txtIPAddr1').value+'.'+document.getElementById('txtIPAddr2').value+'.'
                     		     +document.getElementById('txtIPAddr3').value+'.'+document.getElementById('txtIPAddr4').value;
		popup_contents = "<?php echo lang_get('network_msg_15')?><BR /><a href=\"http://"+IPADDR+"/\">http://"+IPADDR+"/</a>"
		popup_button = 'off';
	}

	if(mode == 'manual'){

		popup_contents = "<?php echo lang_get('network_msg_8')?>"
		popup_button_link = "Get_Network_Info();showTable('idTable_INTERFACE_EDIT');";
	}
	
	if(mode == 'ipconflict'){

		popup_contents = "<?php echo lang_get('network_msg_16')?>"
		popup_button_link = "Get_Network_Info();showTable('idTable_INTERFACE_EDIT');";
	}
/*
	if(mode == 'manual_IP'){
		var IPADDR 	= document.getElementById('txtIPAddr1').value+'.'+document.getElementById('txtIPAddr2').value+'.'
                     		     +document.getElementById('txtIPAddr3').value+'.'+document.getElementById('txtIPAddr4').value;

		popup_contents = "Interface setting is completed<br>Plese follow the link for further configuration<br><a href=\"http://"+IPADDR+"\">http://"+IPADDR+"</a>"
		popup_button = 'off';
	}


*/
////////////////// Domain Setting 

	if(mode == 'same_domain'){

		popup_contents = "<?php echo lang_get('network_msg_2')?>"
		popup_button_link = "showTable('idTable_DOMAIN_EDIT');";
	}

	

	if(mode == 'apply_workgroup'){

		popup_contents = "<?php echo lang_get('network_msg_17')?>"
		popup_button = 'off';
	}

	if(mode == 'apply_domain'){

		popup_contents = "<?php echo lang_get('network_msg_18')?>"
		popup_button = 'off';
	}

	if(mode == 'workgroup_err'){

		popup_contents = "<?php echo lang_get('network_msg_20')?>"
		popup_button_link = "showTable('idTable_DOMAIN_EDIT');";
	}

	if(mode == 'domain_err'){

		popup_contents = "<?php echo lang_get('network_msg_21')?>"
		popup_button_link = "showTable('idTable_DOMAIN_EDIT');";
	}

	if(mode == 'domain'){

		popup_contents = "<?php echo lang_get('network_msg_19')?>"
		popup_button_link = "Get_Network_Info();showTable('idTable_DOMAIN_EDIT');";
	}

	if(mode == 'ns_err'){

		popup_contents = "<?php echo lang_get('network_msg_22')?>"
		popup_button_link = "showTable('idTable_DOMAIN_EDIT');";
	}
	
	if(mode == 'join_err'){

		popup_contents = "<?php echo lang_get('network_msg_23')?>"
		popup_button_link = "showTable('idTable_DOMAIN_EDIT');";
	}
	




////////////////// Domain Setting 





	
	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;
	
	
	if(popup_contents !=''){
	
	showTable('idTable_POPUP');
	
	document.getElementById('system_message').innerHTML = popup;
	
	}
	gprevious_mode = mode;

}


function timedRefresh() {
	setTimeout("location.reload(true);",2000);
}

function changeTable(id)
{

	
	if ( id == 'idTable_HOST_EDIT'){
		
	}
	if ( id == 'idTable_INTERFACE_EDIT'){
	}
	if ( id == 'idTable_DOMAIN_EDIT'){
	
	}
	if ( id == 'idTable_INTERFACE_POPUP'){
	
	}
	if ( id == 'idTable_POPUP'){
	
	}



}

function showTable(id)
{
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";
	
	if ( id != null){
		document.getElementById(id).style.display = "block";
	}
	
}
//========================================================//
// Get server time
//========================================================//
function Get_Network_Info()
{
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
	var _item = res.split(':');
	
	gNetworkInfo.HOSTNAME 		= _item[0];
	gNetworkInfo.HOSTDESC 		= _item[1];
	gNetworkInfo.IP_TYPE 		= _item[2];
	gNetworkInfo.IPADDR 		= _item[3];
	gNetworkInfo.NETMASK		= _item[4];
	gNetworkInfo.GATEWAY		= _item[5];
	gNetworkInfo.DNS1		= _item[6];
	gNetworkInfo.DNS2		= _item[7];
	gNetworkInfo.MTU		= _item[8];
	gNetworkInfo.DOMAIN_TYPE	= _item[9];
	gNetworkInfo.WORKGROUP		= _item[10];
	gNetworkInfo.DOMAIN		= _item[11];
	gNetworkInfo.DOMAINUSER		= _item[12];
	gNetworkInfo.DOMAINPASS		= _item[13];

	ShowNetworkEdit();
}


function ShowNetworkEdit()
{
	//debug(gEmailInfo.email);

	if (gNetworkInfo.DOMAIN_TYPE == 'workgroup')	{
		document.getElementById('rdoDOMAIN_TYPE_W').checked 	= true;
		document.getElementById('txtWorkgroup').value 		= gNetworkInfo.WORKGROUP;
		document.getElementById('txtDomain').value = '';
		document.getElementById('txtDomainAdmin').value ='';
		document.getElementById('txtDomainAdminPass').value ='';
	}
		else{ 					
		document.getElementById('rdoDOMAIN_TYPE_D').checked 	= true;
		document.getElementById('txtWorkgroup').value 		= '';
		document.getElementById('txtDomain').value 		= gNetworkInfo.DOMAIN;
		document.getElementById('txtDomainAdmin').value 	= gNetworkInfo.DOMAINUSER;
		document.getElementById('txtDomainAdminPass').value 	= gNetworkInfo.DOMAINPASS;

	}
	
	if (gNetworkInfo.IP_TYPE == 'static')		document.getElementById('rdoDHCP_disable').checked 	= true;
		else 					document.getElementById('rdoDHCP_enable').checked 	= true;


	document.getElementById('txtHOSTNAME').value 	= gNetworkInfo.HOSTNAME;	
	document.getElementById('txtHOSTDESC').value 	= gNetworkInfo.HOSTDESC;	

	var _IPADDR  = gNetworkInfo.IPADDR.split('.');
	var _NETMASK = gNetworkInfo.NETMASK.split('.');
	var _GATEWAY = gNetworkInfo.GATEWAY.split('.');
	var _DNS1    = gNetworkInfo.DNS1.split('.');
	var _DNS2    = gNetworkInfo.DNS2.split('.');

	document.getElementById('txtIPAddr1').value 		= _IPADDR[0];
	document.getElementById('txtIPAddr2').value 		= _IPADDR[1];
	document.getElementById('txtIPAddr3').value 		= _IPADDR[2];
	document.getElementById('txtIPAddr4').value 		= _IPADDR[3];

	document.getElementById('txtSubnet1').value 		= _NETMASK[0];
	document.getElementById('txtSubnet2').value 		= _NETMASK[1];
	document.getElementById('txtSubnet3').value 		= _NETMASK[2];
	document.getElementById('txtSubnet4').value 		= _NETMASK[3];

	document.getElementById('txtGatewayAddr1').value 	= _GATEWAY[0];
	document.getElementById('txtGatewayAddr2').value 	= _GATEWAY[1];
	document.getElementById('txtGatewayAddr3').value 	= _GATEWAY[2];
	document.getElementById('txtGatewayAddr4').value 	= _GATEWAY[3];

	if(_DNS1[0]!='NULL'){
		document.getElementById('txtDNSAddr1_1').value 		= _DNS1[0];
		document.getElementById('txtDNSAddr1_2').value 		= _DNS1[1];
		document.getElementById('txtDNSAddr1_3').value 		= _DNS1[2];
		document.getElementById('txtDNSAddr1_4').value 		= _DNS1[3];
	}else {
		document.getElementById('txtDNSAddr1_1').value 		= '';
		document.getElementById('txtDNSAddr1_2').value 		= '';
		document.getElementById('txtDNSAddr1_3').value 		= '';
		document.getElementById('txtDNSAddr1_4').value 		= '';

	}

	if(_DNS2[0]!='NULL'){
		document.getElementById('txtDNSAddr2_1').value 		= _DNS2[0];
		document.getElementById('txtDNSAddr2_2').value 		= _DNS2[1];
		document.getElementById('txtDNSAddr2_3').value 		= _DNS2[2];
		document.getElementById('txtDNSAddr2_4').value 		= _DNS2[3];
	}else {
		document.getElementById('txtDNSAddr2_1').value 		= '';
		document.getElementById('txtDNSAddr2_2').value 		= '';
		document.getElementById('txtDNSAddr2_3').value 		= '';
		document.getElementById('txtDNSAddr2_4').value 		= '';

	}
	//debug(gNetworkInfo.MTU);
	if (gNetworkInfo.MTU == '1500') 	document.getElementById('Ethernet_Frame').options[0].selected 	= true;
		else if (gNetworkInfo.MTU == '4084') 	document.getElementById('Ethernet_Frame').options[1].selected 	= true;				
			else if (gNetworkInfo.MTU == '7404') 	document.getElementById('Ethernet_Frame').options[2].selected 	= true;
				else if (gNetworkInfo.MTU == '9676') 	document.getElementById('Ethernet_Frame').options[3].selected 	= true;

	FormDHCP();
	FormDOMAIN();
	
}


function display_mtu(){
	if (gNetworkInfo.MTU == '1500') 	document.getElementById('Ethernet_Frame').options[0].selected 	= true;
		else if (gNetworkInfo.MTU == '4084') 	document.getElementById('Ethernet_Frame').options[1].selected 	= true;				
			else if (gNetworkInfo.MTU == '7404') 	document.getElementById('Ethernet_Frame').options[2].selected 	= true;
				else if (gNetworkInfo.MTU == '9676') 	document.getElementById('Ethernet_Frame').options[3].selected 	= true;

}



function Set_Host_Info()
{

	var 	HOSTNAME,HOSTDESC;
	HOSTNAME 	= document.getElementById('txtHOSTNAME').value;
	HOSTDESC 	= document.getElementById('txtHOSTDESC').value;
	
	if((gNetworkInfo.HOSTNAME != HOSTNAME )||(gNetworkInfo.HOSTDESC != HOSTDESC)){
		if(HostCheck()){
			if(DescCheck()){
				display_POPUP('apply_host');
				
				Set_Network_Info('host');
			}else {
				display_POPUP('desc_err');
			}
		} else display_POPUP('host_err');
	}else {
	display_POPUP('samehost');
	}

}


function Set_Domain_Info()
{

	var 	DOMAIN_TYPE,WORKGROUP,DOMAIN,DOMAINUSER,DOMAINPASS;
	
	if (document.getElementsByName('rdoDOMAIN_TYPE')[0].checked) {
			DOMAIN_TYPE = 'workgroup';
	}
	else DOMAIN_TYPE = 'domain';
	
	WORKGROUP   	=document.getElementById('txtWorkgroup').value;
	DOMAIN		=document.getElementById('txtDomain').value;
	DOMAINUSER	=document.getElementById('txtDomainAdmin').value;
	DOMAINPASS	=document.getElementById('txtDomainAdminPass').value;
	
	//alert("|"+gNetworkInfo.DOMAINPASS+":");
	//alert(gNetworkInfo.DOMAIN+":"+DOMAIN+"|"+gNetworkInfo.DOMAINUSER+":"+DOMAINUSER+"|"+gNetworkInfo.DOMAINPASS+":"+DOMAINPASS);
		
	if(DOMAIN_TYPE == 'workgroup'){
		if(DomainCheck()){
				if(gNetworkInfo.WORKGROUP == WORKGROUP && gNetworkInfo.DOMAIN_TYPE == DOMAIN_TYPE){
					display_POPUP('same_domain');
				}else{
				
					display_POPUP('apply_workgroup');
					Set_Network_Info('domain');
				}
		} else display_POPUP('workgroup_err');		
	} else if(DOMAIN_TYPE == 'domain') {
		if(DomainCheck()){
				if(gNetworkInfo.DOMAIN == DOMAIN && gNetworkInfo.DOMAINUSER == DOMAINUSER && gNetworkInfo.DOMAINPASS == DOMAINPASS && gNetworkInfo.DOMAIN_TYPE == DOMAIN_TYPE){
					
					display_POPUP('same_domain');
				}else{
				
					display_POPUP('apply_domain');
					Set_Network_Info('domain');
				}	
		}else {
			
			display_POPUP('domain_err');		
		}
		
	}
	
}




function Set_Interface_Info()
{
	var len,str,item,res, index ;
	var 	IP_TYPE,IPADDR,NETMASK,GATEWAY,WORKGROUP,DNS1,DNS2,MTU;
		
	if (document.getElementsByName('rdoDHCP')[0].checked) IP_TYPE='static'
		 	else IP_TYPE='dhcp'

	//Juny:  If oct, convert dec
	var convAddr = new Array();
	var Properties = new Array();//new Array('IPADDR','NETMASK','GATEWAY','DNS1','DNS2');
	var addrItems = new Array('txtIPAddr','txtSubnet','txtGatewayAddr','txtDNSAddr1_','txtDNSAddr2_');
	for(var iny = 0; iny < 5; iny++)
	{
		item = addrItems[iny];
		for(var inx = 0; inx < 4; inx++) 
		{
			index = inx+1;
			target = item + index;
			res = document.getElementById(target).value;
			str = new String(res);
			len = str.length;

			if(len > 2)
			{
				if(res.charAt(0) == '0')
				{
					var res = parseFloat(res); 
				}
			}
			document.getElementById(item+index).value = convAddr[index] = res;
		}
		Properties[iny] = convAddr[1] +'.' + convAddr[2] +'.'+ convAddr[3] +'.'+ convAddr[4] ;						
	}

	IPADDR 	= Properties[0];	
	
	NETMASK = Properties[1];
	
	GATEWAY = Properties[2];
	
	DNS1    = Properties[3];
	if(DNS1 == '...')DNS1 = 'NULL';	
	
	DNS2    = Properties[4];
	if(DNS2 == '...')DNS2 = 'NULL';

	if (document.getElementById('Ethernet_Frame').options[0].selected) MTU ='1500';
	if (document.getElementById('Ethernet_Frame').options[1].selected) MTU ='4084';
	if (document.getElementById('Ethernet_Frame').options[2].selected) MTU ='7404';
	if (document.getElementById('Ethernet_Frame').options[3].selected) MTU ='9676';

	//alert(gNetworkInfo.IPADDR+":"+IPADDR+"|"+gNetworkInfo.NETMASK+":"+NETMASK+"|"+gNetworkInfo.GATEWAY+":"+GATEWAY+"|"+gNetworkInfo.DNS1+":"+DNS1+"|"+gNetworkInfo.DNS2+":"+ DNS2+"|"+gNetworkInfo.MTU+":"+MTU);
	if(IP_TYPE == 'static'){
	if((gNetworkInfo.IPADDR != IPADDR )||(gNetworkInfo.NETMASK !=NETMASK)||(gNetworkInfo.GATEWAY != GATEWAY)||(gNetworkInfo.DNS1 != DNS1)||(gNetworkInfo.DNS2 != DNS2)||(gNetworkInfo.MTU != MTU)){ 
		if(IPCheck()){
			if(MASKCheck()){
				if(GATEWAYCheck()){
					if(DNSCheck()){
						if(VailidityCheck()){
							
							if(gNetworkInfo.IPADDR != IPADDR){
								display_POPUP('apply_interface');
								setTimeout('display_POPUP(\'ip_chg_manual\')',10000);
							
						
							}else {
								display_POPUP('apply_interface');
							}
							Set_Network_Info('interface');
							
						}else {
						display_POPUP('validity_err');
						}							
					}else {
					display_POPUP('dns_err');
					}	

				}else {
				display_POPUP('gateway_err');
				}
			}else {
				display_POPUP('mask_err');
			}
		} else display_POPUP('ip_err');
	}else {
	
	if(gNetworkInfo.IP_TYPE != IP_TYPE) {display_POPUP('ip_chg_manual'); Set_Network_Info('interface');}
		else { display_POPUP('same_interface'); }

	}
	}else {
		if(gNetworkInfo.IP_TYPE != IP_TYPE){
			display_POPUP('ip_chg_auto');
			
		}
		else {
			display_POPUP('interface');
			
		}
		Set_Network_Info('interface');
	}

}





function Set_Network_Info(mode)
{
	
		var 	HOSTNAME,HOSTDESC,IP_TYPE,IPADDR,NETMASK,GATEWAY,WORKGROUP,DNS1,DNS2,MTU,DOMAIN_TYPE,WORKGROUP,DOMAIN,DOMAINUSER,DOMAINPASS;
	

		HOSTNAME 	= document.getElementById('txtHOSTNAME').value;
		HOSTDESC 	= document.getElementById('txtHOSTDESC').value;

		if (document.getElementsByName('rdoDHCP')[0].checked) IP_TYPE='static'
		 	else IP_TYPE='dhcp'

		IPADDR 	= document.getElementById('txtIPAddr1').value+'.'+document.getElementById('txtIPAddr2').value+'.'
                          +document.getElementById('txtIPAddr3').value+'.'+document.getElementById('txtIPAddr4').value;
		
		NETMASK = document.getElementById('txtSubnet1').value+'.'+document.getElementById('txtSubnet2').value+'.'
                          +document.getElementById('txtSubnet3').value+'.'+document.getElementById('txtSubnet4').value;

		GATEWAY = document.getElementById('txtGatewayAddr1').value+'.'+document.getElementById('txtGatewayAddr2').value+'.'
                          +document.getElementById('txtGatewayAddr3').value+'.'+document.getElementById('txtGatewayAddr4').value;

		DNS1    = document.getElementById('txtDNSAddr1_1').value+'.'+document.getElementById('txtDNSAddr1_2').value+'.'
                          +document.getElementById('txtDNSAddr1_3').value+'.'+document.getElementById('txtDNSAddr1_4').value;
		
		DNS2    = document.getElementById('txtDNSAddr2_1').value+'.'+document.getElementById('txtDNSAddr2_2').value+'.'
                          +document.getElementById('txtDNSAddr2_3').value+'.'+document.getElementById('txtDNSAddr2_4').value;

		if (document.getElementById('Ethernet_Frame').options[0].selected) MTU ='1500';
		if (document.getElementById('Ethernet_Frame').options[1].selected) MTU ='4084';
		if (document.getElementById('Ethernet_Frame').options[2].selected) MTU ='7404';
		if (document.getElementById('Ethernet_Frame').options[3].selected) MTU ='9676';

		if (document.getElementsByName('rdoDOMAIN_TYPE')[0].checked) {
			DOMAIN_TYPE = 'workgroup';
			WORKGROUP   = document.getElementById('txtWorkgroup').value;
			DOMAIN		='';
			DOMAINUSER	='';
			DOMAINPASS	='';
		}else {
			DOMAIN_TYPE='domain'
			WORKGROUP   	=''; 
			DOMAIN		=document.getElementById('txtDomain').value;
			DOMAINUSER	=document.getElementById('txtDomainAdmin').value;
			DOMAINPASS	=document.getElementById('txtDomainAdminPass').value;
		}
		
		var _txText =	'&txtHostName='+HOSTNAME
				+"&txtHostDesc="+HOSTDESC
				+"&rdoDHCP="+IP_TYPE
				+"&txtIPAddr="+IPADDR
				+"&txtSubnet="+NETMASK
				+"&txtGatewayAddr="+GATEWAY
				+"&txtDNSAddr1="+DNS1
				+"&txtDNSAddr2="+DNS2
				+"&txtMTU="+MTU
				+"&rdoDomainType="+DOMAIN_TYPE
				+"&txtWorkgroup="+WORKGROUP
				+"&txtDomain="+DOMAIN
				+"&txtDomainUser="+DOMAINUSER
				+"&txtDomainPass="+DOMAINPASS
				+"&txtMode="+mode;
		//alert(_txText);
		sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);
		
		
		//if (mode == 'host') showTable('idTable_HOST_EDIT');
		//if (mode == 'interface') showTable('idTable_INTERFACE_EDIT');
		//if (mode == 'domain') showTable('idTable_DOMAIN_EDIT');

		return true;

	
}
function onLoadST(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	code = res.split(':');
	//alert(code);
	if(code[0] == 'ok') {display_POPUP(code[1]);}
	/* Session out message
	else{
		eval('var _res = '+res);
		if(_res.result == '-99'){
			alert("<?php echo lang_get('login_msg_6')?>");
		}
	}*/
}

function IP_FormCheck(form)
{
	if(!(valid_address(document.getElementById(form)))) {
			
			return false;
   } else return true;
}

function IPCheck() {

	if(!((valid_address(document.getElementById('txtIPAddr1')))&&(valid_address(document.getElementById('txtIPAddr2')))&&
	(valid_address(document.getElementById('txtIPAddr3')))&&(valid_address(document.getElementById('txtIPAddr4'))))) {
		//alert('The entered IP Address is not valid\nValid address must be within 0~255 range');
		return false;
	}
	return true;
}

function MASKCheck() {
	if(!((valid_mask(document.getElementById('txtSubnet1')))&&(valid_mask(document.getElementById('txtSubnet2')))&&
	(valid_mask(document.getElementById('txtSubnet3')))&&(valid_mask(document.getElementById('txtSubnet4'))))) {
		//alert('The entered Subnet Mask is not valid\nValid Mask must be one of 0,128,192,224,240,252,255');
		return false;
	}	if((document.getElementById('txtSubnet1').value<document.getElementById('txtSubnet2').value)||(document.getElementById('txtSubnet2').value<document.getElementById('txtSubnet3').value)||
		(document.getElementById('txtSubnet3').value<document.getElementById('txtSubnet4').value)) {
		//alert('Wrong Subnet Mask');
		return false;
	}
	return true;
}
function GATEWAYCheck() {
	if(!((valid_address(document.getElementById('txtGatewayAddr1')))&&(valid_address(document.getElementById('txtGatewayAddr2')))&&
	(valid_address(document.getElementById('txtGatewayAddr3')))&&(valid_address(document.getElementById('txtGatewayAddr4'))))) {
		//alert('The entered Gateway Address is not valid\nValid address must be within 0~255 range');
		return false;
	}

	return true;
}	

function DNSCheck() {	
	if(!((valid_DNS(document.getElementById('txtDNSAddr1_1')))&&(valid_DNS(document.getElementById('txtDNSAddr1_2')))&&
	(valid_DNS(document.getElementById('txtDNSAddr1_3')))&&(valid_DNS(document.getElementById('txtDNSAddr1_4'))))) {
		if(document.getElementById('txtDNSAddr1_1').value=='' && document.getElementById('txtDNSAddr1_2').value=='' && document.getElementById('txtDNSAddr1_3').value=='' && document.getElementById('txtDNSAddr1_4').value=='')
		{
			//no DNS address is OK//
			//alert(1);
		}
		else
		{
			//alert('The entered Primary DNS is not valid\nValid address must be within 0~255 range');
			return false;
		}
	}
	if(!((valid_DNS(document.getElementById('txtDNSAddr2_1')))&&(valid_DNS(document.getElementById('txtDNSAddr2_2')))&&
	(valid_DNS(document.getElementById('txtDNSAddr2_3')))&&(valid_DNS(document.getElementById('txtDNSAddr2_4'))))) {
		if(document.getElementById('txtDNSAddr2_1').value=='' && document.getElementById('txtDNSAddr2_2').value=='' && document.getElementById('txtDNSAddr2_3').value=='' && document.getElementById('txtDNSAddr2_4').value=='')
		{
			//no DNS address is OK//
			//alert(2);
		}
		else
		{
			//alert('The entered Secondary Mask is not valid\nValid address must be within 0~255 range');
			return false;
		}
	}
	return true;
}


function VailidityCheck() {	
	if((document.getElementById('txtIPAddr1').value&document.getElementById('txtSubnet1').value)!=(document.getElementById('txtGatewayAddr1').value&document.getElementById('txtSubnet1').value)) {
		//alert('Default Gateway mismatch');
		return false;
	}
	if((document.getElementById('txtIPAddr2').value&document.getElementById('txtSubnet2').value)!=(document.getElementById('txtGatewayAddr2').value&document.getElementById('txtSubnet2').value)) {
		//alert('Default Gateway mismatch');
		return false;
	}
	if((document.getElementById('txtIPAddr3').value&document.getElementById('txtSubnet3').value)!=(document.getElementById('txtGatewayAddr3').value&document.getElementById('txtSubnet3').value)) {
		//alert('Default Gateway mismatch');
		return false;
	}
	if((document.getElementById('txtIPAddr4').value&document.getElementById('txtSubnet4').value)!=(document.getElementById('txtGatewayAddr4').value&document.getElementById('txtSubnet4').value)) {
		//alert('Default Gateway mismatch');
		return false;
	}
	//document.frmTS.cmdOK.disabled = true;
	return true;
}

function containsCharsOnlyIP(input,chars) {
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           	return false;
    	}
    	return true;
}

function valid_address(input) {
    	var chars = "0123456789";
    	if(input.value.length<1) return false;	
	if(input.value>255) return false;
	return containsCharsOnlyIP(input,chars);
}
function valid_DNS(input)
{
	var chars="0123456789";
	if(input.value.length<1)
	{
		return false;
	}
	if(input.value>255)
	{
		return false;
	}
	return containsCharsOnlyIP(input,chars);
}
function valid_mask(input) {
    	var chars = "0123456789";
    	if(input.value.length<1) return false;	
	if(input.value>255) return false;
	if(containsCharsOnlyIP(input,chars)){
		if((input.value==255)||(input.value==254)||(input.value==252)||(input.value==248)||(input.value==240)||(input.value==224)||(input.value==192)||(input.value==128)||(input.value==0)) return true;
		else return false;
	}else return false;
}		



function HostCheck() {
	if(!(valid_name(document.getElementById('txtHOSTNAME')))) {
		//alert('The entered hostname is not valid\nHostname may include at least 3 and up to 12 alphanumeric character including hypen and underscore');	
		return false;
	}
	return true;
}


function DescCheck() {
	
	if(!(valid_description(document.getElementById('txtHOSTDESC')))) {
		//alert('The entered descriptionn is not valid\nDescription may include at least 3 and up to 12 alphanumeric character including hypen, space, and underscore');	
		return false;
	}
	return true;
}

function DomainCheck() {

	if (document.getElementsByName('rdoDOMAIN_TYPE')[0].checked) {
		if(!(valid_workgroup(document.getElementById('txtWorkgroup')))) {
		//alert('The entered workgroup is not valid\n may include at least 3 and up to 15 alphanumeric character including hypen, dot, and underscore');	
		return false;
		}

	}else {
		if(!(valid_domain(document.getElementById('txtDomain')))) {
		//alert('The entered domain name is not valid\nHostname may include at least 3 and up to 12 alphanumeric character including hypen and underscore');	
		return false;
		}	
	}

	return true;
}



function containsCharsOnly(input,chars) {

    	var non_start_char = "-_";
    	if(!(non_start_char.indexOf(input.value.charAt(0)) == -1)) return false;

    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           	return false;
    	}
    	return true;
}


function containsCharsOnlyHOST(input,chars) {

    	var non_start_char = "-_0123456789";
    	if(!(non_start_char.indexOf(input.value.charAt(0)) == -1)) 
    		return false;
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           	return false;
    	}

	var non_end_char = "-_";
	var len = input.value.length;
	len = len-1;
		
    	if(!(non_end_char.indexOf(input.value.charAt(len)) == -1))
    		return false;
    	return true;
}


function valid_name(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-";
    	if(input.value.length<3) return false;	
	if(input.value.length>12) return false;	
    	return containsCharsOnlyHOST(input,chars);
}

function valid_description(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_ ";
	if(input.value.length<3) return false;
	if(input.value.length>24) return false;	
    	return containsCharsOnly(input,chars);
}

function valid_workgroup(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_.";
	if(input.value.length<3) return false;
	if(input.value.length>15) return false;
    	return containsCharsOnly(input,chars);
}
function valid_domain(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_.";
	if(input.value.length<3) return false;
	if(input.value.length>25) return false;
    	return containsCharsOnly(input,chars);
}

function FormDHCP(){

	if (document.getElementsByName('rdoDHCP')[1].checked == true) {
		for(i=1;i<5;i++)
		{
			document.getElementById('txtIPAddr'+i).disabled = true;
			document.getElementById('txtSubnet'+i).disabled = true;
			document.getElementById('txtGatewayAddr'+i).disabled = true;
			document.getElementById('txtDNSAddr1_'+i).disabled = true;
			document.getElementById('txtDNSAddr2_'+i).disabled = true;
		
					
			document.getElementById('txtIPAddr'+i).style.color="#c4c4c4";
			document.getElementById('txtSubnet'+i).style.color="#c4c4c4";
			document.getElementById('txtGatewayAddr'+i).style.color="#c4c4c4";
			document.getElementById('txtDNSAddr1_'+i).style.color="#c4c4c4";
			document.getElementById('txtDNSAddr2_'+i).style.color="#c4c4c4";
		}
		document.getElementById('Ethernet_Frame').disabled = true;
	}
	else {
		for(i=1;i<5;i++)
		{
			document.getElementById('txtIPAddr'+i).disabled = false;
			document.getElementById('txtSubnet'+i).disabled = false;
			document.getElementById('txtGatewayAddr'+i).disabled = false;
			document.getElementById('txtDNSAddr1_'+i).disabled = false;
			document.getElementById('txtDNSAddr2_'+i).disabled = false;

			document.getElementById('txtIPAddr'+i).style.color="#707070";
			document.getElementById('txtSubnet'+i).style.color="#707070";
			document.getElementById('txtGatewayAddr'+i).style.color="#707070";
			document.getElementById('txtDNSAddr1_'+i).style.color="#707070";
			document.getElementById('txtDNSAddr2_'+i).style.color="#707070";
		}
		document.getElementById('Ethernet_Frame').disabled = false;
	}
	return;
}

function FormDOMAIN() {

	
	if (document.getElementsByName('rdoDOMAIN_TYPE')[0].checked == true) {
		
			document.getElementById('txtWorkgroup').disabled = false;
			//document.getElementById('txtWorkgroup').value 		= gNetworkInfo.WORKGROUP;
			document.getElementById('txtDomain').value 		= '';
			document.getElementById('txtDomainAdmin').value 	= '';
			document.getElementById('txtDomainAdminPass').value 	= '';
			document.getElementById('txtDomain').disabled = true;
			document.getElementById('txtDomainAdmin').disabled = true;
			document.getElementById('txtDomainAdminPass').disabled = true;
			document.getElementById('txtDomain').value = '';
			document.getElementById('txtDomainAdmin').value = '';
			document.getElementById('txtDomainAdminPass').value = '';
	}
	else {
			document.getElementById('txtWorkgroup').disabled = true;
			document.getElementById('txtWorkgroup').value = '';
			document.getElementById('txtWorkgroup').value 		= '';
			//document.getElementById('txtDomain').value 		= gNetworkInfo.DOMAIN;
			//document.getElementById('txtDomainAdmin').value 	= gNetworkInfo.DOMAINUSER;
			//document.getElementById('txtDomainAdminPass').value 	= gNetworkInfo.DOMAINPASS;
			document.getElementById('txtDomain').disabled = false;
			document.getElementById('txtDomainAdmin').disabled = false;
			document.getElementById('txtDomainAdminPass').disabled = false;
		}
	return;
}
//=======================================================//
// Show help
//=======================================================//
var help = 1;
var help_value = new Array('1','2','3');
function show_help()
{
	debug(help);
	switch(help)
	{
		case 1:
		var _win = window.open('../help/system/help_network.html','Help_network','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		//_win.moveTo(540,240);
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_network.html','Help_network','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		case 3:
		
		var _win = window.open('../help/system/help_network.html','Help_network','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		default:
		break;
	}
	
	// //
}
function onMtuChange(){

		_msg = "<?php echo lang_get('network_msg_1')?>";
		var ans=confirm(_msg.replace('<BR />','\n')); 
		if(!ans) display_mtu();

}
