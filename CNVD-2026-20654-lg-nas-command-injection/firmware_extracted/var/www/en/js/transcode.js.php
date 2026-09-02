<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//=======================================================//
// Page init
//=======================================================//
var page = {
	name : "transcoder",
	init : function(){
		startLoad("transcode");
	}
}

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
var gStat=new Array("noDisc","burnReady","imageBurnReady","Disc Loading","Complete Disc Loading");
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
var gCurrentPath="";
//=======================================================//
// Message list
//=======================================================//
var gMsg=new Array("<?php echo lang_get('burning_2')?>",
"<?php echo lang_get('storing_msg_4')?>",
"<?php echo lang_get('storing_msg_5')?>",
"<?php echo lang_get('burning_3')?>",
"<?php echo lang_get('burning_msg_23')?>");
var gWarningMsg=new Array("<?php echo lang_get('burning_msg_27')?>");
//=======================================================//
// Binary Semaphore
//=======================================================//
var gSemaphore_refresh=false;
var gSemaphore_burn=false;
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
//=======================================================//
// Window handle
//=======================================================//
var hPopWin = "";


//=======================================================//
// Burning
//=======================================================//
function getSelectedEncodedFileList() 
{	
	var list=null;	
	for(var i=0;gSelectedFileList[i];i++)	
	{
		var _oj=gSelectedFileList[i];
		var encodedOj=gEncodedSelectedFilename[i];
		if(i==0) 
			var list=_oj.encodedPath+encodedOj;	
		if(i>0) 
			list+=(":"+_oj.encodedPath+encodedOj);	

	}	
	return list;
}

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

function check_name(name)
{
	return true;
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
	/*for(var i=0;gDirListImg[i];i++)
	{
		 _oj=gDirListImg[i];
		 _time=_oj.date+" "+_oj.time;
		 
		_tmp[i]=new file_info(_oj.file_name,_oj.size,"directory",_time,"no",gCurrentPath);
	}*/
	var i = 0;
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
var gEncodedSelectedFilename = new Array();
function send_selected_dir_names(){
		//juny
		//alert("send_selected_dir_names()");
		var fObj = $('files_slc_fm');
		var dir_obj = null;
		var checked_cnt = 0;
		var checked_dir_name = new Array();
		var _checked_list = new Array();
		
		// Check same full path & same folder name
		for(var i=1;fObj.elements[i];i++)
		{
			dir_obj = fObj.elements[i];
			if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
				var _fSame="no";
				var k = i - 1;
				if(gAllFileList[k].selected=="no")
				{
					gAllFileList[k].selected="yes";
					
					//compare to the previous selected list
					var _selected_path=gAllFileList[k].path+gAllFileList[k].name;
					for(var j=0;gSelectedFileList[j];j++)
					{
						var _path=gSelectedFileList[j].path+gSelectedFileList[j].name;
						if(_selected_path==_path)
						{
							_fSame="yes";
							break;
						}else if(gAllFileList[k].name == gSelectedFileList[j].name){
							alert("<?php echo lang_get('burning_msg_50')?>"+'\n'+"<?php echo lang_get('burning_msg_51')?>"+' : '+gAllFileList[k].name);
							//alert('Same name folder was already selected.\nFolder name : '+gAllFileList[k].name);
							gAllFileList[k].selected = 'no';
							_fSame="yes";
							break;
						}
					}
					if(_fSame=="no")
					{
						gAllFileList[k].encodedPath=gEncodedCurrentPath;
						gSelectedFileList=gSelectedFileList.concat(gAllFileList[k]);
						gEncodedSelectedFilename = gEncodedSelectedFilename.concat(document.getElementById('chk_'+k).value);
					}
					if(gSelectedFileList[j]==null)
					{
						gAllFileList[k].encodedPath=gEncodedCurrentPath;
						gSelectedFileList=gSelectedFileList.concat(gAllFileList[k]);
						gEncodedSelectedFilename = gEncodedSelectedFilename.concat(document.getElementById('chk_'+k).value);
					}
				}
			}
		} 
		display_selected_files_list_mode();
		//display_selected_files_size(gIdInBurn[0]);
		
		display_target_format();
		
	}

