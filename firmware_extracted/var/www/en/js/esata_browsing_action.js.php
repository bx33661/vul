<? 
	//Header("content-type: application/x-javascript"); 
	

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

/************************** NAS *************************************/
//다른 디렉토리로 이동
function move_dir(dir_name){
	//debug("@move_dir : move to "+dir_name);
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	//showLoadingImage();
	//$('idPath').innerHTML = "Moving to "+dir_name; // when moving to korean-named folder, dir_name breaks
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('burning_msg_37')?>";
	is_loading_nas = true;
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
					is_loading_nas = false;
					refresh_file_box();
				}else{
					alert(info.error_msg);
					is_loading_nas = false;
					$('idPath').innerHTML = _path;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				is_loading_nas = false;
				$('idPath').innerHTML = _path;
				}
			}
		);
}
//상위 디렉토리로 이동
function move_up(){
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	if(current_path == '/'){
		alert("<?php echo lang_get('extraction_msg_19')?>");
		$('idPath').innerHTML = "/";
		return false;
	}
	var _path = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('burning_msg_37')?>";
	is_loading_nas = true;
	
	
	//showLoadingImage();
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
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
					is_loading_nas = false;
					refresh_file_box();
				}else{
					alert(info.error_msg);
					is_loading_nas = false;
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
//체크된 파일 또는 디렉토리를 버퍼에 복사
function copy_selected(form_name){
	if(is_loading_nas){
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
						is_loading_nas = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						is_loading_nas = false;
					}
				},
			onFailure:function (){
				displayError();
				}
			}
		);
}

//체크된 파일 또는 디렉토리를 버퍼에 잘라내기
function cut_selected(form_name){
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert('Select data to move.');
		return false;
	}
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
						is_loading_nas = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						is_loading_nas = false;
					}
				},
			onFailure:function (){
				displayError();
				}
			}
		);
}

//버퍼를 비움
function clear_selected(){
	if(is_loading_nas){
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
				
				
					is_loading_nas = false;
					refresh_file_box();
					is_loading_nas = false;
				},
			onFailure:function (){
				displayError();
				}
			}
		);
}

//버퍼에 저장된 파일 또는 디렉토리를 지정된 위치로 복사 또는 이동
function paste_selected(){
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
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
						is_loading_nas = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						is_loading_nas = false;
					}
				},
			onFailure:function (){
				displayError();
				}
			}
		);	
}

//체크된 파일 또는 디렉토리를 삭제
function delete_selected(form_name){
	if(is_loading_nas){
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
	is_loading_nas = true;
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
						is_loading_nas = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						$('idPath').innerHTML = _path;
						is_loading_nas = false;
					}
					//debug("aa");
					//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				$('idPath').innerHTML = _path;
				displayError();
				is_loading_nas = false;
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

/************************** e-SATA *************************************/
//다른 디렉토리로 이동
function move_dir_esata(dir_name){
	if( !esata.is_connected ){
		if(usb){
		alert("<?php echo lang_get('usb_msg_4')?>");	
		}
		else{
		alert("<?php echo lang_get('esata_msg_5')?>");
		}
		return false;
	}
	//debug("@move_dir : move to "+dir_name);
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	//showLoadingImage();
	//$('idPathEsata').innerHTML = "Moving to "+dir_name; // when moving to korean-named folder, dir_name breaks
	var _path = $('idPathEsata').innerHTML;
	$('idPathEsata').innerHTML = "<?php echo lang_get('burning_msg_37')?>";
	is_loading_esata = true;
	//debug("move dir : "+dir_name);
	var strResponseURL = remote_php_path+'?action=move_dir_esata&dir_name='+dir_name;
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
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				debug('move dir esata : '+responseHttpObj.responseText);
				if(usb){
					// For external device browser
					if(responseHttpObj.responseText.search("Not connected")>-1){
						usb.disconnect();
						is_loading_esata = false;
						is_refresh_esata = false;
						return;
					}
				}else{
					// ESATA device
					if(responseHttpObj.responseText.search("Not connected")>-1){
						if(usb){
						alert("<?php echo lang_get('usb_msg_4')?>");	
						}
						else{
						alert("<?php echo lang_get('esata_msg_5')?>");
						}
						esata.is_connected = false;
						esata.disconnect();
						return;
					}
				}
				
				
				var info = eval('(' + responseHttpObj.responseText + ')');
				//debug(info);
				if(parseInt(info.result) == 1){
					//debug("ok");
					is_loading_esata = false;
					refresh_file_box_esata();
					//esata.connect();
				}else{
					alert(info.error_msg);
					is_loading_esata = false;
					$('idPathEsata').innerHTML = _path;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('idPathEsata').innerHTML = _path;
				}
			}
		);
}

