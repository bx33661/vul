<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//========================================================//
// System -> Main Page 
//========================================================//

//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/system_get_info.php");

//========================================================//
// Information variable
//========================================================//
var gDateinfo = new datetimeInfo(2008,9,4,1,59,20,'Asia/Seoul');
var gSystemInfo = new SystemInfo('0','0','0','0','0','0','0','0','0','0','0','0','0','0');

//========================================================//
// Data type
//========================================================//
//function SystemInfo(HOSTNAME,HOSTDESC,DOMAIN_TYPE,WORKGROUP,DOMAIN,DOMAINUSER,DOMAINPASS,SYS_VER,FTP,NTP,EMAIL,FAN)
function SystemInfo(HOSTNAME,HOSTDESC,DOMAIN_TYPE,WORKGROUP,DOMAIN,DOMAINUSER,DOMAINPASS,SYS_VER, VOLFSTAB,DDNS,iSCSI,DLNA,EMAIL,FAN)
{
	this.HOSTNAME 		= HOSTNAME;
	this.HOSTDESC 		= HOSTDESC;
	
	
	this.DOMAIN_TYPE 	= DOMAIN_TYPE;
	this.WORKGROUP 		= WORKGROUP;
	this.DOMAIN 		= DOMAIN;
	this.DOMAINUSER 	= DOMAINUSER;
	this.DOMAINPASS 	= DOMAINPASS;

	this.SYS_VER = SYS_VER;

	this.VOLFSTAB = VOLFSTAB;
	this.DDNS = DDNS;
	this.iSCSI = iSCSI;
	this.DLNA = DLNA;
	
	//this.FTP = FTP;
	//this.NTP = NTP;
	this.EMAIL = EMAIL;
	this.FAN = FAN;

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


//========================================================//
// Get Information 
//========================================================//

function Get_System_Info(){
	document.getElementById('page_loading').style.display = 'block';
	sendRequest(onLoadSI,'','post',gPhp[0],true,true);
	
	return true;
}

function onLoadSI(oj){
	var res = decodeURIComponent(oj.responseText);
	
	var _item = res.split(':');
	//alert(res);
	gSystemInfo.HOSTNAME 		= _item[0];
	gSystemInfo.HOSTDESC 		= _item[1];

	gDateinfo.year = _item[2];
	gDateinfo.month = _item[3];
	gDateinfo.day = _item[4];
	gDateinfo.hour = _item[5];
	gDateinfo.minute = _item[6];
	gDateinfo.second = _item[7];
	gDateinfo.timezone = _item[8];
	
	gSystemInfo.SYS_VER = _item[9];
	gSystemInfo.VOLFSTAB= _item[10].toUpperCase();

	gSystemInfo.DDNS = _item[11];
	gSystemInfo.iSCSI = _item[12];
	gSystemInfo.DLNA = _item[13];	
	gSystemInfo.EMAIL = _item[14];
	
	gSystemInfo.FAN = _item[15];
	
	ShowSystemInfo();
	
}

//========================================================//
// Show Information 
//========================================================//
function ShowSystemInfo(){
	//debug(gEmailInfo.email);
	
	// HOST Name & Description
	document.getElementById('txtHOSTNAME').innerHTML 	= gSystemInfo.HOSTNAME;	
	document.getElementById('txtHOSTDESC').innerHTML 	= gSystemInfo.HOSTDESC;	

	// Firmware Version
	document.getElementById('id_FirmUpVer').innerHTML = gSystemInfo.SYS_VER;
	
	// Workgroup & Domain
	/*
	if (gSystemInfo.DOMAIN_TYPE == 'workgroup')	{
		//document.getElementById('rdoDOMAIN_TYPE_W').checked 	= true;
		
		document.getElementById('txtWorkgroup').innerHTML 		= gSystemInfo.WORKGROUP;
		//document.getElementById('txtDomain').value = '';
		//document.getElementById('txtDomainAdmin').value ='';
		//document.getElementById('txtDomainAdminPass').value ='';
	}
		else{ 					
		//document.getElementById('rdoDOMAIN_TYPE_D').checked 	= true;
		document.getElementById('txtWorkgroup').innerHTML 		= '';
		//document.getElementById('txtDomain').value 		= gNetworkInfo.DOMAIN;
		//document.getElementById('txtDomainAdmin').value 	= gNetworkInfo.DOMAINUSER;
		//document.getElementById('txtDomainAdminPass').value 	= gNetworkInfo.DOMAINPASS;

	}
	*/
	// FTP Setting
	/*if (gSystemInfo.FTP == 'on') {document.getElementById('FTPstatus').innerHTML 	= "<?php echo lang_get('common_enable')?>";}
	else{												 document.getElementById('FTPstatus').innerHTML 	= "<?php echo lang_get('common_disable')?>";}
	
	// NTP Setting
	if(gSystemInfo.NTP == 'on') 	{document.getElementById('NTPstatus').innerHTML = "<?php echo lang_get('common_enable')?>";}
	else{												 document.getElementById('NTPstatus').innerHTML = "<?php echo lang_get('common_disable')?>";}
	*/
	// RAID type
	document.getElementById('VOLfstab').innerHTML = gSystemInfo.VOLFSTAB;	

	// DDNS host name
	if (gSystemInfo.DDNS == 'on') 
	{
		document.getElementById('DDNShostname').innerHTML 	= "<?php echo lang_get('common_enable')?>";
	}
	else{
		document.getElementById('DDNShostname').innerHTML 	= "<?php echo lang_get('common_disable')?>";
	}
	
	// iSCSI status
	if(gSystemInfo.iSCSI == 'on') 	
	{
		document.getElementById('iSCSIstatus').innerHTML = "<?php echo lang_get('common_enable')?>";
	}
	else{
		document.getElementById('iSCSIstatus').innerHTML = "<?php echo lang_get('common_disable')?>";
	}
	// DLNA status
	if(gSystemInfo.DLNA == 'on') 	
	{
		document.getElementById('DLNAstatus').innerHTML = "<?php echo lang_get('common_enable')?>";
	}
	else{
		document.getElementById('DLNAstatus').innerHTML = "<?php echo lang_get('common_disable')?>";
	}
	
	
	// Current Time
	document.getElementById('txtCurrentTime').innerHTML = gDateinfo.year+"-"+gDateinfo.month+"-"+gDateinfo.day+" "+gDateinfo.hour+":"+gDateinfo.minute+":"+gDateinfo.second;
	document.getElementById('txtTimeZone').innerHTML = gDateinfo.timezone;

	// NTP Setting
	if(gSystemInfo.EMAIL.toLowerCase() == 'on') 	{document.getElementById('EMAILstatus').innerHTML = "<?php echo lang_get('common_enable')?>";}
	else{											    	 document.getElementById('EMAILstatus').innerHTML = "<?php echo lang_get('common_disable')?>";}


	// Fan Status
		document.getElementById('FANstatus').innerHTML 	= gSystemInfo.FAN;	
	refresh_Clock();
	document.getElementById('page_loading').style.display = 'none';
}


function refresh_Clock() {

   if(gDateinfo.second != null){
 		  		
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
   		
   	 if(gDateinfo.second < 10) gDateinfo.second = "0" + eval(gDateinfo.second);
     if(gDateinfo.minute < 10) gDateinfo.minute = "0" + eval(gDateinfo.minute);
     if(gDateinfo.hour < 10) gDateinfo.hour = "0" + eval(gDateinfo.hour);
     if(gDateinfo.day < 10) gDateinfo.day = "0" + eval(gDateinfo.day);
     if(gDateinfo.month < 10) gDateinfo.month = "0" + eval(gDateinfo.month);
          
	
   }
   document.getElementById('txtCurrentTime').innerHTML = gDateinfo.year+"-"+gDateinfo.month+"-"+gDateinfo.day+" "+gDateinfo.hour+":"+gDateinfo.minute+":"+gDateinfo.second;
	
   setTimeout("refresh_Clock()", 1000); //1초마???�행
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
//=======================================================//
// Help : dummy
//=======================================================//
function show_help()
{
	var _win = window.open('../help/system/help_system.html','Help_system','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
	hPopWin = _win;
}
