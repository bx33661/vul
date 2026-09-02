<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//========================================================//
// Wizard -> Basic step 
//========================================================//

//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/systemw_get_bstep.php","../php/systemw_set_bstep.php");

var tPhp = new Array('../php/time_get_timezonelist.php');

//========================================================//
// Input ID
//========================================================//
var gIdInput=new Array('idTimezone','idYear','idMonth','idDay','idHour','idMinute','idSecond','idSelecttimezone',
'idRadioenableIn','idRadiodisableIn','idServeraddrIn','idFrequencyIn','idChkboxIn','idRadio','idDate','idTime');

//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('systemw_bstep1','systemw_bstep2','systemw_bstep3','systemw_bstep_POPUP');

//========================================================//
// Information variable
//========================================================//
var gNetworkInfo = new NetworkInfo('0','0','0','0','0','0','0','0','0','0','0','0','0','0');
var gDateinfo = new datetimeInfo(2008,9,4,1,59,20,'Asia/Seoul');
var gOtherInfo = new OtherInfo("BIN-rev-316 SRC-REV-639","Disabled","Enabled");

var gTimezonelist = new Array();

//========================================================//
// Message text
//========================================================//
var gMsgtext = new Array('Not available input!\nInput date & time again!',
'Not available input!\nInput again!');
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
function datetimeInfo(year,month,day,hour,minute,second,timezone)
{
	this.year = year;
	this.month = month;
	this.day = day;
	this.hour = hour;
	this.minute = minute;
	this.second = second;
	this.timezone = timezone;
}

function OtherInfo(SYS_VER,FTP,NTP)
{
	this.SYS_VER = SYS_VER;
	this.FTP = FTP;
	this.NTP = NTP;
}

//========================================================//
// Get Information 
//========================================================//
function Get_Bstep_Info(){
	sendRequest(onLoadBS,'','post',gPhp[0],true,true);
	
	return true;
}
function onLoadBS(oj){
	var res = decodeURIComponent(oj.responseText);
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
/*
	gNetworkInfo.DOMAIN_TYPE	= _item[9];
	gNetworkInfo.WORKGROUP		= _item[10];
	gNetworkInfo.DOMAIN		= _item[11];
	gNetworkInfo.DOMAINUSER		= _item[12];
	gNetworkInfo.DOMAINPASS		= _item[13];
*/
	gDateinfo.year = _item[9];
	gDateinfo.month = _item[10];
	gDateinfo.day = _item[11];
	gDateinfo.hour = _item[12];
	gDateinfo.minute = _item[13];
	gDateinfo.second = _item[14];
	gDateinfo.timezone = _item[15];
	
	gOtherInfo.SYS_VER = _item[16];
	gOtherInfo.FTP = _item[17];
	gOtherInfo.NTP = _item[18];
	
	
	ShowStep1();
	ShowStep2();
	getServerTimezoneList();
	ShowStep3('init');
}

// function onLoadBS2() : get only Time Information

function onLoadBS2(oj){
	var res = decodeURIComponent(oj.responseText);
	var _item = res.split(':');
	gDateinfo.year = _item[0];
	gDateinfo.month = _item[1];
	gDateinfo.day = _item[2];
	gDateinfo.hour = _item[3];
	gDateinfo.minute = _item[4];
	gDateinfo.second = _item[5];
	gDateinfo.timezone = _item[6];
	
}
//========================================================//
// Show Information 
//========================================================//
function ShowStep1(){
	//debug(gEmailInfo.email);
	
	// HOST Name & Description
	document.getElementById('txtHOSTNAME').value 	= gNetworkInfo.HOSTNAME;	
	document.getElementById('txtHOSTDESC').value 	= gNetworkInfo.HOSTDESC;	
	
	// Firmware Version
	document.getElementById('id_FirmUpVer').innerHTML = gOtherInfo.SYS_VER;

	// FTP Setting
	if (gOtherInfo.FTP == 'on') {document.getElementById('FTPstatus').innerHTML 	= "<?php echo lang_get('common_enable')?>";}
	else{												 document.getElementById('FTPstatus').innerHTML 	= "<?php echo lang_get('common_disable')?>";}
	
	// NTP Setting
	if(gOtherInfo.NTP == 'on') 	{document.getElementById('NTPstatus').innerHTML = "<?php echo lang_get('common_enable')?>";}
	else{												 document.getElementById('NTPstatus').innerHTML = "<?php echo lang_get('common_disable')?>";}

}

