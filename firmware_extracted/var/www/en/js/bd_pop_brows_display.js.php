<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//=======================================================//
// 10/31/2008
// LGE
// park94
//Display folders
//=======================================================//
/*
 * 화면 표시를 위한 함수들
 */
var file_or_dir_only = 'directory'; // '' / 'file' / 'directory' 파일 또는 디렉토리만 불러옴.''는 전부 불러옴
var remote_php = '../php/bd_pop_brows_remote.php';
 
 
var cfg_img_width = 110;
var cfg_img_height = 90;
var display_mode = 'list'; // 'icon' / 'list'
var sort_cond = 'time';

//페이지가 처음 로딩될때 실행되는 함수 //
// path_mode : rip/store/burn/schedule //
function startLoad(path_mode){
	//showLoadingImage();
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	refresh_file_box(path_mode);
	//hideLoadingImage();
}

//현재 디렉토리내의 디렉토리 및 파일을 표시해주는 함수
function refresh_file_box(path_mode){
	$('file_box_loading').style.display = "block";
	$('file_box').style.display = "none";
	//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "hidden";
	if(path_mode=="")
	{
		path_mode="none";
	}
	//debug("refresh file box : path mode : "+path_mode);
	var strResponseURL = remote_php+'?action=show_me_files&mode='+display_mode+'&sort_cond='+sort_cond+'&file_or_dir_only='+file_or_dir_only+'&path_mode='+path_mode;
	//getCurrentDirectoryPath();
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
				method:'get',
				asynchronous: true,
				onSuccess:function (responseHttpObj) {
					debug(responseHttpObj.responseText);
					display_files_list_mode(responseHttpObj.responseText);
				},
				onFailure:function (){
					displayError();
					$('file_box_loading').style.display = "none";
					$('file_box').style.display = "block";
					//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
					is_loading = false;
				}
			}
		);
}
//파일 및 디렉토리를 리스트로 표시
var gEncodedFolderList = [];
function display_files_list_mode(response){

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
	var i=0;
	var data = null;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;

	for(i=0;_list[i];i++){

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

	var table_frame_html = '<form name="files_slc_fm" id="files_slc_fm" method="POST" onsubmit="return false;" visibility="visible">'
							+'<table>'
							+'<thead><tr style="background:#DDDDDD;">'
							+'<td></td>'
							+'<td><?php echo lang_get('common_name')?></td>'
							
							+'<td><?php echo lang_get('common_time')?></td>'
							+'<td></td>'
							+'</tr></thead>'
							+'<tbody>'
							+'#body_row#'
							+'</tbody>'
							+'</table>'
							+'</form>';
	var body_row_html = '<tr>'
							+"<td>#checkbox#</td>"
							+'<td>#name#</td>'
							
							+'<td>#time#</td>'
							+'<td>#action#</td>'
						+'</tr>';


	//	Directories
	folder_name_list = [];
	

	for(i=0;i<info.dirs.length;i++){
		gEncodedFolderList[i]=info.dirs[i].encoded_file_name;
		data = info.dirs[i];
		body_row = body_row_html;
		var _file_name = data.file_name.replace(/\s/g,'&nbsp;');
		link = '<a href="" onclick="move_dir(\''+data.encoded_file_name+'\');return false;">'+_file_name+'</a>';
		rename_html = getRenameButton('directory',data.file_name);
		
		// Automatically, check the created folder
		if(data.file_name==new_dir_name){
			var _tmp = ' checked ';
		}else{
			var _tmp = '';
		}
		new_dir_name = '';

		// Disable the folder of no permission to write
		if(data.permission == 'r') _tmp += ' disabled ';
		
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			
			checkbox_html = '<input type="radio" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}else{
			checkbox_html = '<input type="radio" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'"'+_tmp+'>'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);

		
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		action_html = '';
		//if(data.permission=='w') action_html = getDeleteButton(data.file_name);
		if(data.permission=='w') action_html = getDeleteButton(data.encoded_file_name);
		//body_row = body_row.replace('#action#',action_html+rename_html);
		body_row = body_row.replace('#action#',action_html);
		
		body_row_total += body_row;
		obj_cnt++;
		
		
		folder_name_list[i] = _file_name.toLowerCase();
	}
	
	$('file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	getCurrentDirectoryPath();
	$('file_box_loading').style.display = "none";
	$('file_box').style.display = "block";
	
	//$('files_slc_fm').style.visibility = "visible";
}



//현재 디렉토리 위치를 브라우저의 Address Bar에 표시
var gEncodedCurrentPath = '';
function getCurrentDirectoryPath(){
	//debug("get current directory");	//
	
	var strResponseURL = remote_php+'?action=get_curr_dir_path';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			asynchronous: true,
			onSuccess:function (responseHttpObj) {
				var path_info = eval('(' + responseHttpObj.responseText + ')');
				current_path = path_info.curr_url;
				$('idPath').innerHTML = path_info.curr_url;
				gEncodedCurrentPath = path_info.cur_encd_path;
				is_loading = false;
			},
			onFailure:displayError
			}
		);
}
// 파일 및 디렉토리를 표시할때 각 개체마다 표시할 삭제버튼의 HTML을 리턴
function getDeleteButton(name){
	return '<a href="javascript:void(0)" onclick="one_delete(\''+sg_quote_escape(name)+'\');return false;">[<?php echo lang_get('esata_6')?>]</a>';
}
//파일 및 디렉토리를 표시할때 각 개체마다 표시할 이름 수정버튼의 HTML을 리턴
function getRenameButton(type,name){
	return '<a href="javascript:void(0)" onclick="pop_rename_box(this,\''+type+'\',\''+sg_quote_escape(name)+'\');return false;">'
			+'[Rename]';
			+'</a>';
}