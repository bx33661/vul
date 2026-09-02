var file_form_prefix = 'file_upload_frm_';
var file_input_prefix = 'up_file_';
var target_frm_prefix = 'upload_trgt_';
var upload_path_input_prefix = 'upload_path_';

var form_stack = new Array();
var file_info = new Array();
var now_uploading_index = null;

//start to upload a file
function form_submit(){
	//before request, check the working state
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	var form_elements = document.getElementsByTagName('form');
	var form_name = null;
	var idx = null;
	var file_path = null;
	var file_name = null;
	var i;
	for(i=0;i<form_elements.length;i++){
		form_name = form_elements[i].name;
		if(form_name.lastIndexOf(file_form_prefix) == 0){
			idx = form_name.replace(file_form_prefix,'');
			file_path = $(file_input_prefix+idx).value;
			if(file_path.length>0){
				form_stack.push(idx);
				file_info[idx] = {"file_size":0,"upload_started":false,"upload_completed":false};
				$(upload_path_input_prefix+idx).value = current_path;
				file_name = getBaseName(file_path);
				$('file_name_'+idx).innerHTML = file_name;
				$('progress_row_'+idx).style.display = 'block';				
				file_info[idx].file_size = get_file_size(idx);
				file_info[idx].file_size;
			}
		}
	}
	form_stack.reverse();

	$('upload_file_select').style.display = 'none';
	$('progress_box').style.display = 'block';

	next_form_submit();
}

//get file size of the index
function get_file_size(idx){ 
	var fObj = $(file_form_prefix+idx);
	var file_size = 0;
	var upload_id = $('UPLOAD_IDENTIFIER_'+idx).value;
	var old_action = fObj.action;
	fObj.action = '../php/browsing_rubbish.php';
	$('MAX_FILE_SIZE_'+idx).value=1;
	
	fObj.submit();
	
	//2008.9.25: after install uploadprogress, use next line
	//file_size = observe_file_size(upload_id);
	file_size = 0;
	
	fObj.action = old_action;
	$('MAX_FILE_SIZE_'+idx).value='';
	
	return file_size;
}

//request the repitition until the response
function observe_file_size(upload_id){
	var strResponseURL = remote_php_path+'?action=tell_me_progress&id='+upload_id;
	var ret_val = false;
	var httpObj = new Ajax.Request   (
	    strResponseURL, {
		method:'get',
		asynchronous: false,
		onSuccess:function (responseHttpObj) {
			var info = eval('(' + responseHttpObj.responseText + ')');
			
			if(parseInt(info[0]) == 1){
				ret_val = info[5];
			}else{
				ret_val = 0;
				//2008.9.25: after install uploadprogress, use next line
				//ret_val = observe_file_size(upload_id);
			}
		},
		onFailure:displayError
		}
	);
	return ret_val;
}

//transfer next form
function next_form_submit(){
	now_uploading_index = null;
	
	if(form_stack.length>0){
		var idx = form_stack.pop();
		now_uploading_index = idx;
		var upload_id = $('UPLOAD_IDENTIFIER_'+idx).value;
		$(file_form_prefix+idx).submit();
		
		//2008.9.25: after install uploadprogress, use next line
		//observe_progress_bar(idx,upload_id);
	}else{
		return false;
	}
}

//every 1 sec, refresh the upload Progress Bar state
function observe_progress_bar(idx,upload_id){ 
	var strResponseURL = remote_php_path+'?action=tell_me_progress&id='+upload_id;
	var httpObj = new Ajax.Request   (
	    strResponseURL, {
		method:'get',
		onSuccess:function (responseHttpObj) {
			var info = eval('(' + responseHttpObj.responseText + ')');
			if(info.length==7){
				file_info[idx].upload_started = true;
				var files_uploaded = parseInt(info[0]);
				if(files_uploaded == 1){
					file_info[idx].upload_completed = true;
				}else{
					file_info[idx].upload_completed = false;
					refresh_progress_bar(upload_id,info[2],info[3],info[4],info[5],info[6]);
					refresh_total_progress_bar(info[3],info[4],responseHttpObj.responseText);
				}
			}else{
				if(file_info[idx].upload_started){
					file_info[idx].upload_completed = true;
				}
			}

			if(file_info[idx].upload_completed){
				complete_upload(idx);
			}else{ 
				setTimeout("observe_progress_bar("+idx+",'"+upload_id+"')",1000);
			}
		},
		onFailure:displayError
		}
	);
}

//주어진 값들로 Progress Bar 변경
function refresh_progress_bar(upload_id,time_elapsed,speed,bytes_uploaded,bytes_total,time_remain){
	var percentage = parseInt((parseInt(bytes_uploaded)/parseInt(bytes_total))*100);
	var idx = upload_id.substr(upload_id.length-1,1);
	
	//업로드 되고있는 파일의 Progress Bar 갱신
	$('progress_bar_uploaded_'+idx).style.width = percentage+'%';
	$('progress_bar_text_'+idx).innerHTML = percentage
											+'%&nbsp;'
											+bytesHumanReadable(bytes_uploaded)
											+'/'
											+bytesHumanReadable(bytes_total);
}

//주어진 값으로 총용량 Progress Bar 갱신
function refresh_total_progress_bar(speed,bytes_uploaded){
	var total_file_count = file_info.reallen();
	var uploaded_file_count = 1;
	var upload_completed_file_size = parseInt(bytes_uploaded);
	for(var i=0;i<file_info.length;i++){
		if(file_info[i].upload_completed){
			uploaded_file_count++;
			upload_completed_file_size += parseInt(file_info[i].file_size);
		}
	}
	
	file_total_size = file_info.el_sum('file_size');
	percentage = Math.round((upload_completed_file_size/file_total_size)*100);
	$('progress_bar_uploaded_total').style.width = percentage+'%';
	$('progress_bar_text_total').innerHTML = percentage+'%&nbsp;'+bytesHumanReadable(speed)+'&nbsp;'
											+uploaded_file_count+'/'+total_file_count;
}

//파일 업로드가 끝나면 호출되며 Progress Bar를 완료상태로 바꿈
function complete_upload(idx){
	$('progress_bar_uploaded_'+idx).className = 'progress_bar_complete';
	$('progress_bar_uploaded_'+idx).style.width = '100%';
	$('progress_bar_text_'+idx).innerHTML = 'Upload Complete';
	refresh_file_box();
	
	if(form_stack.length == 0){
		$('progress_bar_uploaded_total').className = 'progress_bar_complete';
		$('progress_bar_uploaded_total').style.width = '100%';
		$('progress_bar_text_total').innerHTML = 'Total Complete';
	}else{
		refresh_total_progress_bar(0,0);
		next_form_submit();
	}
}

//전송 취소
//이미 전송 완료된 파일은 삭제되지 않음
function stop_all(){
	window.location = "";
}

//파일 업로드 태그를 지정한 갯수만큼 표시
function show_file_tag(tag_count){
	var i=0;
	var prefix = 'file_tag_';
	
	for(i=0;$(prefix+i);i++){
		if(i<=tag_count) $(prefix+i).style.display = 'block';
		else $(prefix+i).style.display = 'none';
	}
}