<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//========================================================//
// System / Power menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/power_hib_get.php","../php/power_hib_set.php",
		"../php/power_ups_get.php","../php/power_ups_set.php",
		"../php/power_schedule_get.php","../php/power_schedule_set.php",	
		"../php/power_shutdown_set.php");

//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_Power_Hib','idTable_Power_Hib_Edit',
		'idTable_Power_Ups','idTable_Power_Ups_Edit',
		'idTable_Power_Sche','idTable_Power_Sche_Edit',		
		'idTable_Power_Shutdown');
		
//========================================================//
// Output ID_Hib
//========================================================//
var gIdOutputHib=new Array('id_HibEna','id_HibWait');

//========================================================//
// Output ID_Ups
//========================================================//
var gIdOutputUps=new Array('id_UpsEna','id_UpsCable',
'id_UpsShutdown','id_UpsPowerOff');

//========================================================//
// Output ID_Sche
//========================================================//
var gIdOutputSche=new Array('id_ScheEna','id_ScheOnTime', 'id_ScheOffTime');
//'id_Sche_SHour','id_Sche_SMin',
//'id_Sche_EHour','id_Sche_EMin');

//========================================================//
// Data type
//========================================================//
function PowerHibInfo(hibernation_enable,hibernation_minutes)
{
	this.enable = hibernation_enable;
	this.minutes = hibernation_minutes;
}
function PowerUpsInfo(ups_enable,ups_shutdown_times,ups_poweroff)
{
	this.enable = ups_enable;
	this.shutdown_time = ups_shutdown_times;
	this.poweroff = ups_poweroff;
}
function PowerScheInfo(schedule_enable,schedule_SHour,schedule_SMin,schedule_EHour,schedule_EMin)
{
	this.enable  = schedule_enable;
	this.startH  = schedule_SHour;
	this.startM  = schedule_SMin;
	this.endH   = schedule_EHour;
	this.endM   = schedule_EMin;
	
}


//========================================================//
// Page status
//========================================================//
var gStat = new Array('hib_basic','hib_edit','ups_basic','ups_edit','sche_basic','sche_edit','shutdown');
var fStat = gStat[0];
//========================================================//
// Information variable
//========================================================//
var gHibInfo = new PowerHibInfo("on",30);
var gUpsInfo = new PowerUpsInfo("off",0,"on");
var gSchedule = new PowerScheInfo("off",0,0,0,0);

//========================================================//
// Hibernation tap open
//========================================================//
function openHib()
{
	//debug('openHib');
	if(fStat==gStat[0])
	{
		return false;
	}else
	{
		showTable(gIdTable[0]);
		Get_Hib_Info();
		fStat = gStat[0];
		return true;
	}
}
//========================================================//
// Ups tap open
//========================================================//
function openUps()
{
	//debug('openUps');
	if(fStat==gStat[2])
	{
		return false;
	}else{
		showTable(gIdTable[2]);
		Get_Ups_Info();
		fStat = gStat[2];
		return true;
	}
}
//========================================================//
// Schedule tap open
//========================================================//
function openSchedule()
{
	if(fStat==gStat[4])
	{
		return false;	
	}else{
		showTable(gIdTable[4]);		

		Get_Sche_Info();
		fStat = gStat[4];
		return true;
	}
}

//========================================================//
// Ups tap open
//========================================================//
function openShutdown()
{
	//debug('openUps');

	if(fStat==gStat[6])
	{
		return false;
	}else{
		showTable(gIdTable[6]);
		fStat = gStat[6];
		return true;
	}
}
//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	//debug(id);

	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";
	document.getElementById(gIdTable[4]).style.display = "none";
	document.getElementById(gIdTable[5]).style.display = "none";
	document.getElementById(gIdTable[6]).style.display = "none";	

	
	if(id!="")
	{		
		document.getElementById(id).style.display = "block";
	}
}
//========================================================//
// Get server time
//========================================================//
function Get_Hib_Info()
{
	
	document.getElementById(gIdOutputHib[0]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputHib[1]).innerHTML = "<?php echo lang_get('common_loading')?>";

	sendRequest(onLoadDT_Hib,'','post',gPhp[0],true,true);
	return true;
}

