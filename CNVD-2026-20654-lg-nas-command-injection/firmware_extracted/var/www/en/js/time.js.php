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
	"name" : "time",
	"init" : function(){
		getServerTimezoneList();		// get timezone list from server
		getServerTime();			// get server time & display it
	}
}


//========================================================//
// System / Time menu / Time tap
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/time_get_info.php",
"../php/time_get_timezonelist.php","../php/time_set_info.php",
"../php/time_ntp_set_info.php","../php/time_ntp_get_info.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable1','idTable2','idTable3','idTable4');
var gIdTab = new Array("idTabDateOn","idTabDateOff",
"idTabNtpOn","idTabNtpOff");
//========================================================//
// Button ID
//========================================================//
var gIdButton=new Array('idButtontime','idButtontimeedit','idButtonntp','idButtonntpedit');
//========================================================//
// Input ID
//========================================================//
var gIdInput=new Array('idTimezone','idYear','idMonth','idDay','idHour','idMinute','idSecond','idSelecttimezone',
'idRadioenableIn','idRadiodisableIn','idServeraddrIn','idFrequencyIn','idChkboxIn','idRadio','idDate','idTime');
//========================================================//
// Output ID
//========================================================//
var gIdOutput=new Array('idOutTimezone','idOutDate','idOutTime',
'idRadioenableOut','idRadiodisableOut','idServeraddrOut','idFrequencyOut');
//========================================================//
// Data type
//========================================================//
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
function ntpInfo(enable,serverurl,defaultserver,refresh)
{
	this.enable = enable;	// on/off
	this.serverurl = serverurl;
	this.defaultserver = defaultserver; // 1/0
	this.refresh = refresh;	// 1d/1w
}
//========================================================//
// Page status
//========================================================//
var gStat = new Array('time_basic','time_edit','ntp_basic','ntp_edit');
var fStat = gStat[0];
//========================================================//
// Information variable
//========================================================//
var gTimezonelist = new Array();
var gDateinfo = new datetimeInfo(2008,9,4,1,59,20,'Asia/Seoul');
var gNtpinfo = new ntpInfo("off","time.bora.net","1","1d");
//========================================================//
// Message text
//========================================================//
var gMsgtext = new Array('Not available input!\nInput date & time again!',
'Not available input!\nInput again!');

