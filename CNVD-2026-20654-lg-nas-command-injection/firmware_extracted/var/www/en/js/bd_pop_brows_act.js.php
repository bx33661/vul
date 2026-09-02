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
 * LJNSoft
 * Create, delete, rename, and move to other folder
 */


//=======================================================//
// Create folder
//=======================================================//
var old_cur_dir = "";
var new_dir_name = '';
function create_dir(){
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	if($('new_directory_name').value.length>0){
		if(check_dir_name($('new_directory_name').value))
		{

			//Check service folder
			var directory = $('idPath').innerHTML;
			var tmp = directory.split('/')

			
			if(((tmp[2] == '') && (tmp[1] == 'service')) || tmp[1]=='')
			{				
				alert("<?php echo lang_get('esata_msg_17')?>");
				return false;
			}
				
			is_loading = true;
			//showLoadingImage();
			$('file_box_loading').style.display = "block";
			$('file_box').style.display = "none";
			old_cur_dir = $('idPath').innerHTML;
			$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
			new_dir_name = $('new_directory_name').value;
			
			
			var strResponseURL = remote_php+'?action=create_dir';
			var httpObj = new Ajax.Request   (
				    strResponseURL, {
					method:'post',
					parameters:Form.serialize('new_directory_fm'),
					onSuccess:function (responseHttpObj) {
						//debug(responseHttpObj.responseText);
						var info = eval('(' + responseHttpObj.responseText + ')');
						
						if(parseInt(info.result) == 1){
							refresh_file_box();
						}else{
							if(parseInt(info.result) == 0){
								alert("<?php echo lang_get('esata_msg_17')?>");
							}
							else{
								alert(info.error_msg);
							}
							$('file_box_loading').style.display = "none";
							$('file_box').style.display = "block";
							//getCurrentDirectoryPath();
							$('idPath').innerHTML = old_cur_dir;
							is_loading = false;
						}
						//hideLoadingImage();
					},
					onFailure:function (){
						//hideLoadingImage();
						displayError();
						$('file_box_loading').style.display = "none";
						$('file_box').style.display = "block";
						//getCurrentDirectoryPath();
						$('idPath').innerHTML = old_cur_dir;
						}
					}
				);
			$('new_directory_name').value = '';
		}
		
	}else{
		alert("<?php echo lang_get('extraction_msg_13')?>");
		$('new_directory_name').focus();
	}
}
function check_dir_name(name)
{
	var _limit = new Array('\\','/',':','*','?','"','<','>','|');
	for(var i=0; _limit[i]; i++)
	{
		if(name.indexOf(_limit[i])>-1)
		{
			//alert('\"\\,/,:,*,?,",<,>,|\" cannot be used in a folder name.');
			_msg ="<?php echo lang_get('extraction_msg_23')?>"; 
			alert(_msg);
			$('file_box_loading').style.display = "none";
			$('file_box').style.display = "block";
			return false;
		}
	}
	for(i=0;folder_name_list[i];i++){
		if(name.toLowerCase() == folder_name_list[i]){
			var _msg = "<?php echo lang_get('storing_msg_21')?>";	// Multi-language
			alert(_msg);
			return false;
		}
	}
	return true;
}

//=======================================================//
// Move to other folder
//=======================================================//
function move_dir(dir_name){
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	is_loading = true;
	//showLoadingImage();
	$('file_box_loading').style.display = "block";
	$('file_box').style.display = "none";
	//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "hidden";
	old_cur_dir = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	
	//debug("move dir : "+dir_name);
	var strResponseURL = remote_php+'?action=move_dir&dir_name='+dir_name;
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
					$('file_box_loading').style.display = "none";
					$('file_box').style.display = "block";
					//getCurrentDirectoryPath();
					$('idPath').innerHTML = old_cur_dir;
					is_loading = false;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
				//getCurrentDirectoryPath();
				$('idPath').innerHTML = old_cur_dir;
				//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
				}
			}
		);
}