function Get_Ups_Info()
{
	
	document.getElementById(gIdOutputUps[0]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputUps[1]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputUps[2]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputUps[3]).innerHTML = "<?php echo lang_get('common_loading')?>";

	sendRequest(onLoadDT_Ups,'','post',gPhp[2],true,true);
	return true;
}

function Get_Sche_Info()
{
	document.getElementById(gIdOutputHib[0]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputHib[1]).innerHTML = "<?php echo lang_get('common_loading')?>";

	sendRequest(onLoadDT_Sche,'','post',gPhp[4],true,true);
	return true;
}

function onLoadDT_Sche(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
	var item = res.split('=>');
	gSchedule.enable = item[0];	

	var start_time = item[1].split(' ~ ');

	var s_time = start_time[0].split(':');
	gSchedule.startH = s_time[0];
	gSchedule.startM  = s_time[1];

	var e_time = start_time[1].split(':');
	gSchedule.endH = e_time[0];
	gSchedule.endM  = e_time[1];

	ShowScheInfo(gSchedule);

}


function onLoadDT_Hib(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var item = res.split(':');
	
	gHibInfo.enable = item[0];
	gHibInfo.minutes = item[1];

	ShowHibInfo(gHibInfo);

}

function onLoadDT_Ups(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var item = res.split(':');
	
	gUpsInfo.enable = item[0];
	gUpsInfo.shutdown_time = item[1];
	gUpsInfo.poweroff = item[2];

	ShowUpsInfo(gUpsInfo);

}
//========================================================//
// Show Power
//========================================================//
function ShowHibInfo(HibInfo)
{
	if( HibInfo.enable == 'on' ){
		document.getElementById(gIdOutputHib[0]).innerHTML = "<?php echo lang_get('common_enable')?>";
		document.getElementById(gIdOutputHib[1]).innerHTML = HibInfo.minutes.toString()+" <?php echo lang_get('common_minute_2')?>";
		//document.getElementById('hib_detail').style.display="block";
	}else{
		document.getElementById(gIdOutputHib[0]).innerHTML = "<?php echo lang_get('common_disable')?>";
		document.getElementById(gIdOutputHib[1]).innerHTML = "<?php echo lang_get('common_disable')?>";
	}
		
}

function ShowUpsInfo(UpsInfo)
{
	if( UpsInfo.enable == 'on' ){
		document.getElementById(gIdOutputUps[0]).innerHTML = "<?php echo lang_get('common_enable')?>";
		document.getElementById(gIdOutputUps[1]).innerHTML = "<?php echo lang_get('power_ups_6')?>";
		if( UpsInfo.shutdown_time == '0' )
			document.getElementById(gIdOutputUps[2]).innerHTML = "<?php echo lang_get('power_ups_9')?>";
		else
			document.getElementById(gIdOutputUps[2]).innerHTML = "<?php echo lang_get('power_ups_7')?> "+UpsInfo.shutdown_time.toString()+" <?php echo lang_get('power_ups_8')?>";
		if( UpsInfo.poweroff == 'on' )
			document.getElementById(gIdOutputUps[3]).innerHTML = "<?php echo lang_get('power_ups_11')?>";
		else
			document.getElementById(gIdOutputUps[3]).innerHTML = "<?php echo lang_get('power_ups_10')?>";
	}else{
		document.getElementById(gIdOutputUps[0]).innerHTML = "<?php echo lang_get('common_disable')?>";
		document.getElementById(gIdOutputUps[1]).innerHTML = "<?php echo lang_get('common_disable')?>";
		document.getElementById(gIdOutputUps[2]).innerHTML = "<?php echo lang_get('common_disable')?>";
		document.getElementById(gIdOutputUps[3]).innerHTML = "<?php echo lang_get('common_disable')?>";
	}
}

