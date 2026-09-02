<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	lang_set_active_language($_GET['lang']);
?>


//=======================================================//
// Information / Log
//=======================================================//
//=======================================================//
// Init : page information
//=======================================================//
var page = {
	"name" : "log",
	"init" : function(){
		// To do
		open_tab_sys();
	}
}

//=======================================================//
// HTTPRequest object for abort
//=======================================================//
var tmp_oj = '';

//=======================================================//
// ID list
//=======================================================//
var gIdTabBtn = new Array("idTabSysLogOn","idTabSysLogOff",
"idTabSmbLogOn","idTabSmbLogOff",
"idTabFtpLogOn","idTabFtpLogOff",
"idTabDiagOn","idTabDiagOff");
var gIdTableBox = new Array("idTableBox");
var gIdTable = new Array("idTableSys","idTableSmb","idTableFtp","idTableDiag");
//=======================================================//
// PHP file list
//=======================================================//
var gPhp = new Array("../php/log_get.php");
//=======================================================//
// Status
//=======================================================//
var gStat = new Array("system_log","samba_log","ftp_log","diag_log");
var fStat = gStat[0];
//=======================================================//
// Tab control
//=======================================================//
function open_tab_sys()
{
	close_table_box();
	get_log("system_log");
	change_tab("sys");
	fStat = gStat[0];
}
function open_tab_smb()
{
	close_table_box();
	get_log("samba_log");
	change_tab("smb");
	fStat = gStat[1];
}
function open_tab_ftp()
{
	close_table_box();
	get_log("ftp_log");
	change_tab("ftp");
	fStat = gStat[2];
}
function open_tab_diag()
{
	close_table_box();
	get_log("diag_log");
	change_tab("diag");
	fStat = gStat[3];
}
function close_table_box()
{
	document.getElementById(gIdTableBox[0]).style.display = "none";
}
function change_tab(mode)
{
	for(var i=0;gIdTabBtn[i];i++)
	{
		document.getElementById(gIdTabBtn[i]).style.display = "none";
	}
	switch(mode)
	{
		case "sys":
		document.getElementById("idTabSysLogOn").style.display = "block";
		document.getElementById("idTabSmbLogOff").style.display = "block";
		document.getElementById("idTabFtpLogOff").style.display = "block";
		document.getElementById("idTabDiagOff").style.display = "block";
		break;
		case "smb":
		document.getElementById("idTabSysLogOff").style.display = "block";
		document.getElementById("idTabSmbLogOn").style.display = "block";
		document.getElementById("idTabFtpLogOff").style.display = "block";
		document.getElementById("idTabDiagOff").style.display = "block";
		break;
		case "ftp":
		document.getElementById("idTabSysLogOff").style.display = "block";
		document.getElementById("idTabSmbLogOff").style.display = "block";
		document.getElementById("idTabFtpLogOn").style.display = "block";
		document.getElementById("idTabDiagOff").style.display = "block";
		break;
		case "diag":
		document.getElementById("idTabSysLogOff").style.display = "block";
		document.getElementById("idTabSmbLogOff").style.display = "block";
		document.getElementById("idTabFtpLogOff").style.display = "block";
		document.getElementById("idTabDiagOn").style.display = "block";
		break;
	}
}
//=======================================================//
// Get log information
//=======================================================//
function get_log(log_mode)
{
	//=======================================================//
	// log_mode : "system_log", "samba_log", "ftp_log"
	//=======================================================//
	var cmd = "&log_mode="+log_mode;
	if(tmp_oj){
		tmp_oj.abort();
	}
	if(log_mode != "diag_log"){
		show_loading_log();
		tmp_oj = sendRequest(on_log,cmd,"post","../php/log_get.php",true,true);
	}else{
		show_loading_diag();
		tmp_oj = sendRequest(on_diag,cmd,"post","../php/log_get.php",true,true);
	}
}
function on_log(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug("return : "+res);
	eval('var res = '+res);
	if(res.result > 0){
		// success
		show_log_box(res);
	}else{
		// error message
		// (1) "Log file open error\nCheck FTP setting" : no ftp log file
		// (2) "Log file open error" : log file open error
		//alert(res.message);
		
		if(res.message == "Log file open error\nCheck FTP setting") {
				show_error_log("<?php echo lang_get('log_7')?>");
		}
		else if (res.message == "Log file open error") {
		show_error_log("<?php echo lang_get('log_8')?>");
		}else if(res.result == '-1'){
			// No line in log file
			show_error_log("No log");
		}
	}
	
	/*
	// Error check
	if(check_error(res)){
		show_log_box(res);
	}else{
		var tmp_txt_total = log_title_start();
		var tmp = "<tr>";
		tmp += "<td class='firstCol_250' style='width:120px'>&nbsp;</td>";
		tmp += "<td class='otherCol_420' style='width:530px'>";	
		tmp += "<?php echo lang_get('log_7')?>";
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
		tmp_txt_total += "</table>";
		document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
		document.getElementById(gIdTableBox[0]).style.display = "block";
	}
	*/
}
function check_error(str)
{
	var tmp = to_array(str);
	//debug(tmp[0]);
	if(tmp[0]=="Error")
	{
		debug(str.substr(str.indexOf("\n")) ); // Debugging
		return false;
	}
	return true;
}
function show_error_log(str)
{
	var tmp_txt_total = log_title_start();
	var tmp = "<tr>";
		tmp += "<td class='firstCol' colspan='3'>";
		tmp += str;
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
	
	tmp_txt_total += "</table>";
	document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
	document.getElementById(gIdTableBox[0]).style.display = "block";
}
function show_loading_log()
{
	var tmp_txt_total = log_title_start();
	var tmp = "<tr>";
		tmp += "<td class='firstCol' colspan='2'>";
		
		tmp += "<?php echo lang_get('common_loading')?>";
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
	
	tmp_txt_total += "</table>";
	document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
	document.getElementById(gIdTableBox[0]).style.display = "block";
}
function show_error_diag(str)
{
	var tmp_txt_total = diag_title_start();
	var tmp = "<tr>";
		tmp += "<td class='firstCol' colspan='2'>";
		tmp += str;
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
	
	tmp_txt_total += "</table>";
	document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
	document.getElementById(gIdTableBox[0]).style.display = "block";
}
function show_loading_diag()
{
	var tmp_txt_total = diag_title_start();
	var tmp = "<tr>";
		//tmp += "<td class='firstCol_250' style='width:80px'>&nbsp;</td>";
		//tmp += "<td class='firstCol_250' style='width:150px'>&nbsp;</td>";
		tmp += "<td class='firstCol' colspan='3'>";
		tmp += "<?php echo lang_get('log_6')?>";
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
	
	tmp_txt_total += "</table>";
	document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
	document.getElementById(gIdTableBox[0]).style.display = "block";
}
function log_title_start()
{
	var str;
	str = "<table width='650' height='25' border='0' cellspacing='0' cellpadding='0'><tr>";
	str += "<td class='header' style='width:120px'>" + "<?php echo lang_get('log_1')?>" + "</td>";
	str += "<td class='header' style='width:530px'>" + "<?php echo lang_get('log_2')?>" + "</td>";
	str += "</tr>";
	return str;
}
function diag_title_start()
{
	var str;
	str = "<table width='650' border='0' cellspacing='0' cellpadding='0'><tr>";
	str += "<td class='header' style='width:80px'>" + "<?php echo lang_get('log_3')?>" + "</td>";
	str += "<td class='header' style='width:150px'>" + "<?php echo lang_get('log_4')?>" + "</td>";
	str += "<td class='header' style='width:150px'>" + "<?php echo lang_get('log_5')?>" + "</td>";
	str += "</tr>";
	return str;
}
function show_log_box(oj)
{
	//var arr = to_array(str);
	//debug(arr[0][0]+"+"+arr[0][1]+"\n"+arr[1][0]+"+"+arr[1][1]);
	
	
	
	var tmp_txt_total = log_title_start();

	for(var i=oj.length-1;oj[i];i--)
	{
		var _time = oj[i].match(/\b\w\w\w\s+\d+\s\d\d:\d\d:\d\d(\s\d\d\d\d)*\b/);
		if(!_time) continue;
		if(_time[1]){
			var _message = oj[i].substr(oj[i].search(/\b\d\d:\d\d:\d\d\b/)+13);
			//debug(_message.search(/\b\d+\.\d+\.\d+\.\d+\b/));
			_message = _message.substr(_message.search(/\b\d+\.\d+\.\d+\.\d+\b/));
		}else{
			var _message = oj[i].substr(oj[i].search(/\b\d\d:\d\d:\d\d\b/)+8);
		}
		var tmp = "<tr>";
		tmp += "<td class='firstCol_250' style='width:120px'>";
		tmp += _time[0];
		tmp += "</td>";
		tmp += "<td class='otherCol_420' style='width:530px'>";
		tmp += _message;
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
	}
	
	tmp_txt_total += "</table>";
	document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
	document.getElementById(gIdTableBox[0]).style.display = "block";
}
function on_diag(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug("return => "+res);
	eval('var res = '+res);
	if(res.result){
		// success
		show_diag_box(res);
	}else{
		// error message
		// (1) "Log file open error" : log file open error
		// alert(res.message);
		
		if(res.message == "Log file open error") show_error_diag("<?php echo lang_get('log_8')?>");
		
	}
	return;
	//=========================================================//
	// Error check
	if(check_error(res)) show_diag_box(res);
}

