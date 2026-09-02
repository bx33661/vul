<? 
	//Header("content-type: application/x-javascript"); 
	

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>




/*
 * 화면 표시를 위한 함수들
 */
//var cfg_img_width = 110;
//var cfg_img_height = 90;
var display_mode = 'list'; // 'icon' / 'list'
var file_or_dir_only = ''; // '' / 'file' / 'directory' 파일 또는 디렉토리만 불러옴.''는 전부 불러옴
var sort_cond = 'time';
var is_refresh_nas = false;
var is_refresh_esata = false;
var is_loading_nas = false;
var is_loading_esata = false;

//페이지가 처음 로딩될때 실행되는 함수
function startLoad(path_mode){
	
	//var _path = show_load(path_mode);
	switch(path_mode)
	{
		case 'burn':
		//debug (path_mode);
		is_loading_nas = false;
		refresh_file_box(path_mode);
		break;
		case 'image burn':
		//debug (path_mode);
		is_loading_esata = false;
		refresh_file_box_img(path_mode);
		break;
		default:
		break;
	}
}

//현재 디렉토리내의 디렉토리 및 파일을 표시해주는 함수

function refresh_file_box(path_mode){
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	if( is_refresh_nas ){
		alert("<?php echo lang_get('burning_msg_36')?>");
		return false;
	}
	is_refresh_nas = true;
	is_loading_nas = true;
	if(path_mode=="")
	{
		path_mode="none";
	}
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	var strResponseURL = remote_php_path+'?action=show_me_files&mode='+display_mode+'&sort_cond='+sort_cond+'&file_or_dir_only='+file_or_dir_only+'&path_mode='+path_mode;
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
				
				
				//debug(responseHttpObj.responseText);
				if(display_mode == 'list'){
					display_files_list_mode(responseHttpObj.responseText);
				}else{
					alert("Display mode error!");
					is_loading_nas = false;
					is_refresh_nas = false;
					$('idPath').innerHTML = _path;
				}
			},
			onFailure: displayError
		}
	);
	
	/* Get nas free capacity
	nasFreeCap.get();
	*/
}

//파일 및 디렉토리를 리스트로 표시
function display_files_list_mode(response){
	var _list = eval('(' + response + ')');
	var total_size = 0;
	var info = {
		dirs : [],
		files : []
	}
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
			//encoded_file_name : encodeURI(_filename)
			encoded_file_name : encodeURI(_filename).replace(/\+/g,'%2B')
		}
		if(_list[i].type == 'd'){
			_list[i].size = '';
			info.dirs[info.dirs.length] = _list[i];
		}else if(_list[i].type == '-'){
			info.files[info.files.length] = _list[i];
			total_size += _list[i].size;
			
		}else{
		}
	}
	
	
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
							+"<table width=\'300\' >"
							+'<thead><tr style="background:#DDDDDD;">'
							+"<td><input type='checkbox' id='id_chkbx_nas' onclick='chk.all_list_nas();'/></td>"
							+'<td><?php echo lang_get('common_name')?></td>'
							+'<td><?php echo lang_get('schedule_restore_1')?></td>'
							+'<td><?php echo lang_get('common_time')?></td>'
							+'</tr></thead>'
							+'<tbody>'
							+'#body_row#'
							+'</tbody>'
							+'</table>'
							+'</form>';
	var body_row_html = '<tr>'
							+'<td>#checkbox#</td>'
							+"<td >#name#</td>"
							+'<td>#size#</td>'
							+'<td >#time#</td>'
							+'</tr>';
	
	//	Directories
	folderNameListNas = [];
	for(i=0;i<info.dirs.length;i++){
		data = info.dirs[i];
		body_row = body_row_html;
		link = '<a href="" onclick="move_dir(\''+data.encoded_file_name+'\');return false;">'+char_leng.get_filename(data.file_name)+'</a>';
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>'+"<img src='../images/comnso/cms_folder.gif' />&nbsp";
			//checkbox_html += "<img src='../images/comnso/cms_folder.gif' />";
		}else{
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" onclick="folder_size.loading(this.id)">'+"<img src='../images/comnso/cms_folder.gif' />&nbsp";
			//checkbox_html += "<img src='../images/comnso/cms_folder.gif' />";
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#','<div id="chk_'+obj_cnt+'_size">'+((data.size)? bytesHumanReadable(data.size):data.size)+'</div>');
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		body_row_total += body_row;
		
		folderNameListNas[obj_cnt] = data.file_name.toLowerCase();
		obj_cnt++;
	}
	// Files
	for(i=0;i<info.files.length;i++){
		data = info.files[i];
		body_row = body_row_html;
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>';
		}else{
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" onclick="folder_size.show(this.id)">';
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#', char_leng.get_filename(data.file_name) );
		body_row = body_row.replace('#size#',bytesHumanReadable(data.size));
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		body_row_total += body_row;
		
		folderNameListNas[obj_cnt] = data.file_name.toLowerCase();
		obj_cnt++;
	}
	////park94
	gDirList=info.dirs;
	gFileList=info.files;
	//gAllFileList=get_all_list();
	
	$('file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	$('directory_info').innerHTML = info.dirs.length+" <?php echo lang_get('burning_4')?>&nbsp;&nbsp;&nbsp;&nbsp;"+info.files.length+" <?php echo lang_get('burning_5')?>&nbsp;&nbsp;&nbsp;&nbsp;"+bytesHumanReadable(total_size);
	getCurrentDirectoryPath();
	//hide_load('burn');
	//debug(response);
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
				current_path = path_info.curr_url;
				
				$('idPath').innerHTML = path_info.curr_url;
				//$('idPathImage').innerHTML = path_info.curr_url;
				
				gCurrentPath=path_info.curr_url;
				gAllFileList=get_all_list();
				is_loading_nas = false;
				is_refresh_nas = false;
			},
			onFailure:displayError
			}
		);
}