//========================================================//
// Time tap open
//========================================================//
function openTime()
{
	//debug('openTime');
	if(fStat==gStat[0])
	{
		return false;
	}else
	{
		fStat = gStat[0];
		set_tab_button();
		showTable(gIdTable[0]);
		showButton(gIdButton[0]);
		getServerTime();
		
		return true;
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

	if(mode == 'in_process'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";	
		popup_button_header = "<tr><td align=\"center\">"
		popup_contents = "<?php echo lang_get('wizard_msg_7')?>";
		popup_button = 'off';
	}/*	
	else if(mode.match('on')){
		popup_contents = "<?php echo lang_get('time_ntp_1')?>"+" "+"<?php echo lang_get('common_msg_service_start')?>";
		popup_button_link = "Get_DLNA_Info();showTable('idTable_TIME');";
	}
	else if(mode.match('off')){
		popup_contents = "<?php echo lang_get('time_ntp_1')?>"+" "+"<?php echo lang_get('common_msg_service_stop')?>";
		popup_button_link = "Get_DLNA_Info();showTable('idTable_TIME');";
	}
	if(mode == 'id_fail'){
		popup_contents = "<?php echo lang_get('time_ntp_1')?>"+" "+"";
		popup_button_link = "showTable('idTable_TIME');";
	}*/

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('idTable_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}


//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	////debug('showTable');
	if(id=='idTable_POPUP'){
		document.getElementById('idTable_TIME').style.display = "none";
		document.getElementById('idTable_POPUP').style.display = "block";		

	}
	else
	{
		document.getElementById('idTable_POPUP').style.display = "none";
		document.getElementById('idTable_TIME').style.display = "block";		
	
		document.getElementById(gIdTable[0]).style.display = "none";
		document.getElementById(gIdTable[1]).style.display = "none";
		document.getElementById(gIdTable[2]).style.display = "none";
		document.getElementById(gIdTable[3]).style.display = "none";

		if(id!="")
		{
			document.getElementById(id).style.display = "block";
		}
	}
}




//========================================================//
// Show button area
//========================================================//
function showButton(id)
{
	////debug('showButton');
	document.getElementById(gIdButton[0]).style.display = "none";
	document.getElementById(gIdButton[1]).style.display = "none";
	document.getElementById(gIdButton[2]).style.display = "none";
	document.getElementById(gIdButton[3]).style.display = "none";
	if(id!="")
	{
		document.getElementById(id).style.display = "block";
	}
}
//========================================================//
// Get server time
//========================================================//
function getServerTime()
{
	////debug('getServerTime');
	document.getElementById(gIdOutput[0]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutput[1]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutput[2]).innerHTML = "<?php echo lang_get('common_loading')?>";
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	var _date = getItem(res,"\n");
	var _cnt = _date.length;
	var _item = new Array();
	for(var i=0; i< _cnt; i++)
	{
		_item[i] = getItem(_date[i],":");
	}
	if(_item[2]=="")
	{
		_item[2] = "None";
	}
	gDateinfo.year = _item[0][0];
	gDateinfo.month = _item[0][1];
	gDateinfo.day = _item[0][2];
	gDateinfo.hour = _item[1][0];
	gDateinfo.minute = _item[1][1];
	gDateinfo.second = _item[1][2];
	gDateinfo.timezone = _item[2];
	showServerTime(gDateinfo);
}
//========================================================//
// Show server time
//========================================================//
function showServerTime(dateinfo)
{
	document.getElementById(gIdOutput[0]).innerHTML = dateinfo.timezone;
	document.getElementById(gIdOutput[1]).innerHTML = dateinfo.year+" <?php echo lang_get('common_year')?> / "+dateinfo.month+" <?php echo lang_get('common_month')?> / "+dateinfo.day+" <?php echo lang_get('common_day')?>";
	document.getElementById(gIdOutput[2]).innerHTML = dateinfo.hour+" <?php echo lang_get('common_hour_1')?> / "+dateinfo.minute+" <?php echo lang_get('common_minute_1')?> / "+dateinfo.second+" <?php echo lang_get('common_second_1')?>";
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
// Timezone list
//========================================================//
function getServerTimezoneList()
{
	//debug('getServerTimezoneList');
	sendRequest(onLoadTL,'','post',gPhp[1],true,true);
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
// Edit mode
//========================================================//
function editMode()
{
	//debug('editMode');
	showTable(gIdTable[1]);
	showButton(gIdButton[1]);
	showInputDate();	
	fStat = gStat[1];
}
function showInputDate()
{
	//debug('showInputDate');
	document.getElementById(gIdInput[0]).innerHTML = makeTimezonetext();
	//var abc=	document.getElementById(gIdInput[0]).innerHTML;
	//alert(abc);
	document.getElementById(gIdInput[1]).value = gDateinfo.year;
	document.getElementById(gIdInput[2]).value = gDateinfo.month;
	document.getElementById(gIdInput[3]).value = gDateinfo.day;
	document.getElementById(gIdInput[4]).value = gDateinfo.hour;
	document.getElementById(gIdInput[5]).value = gDateinfo.minute;
	document.getElementById(gIdInput[6]).value = gDateinfo.second;
}
function makeTimezonetext()
{
	var _tzlist = gTimezonelist;
	var _cnt = _tzlist.length;
	var _regexp = new RegExp(gDateinfo.timezone[0].replace(/([-+])/,"\\$1").replace(/$/,"\$"));
	var _timezonetext = "<select id='idSelecttimezone' name='select' size='1' id='select' class='SELECT'>";

	for(var i=0; i<_cnt; i++)
	{
		if(_tzlist[i].match(_regexp) )
		{
			_timezonetext += "<option selected>"+_tzlist[i]+"</option>";
		}else
		{
			_timezonetext += "<option>"+_tzlist[i]+"</option>";
		}
	}

	_timezonetext += "</select>";
	return _timezonetext;
}
//========================================================//
// Client PC time
//========================================================//
function getTime()
{
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
	
	
	document.getElementById(gIdInput[1]).value = gDateinfo.year;
	document.getElementById(gIdInput[2]).value = gDateinfo.month;
	document.getElementById(gIdInput[3]).value = gDateinfo.day;
	document.getElementById(gIdInput[4]).value = gDateinfo.hour;
	document.getElementById(gIdInput[5]).value = gDateinfo.minute;
	document.getElementById(gIdInput[6]).value = gDateinfo.second;
	
	//showServerTime(gDateinfo);
	return true;
}
//========================================================//
// Set time to server
//========================================================//
function setTime()
{
	var _time = readInput();
	var _date = new datetimeInfo(_time[0],_time[1],_time[2],_time[3],_time[4],_time[5],_time[6]);
	if(!timeCheck(_date) )
	{
		//alert(gMsgtext[0]);
		alert("<?php echo lang_get('time_msg_2')?>");
		
		return false;
	}else
	{
		var _txText = '&txtYear='+_time[0]+"&txtMonth="+_time[1]+"&txtDay="+_time[2]+"&txtHour="+_time[3]+"&txtMin="+_time[4]+"&txtSec="+_time[5]+"&txtTimeZone="+_time[6];
		sendRequest(onLoadST,_txText,'post',gPhp[2],true,true);
		showTable(gIdTable[0]);
		showButton("");
		document.getElementById(gIdOutput[0]).innerHTML = "<?php echo lang_get('common_setting')?>";
		document.getElementById(gIdOutput[1]).innerHTML = "<?php echo lang_get('common_setting')?>";
		document.getElementById(gIdOutput[2]).innerHTML = "<?php echo lang_get('common_setting')?>";
		return true;
	}
}
function onLoadST(oj)
{
	var res = decodeURIComponent(oj.responseText);
	document.getElementById(gIdOutput[0]).innerHTML = "Setting complete";
	document.getElementById(gIdOutput[1]).innerHTML = "Setting complete";
	document.getElementById(gIdOutput[2]).innerHTML = "Setting complete";
	showButton(gIdButton[0]);
	fStat = gStat[0];
	getServerTime();
}
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
//========================================================//
// Date & time check
//========================================================//
function timeCheck(datetime)
{
	//NC1 released year: 2009
	if(datetime.year>=2009 && datetime.month>=1 && datetime.month<=12 && datetime.day>=1 && datetime.day<=31)	// Needs a modification
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
// System / Time menu / NTP tap
//========================================================//
//========================================================//
// NTP tap open
//========================================================//
function openNtp()
{
	//debug('openNtp');
	if(fStat==gStat[2])
	{
		return false;
	}else
	{
		getServerntp();
		fStat = gStat[2];
		set_tab_button();
		showTable(gIdTable[2]);
		showButton(gIdButton[2]);
		
		
		return true;
	}
}
function set_tab_button()
{
	if(fStat=="time_basic" || fStat=="time_edit")
	{
		document.getElementById("idTabDateOn").style.display = "block";
		document.getElementById("idTabDateOff").style.display = "none";
		document.getElementById("idTabNtpOn").style.display = "none";
		document.getElementById("idTabNtpOff").style.display = "block";
	}else if(fStat=="ntp_basic" || fStat=="ntp_edit")
	{
		document.getElementById("idTabDateOn").style.display = "none";
		document.getElementById("idTabDateOff").style.display = "block";
		document.getElementById("idTabNtpOn").style.display = "block";
		document.getElementById("idTabNtpOff").style.display = "none";
	}
	
}
//========================================================//
// NTP edit mode
//========================================================//
function ntpEditmode()
{
	//debug('ntpEditmode');
	if(fStat==gStat[3])
	{
		return false;
	}else
	{
		showTable(gIdTable[3]);
		showButton(gIdButton[3]);
		showNtpEdit();
		fStat = gStat[3];
		return true;
	}
}
function showNtpEdit()
{
	//debug('showNtpEdit');
	if(gNtpinfo.enable=="on")
	{
		document.getElementById(gIdInput[8]).checked = true;
		if(gNtpinfo.serverurl=='none')
		{
			var tmp = gNtpinfo.defaultserver;
			document.getElementById(gIdInput[12]).checked=true;
		}else
		{
			var tmp = gNtpinfo.serverurl;
			document.getElementById(gIdInput[12]).checked=false;
		}
		switch(gNtpinfo.refresh)
		{
			case '1d':
			var _i = 0;
			break;
			case '1w':
			var _i = 1;
			break;
			default:
			//debug('error');
			break;
		}
		ntp_setting.en();
	}else
	{
		document.getElementById(gIdInput[9]).checked = true;
		var tmp = "";
		document.getElementById(gIdInput[12]).checked=false;
		var _i = 0;

		ntp_setting.dis();
	}
	
	document.getElementById(gIdInput[10]).value = tmp;
	
	
	document.getElementById(gIdInput[11]).options[_i].selected = true;
}
//========================================================//
// Get NTP value from server
//========================================================//
function getServerntp()
{
	//debug("get ntp info. from server");
	var tmp = "<?php echo lang_get('common_loading')?>";
	showNtpOut(tmp);
	sendRequest(onLoadGS,'','post',gPhp[4],true,true);
	return true;
}
function showNtpOut(str)
{
	document.getElementById(gIdOutput[5]).innerHTML = str;
	document.getElementById(gIdOutput[6]).innerHTML = str;
}
function onLoadGS(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var _tmp = getItem(res,"\n");
	gNtpinfo.enable = _tmp[0];
	gNtpinfo.serverurl = _tmp[1];
	gNtpinfo.refresh = _tmp[2];
	gNtpinfo.defaultserver = _tmp[3];
	showNtp();
}
//========================================================//
// Show NTP information
//========================================================//
function showNtp()
{
	if(gNtpinfo.enable=="on")
	{
		document.getElementById(gIdOutput[3]).checked = true;
		if(gNtpinfo.serverurl=='none')
		{
			var tmp = gNtpinfo.defaultserver;
		}else
		{
			var tmp = gNtpinfo.serverurl;
		}
		switch(gNtpinfo.refresh)
		{
			case '1d':
			var tmpA = "<?php echo lang_get('time_ntp_5')?>";
			break;
			case '1w':
			var tmpA = "<?php echo lang_get('time_ntp_6')?>";
			break;
			default:
			//debug('error');
			break;
		}
	}else
	{
		document.getElementById(gIdOutput[4]).checked = true;
		var tmp = "&nbsp;";
		var tmpA = "&nbsp;";
	}
	
	document.getElementById(gIdOutput[5]).innerHTML = tmp;
	
	document.getElementById(gIdOutput[6]).innerHTML = tmpA;
}
//========================================================//
// NTP basic mode
//========================================================//
function ntpBasicmode()
{
	//debug('ntpBasicmode');
	if(fStat==gStat[1])
	{
		return false;
	}else
	{
		showTable(gIdTable[2]);
		showButton(gIdButton[2]);
		showNtp();
		fStat = gStat[1];
		return true;
	}
}
//========================================================//
// Set NTP value
//========================================================//
function setNtp()
{
	//debug('setNtp');
	var _input = readNtpinput();
	if(checkNtpinput(_input) )
	{
		//on/off, server url, 1/0, 1d/1w
		var _txText = "&rdoNTP="+_input.enable+"&txtNTPServer=";
		_txText += (_input.serverurl+"&chkNTPDefaultServer="+_input.defaultserver+"&txtNTPRefresh="+_input.refresh);

		display_POPUP('in_process');	
		sendRequest(onLoadSN,_txText,"post",gPhp[3],true,true);
	}else
	{
		alert("<?php echo lang_get('time_msg_5')?>");
	}
}
function onLoadSN(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);

	code = res.split(':');	
	//display_POPUP(code[1]); //'on' or 'off'
	//display_POPUP('on'); //'on' or 'off'
	openNtp();
}
function readNtpinput()
{
	//debug('readNtpinout');
	var _tmp = new Array();
	if(document.getElementById(gIdInput[8]).checked==true)
	{
		_tmp[0] = 'on';
	}else
	{
		_tmp[0] = 'off';
	}
	if(document.getElementById(gIdInput[12]).checked==true)
	{
		_tmp[1] = 'time.bora.net';
		_tmp[2] = 1;
	}else
	{
		_tmp[1] = document.getElementById(gIdInput[10]).value;
		_tmp[2] = 0;
	}
	//debug(document.getElementById(gIdInput[11]).selectedIndex);
	if(document.getElementById(gIdInput[11]).selectedIndex==0)
	{
		_tmp[3] = '1d';
	}else
	{
		_tmp[3] = '1w';
	}
	var _ntp = new ntpInfo(_tmp[0],_tmp[1],_tmp[2],_tmp[3]);
	return _ntp;
}
function checkNtpinput(ntpinfo)
{
	//debug('checkNtpinput');
	/*if(ntpinfo.defaultserver==0)
	{
		var myRE = new genreex();
		//document.getElementById(gIdInput[10]).value;
	}*/

	//juny
	var IsValid = document.getElementById('idServeraddrIn');
	
	if(valid_name(IsValid)){
		if(document.getElementById(gIdInput[12]).checked==false)
		{
			var _oj = document.getElementById('idServeraddrIn');
			var _val = _oj.value;
			_reg = /([a-z\d][-a-z\d]*[a-z\d]\.)+[a-z][-a-z\d]*[a-z]/;
			if( !_val.match(_reg) ){
				alert("<?php echo lang_get('time_msg_5')?>");
				_oj.value = "";
				return false;
			};	
		}
		
		return true;		
	}
	else 
	{
		return false;//display_POPUP('id_error');
	}
}

function valid_name(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	if(input.value.length<3) return false;	
    	return containsCharsOnly(input,chars);
}

/*
function valid_name(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-.";	
    	return containsCharsOnly(input,chars);
}
*/


function containsCharsOnly(input,chars) {

    	var non_start_char = ".-_0123456789";
    	if(!(non_start_char.indexOf(input.value.charAt(0)) == -1)) 
    		return false;
    	for (var inx = 0; inx < input.value.length; inx++) {
    		var test = input.value.charAt(inx);
       	if (chars.indexOf(input.value.charAt(inx)) == -1)
           		return false;
    	}
    	return true;
}

//========================================================//
// Radio buttons click
//========================================================//
function clickEnntp()
{
	//debug('clicked ntp enable');
	//showButton(gId[12]);
}
function clickDisntp()
{
	//debug('clicked ntp disable');
	//showButton('');
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
//=======================================================//
// NTP setup / default NTP server : checkbox
//=======================================================//
var set_ntp = {
	"default" : "pool.ntp.org",
	"set_default" : function(ntp_url){
		document.getElementById('id_ntp_def_addr').innerHTML = "Use Default NTP Server ("+ntp_url+")";
		this["default"] = ntp_url;
	},
	"check_chkbox" : function(){
		var _oj_chkbox = document.getElementById('idChkboxIn');
		var _oj_ntpaddr = document.getElementById('idServeraddrIn');
		if( _oj_chkbox.checked ){
			_oj_ntpaddr.value = this["default"];
		}else{
			_oj_ntpaddr.value = "";
		}
	}
};
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
		var _win = window.open('../help/system/help_time.html','Help_time','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_time.html','Help_time','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		case 3:
		
		var _win = window.open('../help/system/help_time.html','Help_time','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		default:
		break;
	}

}
//=======================================================//
// NTP SETUP
// Input field en/disable
//=======================================================//
var ntp_setting = {
	en : function(){
		document.getElementById('idServeraddrIn').disabled = false;
		document.getElementById('idChkboxIn').disabled = false;
		document.getElementById('idFrequencyIn').disabled = false;
	},
	dis : function(){
		document.getElementById('idServeraddrIn').disabled = true;
		//document.getElementById('idServeraddrIn').value = "";
		document.getElementById('idChkboxIn').disabled = true;
		document.getElementById('idFrequencyIn').disabled = true;
	}
}