function display_target_format()
{

	if (document.getElementById('File_Format').options[0].selected) 
	{
		document.getElementById('idVideoFormat').value = "Mpeg4, 480x272, 30.00Fps, 821kbps";	
		document.getElementById('idAudioFormat').value = "MP3, 2ch, 44100Hz, 128Kbps, 1Audio";
	}
	else if (document.getElementById('File_Format').options[1].selected) 
	{
		document.getElementById('idVideoFormat').value = "Mpeg4, 320x240, 24.00Fps, 504kbps";
		document.getElementById('idAudioFormat').value = "AAC, 2ch, 44100Hz, 128kbps, 1Audio";
	}
	else if (document.getElementById('File_Format').options[2].selected) 
	{
		document.getElementById('idVideoFormat').value = "flv1, 480x272, 24.00Fps, 600kbps";
		document.getElementById('idAudioFormat').value = "MP3, 2ch, 44100Hz, 96kbps, 1Audio";
	}
	else if (document.getElementById('File_Format').options[3].selected) 
	{
		document.getElementById('idVideoFormat').value = "Mpeg4, 320x240, 30.00Fps, 821kbps";	
		document.getElementById('idAudioFormat').value = "MP3, 2ch, 44100Hz, 128Kbps, 1Audio";
	}
	else if (document.getElementById('File_Format').options[4].selected) 
	{
		document.getElementById('idVideoFormat').value = "Mpeg4, 480x320, 30.00Fps, 821kbps";	
		document.getElementById('idAudioFormat').value = "MP3, 2ch, 44100Hz, 128Kbps, 1Audio";
	}	

	//return sInfo.size;		

}

//=======================================================//
// Transcoding Progress
//=======================================================//
var progress_show = false;
var trans_progress = {
	per : 0,
	fFin : false,
	c_err : 0,
	c_err_max : 100,
	w_max : 434,
	timer : "",
	start_read : function(){
		this.timer = setInterval('trans_progress.read()',7000);

	},
	finish : function(){
		clearInterval(trans_progress.timer);
		
	},
	stop : function(){
		if(this.timer) 
			clearInterval(this.timer);
	},
	read : function(){

		var cmd = "&mode=progress";
		var php = '../php/service_get_transcode.php';
						
		//clearInterval(this.timer);
		sendRequest(on_start_trans,cmd,'post',php,true,true);

		function on_start_trans(oj){
			var res=decodeURIComponent(oj.responseText);

			if(res.match('OK'))
			{
				progress_show = true;
				var duration, _h, _m, _w, duration_sec, trans_time, _t,_number, _total;

				duration = res.split(":");
				_h = parseInt(duration[2]); //hours
				_h= 60*60*_h;				
				_m = parseInt(duration[3]); //minutes
				_m= 60*_m;
				_w = parseInt(duration[4]); //seconds

				duration_sec = _h + _m + _w;

				trans_time = res.split("||");
				_number = parseInt(trans_time[1]); //current transcoded file number
				_total =  parseInt(trans_time[2]); //total file number to be transcoded
				_t =  parseInt(trans_time[3]); //seconds
				

				var rate = parseInt((_t/duration_sec)*100);
				//alert(rate);

				if(rate<1)	rate =1
				if(rate<99){
					//progress_show = true;
					document.getElementById('idVolCapap0').innerHTML ="["+_number+"/"+_total+"]    "+rate+" %";
					trans_progress.per = rate;
					document.getElementById('idVolProg_width0').width = trans_progress.w_max/100*rate;					
					
				}
				else if(rate >=99)
				{
					rate = 100;
					document.getElementById('idVolCapap0').innerHTML = "["+_number+"]    "+rate+" %";
					document.getElementById('idVolProg_width0').width = trans_progress.w_max/100*rate;
					progress_show = false;
					trans_progress.finish_lcd();
					
				}
				else
				{
					document.getElementById('idVolCapap0').innerHTML = "";
				}						
				
				//trans_progress.progress(_w);			
			}
			else
			{
				if(progress_show == true)
				{
					rate = 100;
					document.getElementById('idVolCapap0').innerHTML = "100 %";
					document.getElementById('idVolProg_width0').width = trans_progress.w_max/100*rate;
				}
				else
				{
					document.getElementById('idVolCapap0').innerHTML = "--";
				}
				progress_show = false;
				trans_progress.finish();
				
			}			
		}
	},
	finish_lcd : function(){
		this.finish();
		document.getElementById('idVolCapap0').innerHTML = "complete";
		//window.location.href = '../system/volume.php';
 
	}
}