/***** e-SATA *****/
//페이지가 처음 로딩될때 실행되는 함수
function startLoad_esata(){
	refresh_file_box_esata("e_sata");
}

//현재 디렉토리내의 디렉토리 및 파일을 표시해주는 함수
function refresh_file_box_esata(path_mode){

	if( !esata.is_connected ) return false;
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	if( is_refresh_esata ){
		alert("<?php echo lang_get('burning_msg_36')?>");
		return false;
	}
	is_refresh_esata = true;
	is_loading_esata = true;
	var path_esata = document.getElementById('idPathEsata').innerHTML;
	document.getElementById('idPathEsata').innerHTML = "<?php echo lang_get('common_loading')?>";
	//temp Juny
	path_esata = "/"; 
	if(!path_mode)
	{
		path_mode="none";
	}
	debug(path_mode);
	var strResponseURL = remote_php_path+'?action=show_me_files_esata&mode='+display_mode+'&sort_cond='+sort_cond+'&file_or_dir_only='+file_or_dir_only+'&path_mode='+path_mode;
	if(usb){
		// For external device browser
		strResponseURL += '&device='+usb.selected_dev.device; // Attach device name
	}else{
		// ESATA
		strResponseURL += '&device=esata'; // Attach device name
	}
	
	
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
				
				
				debug('refresh esata file box : '+responseHttpObj.responseText);
				if(usb){
					// For external device browser
					if(responseHttpObj.responseText.search("Not connected")>-1){
						is_loading_esata = false;
						is_refresh_esata = false;
						usb.disconnect();
						return;
					}
				}else{
					// ESATA device
					if(responseHttpObj.responseText.search("Not connected")>-1){
						alert("<?php echo lang_get('esata_msg_5')?>");
						esata.is_connected = false;
						esata.disconnect();
						return;
					}
				}
				
				
				if(responseHttpObj.responseText=="NO ESATA"){
					//esata.connect();
					alert("<?php echo lang_get('esata_msg_5')?>");
					esata.is_connected = false;
					return false;
				}
				
				if(display_mode == 'list'){
					display_files_list_mode_esata(responseHttpObj.responseText);
				}else{
					alert("Display mode error!");
					is_loading_esata = false;
					is_refresh_esata = false;
					document.getElementById('idPathEsata').innerHTML = path_esata;
				}
			},
			onFailure:displayError
			}
		);
	
	/* Get esata free capacity
	if(usb){
		esataFreeCap.get(usb.selected_dev.node);
	}else{
		esataFreeCap.get();
	}
	*/
}

