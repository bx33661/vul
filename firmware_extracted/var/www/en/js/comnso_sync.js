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
"../php/schedule_backup.php", "../php/comnso_sync_xml.php");
//=======================================================//
// Mesage list
//=======================================================//
var gWngMsg=new Array("No task was selected.\nSelect a task.",
"Select a task.");
//=======================================================//
// Page status
//=======================================================//
var gStat=new Array("ready","create","delete","edit","backup","restore");
var fStat=gStat[0];
var gSelectedTaskNumber=null;

//comnso 기본 폴더..
var srcdef="/mnt/fs/Vol1/system/Share";


//=======================================================//
// XML manipulation
//=======================================================//
function read_xml()
{
	sendRequest(on_1,"&act=read","post",gPhp[2],false,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
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
    strsel = "<select class='selectbox03' id='cms_sch_date' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_hour'>\n";
    for(var i=0; i<31; i++){
        strsel += "<option value='";
        strsel += i+1;
        strsel += "'>";
        strsel += i+1;
        strsel += "</option>\n";
    }
    strsel += "</select>\n"
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
    strsel += "</select>\n"
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
    strsel += "</select>\n"
    seltimemin.innerHTML=strsel;    
}
//=======================================================//
// Show information
//=======================================================//
function show_xml_list(arr)
{
	//debug("show_xml_list");
	//var info=gXmlInfo;
	var info=arr;
	//debug(info[0]['NAME']);

	var _table=new Array();
	for(var i=0;info[i];i++)
	{
		_table[i]="<table width='670' border='0' cellspacing='0' cellpadding='0' id='idSetList"+i+"'><tr>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'></td>"
			+"<td width='130' bgcolor='#f5f5f7' class='m_gray_04' style='padding:0 0 0 20px;'>"
			+"<input type='checkbox' name='chkBox' id='idSetList_chkB_"+i+"' value='test'/>"
			+"<a href='#' onclick='open_edit_table("+i+");'>"
			+info[i]['NAME']
			+"</a>"
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"<td width='200' style='padding:0 0 0 20px;' class='m_gray_04'>"
			+info[i]['DESCRIPTION']
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"<td width='140' bgcolor='#f5f5f7' class='m_gray_04' style='padding:0 0 0 20px;'>"
			+info[i]['SRCPATH']
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"<td width='120' style='padding:0 0 0 20px;' class='m_gray_04'>"
			+info[i]['CYCLE']
			+"</td>"
			+"<td width='1' height='25' bgcolor='#e3e3e3'/>"
			+"</tr>"
			+"<tr><td height='1' bgcolor='#e3e3e3'></td></tr>"
			+"</table>";
		if(i==0)
		{
			var _table_total=_table[i];
		}else
		{
			_table_total+=_table[i];
		}
	}
			
	var _table_frame="<table width='670' border='0' cellspacing='0' cellpadding='0'><tr><td height='1' bgcolor='#e3e3e3'></td></tr>"
					+_table_total
					+"<tr><td height='1' bgcolor='#e3e3e3'></td></tr></table>";
		
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
	if(cnt==0) alert(gWngMsg[0]);
	if(cnt>1) alert(gWngMsg[1]);
	return -1;
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
	
    var cmd;
    cmd = "&act=new&task="+taskname;
    
    cmd += "&user="+document.getElementById("cms_user").value;
    cmd += "&name="+document.getElementById("cms_name").value;
    cmd += "&desc="+document.getElementById("cms_description").value;
    cmd += "&ctrlnum="+document.getElementById("cms_ctrlnum").value;
    cmd += "&srcdef="+srcdef;
    cmd += "&srcpath="+document.getElementById("cms_source").value;
    cmd += "&dstpath="+document.getElementById("cms_dest").value;
    cmd += "&include="+document.getElementById("cms_filter_include").value;
    cmd += "&exclude="+document.getElementById("cms_filter_exclude").value;
    
    var obj;
    
    // 생성될 폴더의 방법을 얻는다(파일 날짜/백업 날짜)
    obj=document.getElementById("cms_crtfld_filedate");
    if(obj.checked){
        cmd += "&crtfld_date=file";
    }else{
        cmd += "&crtfld_date=backup";
    }
    
    obj=document.getElementById("cms_sch_cycle");
    cmd += "&cycle="+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_sch_date");
    cmd += "&date="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_week");
    cmd += "&week="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_hour");
    cmd += "&time="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_min");
    cmd += ":"+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_usbatt");
    if(obj.checked){
        cmd += "&usbatt=1";
    }else{
        cmd += "&usbatt=0";
    }
    
    // 동기화 방햑을 설정한다.(incre 를 기본으로한다)
    obj=document.getElementById("cms_direc_full");
    if(obj.checked){
        cmd += "&direc=full";
    }else{
        obj=document.getElementById("cms_direc_copy");
        if(obj.checked){
            cmd += "&direc=copy";
        }else{
            obj=document.getElementById("cms_direc_one");
            if(obj.checked){
                cmd += "&direc=one";
            }else{
                cmd += "&direc=incre";
            }
        }
    }
    fStat=gStat[1];
    sendRequest(on_3, cmd, "post", gPhp[2],true,true);
}
function on_3(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	open_task_list();
	fStat=gStat[0];
}
//=======================================================//
// Do backup task
//=======================================================//}
function backup_task()
{
    //debug("test");
	var i=get_task_chk();
	if(i==-1) return false;
	fStat=gStat[4];
	var cmd="&task_number="+i;
	//debug(cmd);
	sendRequest(on_4,cmd,"post",gPhp[1],true,true);
}
function on_4(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	fStat=gStat[0];
}
//=======================================================//
// Edit task
//=======================================================//
function open_edit_table(index)
{
	//debug("open_edit_table : "+index);
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
	//debug("edit_task : "+gSelectedTaskNumber);
	var task_number=gSelectedTaskNumber;
	if(fStat==gStat[3]&&task_number!=null)
	{
		var task_name=gXmlInfo[task_number]['NAME'];
		//debug(task_name);
		modify_task(task_name);
	}
}
function modify_task(taskname)
{
    var cmd;
    cmd = "&act=modify&task="+taskname;
    
    cmd += "&user="+document.getElementById("cms_user").value;
    cmd += "&name="+document.getElementById("cms_name").value;
    cmd += "&desc="+document.getElementById("cms_description").value;
    cmd += "&ctrlnum="+document.getElementById("cms_ctrlnum").value;
    cmd += "&srcdef="+srcdef;
    cmd += "&srcpath="+document.getElementById("cms_source").value;
    cmd += "&dstpath="+document.getElementById("cms_dest").value;
    cmd += "&include="+document.getElementById("cms_filter_include").value;
    cmd += "&exclude="+document.getElementById("cms_filter_exclude").value;
    
    var obj;
    
    // 생성될 폴더의 방법을 얻는다(파일 날짜/백업 날짜)
    obj=document.getElementById("cms_crtfld_filedate");
    if(obj.checked){
        cmd += "&crtfld_date=file";
    }else{
        cmd += "&crtfld_date=backup";
    }
    
    obj=document.getElementById("cms_sch_cycle");
    cmd += "&cycle="+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_sch_date");
    cmd += "&date="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_week");
    cmd += "&week="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_hour");
    cmd += "&time="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_min");
    cmd += ":"+obj.options[obj.selectedIndex].value;
    
    obj=document.getElementById("cms_usbatt");
    if(obj.checked){
        cmd += "&usbatt=1";
    }else{
        cmd += "&usbatt=0";
    }
    
    // 동기화 방햑을 설정한다.(incre 를 기본으로한다)
    obj=document.getElementById("cms_direc_full");
    if(obj.checked){
        cmd += "&direc=full";
    }else{
        obj=document.getElementById("cms_direc_copy");
        if(obj.checked){
            cmd += "&direc=copy";
        }else{
            obj=document.getElementById("cms_direc_one");
            if(obj.checked){
                cmd += "&direc=one";
            }else{
                cmd += "&direc=incre";
            }
        }
    }
    sendRequest(on_5, cmd, "post", gPhp[2],true,true);
}
function on_5(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	open_task_list();
	fStat=gStat[0];
}
//=======================================================//
// Save button
//=======================================================//
function save_task()
{
	//debug(fStat)
	if(fStat==gStat[1])
	{
		create_task();
	}else if(fStat==gStat[3])
	{
		edit_task();
	}else
	{
		alert("Save task error!");
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
	var win = window.open('../popup/browsing_pop_01.php','DIR_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=500px,height=500px'); 
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

	//확장자의 오른쪽 끝에 구분자가 없으면 삽입니다.
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
		//사진 확장자가 이전에 포함되지 않았을 경우 추가한다.
		for (i = 0; i < extsplit.length; i++) 
		{
			isfind = false;
			for(j=0; j<oldsplit.length; j++)
			{
				//이전 확장자에 포함된 경우..
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
		//기존 확장자를 사진 확장자와 비교하여 사진확장자일 경우 제거한다.
		for(j=0; j<oldsplit.length; j++)
		{
			isfind = false;
			for (i = 0; i < extsplit.length; i++) 
			{
				//이전 확장자에 포함된 경우..
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

	//확장자의 오른쪽 끝에 구분자가 없으면 삽입니다.
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
		//사진 확장자가 이전에 포함되지 않았을 경우 추가한다.
		for (i = 0; i < extsplit.length; i++) 
		{
			isfind = false;
			for(j=0; j<oldsplit.length; j++)
			{
				//이전 확장자에 포함된 경우..
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
		//기존 확장자를 사진 확장자와 비교하여 사진확장자일 경우 제거한다.
		for(j=0; j<oldsplit.length; j++)
		{
			isfind = false;
			for (i = 0; i < extsplit.length; i++) 
			{
				//이전 확장자에 포함된 경우..
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
    document.getElementById("cms_ctrlnum").value = get_defval(info['CTRLNUM']);
    document.getElementById("cms_description").value = get_defval(info['DESCRIPTION']);
    document.getElementById("cms_source").value = get_defval(info['SRCPATH']);
    document.getElementById("cms_dest").value = get_defval(info['DSTPATH']);
    document.getElementById("cms_filter_include").value = get_defval(info['INCLUDE']);
    document.getElementById("cms_filter_exclude").value = get_defval(info['EXCLUDE']);
    
    var objcycle=document.getElementById("cms_sch_cycle");
    var strcycle=get_defval(info['CYCLE']);
    switch(strcycle.toLowerCase()){
    case 'daily':
        objcycle.options[1].selected=true;
        break;
    case 'weekly':
        objcycle.options[2].selected=true;
        break;
    case 'monthly':
        objcycle.options[3].selected=true;
        break;
    default:
        objcycle.options[0].selected=true;
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
    
    var usbatt=get_defval(info['USBATT']);
    if(usbatt == 1){
        var objusbatt=document.getElementById("cms_usbatt");
        objusbatt.checked=true;
    }
    
    var objcrtfld;
    var crtfld=get_defval(info['CRTFLD_DATE']);
    switch(crtfld.toLowerCase()){
    case 'file':
        objcrtfld=document.getElementById("cms_crtfld_filedate");
        break;
    case 'backup':
        objcrtfld=document.getElementById("cms_crtfld_backupdate");
        break;
    }
    if(objcrtfld){
        objcrtfld.checked=true;
    }
    
    var objdire;
    var strdirec=get_defval(info['DIRECTION']);
    switch(strdirec.toLowerCase()){
    case 'full':
        objdire=document.getElementById("cms_direc_full");
        break;
    case 'incre':
        objdire=document.getElementById("cms_direc_incre");
        break;
    case 'copy':
        objdire=document.getElementById("cms_direc_copy");
        break;
    case 'one':
        objdire=document.getElementById("cms_direc_one");
        break;
    }
    if(objdire){
        objdire.checked=true;
    }
}

function get_defval(obj)
{
    if(obj != undefined){
        return obj;
    }
    return "";
}
//=======================================================//
// Open Tab
//=======================================================//
function open_tab_setting()
{
	create_cancel();
	fStat=gStat[0];
}
function open_tab_restore()
{
	close_all_table();
	var cmd="block";
	document.getElementById(gIdTab[1]).style.display=cmd;
	document.getElementById(gIdTable[2]).style.display=cmd;
	fStat=gStat[5];
	browse('open', 'root');
	//lang_init();
}

//=======================================================//
// Test
//=======================================================//
//debug("schedule.js");