function start_transcoding()
{
	var php = '../php/service_set_transcode.php';	
	var encodeFileList = getSelectedEncodedFileList();		
	alert("JUNY");
	
	if(document.getElementById('idBurnTitle')){
		document.getElementById('idBurnTitle').innerHTML = "Start transcoding..."; //"<?php echo lang_get('burning_msg_15')?>";
	}

	var options, extension;
	if (document.getElementById('File_Format').options[0].selected) 
	{
		options = "-acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec mpeg4 -s 480x272 -r 30 -vb 821k -y";	
		extension ="avi" ;
	}
	else if (document.getElementById('File_Format').options[1].selected) 
	{
		options = "-acodec libfaac -ab 128k -ar 44100 -ac 2 -vcodec mpeg4 -s 320x240 -r 24 -vb 504k -y";	
		extension ="avi" ;
	}
	else if (document.getElementById('File_Format').options[2].selected) 
	{
		//options = "-acodec libmp3lame -ab 96k -ar 44100 -ac 2 -vcodec flv -s 480x272 -r 24 -vb 600k -y";	
		options = "-acodec libmp3lame -ab 96k -ar 44100 -ac 2 -vcodec libx264 -vpre hq -s 480x272 -r 24 -vb 600k -y";	
		extension="flv" ;
	}
	else if (document.getElementById('File_Format').options[3].selected) 
	{
		options = "-acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec mpeg4 -s 320x240 -r 30 -vb 821k -y";	
		extension ="avi" ;
	}
	else if (document.getElementById('File_Format').options[4].selected) 
	{
		options = "-acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec mpeg4 -s 480x320 -r 30 -vb 821k -y";	
		extension ="avi" ;
	}	
	else
	{
		options = "-acodec libmp3lame -ab 128k -ar 44100 -ac 2 -vcodec mpeg4 -s 480x272 -r 30 -vb 821k -y";	
		extension ="avi" ;	
	}
		
	var cmd = '&list='+encodeFileList
	              +'&options='+options
			+'&extension='+ extension;
	
	sendRequest(on_trancoding,cmd,'post',php,true,true);
}


