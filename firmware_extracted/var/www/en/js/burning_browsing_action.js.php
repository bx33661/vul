<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


<!--
/*
 * 파일 브라우저에서 실행되는 기능을 구현
 * (디렉토리 변경,디렉토리 생성 및 삭제, 파일과 디렉토리 복사,잘라내기,붙여넣기,삭제,이름 변경)
 */



//디렉토리 생성
/*function create_dir(){
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
}*/


//다른 디렉토리로 이동
function move_dir(dir_name){
	//debug("@move_dir : move to "+dir_name);
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	//showLoadingImage();
	//$('idPath').innerHTML = "Moving to "+dir_name; // when moving to korean-named folder, dir_name breaks
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('burning_msg_37')?>";
	is_loading = true;
	var strResponseURL = remote_php_path+'?action=move_dir&dir_name='+dir_name;
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				//debug(responseHttpObj.responseText);
				var info = eval('(' + responseHttpObj.responseText + ')');
				//debug(info);
				if(parseInt(info.result) == 1){
					//debug("ok");
					is_loading = false;
					refresh_file_box();
				}else{
					alert(info.error_msg);
					is_loading = false;
					$('idPath').innerHTML = _path;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('idPath').innerHTML = _path;
				}
			}
		);
}
function move_dir_img(dir_name){
	//debug("@move_dir : move to "+dir_name);
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	//showLoadingImage();
	//$('idPathImage').innerHTML = "Moving to "+dir_name;	// when moving to korean-named folder, dir_name breaks
	var _path = $('idPathImage').innerHTML;
	$('idPathImage').innerHTML = "<?php echo lang_get('burning_msg_37')?>";
	//debug("move dir : "+dir_name);
	var strResponseURL = remote_php_path+'?action=move_dir&dir_name='+dir_name;
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				//debug(responseHttpObj.responseText);
				var info = eval('(' + responseHttpObj.responseText + ')');
				//debug(info);
				if(parseInt(info.result) == 1){
					//debug("ok");
					is_loading = false;
					refresh_file_box_img();
				}else{
					alert(info.error_msg);
					is_loading = false;
					$('idPathImage').innerHTML = _path;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('idPathImage').innerHTML = _path;
				}
			}
		);
}
//상위 디렉토리로 이동
function move_up(){
	debug("move up");
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	if(current_path == '/'){
		alert("<?php echo lang_get('extraction_msg_19')?>");
		$('idPath').innerHTML = "/";
		return false;
	}
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('burning_msg_32')?>";
	debug("");
	//if($('current_path').value == '/'){
	
	
	//showLoadingImage();
	//$('idPath').innerHTML = "Loading...";
	var strResponseURL = remote_php_path+'?action=move_up';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					is_loading = false;
					refresh_file_box();
				}else{
					alert(info.error_msg);
					is_loading = false;
					$('idPath').innerHTML = _path;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('idPath').innerHTML = _path;
				}
			}
		);
}
function move_up_img(){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	//$('idPathImage').innerHTML = "Loading...";
	
	//if($('current_path').value == '/'){
	if(current_path == '/'){
		alert("<?php echo lang_get('extraction_msg_19')?>");
		$('idPathImage').innerHTML = "/";
		return false;
	}
	var _path = $('idPathImage').innerHTML;
	$('idPathImage').innerHTML = "<?php echo lang_get('burning_msg_32')?>";
	//showLoadingImage();
	var strResponseURL = remote_php_path+'?action=move_up';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					is_loading = false;
					refresh_file_box_img();
				}else{
					alert(info.error_msg);
					is_loading = false;
					$('idPathImage').innerHTML = _path;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('idPathImage').innerHTML = _path;
				}
			}
		);
}
//체크된 파일 또는 디렉토리를 버퍼에 복사
function copy_selected(form_name){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert("<?php echo lang_get('extraction_msg_15')?>");
		return false;
	}
	
	//showLoadingImage();
	//$('idPath').innerHTML = "Loading...";
	
	var strResponseURL = remote_php_path+'?action=copy';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(form_name),
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						is_loading = false;
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

//체크된 파일 또는 디렉토리를 버퍼에 잘라내기
function cut_selected(form_name){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert("<?php echo lang_get('extraction_msg_16')?>");
		return false;
	}
	
	showLoadingImage();
	var strResponseURL = remote_php_path+'?action=cut';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(form_name),
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						is_loading = false;
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

//버퍼를 비움
function clear_selected(){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	showLoadingImage();
	
	var strResponseURL = remote_php_path+'?action=clear_selected';	
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize('files_slc_fm'),
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
					is_loading = false;
					refresh_file_box();
					//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				}
			}
		);
}

//버퍼에 저장된 파일 또는 디렉토리를 지정된 위치로 복사 또는 이동
function paste_selected(){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	//showLoadingImage();
	var strResponseURL = remote_php_path+'?action=paste';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						is_loading = false;
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
}

//체크된 파일 또는 디렉토리를 삭제
function delete_selected(form_name){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}	
	if(Form.serialize(form_name).length < 1){
		alert("<?php echo lang_get('extraction_msg_17')?>");
		return false;
	}
	if(!confirm("<?php echo lang_get('extraction_msg_24')?>")){
		return false;
	}	
	//showLoadingImage();
	//$('idPath').innerHTML = "Loading...";
	is_loading = true;
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('extraction_msg_25')?>";
	var strResponseURL = remote_php_path+'?action=delete';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(form_name),
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				debug(responseHttpObj.responseText);
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						is_loading = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						$('idPath').innerHTML = _path;
						is_loading = false;
					}
					//debug("aa");
					//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				$('idPath').innerHTML = _path;
				displayError();
				}
			}
		);
}

//개별 파일 또는 디렉토리를 삭제
function one_delete(type,name){
	if(type == 'directory'){
		$('do_for_one').name = 'directory[]';
	}else if(type == 'file'){
		$('do_for_one').name = 'file[]';
	}
	$('do_for_one').value = name;
	delete_selected('do_for_one_fm');
}

//이름변경 메뉴 선택시에 새이름을 입력할 수 있는 메뉴를 표시
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

//이름변경 메뉴의 새이름을 입력박스를 감춤
function hide_rename_box(){
	$('rename_box').style.display = 'none';
	$('rename_fm_old_name').value = '';
	$('rename_fm_type').value = '';
	$('rename_fm_new_name').value = '';
	
   	return true;
}

//파일 또는 디렉토리의 이름을 입력한 새 이름으로 변경
function rename_submit(){
	if(is_loading){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	var old_name = $('rename_fm_old_name').value;
	var type = $('rename_fm_type').value;
	var new_name = $('rename_fm_new_name').value;
	
	if(old_name.length == 0
		||(type != 'directory' && type != 'file')){
		alert("<?php echo lang_get('extraction_msg_20')?>");
		return false;
	}else if(new_name.length == 0){
		alert("<?php echo lang_get('extraction_msg_21')?>");
		return false;
	}else if(new_name == old_name){
		alert("<?php echo lang_get('extraction_msg_22')?>");
		return false;
	}
	//showLoadingImage();
	
	var strResponseURL = remote_php_path+'?action=rename_file';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize('rename_fm'),
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
					var info = eval('(' + responseHttpObj.responseText + ')');
					if(parseInt(info.result) == 1){
						//hide_rename_box();
						is_loading = false;
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
	
}
//-->