function ShowStep2(){
	// IP & NETMAST & GATEWAY & DNS
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

	if(_DNS1[0]!= 'NULL'){
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
	
	// DHCP Radio Button
	if(gNetworkInfo.IP_TYPE == 'none')		document.getElementById('rdoDHCP_disable').checked 	= true;
	else 																	document.getElementById('rdoDHCP_enable').checked 	= true;
	
	FormDHCP();
	
	//debug(gNetworkInfo.MTU);
	/*
	if (gNetworkInfo.MTU == '1500') 	document.getElementById('Ethernet_Frame').options[0].selected 	= true;
		else if (gNetworkInfo.MTU == '4004') 	document.getElementById('Ethernet_Frame').options[1].selected 	= true;				
			else if (gNetworkInfo.MTU == '7004') 	document.getElementById('Ethernet_Frame').options[2].selected 	= true;
				else if (gNetworkInfo.MTU == '9004') 	document.getElementById('Ethernet_Frame').options[3].selected 	= true;
	*/

	//FormDOMAIN();
	
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
	}
	return;
}


function ShowStep3(mode){
	
	
	document.getElementById(gIdInput[1]).value = gDateinfo.year;
	document.getElementById(gIdInput[2]).value = gDateinfo.month;
	document.getElementById(gIdInput[3]).value = gDateinfo.day;
	document.getElementById(gIdInput[4]).value = gDateinfo.hour;
	document.getElementById(gIdInput[5]).value = gDateinfo.minute;
	document.getElementById(gIdInput[6]).value = gDateinfo.second; 

	document.getElementById('currentTime').innerHTML = gDateinfo.year+"-"+gDateinfo.month+"-"+gDateinfo.day+" "+gDateinfo.hour+":"+gDateinfo.minute+":"+gDateinfo.second;

	
	if(mode == 'init'){
		document.getElementById(gIdInput[0]).innerHTML = makeTimezonetext();
		//refresh_Clock();
	}
}

function makeTimezonetext(){
	var _tzlist = gTimezonelist;
	var _cnt = _tzlist.length;
	
	var _regexp = new RegExp(gDateinfo.timezone);
	
	var _timezonetext = "<select id='idSelecttimezone' name='select' size='1' id='select' class='SELECT'>";
	for(var i=0; i<_cnt; i++)
	{
		if(_tzlist[i].match(_regexp))
		{
			_timezonetext += "<option selected>"+_tzlist[i]+"</option>";
			document.getElementById('timeZone').innerHTML = _tzlist[i];
		}else
		{
			_timezonetext += "<option>"+_tzlist[i]+"</option>";
		}
	}
	_timezonetext += "</select>";
	return _timezonetext;
}
//========================================================//
// refresh Clock
//========================================================//

