<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>



/*
 * 화면 표시를 위한 함수들
 */

var cfg_img_width = 110;
var cfg_img_height = 90;
var display_mode = 'list'; // 'icon' / 'list'
var file_or_dir_only = ''; // '' / 'file' / 'directory' 파일 또는 디렉토리만 불러옴.''는 전부 불러옴
var sort_cond = 'time';
//var fGetCurDir = false;
var is_refresh = false;

//페이지가 처음 로딩될때 실행되는 함수
function startLoad(path_mode){
	is_loading = true;
	var _path = show_load(path_mode);
	switch(path_mode)
	{
		case 'burn':
		case 'dlna':
			//debug (path_mode);
			is_loading = false;
			refresh_file_box(path_mode);
			break;
		case 'image burn':
			//debug (path_mode);
			is_loading = false;
			refresh_file_box_img(path_mode);
			break;
		default:
		break;
	}
}

//현재 디렉토리내의 디렉토리 및 파일을 표시해주는 함수

function refresh_file_box(path_mode){
	debug("refresh box");
	if( is_refresh ){
		alert("<?php echo lang_get('burning_msg_36')?>");
		return false;
	}
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	is_refresh = true;
	is_loading = true;
	if(path_mode=="")
	{
		path_mode="none";
	}
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	//alert("refresh_file");
	var strResponseURL = remote_php_path+'?action=show_me_files&mode='+display_mode+'&sort_cond='+sort_cond+'&file_or_dir_only='+file_or_dir_only+'&path_mode='+path_mode;
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			asynchronous: true,
			onSuccess:function (responseHttpObj) {				

				var text = responseHttpObj.responseText;
				if(text.match('no volume configuration')){
					$('idPath').innerHTML = "<?php echo lang_get('volume_8')?>";
					return;
				}
				else if(text == '-99'){       // Session out
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				if(display_mode == 'list'){
					display_files_list_mode(responseHttpObj.responseText);
				}else{
					alert("Display mode error!");
					is_loading = false;
					is_refresh = false;
					$('idPath').innerHTML = _path;
				}
			},
			onFailure: displayError
			}
		);
	};


//파일 및 디렉토리를 리스트로 표시
function display_files_list_mode(response){
	
	getCurrentDirectoryPath();
	//var info = eval('(' + response + ')');
	var _tmpList = eval('(' + response + ')');	
	var _list = _tmpList[0];	
	var _encodedList = _tmpList[1];
	
	var _month = {
		'Jan' : '01',
		'Feb' : '02',
		'Mar' : '03',
		'Apr' : '04',
		'May' : '05',
		'Jun' : '06',
		'Jul' : '07',
		'Aug' : '08',
		'Sep' : '09',
		'Oct' : '10',
		'Nov' : '11',
		'Dec' : '12'
	}
	
	var info = {
		dirs : [],
		files : []
	}
		
	var total_size = 0;
	
	for(i=0;_list[i];i++){
		//alert(_list[i]);
		//var _tmp = info[i].split(/\s+/);
		var _tmp = _list[i].split(/\s+/);
				
		var _filename = _list[i].substr(_list[i].search(/\d\d:\d\d:\d\d \d{4}/)+14);
		_list[i] = {
			type : _tmp[0].substr(0,1) ,
			size : parseFloat(_tmp[4],10) ,
			file_name : _filename ,
			date : _tmp[9]+'/'+_month[_tmp[6]]+'/'+_tmp[7] ,
			time : _tmp[8] ,
			selected : '' ,
			encoded_file_name : _encodedList[i]			
		}		
		
		if(_list[i].type == 'd'){
			_list[i].size = '';
			info.dirs[info.dirs.length] = _list[i];
		}else if(_list[i].type == '-'){
			info.files[info.files.length] = _list[i];
			total_size += _list[i].size;			
		}
	}
	
	//var total_size = info.total_size;
	var i=0;
	var data = null;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var table_frame_html = '<form name="files_slc_fm" id="files_slc_fm" method="POST" onsubmit="return false;">'
							+"<table width='300' style='word-break:break-all;white-space:normal;table:fixed;'>"
							+'<thead><tr style="background:#DDDDDD;">'
							+"<td width='20' align='center'><input type='checkbox' id='id_chkbx_sel_nas' onclick='check_all_nas_list();'/></td>"
							+"<td style='max-width:200;' ><?php echo lang_get('common_name')?></td>"
							+"<td width='40' align='center'><?php echo lang_get('schedule_restore_1')?></td>"
							+"<td width='40' align='center'><?php echo lang_get('common_time')?></td>"
							+'</tr></thead>'
							+'<tbody>'
							+'#body_row#'
							+'</tbody>'
							+'</table>'
							+'</form>';
							//+"<table style='width:300px;word-break:break-all'>"
	var body_row_html = '<tr>'
							+'<td>#checkbox#</td>'
							+"<td >#name#</td>"
							+'<td>#size#</td>'
							+'<td >#time#</td>'
							+'</tr>';

	//	Directories
	for(i=0;i<info.dirs.length;i++){
		data = info.dirs[i];
		body_row = body_row_html;
		link = '<a href="" onclick="move_dir(\''+data.encoded_file_name+'\');return false;">'+char_leng.get_filename(data.file_name)+'</a>';
		rename_html = getRenameButton('directory',data.file_name);

		if(data.selected.length>0){	
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');			
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.encoded_file_name+'" checked onclick="checkPath('+i+');">'+"<img src='../images/comnso/cms_folder.gif' />&nbsp";			
		}else{				
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.encoded_file_name+'" onclick="checkPath('+i+');">'+"<img src='../images/comnso/cms_folder.gif' />&nbsp";			
		}
		
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#',data.size);
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		action_html = getDeleteButton('directory',data.file_name);
		body_row_total += body_row;
		obj_cnt++;
	}
	// Files
	for(i=0;i<info.files.length;i++){
		data = info.files[i];
		body_row = body_row_html;
		rename_html = getRenameButton('file',data.file_name);
		if(data.selected.length>0){			
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');			
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_'+obj_cnt+'" value="'+data.encode_file_name+'" checked onclick="checkPath('+obj_cnt+');">';		
		}else{			
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_'+obj_cnt+'" value="'+data.encoded_file_name+'" onclick="checkPath('+obj_cnt+');">';		
		}		
		
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',char_leng.get_filename(data.file_name));
		body_row = body_row.replace('#size#',bytesHumanReadable(data.size));
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		action_html = getDeleteButton('file',data.file_name);

		body_row_total += body_row;
		obj_cnt++;
	}
	////park94
	gDirList=info.dirs;
	gFileList=info.files;
	gAllFileList=get_all_list();/////

	$('file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	$('directory_info').innerHTML = info.dirs.length+" <?php echo lang_get('burning_4')?>&nbsp;&nbsp;&nbsp;&nbsp;"+info.files.length+" <?php echo lang_get('burning_5')?>&nbsp;&nbsp;&nbsp;&nbsp;"+bytesHumanReadable(total_size);
	hide_load('burn');
}


//파일을 다운로드 할수 있는 창을 띄움
function show_download_window(url){
	var win = window.open("../php/browsing_file_download.php?path="+url,'WIN','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=10px,height=10px');
	win.focus();
}

//현재 디렉토리 위치를 브라우저의 Address Bar에 표시
function getCurrentDirectoryPath(){
	var strResponseURL = remote_php_path+'?action=get_curr_dir_path';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			asynchronous: true,
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}				
				
				var path_info = eval('(' + responseHttpObj.responseText + ')');
				if(path_info.curr_url) {					
					current_path = path_info.curr_url;									
				}				
				else path_info.curr_url='Loading Failure!';

				$('idPath').innerHTML = path_info.curr_url;
				//$('idPathImage').innerHTML = path_info.curr_url;

				gCurrentPath=path_info.curr_url;
				gEncodedCurrentPath=path_info.cur_encd_path;
				gAllFileList=get_all_list();
				is_loading = false;
				is_refresh = false;
			},
			onFailure:displayError
			}
		);
}