function ShowScheInfo(ScheInfo)
{
	if( ScheInfo.enable == 'on' ){
		document.getElementById(gIdOutputSche[0]).innerHTML = "<?php echo lang_get('common_enable')?>";
		document.getElementById(gIdOutputSche[1]).innerHTML = ScheInfo.startH.toString()+':'+convertDigit(ScheInfo.startM.toString());
															//+ScheInfo.endH.toString()+':'+ScheInfo.endM.toString()
															//+" <?php echo lang_get('common_daily')?>";
		document.getElementById(gIdOutputSche[2]).innerHTML = ScheInfo.endH.toString()+':'+convertDigit(ScheInfo.endM.toString()); 
		
	}else{
		document.getElementById(gIdOutputSche[0]).innerHTML = "<?php echo lang_get('common_disable')?>";
		document.getElementById(gIdOutputSche[1]).innerHTML ="<?php echo lang_get('common_disable')?>";
		document.getElementById(gIdOutputSche[2]).innerHTML ="<?php echo lang_get('common_disable')?>";
		
	}
}

function convertDigit(number){
	
	if(number >= 0 && number < 10){
		return "0"+number;
	}
	else
		return number;
}

function ShowHibEdit(HibInfo)
{
	//debug(HibInfo.enable);
	if(HibInfo.enable == 'off')
		document.getElementById('rdoHibDisable').checked 	= true;
	else
		document.getElementById('rdoHibEnable').checked 	= true;
	
	if (HibInfo.minutes == '10')
		document.getElementById('sltHibWait').options[0].selected = true;
	else if (HibInfo.minutes == '30')
		document.getElementById('sltHibWait').options[1].selected = true;
	else if (HibInfo.minutes == '60')
		document.getElementById('sltHibWait').options[2].selected = true;
	else if (HibInfo.minutes == '120')
		document.getElementById('sltHibWait').options[3].selected = true;

  	setEnable();
}

function ShowUpsEdit(UpsInfo)
{
	//debug(UpsInfo.enable);
	if(UpsInfo.enable == 'off')
		document.getElementById('rdoUpsDisable').checked 	= true;
	else
		document.getElementById('rdoUpsEnable').checked 	= true;
	
	if (UpsInfo.shutdown_time == '0')
		document.getElementById('rdoUpsShutdownLow').checked 	= true;
	else{
		document.getElementById('rdoUpsShutdownTime').checked 	= true;
		if (UpsInfo.shutdown_time == '5')
			document.getElementById('sltUpsMinutes').options[0].selected = true;
		else if (UpsInfo.minutes == '30')
			document.getElementById('sltUpsMinutes').options[1].selected = true;
		else if (UpsInfo.minutes == '60')
			document.getElementById('sltUpsMinutes').options[2].selected = true;
	}
	
		if(UpsInfo.poweroff == 'on')
		document.getElementById('rdoUpsPowerOffEnable').checked 	= true;
	else
		document.getElementById('rdoUpsPowerOffDisable').checked 	= true;
}

function ShowScheEdit(Schedule)
{
	if(Schedule.enable == 'off')
		document.getElementById('rdoScheDisable').checked 	= true;
	else
		document.getElementById('rdoScheEnable').checked 	= true;


	cms_init_select();
	
	// 090723 Min
  if(Schedule.enable == 'on'){
  
  				var Shour = parseInt(gSchedule.startH);
  				var Smin = parseInt(gSchedule.startM);
  				var Ehour = parseInt(gSchedule.endH);
  				var Emin = parseInt(gSchedule.endM);
  																				 
			    var obj=document.getElementById("cms_sch_Shour");
			    
			    if(obj.options[Shour]){
			        obj.options[Shour].selected=true;
			    }
			    
			    obj=document.getElementById("cms_sch_Smin");
			    Smin = Smin/10;
			    if(obj.options[Smin]){
			        obj.options[Smin].selected=true;
			    }
			        
			    obj=document.getElementById("cms_sch_Ehour");
			    if(obj.options[Ehour]){
			        obj.options[Ehour].selected=true;
					}
					
			    obj=document.getElementById("cms_sch_Emin");
					Emin = Emin/10;
			    if(obj.options[Emin]){
			        obj.options[Emin].selected=true;
					}
		
  
  
  
  }



/*	
	if (HibInfo.minutes == '10')
		document.getElementById('sltHibWait').options[0].selected = true;
	else if (HibInfo.minutes == '30')
		document.getElementById('sltHibWait').options[1].selected = true;
	else if (HibInfo.minutes == '60')
		document.getElementById('sltHibWait').options[2].selected = true;
	else if (HibInfo.minutes == '120')
		document.getElementById('sltHibWait').options[3].selected = true;

 */ 	setEnable_Sche();
}