//상위 디렉토리로 이동
function move_up_esata(){
	//debug("move up esata");
	if( !esata.is_connected ){
		if(usb){
		alert("<?php echo lang_get('usb_msg_4')?>");	
		}
		else{
		alert("<?php echo lang_get('esata_msg_5')?>");
		}
		return false;
	}
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	
	if(current_path_esata == '/'){
		alert("<?php echo lang_get('extraction_msg_19')?>");
		return false;
	}
	var _path = $('idPathEsata').innerHTML;
	$('idPathEsata').innerHTML = "<?php echo lang_get('burning_msg_37')?>";
	is_loading_esata = true;
	var strResponseURL = remote_php_path+'?action=move_up_esata';
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
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				if(usb){
					// For external device browser
					if(responseHttpObj.responseText.search("Not connected")>-1){
						usb.disconnect();
						is_loading_esata = false;
						is_refresh_esata = false;
						return;
					}
				}else{
					// ESATA device
					if(responseHttpObj.responseText.search("Not connected")>-1){
						if(usb){
						alert("<?php echo lang_get('usb_msg_4')?>");	
						}
						else{
						alert("<?php echo lang_get('esata_msg_5')?>");
						}
						esata.is_connected = false;
						esata.disconnect();
						return;
					}
				}
				
				
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					is_loading_esata = false;
					refresh_file_box_esata();
					//esata.connect();
				}else{
					alert(info.error_msg);
					is_loading_esata = false;
					$('idPathEsata').innerHTML = _path;
				}
			},
			onFailure:function (){
				displayError();
				$('idPathEsata').innerHTML = _path;
				}
			}
		);
}

//체크된 파일 또는 디렉토리를 버퍼에 복사
function copy_selected_esata(src_form_name,dst_form_name){
	//juny
	if( !esata.is_connected ){
		if(usb){
		alert("<?php echo lang_get('usb_msg_4')?>");	
		}
		else{
		alert("<?php echo lang_get('esata_msg_5')?>");
		}
		return false;
	}
	if(is_loading_esata||is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	if(Form.serialize(src_form_name).length < 1){
		alert("<?php echo lang_get('burning_msg_23')?>");
		return false;
	}
	// Show message //
	is_loading_esata = true;
	is_loading_nas = true;
	if(src_form_name=="files_slc_fm"){
		if(usb){
			$('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_8')?> "+usb.selected_dev.device+" <?php echo lang_get('usb_msg_8_1')?>";
		}else{
			$('idBurnSize').innerHTML = "<?php echo lang_get('esata_msg_13')?>";
		}
		
	}else{
		if(usb){
			$('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_9')?> "+usb.selected_dev.device+" <?php echo lang_get('usb_msg_9_1')?>";
		}else{
			$('idBurnSize').innerHTML = "<?php echo lang_get('esata_msg_14')?>";
		}
	}
	
	// Show copying image
	//copy_layer.open();
	
	if(src_form_name=="files_slc_fm")
	{
		var tmp = "copy_esata_n2e";
		var _op = "e_sata";
		_op_mode = "e_sata";
	}else
	{
		var tmp = "copy_esata_e2n";
		var _op = "burn";
		_op_mode = "burn";
	}
	debug(tmp);
	var strResponseURL = remote_php_path+'?action='+tmp;
	if(usb){
		// For external device browser
		strResponseURL += '&device='+usb.selected_dev.device; // Attach device name
	}else{
		// ESATA
		strResponseURL += '&device=esata'; // Attach device name
	}
	$('l_copy_img').style.display = 'block';
	copy_layer.init();
	copy_layer.open();
	
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'post',
			parameters:Form.serialize(src_form_name),
			onSuccess:function (responseHttpObj) {
				
				// Session out
				if(responseHttpObj.responseText == '-99'){
					alert("<? echo lang_get('login_msg_6') ?>");
					window.location.href = '../login/login.php';
					return;
				}
				
				
				debug(responseHttpObj.responseText);
				
				if(usb){
					// For external device browser
					if(responseHttpObj.responseText.search("Not connected")>-1){
						usb.disconnect();
						is_loading_esata = false;
						is_refresh_esata = false;
						//juny
						copy_layer.close();
						window.location.reload();
						
						return;
					}
				}else{
					// ESATA device
					if(responseHttpObj.responseText.search("Not connected")>-1){
						if(usb){
						alert("<?php echo lang_get('usb_msg_4')?>");	
						}
						else{
						alert("<?php echo lang_get('esata_msg_5')?>");
						}
						esata.is_connected = false;
						esata.disconnect();
						//juny
						copy_layer.close();
						window.location.reload();

						return;
					}
				}
				
				//var info = eval('(' + responseHttpObj.responseText + ')');
				
				if(_op == "burn"){
					is_loading_nas = false;
					refresh_file_box();
					is_loading_esata = false;
				}else{
					is_loading_esata = false;
					refresh_file_box_esata();
					is_loading_nas = false;
				}
				clearInterval(copy_layer.read_timer);
				copy_layer.close();
				copy_layer.show_msg('Complete');
				
				/*if(parseInt(info.result) == 1){
					//refresh_file_box("burn");// or
					//refresh_file_box_esata("e_sata");//
					//debug("local"+_op);
					//debug("global"+_op_mode);
					if(_op == "burn"){
						is_loading_nas = false;
						//refresh_file_box();
						is_loading_esata = false;
					}else{
						is_loading_esata = false;
						//refresh_file_box_esata();
						is_loading_nas = false;
					}
					//var _msg = "<?php echo lang_get('esata_msg_10')?>";
					copy_layer.open();
				}else{
					if(info.result==11){
						alert("<?php echo lang_get('esata_msg_10')?>");
					}
					else{
						alert(info.error_msg);
					}
					
					is_loading_esata = false;
					is_loading_nas = false;
					var _msg = "Error";
					if(info.result==10){
						_msg="<?php echo lang_get('esata_7')?>";
					}else if(parseInt(info.result)<0){
						if(info.error_msg){
							var _msg = info.error_msg;
						}else{
							var _msg = 'Unknown error';
						}
					}
					$('idBurnSize').innerHTML = _msg;
				}*/
				
				// Hide copying image
				//copy_layer.show_msg(_msg);
			},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				// copying image
				$('idBurnSize').value = "<?php echo lang_get('common_error')?>";
				//copy_layer.close();
				is_loading_nas = false;
				is_loading_esata = false;
				}
			}
		);
}