//파일 및 디렉토리 표시모드를 아이콘 또는 리스트로 바꿈
function changeDisplayMode(){
	if(display_mode == 'icon'){
		display_mode = 'list';
	}else{
		display_mode = 'icon';
	}
	refresh_file_box();
}

// 파일 및 디렉토리를 표시할때 각 개체마다 표시할 삭제버튼의 HTML을 리턴
function getDeleteButton(type,name){
	return '<a href="javascript:void(0)" onclick="one_delete(\''+type+'\',\''+sg_quote_escape(name)+'\');return false;">[Delete]</a>';
}

//파일 및 디렉토리를 표시할때 각 개체마다 표시할 이름 수정버튼의 HTML을 리턴
function getRenameButton(type,name){
	return '<a href="javascript:void(0)" onclick="pop_rename_box(this,\''+type+'\',\''+sg_quote_escape(name)+'\');return false;">'
			+'[Rename]';
			+'</a>';
}

//파일 및 디렉토리의 소트방법을 변경후 브라우저 갱신
function changeSort(key){
	sort_cond = key;
	refresh_file_box();
}


//=======================================================//
// Loading display
//=======================================================//
function show_load(mode)
{
	switch(mode)
	{
		case 'burn':
		var _id = 'idPath';
		var _id_box = 'file_box';
		break;
		case 'image burn':
		var _id = 'idPathImage';
		var _id_box = 'file_box_img';
		break;
		default:
		break;
	}
	var oj = document.getElementById(_id);
	var tmp = oj.innerHTML;
	oj.innerHTML = "Loading...";
	document.getElementById(_id_box).style.visibility= 'hidden';
	return tmp;
}
function hide_load(mode)
{
	switch(mode)
	{
		case 'burn':
		//var _id = 'idPath';
		var _id_box = 'file_box';
		break;
		case 'image burn':
		//var _id = 'idPathImage';
		var _id_box = 'file_box_img';
		break;
		default:
		break;
	}
	document.getElementById(_id_box).style.visibility= 'visible';
}

//=======================================================//
// Confine the filename length within 32 or 64
//=======================================================//
var char_leng = {
	"length" : 32,
	img_filename_length : 64,
	get_filename : function(filename){
		if(filename.length>this["length"]){
			var _ret = "<span title='"+filename+"' >"+filename.substr(0, this["length"])+"...</span>";
		}else{
			var _ret = filename;
		}

		return _ret;
	},
	get_filename_img : function(filename){
		if(filename.length>this.img_filename_length){
			var _ret = "<span title='"+filename+"' >"+filename.substr(0, this.img_filename_length)+"...</span>";
		}else{
			var _ret = filename;
		}

		return _ret;
	}
}