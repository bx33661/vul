//=======================================================//
// Information / Log
//=======================================================//

//=======================================================//
// ID list
//=======================================================//
var gIdTabBtn = new Array("idTabDiagOn","idTabDiagOff");
var gIdTableBox = new Array("idTableBox");
var gIdTable = new Array("idTableDiag");
//=======================================================//
// PHP file list
//=======================================================//
var gPhp = new Array("../php/diag_get.php");
//=======================================================//
// Status
//=======================================================//
var gStat = new Array("diag_log");
var fStat = gStat[0];
//=======================================================//
// Tab control
//=======================================================//
function open_tab_diag()
{
	close_table_box();
	get_log("diag_log");
	change_tab("diag");
	fStat = gStat[0];
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
		case "diag":
		document.getElementById("idTabDiagOn").style.display = "block";
		/*
		document.getElementById("idTabSmbLogOff").style.display = "block";
		document.getElementById("idTabFtpLogOff").style.display = "block";
		*/
		break;
		/*
		case "smb":
		document.getElementById("idTabSysLogOff").style.display = "block";
		document.getElementById("idTabSmbLogOn").style.display = "block";
		document.getElementById("idTabFtpLogOff").style.display = "block";
		break;
		case "ftp":
		document.getElementById("idTabSysLogOff").style.display = "block";
		document.getElementById("idTabSmbLogOff").style.display = "block";
		document.getElementById("idTabFtpLogOn").style.display = "block";
		*/
	}
}
//=======================================================//
// Get log information
//=======================================================//
function get_log(log_mode)
{
	//=======================================================//
	// log_mode : "diag_log"
	//=======================================================//
	var cmd = "&log_mode="+log_mode;
	sendRequest(on_1,cmd,"post",gPhp[0],true,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug("+"+res+"+");
	// Error check
	if(check_error(res)) show_log_box(res);
}
function check_error(str)
{
	var tmp = to_array(str);
	//debug(tmp[0]);
	if(tmp[0]=="Error")
	{
		alert(str.substr(str.indexOf("\n")) ); // Debugging
		return false;
	}
	return true;
}
function show_log_box(str)
{
	var arr = to_array(str);
	//debug(arr[0][0]+"+"+arr[0][1]+"\n"+arr[1][0]+"+"+arr[1][1]);
	
	var bay = arr;
	var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'><tr bgcolor='#5d5d5d'>";
	tmp_txt_total += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
	tmp_txt_total += "<td width='100' class='white' style='padding:0 0 0 20px'>";
	tmp_txt_total += "<strong>Diag Item</strong></td>";
	tmp_txt_total += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
	tmp_txt_total += "<td width='100' class='white' style='padding:0 0 0 20px'>";
	tmp_txt_total += "<strong>Sub Item</strong></td>";
	tmp_txt_total += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
	tmp_txt_total += "<td width='500' class='white' style='padding:0 0 0 20px'>";
	tmp_txt_total += "<strong>Test result</strong></td>";
	tmp_txt_total += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
	tmp_txt_total += "</tr>";
	
	var cnt = bay.length;
	for(var i=0;bay[i];i++)
	{
		var tmp = "<tr>";
		tmp += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
		tmp += "<td bgcolor='#f5f5f7' class='m_gray_04' style='padding:0 0 0 20px'>";
		tmp += bay[i][0];
		tmp += "</td>";
		tmp += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
		tmp += "<td bgcolor='#f5f5f7' class='m_gray_04' style='padding:0 0 0 20px'>";
		tmp += bay[i][1];
		tmp += "</td>";
		tmp += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
		tmp += "<td style='padding:0 0 0 20px' class='m_gray_04'>";
		tmp += bay[i][2];
		tmp += "</td>";
		tmp += "<td width='1' height='25' bgcolor='#e3e3e3'></td>";
		tmp += "</tr>";
		tmp += "<tr><td height='1' bgcolor='#e3e3e3'></td></tr>";
	
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
	document.getElementById('idFormDiag').submit();
}
//=======================================================//
// Loading confirm
//=======================================================//
debug("diag.js has been loaded.");