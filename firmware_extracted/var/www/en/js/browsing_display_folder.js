////park94
//var gDirList=new Array();
//var gFileList=new Array();

/*
 * 화면 표시를 위한 함수들
 */

var cfg_img_width = 110;
var cfg_img_height = 90;
var display_mode = 'list'; // 'icon' / 'list'
//var display_mode = 'test'; // 'icon' / 'list'
var file_or_dir_only = ''; // '' / 'file' / 'directory' 파일 또는 디렉토리만 불러옴.''는 전부 불러옴
var sort_cond = 'time';

//페이지가 처음 로딩될때 실행되는 함수 //
// path_mode : rip/store/burn/schedule //
function startLoad(path_mode){
	//showLoadingImage();
	$('idPath').innerHTML = "Loading...";
	refresh_file_box(path_mode);
	//hideLoadingImage();
}

//현재 디렉토리내의 디렉토리 및 파일을 표시해주는 함수
function refresh_file_box(path_mode){
	if(path_mode=="")
	{
		path_mode="none";
	}
	debug("refresh file box : path mode : "+path_mode);
	var strResponseURL = remote_php_path+'?action=show_me_files&mode='+display_mode+'&sort_cond='+sort_cond+'&file_or_dir_only='+file_or_dir_only+'&path_mode='+path_mode;
	//getCurrentDirectoryPath();

	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			asynchronous: false,
			onSuccess:function (responseHttpObj) {
				if(display_mode == 'list'){
					display_files_list_mode(responseHttpObj.responseText);
				}else{
					display_files_list_mode(responseHttpObj.responseText);
				}
			},
			onFailure:displayError
			}
		);
}


//파일 및 디렉토리를 리스트로 표시
function display_files_list_mode(response){
	debug(response);	//**//
	
	var info = eval('(' + response + ')');
	var total_size = info.total_size;
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
							+'<table>'
							+'<thead><tr style="background:#DDDDDD;">'
							+'<td>select</td>'
							+'<td><a href="" onclick="changeSort(\'name\');return false;">name</a></td>'
							+'<td><a href="" onclick="changeSort(\'size\');return false;">size</a></td>'
							+'<td><a href="" onclick="changeSort(\'time\');return false;">time</a></td>'
							+'<td>action</td>'
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
							+'<td>#action#</td>'
						+'</tr>';
	
	//	Directories
	for(i=0;i<info.dirs.length;i++){
		data = info.dirs[i];
		body_row = body_row_html;
		link = '<a href="" onclick="move_dir(\''+data.encoded_file_name+'\');return false;">'+data.file_name+'</a>';
		rename_html = getRenameButton('directory',data.file_name);
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}else{
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'">'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#','');
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		action_html = getDeleteButton('directory',data.file_name);
		body_row = body_row.replace('#action#',action_html+rename_html);
		
		body_row_total += body_row;
		obj_cnt++;
	}
	
	
	////park94
	//gDirList=info.dirs;
	//gFileList=info.files;
	//debug("aa");
	$('file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	//$('directory_info').innerHTML = info.dirs.length+'directories&nbsp;&nbsp;&nbsp;&nbsp;'+info.files.length+'files&nbsp;&nbsp;&nbsp;&nbsp;'+total_size;	
//$('idPath').innerHTML = "test";
getCurrentDirectoryPath();
}
////test
function display_files_list_mode_test(response){
	var info = eval('(' + response + ')');
	var total_size = info.total_size;
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
							+"<table width='320px' height='190px'>"
							+'<thead><tr style="background:#DDDDDD;">'
							+'<td>select</td>'
							+'<td><a href="" onclick="changeSort(\'name\');return false;">name</a></td>'
							+'<td><a href="" onclick="changeSort(\'size\');return false;">size</a></td>'
							+'<td><a href="" onclick="changeSort(\'time\');return false;">time</a></td>'
							+'<td>action</td>'
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
							+'<td>#action#</td>'
						+'</tr>';
	
	//	Directories
	for(i=0;i<info.dirs.length;i++){
		data = info.dirs[i];
		body_row = body_row_html;
		link = '<a href="" onclick="move_dir(\''+data.encoded_file_name+'\');return false;">'+data.file_name+'</a>';
		rename_html = getRenameButton('directory',data.file_name);
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}else{
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'">'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#','');
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		action_html = getDeleteButton('directory',data.file_name);
		body_row = body_row.replace('#action#',action_html+rename_html);
		
		body_row_total += body_row;
		obj_cnt++;
	}
	
	for(i=0;i<info.files.length;i++){
		data = info.files[i];
		body_row = body_row_html;
		link = '<a href="" onclick="show_download_window(\''+data.encoded_url+'\');return false;">'+data.file_name+'</a>';
		rename_html = getRenameButton('file',data.file_name);
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>';
		}else{
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'">';
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#',data.size);
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		action_html = getDeleteButton('file',data.file_name);
		body_row = body_row.replace('#action#',action_html+rename_html);

		body_row_total += body_row;
		obj_cnt++;
	}
	$('file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	$('directory_info').innerHTML = info.dirs.length+'directories&nbsp;&nbsp;&nbsp;&nbsp;'+info.files.length+'files&nbsp;&nbsp;&nbsp;&nbsp;'+total_size;	

	////park94
	gDirList=info.dirs;
	gFileList=info.files;
	
}
//파일 및 디렉토리의 표시모드가 아이콘일때 그림파일의 경우 아이콘 대신 그림을 표시하므로 그림의 사이즈를 조절
function resizeImg(img){
	if((img.width/cfg_img_width) > (img.height/cfg_img_height)
			&& img.width > cfg_img_width){
		img.width = cfg_img_width;
	}else if(img.height > cfg_img_height){
		img.height = cfg_img_height;
	}
}

//파일을 다운로드 할수 있는 창을 띄움
function show_download_window(url){
	var win = window.open("../php/browsing_file_download.php?path="+url,'WIN','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=10px,height=10px'); 
	win.focus(); 
}

//현재 디렉토리 위치를 브라우저의 Address Bar에 표시
function getCurrentDirectoryPath(){
	//debug("get current directory");	//
	
	var strResponseURL = remote_php_path+'?action=get_curr_dir_path';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			asynchronous: false,
			onSuccess:function (responseHttpObj) {
				//debug(responseHttpObj.responseText);
				var path_info = eval('(' + responseHttpObj.responseText + ')');
				//debug(path_info);	//
				current_path = path_info.curr_url;
				$('idPath').innerHTML = path_info.curr_url;
				//$('idPath').innerHTML = "test";
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