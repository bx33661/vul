<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
?>



//=======================================================//
// Page initialization
//=======================================================//
var page = {
	name : "schecule",
	init : function(){
		read_xml("cmsbackup.xml");
		browse('open', 'root');
		setDetail();
	}
}
//=======================================================//
// XML file list
//=======================================================//
var gXmlList=new Array("cmsbackup.xml");
var gXmlRootTagName="TASK";
//=======================================================//
// XML information
//=======================================================//
var gXmlInfo=new Array();
//=======================================================//
// ID list
//=======================================================//
var gIdBox=new Array("idListBox");
var gIdTab=new Array("idTab1","idTab2");
var gIdTable=new Array("idTable1","idTable2","idTable3");
// Check box id = idSetList_chkB_i //
var gIdIn=new Array("idInSrc");
var gIdSTitle=new Array("idSTitleCreate","idSTitleEdit");

//=======================================================//
// PHP file list
//=======================================================//
var gPhp=new Array("../php/schedule_xml.php",
"../php/schedule_image_progress_pop.php", // swkim 2008-11-16
"../php/schedule_init_db.php");
//=======================================================//
// Mesage list
//=======================================================//
var gWngMsg=new Array("<?php echo lang_get('schedule_msg_10')?>",
"Select a task.");
//=======================================================//
// Page status
//=======================================================//
var gStat=new Array("ready","create","delete","edit","backup","restore");
var fStat=gStat[0];
var gSelectedTaskNumber=null;
//=======================================================//
// Task variable
//=======================================================//
var gSelectedTaskIndex = -1;
//=======================================================//
// XML manipulation
//=======================================================//