//체크된 파일 또는 디렉토리를 버퍼에 잘라내기
function cut_selected_esata(form_name){
	if( !esata.connected() ) return false;
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	if(Form.serialize(form_name).length < 1){
		alert("<?php echo lang_get('extraction_msg_17')?>");
		return false;
	}
	is_loading_esata = true;
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
						is_loading_esata = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						is_loading_esata = false;
					}
				},
			onFailure:function (){
				displayError();
				}
			}
		);
}

//버퍼를 비움
function clear_selected_esata(){
	if( !esata.connected() ) return false;
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	is_loading_esata = true;
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
				
				
				is_loading_esata = false;
					refresh_file_box();
				},
			onFailure:function (){
				displayError();
				is_loading_esata = false;
				}
			}
		);
}

//버퍼에 저장된 파일 또는 디렉토리를 지정된 위치로 복사 또는 이동
function paste_selected_esata(){
	if( !esata.connected() ) return false;
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	is_loading_esata = true;
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
						is_loading_esata = false;
						refresh_file_box();
					}else{
						alert(info.error_msg);
						is_loading_esata = false;
					}
				},
			onFailure:function (){
				displayError();
				}
			}
		);	
}

//체크된 파일 또는 디렉토리를 삭제
function delete_selected_esata_esata(form_name){
	if(!esata.is_connected){
		if(usb){
		alert("<?php echo lang_get('usb_msg_4')?>");	
		}
		else{
		alert("<?php echo lang_get('esata_msg_5')?>");
		}
		return false;
	}
	if(is_loading_esata){
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
	//$('idPathEsata').innerHTML = "Loading...";
	is_loading_esata = true;
	var _path = $('idPathEsata').innerHTML;
	$('idPathEsata').innerHTML = "<?php echo lang_get('extraction_msg_25')?>";
	var strResponseURL = remote_php_path+'?action=delete_esata';
	if(usb){
		// For external device browser
		strResponseURL += '&device='+usb.selected_dev.device; // Attach device name
	}else{
		// ESATA
		strResponseURL += '&device=esata'; // Attach device name
	}
	
	
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
				
				
				//debug(responseHttpObj.responseText);
				if(usb){
					// For external device browser
					if(responseHttpObj.responseText=="Not connected"){
						usb.disconnect();
						is_loading_esata = false;
						is_refresh_esata = false;
						return;
					}
					
				}else{
					// ESATA device
					if(responseHttpObj.responseText=="Not connected"){
						if(usb){
						alert("<?php echo lang_get('usb_msg_4')?>");	
						}
						else{
						alert("<?php echo lang_get('esata_msg_5')?>");
						}
						esata.is_connected = false;
						esata.disconnect();
						return;
					}
				}
				
				
					var info = eval('(' + responseHttpObj.responseText + ')');
					
					if(parseInt(info.result) == 1){
						//debug("refresh browsing window");
						is_loading_esata = false;
						startLoad_esata();
					}
					else{
						if(info.error_msg.search("Problem in deleting. Some files") != -1){
							info.error_msg = "<?php echo lang_get('usb_msg_17')?>";
						}
						
						alert(info.error_msg);
						$('idPathEsata').innerHTML = _path;
						is_loading_esata = false;
					}
				},
			onFailure:function (){
				//hideLoadingImage();
				$('idPathEsata').innerHTML = _path;
				displayError();
				is_loading_esata = false;
				}
			}
		);
}