function refresh_Clock() {

   if(document.getElementById(gIdInput[6]).value != null){
 		  		gDateinfo.year = eval(document.getElementById(gIdInput[1]).value);
   				gDateinfo.month = eval(document.getElementById(gIdInput[2]).value);
   				gDateinfo.day = eval(document.getElementById(gIdInput[3]).value);
   				gDateinfo.hour = eval(document.getElementById(gIdInput[4]).value);	
   				gDateinfo.minute = eval(document.getElementById(gIdInput[5]).value);
   				gDateinfo.second = eval(document.getElementById(gIdInput[6]).value);
   				
   				gDateinfo.second ++;
					if(gDateinfo.second >=60){   // 
		     			gDateinfo.second = 0;
		     			gDateinfo.minute ++;
		     			if(gDateinfo.minute >=60){
		     				gDateinfo.minute = 0;
		     				gDateinfo.hour ++;
		     				if(gDateinfo.hour >=24){
		     					gDateinfo.hour =0;
		     					gDateinfo.day ++;
		     					
		     					var totalday = totaldays(gDateinfo.year,gDateinfo.month);
		     					if(gDateinfo.day > totalday){
		     						gDateinfo.day = 1;
		     						gDateinfo.month ++;
		     						if(gDateinfo.month > 12){
		     							 	gDateinfo.month = 1;
		     							 	gDateinfo.year++;
		     						}
		     					}
		     				}		
		     			}	
		     		}
   		
   	 if(gDateinfo.second < 10) gDateinfo.second = "0" + gDateinfo.second;
     if(gDateinfo.minute < 10) gDateinfo.minute = "0" + gDateinfo.minute;
     if(gDateinfo.hour < 10) gDateinfo.hour = "0" + gDateinfo.hour;
     if(gDateinfo.day < 10) gDateinfo.day = "0" + gDateinfo.day;
     if(gDateinfo.month < 10) gDateinfo.month = "0" + gDateinfo.month;
          
     ShowStep3(); 	
   }
   //setTimeout("refresh_Clock()", 1000); //1초마다 실행
   //return false;
   
}   

