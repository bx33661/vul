<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>



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
// ID list
//=======================================================//
var gIdBox=new Array("idListBox","idListBoxUsb",'usb_select_box');
var gIdTab=new Array("idTab1","idTab2");
var gIdTable=new Array("mobilew_step1","mobilew_step2","mobilew_step3");
// Check box id = idSetList_chkB_i //
var gIdIn=new Array("idInSrc");	
var gIdSTitle=new Array("idSTitleCreate","idSTitleEdit");
//=======================================================//
// PHP file list
//=======================================================//
var gPhp=new Array("../php/usb_xml.php",
"../php/usb_get_dev_list.php",
"../php/usb_get_dev_num.php");
//=======================================================//
// Mesage list
//=======================================================//
var gWngMsg=new Array("<?php echo lang_get('schedule_msg_24')?>",
"Select a task.");

var gSyncInfo = new SyncInfo('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');

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

//========================================================//
// Data type
//========================================================//
function SyncInfo(user,name,desc,ctrlnum,source,dstpath,include,exclude,crtfld_date,cycle,date,week,time,usbatt,direc,showCycle,showDirec)
{
	this.user 	   	= user;
	this.name  		  = name;
	this.desc 		  = desc;
	this.ctrlnum 		= ctrlnum;
	this.source     = source;
	this.dstpath 		= dstpath;
	this.include 		= include;
	this.exclude 		= exclude;
	this.crtfld_date 	= crtfld_date;
	this.cycle 		  = cycle;
	this.date 		  = date;
	this.week 	    = week;
	this.time 	    = time;
	this.usbatt     = usbatt;
	this.direc      = direc;
	this.showCycle = showCycle;  // Variable to show the information of Step 3
	this.showDirec = showDirec;  // Variable to show the information of Step 3
}

var XML_result;

function $(obj){
	return document.getElementById(obj);
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
	XML_result = decodeURIComponent(oj.responseText);
}

//=======================================================//
// Save task 
//=======================================================//
function save_task()
{
	// Name & Description Black Check
	if( !check_condition() )	return false;
	
	// Duplicate Name Check
	if(check_same_name()) return false;
	
	/* get Information From Step2 */
    gSyncInfo.user        = $('cms_user').value;
		gSyncInfo.name  		  = $('cms_name').value;
		gSyncInfo.desc 		    = $('cms_description').value;
		gSyncInfo.ctrlnum 		= $('cms_ctrlnum').value;
		gSyncInfo.dstpath 		= $('cms_dest').value;
		gSyncInfo.include 		= $('cms_filter_include').value;
		gSyncInfo.exclude 		= $('cms_filter_exclude').value;
		gSyncInfo.source 		= $('usb_select_box').innerHTML;
	
		
		if($('cms_crtfld_filedate').checked){
        gSyncInfo.crtfld_date = "file";
    }else{
        gSyncInfo.crtfld_date= "backup";
    }
    
		

		// InnerText supports only IE 
		// So Check the browser and define prototype for FF
		if (navigator.appName != "Microsoft Internet Explorer"){ 
			HTMLElement.prototype.__defineGetter__("innerText", function() { return this.textContent; }); 
			HTMLElement.prototype.__defineSetter__("innerText", function(txt) { this.textContent = txt; }); 
		} 
		
		var obj=document.cms_sch.name_direc;
    
    for (var i=0;i<obj.length;i++) { 
			if (obj[i].checked == true) { 
 				 gSyncInfo.direc = obj[i].value;
 				 gSyncInfo.showDirec = obj[i].nextSibling.innerText;

  		}
		}
				 
		
		gSyncInfo.date 		    = $('cms_sch_date').options[$('cms_sch_date').selectedIndex].value;
		gSyncInfo.week 	      = $('cms_sch_week').options[$('cms_sch_week').selectedIndex].value;
		gSyncInfo.time 	      = $('cms_sch_hour').options[$('cms_sch_hour').selectedIndex].value;
		     gSyncInfo.time  += ":"+$('cms_sch_min').options[$('cms_sch_min').selectedIndex].value;;
		

		if($('cms_usbatt').checked){
        gSyncInfo.usbatt = "1";
    }else{
        gSyncInfo.usbatt= "0";
    }
		
		
    
		gSyncInfo.cycle 	      = $('cms_sch_cycle').options[$('cms_sch_cycle').selectedIndex].value;
		gSyncInfo.showCycle 	      = $('cms_sch_cycle').options[$('cms_sch_cycle').selectedIndex].innerText;
		
		
	  //cmd += "&srcdef="+srcdef;
    //cmd += "&srcpath="+document.getElementById("cms_source").value;
    //var srcdef="/mnt/fs";
    var dstdef="/mnt/fs";
    var cmd;

    cmd = "&act=new&task="+gSyncInfo.name;
    cmd += "&user="+gSyncInfo.user;
    cmd += "&name="+gSyncInfo.name;
    cmd += "&desc="+gSyncInfo.desc;
    cmd += "&ctrlnum="+gSyncInfo.ctrlnum;
    
    cmd += "&dstdef="+dstdef;
    cmd += "&dstpath="+gSyncInfo.dstpath;
    cmd += "&include="+gSyncInfo.include;
    cmd += "&exclude="+gSyncInfo.exclude;
   
    cmd += "&crtfld_date="+ gSyncInfo.crtfld_date;
    cmd += "&cycle="+ gSyncInfo.cycle;
    cmd += "&date="+ gSyncInfo.date;
    cmd += "&week="+ gSyncInfo.week;
    cmd += "&time="+ gSyncInfo.time;
    
    cmd += "&usbatt="+ gSyncInfo.usbatt;
    cmd += "&direc="+ gSyncInfo.direc;
    
    sendRequest(on_3, cmd, "post", gPhp[0],true,true);	
	
}

