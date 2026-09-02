<!--
//========================================================//
// System / Burning menu
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/burning_chk_blank.php",
"../php/burning_burn_disc.php",
"../php/burning_burn_image.php",
"../php/burning_get_dir.php","../php/burning_burn_image.php");
//========================================================//
// ID list
//========================================================//
var gIdTable=new Array('idTableBurn','idTableImage');
var gIdTab=new Array('idTabBurn','idTabImage');
var gIdInBurn=new Array("idBurnSize","idBurnVolume");
var gIdInImage=new Array("idBurnSizeImg","idBurnVolumeImg");
var gIdOutBurn=new Array("idBurnTitle");
var gIdOutImage=new Array("idBurnTitleImage");
var gIdBtn=new Array("idButtonBurnNext");
//=======================================================//
// Page status
//=======================================================//
var gStat=new Array("noDisc","burnReady","imageBurnReady");
var fStat=gStat[0];
//=======================================================//
// File+folder List
//=======================================================//
var gAllFileList=new Array();
var gAllFileListImg=new Array();
var gSelectedFileList=new Array();
var gSelectedFileListImg=new Array();
var gSelectedList=new Array();
var gDirList=new Array();
var gFileList=new Array();
var gDirListImg=new Array();
var gFileListImg=new Array();
//=======================================================//
// Path
//=======================================================//
var gRootPath="";
var gCurrentPath="";
//=======================================================//
// Message list
//=======================================================//
var gMsg=new Array("Disc is ready for burning.",
"No disc for burning.",
"Blu-ray drive is busy. Try later.",
"Disc loading error!",
"No selected file or folder!");
var gWarningMsg=new Array("Select a image file.");
//=======================================================//
// Data type definition
//=======================================================//
function file_info(name,size,type,time,selected,path)
{
	this.name=name;
	this.size=size;
	this.type=type;
	this.time=time;
	this.selected=selected;
	this.path=path;
}