function totaldays(month, year){
    var tempday = new Array(31,28,31,30,31,30,31,31,30,31,30,31)
    if(((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0))
        tempday[1] = 29
    else
        tempday[1] = 28

    return tempday[month]
}

//========================================================//
// Client PC time
//========================================================//
function getTime()
{
	if(document.getElementById('useLocalTime').checked == true){ 
	
			var now = new Date();
			var tzo = now.getTimezoneOffset()/60*(-1);
			if (now.getYear() >= 2000)
			{
				gDateinfo.year = now.getYear();
			}
			else {
				gDateinfo.year = now.getYear() + 1900;
			}
			gDateinfo.month = now.getMonth() + 1;
			gDateinfo.day = now.getDate();
			gDateinfo.hour = now.getHours();
			gDateinfo.minute = now.getMinutes();
			gDateinfo.second = now.getSeconds();
			ShowStep3();
			return true;
	}
	else if(document.getElementById('useLocalTime').checked == false){ 

		
			
		var _txText =	"&txtMode=timeOnly"; 
		
		sendRequest(onLoadBS2,_txText,'post',gPhp[0],false,true);	
		ShowStep3();
	
		return true;
	}

}

//========================================================//
// Timezone list
//========================================================//
function getServerTimezoneList()
{
	debug('getServerTimezoneList');

	sendRequest(onLoadTL,'','post',tPhp[0],false,true);
	
	
	return true;
}

function onLoadTL(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
	res = res.substring(1);
	var _tzlist = getItem(res,":");
	gTimezonelist = _tzlist;
	
}
//========================================================//
// Get array from string with delimiter
//========================================================//
function getItem(str,mark)
{
	var _tmp = str;
	var _i = _tmp.indexOf(mark);
	var _ret = new Array();
	var _cnt = 0;
	while(_i>0)
	{
		_ret[_cnt] = _tmp.substring(0,_i);
		_tmp = _tmp.substring(_i+1);
		_i = _tmp.indexOf(mark);
		_cnt++;
	}
	return _ret;
}
//========================================================//



//========================================================//
// Show table area
//========================================================//
function showTable(id){	
	//debug(id);

	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";
	

	if(id!=""){
		document.getElementById(id).style.display = "block";
	}
}


	
var HOSTNAME,HOSTDESC;	
var IP_TYPE,IPADDR,NETMASK,GATEWAY,DNS1,DNS2,MTU;
var _time;

	var needChangeStep1;
	var needChangeStep2;
	var needChangeStep3;
	var needChange;
	var hasChanged;
	
function check_Bstep()
{
	
		
	// Get Host Infomation from HTML
	//Set_Host_Info();
	//Set_Interface_Info();
	
	
		HOSTNAME 	= document.getElementById('txtHOSTNAME').value;
		HOSTDESC 	= document.getElementById('txtHOSTDESC').value;

		if (document.getElementsByName('rdoDHCP')[0].checked) IP_TYPE='none'
		 	else IP_TYPE='dhcp'

		IPADDR 	= document.getElementById('txtIPAddr1').value+'.'+document.getElementById('txtIPAddr2').value+'.'
                          +document.getElementById('txtIPAddr3').value+'.'+document.getElementById('txtIPAddr4').value;
		
		NETMASK = document.getElementById('txtSubnet1').value+'.'+document.getElementById('txtSubnet2').value+'.'
                          +document.getElementById('txtSubnet3').value+'.'+document.getElementById('txtSubnet4').value;

		GATEWAY = document.getElementById('txtGatewayAddr1').value+'.'+document.getElementById('txtGatewayAddr2').value+'.'
                          +document.getElementById('txtGatewayAddr3').value+'.'+document.getElementById('txtGatewayAddr4').value;

		DNS1    = document.getElementById('txtDNSAddr1_1').value+'.'+document.getElementById('txtDNSAddr1_2').value+'.'
                          +document.getElementById('txtDNSAddr1_3').value+'.'+document.getElementById('txtDNSAddr1_4').value;
		if(DNS1 == '...')DNS1 = 'NULL';
	
		DNS2    = document.getElementById('txtDNSAddr2_1').value+'.'+document.getElementById('txtDNSAddr2_2').value+'.'
                          +document.getElementById('txtDNSAddr2_3').value+'.'+document.getElementById('txtDNSAddr2_4').value;
		if(DNS2 == '...')DNS2 = 'NULL';

		MTU = gNetworkInfo.MTU;
		
		_time = readInput();
					
		var newDate = new datetimeInfo(_time[0],_time[1],_time[2],_time[3],_time[4],_time[5],_time[6]);
/*

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
	*/	
	checkChange(); 
	hasChanged=0;
	
	display_POPUP('apply_bstep');
	
	///////////////////////////////////////////
	//        STEP 1 : Error Check           //
	///////////////////////////////////////////
	
	if(needChangeStep1 > 0){
		
				if(!HostCheck()){
					 display_POPUP('host_err');
					 return false;
				 }		
					
				if(!DescCheck()){
					 display_POPUP('desc_err');
					 return false;
				 }		
	 		
	}

	
	///////////////////////////////////////////
	//        STEP 3 : Error Check           //
	///////////////////////////////////////////

	if(needChangeStep3 > 0) {

			if(!timeCheck(newDate) )
			{
					display_POPUP('time_err');
					return false;
			}
			
	}

	
	///////////////////////////////////////////
	//        STEP 2 : Error Check           //
	///////////////////////////////////////////

	
	if(needChangeStep2 > 0){
	
		if(IP_TYPE == 'none'){
			
				if(!IPCheck()){
					display_POPUP('ip_err');
					return false;
				}
						
				if(!MASKCheck()){
						display_POPUP('mask_err');
						return false;
				}
					
				if(!GATEWAYCheck()){
						display_POPUP('gateway_err');
						return false;
				}
							
				if(!DNSCheck()){
						display_POPUP('dns_err');
						return false;
				}	
								
				if(!VailidityCheck()){
						
							display_POPUP('validity_err');
							return false;
				}		
		}				
	}
	systemw_set_bstep('bstep3');
	
}

function systemw_set_bstep(mode)
{
	
		var _txText 
		if(mode == 'bstep1'){
				_txText=	'&txtHostName='+HOSTNAME
				+"&txtHostDesc="+HOSTDESC
				+"&txtMode="+mode;
				/*+"&rdoDomainType="+DOMAIN_TYPE
				+"&txtWorkgroup="+WORKGROUP
				+"&txtDomain="+DOMAIN
				+"&txtDomainUser="+DOMAINUSER
				+"&txtDomainPass="+DOMAINPASS*/
				
				needChangeStep1 =0;
		}
		else if(mode == 'bstep2'){	
				_txText=	'&rdoDHCP='+IP_TYPE
				+"&txtIPAddr="+IPADDR
				+"&txtSubnet="+NETMASK
				+"&txtGatewayAddr="+GATEWAY
				+"&txtDNSAddr1="+DNS1
				+"&txtDNSAddr2="+DNS2
				+"&txtMTU="+MTU
				+"&txtMode="+mode;
				
				needChangeStep2 = 0;
			}	
			else if(mode == 'bstep3'){
			  _txText = '&txtYear='+_time[0]
			  +"&txtMonth="+_time[1]
			  +"&txtDay="+_time[2]
			  +"&txtHour="+_time[3]
			  +"&txtMin="+_time[4]
			  +"&txtSec="+_time[5]
			  +"&txtTimeZone="+_time[6]
			  +"&txtMode="+mode;
			  
			  needChangeStep3 = 0;
		  }
			//alert(_txText);
			//alert(mode);
			
		sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);
		
		
		return true;

	
}
function onLoadST(oj)
{	
	var res = new String();
	
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	code = res.split(':');
	//alert(code);
	
	if(code[0] == 'ok') {
		hasChanged++;
		display_POPUP(code[1]);
		}
		
	if(needChangeStep1 > 0) {
			systemw_set_bstep('bstep1');
	}
	else if(needChangeStep2 > 0) {
		systemw_set_bstep('bstep2');
	}
		
}


