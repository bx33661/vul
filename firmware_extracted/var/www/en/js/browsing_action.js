/*
 * ?? ?????? ???? ??? ??
 * (???? ??,???? ?? ? ??, ??? ???? ??,????,????,??,?? ??)
 */



//???? ??
function create_dir(){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	if($('new_directory_name').value.length>0){
		//showLoadingImage();
		$('idPath').innerHTML = "Loading...";
		
		var strResponseURL = remote_php_path+'?action=create_dir';
		var httpObj = new Ajax.Request   (
			    strResponseURL, {
				method:'post',
				parameters:Form.serialize('new_directory_fm'),
				onSuccess:function (responseHttpObj) {
					debug(responseHttpObj.responseText);
					var info = eval('(' + responseHttpObj.responseText + ')');
					
					if(parseInt(info.result) == 1){
						refresh_file_box();
					}else{
						alert(info.error_msg);
					}
					//hideLoadingImage();
				},
				onFailure:function (){
					//hideLoadingImage();
					displayError();
					}
				}
			);
		$('new_directory_name').value = '';
	}else{
		alert('Enter other folder name.');
		$('new_directory_name').focus();
	}
}


//?? ????? ??
function move_dir(dir_name){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	//showLoadingImage();
	$('idPath').innerHTML = "Loading...";
	//debug("move dir : "+dir_name);
	var strResponseURL = remote_php_path+'?action=move_dir&dir_name='+dir_name;
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				//debug(responseHttpObj.responseText);
				var info = eval('(' + responseHttpObj.responseText + ')');
				//debug(info);
				if(parseInt(info.result) == 1){
					//debug("ok");
					refresh_file_box();
				}else{
					alert(info.error_msg);
				}
				hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
}

//?? ????? ??
function move_up(){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	$('idPath').innerHTML = "Loading...";
	
	//if($('current_path').value == '/'){
	if(current_path == '/'){
		alert('Here is root path.');
		return false;
	}
	
	//showLoadingImage();
	$('idPath').innerHTML = "Loading...";
	var strResponseURL = remote_php_path+'?action=move_up';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					refresh_file_box();
				}else{
					alert(info.error_msg);
				}
				hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
}

//??? ?? ?? ????? ??? ??
function copy_selected(form_name){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert('Select data to copy.');
		return false;
	}
	
	//showLoadingImage();
	$('idPath').innerHTML = "Loading...";
	
	var strResponseURL = remote_php_path+'?action=copy';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(form_name),
			onSuccess:function (responseHttpObj) {
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						refresh_file_box();
					}else{
						alert(info.error_msg);
					}
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
}

//??? ?? ?? ????? ??? ????
function cut_selected(form_name){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert('Select data to move.');
		return false;
	}
	
	showLoadingImage();
	var strResponseURL = remote_php_path+'?action=cut';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(form_name),
			onSuccess:function (responseHttpObj) {
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						refresh_file_box();
					}else{
						alert(info.error_msg);
					}
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
}

//??? ??
function clear_selected(){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	
	showLoadingImage();
	
	var strResponseURL = remote_php_path+'?action=clear_selected';	
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize('files_slc_fm'),
			onSuccess:function (responseHttpObj) {
					refresh_file_box();
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
}

//??? ??? ?? ?? ????? ??? ??? ?? ?? ??
function paste_selected(){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	
	showLoadingImage();
	var strResponseURL = remote_php_path+'?action=paste';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						refresh_file_box();
					}else{
						alert(info.error_msg);
					}
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);	
}

//??? ?? ?? ????? ??
function delete_selected(form_name){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert('Select data to remove.');
		return false;
	}
	if(!confirm('Remove the selected data?')){
		return false;
	}
	
	//showLoadingImage();
	$('idPath').innerHTML = "Loading...";
	
	var strResponseURL = remote_php_path+'?action=delete';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(form_name),
			onSuccess:function (responseHttpObj) {
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						refresh_file_box();
					}else{
						alert(info.error_msg);
					}
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
}

//?? ?? ?? ????? ??
function one_delete(type,name){
	if(type == 'directory'){
		$('do_for_one').name = 'directory[]';
	}else if(type == 'file'){
		$('do_for_one').name = 'file[]';
	}
	$('do_for_one').value = name;
	delete_selected('do_for_one_fm');
}

//???? ?? ???? ???? ??? ? ?? ??? ??
function pop_rename_box(link_obj,type,name){
	var top = getObjTopPos(link_obj);
	var left = getObjLeftPos(link_obj);
	
	if(!Prototype.Browser.IE){
		top -= $('file_box').scrollTop;
		left -= $('file_box').scrollLeft;
	}
	
	var box_obj = $('rename_box');
	box_obj.style.visibility = 'visible';
	box_obj.style.zIndex = 1000;
	box_obj.style.top = top+'px';
	box_obj.style.left = left+'px';	
	box_obj.style.display = 'block';
	
	$('rename_fm_old_name').value = name;
	$('rename_fm_type').value = type;
	$('rename_fm_new_name').value = name;
	
	return true;
}

//???? ??? ???? ????? ??
function hide_rename_box(){
	$('rename_box').style.display = 'none';
	$('rename_fm_old_name').value = '';
	$('rename_fm_type').value = '';
	$('rename_fm_new_name').value = '';
	
   	return true;
}

//?? ?? ????? ??? ??? ? ???? ??
function rename_submit(){
	if(is_loading){
		alert('Other request is working.');
		return false;
	}
	
	var old_name = $('rename_fm_old_name').value;
	var type = $('rename_fm_type').value;
	var new_name = $('rename_fm_new_name').value;
	
	if(old_name.length == 0
		||(type != 'directory' && type != 'file')){
		alert('No file was selected.');
		return false;
	}else if(new_name.length == 0){
		alert('Enter new name.');
		return false;
	}else if(new_name == old_name){
		alert('Enter different name from current name.');
		return false;
	}
	showLoadingImage();
	
	var strResponseURL = remote_php_path+'?action=rename_file';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize('rename_fm'),
			onSuccess:function (responseHttpObj) {
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						hide_rename_box();
						refresh_file_box();
					}else{
						alert(info.error_msg);
					}
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				}
			}
		);
	
}