//========================================================//
// Edit mode : Hibernation
//========================================================//
function editMode_Hib()
{
	//debug('editMode');
	showTable(gIdTable[1]);
	ShowHibEdit(gHibInfo);	
	fStat = gStat[1];
}
function InfoMode_Hib()
{
	//debug('InfoMode');
	showTable(gIdTable[0]);
	ShowHibInfo(gHibInfo);	
//	fStat = gStat[0];
}
//========================================================//
// Edit mode : UPS
//========================================================//
function editMode_Ups()
{
	//debug('editMode');
	showTable(gIdTable[3]);
	ShowUpsEdit(gUpsInfo);	
	fStat = gStat[3];
}
function InfoMode_Ups()
{
	//debug('InfoMode');
	showTable(gIdTable[2]);
	ShowUpsInfo(gUpsInfo);	
//	fStat = gStat[2];
}
//========================================================//
// Edit mode : Schedule
//========================================================//
function editMode_Sche()
{
	showTable(gIdTable[5]);
	ShowScheEdit(gSchedule);	
	fStat = gStat[5];
}
function InfoMode_Sche()
{
	//debug('InfoMode');
	showTable(gIdTable[4]);
	ShowScheInfo(gSchedule);	
//	fStat = gStat[4];
}

//========================================================//
// Set Hib to server
//========================================================//
function setHib()
{
	
	var hib_enable,hib_minutes;
	
	if (document.getElementsByName('rdoHibEna')[0].checked)
		hib_enable='on'
	else
		hib_enable='off'
	
	if (document.getElementById('sltHibWait').options[0].selected) hib_minutes = 10
	if (document.getElementById('sltHibWait').options[1].selected) hib_minutes = 30
	if (document.getElementById('sltHibWait').options[2].selected) hib_minutes = 60
	if (document.getElementById('sltHibWait').options[3].selected) hib_minutes = 120

	var _txText =	'&rdoHibEna='+hib_enable+"&sltHibWait="+hib_minutes;

	document.getElementById(gIdOutputHib[0]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputHib[1]).innerHTML = "<?php echo lang_get('common_setting')?>";
	sendRequest(onLoadST_Hib, _txText,'post',gPhp[1],true,true);
	showTable(gIdTable[0]);
	return true;
	
}
function onLoadST_Hib(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res)
	fStat = gStat[0];
	Get_Hib_Info();
}

//========================================================//
// Set Ups to server
//========================================================//
function setUps()
{
	
	var ups_enable,ups_shutdown_times,ups_poweroff;
	
	if (document.getElementsByName('rdoUpsEna')[0].checked)
		ups_enable='on';
	else
		ups_enable='off';

	if (document.getElementsByName('rdoUpsShutdown')[0].checked){
		if (document.getElementById('sltUpsMinutes').options[0].selected) 
			ups_shutdown_times = 5
		else if (document.getElementById('sltUpsMinutes').options[1].selected)
			ups_shutdown_times = 30
		else if (document.getElementById('sltUpsMinutes').options[2].selected)
			ups_shutdown_times = 60
	}else{
		ups_shutdown_times = 0;
	}

	if (document.getElementsByName('rdoUpsPower')[0].checked)
		ups_poweroff = 'on';
	else{
		ups_poweroff = 'off';
	}

	var _txText =	'&rdoUpsEna='+ups_enable
		+"&rdoUpsShutdown="+ups_shutdown_times
		+"&rdoUpsPower="+ups_poweroff;

	document.getElementById(gIdOutputUps[0]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputUps[1]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputUps[2]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputUps[3]).innerHTML = "<?php echo lang_get('common_setting')?>";
	//debug('ups1')
	sendRequest(onLoadST_Ups, _txText,'post',gPhp[3],true,true);
	showTable(gIdTable[2]);
	return true;
	
}
function onLoadST_Ups(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res)
	fStat = gStat[2];
	Get_Ups_Info();
}

