<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>



//========================================================//
// System / Firm menu /
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/firmware_up_get.php","../php/firmware_up_set.php",
		"../php/firmware_init_get.php","../php/firmware_init_set.php",
		"../php/firmware_conf_get.php","../php/firmware_conf_set.php");

//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_FirmwareUp','idTable_FirmwareInit','idTable_FirmwareConf');
		
//========================================================//
// Output ID_Upgrade
//========================================================//
var gIdOutputFirmUp=new Array('id_FirmUpVer','id_FirmUpDate','id_FirmUpOddVer','id_FirmUpOddDate');

//========================================================//
// Output ID_Initialization
//========================================================//
var gIdOutputFirmInit=new Array('id_FirmInitVer','id_FirmInitDate');

//========================================================//
// Output ID_Backup Configuration
//========================================================//
var gCONFMAX = 5;
var gIdOutputFirmConf = new Array(gCONFMAX);
var gIdTableConf=new Array('idTableConf1','idTableConf2','idTableConf3','idTableConf4','idTableConf5');
var gIdConfCheck=new Array('idCbConf1','idCbConf2','idCbConf3','idCbConf4','idCbConf5');
var gIdConfLine=new Array('conf_line1','conf_line2','conf_line3','conf_line4','conf_line5');

gIdOutputFirmConf[0]=new Array('id_FirmConfFile1','id_FirmConfVer1','id_FirmConfDate1');
gIdOutputFirmConf[1]=new Array('id_FirmConfFile2','id_FirmConfVer2','id_FirmConfDate2');
gIdOutputFirmConf[2]=new Array('id_FirmConfFile3','id_FirmConfVer3','id_FirmConfDate3');
gIdOutputFirmConf[3]=new Array('id_FirmConfFile4','id_FirmConfVer4','id_FirmConfDate4');
gIdOutputFirmConf[4]=new Array('id_FirmConfFile5','id_FirmConfVer5','id_FirmConfDate5');

//========================================================//
// Information variable
//========================================================//
var gFirmUpInfo = new FirmUpInfo("1.0.2","2008.03.02","v0.2","2008.05.02");
var gFirmInitInfo = new FirmInitInfo("1.0.4","2008.07.02");

var gConfCnt = 1;
var gFirmConfInfo = new Array(gCONFMAX);
var gConfigName = "";
var gConfigDate = "";

gFirmConfInfo[0] = new FirmConfInfo("","","");
gFirmConfInfo[1] = new FirmConfInfo("","","");
gFirmConfInfo[2] = new FirmConfInfo("","","");
gFirmConfInfo[3] = new FirmConfInfo("","","");
gFirmConfInfo[4] = new FirmConfInfo("","","");
//========================================================//
// Data type
//========================================================//
function FirmUpInfo(sys_ver,sys_date,odd_ver,odd_date)
{
	this.sys_ver = sys_ver;
	this.sys_date = sys_date;
	this.odd_ver = odd_ver;
	this.odd_date = odd_date;
}
function FirmInitInfo(init_ver,init_date)
{
	this.init_ver = init_ver;
	this.init_date = init_date;
}
function FirmConfInfo(conf_file,conf_ver,conf_date)
{
	this.conf_file = conf_file;
	this.conf_ver = conf_ver;
	this.conf_date = conf_date;
}
//========================================================//
// Page status
//========================================================//
var gStat = new Array('FirmUp','FirmInit','FirmConf');
var fStat = gStat[0];

//========================================================//
// General function
//========================================================//
function to_array(str)
{
	var tmp = str.split("\n");
	for(var i=0;tmp[i];i++)
	{
		tmp[i] = tmp[i].split(";");
	}
	return tmp;
}