//=======================================================//
// Move up
//=======================================================//
function move_up(){
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	is_loading = true;
	$('file_box_loading').style.display = "block";
	$('file_box').style.display = "none";
	//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "hidden";
	old_cur_dir = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	
	//if($('current_path').value == '/'){
	if(current_path == '/'){
		alert("<?php echo lang_get('extraction_msg_19')?>");
		//$('idPath').innerHTML = '/';
		$('idPath').innerHTML = old_cur_dir;
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		is_loading = false;
		return false;
	}
	
	//showLoadingImage();
	is_loading = true;
	old_cur_dir = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	var strResponseURL = remote_php+'?action=move_up';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					refresh_file_box();
				}else{
					alert(info.error_msg);
					$('file_box_loading').style.display = "none";
					$('file_box').style.display = "block";
					//getCurrentDirectoryPath();
					$('idPath').innerHTML = old_cur_dir;
					is_loading = false;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
				//getCurrentDirectoryPath();
				$('idPath').innerHTML = old_cur_dir;
				//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
				}
			}
		);
}

//=======================================================//
// Delete a folder
//=======================================================//
function one_delete(name)
{

	//debug(name);
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	is_loading = true;
	$('file_box_loading').style.display = "block";
	$('file_box').style.display = "none";
	old_cur_dir = $('idPath').innerHTML;
	$('idPath').innerHTML = "Deleting...";
	var strResponseURL = remote_php+'?action=one_delete&file='+name;
	var httpObj = new Ajax.Request   (
	    strResponseURL, {
		method:'get',
		onSuccess:function (responseHttpObj) {
			
			//debug(responseHttpObj.responseText);
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					refresh_file_box();
				}else{
					if(parseInt(info.result) == 0){
						alert("<?php echo lang_get('burning_msg_48')?>");
					}
					else{
						alert(info.error_msg);
					}
					$('file_box_loading').style.display = "none";
					$('file_box').style.display = "block";
					//getCurrentDirectoryPath();
					$('idPath').innerHTML = old_cur_dir;
					is_loading = false;
				}
				//hideLoadingImage();
			},
		onFailure:function (){
			//hideLoadingImage();
			displayError();
			$('file_box_loading').style.display = "none";
			$('file_box').style.display = "block";
			//getCurrentDirectoryPath();
			$('idPath').innerHTML = old_cur_dir;
			is_loading = false;
			//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
			}
		}
	);
}
/*function one_delete(type,name){
	if(type == 'directory'){
		$('do_for_one').name = 'directory[]';
	}else if(type == 'file'){
		$('do_for_one').name = 'file[]';
	}
	$('do_for_one').value = name;
	delete_selected('do_for_one_fm');
}*/
function delete_selected(form_name){
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	
	if(Form.serialize(form_name).length < 1){
		alert('Select data to remove.');
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	if(!confirm('Remove the selected data?')){
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	
	//showLoadingImage();
	$('file_box_loading').style.display = "block";
	$('file_box').style.display = "none";
	//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "hidden";
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	
	var strResponseURL = remote_php+'?action=delete';
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
						$('file_box_loading').style.display = "none";
						$('file_box').style.display = "block";
						getCurrentDirectoryPath();
					}
					//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
				getCurrentDirectoryPath();
				//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
				}
			}
		);
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
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
		return false;
	}
	
	var old_name = $('rename_fm_old_name').value;
	var type = $('rename_fm_type').value;
	var new_name = $('rename_fm_new_name').value;
	
	if(old_name.length == 0
		||(type != 'directory' && type != 'file')){
		alert('No file was selected.');
		$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
		return false;
	}else if(new_name.length == 0){
		alert('Enter new name.');
		$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
		return false;
	}else if(new_name == old_name){
		alert('Enter different name from current name.');
		$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
		return false;
	}
	showLoadingImage();
	
	var strResponseURL = remote_php+'?action=rename_file';
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
						$('file_box_loading').style.display = "none";
						$('file_box').style.display = "block";
						getCurrentDirectoryPath();
					}
					hideLoadingImage();
				},
			onFailure:function (){
				hideLoadingImage();
				displayError();
				getCurrentDirectoryPath();
				}
			}
		);
	
}
// For upper case/lower case folder name
var folder_name_list = [];
//-->