//========================================================//
// Open tab
//========================================================//
function open_tab_burn()
{
	close_tab_all();
	open_tab(gIdTable[0]);
	open_tab(gIdTab[0]);
	fStat=gStat[0];
	startLoad("burn");
}
function open_tab_image()
{
	close_tab_all();
	open_tab(gIdTable[1]);
	open_tab(gIdTab[1]);
	fStat=gStat[1];
	startLoad("image burn");
}
function open_tab(id)
{
	document.getElementById(id).style.display='block';
}
function close_tab_all()
{
	document.getElementById(gIdTable[0]).style.display='none';
	document.getElementById(gIdTable[1]).style.display='none';
	document.getElementById(gIdTab[0]).style.display='none';
	document.getElementById(gIdTab[1]).style.display='none';
}
//=======================================================//
// ODD information
//=======================================================//
function load_disc()
{
	debug('load disc');
	show_text(gIdOutBurn[0],'Disc loading...');
	show_text(gIdOutImage[0],'Disc loading...');
	vis_ctl(gIdBtn[0],'hidden');
	check_disc_info();
}
function check_disc_info()
{
	debug("check_disc_info");
	sendRequest(on_1,"","post",gPhp[0],true,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	switch (res)
	{
	case "OK":
		show_text(gIdOutBurn[0],gMsg[0]);
		show_text(gIdOutImage[0],gMsg[0]);
		break;
	case "NG":
		show_text(gIdOutBurn[0],gMsg[1]);
		vis_ctl(gIdBtn[0],'visible');
		break;
	case "ODD BUSY":
		show_text(gIdOutBurn[0],gMsg[2]);
		vis_ctl(gIdBtn[0],'visible');
		break;
	default:
		alert(gMsg[3]);
		break;
	}
	vis_ctl(gIdBtn[0],'visible');
}
//=======================================================//
// Burning
//=======================================================//
function burn_data()
{
	debug("burn disc");
	if(check_condition())
	{
		////start burning
		var _list=get_selected_file_list();
		//debug(document.getElementById(gIdInBurn[1]).value);
		show_text(gIdOutBurn[0],'Burning disc...');
		var _cmd="&list="+_list+"&vol_name="+document.getElementById(gIdInBurn[1]).value;
		debug(_cmd);
		sendRequest(on_2,_cmd,"post",gPhp[1],true,true);
		////display
	}
}
function on_2(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	show_text(gIdOutBurn[0],'Complete disc burning : '+res);
}
function check_condition()
{
	debug("check burn conditions");
	////max disc capacity
	////disc name
	return true;
}
//========================================================//
// Image burn
//========================================================//
function image_burn()
{
	debug("image_burn");
	var _data=gSelectedFileListImg[0];
	//debug(_data.path+_data.name);
	var _cmd='&filename='+_data.path+_data.name;
	show_text(gIdOutImage[0],'Burning image...');
	sendRequest(on_3,_cmd,'post',gPhp[2],true,true);
}
function on_3(oj)
{
	var res = decodeURIComponent(oj.responseText);
	debug(res);
	show_text(gIdOutImage[0],'Complete image burning : '+res);
}


/***************************************************/
function get_all_list()
{
	var _tmp=new Array();
	var _oj=null;
	var _time=null;
	for(var i=0;gDirList[i];i++)
	{
		 _oj=gDirList[i];
		 _time=_oj.date+" "+_oj.time;
		 
		_tmp[i]=new file_info(_oj.file_name,_oj.size,"directory",_time,"no",gCurrentPath);
	}
	for(var j=0;gFileList[j];j++)
	{	
		var _oj=gFileList[j];
		_time=_oj.date+" "+_oj.time;
		_tmp[i+j]=new file_info(_oj.file_name,_oj.size,_oj.subtype,_time,"no",gCurrentPath);
	}
	return _tmp;
}
function get_all_list_img()
{
	var _tmp=new Array();
	var _oj=null;
	var _time=null;
	for(var i=0;gDirListImg[i];i++)
	{
		 _oj=gDirListImg[i];
		 _time=_oj.date+" "+_oj.time;
		 
		_tmp[i]=new file_info(_oj.file_name,_oj.size,"directory",_time,"no",gCurrentPath);
	}
	for(var j=0;gFileListImg[j];j++)
	{	
		var _oj=gFileListImg[j];
		_time=_oj.date+" "+_oj.time;
		_tmp[i+j]=new file_info(_oj.file_name,_oj.size,_oj.subtype,_time,"no",gCurrentPath);
	}
	return _tmp;
}
//=======================================================//
// Selected file manipulation
//=======================================================//
function send_selected_dir_names(){
		var fObj = $('files_slc_fm');
		var dir_obj = null;
		var checked_cnt = 0;
		var checked_dir_name = new Array();
		var _checked_list=new Array();
		
		for(var i=0;fObj.elements[i];i++){
			dir_obj = fObj.elements[i];
			if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
				var _fSame="no";
				if(gAllFileList[i].selected=="no")
				{
					gAllFileList[i].selected="yes";
					
					//compare to the previous selected list
					var _selected_path=gAllFileList[i].path+gAllFileList[i].name;
					for(var j=0;gSelectedFileList[j];j++)
					{
						var _path=gSelectedFileList[j].path+gSelectedFileList[j].name;
						if(_selected_path==_path)
						{
							_fSame="yes";
							break;
						}
					}
					if(_fSame=="no")
					{
						gSelectedFileList=gSelectedFileList.concat(gAllFileList[i]);
					}
					if(gSelectedFileList[j]==null)
					{
						gSelectedFileList=gSelectedFileList.concat(gAllFileList[i]);
					}
				}
			}
		} 
		display_selected_files_list_mode();
		display_selected_files_size(gIdInBurn[0]);
	}
function delete_selected_dir_names()
{
	var _list=gSelectedFileList;
	if(_list.length==0) return false;
	
	var fObj = $('files_aaa_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = new Array();
	var _checked_list=new Array();
	
	for(var i=0;fObj.elements[i];i++){
		dir_obj = fObj.elements[i];
		if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
			_list[i].selected="no";
			_checked_list[checked_cnt]=i;
			checked_cnt++;
		}else
		{
			_list[i].selected="yes";
		}
	} 
	for(var i=(_list.length-1);i>=0;i--)
	{
		if(_list[i].selected=="no")
		{
			_list.splice(i,1);
		}
	}
	gSelectedFileList=_list;
	display_selected_files_list_mode();
	display_selected_files_size(gIdInBurn[0]);
}	
function send_selected_dir_names_image(){
		var fObj = $('files_img_fm');
		var dir_obj = null;
		var checked_cnt = 0;
		var checked_dir_name = new Array();
		var _checked_list=new Array();
		
		for(var i=0;fObj.elements[i];i++){
			dir_obj = fObj.elements[i];
			if(dir_obj.type == 'checkbox' && dir_obj.checked == true&&gAllFileListImg[i].type!="directory"){
				var _fSame="no";
				if(gAllFileListImg[i].selected=="no")
				{
					gAllFileListImg[i].selected="yes";
					checked_cnt++;
					
					//compare to the previous selected list
					var _selected_path=gAllFileListImg[i].path+gAllFileListImg[i].name;
					for(var j=0;gSelectedFileListImg[j];j++)
					{
						var _path=gSelectedFileListImg[j].path+gSelectedFileListImg[j].name;
						if(_selected_path==_path)
						{
							_fSame="yes";
							break;
						}
					}
					if(_fSame=="no")
					{
						gSelectedFileListImg=gSelectedFileListImg.concat(gAllFileListImg[i]);
					}
					if(gSelectedFileListImg[j]==null)
					{
						gSelectedFileListImg=gSelectedFileListImg.concat(gAllFileListImg[i]);
					}
				}else
				{
					checked_cnt++;
				}
			}
		}
		if(checked_cnt>1||checked_cnt==0)
		{
			alert(gWarningMsg[0]);
		}else
		{
			display_selected_files_list_mode_img();
			display_selected_files_size_img(gIdInImage[0]);
		}
	}