function on_trancoding(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	code = res.split(':');
	
	if(code[0] == 'NG')  //Check whether transcode binary is running now !!
	{
		alert("Transcoding is in progress");
	}
	else
	{
		//display_POPUP(code[1]);
		trans_progress.start_read();
	}

/*
	
	if(_code[0]=="OK"){
		document.getElementById('burning_erase_disc').style.display = "none";
		alert("<?php echo lang_get('schedule_msg_25')?>");

		return;
	}else if(_code[0]=="BUSY"){
		document.getElementById('burning_erase_disc').style.display = "none";
		alert("<?php echo lang_get('storing_msg_2')?>");
		return;
	}else{
		// Multi Language Apply
			if(_msg.search("NOT FORMATTABLE MEDIA") != -1)
			{
				_msg = "<?php echo lang_get('schedule_msg_34')?>";
			}
		
		alert("<?php echo lang_get('common_error')?> : "+_msg.replace('<BR />','\n'));
		document.getElementById('burning_erase_disc').style.display = "none";
		return;
	}
*/

	
}




	
function delete_selected_dir_names()
{
	var _list=gSelectedFileList;
	var _listEn = gEncodedSelectedFilename;
	if(_list.length==0) return false;
	
	var fObj = $('files_aaa_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = new Array();
	var _checked_list=new Array();
	
	for(var j=1;fObj.elements[j];j++){
		var i = j - 1;
		dir_obj = fObj.elements[j];
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
			_listEn.splice(i,1);
		}
	}
	gSelectedFileList=_list;
	gEncodedSelectedFilename=_listEn;
	display_selected_files_list_mode();
	display_selected_files_size(gIdInBurn[0]);
}	
function send_selected_dir_names_image(id){
	
	var _num = parseInt(id.substr(id.search(/\d+/)));
	
	
	gSelectedFileListImg = gAllFileListImg[_num];
	display_selected_files_size_img();	
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

function display_selected_files_list_mode(){
	
	//var tmp = document.getElementById('idBurnSize').value.split("/");
	//document.getElementById('idBurnSize').value = "<?php echo lang_get('common_loading');?>"+" /"+tmp[1];
	
	
	var info = gSelectedFileList;
	var i=0;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var total_size = 0;
	var table_frame_html = '<form name="files_slc_fm" id="files_aaa_fm" method="POST" onsubmit="return false;">'
							+"<table width='300' style='word-break:break-all;white-space:normal;table:fixed;'>"
							+'<thead><tr style="background:#DDDDDD;">'
							+"<td width='20' align='center'><input type='checkbox' id='id_chkbx_sel_burn' onclick='check_all_burn_list();'/></td>"
							+"<td style='max-width:200;' ><?php echo lang_get('common_name')?></td>"
							+"<td width='40' align='center'><?php echo lang_get('schedule_restore_1')?></td>"
							+"<td width='40' align='center'><?php echo lang_get('common_time')?></td>"
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
							+'<td>#time#</td>'
						+'</tr>';
	var _dirs_length = 0;
	var _files_length = 0;
	
	for(var i=0;info[i];i++){
		body_row = body_row_html;
		var data=info[i];
		if(data.type=="directory"){		
		//if(data.type == 'd'){
			//debug("directory");
			//body_row = body_row.replace('#size#','');
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_burn'+obj_cnt+'" value="'+data.file_name+'">'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
			
			if(data.size == ''){
				var _size = "<?php echo lang_get('common_loading');?>";
			}else if(data.size < 0){
				var _size = 'removed';	// Multi-language
				
			}else{
				var _size = bytesHumanReadable(data.size);
			}
			_dirs_length++;
		}else{
		//}else if(data.type == '-'){
			//debug("file");
			//body_row = body_row.replace('#size#',data.size);
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_burn'+obj_cnt+'" value="'+data.file_name+'">';
			var _size = bytesHumanReadable(data.size)
			
			_files_length++;
		}
		var _total_size = parseFloat(data.size);
		if(!isNaN(_total_size)){
			total_size += parseFloat(data.size);
		}
		
		
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',char_leng.get_filename(data.name));
		body_row = body_row.replace('#size#',_size);
		body_row = body_row.replace('#time#',data.time);
		body_row_total += body_row;
		obj_cnt++;
	}
	
	if( i==0 ){
		$('selected_file_box').innerHTML = "&nbsp;";
		$('directory_info_selected').innerHTML = "&nbsp;";
	}else{
		$('selected_file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
		$('directory_info_selected').innerHTML = _dirs_length+" <?php echo lang_get('burning_4')?>&nbsp;&nbsp;&nbsp;&nbsp;"+_files_length+" <?php echo lang_get('burning_5')?>&nbsp;&nbsp;&nbsp;&nbsp;"+bytesHumanReadable(total_size);

		//JUNY
		//var tmp = document.getElementById('idBurnSize').value.split("/");
		//document.getElementById('idBurnSize').value = bytesHumanReadable(total_size) +" /"+tmp[1];
	
	}
	
	
	//display_selected_files_size(gIdInBurn[0]);
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
/*function display_selected_files_list_mode_img(){
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
							+'<td>select</td>'
							+'<td>name</td>'
							+'<td>size</td>'
							+'<td>time</td>'
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
		body_row = body_row.replace('#size#',data.size);
		body_row = body_row.replace('#time#',data.time);
		body_row_total += body_row;
		obj_cnt++;
	}
	$('selected_file_box_img').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
}*/

//=======================================================//
// Burning information display
//=======================================================//
function display_selected_files_size(id){
	var _size=get_total_selected_file_size();
	var tmp = document.getElementById(id).value.split("/");
	document.getElementById(id).value=_size+" /"+tmp[1];
}
function display_selected_files_size_img(){
	var id = "idBurnSizeImg";
	var _size = gSelectedFileListImg.size;
	if(isNaN(_size)){
		_size = parseFloat(_size);
	}
	if(gSelectedFileListImg.name.substr(gSelectedFileListImg.name.length-3) == 'cue'){
		//var _tmp = toByte(_size);
		//_tmp = _tmp * 2048 / 2352;
		//_tmp = bytesHumanReadable(_tmp);
		var _tmp = bytesHumanReadable(_size * 2048 /2352);
		//_size = _tmp;
		// Please Don't Remove Below Message
		//alert("A image file of audio CD has larger capacity than a data CD.\nThe real capacity will be burned into the disc is smaller than the image file capacity.");
	}else{
		var _tmp = bytesHumanReadable(_size);
	}
	var tmp = document.getElementById(id).value.split("/");
	document.getElementById(id).value = _tmp+" /"+tmp[1];
	
}
//=======================================================//
// Size information calculation
//=======================================================//
function get_total_selected_file_size()
{
	var __total_size = 0;
	for(var i=0;gSelectedFileList[i];i++)
	{
		__total_size += gSelectedFileList[i].size;
		if(i==0) var _total_size=new size_info(0,"B");		
		var _size=get_size_info(gSelectedFileList[i].size);	
		//debug//
		//	
		debug('total size info : current size info => '+_total_size.size+' '+_size.size);
		_total_size=sum_size_info(_total_size,_size);		
	}
	return bytesHumanReadable(__total_size);
	
	if(_total_size)
	{
		var _ret=_total_size.size+" "+_total_size.unit;
		debug('total size info: size unit => '+_total_size.size+' '+_total_size.unit);
	}else
	{
		var _ret="0 B";
	}
	return _ret;
}
function get_total_selected_file_size_img(){
	
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
	if(size==null || size == '')
	{
		debug("get_size_info : "+size);
		size="0K";
	}
	// Unit detect
	var _tmp = String(size);
	var _unit = _tmp.substr(size.length-1);
	if(!_unit.match(/[BKkMGT]/)){
		var _unit = 'B';
	}
	
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
		var _tmp = Math.round(_info.size * 100)/100;
		
		//_info.size=_info.size.toFixed(2);
		_info.size=_tmp.toFixed(2);
	}
	return _info;
}
function size_info(num,unit)
{
	this.size=num;
	this.unit=unit;
}
//=======================================================//
// Check box control
//=======================================================//
function check_all_nas_list(){
	
	//debug("nas list");
	var _oj = document.getElementById('id_chkbx_sel_nas');
	var cnt = 0;
	if( _oj.checked ){		
		for( var i=0;document.getElementById('chk_'+i);i++ ){
			if(checkPath(i)) {				
				document.getElementById('chk_'+i).checked = true;				
				cnt++;			
			}
			else break;		
		}		
		if(cnt==0) _oj.checked=false;
	}else{
		for( var i=0;document.getElementById('chk_'+i);i++ ){
			//debug(document.getElementById('chk_'+i).value);
			document.getElementById('chk_'+i).checked = false;
		}
	}
}
function check_all_burn_list(){
	//debug("burn list");
	var _oj = document.getElementById('id_chkbx_sel_burn');
	if( _oj.checked ){
		
		for( var i=0;document.getElementById('chk_burn'+i);i++ ){
			document.getElementById('chk_burn'+i).checked = true;
		}
	}else{
		for( var i=0;document.getElementById('chk_burn'+i);i++ ){
			document.getElementById('chk_burn'+i).checked = false;
		}
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
		var _win = window.open('../help/blu-ray/help_burning.html#burndisc','Help_burning','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/blu-ray/help_burning#burnimage','Help_burning','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;

		default:
		break;
	}
}


// Loading selected folder size
var folder_size = {
	status : 'init',
	loading_php : '../php/burning_get_folder_size.php',
	loading : function(){
		this.status = 'loading';
		var _folders = this.make_folders_str();
		sendRequest(on_loading, '&folders='+_folders, 'post', this.loading_php, true, true);
		
		function on_loading(oj){
			//var res=decodeURIComponent(oj.responseText);
			var res = oj.responseText;

			eval('var _ret = '+res);
			
			switch(_ret.result){
				case -99:
					alert("<?php echo lang_get('login_msg_6');?>");
					return;
				break;
				default:
				break;
			}
			
			folder_size.update_list_size(_ret);
			
			display_selected_files_list_mode();
			
			display_selected_files_size(gIdInBurn[0]);
			
			folder_size.status = 'init';
		}
	},
	make_folders_str : function(){
	/*
		var _ret = '';
		var _obj = gSelectedFileList;
		for(i=0;_obj[i];i++){
			if(_obj[i].type == 'directory'){
				_ret += _obj[i].path+_obj[i].name+':';
			}
		}
		return _ret;
	*/
		var str = '';		
		for(var i=0 ; document.getElementById('chk_'+i) ; i++) {			
			if(document.getElementById('chk_'+i).checked) str += document.getElementById('chk_'+i).value + ':';		
		}		
		return str;
	
	},
	update_list_size : function(oj){
		var _oj1 = gSelectedFileList;		
		var _oj = gEncodedSelectedFilename;
		
		for(i=0;_oj[i];i++){
			
			var _tmp_oj = oj[_oj[i]];
			if(_tmp_oj){		
				//alert(_tmp_oj.path.substr(9));				
				//alert(_oj1[i].path);
				var cmp = _tmp_oj.path.split('/');
				//alert(cmp[4]);
				
				//if(_tmp_oj.path.substr(9) == _oj1[i].path){		
					_oj1[i].size = _tmp_oj.size;
					//alert(_oj1[i].size);
					if(_tmp_oj.size<0){
						alert("<?php echo lang_get('folder_create_1');?> : "+_oj[i].name+' : removed');						
					}
				//}
			}
		}
	}
}
/*	Check '=' exists in path	*/
/*	Error 01		*/
function checkPath(index) {	
	if(gCurrentPath.search('=')>-1) {		
		alert('Current path includes "=".\n"=" is not supported for burning!');		
		if(document.getElementById('chk_'+index).checked) document.getElementById('chk_'+index).checked = false;		
		return false;	
	}	
	//alert(index+' : file name => '+gAllFileList[index].name);	
	if(gAllFileList[index].name.search('=')>-1) {		
		//alert(index+' : file name => '+gAllFileList[index].name);		
		alert('"=" is not supported for burning!');		
		if(document.getElementById('chk_'+index).checked) document.getElementById('chk_'+index).checked = false;		
		return false;	
	}	
	return true;
}
//========================================================//
// Volume popup window
//========================================================//
function open_popup(id)
{	
	document.getElementById(id).style.display = 'block';
	document.getElementById('burning_table').style.display = 'none';
}

function close_popup(id)
{
	document.getElementById('burning_table').style.display = 'block';
	document.getElementById(id).style.display = 'none';
}

//========================================================//
// Show volume info (Delete)
//========================================================//
function ShowDeleteVolume()
{
	var vol_num;

	var used_vol = "<?php echo $str1_array[2] ?>";	

	if("<?php echo $valid_vol_count ?>" == 2)
		used_vol +="<?php echo $str2_array[2] ?>";

	document.getElementById('idDelVolume').innerHTML= 
	"<?php echo lang_get('volume_msg_23')?> "+used_vol+" <?php echo lang_get('volume_msg_23_1')?>";
}