//개별 파일 또는 디렉토리를 삭제
function one_delete_esata(type,name){
	if( !esata.connected() ) return false;
	if(type == 'directory'){
		$('do_for_one').name = 'directory[]';
	}else if(type == 'file'){
		$('do_for_one').name = 'file[]';
	}
	$('do_for_one').value = name;
	delete_selected('do_for_one_fm');
}

//=======================================================//
// Create folder
//=======================================================//
function create_dir_nas(){
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		
		return false;
	}
	
	
	if($('new_directory_name_nas').value.length>0){
		if(check_dir_name($('new_directory_name_nas').value,'nas'))
		{
			is_loading_nas = true;
			
			var _fname = $('new_directory_name_nas').value;
			
			
			var strResponseURL = remote_php_path+'?action=create_dir_nas';
			var httpObj = new Ajax.Request   (
				    strResponseURL, {
					method:'post',
					parameters:Form.serialize('new_directory_fm_nas'),
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
							is_loading_nas = false;
							refresh_file_box();
						}else{
							alert(info.error_msg);
							//$('new_directory_name_nas').value = "Fail creation";
							is_loading_nas = false;
						}
						
					},
					onFailure:function (responseHttpObj){
						debug(responseHttpObj.responseText);
						displayError();
						//$('new_directory_name_nas').value = "Fail creation";
						}
					}
				);
			//$('new_directory_name_nas').value = '';
		}
		
	}else{
		alert("<?php echo lang_get('extraction_msg_13')?>");
		$('new_directory_name_nas').focus();
	}
}
function create_dir_esata(){
	if(is_loading_esata){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		
		return false;
	}
	if(usb){
		var _flag = false;
		for(var _i = 0 ; usb.connected_dev[_i] ; _i++){
			if(usb.selected_dev == usb.connected_dev[_i]){
				_flag = true;
				break;
			}
		}
		if(!_flag){
			alert("<?php echo lang_get('usb_msg_4')?>");
			return false;
		}
	}else if(!esata.is_connected){
		alert("<?php echo lang_get('esata_msg_5')?>");
		return false;
	}
	
	if($('new_directory_name_esata').value.length>0){
		if(check_dir_name($('new_directory_name_esata').value,'esata'))
		{
			is_loading_esata = true;
			
			var _fname = $('new_directory_name_esata').value;
			
			
			var strResponseURL = remote_php_path+'?action=create_dir_esata';
			var httpObj = new Ajax.Request   (
				    strResponseURL, {
					method:'post',
					parameters:Form.serialize('new_directory_fm_esata'),
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
							is_loading_esata = false;
							refresh_file_box_esata();
						}else{
							alert(info.error_msg);
							//$('new_directory_name_nas').value = "Fail creation";
							is_loading_esata = false;
						}
						
					},
					onFailure:function (responseHttpObj){
						debug(responseHttpObj.responseText);
						displayError();
						//$('new_directory_name_nas').value = "Fail creation";
						}
					}
				);
			//$('new_directory_name_nas').value = '';
		}
		
	}else{
		alert("<?php echo lang_get('extraction_msg_13')?>");
		$('new_directory_name_esata').focus();
	}
}
function check_dir_name(name,mode)
{
	var _limit = new Array('\\','/',':','*','?','"','<','>','|');
	for(var i=0; _limit[i]; i++)
	{
		if(name.indexOf(_limit[i])>-1)
		{
			_msg ="<?php echo lang_get('extraction_msg_23')?>"; 
			_msg = _msg.replace('&quot;','\"');
			alert(_msg);
			
			return false;
		}
	}
	if(mode=='nas'){
		var _list = folderNameListNas;
	}else{
		var _list = folderNameListEsata;
	}
	for(i=0;_list[i];i++){
		if(name.toLowerCase() == _list[i]){
			var _msg = "<?php echo lang_get('storing_msg_21')?>"
			alert(_msg);
			return false;
		}
	}
	return true;
}

// For upper case/lower case folder name
var folderNameListNas = [];
var folderNameListEsata = [];
//-->