function delete_selected_dir_names_img()
{
	var _list=gSelectedFileListImg;
	if(_list.length==0) return false;
	//debug("a");
	var fObj = $('files_simg_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = new Array();
	var _checked_list=new Array();
	
	for(var i=0;fObj.elements[i];i++){
		dir_obj = fObj.elements[i];
		if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
			//debug(i);
			_list[i].selected="no";
			_checked_list[checked_cnt]=i;
			checked_cnt++;
		}else
		{
			_list[i].selected="yes";
		}
	} 
	for(var i=(_list.length-1);i>=0;i--)
	{
		if(_list[i].selected=="no")
		{
			_list.splice(i,1);
		}
	}
	gSelectedFileListImg=_list;
	display_selected_files_list_mode_img();
	display_selected_files_size_img(gIdInImage[0]);
	//debug("b");
}
// Listing files and directories
function display_selected_files_list_mode(){
	var info = gSelectedFileList;
	var i=0;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var table_frame_html = '<form name="files_slc_fm" id="files_aaa_fm" method="POST" onsubmit="return false;">'
							+'<table>'
							+'<thead><tr style="background:#DDDDDD;">'
							+'<td></td>'
							+'<td><a href="" onclick="changeSort(\'name\');return false;">name</a></td>'
							+'<td><a href="" onclick="changeSort(\'size\');return false;">size</a></td>'
							+'<td><a href="" onclick="changeSort(\'type\');return false;">type</a></td>'
							+'<td><a href="" onclick="changeSort(\'time\');return false;">time</a></td>'
							+'</tr></thead>'
							+'<tbody>'
							+'#body_row#'
							+'</tbody>'
							+'</table>'
							+'</form>';
	var body_row_html = '<tr>'
							+'<td>#checkbox#</td>'
							+'<td>#name#</td>'
							+'<td>#size#</td>'
							+'<td>#type#</td>'
							+'<td>#time#</td>'
						+'</tr>';
	
	for(var i=0;info[i];i++){
		body_row = body_row_html;
		var data=info[i];
		if(data.type=="directory")
		{
			//debug("directory");
			body_row = body_row.replace('#size#','');
		}else
		{
			//debug("file");
			body_row = body_row.replace('#size#',data.size);
		}
		checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'">';
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',data.name);
		body_row = body_row.replace('#type#',data.type);
		body_row = body_row.replace('#time#',data.time);
		body_row_total += body_row;
		obj_cnt++;
	}
	$('selected_file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
}
function get_selected_file_list()
{
	var _list=null;
	for(var i=0;gSelectedFileList[i];i++)
	{
		var _oj=gSelectedFileList[i];
		if(i==0) var _list=_oj.path+_oj.name;
		if(i>0) _list+=(":"+_oj.path+_oj.name);
		//debug(_list);
	}
	return _list;
}
function display_selected_files_list_mode_img(){
	var info = gSelectedFileListImg;
	var i=0;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var table_frame_html = '<form name="files_simg_fm" id="files_simg_fm" method="POST" onsubmit="return false;">'
							+'<table>'
							+'<thead><tr style="background:#DDDDDD;">'
							+'<td></td>'
							+'<td><a href="" onclick="changeSort(\'name\');return false;">name</a></td>'
							+'<td><a href="" onclick="changeSort(\'size\');return false;">size</a></td>'
							+'<td><a href="" onclick="changeSort(\'type\');return false;">type</a></td>'
							+'<td><a href="" onclick="changeSort(\'time\');return false;">time</a></td>'
							+'</tr></thead>'
							+'<tbody>'
							+'#body_row#'
							+'</tbody>'
							+'</table>'
							+'</form>';
	var body_row_html = '<tr>'
							+'<td>#checkbox#</td>'
							+'<td>#name#</td>'
							+'<td>#size#</td>'
							+'<td>#type#</td>'
							+'<td>#time#</td>'
						+'</tr>';
	
	for(var i=0;info[i];i++){
		body_row = body_row_html;
		var data=info[i];
		if(data.type=="directory")
		{
			//debug("directory");
			body_row = body_row.replace('#size#','');
		}else
		{
			//debug("file");
			body_row = body_row.replace('#size#',data.size);
		}
		checkbox_html = '<input type="checkbox" name="directory[]" id="chk_img_'+obj_cnt+'" value="'+data.file_name+'">';
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',data.name);
		body_row = body_row.replace('#type#',data.type);
		body_row = body_row.replace('#time#',data.time);
		body_row_total += body_row;
		obj_cnt++;
	}
	$('selected_file_box_img').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
}

//=======================================================//
// Burning information display
//=======================================================//
function display_selected_files_size(id)
{
	//debug("show selected size");
	var _size=get_total_selected_file_size();
	document.getElementById(id).value=_size;
}
function display_selected_files_size_img(id)
{
	//debug("show selected size");
	var _size=get_total_selected_file_size_img();
	document.getElementById(id).value=_size;
}
//=======================================================//
// Size information calculation
//=======================================================//
function get_total_selected_file_size()
{
	for(var i=0;gSelectedFileList[i];i++)
	{
		if(i==0) var _total_size=new size_info(0,"B");
		//debug("+"+gSelectedFileList[i].size+"+");
		var _size=get_size_info(gSelectedFileList[i].size);
		//debug(_size.size+"\n"+_size.unit);
		//debug(_total_size.size+"+"+_total_size.unit);
		_total_size=sum_size_info(_total_size,_size);
		//debug(_total_size.size+"+"+_total_size.unit);
	}
	if(_total_size)
	{
		var _ret=_total_size.size+" "+_total_size.unit;
	}else
	{
		var _ret="0 B";
	}
	//debug(_ret);
	return _ret;
}
function get_total_selected_file_size_img()
{
	for(var i=0;gSelectedFileListImg[i];i++)
	{
		if(i==0) var _total_size=new size_info(0,"B");
		var _size=get_size_info(gSelectedFileListImg[i].size);
		_total_size=sum_size_info(_total_size,_size);
	}
	if(_total_size)
	{
		var _ret=_total_size.size+" "+_total_size.unit;
	}else
	{
		var _ret="0 B";
	}
	return _ret;
}
function get_size_info(size)
{
	//debug(size)
	if(size==null)
	{
		debug("get_size_info : "+size);
		size="0K";
	}
	// Unit detect
	var _unit = size.substr(size.length-1);
	if(!_unit.match(/[BKkMGT]/)){
		var _unit = 'B';
	}
	debug(size.substr(size.length-1)+' => '+_unit);
	var _size=new size_info(parseFloat(size),_unit);
	//debug("+"+_size.size+"+"+_size.unit+"+");
	return _size;
}
function sum_size_info(sInfo1,sInfo2)
{
	var _size1=get_basic_size(sInfo1);
	var _size2=get_basic_size(sInfo2);
	var _size3=parseFloat(_size1)+parseFloat(_size2);
	var _size4=convert_size_to_info(_size3);
	debug("size1 : size2 : sum size : converted sum size : unit\n"+_size1+" : "+_size2+" : "+_size3+" : "+_size4.size+" : "+_size4.unit);
	return _size4;
}
function get_basic_size(sInfo)
{
	switch (sInfo.unit)
	{
	case "K":
		sInfo.size*=1024;
		break;
	case "k":
		sInfo.size*=1024;
		break;
	case "M":
		sInfo.size*=1048576;
		break;
	case "G":
		sInfo.size*=1073741824;
		break;
	case "T":
		sInfo.size*=133143986176;
		break;
	default:
		break;
	}
	return sInfo.size;
}
function convert_size_to_info(size)
{
	var _size=parseFloat(size);
	if(isNaN(_size))
	{
		debug("convert_size_to_info : "+size+"==>"+_size);
		_size=0;
	}
	var _K=1024;
	var _M=1048576;
	var _G=1073741824;
	var _T=133143986176;
	if(_size<_K)
	{
		var _info=new size_info(_size,"B");
	}else if(_size<_M)
	{
		var _info=new size_info(_size/_K,"K");
	}else if(_size<_G)
	{
		var _info=new size_info(_size/_M,"M");
	}else if(_size<_T)
	{
		var _info=new size_info(_size/_G,"G");
	}else
	{
		var _info=new size_info(_size/_T,"T");
	}
	/*if(!_info.size.toFixed(3))
	{
		debug("convert_size_to_info : toFixed ==>"+_info.size.toFixed(3));
	}*/
	if(_info.size==0){
		_info.size = 0;
	}else{
		_info.size=_info.size.toFixed(3);
	}
	return _info;
}
function size_info(num,unit)
{
	this.size=num;
	this.unit=unit;
}



//========================================================//
// Test code
//========================================================//
debug('burning.js');
//-->