// ======================================================//
// Define Prototype
// ======================================================//
String.prototype.replaceAll = function( searchStr, replaceStr )
{
var temp = this;

while( temp.indexOf( searchStr ) != -1 )
{
temp = temp.replace( searchStr, replaceStr );
}

return temp;
}
function read_xml()
{
	document.getElementById('idListBox').innerHTML = "<table width='670px' cellspacing='0px' cellpadding='0px'><tr><td class='firstCol'>" 
			                                             +"<?php echo lang_get('common_loading')?>"
			                                             +"</td></tr></table>";
	sendRequest(on_1,"&act=read","post",gPhp[0],false,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug("+"+res+"+");
	if(res=='')
	{
		document.getElementById('idListBox').innerHTML = "<table width='670px' cellspacing='0px' cellpadding='0px'><tr><td class='firstCol'>" 
			                                             +"<?php echo lang_get('schedule_msg_3')?>"
			                                             +"</td></tr></table>";
		gXmlInfo = [];
		return false;
	}
	var _arr=to_array(res);
	gXmlInfo=_arr;
	show_xml_list(_arr);
	//debug(_arr[0]['INCLUDE']);
}
function to_array(str)
{
	var cnt=0;
	var b=new Array();
	var a=str.split("\n");
	for(var i=0;a[i];i++)
	{
		b[i]=a[i].split("|");
		for(var j=0;b[i][j];j++)
		{
			var c=b[i][j].split(";");
			if(c[0]=="INCLUDE"||c[0]=="EXCLUDE")
			{
				c[1]=c[1].replace(/\//g,";");
			}
			b[i][c[0]]=c[1];
		}
	}
	return b;
}
//=======================================================//
// Page initialize
//=======================================================//
function cms_init_select()
{
    var strsel;
    
    var seldate=document.getElementById("cms_date");
    strsel = "&nbsp;<select class='selectbox03' id='cms_sch_date' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_hour'>\n";
    for(var i=0; i<31; i++){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i+1;
        strsel += "</option>\n";
    }
    strsel += "</select> day"
    seldate.innerHTML=strsel;
    
    var seltimehour=document.getElementById("cms_time_hour");
    strsel = "<select class='selectbox03' id='cms_sch_hour' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_hour'>\n";
    for(var i=0; i<24; i++){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>&nbsp;"
    seltimehour.innerHTML=strsel;
    
    var seltimemin=document.getElementById("cms_time_min");
    strsel = "<select class='selectbox03' id='cms_sch_min' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_min'>\n";
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
//=======================================================//
// Show information
//=======================================================//
function show_xml_list(arr)
{
	debug("show_xml_list");
	//var info=gXmlInfo;
	var info=arr;
	//debug(info[0]['NAME']);

	var _table=new Array();
	var _table_total = "";
	/*
	for(var i=0;info[i];i++)
	{
		_table[i]="<table width='670' border='0' cellspacing='0' cellpadding='0' id='idSetList"+i+"'><tr>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'></td>"
			+"<td width='130' bgcolor='#f5f5f7' class='m_gray_04' style='padding:0 0 0 10px;'>"
			+"<input type='radio' name='chkBox' id='idSetList_chkB_"+i+"' value='test'/>"
			+"<a href='#' onclick='open_edit_table("+i+");'>"
			+info[i]['NAME']
			+"</a>"
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"<td width='200' style='padding:0 0 0 10px;' class='m_gray_04'>"
			+info[i]['DESCRIPTION']
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"<td width='270' bgcolor='#f5f5f7' class='m_gray_04' style='padding:0 0 0 10px;'>"
			+info[i]['SRCPATH']
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"<td width='70' align='center' style='padding:0 0 0 0px;' class='m_gray_04'>"
			+info[i]['CYCLE']
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"</tr>"
			+"<tr><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td><td height='1' bgcolor='#e3e3e3'></td></tr>"
			+"</table>";
		if(i==0)
		{
			var _table_total=_table[i];
		}else
		{
			_table_total+=_table[i];
		}
	}
	var _table_frame=_table_total;
	*/
	
	for(var i=0;info[i];i++)
	{
			
			
			if(info[i]['CYCLE'] == 'none') ml_cycle = "<?php echo lang_get('common_none')?>";
			else if(info[i]['CYCLE'] == 'daily') ml_cycle = "<?php echo lang_get('common_daily')?>";
			else if(info[i]['CYCLE'] == 'weekly') ml_cycle = "<?php echo lang_get('common_weekly')?>";
			else if(info[i]['CYCLE'] == 'monthly') ml_cycle = "<?php echo lang_get('common_monthly')?>";
			
			_table[i]="<tr>"
			+"<td class='firstCol_250' style='word-break:break-all;padding-left:10px;width:130px'>"
			+"<input type='radio' name='chkBox' id='idSetList_chkB_"+i+"' value='test'/>"
			+"<a href='#' onclick='open_edit_table("+i+");'>"
			+info[i]['NAME']
			+"</a>"
			+"</td>"
			+"<td class='otherCol_420' style='word-break:break-all;padding-left:10px;width:200px'>"
			+info[i]['DESCRIPTION']
			+"&nbsp;</td>"
			+"<td class='firstCol_250' style='word-break:break-all;padding-left:10px;width:270px'>"
			+info[i]['SRCPATH']
			+"</td>"
			+"<td class='otherCol' style='width:70px;border-right:none;'>"
			+ml_cycle
			+"</td>"
			+"</tr>";
			
		
			_table_total+=_table[i];
		
	}
	var _table_frame="<table width='670px' cellspacing='0px' cellpadding='0px' border='0px'>" + _table_total + "</table>";
		
	document.getElementById(gIdBox[0]).innerHTML=_table_frame;
}
//=======================================================//
// Delete a task in xml list
//=======================================================//
function delete_task()
{
	var i=get_task_chk();
	if(i==-1) return false;
	var name=gXmlInfo[i]['NAME'];
	var cmd="&act=delete&task="+name;
	fStat=gStat[2];
	sendRequest(on_2, cmd, "post", gPhp[0],true,true);
	/******/	
	//if(count_task() == 1)
	//{
	//	/*** last task is deleted ***/
	//	/*** init db ***/
	//	var mode = 'init_file';
	//	submit_init_db(mode);
	//}
}
function on_2(oj)
{
	var res=decodeURIComponent(oj.responseText);
	fStat=gStat[0];
	read_xml();
}
function get_task_chk()
{
	var cnt=0;
	for(var i=0;document.getElementById('idSetList_chkB_'+i);i++)
	{
		if(document.getElementById('idSetList_chkB_'+i).checked==true)
		{
			var ret=i;
			cnt++;
		}
	}
	if(cnt==1) return ret;
	if(cnt==0) alert(gWngMsg[0].replaceAll('<BR />','\n'));
	if(cnt>1) alert(gWngMsg[1]);
	return -1;
}
function count_task()
{
	for(var i=0;document.getElementById('idSetList_chkB_'+i);i++)
	{}
	return i;
}
//=======================================================//
// Create a task in xml list
//=======================================================//
function open_create_table()
{
	close_all_table();
	var cmd="block";
	cms_init_select();
	document.getElementById(gIdTab[0]).style.display=cmd;
	document.getElementById(gIdTable[1]).style.display=cmd;
	document.getElementById(gIdSTitle[0]).style.display=cmd;
	fStat=gStat[1];
	cms_init_taskdata(null);
}
function close_all_table()
{
	var cmd="none";
	document.getElementById(gIdTab[0]).style.display=cmd;
	document.getElementById(gIdTab[1]).style.display=cmd;
	document.getElementById(gIdTable[0]).style.display=cmd;
	document.getElementById(gIdTable[1]).style.display=cmd;
	document.getElementById(gIdTable[2]).style.display=cmd;
	document.getElementById(gIdSTitle[0]).style.display=cmd;
	document.getElementById(gIdSTitle[1]).style.display=cmd;
}
function create_cancel()
{
	close_all_table();
	var cmd="block";
	document.getElementById(gIdTab[0]).style.display=cmd;
	document.getElementById(gIdTable[0]).style.display=cmd;
	
	fStat=gStat[0];
	gSelectedTaskNumber=null;
}
function create_task()
{
	var taskname=document.getElementById("cms_name").value;
	
    var srcdef="/mnt/fs";
    var cmd;
    cmd = "&act=new&task="+taskname;
    
    cmd += "&name="+document.getElementById("cms_name").value;
    cmd += "&desc="+document.getElementById("cms_description").value;
    cmd += "&srcdef="+srcdef;
    cmd += "&srcpath="+document.getElementById("cms_source").value;
    cmd += "&include="+document.getElementById("cms_filter_include").value;
    cmd += "&exclude="+document.getElementById("cms_filter_exclude").value;
    
    var obj;
    var obj=document.getElementById("cms_sch_cycle");
    cmd += "&cycle="+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_sch_date");
    cmd += "&date="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_week");
    cmd += "&week="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_hour");
    cmd += "&time="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_min");
    cmd += ":"+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_direc_full");
    if(obj.checked){
        cmd += "&direc=full";
    }else{
        cmd += "&direc=incre";
    }
    fStat=gStat[1];
 
    sendRequest(on_3, cmd, "post", "../php/comnso_schedule_xml.php",true,true);
}
function on_3(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	open_task_list();
	fStat=gStat[0];
}
// swkim 2008-11-16
//-------------------------------------------------------//
//=======================================================//
// Do backup task
//=======================================================//
function backup_task(index)
{
    //debug("backup_task");
    if(!index)
    {
   		var i = get_task_chk();
	}else
	{
		var i = index;
	}
	
	gSelectedTaskNumber = i;
	if(i==-1) return false;
	fStat=gStat[4];
	document.getElementById('id_task_number').value = i;
	//debug(document.getElementById("id_back").disabled);
	var url = gPhp[1];
	//var newWindow = window.open(url, 'SCHEDULE_BACKUP','scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no,width=432px,height=251px');
	var newWindow = window.open(url, '_blank','scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no,width=432px,height=251px');
	if (!newWindow){
		debug(newWindow);
		return false;
	}
	/*var html = "";
	html += "<html><head></head><body><form id='formid' method='post' action='" + url + "'>";
	html += "<input type='hidden' name='task_number' value='" + i + "'/>";
	html += "</form><script type='text/javascript'>document.getElementById(\"formid\").submit()</script></body></html>";
	debug("test");
	newWindow.document.write(html);*/
	
	document.getElementById("id_back").disabled = true;
	
	//debug("end backup task");
	return true;
}

/*
function on_backup_task(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	var tmp = res.split(":");
	if(!tmp[1])
	{
		fStat = gStat[0];
		alert(tmp[0]);
		return true;
	}
	if(tmp[1].match("ODD is busy now. Try later again please"))
	{
		_msg = "<?php echo lang_get('storing_msg_5')?>";
		alert(_msg);
	}else if(tmp[1].match("Close tray please"))
	{
		_msg = "<?php echo lang_get('schedule_msg_17')?>"
		alert(_msg);
	}else if(tmp[1].match("No Disc in Drive"))
	{
		_msg = "<?php echo lang_get('schedule_msg_18')?>"
		alert(_msg);
	}else if(tmp[1].match("Some problem in DB"))
	{
		_msg = "<?php echo lang_get('schedule_msg_19')?>"
		alert(_msg);
	}else if(tmp[1].match("Not enough space in Disc"))
	{
		_msg = "<?php echo lang_get('schedule_msg_20')?>"
		alert(_msg);
	}else if(tmp[1].match("This is not correct disc"))
	{
		tmp[1] = "<?php echo lang_get('schedule_msg_41')?>\n";
		tmp[1] += "<?php echo lang_get('schedule_msg_42')?>";
		if(confirm(tmp[1]))
		{
			init_disc();
		}
	}else if(tmp[1].match("Initialize disc please"))
	{
		tmp[1] = "<?php echo lang_get('schedule_msg_23')?>\n";
		tmp[1] += "<?php echo lang_get('schedule_msg_43')?>\n";
		tmp[1] += "<?php echo lang_get('schedule_msg_44')?>";
		if(confirm(tmp[1]))
		{
			init_disc();
		}
	}
	fStat=gStat[0];
	//alert(res);
}
*/
//-------------------------------------------------------//

//=======================================================//
// Edit task
//=======================================================//
function open_edit_table(index)
{
	debug("open_edit_table : "+index);
	close_all_table();
	var cmd="block";
	cms_init_select();
	document.getElementById(gIdTab[0]).style.display=cmd;
	document.getElementById(gIdTable[1]).style.display=cmd;
	document.getElementById(gIdSTitle[1]).style.display=cmd;
	fStat=gStat[3];
	gSelectedTaskNumber=index;
	var info=gXmlInfo;
	cms_init_taskdata(info[index]);
}
function edit_task()
{
	debug("edit_task : "+gSelectedTaskNumber);
	var task_number=gSelectedTaskNumber;
	if(fStat==gStat[3]&&task_number!=null)
	{
		var task_name=gXmlInfo[task_number]['NAME'];
		debug(task_name);
		modify_task(task_name);
	}
}
function modify_task(taskname)
{
    var srcdef="/mnt/fs";
    var cmd;
    cmd = "&act=modify&task="+taskname;
    
    cmd += "&name="+document.getElementById("cms_name").value;
    cmd += "&desc="+document.getElementById("cms_description").value;
    cmd += "&srcdef="+srcdef;
    cmd += "&srcpath="+document.getElementById("cms_source").value;
    cmd += "&include="+document.getElementById("cms_filter_include").value;
    cmd += "&exclude="+document.getElementById("cms_filter_exclude").value;
    
    var obj;
    var obj=document.getElementById("cms_sch_cycle");
    cmd += "&cycle="+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_sch_date");
    cmd += "&date="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_week");
    cmd += "&week="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_hour");
    cmd += "&time="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_min");
    cmd += ":"+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_direc_full");
    if(obj.checked){
        cmd += "&direc=full";
    }else{
        cmd += "&direc=incre";
    }
    sendRequest(on_5, cmd, "post", "../php/comnso_schedule_xml.php",true,true);
}
function on_5(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	open_task_list();
	fStat=gStat[0];
}
//=======================================================//
// Save button
//=======================================================//
function save_task()
{
	if( !check_condition() ){
		return false;
	}
	//debug(fStat)
	if(fStat==gStat[1])
	{
		alert("<?php echo lang_get('schedule_msg_4')?>"); // KHJ20081111B add alert
		create_task();
	}else if(fStat==gStat[3])
	{
		alert("<?php echo lang_get('schedule_msg_4')?>"); // KHJ20081111B add alert
		edit_task();
	}else
	{
		alert("<?php echo lang_get('schedule_msg_5')?>");
	}
}
//=======================================================//
// Open task list
//=======================================================//
function open_task_list()
{
	close_all_table();
	var cmd="block";
	read_xml();
	document.getElementById(gIdTab[0]).style.display=cmd;
	document.getElementById(gIdTable[0]).style.display=cmd;
	fStat=gStat[0];
}
//=======================================================//
// Open popup window for folder selection
//=======================================================//
function popup_file_browser(id)
{
	document.getElementById("idInputFieldId").value=id;
	var win = window.open('bd_pop_brows.php','SCHEDULE_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
	//var win = window.open('../popup/browsing_pop_01.php','DIR_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=500px,height=500px'); 
	win.focus(); 
}

//=======================================================//
// Page manipulation
//=======================================================//
function on_inext_pic()
{
    var obj_input = document.getElementById("cms_filter_include");
    var obj_check = document.getElementById("cms_inext_pic");
	var strold    = obj_input.value;
	strold = strold.toLowerCase();
	strold = strold.replace(/,/g, ";");
	var oldsplit = strold.split(";");

	//???? ??? ?? ???? ??? ????.
	if(strold.charAt(strold.length-1) != ";")
	{
		if(strold != "")
		{
			strold += ";";
		}
	}

	var strlist  = "*.jpg;*.jpeg;*.png;*.bmp;*.tif;*.tiff;*.gif;*.psd;*.jbg;*.jpe;*.raw;*.pcx;*.ras;*.tga;*.wmf;*.eps;*.emf;";
	var extsplit = strlist.split(";");

	var strnew, isfind;

	strnew = "";
	if(obj_check.checked)
	{
		//?? ???? ??? ???? ??? ?? ????.
		for (i = 0; i < extsplit.length; i++) 
		{
			isfind = false;
			for(j=0; j<oldsplit.length; j++)
			{
				//?? ???? ??? ??..
				if(extsplit[i] == oldsplit[j])
				{
					isfind = true;
					break;
				}
			}

			if(!isfind)
			{
				if(extsplit[i] != "")
				{
					strnew += extsplit[i];
					strnew += ";";
				}
			}
		}

		strold += strnew;
		obj_input.value = strold;
	}else
	{
		//?? ???? ?? ???? ???? ?????? ?? ????.
		for(j=0; j<oldsplit.length; j++)
		{
			isfind = false;
			for (i = 0; i < extsplit.length; i++) 
			{
				//?? ???? ??? ??..
				if(oldsplit[j] == extsplit[i])
				{
					isfind = true;
					break;
				}
			}

			if(!isfind)
			{
				if(oldsplit[j] != "")
				{
					strnew += oldsplit[j];
					strnew += ";";
				}
			}
		}
		obj_input.value = strnew;
	}
}

function on_inext_doc()
{
    var obj_input = document.getElementById("cms_filter_include");
    var obj_check = document.getElementById("cms_inext_doc");
    
	var strold   = obj_input.value;
	strold = strold.toLowerCase();
	strold = strold.replace(/,/g, ";");
	var oldsplit = strold.split(";");

	//???? ??? ?? ???? ??? ????.
	if(strold.charAt(strold.length-1) != ";")
	{
		if(strold != "")
		{
			strold += ";";
		}
	}

	var strlist  = "*.doc;*.ppt;*.xls;*.txt;*.docx;*.pptx;*.xlsx;*.rtf;*.xml;*.htm;*.html;*.xmlx";
	var extsplit = strlist.split(";");

	var strnew, isfind;

	strnew = "";
	if(obj_check.checked)
	{
		//?? ???? ??? ???? ??? ?? ????.
		for (i = 0; i < extsplit.length; i++) 
		{
			isfind = false;
			for(j=0; j<oldsplit.length; j++)
			{
				//?? ???? ??? ??..
				if(extsplit[i] == oldsplit[j])
				{
					isfind = true;
					break;
				}
			}

			if(!isfind)
			{
				if(extsplit[i] != "")
				{
					strnew += extsplit[i];
					strnew += ";";
				}
			}
		}

		strold += strnew;
		obj_input.value = strold;
	}else
	{
		//?? ???? ?? ???? ???? ?????? ?? ????.
		for(j=0; j<oldsplit.length; j++)
		{
			isfind = false;
			for (i = 0; i < extsplit.length; i++) 
			{
				//?? ???? ??? ??..
				if(oldsplit[j] == extsplit[i])
				{
					isfind = true;
					break;
				}
			}

			if(!isfind)
			{
				if(oldsplit[j] != "")
				{
					strnew += oldsplit[j];
					strnew += ";";
				}
			}
		}
		obj_input.value = strnew;
	}
}

function cms_init_taskdata(info)
{
	if(info==null)
	{
		var info=new Array();
	}
    document.getElementById("cms_name").value = get_defval(info['NAME']);
    document.getElementById("cms_description").value = get_defval(info['DESCRIPTION']);
    document.getElementById("cms_source").value = get_defval(info['SRCPATH']);
    document.getElementById("cms_filter_include").value = get_defval(info['INCLUDE']);
    document.getElementById("cms_filter_exclude").value = get_defval(info['EXCLUDE']);
    
    var objcycle=document.getElementById("cms_sch_cycle");
    var strcycle=get_defval(info['CYCLE']);
    switch(strcycle.toLowerCase()){
    case 'none' :
		objcycle.options[0].selected=true;
		document.getElementById('cms_sch_hour').disabled = true;
		document.getElementById('cms_sch_min').disabled = true;
		break;
    case 'daily':
        objcycle.options[1].selected=true;
        break;
    case 'weekly':
        objcycle.options[2].selected=true;
        	  document.getElementById('cms_day').style.display = "block";
        break;
    case 'monthly':
        objcycle.options[3].selected=true;
        	  document.getElementById('cms_date').style.display = "block";
        break;
    default:
        objcycle.options[0].selected=true;
        document.getElementById('cms_sch_hour').disabled = true;
		document.getElementById('cms_sch_min').disabled = true;
    }
    
    var objweek=document.getElementById("cms_sch_week");
    var strweek=get_defval(info['WEEK']);
    switch(strweek.toLowerCase()){
    case 'sun':
        objweek.options[0].selected=true;
        break;
    case 'mon':
        objweek.options[1].selected=true;
        break;
    case 'tue':
        objweek.options[2].selected=true;
        break;
    case 'wed':
        objweek.options[3].selected=true;
        break;
    case 'thu':
        objweek.options[4].selected=true;
        break;
    case 'fri':
        objweek.options[5].selected=true;
        break;
    case 'sat':
        objweek.options[6].selected=true;
        break;                                
    default:
        objweek.options[0].selected=true;
    }
   
    var objdate=document.getElementById("cms_sch_date");
    var nday=get_defval(info['DATE']);
    if(objdate.options[nday]){
        objdate.options[nday].selected=true;
    }
    
    var strtime=get_defval(info['TIME']);
    
    var nhour, nmin;
    var sptime = strtime.split(":");
    if(sptime.length == 2){
        nhour = sptime[0];
        nmin  = sptime[1];    
    }

    var objhour=document.getElementById("cms_sch_hour");
    if(objhour.options[nhour]){
        objhour.options[nhour].selected=true;
    }
    
    var objmin=document.getElementById("cms_sch_min");
    nmin = nmin/10;
    if(objmin.options[nmin]){
        objmin.options[nmin].selected=true;
    }
    
    var strdirec=get_defval(info['DIRECTION']);
    strdirec=strdirec.toLowerCase();
    if(strdirec=="full"){
        var objfull=document.getElementById("cms_direc_full");
        objfull.checked=true;
    }else{
        var objfull=document.getElementById("cms_direc_incre");
        objfull.checked=true;    
    }
}

function get_defval(obj)
{
    if(obj != undefined){
        return obj;
    }
    return "";
}
function showLoadingImage()
{
	// IE : GIF Animation Problem Fix
	document.getElementById('sch_erase_disc_loading').src = "../images/Burn/loading.gif";
	//document.getElementById('restore_erase_disc_loading').src = "../images/Burn/loading.gif";
}
//=======================================================//
// Open Tab
//=======================================================//
function open_tab_setting()
{
	create_cancel();
	
	// IE : GIF Animation Problem Fix

	setTimeout(showLoadingImage,500);
	
	fStat=gStat[0];

}
function open_tab_restore()
{
	close_all_table();
	var cmd="block";
	document.getElementById(gIdTab[1]).style.display=cmd;
	document.getElementById(gIdTable[2]).style.display=cmd;
	
	// IE : GIF Animation Problem Fix

	//setTimeout(showLoadingImage,500);
	fStat=gStat[5];
	browse('open', 'root');
	//lang_init();
}
function open_adv_setting()
{
	debug("open adv set");
	dis_ctl('idBtnOpenAdvSet','none');
	dis_ctl('idBtnCloseAdvSet','block');
	dis_ctl('idAdvSet','block');
}
function close_adv_setting()
{
	debug("close adv set");
	dis_ctl('idBtnOpenAdvSet','block');
	dis_ctl('idBtnCloseAdvSet','none');
	dis_ctl('idAdvSet','none');
}
//=======================================================//
// Initialize restore data base
//=======================================================//
function submit_init_db(mode)
{
	var _msg = "<?php echo lang_get('schedule_msg_29')?>\n<?php echo lang_get('schedule_msg_29_1')?>";
	if(!confirm(_msg)) return 0;
	
	var _url = "../blu_ray/schedule_init_prog_pop.php";
	var newWindow = window.open(_url, 'SCHEDULE_INITIALIZE','scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no,width=420px,height=270px');
	if(!newWindow){
		debug(newWindow);
		return false;
	}
	document.getElementById('id_btn_init').disabled = true;
	document.getElementById('id_btn_rest').disabled = true;
	//document.getElementById('id_btn_erase_disc').disabled = true;
	return true;
	/*if(!mode) mode = 'init_all';
	var cmd = '&mode='+mode;
	var php = '../php/schedule_init.php';
	sendRequest(on_6,cmd,"post",php,true,true);*/
}
function on_6(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	var tmp = res.split("\n");
	res = tmp[0].split(":");
	alert(res[1]);
}
//=======================================================//
// Init Disc after trying backup failure
//=======================================================//
function init_disc()
{
	var cmd = "&mode=format_disc";
	var php = "../php/schedule_init.php";
	sendRequest(on_init_disc,cmd,'post',php,true,true);
}
function on_init_disc(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	var tmp = res.split("\n");
	res = tmp[0].split(":");
	if(res[0]=='OK')
	{
		if(confirm("<?php echo lang_get('schedule_msg_30')?>"))
		{
			backup_task(gSelectedTaskNumber);
			return true;
		}
	}
	alert(res[1]);
}
//=======================================================//
// Check input
//=======================================================//
function check_condition(){
	var _msg = "";
	var _flag = true;
	if( document.getElementById('cms_name').value == "" ){
		_msg += "<?php echo lang_get('schedule_msg_6')?>\n\n";
		_flag = false;
	}
	if( document.getElementById('cms_description').value == "" ){
		_msg += "<?php echo lang_get('schedule_msg_7')?>\n\n";
		_flag = false;
	}
	if(document.getElementById('cms_source').value == ""){
		_msg += "<?php echo lang_get('schedule_msg_8')?>";
		_flag = false;
	}
	if(_msg) alert(_msg);
	
	// Check if same name exists
	var _name = document.getElementById('cms_name').value;
	for(var _i = 0 ; gXmlInfo[_i] ; _i++){
		if(_i == gSelectedTaskNumber) continue;
		var _info = gXmlInfo[_i][0];
		//alert('input name : '+_name+' / name'+_i+' : '+_info.substr(_info.indexOf(';')+1)+' / current state : '+fStat);
		if(_name == _info.substr(_info.indexOf(';')+1)){
			_flag = false;
			var _msg = "<?php echo lang_get('usb_sync_msg_6')?>";
			alert(_msg.replaceAll('<BR />' , '\n'));
			return false;
			break;
		}
	}
	
	if(_flag) return true;

}
//=======================================================//
// Backup
//=======================================================//
var backup = {
	"task_no" : "" ,
	"edit" : function(index){
		if( !index ){
			var _flag = false;
			var _index = -1;
			for( var i=0; document.getElementById('idSetList_chkB_'+i); i++){
				if( document.getElementById('idSetList_chkB_'+i).checked ) _index = i;
			}
			if( _index == -1 ){
				alert("<?php echo lang_get('schedule_msg_9')?>");
				return false;
			}
			open_edit_table(_index);
		}
	}
}



function setDetail(){
	
	var sch_cycle = document.getElementById('cms_sch_cycle').value;
	
	document.getElementById('cms_date').style.display = "none";
	document.getElementById('cms_day').style.display = "none";
  
  	//document.getElementById('cms_sch_hour').disabled = false;
	//document.getElementById('cms_sch_min').disabled = false;
  
	if(sch_cycle == 'weekly'){
	  document.getElementById('cms_day').style.display = "block";
	}
	else if(sch_cycle == 'monthly'){
		document.getElementById('cms_date').style.display = "block";
	}
	else if(sch_cycle == 'none'){
		document.getElementById('cms_sch_hour').disabled = true;
		document.getElementById('cms_sch_min').disabled = true;
	}
	
} 
//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2');
function show_help()
{
	switch(help)
	{
		case 1:
		var _win = window.open('../help/blu-ray/help_schedule.html#schedule','Help_schedule','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/blu-ray/help_schedule#restore','Help_schedule','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;

		default:
		break;
	}
}

//=======================================================//
// Disc 
// 1. Format   -> Scheduling Backup List / Erease Disc
// 
//=======================================================//
var disc ={
	txt_finish : "<?php echo lang_get('schedule_msg_25')?>",
	format_sts : 'init',
	"format" : function(){
		if(this.format_sts == 'format'){
			alert("<?php echo lang_get('volume_format_1')?>");
			return;
		}
		var cmd = "&mode=format_disc";
		var php = "../php/schedule_init.php";
		if(!confirm("<?php echo lang_get('schedule_msg_26')?>")){
				return 0;
		}
		this.format_sts = 'format';
		sendRequest(on_disc_format,cmd,'post',php,true,true);
		
		document.getElementById('sch_erase_disc').style.display = "block";
		
		function on_disc_format(oj){
			var res=decodeURIComponent(oj.responseText);
			/* New error handling */
			if(res.search('{')>-1){
				/* In case of tray is not closed */
				eval('var _ret = '+res);
				if(_ret.result == '-4'){
					/* Restore is working */
					//var _msg = _ret.message;
					var _msg = "<?php echo lang_get('storing_msg_2')?>";	// Multi-language conversion
					//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
				}else if(_ret.result == '-5'){
					/* Tray is not closed */
					//var _msg = _ret.message;
					var _msg = "<?php echo lang_get('schedule_msg_17')?>";	// Multi-language conversion
					//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
				}else if(_ret.result == '-6'){
					/* No disc in drive */
					//var _msg = _ret.message;
				  var _msg = "<?php echo lang_get('schedule_msg_18')?>";	// Multi-language conversion
					//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
				}else if(_ret.result == '-99'){
					var _msg = "<?php echo lang_get('login_msg_6')?>";
				}
					alert(_msg);	// Currently message is english
					//document.getElementById('idButtonBurnNext').innerHTML = "<input type='image' src='../images/btn/btn_confirm.gif' onclick='close_backup();'/>";
					//document.getElementById('idButtonBurnNext').style.visibility = "visible";
					//clearTimeout(timerA);
					//document.getElementById('burning_erase_disc').style.display = "none";
					document.getElementById('sch_erase_disc').style.display = "none";
					disc.format_sts = 'init';
					return;
				/************************/
			}
			
			
			var _tmp = res.split(":");
			var _code = _tmp[0];
			var _msg = _tmp[1];

			disc.format_sts = 'init';
			if(_code=="OK"){				
				alert(disc.txt_finish);
				document.getElementById('sch_erase_disc').style.display = "none";
				return;
			}else if(_code=='BUSY'){
				document.getElementById('sch_erase_disc').style.display = "none";
				alert("<?php echo lang_get('storing_msg_2')?>");
				return;
			}else{
				// Multi Language Apply
					if(_msg.search("NOT FORMATTABLE MEDIA") != -1)
					{
						_msg = "<?php echo lang_get('schedule_msg_34')?>";
					}
				alert("<?php echo lang_get('common_error')?> : "+_msg.replace('<BR />','\n'));
				document.getElementById('sch_erase_disc').style.display = "none";
				return;
			}
			
		}
	}

	
}
//=======================================================//
// Backup DB Init
//=======================================================//
function init_db(){
	var cmd = "&mode=init_db";
	var php = "../php/schedule_init.php";
	alert("<?php echo lang_get('schedule_msg_27')?>");
	sendRequest(on_init_db,cmd,'post',php,true,true);
	
	function on_init_db(oj){
		var res=decodeURIComponent(oj.responseText);
		//debug(res);
		var _tmp = res.split(":");
		var _code = _tmp[0];
		var _msg = _tmp[1];
		if(_code=="OK"){
			alert("<?php echo lang_get('schedule_msg_28')?>");
			return;
		}else{
			alert(_msg);
			return;
		}
	}
}