//파일 및 디렉토리를 리스트로 표시
function display_files_list_mode_esata(response){
	var _list = eval('(' + response + ')');
	var total_size = 0;
	var info = {
		dirs : [],
		files : []
	}
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
	for(i=0;_list[i];i++){
		var _tmp = _list[i].split(/\s+/);
		var _filename = _list[i].substr(_list[i].search(/\d\d:\d\d:\d\d \d{4}/)+14);
	if(_filename.substr(0,1) == '$') continue; _list[i] = {
			type : _tmp[0].substr(0,1) ,
			size : parseFloat(_tmp[4],10) ,
			file_name : _filename ,
			date : _tmp[9]+'/'+_month[_tmp[6]]+'/'+_tmp[7] ,
			time : _tmp[8] ,
			selected : '' ,
			encoded_file_name : encodeURI(_filename).replace('+','%2B')
		}
		if(_list[i].type == 'd'){
			_list[i].size = '';
			info.dirs[info.dirs.length] = _list[i];
		}else if(_list[i].type == '-'){
			info.files[info.files.length] = _list[i];
			total_size += _list[i].size;
			
		}else{
		}
	}
	
	
	var i=0;
	var data = null;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var table_frame_html = '<form name="files_esata_fm" id="files_esata_fm" method="POST" onsubmit="return false;">'
							+"<table width=\'300\' style=\'word-break:break-all\'>"
							+'<thead><tr style="background:#DDDDDD;">'
							+"<td><input type='checkbox' id='id_chkbx_esata' onclick='chk.all_list_esata();'/></td>"
							+'<td><?php echo lang_get('common_name')?></td>'
							+'<td><?php echo lang_get('schedule_restore_1')?></td>'
							+'<td><?php echo lang_get('common_time')?></td>'
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
	folderNameListEsata = [];
	for(i=0;i<info.dirs.length;i++){
		data = info.dirs[i];
		body_row = body_row_html;
		link = '<a href="" onclick="move_dir_esata(\''+data.encoded_file_name+'\');return false;">'+char_leng.get_filename(data.file_name)+'</a>';
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_esata_'+obj_cnt+'" value="'+data.file_name+'" checked>'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}else{
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_esata_'+obj_cnt+'" value="'+data.file_name+'" onclick="folder_size_ext.loading(this.id)">'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#','<div id="chk_esata_'+obj_cnt+'_size">'+((data.size)? bytesHumanReadable(data.size):data.size)+'</div>');
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		body_row_total += body_row;
		
		folderNameListEsata[obj_cnt] = data.file_name.toLowerCase();
		obj_cnt++;
	}
	// Files
	for(i=0;i<info.files.length;i++){
		data = info.files[i];
		body_row = body_row_html;
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_esata_'+obj_cnt+'" value="'+data.file_name+'" checked>';
		}else{
			checkbox_html = '<input type="checkbox" name="file[]" id="chk_esata_'+obj_cnt+'" value="'+data.file_name+'" onclick="folder_size_ext.show(this.id)">';
		}
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#', char_leng.get_filename(data.file_name) );
		body_row = body_row.replace('#size#',bytesHumanReadable(data.size));
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		body_row_total += body_row;
		
		folderNameListEsata[obj_cnt] = data.file_name.toLowerCase();
		obj_cnt++;
	}
	////park94
	gDirList_esata=info.dirs;
	gFileList_esata=info.files;
	//gAllFileList_esata=get_all_list_esata();
	
	$('file_box_esata').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	$('directory_info_esata').innerHTML = info.dirs.length+" <?php echo lang_get('burning_4')?>&nbsp;&nbsp;&nbsp;&nbsp;"+info.files.length+" <?php echo lang_get('burning_5')?>&nbsp;&nbsp;&nbsp;&nbsp;"+bytesHumanReadable(total_size);	
	getCurrentDirectoryPath_esata();
	
}
function getCurrentDirectoryPath_esata(){
	var strResponseURL = remote_php_path+'?action=get_curr_dir_path_esata';
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
				
				
				//debug(responseHttpObj.responseText);
				var path_info = eval('(' + responseHttpObj.responseText + ')');
				current_path_esata = path_info.curr_url;
				
				$('idPathEsata').innerHTML = path_info.curr_url;
				
				gCurrentPath_esata=path_info.curr_url;
				gAllFileList_esata=get_all_list_esata();
				is_loading_esata = false;
				is_refresh_esata = false;
			},
			onFailure:displayError
			}
		);
}

//=======================================================//
// e-SATA : make file list of e-SATA device
//=======================================================//
function get_all_list_esata(){
	var _tmp=new Array();
	var _oj=null;
	var _time=null;
	for(var i=0;gDirList_esata[i];i++)
	{
		 _oj=gDirList_esata[i];
		 _time=_oj.date+" "+_oj.time;
		 
		_tmp[i]=new file_info(_oj.file_name,_oj.size,"directory",_time,"no",gCurrentPath_esata);
	}
	for(var j=0;gFileList_esata[j];j++)
	{	
		var _oj=gFileList_esata[j];
		_time=_oj.date+" "+_oj.time;
		_tmp[i+j]=new file_info(_oj.file_name,_oj.size,_oj.subtype,_time,"no",gCurrentPath_esata);
	}
	return _tmp;
}