//========================================================//
// Firmware Upgrade tap open
//========================================================//
function openFirmUp()
{
	debug('openFirmUp :'+fStat.toString());
	if(fStat==gStat[0])
	{
		return false;
	}else{
		showTable(gIdTable[0]);
		GetFirmUpInfo();
		fStat = gStat[0];
		return true;
	}
}
//========================================================//
// Firmware Initialization tap open
//========================================================//
function openFirmInit()
{
	debug('openFirmInit :'+fStat.toString());
	if(fStat==gStat[1])
	{
		return false;
	}else{
		showTable(gIdTable[1]);
		//GetFirmInitInfo();
		fStat = gStat[1];
		return true;
	}
}
//========================================================//
// Firmware Configuration tap open
//========================================================//
function openFirmConf()
{
	debug('openFirmConf :'+fStat.toString());
	if(fStat==gStat[2])
	{
		return false;
	}else{
		showTable(gIdTable[2]);
		GetFirmConfInfo();
		fStat = gStat[2];
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
	
	if(id!="")
	{
		document.getElementById(id).style.display = "block";
	}
}
//========================================================//
// Get server time
//========================================================//
function GetFirmUpInfo()
{
	debug('GetFirmUpInfo');
	document.getElementById(gIdOutputFirmUp[0]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputFirmUp[1]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputFirmUp[2]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputFirmUp[3]).innerHTML =  "<?php echo lang_get('common_loading')?>";

	sendRequest(onLoadDT_FirmUp,'','post',gPhp[0],true,true);
	return true;
}

function GetFirmInitInfo()
{
	debug('GetFirmInitInfo');
	document.getElementById(gIdOutputFirmInit[0]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputFirmInit[1]).innerHTML =  "<?php echo lang_get('common_loading')?>";

	sendRequest(onLoadDT_FirmInit,'','post',gPhp[2],true,true);
	return true;
}

function GetFirmConfInfo()
{
	debug('GetFirmConfInfo');
	for(var i=0; i<gCONFMAX; i++){
		document.getElementById(gIdTableConf[i]).style.display = "none";
		//document.getElementById(gIdConfLine[i]).style.display = "none";
		document.getElementById(gIdConfCheck[i]).style.display = "none";
		document.getElementById(gIdConfCheck[i]).checked = false;
	}
	
	document.getElementById(gIdOutputFirmConf[0][0]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputFirmConf[0][1]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputFirmConf[0][2]).innerHTML =  "<?php echo lang_get('common_loading')?>";
	
	document.getElementById(gIdConfCheck[0]).style.display = "block";
	document.getElementById(gIdTableConf[0]).style.display = "block";
	//document.getElementById(gIdConfLine[0]).style.display = "block";

	sendRequest(onLoadDT_FirmConf,'','post',gPhp[4],true,true);
	return true;
}

function onLoadDT_FirmUp(oj)
{
	var res = decodeURIComponent(oj.responseText);
	debug(res);
	var item = res.split(':');
	
//	gFirmUpInfo.sys_ver = item[0].split('-')[4];
	gFirmUpInfo.sys_ver = item[0];
	gFirmUpInfo.sys_date = item[1];
	gFirmUpInfo.odd_ver = item[2];
	gFirmUpInfo.odd_date = item[3];
	
	ShowFirmUpInfo(gFirmUpInfo);
}

function onLoadDT_FirmInit(oj)
{
	var res = decodeURIComponent(oj.responseText);
	debug(res);
	var item = res.split(':');
	
	gFirmInitInfo.init_ver = item[0];
	gFirmInitInfo.init_date = item[1];

	ShowFirmInitInfo(gFirmInitInfo);
}

function onLoadDT_FirmConf(oj)
{
	var res = decodeURIComponent(oj.responseText);
	var item = to_array(res);
	
	gConfCnt = item.length - 1;
	debug(res);
	for(var i=0; i<gConfCnt; i++){
		gFirmConfInfo[i].conf_file = item[gConfCnt-i-1][0];
		gFirmConfInfo[i].conf_ver = item[gConfCnt-i-1][1];
		gFirmConfInfo[i].conf_date = item[gConfCnt-i-1][2];
	}
	ShowFirmConfInfo(gFirmConfInfo);
}
//========================================================//
// Show firmware version and date
//========================================================//
function ShowFirmUpInfo(FirmUpInfo)
{
	document.getElementById(gIdOutputFirmUp[0]).innerHTML = FirmUpInfo.sys_ver.toString();
	document.getElementById(gIdOutputFirmUp[1]).innerHTML = FirmUpInfo.sys_date
	document.getElementById(gIdOutputFirmUp[2]).innerHTML = FirmUpInfo.odd_ver
	document.getElementById(gIdOutputFirmUp[3]).innerHTML = FirmUpInfo.odd_date
}

function ShowFirmInitInfo(FirmInitInfo)
{
	document.getElementById(gIdOutputFirmInit[0]).innerHTML = FirmInitInfo.sys_ver.toString();
	document.getElementById(gIdOutputFirmInit[1]).innerHTML = FirmInitInfo.init_date
}

function ShowFirmConfInfo(ConfInfo)
{
	for(var i=0; i<gCONFMAX; i++){
		document.getElementById(gIdTableConf[i]).style.display = "none";
		//document.getElementById(gIdConfLine[i]).style.display = "none";
		document.getElementById(gIdConfCheck[i]).style.display = "none";
		document.getElementById(gIdConfCheck[i]).checked = false;
	}
	if(!ConfInfo[0].conf_file){
		document.getElementById(gIdOutputFirmConf[0][0]).innerHTML = "<?php echo lang_get('firmware_msg_10')?>"
		document.getElementById(gIdOutputFirmConf[0][1]).innerHTML = "..."
		document.getElementById(gIdOutputFirmConf[0][2]).innerHTML = "..."
		//document.getElementById(gIdConfCheck[0]).style.display = "block";
		document.getElementById(gIdTableConf[0]).style.display = "block";
		return;
	}
	for(var i=0; i<gConfCnt; i++){
		document.getElementById(gIdOutputFirmConf[i][0]).innerHTML = ConfInfo[i].conf_file
		document.getElementById(gIdOutputFirmConf[i][1]).innerHTML = ConfInfo[i].conf_ver
		document.getElementById(gIdOutputFirmConf[i][2]).innerHTML = ConfInfo[i].conf_date
		
		document.getElementById(gIdConfCheck[i]).style.display = "block";
		document.getElementById(gIdTableConf[i]).style.display = "block";
		//document.getElementById(gIdConfLine[i]).style.display = "block";
		
	}
}
//========================================================//
// Display Info mode
//========================================================//
function InfoModeFirmUp()
{
	//debug('editMode');
	showTable(gIdTable[0]);
	ShowFirmUpInfo(gFirmUpInfo);	
//	fStat = gStat[0];
}
function InfoModeFirmInit()
{
	//debug('InfoMode');
	showTable(gIdTable[1]);
	ShowFirmInitInfo(gFirmInitInfo);	
//	fStat = gStat[1];
}
function InfoModeFirmConf()
{
	//debug('editMode');
	showTable(gIdTable[2]);
	ShowFirmConfInfo(gFirmConfInfo);	
//	fStat = gStat[2];
}
//========================================================//
// Set Up to server
//========================================================//
function setFirmSysUp()
{
	if(document.getElementById("system").value	== ''){
		alert("<?php echo lang_get('firmware_msg_1')?>");
		return false;
	}
	debug('setFirmSysUp');
	document.getElementById("idUpgrade").value	= 'system';
	document.getElementById(gIdOutputFirmUp[0]).innerHTML = "<?php echo lang_get('firmware_msg_11')?>";
	document.getElementById(gIdOutputFirmUp[1]).innerHTML = "<?php echo lang_get('firmware_msg_11')?>";

	showTable(gIdTable[0]);
	document.getElementById('idDisableBackground').style.height =	getPageSizeWithScroll();
	document.getElementById('idDisableBackground').style.display = 'block';   // Jongmin
	
	
	var _txText = '&rdoUpgrade='+'system'
	sendRequest(onLoadST_FirmUp, _txText,'post','../php/firmware_up_pre_set.php',true,true);

	document.getElementById('idfrmSys').submit();
	
	layer.open();		// Updating message in screen
	// document.getElementById('idDisableBackground').style.display = 'none';   // Jongmin
	return true;
}

function setFirmOddUp()
{
	if(document.getElementById("odd").value	== ''){
		alert("<?php echo lang_get('firmware_msg_2')?>");
		return false;
	}
	document.getElementById("idUpgrade").value	= 'odd';
	document.getElementById(gIdOutputFirmUp[2]).innerHTML = "<?php echo lang_get('firmware_msg_4')?>";
	document.getElementById(gIdOutputFirmUp[3]).innerHTML = "<?php echo lang_get('firmware_msg_4')?>";

	showTable(gIdTable[0]);
	
	document.getElementById('idDisableBackground').style.height =	getPageSizeWithScroll();
	document.getElementById('idDisableBackground').style.display = 'block';   // Jongmin
	
	
	var _txText = '&rdoUpgrade='+'odd';
	sendRequest(onLoadST_FirmUp, _txText,'post','../php/firmware_up_pre_set.php',true,true);

	document.getElementById('idfrmSys').submit();
	layer.open();		// Updating message in screen
	document.getElementById('popup_text_02').style.display="none";
	// document.getElementById('idDisableBackground').style.display = 'none';   // Jongmin
	return true;
}

function onLoadST_FirmUp(oj)
{
	var res = decodeURIComponent(oj.responseText);
	debug(res);
	layer.close();		// Updating message in screen

	if(res == 'fail')
	{
		alert("<?php echo lang_get('firmware_upgrade_9')?>");
	}
	
}

//========================================================//
// Set Init to server
//========================================================//

// Jongmin
function open_system_init_alert()
{
	document.getElementById('idDisableBackground').style.height =	getPageSizeWithScroll();
	document.getElementById('idDisableBackground').style.display = 'block';   

	
	//document.getElementById('idSystemInit').innerHTML= "";
	//document.getElementById('idSystemInit').style.fontSize = '15';		// emphasize message
	//document.getElementById('idSystemInit').style.fontWeight = 'bolder';	// emphasize message
	document.getElementById('idPopSystemInit').style.zIndex = 201;  
	document.getElementById('idPopSystemInit').style.visibility = 'visible';
	document.getElementById('idTable_FirmwareInit').style.display = "none";
}	

function agree_system_init()
{
	cancel_system_init();
	document.getElementById("idUpgrade").value	= 'init';
	document.getElementById('layer_init').style.display = "block";  
	document.getElementById('layer_init_img').src="../images/Burn/file_box_loading.gif";

	//var _txText = '&rdoUpgrade='+'init';
	//sendRequest(onLoadST_FirmInit, _txText,'post','../php/firmware_up_pre_set.php',true,true);

	showTable(gIdTable[0]);
	document.getElementById('idDisableBackground').style.height =	getPageSizeWithScroll();
	document.getElementById('idDisableBackground').style.display = 'block';  

	var _txText = '&rdoUpgrade='+'init';
	sendRequest(onLoadST_FirmInit, _txText,'post','../php/firmware_up_pre_set.php',true,true);
	
	document.getElementById('idfrmSys').submit();
	layer.open();		// Updating message in screen
	document.getElementById('popup_text_02').style.display="none";
	// document.getElementById('idDisableBackground').style.display = 'none';  

	return true;
}

function cancel_system_init()
{
	document.getElementById('idDisableBackground').style.display = 'none'; 
	 
	document.getElementById('idTable_FirmwareInit').style.display = 'block';
	document.getElementById('idPopSystemInit').style.visibility = 'hidden';
}
	
function setFirmInit()
{
	
	document.getElementById(gIdOutputFirmInit[0]).innerHTML = "ToDo...";
	document.getElementById(gIdOutputFirmInit[1]).innerHTML = "ToDo...";
	debug('setFirmInit')
	
	//sendRequest(onLoadST_FirmInit, _txText,'post',gPhp[3],true,true);
	showTable(gIdTable[1]);
	return true;
	
}

function onLoadST_FirmInit(oj)
{
	var res = decodeURIComponent(oj.responseText);
	layer.close();		

	if(res == 'fail')
	{
		alert("<?php echo lang_get('firmware_upgrade_9')?>");
	}
}


//========================================================//
// Set Configure to server
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





function SetFirmConfBackup()
{
	debug('GetFirmConfInfo');
	for(var i=0; i<gCONFMAX; i++){
		document.getElementById(gIdTableConf[i]).style.display = "none";
		document.getElementById(gIdConfCheck[i]).style.display = "none";
		document.getElementById(gIdConfCheck[i]).checked = false;
	}
	
	document.getElementById(gIdOutputFirmConf[0][0]).innerHTML = "<?php echo lang_get('firmware_msg_6')?>";
	document.getElementById(gIdOutputFirmConf[0][1]).innerHTML = "...";
	document.getElementById(gIdOutputFirmConf[0][2]).innerHTML = "...";

	document.getElementById(gIdTableConf[0]).style.display = "block";

	var _txText = '&txtUpgrade='+'conf_backup';
	sendRequest(onLoadST_FirmConf, _txText,'post','../php/firmware_up_set_conf.php',true,true);
	return true;
}
function open_system_conf_backup()
{
	SetFirmConfBackup();
	return true;
}
function open_system_conf_alert()
{
	for(var i=0; i<gConfCnt; i++){
		if(document.getElementById(gIdConfCheck[i]).checked){
			gConfigName = gFirmConfInfo[i].conf_file;
			gConfigDate = gFirmConfInfo[i].conf_date;
			break;
		}
	}
	
	if(i == gConfCnt){
		// Restore from PC
		if( conf.restoreFromPc() ) return;
		
		alert("<?php echo lang_get('firmware_msg_3')?>");
		return;
	}else if( conf.checkFromPc() ) {
		// Check multiple selection of config file
		if( !confirm('Restore from PC was selected.\nRestore with the config file in NAS?') ) {
			return;
		}
	}

	
	
	document.getElementById('idDisableBackground').style.height =	getPageSizeWithScroll();		
	document.getElementById('idDisableBackground').style.display = 'block';   
  
  document.getElementById('idSystemConf').innerHTML= "<?php echo lang_get('firmware_msg_8')?> "+gConfigName+"("+gConfigDate+")<?php echo lang_get('firmware_msg_8_1')?>";
  document.getElementById('idPopSystemConf').style.visibility = 'visible';

}	

function agree_system_conf()
{

	document.getElementById('idPopSystemConf').style.visibility = 'hidden';
	document.getElementById('idPopSystemConfLoading').style.visibility = 'visible';
	document.getElementById('idPopSystemConfLoadingImg').src = "../images/Burn/file_box_loading.gif";
	
	var _txText = '&rdoUpgrade='+'conf_restore';
	sendRequest(onLoadST_FirmConf, _txText,'post','../php/firmware_up_pre_set.php',true,true);

	var _txText = '&txtUpgrade='+'conf_restore'
				 +'&txtConfig='+gConfigName
				 +'&txtConfig_date='+gConfigDate;
	sendRequest(onLoadST_FirmConf_Restore, _txText,'post','../php/firmware_up_set_conf.php',true,true);

	return true;
}
function cancel_system_conf()
{
	for(var i=0; i<gCONFMAX; i++){
		document.getElementById(gIdConfCheck[i]).checked = false;
	}
	
	document.getElementById('idDisableBackground').style.display = 'none'; 
	document.getElementById('idPopSystemConf').style.visibility = 'hidden';
}

function onLoadST_FirmConf(oj)
{
	
	var res = decodeURIComponent(oj.responseText);
	//debug(res)
	fStat = gStat[2];
	GetFirmConfInfo();
}

function onLoadST_FirmConf_Restore(oj)
{
	document.getElementById('idDisableBackground').style.display = 'none';
	document.getElementById('idPopSystemConfLoading').style.visibility = 'hidden';
	
	var res = decodeURIComponent(oj.responseText);
	//debug(res)
	fStat = gStat[2];
	GetFirmConfInfo();
}
//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2','3');
function show_help()
{
	switch(help)
	{
		case 1:
		var _win = window.open('../help/system/help_firmware.html#upgrade','Help_firmware','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    	_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_firmware.html#initialize','Help_firmware','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 3:
		var _win = window.open('../help/system/help_firmware.html#configuration','Help_firmware','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		default:
		break;
	}
}
//=======================================================//
// Layer : show/hide warning message
//=======================================================//
var layer = {
	open : function(){
		document.getElementById('l_copy').style.display="block";
		var _oj = document.getElementById('l_copy');
		var _oji = document.getElementById('l_copy_img');

		_oji.src="../images/Burn/file_box_loading.gif";
		//setTimeout(checkIsHangup, 2000000);
	},
	close : function(){
		//$('l_copy').style.display="none";
	}
}

function checkIsHangup() {
		
	window.location.reload()
	
       /*document.getElementById('l_copy').style.display="block";
       var _oj = document.getElementById('l_copy');
       var _oji = document.getElementById('l_copy_img');

       _oji.src="../images/Burn/file_box_loading.gif";*/

}






var conf = {
	init : function() {
		// To do
		document.getElementById('chkConfFromPc').checked = false;
	},
	getSelected : function() {
		for(var i=1; document.getElementById('idCbConf'+i); i++) {
			if(document.getElementById('idCbConf'+i).checked) {
				return i;
			}
		}
		return -1;
	},
	save : function(str) {
		var index = this.getSelected();
		if(index==-1) {
			alert("<?php echo lang_get('extraction_msg_20')?>");
			return;
		}else {
			var tmpValue = [
				document.getElementById('id_FirmConfFile'+index).innerHTML
				];
			document.getElementById('idInputLogMode').value = tmpValue;
			document.getElementById('idForm').submit();
			
		}
	},
	checkFromPc : function() {
		return document.getElementById('chkConfFromPc').checked;
	},
	openUpload : function(id) {
		if(document.getElementById(id).checked) {
			// Check if a backup file is selected
			var tmp = this.getSelected();
			if(tmp > -1) {
				document.getElementById('idCbConf'+tmp).checked = false;
			}
			
			var tmp = this.getSelected();
			if(tmp > -1) {
				document.getElementById('idCbConf'+tmp).checked = false;
			}
			document.getElementById('inputFromPc').style.display = 'block';
		}else {
			document.getElementById('inputFromPc').style.display = 'none';
		}
	},
	restoreFromPc : function() {
		if( this.checkFromPc() ) {
			// To do
			if(!document.getElementById('fileFromPc').value) {
				alert("<?php echo lang_get('no_pc_conf_file_selected')?>");
				return true;
			}/*else if(document.getElementById('fileFromPc').value.search(/Config_\d{6}_\d{8}\.tar\.bz2$/) < 0) {
				alert('Selected file is not a config file.\nWrong file name.(e.g., Config_xxxxxx_xxxxxxxx.tar.bz2)');
				return true;
			}*/
			conf.showLoading();
			if(!confirm("<?php echo lang_get('firmware_conf_restore_confirm')?>")) {
				conf.hideLoading();
				return true;
			}
			
			document.getElementById('idfrmUpFromPc').submit();
			
			conf.timer = setInterval('conf.getRestoreStat();', 1000);
			return true;
		}else {
			return false;
		}
	},
	timer : '',
	getRestoreStat : function() {
		if(!document.getElementById('uploadTrgt0')) return;
		//var ojIframe = document.getElementById('uploadTrgt0');
		var ojIframe = document.getElementById('uploadTrgt0').contentDocument || document.getElementById('uploadTrgt0').contentWindow.document;
		
		//var ojResult = ojIframe.contentDocument.getElementById('resultRestore');
		
		if(!ojIframe.getElementById('resultRestore')) return;
		var result = ojIframe.getElementById('resultRestore').innerHTML;
		
		//alert(result);
		if(result == 'complete') {
			clearInterval(conf.timer);
			conf.hideLoading();
			alert("<?php echo lang_get('pc_conf_restore_complete')?>");
		}else{
			clearInterval(conf.timer);
			conf.hideLoading();
			alert("<?php echo lang_get('invalid_conf_file')?>");
		}
	},
	showLoading : function() {
		document.getElementById('idPopSystemConf').style.visibility = 'hidden';
		document.getElementById('idPopSystemConfLoading').style.visibility = 'visible';
		document.getElementById('idDisableBackground').style.height =	getPageSizeWithScroll();
		document.getElementById('idDisableBackground').style.display = 'block';
		document.getElementById('idPopSystemConfLoadingImg').src = "../images/Burn/file_box_loading.gif";
	},
	hideLoading : function() {
		document.getElementById('idPopSystemConfLoading').style.visibility = 'hidden';
		document.getElementById('idDisableBackground').style.display = 'none';
	}
}