function show_diag_box(oj)
{
	//var arr = to_array(str);
	//var bay = arr;
	var tmp_txt_total = diag_title_start();

	for(var i=0;oj[i];i++)
	{
		var _tmp = oj[i].split(':');
		var tmp = "<tr>";
		tmp += "<td class='firstCol_250' style='width:80px'>";
		tmp += _tmp[0];
		tmp += "</td>";
		tmp += "<td class='firstCol_250' style='width:150px'>";
		tmp += _tmp[1];
		tmp += "</td>";
		tmp += "<td class='otherCol_420'>";
		tmp += _tmp[2];
		tmp += "</td>";
		tmp += "</tr>";
		
		tmp_txt_total += tmp;
	}
	tmp_txt_total += "</table>";
	document.getElementById(gIdTableBox[0]).innerHTML = tmp_txt_total;
	document.getElementById(gIdTableBox[0]).style.display = "block";
}
function to_array(str)
{
	var tmp = str.split("\n");
	for(var i=0;tmp[i];i++)
	{
		tmp[i] = tmp[i].split(";");
	}
	return tmp;
}

//=======================================================//
// Download log file
//=======================================================//
function save_log()
{
	debug("save log");
	debug(fStat);
	
	document.getElementById('idInputLogMode').value = fStat;
	document.getElementById('idForm').submit();
}
//========================================================//
// show_help
//========================================================//
function show_help()
{
		var _win = window.open('../help/information/help_log.html','Help_log','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;
}
