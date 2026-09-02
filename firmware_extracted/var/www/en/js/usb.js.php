<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>



<!--
//=======================================================//
// Init : page information
//=======================================================//
var page = {
	"name" : "usb",
	"init" : function(){
		// To do
		document.getElementById('idPathMode').value = "usb";
		var res = "<?php echo lang_get('common_loading')?>";
		document.getElementById('idListBox').innerHTML=res;
		//document.getElementById('idListBoxUsb').innerHTML=res;
		get_dev_info("usb");
		//read_xml();
	}
}


//=======================================================//
// XML file list
//=======================================================//
var gXmlList=new Array("cmssync.xml");
var gXmlRootTagName="TASK";
//=======================================================//
// XML information
//=======================================================//
var gXmlInfo=new Array();
//=======================================================//
// USB control number
//=======================================================//
var gCtrlNum=new Array();


//=======================================================//
// ID list
//=======================================================//
var gIdBox=new Array("idListBox","idListBoxUsb",'usb_select_box');
var gIdTab=new Array("idTab1","idTab2");
var gIdTable=new Array("idTableSync","idTableSetting",
"idTableUsb");
// Check box id = idSetList_chkB_i //
var gIdIn=new Array("idInSrc");	
var gIdSTitle=new Array("idSTitleCreate","idSTitleEdit");
//=======================================================//
// PHP file list
//=======================================================//
var gPhp=new Array("../php/usb_xml.php",
"../php/usb_get_dev_list.php",
"../php/usb_get_dev_num.php",
"../php/usb_set_conf.php",
"../php/usb_get_conf.php");
//=======================================================//
// Mesage list
//=======================================================//
var gWngMsg=new Array("<?php echo lang_get('schedule_msg_10')?>",
"<?php echo lang_get('schedule_msg_11')?>");
//=======================================================//
// Page status
//=======================================================//
var gStat=new Array("ready","create","delete","edit","sync",
"usb_list","usb_create");
var fStat=gStat[0];
var gSelectedTaskNumber=null;
var gSelectedUsbNumber=-1;
var gCtrl_num=null;
var gDev_name=null;

var gPreStat=new Array("ready","create","delete","edit","sync",
"usb_list","usb_create");
var PStat=gPreStat[0];


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