//========================================================//
// Set time to server
//========================================================//

function readInput()
{
	var _value = new Array();
	var _cnt =6;
	for(var i=0; i<_cnt; i++)
	{
		_value[i] = document.getElementById(gIdInput[i+1]).value;
	}
	var _ojTZ = document.getElementById(gIdInput[7]);
	var _selectedTZindex = _ojTZ.selectedIndex;
	_value[i] = _ojTZ.options[_selectedTZindex].text;
	return _value;
}


function movePage(movePage){
		location.href=movePage;
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
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">"
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><a href=\"#\" onclick=\""; 
	popup_button_footer = "\"><img src=\"../images/btn/btn_confirm.gif\"  border=\"0\"></a></td>"
        			+"</tr></table>";
	
	var popup = new String();
	
// STEP 1 : Start
	if(mode == 'noChange'){

		popup_contents = "<?php echo lang_get('network_msg_2')?>";
		popup_button_link = "showTable('systemw_bstep1');";
	}

	if(mode == 'host_err'){

		popup_contents = "<?php echo lang_get('network_msg_5')?>";
		popup_button_link = "showTable('systemw_bstep1');";
	}

	if(mode == 'desc_err'){

		popup_contents = "<?php echo lang_get('network_msg_6')?>";
		popup_button_link = "showTable('systemw_bstep1');";
	}

// STEP 1 : End


// STEP 3 : Start
	if(mode == 'time_err'){

		popup_contents = "<?php echo lang_get('time_msg_1')?>";
		popup_button_link = "showTable('systemw_bstep3');";
	}
// STEP 3 : End
	
// STEP 2 : Start
	if(mode == 'same_interface'){

		popup_contents = "<?php echo lang_get('network_msg_2')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}

	if(mode == 'ip_err'){

		popup_contents = "<?php echo lang_get('network_msg_9')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}
	if(mode == 'mask_err'){

		popup_contents = "<?php echo lang_get('network_msg_10')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}	
	if(mode == 'gateway_err'){

		popup_contents = "<?php echo lang_get('network_msg_11')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}	
	if(mode == 'dns_err'){

		popup_contents = "<?php echo lang_get('network_msg_12')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}
	if(mode == 'validity_err'){

		popup_contents = "<?php echo lang_get('network_msg_13')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}

	if(mode == 'ip_chg_auto'){

		popup_contents = "<?php echo lang_get('network_msg_14')?>";
		popup_button = "off";
	}

	if(mode == 'ip_chg_manual' && gprevious_mode != 'ipconflict'){
		
		popup_contents = "<?php echo lang_get('network_msg_15')?><BR /><a href=\"http://"+IPADDR+"/\">http://"+IPADDR+"/</a>"
		popup_button = 'off';
	}

	if(mode == 'ipconflict'){

		popup_contents = "<?php echo lang_get('network_msg_16')?>";
		popup_button_link = "showTable('systemw_bstep2');";
	}
// STEP 2 : End	
	

// COMMON PART : Start	
	if(mode == 'apply_bstep'){
		popup_contents = "<?php echo lang_get('wizard_msg_7')?> (0/"+needChange+")";
		popup_button = 'off';
	}


	if(mode == 'bstep'){
		if((hasChanged+1) == needChange){
				if(gNetworkInfo.IP_TYPE != IP_TYPE && IP_TYPE == 'dhcp'){
					popup_contents = "<?php echo lang_get('network_msg_14')?>";
					popup_button = "off";		
				}
				else if(gNetworkInfo.IPADDR != IPADDR){

					popup_contents = "<?php echo lang_get('wizard_msg_7')?> ("+hasChanged+"/"+needChange+")";
					popup_button = 'off';		
					setTimeout("display_POPUP('ip_chg_manual')",10000);
				}
				else{	
					popup_contents = "<?php echo lang_get('wizard_msg_7')?> ("+hasChanged+"/"+needChange+")";
					popup_button = 'off';
				}
		}
		else if(hasChanged == needChange){
			popup_contents = "<?php echo lang_get('wizard_msg_8')?>";
			popup_button_link = "movePage('./systemw_00.php');";
		
		
		}
		else{	
			popup_contents = "<?php echo lang_get('wizard_msg_7')?> ("+hasChanged+"/"+needChange+")";
			popup_button = 'off';
		}
	}
// COMMON PART : End
	
	

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	if(popup_contents !=''){
	showTable('systemw_bstep_POPUP');
	document.getElementById('system_message').innerHTML = popup;
	}
	gprevious_mode = mode;


}

function checkChange()
{
	//alert( HOSTNAME +"-"+ HOSTDESC +"-"+ IP_TYPE +"-"+ IPADDR+"-"+ NETMASK+"-"+ GATEWAY+"-"+ DNS1+"-"+ DNS2);	
	needChangeStep1=0;
	needChangeStep2=0;
	needChangeStep3=1;
	
	
	if(gNetworkInfo.HOSTNAME != HOSTNAME ) needChangeStep1++;
	else if(gNetworkInfo.HOSTDESC != HOSTDESC ) needChangeStep1++;
	
	if(gNetworkInfo.IP_TYPE != IP_TYPE && IP_TYPE == 'dhcp') needChangeStep2++;
	else if(gNetworkInfo.IP_TYPE != IP_TYPE && IP_TYPE == 'none') needChangeStep2++;
	else if(gNetworkInfo.IP_TYPE == IP_TYPE && IP_TYPE == 'none'){
		if((gNetworkInfo.IPADDR == IPADDR) && (gNetworkInfo.NETMASK ==NETMASK) && (gNetworkInfo.GATEWAY == GATEWAY) && (gNetworkInfo.DNS1 == DNS1) && (gNetworkInfo.DNS2 == DNS2)){
		}			
		else {
			needChangeStep2++;	
		}
	}

	needChange = needChangeStep1 + needChangeStep2 + needChangeStep3;

	
}


//========================================================//
// Validation Process
//========================================================//
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
			alert('The entered Primary DNS is not valid\nValid address must be within 0~255 range');
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
function valid_DNS(input){
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
		if((input.value==255)||(input.value==252)||(input.value==248)||(input.value==240)||(input.value==224)||(input.value==192)||(input.value==128)||(input.value==0)) return true;
		else return false;
	}else return false;
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

function valid_name(input) {
			
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
    	if(input.value.length<3) return false;	
	if(input.value.length>12) return false;	
    	return containsCharsOnly(input,chars);
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


function CheckRange(id){
	if(document.getElementById(id).value<0||document.getElementById(id).value>255){
		alert("<?php echo lang_get('network_msg_25')?>");
		document.getElementById(id).value='0';
		return false;
	}else if((!(IP_FormCheck(id)))&&(document.getElementById(id).value!='')){
		alert("<?php echo lang_get('network_msg_25')?>");
		document.getElementById(id).value='';
	}else return true;
}

function valid_address(input) {
    	var chars = "0123456789";
    	if(input.value.length<1) return false;	
	if(input.value>255) return false;
	return containsCharsOnlyIP(input,chars);
}

function IP_FormCheck(form)
{
	if(!(valid_address(document.getElementById(form)))) {
			
			return false;
   } else return true;
}


//=======================================================//
// Input character check
//=======================================================//
var check_input = {
	"date" : function(item){
		var _item = {
			"year" : "idYear" ,
			"month" : "idMonth" ,
			"day" : "idDay"
		};
		var _reg = {
			"year" : /^20[0-9]{2}$/ ,
			"month" : /(^[1-9]$)|(^0[1-9]$)|(^1[0-2]$)/ ,
			"day" : /(^[1-9]$)|(^0[1-9]$)|(^[1-2][0-9]$)|(^3[01]$)/
		};
		//alert(_item[item]);
		var _oj = document.getElementById(_item[item]);
		
		var _val = _oj.value;
		if( !_val.match(_reg[item]) ){
			alert("<?php echo lang_get('time_msg_2')?>");
			_oj.value = "";
			return false;
		};
		return true;
	} ,
	"time" : function(item){
		var _item = {
			"hour" : "idHour" ,
			"min" : "idMinute" ,
			"sec" : "idSecond"
		};
		var _reg = {
			"hour" : /(^[0-9]$)|(^[01][0-9]$)|(^2[0-4]$)/ ,
			"min" : /(^[0-9]$)|(^[0-5][0-9]$)/ ,
			"sec" : /(^[0-9]$)|(^[0-5][0-9]$)/
		};
		var _oj = document.getElementById(_item[item]);
		var _val = _oj.value;
		if( !_val.match(_reg[item]) ){
			alert("<?php echo lang_get('time_msg_2')?>");
			_oj.value = "";
			return false;
		};
		return true;
	} ,
	"server_name" : function(){
		var _oj = document.getElementById('idServeraddrIn');
		var _val = _oj.value;
		_reg = /([a-z\d][-a-z\d]*[a-z\d]\.)+[a-z][-a-z\d]*[a-z]/;
		//alert(_val.match(_reg));
		if( !_val.match(_reg) ){
			alert("<?php echo lang_get('time_msg_5')?>");
			_oj.value = "";
			return false;
		};
		return true;
	}
}


//========================================================//
// Date & time check
//========================================================//
function timeCheck(datetime)
{
	if(datetime.year>=2008 && datetime.month>=1 && datetime.month<=12 && datetime.day>=1 && datetime.day<=31)	// Needs a modification
	{
		if(datetime.hour=="" || datetime.minute=="" || datetime.second=="")
		{
			return false;
		}else if(datetime.hour>=0 && datetime.hour<=24 && datetime.minute>=0  && datetime.minute<=60 && datetime.second>=0 && datetime.second<=60)
		{
			return true;
		}
	}
	return false;
}

//========================================================//
// show_help
//========================================================//

function show_help()
{

		var _win = window.open('../help/wizard/help_system_wizard.html','Help_System_wizard','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;

	}