//========================================================//
// Set Schedule to server
//========================================================//
function setSche()
{

	if (document.getElementsByName('rdoScheEna')[0].checked)
		gSchedule.enable='on'
	else
		gSchedule.enable='off'


	gSchedule.startH =document.getElementById("cms_sch_Shour");	
	gSchedule.startM=document.getElementById("cms_sch_Smin");	
	gSchedule.endH=document.getElementById("cms_sch_Ehour");	
	gSchedule.endM=document.getElementById("cms_sch_Emin");	

	var obj;
	obj=document.getElementById("cms_sch_Shour");
	gSchedule.startH = obj.options[obj.selectedIndex].value;

	obj=document.getElementById("cms_sch_Smin");
	gSchedule.startM =  obj.options[obj.selectedIndex].value;	


	obj=document.getElementById("cms_sch_Ehour");
	gSchedule.endH = obj.options[obj.selectedIndex].value;	

	obj=document.getElementById("cms_sch_Emin");
	gSchedule.endM = obj.options[obj.selectedIndex].value;	

	
	var _txText =	'&rdoScheEna='+gSchedule.enable
				+'&StartTime='+gSchedule.startH+':'+gSchedule.startM
				+'&EndTime='+gSchedule.endH+':'+gSchedule.endM;

	document.getElementById(gIdOutputSche[0]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputSche[1]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputSche[2]).innerHTML = "<?php echo lang_get('common_setting')?>";
	
	sendRequest(onLoadST_Sche, _txText,'post',gPhp[5],true,true);
	showTable(gIdTable[4]);
	return true;
	
}
function onLoadST_Sche(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res)
	fStat = gStat[4];
	Get_Sche_Info();
}