//=======================================================//
// XML manipulation
//=======================================================//
function read_xml()
{
	sendRequest(on_1,"&act=read","post",gPhp[0],false,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug("+"+res+"+");
	if(res=='')
	{
		//debug('aa');
		var res = "<table width='670px' border='0' cellspacing='0' cellpadding='0'><tr>"
					    +"<td class='firstCol'>" + "<?php echo lang_get('usb_sync_msg_16')?>" + "</td>"
					    +"</tr></table>";
		document.getElementById('idListBox').innerHTML = res;
		return false;
	}
	var _arr=to_array(res);
	gXmlInfo=_arr;
	show_xml_list(_arr);
	////debug(_arr[0]['INCLUDE']);
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
    strsel = "&nbsp;Day&nbsp;<select class='selectbox03' id='cms_sch_date' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_hour'>\n";
    for(var i=0; i<31; i++){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i+1;
        strsel += "</option>\n";
    }
    strsel += "</select> of every month"
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
    strsel += "</select> "
    seltimehour.innerHTML=strsel;
    
    var seltimemin=document.getElementById("cms_time_min");
    strsel = "<select class='selectbox03' id='cms_sch_min' style='WIDTH: 50px; HEIGHT: 20px' id='lng_time_min'>\n";
    for(var i=0; i<60; i +=10){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>";
    }
    strsel += "</select> "
    seltimemin.innerHTML=strsel;    
}
//=======================================================//
// Show information
//=======================================================//
function show_xml_list(arr)
{
	//debug('show_xml_list : '+arr);
	if(!arr)
	{
		var _tmp = '&nbsp;No setting';
		document.getElementById(gIdBox[0]).innerHTML = _tmp;
	}

	var info=arr;
	////debug(info[0]['NAME']);

	var _table=new Array();
	var _table_total = "";
	
	for(var i=0;info[i];i++)
	{
		_table[i]="<table width='670px' border='0' cellspacing='0' cellpadding='0' id='idSetList"+i+"'><tr>"
			
			+"<td class='firstCol_250' style='width:130px'>"
			+"<input type='radio' name='chkBox' id='idSetList_chkB_"+i+"' value='"+info[i]['NAME']+"'/>&nbsp;"
			+info[i]['NAME']
			+"</td>"
			
			+"<td class='otherCol_420' style='width:200px'>"
			+info[i]['DESCRIPTION']
			+"</td>"
			
			+"<td class='thirdCol_100' style='width:140px'>"
			+info[i]['CTRLNUM']
			+"</td>"
			
			+"<td class='otherCol_420' style='width:120px'>"
			+info[i]['CYCLE']
			+"</td>"
			
			+"</tr>"
			
			+"</table>";
		
			_table_total+=_table[i];
		
	}
			
	var _table_frame= _table_total;
					
		
	document.getElementById(gIdBox[0]).innerHTML=_table_frame;
}
//=======================================================//
// Delete a task in xml list
//=======================================================//
function delete_task(index)
{
	if( !index ){
		//debug();
		var _index = -1;
		for( var i=0;document.getElementById('idSetList_chkB_'+i);i++ ){
			if( document.getElementById('idSetList_chkB_'+i).checked ){
				_index = i;
				continue;
			}
		}
		if( !i ){
			//debug(i);
			alert("<?php echo lang_get('usb_sync_msg_5')?>");
			return false;
		}else if( _index<0 ){
			alert("<?php echo lang_get('no_settings_to_delete')?>");
			return false;
		}else{
			index = _index;
		}
	}
	
	var num = gCtrlNum[index];

	var cmd="&act=delete";
	cmd += "&ctrlnum="+num;
	fStat=gStat[2];
	sendRequest(on_2, cmd, "post", gPhp[3],true,true);
	
}
function on_2(oj)
{
	//var res=decodeURIComponent(oj.responseText);
	var res=oj.responseText;
	////debug(res);
	open_task_list();
	fStat=gStat[0];
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
	if(cnt==0) {
		alert(gWngMsg[0].replaceAll('<BR />','\n'));
	
  }
	if(cnt>1) alert(gWngMsg[1]);
	return -1;
}
//=======================================================//
// Save task : create or edit task
//=======================================================//
function save_task()
{
	if( !check_condition() ){
		return false;
	}
	////debug(fStat)
	if(fStat==gStat[1]||fStat==gStat[6])
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
// Create a task in xml list
//=======================================================//
function open_create_table()
{
	alert("<?php echo lang_get('usb_sync_msg_1')?>");
	var cmd = "&dev_type=usb";
	sendRequest(on_open_create_table,cmd,"post",gPhp[1],true,true);
	function on_open_create_table(oj){
		//var res=decodeURIComponent(oj.responseText);
		var res=oj.responseText;

		//debug(res);
		if( res=="No USB device" ){
			_msg = "<?php echo lang_get('usb_sync_msg_2')?>";
			alert(_msg.replaceAll('<BR />','\n'));
			return false;
		}
		close_adv_set();
		var _arr = to_array(res);
		show_usb_list(_arr);
		close_all_table();
		var cmd="block";
		//cms_init_select();
		//document.getElementById('input_01').style.visibility="visible";
		document.getElementById(gIdTab[1]).style.display=cmd;
		sch_tab.show_src();
		document.getElementById(gIdTable[1]).style.display=cmd;
		document.getElementById(gIdSTitle[0]).style.display=cmd;
		fStat=gStat[1];
		cms_init_taskdata(null);
		refresh_usb_select(-1);
	}
	
	
	
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
	//document.getElementById(gIdTable[2]).style.display=cmd;
	//document.getElementById(gIdSTitle[0]).style.display=cmd;
	//document.getElementById(gIdSTitle[1]).style.display=cmd;
}
function create_cancel()
{
	close_all_table();
	//show_src_path();
	sch_tab.show_src();
	var cmd="block";

	if(fStat==gStat[6] || PStat==gPreStat[1])
	{
		document.getElementById(gIdTab[0]).style.display=cmd;
		document.getElementById(gIdTable[2]).style.display=cmd;
	}
	else 	if(fStat==gStat[1]||fStat==gStat[3])
	{
		document.getElementById(gIdTab[1]).style.display=cmd;
		document.getElementById(gIdTable[0]).style.display=cmd;
	}else
	{
		alert("<?php echo lang_get('usb_sync_msg_3')?>");
		return false;
	}
	
	fStat=gStat[0];
	PStat=gPreStat[0];
	
	gSelectedTaskNumber=null;
	flag_get_control_number = false;
	/*function show_src_path(){
		document.getElementById('idSrcpath_01').height = 25;
		document.getElementById('idSrcpath').style.display = "block";
		document.getElementById('idSrcpath_02').height = 1;
	}*/
}
function create_task()
{
	////debug("create_task");
	if(!check_task())
	{
		return false;
	}

	var taskname=document.getElementById("cms_name").value;

	
	//var dstdef="/mnt/disk";




	
	var cmd;
	cmd = "&act=new&task="+taskname;

	cmd += "&user="+document.getElementById("cms_user").value;
	cmd += "&name="+document.getElementById("cms_name").value;
	cmd += "&desc="+document.getElementById("cms_description").value;
	cmd += "&ctrlnum="+document.getElementById("cms_ctrlnum").value;
	//cmd += "&dstdef="+dstdef;
	cmd += "&dstpath="+encodeURIComponent(document.getElementById("cms_dest").value);
	
	
	var obj;
	obj=document.getElementById("cms_usbatt");
	if(obj.checked){
	    cmd += "&usbatt=on";
	}else{
	    cmd += "&usbatt=off";
	}

	// 동기화 방햑을 설정한다.(incre 를 기본으로한다)
	obj=document.getElementById("cms_direc_full");
	if(obj.checked){
	    cmd += "&direc=full";
	}/*
	else{
	    obj=document.getElementById("cms_direc_copy");
	    if(obj.checked){
	        cmd += "&direc=sync";
	    }*/
	    else{
	        cmd += "&direc=incremental";            
	    }
	/*}*/
	fStat=gStat[1];
	////debug(cmd);



	sendRequest(on_3, cmd, "post", gPhp[3],true,true);
}

function on_3(oj)
{

	//var res=decodeURIComponent(oj.responseText);
	var res=oj.responseText;
	////debug(res);
	open_task_list();
	fStat=gStat[0];
}

//=======================================================//
// Advanced setting
//=======================================================//
function open_adv_set()
{
	////debug("open_adv_set");
	dis_ctl("idTableAdvSet","block");
	dis_ctl("idAdvSetOpen","none");
	dis_ctl("idAdvSetClose","block");
}
function close_adv_set()
{
	dis_ctl("idTableAdvSet","none");
	dis_ctl("idAdvSetOpen","block");
	dis_ctl("idAdvSetClose","none");
}
//=======================================================//
// Do sync task
//=======================================================//
function sync_task(){
	
	var cmd = "&dev_type=usb";
	sendRequest(on_sync_task,cmd,"post",gPhp[1],true,true);
	
	function on_sync_task(oj){
		//var res=decodeURIComponent(oj.responseText);
		var res=oj.responseText;
		debug(res);
		if( res=="No USB device" ){
			_msg = "<?php echo lang_get('usb_sync_msg_2')?>";
			alert(_msg.replaceAll('<BR />','\n'));
			return false;
		}
		
		sync_task_do();
	}
}

function sync_task_do()
{
    //debug("sync_task");
	var i=get_task_chk();
	if(i==-1) return false;
	fStat=gStat[4];
	
	/* swkim 200-11-16
	var cmd="&act=sync&task_number="+i;
	////debug(cmd);
	sendRequest(on_4,cmd,"post",gPhp[0],true,true);
	*/
	document.getElementById('id_task_number').value = i;
	//alert(document.getElementById('id_task_number').value);
	var url = "../php/usb_image_progress_pop.php";
	var newWindow = window.open(url, 'USB_SYNC','scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no,width=420px,height=251px');
	if (!newWindow){
		alert("<?php echo lang_get('usb_sync_msg_4')?>");
		return false;
	}
	document.getElementById('id_btn_sync').disabled = true;
	
	return true;	
}

function on_4(oj)
{
	//var res=decodeURIComponent(oj.responseText);
	var res=oj.responseText;
	////debug(res);
	fStat=gStat[0];
}

//=======================================================//
// Edit task
//=======================================================//
function open_edit_table(index)
{
	////debug("open_edit_table : "+index);
	if( !index ){
		//debug();
		var _index = -1;
		for( var i=0;document.getElementById('idSetList_chkB_'+i);i++ ){
			if( document.getElementById('idSetList_chkB_'+i).checked ){
				_index = i;
				continue;
			}
		}
		if( !i ){
			//debug(i);
			alert("<?php echo lang_get('usb_sync_msg_5')?>");
			return false;
		}else if( _index<0 ){
			alert("<?php echo lang_get('schedule_msg_9')?>");
			return false;
		}else{
			index = _index;
		}
	}
	close_adv_set();
	close_all_table();
	var cmd="block";
	//cms_init_select();
	//document.getElementById('input_01').style.visibility="hidden";
	document.getElementById(gIdTab[1]).style.display=cmd;
	//hide_src_path();
	sch_tab.hide_src();
	document.getElementById(gIdTable[1]).style.display=cmd;
	document.getElementById(gIdSTitle[1]).style.display=cmd;
	fStat=gStat[3];

	var num = gCtrlNum[index];

	var type="one";	
	get_usb_backup_list(type,num);

}
function edit_task()
{
	//debug("edit_task : "+gSelectedTaskNumber);
	//if(!check_task())
	//{
	//	return false;
	//}

	if(fStat==gStat[3])
	{
		modify_task();
	}
}
function modify_task()
{
    //var dstdef="/mnt/disk";
    var cmd;
    cmd = "&act=modify";
    
    cmd += "&name="+document.getElementById("cms_name").value;
    cmd += "&desc="+document.getElementById("cms_description").value;
    cmd += "&ctrlnum="+document.getElementById('cms_ctrlnum').value;
    //cmd += "&srcdef="+srcdef;
    //cmd += "&srcpath="+document.getElementById("cms_source").value;
    //cmd += "&dstdef="+dstdef;
    //cmd += "&dstpath="+document.getElementById('cms_dest').value;
    cmd += "&dstpath="+encodeURIComponent(document.getElementById("cms_dest").value);

    //cmd += "&include="+document.getElementById("cms_filter_include").value;
    //cmd += "&exclude="+document.getElementById("cms_filter_exclude").value;
    
    var obj;
    obj=document.getElementById("cms_usbatt");
    if(obj.checked){
        cmd += "&usbatt=on";
    }else{
        cmd += "&usbatt=off";
    }
    /*
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
        cmd += "&usbatt=on";
    }else{
        cmd += "&usbatt=off";
    }
    */
    // 동기화 방햑을 설정한다.(incre 를 기본으로한다)
    obj=document.getElementById("cms_direc_full");
    if(obj.checked){
        cmd += "&direc=full";
    }/*
    else{
        obj=document.getElementById("cms_direc_copy");
        if(obj.checked){
            cmd += "&direc=sync";
        }*/
        else{
            cmd += "&direc=incremental";            
        /*}*/
    }

    sendRequest(on_5, cmd, "post", gPhp[3],true,true);
    
    //sendRequest(on_5, cmd, "post", gPhp[0],true,true);
}
function on_5(oj)
{
	//var res=decodeURIComponent(oj.responseText);
	var res=oj.responseText;
	//debug(res);
	open_task_list();
	fStat=gStat[0];
}

//=======================================================//
// Open task list
//=======================================================//
function open_task_list()
{
	close_all_table();
	var cmd="block";
	
	var type="all";	
	get_usb_backup_list(type,'');

	document.getElementById(gIdTab[1]).style.display=cmd;
	document.getElementById(gIdTable[0]).style.display=cmd;
	fStat=gStat[0];


}

//=======================================================//
// Get usb backup list 
//=======================================================//
function get_usb_backup_list(type,num)
{	
	var ctrl_num;
       if(type == "all")
       {
       	var cmd ='&act='+type;
       	sendRequest(on_usb_backup_list,cmd,"post",gPhp[4],false,true);
	}
	else 
	{
		var cmd ='&act='+type+'&ctrl_num='+num;
	 	sendRequest(on_usb_backup_edit,cmd,"post",gPhp[4],false,true);
	}
}

function on_usb_backup_list(oj)
{
	//var res=decodeURIComponent(oj.responseText);
	var res=oj.responseText;

	var _items = res.split('||');

	if(_items[0] != 'error')
	{	
		//Get name array
		var namelist = _items[0].split("name:");	
		
		//Get description array
		var descriptlist = _items[1].split("descript:");
		
		//Get destination array
		var destination = _items[2].split("dest:");
		
		//Get control_num array
		var control_num = _items[3].split(' ');
		
		//Get auto_sync array
		var auto_sync = _items[4].split(' ');	   
		
		//Get method array
		var methodlist = _items[5].split(' ');

		var _table=new Array();
		var _table_total = "";

/*		for(var i=0;namelist[i+1] ;i++ ){
			_table[i]="<table width='670px' border='0' style='table-layout:fixed;' cellspacing='0' cellpadding='0' id='idSetList"+i+"'><tr>"
				
				+"<td class='firstCol_250' height='hidden' style='table-layout:fixed;width:50px'>"
				+"<input type='radio' name='chkBox' id='idSetList_chkB_"+i+"' value='"+namelist[i+1]+"'/>&nbsp;"
				+namelist[i+1]
				+"</td>"
				
				+"<td class='otherCol_420' style='width:200px'>"
				+descriptlist[i+1]
				+"</td>"
				
				+"<td class='thirdCol_100' style='width:90px'>"
				+methodlist[i]
				+"</td>"
				
				+"<td class='otherCol_420' style='width:30px' align='left'>"
				+auto_sync[i]
				+"</td>"
				
				+"</tr>"
				
				+"</table>";
			
				_table_total+=_table[i];
				gCtrlNum[i] = control_num[i];
		}
*/
		for(var i=0;namelist[i+1] ;i++ ){
			_table[i]="<table style='table-layout:fixed;' width='670px' border='0' cellspacing='0' cellpadding='0' id='idSetList"+i+"'><tr>"
				
				+"<td class='firstCol_250' style='width:170px' >"
				+"<input type='radio' name='chkBox' id='idSetList_chkB_"+i+"' value='"+namelist[i+1]+"'/>&nbsp;"
				+namelist[i+1]
				+"</td>"
				
				+"<td class='otherCol_420' style='width:220px'>"
				+descriptlist[i+1]
				+"</td>"
				
				+"<td class='thirdCol_100' width='120px'>"
				+methodlist[i]
				+"</td>"
				
				+"<td class='otherCol_420' style='font-weight:bolder;width:100px' align='center' >"
				+auto_sync[i]
				+"</td>"
				
				+"</tr>"
				
				+"</table>";
			
				_table_total+=_table[i];
				gCtrlNum[i] = control_num[i];
		}

		var _table_frame= _table_total;	
		document.getElementById(gIdBox[0]).innerHTML=_table_frame;
	}
	else
	{
		//debug('aa');
		var res = "<table width='670px' border='0' cellspacing='0' cellpadding='0'><tr>"
					    +"<td class='firstCol'>" + "<?php echo lang_get('usb_backup_msg_1')?>" + "</td>"
					    +"</tr></table>";
		document.getElementById('idListBox').innerHTML = res;
		return false;
	}
}

function on_usb_backup_edit(oj)
{
	var res=decodeURIComponent(oj.responseText);

	var _item = res.split('||');	
 	var name= _item[0].split("name:");
	var descript= _item[1].split("descript:");
	var destination = _item[2].split("dest:");
	
	
	var control_num = _item[3];
	var auto_backup = _item[4];
	var method = _item[5];
	
       document.getElementById("cms_name").value = name[1];
       document.getElementById("cms_ctrlnum").value = control_num;
       document.getElementById("cms_description").value = descript[1];
       document.getElementById("cms_dest").value = destination[1];
	

	if(auto_backup == 'on'){
       	var objusbatt=document.getElementById("cms_usbatt");
       	objusbatt.checked=true;
    	}
    	else
    	{
    		var objusbatt=document.getElementById("cms_usbatt");
       	objusbatt.checked=false;
    	}
   
	var objdire;
	method
	switch(method){
	case 'full':
	    objdire=document.getElementById("cms_direc_full");
	    break;
	case 'incremental':
	    objdire=document.getElementById("cms_direc_incre");
	    break;
	case 'sync':
	    objdire=document.getElementById("cms_direc_copy");
	    break;
	default:
	    objdire=document.getElementById("cms_direc_incre");
	    break;
	}
	if(objdire){
	    objdire.checked=true;
	}

}


//=======================================================//
// Open USB list
//=======================================================//
var usbList = {
	"state" : "close",
	"open" : function(){
		flag_get_control_number = false;
		
		close_all_table();
		var cmd="block";
		//read_xml();
		document.getElementById("idTab1").style.display=cmd;
		document.getElementById("idTableUsb").style.display=cmd;
		fStat="usb_list";
		var res = "<table width='670px' border='0' cellspacing='0' cellpadding='0'><tr>"
					    +"<td class='firstCol'>" + "<?php echo lang_get('common_loading')?>" + "</td>"
					    +"</tr></table>";
		document.getElementById('idListBox').innerHTML=res;
		document.getElementById('idListBoxUsb').innerHTML=res;
		get_dev_info("usb");
	}
}
function open_usb_list()
{
	close_all_table();
	var cmd="block";
	//read_xml();
	document.getElementById(gIdTab[0]).style.display=cmd;
	document.getElementById(gIdTable[2]).style.display=cmd;
	fStat=gStat[5];
}
//=======================================================//
// Open popup window for folder selection
//=======================================================//
function popup_file_browser(id)
{
	//debug(document.getElementById('idPathMode').value);
	document.getElementById("idInputFieldId").value=id;
	var win = window.open('usb_pop_brows.php','DIR_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
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
    //document.getElementById("cms_source").value = get_defval(info['SRCPATH']);
    document.getElementById("cms_dest").value = get_defval(info['DSTPATH']);

    
    //document.getElementById("cms_filter_include").value = get_defval(info['INCLUDE']);
    //document.getElementById("cms_filter_exclude").value = get_defval(info['EXCLUDE']);
    //var usbatt=get_defval(info['USBATT']);
    var usbatt=0;
    if(usbatt == 1){
        var objusbatt=document.getElementById("cms_usbatt");
        objusbatt.checked=true;
    }
    else{
        var objusbatt=document.getElementById("cms_usbatt");
        objusbatt.checked=false;    
    }
    
    /*
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
    

    
    var objcrtfld;
    var crtfld=get_defval(info['CRTFLD_DATE']);
    switch(crtfld.toLowerCase()){
    case 'file':
        objcrtfld=document.getElementById("cms_crtfld_filedate");
        break;
    case 'backup':
        objcrtfld=document.getElementById("cms_crtfld_backupdate");
        break;
    default:
        objcrtfld=document.getElementById("cms_crtfld_backupdate");
        break;
    }
    if(objcrtfld){
        objcrtfld.checked=true;
    }
    */
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
	default:
        objdire=document.getElementById("cms_direc_incre");
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
// Check functions
//=======================================================//
function check_task()
{
	if(check_is_task_name())
	{
		var task_name = document.getElementById('cms_name').value;
		if(check_same_name(task_name,gSelectedTaskNumber))
		{
			_msg = "<?php echo lang_get('usb_sync_msg_6')?>";
			alert(_msg.replaceAll('<BR />','\n'));
		}else
		{
			return true;
		}
	}else
	{
		_msg = "<?php echo lang_get('usb_sync_msg_7')?>";
		alert(_msg.replaceAll('<BR />','\n'));
	}
	return false;
}
function check_same_name(task_name,selected_task_number)
{
	//
	// true : same name exists.
	// false : no same name
	//
	//debug("check_same_name");
	var task_name_list = get_all_task_name();
	
	if(fStat==gStat[1]||fStat==gStat[6])
	{
		// Create : compare to all names
		for(var i=0;task_name_list[i];i++)
		{
			if(task_name==task_name_list[i]) return true;
		}
	}else if(fStat==gStat[3])
	{
		// Edit : compare to names except own name
		for(var i=0;task_name_list[i];i++)
		{
			if(i!=selected_task_number)
			{
				if(task_name==task_name_list[i]) return true;
			}
		}
	}else
	{
		// Error
		alert("<?php echo lang_get('usb_sync_msg_8')?>");
	}
	return false;
}
function get_all_task_name()
{
	var task_names = new Array();
	var _id = "idSetList_chkB_";
	for(var i=0;document.getElementById(_id+i);i++)
	{
		////debug(document.getElementById(_id+i).value);
		task_names[i] = document.getElementById(_id+i).value;
	}
	return task_names;
}
function check_is_task_name()
{
	if(fStat==gStat[1]||fStat==gStat[3]||fStat==gStat[6])
	{
		if(document.getElementById('cms_name').value) return true;
		return false;
	}else
	{
		alert("<?php echo lang_get('usb_sync_msg_9')?>");
	}
}


//=======================================================//
// USB list
//=======================================================//
function get_dev_info(dev_type)
{
	////debug("get_dev_info : "+dev_type);
	var cmd = "&dev_type="+dev_type;
	sendRequest(on_get_dev_info,cmd,"post",gPhp[1],true,true);
}
function on_get_dev_info(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	if(res=="No USB device")
	{ 
		var result = "<table width='670px' border='0' cellspacing='0' cellpadding='0'><tr>"
					    	+"<td class='firstCol'>" + "<?php echo lang_get('usb_sync_13')?>" + "</td>"
					    	+"</tr></table>";
		//document.getElementById('idListBox').innerHTML=res;
		document.getElementById('idListBoxUsb').innerHTML=result;
		return false;
	}
	var arr = to_array(res);
	////debug(arr);
	show_usb_list(arr);
}
function show_usb_list(arr)
{
	////debug("@show_usb_list, "+arr);
	var info=arr;

	var _table=new Array();
	var _table_total = "";

	//juny : 090318 => changed : array's index	
	for(var i=0;info[i];i++)
	{
		//check whether usb is registered 
		//var usb_num = document.getElementById('idUsb_chk_'+i).value;
		//alert(usb_num);
		//var index = i+1;
		//var cmd = "&usb_num="+"usb"+index;
		//sendRequest(on_7,cmd,"post",gPhp[2],true,true);


  		var tmp = info[i][0].split(" ");
		var name= tmp[0].toUpperCase();

		_table[i]="<table style='table-layout:fixed;' width='670px' border='0' cellspacing='0' cellpadding='0' id='idUsbList"+i+"'><tr>"
			
			+"<td class='firstCol_250' style='width:190px'>"
			+"<input type='radio' name='chkBox' id='idUsb_chk_"+i+"' value='"+name+"'/>"
			+name
			+"&nbsp;</td>"
			
			+"<td class='otherCol_420' style='width:240px'>"
			+info[i][1]
			+"&nbsp;</td>"
			
			+"<td class='thirdCol_100' style='width:160px'>"
			+info[i][2]
			+"&nbsp;</td>"
			
			+"</tr>"
			
			+"</table>";
		
			_table_total+=_table[i];
		
	}
			
	var _table_frame=_table_total;
					
		
	document.getElementById(gIdBox[1]).innerHTML=_table_frame;
}
//=======================================================//
// USB : create initial setting
//=======================================================//
function open_create_table_usb()
{
	close_adv_set();
	fStat = gStat[6];
	var i=get_task_chk_usb();
	if(i==-1) return false;
	gSelectedUsbNumber = i;
	var usb_num = document.getElementById('idUsb_chk_'+i).value;
	usb_num = usb_num.toLowerCase();
	var act = 'get_info';
	var cmd = "&usb_num="+usb_num
	                "&act="+act;
	sendRequest(on_7,cmd,"post",gPhp[2],true,true);
	refresh_usb_select(i);
}
function on_7(oj)
{
	var res=decodeURIComponent(oj.responseText);
	close_all_table();
	//cms_init_select();
	cms_init_taskdata(null);
	
	var item = new Array();
	item = res.split(':');
	gCtrl_num=item[0]; gDev_name=item[1];

	//Check whether usb is already registered
	var act = 'check_registered';
      	var cmd ='&act='+act
      	               +'&ctrl_num='+gCtrl_num;

       sendRequest(on_check_usb_conf,cmd,"post",gPhp[2],false,true);
		
}

function on_check_usb_conf(oj)
{
	var res=decodeURIComponent(oj.responseText);

	if(res.match('Already Registered')){
		var result=confirm("<?php echo lang_get('already_resitered_usb')?>"); 
		if(result)
		{
			close_adv_set();
			close_all_table();
			var cmd="block";
			//document.getElementById('input_01').style.visibility="hidden";
			document.getElementById(gIdTab[1]).style.display=cmd;
			sch_tab.hide_src();
			document.getElementById(gIdTable[1]).style.display=cmd;
			document.getElementById(gIdSTitle[1]).style.display=cmd;
			fStat=gStat[3];

			var type="one";
			get_usb_backup_list('one',gCtrl_num);

			//Store previous stats	
			PStat=gPreStat[1];
			return;
		}
		else	
		{
			fStat=gStat[6];			
			create_cancel();			
			return;
		}		
	}
	var cmd="block";
	init_taskdata_usb(gCtrl_num,gDev_name);

	//document.getElementById('input_01').style.visibility="visible";
	document.getElementById(gIdTab[0]).style.display=cmd;
	sch_tab.show_src();
	document.getElementById(gIdTable[1]).style.display=cmd;
	document.getElementById(gIdSTitle[0]).style.display=cmd;
	fStat=gStat[6];
}




function init_taskdata_usb(ctrl_num,name)
{
	//debug(ctrl_num);
	document.getElementById("cms_name").value = name;	
	document.getElementById("cms_ctrlnum").value = ctrl_num;
	//var src_name = document.getElementById('idUsb_chk_'+gSelectedUsbNumber).value;
	//document.getElementById("cms_source").value = src_name;

	var cmd = "&dev_type="+"usb_path";
	sendRequest(on_get_path,cmd,"post",gPhp[1],true,true);

}

function on_get_path(oj)
{
	var res=decodeURIComponent(oj.responseText);
	
	document.getElementById("cms_dest").value = res;
	flag_get_control_number = false;
}


function get_task_chk_usb()
{
	var cnt=0;
	for(var i=0;document.getElementById('idUsb_chk_'+i);i++)
	{
		if(document.getElementById('idUsb_chk_'+i).checked==true)
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
//=======================================================//
// Refresh USB select box in setting table
//=======================================================//
var usb_list = {
	"list" : [],
	refresh : function(){
		this.get_usb_list('usb');
	},
	select_box_id : 'usb_select_box',
	make_select_box : function(mode){
		var _select = "<select class='selectbox03' id='usb_select_box_select' style='width:105px;height:20px;' onchange='usb_list.select();'>";
		if(mode=='no_dev'){
			_select += "<option><?php echo lang_get('usb_sync_13')?></option>";
		}else{
			_select += "<option>---</option>";
			for(var i=0;this.list[i];i++)
			{
				var _dev = this.list[i][0].split(' ');
				var _dev_name = _dev[1];
				_select += "<option value='idUsb_chk_"+i+"'>"+_dev_name+"</option>";
			}
		}
		_select += "</select>";
		
		document.getElementById(this.select_box_id).innerHTML = _select;
	},
	get_usb_list : function(dev_type){
		var cmd = "&dev_type="+dev_type;
		var _php = '../php/usb_get_dev_list.php';
		sendRequest(on_get_usb_list,cmd,"post",_php,true,true);
		
		function on_get_usb_list(oj){
			var res=decodeURIComponent(oj.responseText);
			debug('usb info => '+res);
			if(res.match(/no usb device/i)){
				debug('no external device');
				usb_list.make_select_box('no_dev');
				return;
			}
			usb_list.list = to_array(res);
			//debug('list => '+usb_list['list'][1]);
			usb_list.make_select_box();
		}
	},
	select : function(){
		var i = document.getElementById('usb_select_box_select').selectedIndex;
		if(i==0){
			document.getElementById("cms_ctrlnum").value = ' ';
			document.getElementById("cms_dest").value = ' ';
			return false;
		}
		var dev_name = this.list[i-1][0].split(' ');
		dev_name = dev_name[1];
		get_control_number(dev_name);
	}
}


function refresh_usb_select(selected_dev_index)
{
	
	//if( selected_dev_index == '' ) selected_dev_index = -1;
	if( selected_dev_index < 0 ){
		// No USB device selected
		var _list = new Array();
		var _list_id = new Array();
		for(var i=0;document.getElementById('idUsb_chk_'+i);i++)
		{
			_list[i] = document.getElementById('idUsb_chk_'+i).value;
			_list_id[i] = 'idUsb_chk_'+i;
		}
		var usb_list = null;
		var _select = "<select class='selectbox03' id='usb_select_box_select' style='width:105px;height:20px;' onchange='select_usb_dev();'>";
		_select += "<option>---</option>";
		for(var i=0;_list[i];i++)
		{
			if(i==selected_dev_index)
			{
				_select += "<option value='"+_list_id[i]+"' selected>"+_list[i]+"</option>";
			}else
			{
				_select += "<option value='"+_list_id[i]+"'>"+_list[i]+"</option>";
			}
		}
		_select += "</select>";
		//document.getElementById('id_refresh_btn').style.display = "block";
	}else{
		// USB device selected
		var _select = document.getElementById('idUsb_chk_'+selected_dev_index).value;
		//document.getElementById('id_refresh_btn').style.display = "none";
	}
	//document.getElementById('usb_select_box').innerHTML = _select;
}
function select_usb_dev()
{
	//debug('select_usb_dev');
	var _oj = document.getElementById('usb_select_box_select');
	var _sel = _oj.selectedIndex;
	if(_sel==0)
	{
		document.getElementById("cms_ctrlnum").value = ' ';
		document.getElementById("cms_dest").value = ' ';
		return false;
	}
	var _sel_value = _oj.options[_sel].value;
	var tmp = document.getElementById(_sel_value).value;
	//debug(_sel_value+"\n"+tmp);
	get_control_number(tmp);
}
//=======================================================//
// Get device control number
//=======================================================//
var flag_get_control_number = false;
function get_control_number(dev_name)
{
	//debug(dev_name);
	flag_get_control_number = true;
	document.getElementById("cms_ctrlnum").value = "<?php echo lang_get('common_loading')?>";
	document.getElementById("cms_dest").value = "<?php echo lang_get('common_loading')?>";
	var cmd = "&usb_num="+dev_name;
	var php = '../php/usb_get_dev_num.php';
	sendRequest(on_get_control_number,cmd,"post",php,true,true);
}
function on_get_control_number(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	if(res=='')
	{
		var _msg = "<?php echo lang_get('usb_sync_msg_2')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		document.getElementById("cms_ctrlnum").value = ' ';
		document.getElementById("cms_dest").value = ' ';
		return false;
	}
	init_taskdata_usb(res,'');
}
//=======================================================//
// Backup method check
//=======================================================//
function check_incremental()
{
	var _tmp = document.getElementById('cms_direc_incre').checked;
	var _disabled = true;
	if(_tmp == true) _disabled = false;
	//document.getElementById('cms_crtfld_filedate').disabled = _disabled;
	//document.getElementById('cms_crtfld_backupdate').disabled = _disabled;
}
//=======================================================//
// Check condition before task save
//=======================================================//
function check_condition(){
	if(flag_get_control_number){
		alert("<?php echo lang_get('schedule_msg_11')?>");
		return false;
	}
	var _cnt = 0;
	var _msg = "";
	if( document.getElementById('cms_name').value == "" ){
		_msg += "<?php echo lang_get('schedule_msg_6')?>\n";
		_cnt++;
	}
	if( document.getElementById('cms_description').value == "" ){
		_msg += "<?php echo lang_get('schedule_msg_7')?>\n";
		_cnt++;
	}
	if( document.getElementById('cms_ctrlnum').value == "" ){
		_msg += "<?php echo lang_get('usb_sync_msg_14')?>\n";
		_cnt++;
	}	
	if( document.getElementById('cms_dest').value == "" ){
		_msg += "<?php echo lang_get('usb_sync_msg_17')?>\n";
		_cnt++;
	}
	if( _cnt>0 ){
		alert(_msg+"<?php echo lang_get('usb_sync_msg_15')?>");
		return false;
	}
	return true;
}


function setDetail(){
	
	var sch_cycle = document.getElementById('cms_sch_cycle').value;
	
	document.getElementById('cms_date').style.display = "none";
	document.getElementById('cms_day').style.display = "none";
  
  if(sch_cycle == 'weekly'){
	  document.getElementById('cms_day').style.display = "block";
	}
	else if(sch_cycle == 'monthly'){
		document.getElementById('cms_date').style.display = "block";
	}
	
}
//=======================================================//
// Control backup schedule table
//=======================================================//
var sch_tab = {
	"id" : ["idSrcpath_01","idSrcpath","idSrcpath_02"],
	"hide_src" : function(){
		//document.getElementById(this.id[0]).height = 1;
		//document.getElementById(this.id[1]).style.display = "none";
		//document.getElementById(this.id[2]).height = 1;
	},
	"show_src" : function(){
		//document.getElementById(this.id[0]).height = 25;
		//document.getElementById(this.id[1]).style.display = "block";
		//document.getElementById(this.id[2]).height = 1;
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
		var _win = window.open('../help/mobile/help_usb.html#list','Help_usb','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/mobile/help_usb.html#setting','Help_usb','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;

		default:
		break;
	}
}
//-->