function on_3(oj)
{
	var res=decodeURIComponent(oj.responseText);
	
	showTable(gIdTable[2]);
	
	setStep3();

}

function setStep3(){
	
	  $('step3_name').innerHTML = gSyncInfo.name;
	  $('step3_desc').innerHTML = gSyncInfo.desc;
	  $('step3_ctrlnum').innerHTML = gSyncInfo.ctrlnum;
	  $('step3_dstpath').innerHTML = gSyncInfo.dstpath;
	  $('step3_include').innerHTML = gSyncInfo.include;
	  $('step3_exclude').innerHTML = gSyncInfo.exclude;
	  $('step3_source').innerHTML = gSyncInfo.source;
	  
	  $('step3_cycle').innerHTML = gSyncInfo.cycle;
	  if(gSyncInfo.cycle == 'monthly'){
	  	$('step3_cycle').innerHTML = gSyncInfo.showCycle + " : Day " + gSyncInfo.date + " of every month";
	  }
	  else if(gSyncInfo.cycle == 'weekly'){
	  	$('step3_cycle').innerHTML = gSyncInfo.showCycle + gSyncInfo.week;
	  }
	  else{
	  	$('step3_cycle').innerHTML = gSyncInfo.showCycle;
	  }
	 
	  $('step3_time').innerHTML = gSyncInfo.time;
	  $('step3_direc').innerHTML = gSyncInfo.showDirec;

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
// Open popup window for folder selection
//=======================================================//
function popup_file_browser(id)
{
	//debug(document.getElementById('idPathMode').value);
	document.getElementById("idInputFieldId").value=id;
	var win = window.open('../mobile/usb_pop_brows.php','DIR_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
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
		//document.getElementById('idListBox').innerHTML=res;
		document.getElementById('idListBoxUsb').innerHTML="<table width='400' border='0' cellspacing='0' cellpadding='0'>"
			+ "<tr><td class='firstCol' colspan='3' >"+"<?php echo lang_get('usb_sync_13')?>"+"</td></tr></table>";
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
  var usbImage=new Array();
	var _table=new Array();
	var _table_total = "";
	for(var i=0;info[i];i++)
	{
		var tmp = info[i][0].split(" ");
		var name = tmp[1];
		_table[i]="<tr><td class='firstCol_250' style='width:100px;'>"
			+"<input type='radio' name='chkBox' id='idUsb_chk_"+i+"' value='"+name+"'/>"
			+name
			+"</td>"
			+"<td class='otherCol_420' style='width:200px;'>"
			+info[i][2]
			+"</td>"
			+"<td class='thirdCol_100'>"
			+info[i][1]
			+"</td></tr>";
		
			_table_total+=_table[i];
		
		
		if(tmp[1] == 'USB1') usbImage[i] = 1;
		else if(tmp[1] == 'USB2') usbImage[i] = 2;
		else if(tmp[1] == 'USB3') usbImage[i] = 3;
		else if(tmp[1] == 'MemCard') usbImage[i] =4;
		
	}
	
	// Refresh Image using USB connnect Information : 20081204
	usbImage.sort();
	
		var temp_no="";
		for(var i=0;i<usbImage.length;i++){
			temp_no += usbImage[i];
		}
	
	document.getElementById('usbImage').src = "../images/wizard/img_Nas"+temp_no+".gif";	
	
	_table_frame = "<table border='0' cellspacing='0' cellpadding='0' width='400px' class='basicTable'>"+_table_total+"</table>";		
	//alert(_table_frame);
	
	document.getElementById(gIdBox[1]).innerHTML=_table_frame;
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
        strsel += "</option>";
    }
    strsel += "</select>  "
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
    strsel += "</select>"
    seltimemin.innerHTML=strsel;    
}







//========================================================//
// Show table area
//========================================================//
function showTable(id){	
	//debug(id);
	
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	
	
	if(id!=""){
		document.getElementById(id).style.display = "block";
	}
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
// USB : create initial setting
//=======================================================//
function open_create_table_usb()
{
	
	var i=get_task_chk_usb();
	if(i==-1) return false;
	gSelectedUsbNumber = i;
	var usb_num = document.getElementById('idUsb_chk_'+i).value;
	var cmd = "&usb_num="+usb_num;
	sendRequest(on_7,cmd,"post",gPhp[2],true,true);
	
	showTable(gIdTable[1]);
	document.getElementById('usb_select_box').innerHTML = usb_num;
	cms_init_select();
}
function on_7(oj)
{
	var res=decodeURIComponent(oj.responseText);
	
	var default_path = "/Vol1/system/Backup/USB/"+res;
	document.getElementById("cms_ctrlnum").value = res;
	document.getElementById("cms_dest").value = default_path;
	

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
// Backup method check
//=======================================================//
function check_incremental()
{
	var _tmp = document.getElementById('cms_direc_incre').checked;
	var _disabled = true;
	if(_tmp == true) _disabled = false;
	document.getElementById('cms_crtfld_filedate').disabled = _disabled;
	document.getElementById('cms_crtfld_backupdate').disabled = _disabled;
}
//=======================================================//
// Check condition before task save
//=======================================================//
function check_condition(){
	if( document.getElementById('cms_name').value == "" ){
		alert("<?php echo lang_get('schedule_msg_6')?>");
		return false;
	}else if( document.getElementById('cms_description').value == "" ){
		alert("<?php echo lang_get('schedule_msg_7')?>");
		return false;
	}
	return true;
} 

function check_same_name()
{
	//
	// true : same name exists.
	// false : no same name
	//

   if(XML_result != null){
   		
   		var checkName = $('cms_name').value;
   	
   		if(XML_result.indexOf("|NAME;"+checkName+"|") != -1 ){ 
   			_msg = "<?php echo lang_get('usb_sync_msg_6')?>";
   			alert(_msg.replace('<BR />','\n'));
   			return true;
   		}
   		else return false;
    	
   }
}

//========================================================//
// show_help
//========================================================//

function show_help()
{

		var _win = window.open('../help/wizard/help_mobile_wizard.html','Help_System_wizard','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;

	}