//=======================================================//
// Schedule initialize
//=======================================================//
function cms_init_select()
{
    var strsel;

    // Set start time : hour min      
    var seltimehour=document.getElementById("cms_time_Shour");
    strsel = "<select class='selectbox03' id='cms_sch_Shour' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_Shour'>\n";
    for(var i=0; i<24; i++){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>&nbsp;"
    seltimehour.innerHTML=strsel;
    
    var seltimemin=document.getElementById("cms_time_Smin");
    strsel = "<select class='selectbox03' id='cms_sch_Smin' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_Smin'>\n";
    for(var i=0; i<60; i +=10){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>&nbsp;"
    seltimemin.innerHTML=strsel;    


    // End start time : hour min  
    var seltimehour=document.getElementById("cms_time_Ehour");
    strsel = "<select class='selectbox03' id='cms_sch_Ehour' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_Shour'>\n";
    for(var i=0; i<24; i++){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>&nbsp;"
    seltimehour.innerHTML=strsel;
    
    var seltimemin=document.getElementById("cms_time_Emin");
    strsel = "<select class='selectbox03' id='cms_sch_Emin' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_Emin'>\n";
    for(var i=0; i<60; i +=10){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>&nbsp;"
    seltimemin.innerHTML=strsel;   
    
}


//========================================================//
// Set Shutdown to server
//========================================================//
function setShutdown()
{

	var shutdown_mode;
	shutdown_mode='shutdown';
	
	var _txText =	'&rdoShutdown='+shutdown_mode;
	sendRequest(onLoadST_Shutdown,_txText,'post',gPhp[6],true,true);
	return true;

}

function setRestart()
{

	var shutdown_mode;
	shutdown_mode='restart';
	var _txText =	'&rdoShutdown='+shutdown_mode;
	sendRequest(onLoadST_Shutdown,_txText,'post',gPhp[6],true,true);
	return true;

}
function onLoadST_Shutdown(oj)
{
	var res = decodeURIComponent(oj.responseText);
}
//========================================================//
// Popup window
//========================================================//
<!-- Get Page's Height with scrolling -->
function getPageSizeWithScroll(){ 
		//Fix for IE7 (at then end)
		if( window.innerHeight && window.scrollMaxY ) // Firefox 
		{
		pageWidth = window.innerWidth + window.scrollMaxX;
		pageHeight = window.innerHeight + window.scrollMaxY;
		}
		else if( document.body.scrollHeight > document.body.offsetHeight ) // all but Explorer Mac
		{
		pageWidth = document.body.scrollWidth;
		pageHeight = document.body.scrollHeight;
		}
		else // works in Explorer 6 Strict, Mozilla (not FF) and Safari
		{ pageWidth = document.body.offsetWidth + document.body.offsetLeft; 
			pageHeight = document.body.offsetHeight + document.body.offsetTop; 
		}
		
		// 20090111 Min
		// In this time We only Consider about Page's Height. So Only Return Pages'Height 
		return pageHeight;
}	


function open_power_shutdown_alert()
{
	document.getElementById('idDisableBackground').style.height = getPageSizeWithScroll();
	document.getElementById('idDisableBackground').style.display = 'block';   // Jongmin
	
	document.getElementById('idPopPowerShutdown').style.visibility = 'visible'; // Min
}	
function open_power_restart_alert()
{
	document.getElementById('idDisableBackground').style.height = getPageSizeWithScroll();
	document.getElementById('idDisableBackground').style.display = 'block';   // Jongmin
	document.getElementById('idPopPowerRestart').style.visibility = 'visible';	// Min
}		
function agree_power_shutdown()
{
	document.getElementById('idPopPowerShutdown').style.zIndex = 100;   // Jongmin
	
	setShutdown();
}
function cancle_power_shutdown()
{
	document.getElementById('idDisableBackground').style.display = 'none';  // Jongmin
	document.getElementById('idPopPowerShutdown').style.visibility = 'hidden';
}
function agree_power_restart()
{
	document.getElementById('idPopPowerRestart').style.visibility = 'hidden';   // Jongmin
	document.getElementById('restart_msg_box').style.visibility = 'visible';
	
	setRestart();
}
function cancle_power_restart()
{
	document.getElementById('idDisableBackground').style.display = 'none';  // Jongmin
	document.getElementById('idPopPowerRestart').style.visibility = 'hidden';
}
function open_popup(id)
{
	document.getElementById(id).style.visibility = 'visible';
	//document.getElementById(gIdTable[4]).style.display = "none";
}
function close_popup(id)
{
	document.getElementById(gIdTable[4]).style.display = 'block';
	document.getElementById(id).style.visibility = 'hidden';
}

function setEnable(){
	if(document.getElementById('rdoHibEnable').checked == true){
		document.getElementById('sltHibWait').disabled = false;
	}
	else document.getElementById('sltHibWait').disabled = true;

/*	if(fStat == gStat[4])
	{
		
		if(document.getElementById('rdoScheDisable').checked == true)
		{
			document.getElementById('cms_sch_Shour').disabled = true;
			document.getElementById('cms_sch_Smin').disabled = true;
			document.getElementById('cms_sch_Ehour').disabled = true;
			document.getElementById('cms_sch_Emin').disabled = true;
		}
		else
		{			
			document.getElementById('cms_sch_Shour').disabled = false;
			document.getElementById('cms_sch_Smin').disabled = false;
			document.getElementById('cms_sch_Ehour').disabled = false;
			document.getElementById('cms_sch_Emin').disabled = false;	
		}

	}
*/
}

function setEnable_Sche()
{
	if(document.getElementById('rdoScheDisable').checked == true)
	{
		document.getElementById('cms_sch_Shour').disabled = true;
		document.getElementById('cms_sch_Smin').disabled = true;
		document.getElementById('cms_sch_Ehour').disabled = true;
		document.getElementById('cms_sch_Emin').disabled = true;
	}
	else
	{			
		document.getElementById('cms_sch_Shour').disabled = false;
		document.getElementById('cms_sch_Smin').disabled = false;
		document.getElementById('cms_sch_Ehour').disabled = false;
		document.getElementById('cms_sch_Emin').disabled = false;	
	}
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
		var _win = window.open('../help/system/help_power.html#hib','Help_power','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		//_win.moveTo(540,240);
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_power.html#ups','Help_power','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		case 3:
		
		var _win = window.open('../help/system/help_power.html#shutdown','Help_power','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		default:
		break;
	}
}
 