<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>



<!--
//=======================================================//
// Init : page information
//=======================================================//
/*var timerA = '';
function read_prog(){
	sendRequest(on_read_prog,"","post","../php/esata_chk_job.php",true,true);
	
	function on_read_prog(oj){
		var res=decodeURIComponent(oj.responseText);
		debug('on_read_prog : '+res);
		if(res.match('ok')){
			clearInterval(timerA);
			page.accessing = false;
			startLoad("burn");
			copy_layer.show_msg('Complete');
			document.getElementById('idDisableBackground').style.display='none';
		}
	}
}*/
var page = {
	"name" : "e_sata",
	"init" : function(){
		this.chk_job();
		//startLoad("burn");
	},
	accessing : false,
	chk_job : function(){
		sendRequest(on_chk_job,"","post","../php/esata_chk_job.php",true,true);
		
		function on_chk_job(oj){
			var res=decodeURIComponent(oj.responseText);
			eval( 'var _res = '+res);
			/* Test return value
			if(_res.result == 1){
				alert(_res.result.substr(0));
			}
			*/
			
			switch(_res.result){
				case '0':
					alert("<?php echo lang_get('esata_msg_10')?>");
					copy_layer.open();
					page.accessing = true;
					return false;
					break;
				case '1':
					page.accessing = false;
					startLoad("burn");
					return true;
					break;
				case '-1':
					alert("<?php echo lang_get('esata_msg_10')?>");
					copy_layer.open_show();
					page.accessing = true;
					return false;
					break;
				default:
					return false;
					break;
			}
		}
	}
}
//=======================================================//
// For compatibility with USB browser menu
// Define usb object
//=======================================================//
var usb = false;
//=======================================================//
// ID list
//=======================================================//
var gIdBox = new Array("idBoxNas");
//=======================================================//
// PHP file list
//=======================================================//
var gPhp = new Array("../php/esata_get_info.php");
//=======================================================//
// Data list
//=======================================================//
var gDirList_esata=new Array();
var gFileList_esata=new Array();
var gAllFileList_esata=new Array();
//=======================================================//
// Path
//=======================================================//
var gRootPath_esata="";
var gCurrentPath_esata="";
var current_path_esata="";

//=======================================================//
// Status list
//=======================================================//
var gStat = new Array("init");
var fStat = gStat[0];
//=======================================================//
// Form list
//=======================================================//
var gFormName = new Array("files_slc_fm","files_esata_fm");
//=======================================================//
// Load e-SATA information
//=======================================================//
var esata = {
	connect_type : "",
	"is_connected" : false ,
	"stat_line" : ["<?php echo lang_get('esata_msg_4')?>","<?php echo lang_get('esata_msg_5')?>","<?php echo lang_get('esata_msg_6')?>"] ,
	"connect" : function(mode){
		if(mode=="conn" || !this.is_connected){
			this.connect_type = "e_sata";
			var _mode = "mount_point";
			var _msg = "<?php echo lang_get('esata_msg_4')?>";
		}else if(mode=="refr"){
			this.connect_type = "";
			var _mode = "mounted";
			var _msg = "<?php echo lang_get('esata_msg_4')?>";
		}
		var _cmd = "&mode="+_mode;
		var _php = "../php/esata_get_info.php";
		/*if( this["is_connected"] ){
			alert("e-SATA is connected already.");
			return false;
		}*/
		if(is_loading_esata){
			alert("<?php echo lang_get('extraction_msg_18')?>");
			return false;
		}
		is_loading_esata = true;
		document.getElementById("idPathEsata").innerHTML = _msg;
		sendRequest(on_connect,_cmd,"post",_php,true,true);
		
		function on_connect(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
			if( res == "No e-SATA" ){
				document.getElementById("idPathEsata").innerHTML = "<?php echo lang_get('esata_msg_5')?>";
				$('file_box_esata').innerHTML = "";
				$('directory_info_esata').innerHTML = "";
				is_loading_esata = false;
				esata.is_connected = false;
				return false;
			}else if(res == "Unknown format e-SATA"){
				// Need multi-language conversion
				document.getElementById("idPathEsata").innerHTML = "<?php echo lang_get('esata_msg_18')?>";
				$('file_box_esata').innerHTML = "";
				$('directory_info_esata').innerHTML = "";
				is_loading_esata = false;
				esata.is_connected = false;
				return false;
			}else if(res == "Not formatted e-SATA"){
				// Need multi-language conversion
				document.getElementById("idPathEsata").innerHTML = "<?php echo lang_get('esata_msg_19')?>";
				$('file_box_esata').innerHTML = "";
				$('directory_info_esata').innerHTML = "";
				is_loading_esata = false;
				esata.is_connected = false;
				return false;
			}else if(esata.connect_type == "e_sata"){
				document.getElementById("idPathEsata").innerHTML = "<?php echo lang_get('esata_msg_6')?>";
				esata["is_connected"] = true;
				is_loading_esata = false;
				//startLoad_esata();
				refresh_file_box_esata(esata.connect_type);
				return true;
			}else{
				debug("refresh:"+esata.connect_type);
				esata.is_connected = true;
				is_loading_esata = false;
				refresh_file_box_esata(esata.connect_type);
				return true;
			}
		}
	} ,
	"connected" : function(){
		return this.is_connected;
		
		
		var _val = document.getElementById("idPathEsata").innerHTML;
		//debug(_val);
		if( _val == "No e-SATA device is connected." || _val == "Searching e-SATA device connected..." || 
			_val == "Push Next button to load e-SATA device." ){
				alert("<?php echo lang_get('esata_msg_5')?>");
				return false;
			}
		return true;
	},
	cancel : function(){
		sendRequest(on_esata_cancel,"&mode=cancel","post","../php/esata_copy_cancel.php",true,true);
		
		function on_esata_cancel(oj){
			var res=decodeURIComponent(oj.responseText);
			eval('var _res = '+res);
			switch(_res.result){
				case '1':
					var _msg = "<?php echo lang_get('esata_msg_16')?>";
					alert(_msg);
					clearInterval(copy_layer.read_timer);
					copy_layer.show_msg(_msg);
					refresh_file_box();
					refresh_file_box_esata();
					return false;
				break;
				case '0':
					alert("<?php echo lang_get('usb_sync_msg_3')?>");
					return false;
				break;
				case '-1':
					/* Session-out */
					alert("<?php echo lang_get('login_msg_6')?>");
					return false;
				break;
				default:
				break;
			}
		}
	},
	disconnect : function(){
		$('idPathEsata').innerHTML = "<?php echo lang_get('esata_msg_5')?>";
		$('file_box_esata').innerHTML = '';
		$('directory_info_esata').innerHTML = '';
	}
}
function chk_loading_esata(){
	if( !esata.connected() )	return false;
	if( is_loading_esata ){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	return true;
}

//=======================================================//
// Copy function
//=======================================================//
function copy_nas_to_esata()
{
	//debug("copy nas to esata");
	if( !chk_loading_esata() ) return false;
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	// Capacity check
	if(chk_cap('nas','esata')){
		var _msg = "<?php echo lang_get('esata_msg_15')?>";
		alert(_msg);
		return;
	}
	debug("size is O.K.");
	
	chk_odd.is_busy("nas2esata");
	//copy_selected_esata("files_slc_fm","files_esata_fm");
}
function copy_esata_to_nas()
{
	//debug("copy esata to nas");
	if( !chk_loading_esata() ) return false;
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	// Capacity check
	if(chk_cap('esata','nas')){
		var _msg = "<?php echo lang_get('esata_msg_15')?>";
		alert(_msg);
		return;
	}
	debug();
	
	chk_odd.is_busy("esata2nas");
	//copy_selected_esata("files_esata_fm","files_slc_fm");
}
//=======================================================//
// Delete
//=======================================================//
function delete_selected_esata()
{
	//debug("deleted selected esata");
	if( !chk_loading_esata() ) return false;
	delete_selected_esata_esata("files_esata_fm");
}
function delete_selected_nas()
{
	if(is_loading_nas){
		alert("<?php echo lang_get('extraction_msg_18')?>");
		return false;
	}
	delete_selected("files_slc_fm");
}
//=======================================================//
// Check box control
//=======================================================//
var chk = {
	"all_list_nas" : function(){
		if(document.getElementById('id_chkbx_nas').checked){
			for( var i=0;document.getElementById('chk_'+i);i++ ){
				document.getElementById('chk_'+i).checked = true;
				gAllFileList[i].selected = 'yes';
			}
			folder_size.loading_all();
		}else{
			for( var i=0;document.getElementById('chk_'+i);i++ ){
				document.getElementById('chk_'+i).checked = false;
				gAllFileList[i].selected = 'no';
				folder_size.show_selected_total_size();
			}
		}
		
	},
	"all_list_esata" : function(){
		if(document.getElementById('id_chkbx_esata').checked){
			for( var i=0;document.getElementById('chk_esata_'+i);i++ ){
				document.getElementById('chk_esata_'+i).checked = true;
				gAllFileList_esata[i].selected = 'yes';
			}
			folder_size_ext.loading_all();
		}else{
			for( var i=0;document.getElementById('chk_esata_'+i);i++ ){
				document.getElementById('chk_esata_'+i).checked = false;
				gAllFileList_esata[i].selected = 'no';
				folder_size_ext.show_selected_total_size();
			}
		}
	}
}
//=======================================================//
// test
//=======================================================//
var copy_layer = {
	init : function(){
		
		$('l_copy_txt').innerHTML = "<?php echo lang_get('esata_msg_10')?>";
		$('id_inp_canc').style.display = "block";
		$('id_inp_clos').style.display = "none";
	},
	open : function(){
		$('l_copy').style.display="block";
		$('l_copy_img').src = "../images/Burn/ajax_loader_03.gif";		
		/*
		var _oj = document.getElementById('l_copy');
		var _oji = document.getElementById('l_copy_img');
		var _ojt = document.getElementById('l_copy_txt');
		if(!!(window.attachEvent && !window.opera)){
			// IE
			_oj.style.padding = "15px 16px 0 16px";
		}else{
			// Not IE
			_oj.style.padding = "0 0 0 0";
			_oji.style.padding = "15px 16px 0 16px";
			_ojt.style.left = "0";
			_ojt.style.top = "0";
			_ojt.style.padding = "5px 0 0 50px";
			//_oj.style.margin = "10px 0 0 0";
		}*/
		
		this.start_read_prog();
		
	},
	open_show : function(){
		$('id_inp_canc').style.display = "none";
		$('id_inp_clos').style.display = "none";
		$('l_copy').style.display="block";
		$('l_copy_img').src = "../images/Burn/ajax_loader_03.gif";	
		this.start_read_prog();
		
	},
	read_timer : '',
	start_read_prog : function(){
		this.read_timer = setInterval('copy_layer.read_prog()',1000);
	},
	//read_php : '../php/esata_get_info.php',
	read_php : '../php/esata_get_prog.php',
	read_prog : function(){
		//sendRequest(on_read_prog,'&mode=read_prog','post',this.read_php,true,true);
		sendRequest(on_read_prog,'','post',this.read_php,true,true);
		
		function on_read_prog(oj){
			/* * * New version * * */
			debug(oj.responseText);
			eval('var _ret = '+oj.responseText);
			switch(_ret.result){
				case '1':
					// complete
					var _msg = "<?php echo lang_get('esata_5')?>";
					break;
				case '0':
					// Ing...
					return;
				case '-1':
					// cancel
					var _msg = "<?php echo lang_get('esata_7')?>";
					break;
				case '-2':
					switch(_ret.message){
						case -90:
							var _msg = "<?php echo lang_get('esata_msg_23')?>";
							break;
						case -92:
							var _msg = "<?php echo lang_get('esata_msg_21')?>";
							break;
						case -94 :
							var _msg = "<?php echo lang_get('esata_msg_24')?>";
							break;
						case -96 :
							var _msg = "<?php echo lang_get('esata_msg_22')?>";
							break;
						case -98 :
							var _msg = "<?php echo lang_get('esata_msg_25')?>";
							break;
						case -153 :
							var _msg = "FAT32 <?php echo lang_get('common_error')?>";// 4G file to FAT32
							break;
						default:
							var _msg = "<?php echo lang_get('common_error')?>"; // original msg == 'unknown error'
							break;
					}
					break;
				default:
					// Unknown state
					return;
					break;
			}
			copy_layer.show_msg(_msg);
			clearInterval(copy_layer.read_timer);
			if(!is_loading_nas) refresh_file_box();
			if(!is_loading_esata) refresh_file_box_esata();
			return;
			
			/* * * Old version * * */
			
			
			//var _prog = parseInt(oj.responseText,10);
			var _prog = oj.responseText;
			
			if(_prog == 'complete'){
				copy_layer.show_msg("<?php echo lang_get('esata_5')?>");
				clearInterval(copy_layer.read_timer);
				refresh_file_box();
				refresh_file_box_esata();
				return;
			}else if(parseInt(_prog , 10) < 0){
				var _err_code = {
					'-90' : 'Source Not Exist or Cannot Read',
					'-92' : 'Cannot Create Directory',
					'-94' : 'Cannot Read Source',
					'-96' : 'Cannot Create File',
					'-98' : 'Memory Over'
				}
				if(_err_code[_prog.toString()]){
					var _msg = _err_code[_prog.toString()];
				}else{
					var _msg = 'Unknown error';
				}
				copy_layer.show_msg(_msg);
				clearInterval(copy_layer.read_timer);
			}
		}
	},
	close : function(){
		$('l_copy').style.display="none";
		this.init();
	},
	show_msg : function(msg){
		$('l_copy_img').style.display = 'none';
		$('l_copy_txt').innerHTML = msg;
		$('id_inp_canc').style.display = "none";
		$('id_inp_clos').style.display = "block";
		$('idBurnSize').innerHTML = msg;
	}
}
//========================================================//
// show_help
//========================================================//
function show_help()
{
  	var _win = window.open('../help/mobile/help_esata.html','Help_usb','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
}

//=======================================================//
// Confine the filename length within 32 or 64
//=======================================================//
var char_leng = {
	"length" : 32,
	img_filename_length : 64,
	get_filename : function(filename){
		var _ret = "<span title='"+filename+"' >"+filename.substr(0, this["length"])+"</span>";
		return _ret;
	},
	get_filename_img : function(filename){
		var _ret = "<span title='"+filename+"' >"+filename.substr(0, this.img_filename_length)+"</span>";
		return _ret;
	}
}

//=======================================================//
// Check if ODD is working
//=======================================================//
var chk_odd = {
	mode : "",
	msg_odd_working : "<?php echo lang_get('esata_msg_11')?>",
	msg_confirm : "<?php echo lang_get('esata_msg_12')?>",
	//msg_confirm : "<?php echo lang_get('esata_msg_20')?>\n"+"<?php echo lang_get('esata_msg_12')?>",
	// Warning message : overwrite same file
	is_busy : function(mode){
		if(!confirm(this.msg_confirm)){
			
			return false;
		}
		this.mode = mode;
		sendRequest(on_is_busy,"","post","../php/esata_chk_odd_busy.php",true,true);
		
		function on_is_busy(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
			var _tmp = res.split("\n");
			debug(_tmp[0]);
			var _op = _tmp[0].split(":");
			res = _op[0];
			
			switch(res){
				case "OK":
					debug("ok");
					break;
				default:
					if(!confirm(chk_odd.msg_odd_working+"\n"+chk_odd.msg_confirm)){
						return false;
					}
					break;
			}
			if(chk_odd.mode == "nas2esata"){
				copy_selected_esata("files_slc_fm","files_esata_fm");
			}else{
				copy_selected_esata("files_esata_fm","files_slc_fm");
			}
		}
	}
}

//=======================================================//
// Free capacity check
//=======================================================//
function chk_cap(src,dst){
	/*var _src_cap = get_selected_cap(src);
	var _dst_cap = get_free_cap(dst);
	if(_src_cap > _dst_cap) return true;*/
	return false;
}
//=======================================================//
// Get capacity
//=======================================================//
function get_selected_cap(dev){
	switch(dev){
		case 'nas':
			var _tCap = 0;
			for(var i=0;document.getElementById('chk_'+i);i++){
				var _oj = document.getElementById('chk_'+i);
				if(_oj.checked){
					_tCap += toByte(gAllFileList[i].size);
				}
				
			}
			return _tCap;
		break;
		case 'esata':
			var _tCap = 0;
			for(var i=0;document.getElementById('chk_esata_'+i);i++){
				var _oj = document.getElementById('chk_esata_'+i);
				if(_oj.checked){
					_tCap += toByte(gAllFileList_esata[i].size);
				}
				
			}
			debug("total selected file in esata : "+_tCap);
			return _tCap;
		break;
		default:
	}
	return -1;
}
var nasFreeCap = {
	capacity : 0,
	get : function(){
		var _cmd = "&mode=nas";
		var _php = "../php/esata_chk_free_cap.php";
		sendRequest(on_nasFreeCap_get,_cmd,"post",_php,true,true);
		
		function on_nasFreeCap_get(oj){
			var res=decodeURIComponent(oj.responseText);
			nasFreeCap.capacity = res;
			debug("nas free cap : "+res);
		}
	}
};
var esataFreeCap = {
	capacity : 0,
	get : function(){
		var _cmd = "&mode=esata";
		var _php = "../php/esata_chk_free_cap.php";
		sendRequest(on_esataFreeCap_get,_cmd,"post",_php,true,true);
		
		function on_esataFreeCap_get(oj){
			var res=decodeURIComponent(oj.responseText);
			esataFreeCap.capacity = res;
			debug("esata free cap : "+res);
		}
	}
};
function get_free_cap(dev){
	switch(dev){
		case 'nas':
			return nasFreeCap.capacity;
		break;
		case 'esata':
			return esataFreeCap.capacity;
		break;
		default:
	}
	return -1;
}
//=======================================================//
// Convert capacity
//=======================================================//

function toByte(cap){
	var _tmp = String(cap);
	var K = 1024;
	var M = 1024 * 1024;
	var G = 1024 * 1024 * 1024;
	var T = 1024 * 1024 * 1024 * 1024;
	var _byte = parseFloat(cap,10);
	//var _unit = cap.substr(cap.length-1);
	var _unit = _tmp.match(/[a-z]+/i);
	if(_unit){
		_unit = String(_unit).substr(0,1);
	}else{
		_unit = 'Byte';
	}
	if(_unit.search(/k/i)>-1){
		_byte *= K;
	}else if(_unit=="M"){
		_byte *= M;
	}else if(_unit=="G"){
		_byte *= G;
	}else if(_unit=="T"){
		_byte *= T;
	}
	return _byte;
}


// Loading selected folder size
var folder_size = {
	status : 'init',
	loading_php : '../php/burning_get_folder_size.php',
	loading : function(id){
		if(!document.getElementById(id)){
			return;
		}
		if(!document.getElementById(id).checked){
			gAllFileList[id.match(/\d+/)].selected = 'no';
			folder_size.show_selected_total_size();
			return;
		}
		this.status = 'loading';
		document.getElementById(id+'_size').innerHTML = "<?php echo lang_get('common_loading');?>";
		var _oj = gAllFileList[id.match(/\d+/)];
		sendRequest(on_loading, '&folders='+_oj.path+_oj.name, 'post', this.loading_php, true, true);
		
		_oj.selected = 'yes';
		document.getElementById('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_14')?>" + " : " + "<?php echo lang_get('common_loading')?>";
		
		function on_loading(oj){
			var res=decodeURIComponent(oj.responseText);
			eval('var _ret = '+res);
			
			switch(_ret.result){
				case -99:
					alert("<?php echo lang_get('login_msg_6');?>");
					return;
				break;
				default:
				break;
			}
			
			folder_size.update_size(_ret);
			folder_size.status = 'init';
			folder_size.show_selected_total_size();
			return;
		}
	},
	update_size : function(oj){
		var _oj = gAllFileList;
		for(i=0;_oj[i];i++){
			var _tmp_oj = oj[_oj[i].name];
			if(_tmp_oj){
				if(_tmp_oj.path == _oj[i].path){
					_oj[i].size = _tmp_oj.size;
					document.getElementById('chk_'+i+'_size').innerHTML = bytesHumanReadable(_tmp_oj.size);
					
				}
			}
		}
		
		
		//alert(Form.serialize('files_slc_fm'));
		
		
		//$('idBurnSize').innerHTML = bytesHumanReadable(_total_size);
		
	},
	loading_all : function(){
		this.status = 'loading';
		var _folders = this.make_folders_str();
		sendRequest(on_loading, '&folders='+_folders, 'post', this.loading_php, true, true);
		
		document.getElementById('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_14')?>" + " : " + "<?php echo lang_get('common_loading')?>";
		
		for(i=0;gAllFileList[i];i++){
			if(gAllFileList[i].type == 'directory'){
				document.getElementById('chk_'+i+'_size').innerHTML = "<?php echo lang_get('common_loading');?>";
			}
		}
		
		
		function on_loading(oj){
			var res=decodeURIComponent(oj.responseText);
			
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
			
			folder_size.status = 'init';
			
			folder_size.show_selected_total_size();
		}
	},
	make_folders_str : function(){
		var _ret = '';
		var _obj = gAllFileList;
		for(i=0;_obj[i];i++){
			if(_obj[i].type == 'directory'){
				_ret += _obj[i].path+_obj[i].name+':';
			}
		}
		return _ret;
	},
	update_list_size : function(oj){
		var _oj = gAllFileList;
		
		for(i=0;_oj[i];i++){
			
			var _tmp_oj = oj[_oj[i].name];
			
			if(_tmp_oj){
				
				if(_tmp_oj.path == _oj[i].path){
					_oj[i].size = _tmp_oj.size;
					document.getElementById('chk_'+i+'_size').innerHTML = bytesHumanReadable(_oj[i].size);
				}
			}
		}
	},
	show_selected_total_size : function(){
		var _total_size = 0;
		for(i=0;gAllFileList[i];i++){
			var _data = gAllFileList[i];
			if(_data.selected == 'yes'){
				_total_size += _data.size;
			}
		}
		document.getElementById('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_14')?>" + " : " + bytesHumanReadable(_total_size);
	},
	show : function(id){
		if(!document.getElementById(id).checked){
			gAllFileList[id.match(/\d+/)].selected = 'no';
			
		}else{
			gAllFileList[id.match(/\d+/)].selected = 'yes';
		}
		folder_size.show_selected_total_size();
		return;
	}
}
var folder_size_ext = {
	status : 'init',
	loading_php : '../php/esata_get_folder_size.php',
	loading : function(id){
		if(!document.getElementById(id)){
			return;
		}
		if(!document.getElementById(id).checked){
			gAllFileList_esata[id.match(/\d+/)].selected = 'no';
			folder_size_ext.show_selected_total_size();
			return;
		}
		this.status = 'loading';
		document.getElementById(id+'_size').innerHTML = "<?php echo lang_get('common_loading');?>";
		var _oj = gAllFileList_esata[id.match(/\d+/)];
		sendRequest(on_loading_ext, '&device=esata&folders='+_oj.path+_oj.name, 'post', this.loading_php, true, true);
		
		document.getElementById('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_15')?>" + " : " + "<?php echo lang_get('common_loading')?>";
		_oj.selected = 'yes';
		
		function on_loading_ext(oj){
			var res=decodeURIComponent(oj.responseText);
			eval('var _ret = '+res);
			
			switch(_ret.result){
				case -1:
					//alert(_ret.message);	// Device is disconnected
					alert("<?php echo lang_get('usb_msg_12');?>");
					return;
				break;
				case -99:
					alert("<?php echo lang_get('login_msg_6');?>");
					return;
				break;
				default:
				break;
			}
			
			folder_size_ext.update_size(_ret);
			folder_size_ext.status = 'init';
			folder_size_ext.show_selected_total_size();
			return;
		}
	},
	update_size : function(oj){
		var _oj = gAllFileList_esata;
		
		for(i=0;_oj[i];i++){
			var _tmp_oj = oj[_oj[i].name];
			
			if(_tmp_oj){
				
				if(_tmp_oj.path == _oj[i].path){
					
					_oj[i].size = _tmp_oj.size;
					document.getElementById('chk_esata_'+i+'_size').innerHTML = bytesHumanReadable(_tmp_oj.size);
					
				}
			}
		}		
	},
	loading_all : function(){
		this.status = 'Loading';
		var _folders = this.make_folders_str();
		sendRequest(on_loading, '&device=esata&folders='+_folders, 'post', this.loading_php, true, true);
		
		document.getElementById('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_15')?>" + " : " + "<?php echo lang_get('common_loading')?>";
		
		for(i=0;gAllFileList_esata[i];i++){
			if(gAllFileList_esata[i].type == 'directory'){
				document.getElementById('chk_esata_'+i+'_size').innerHTML = "<?php echo lang_get('common_loading');?>";
			}
		}
		
		
		function on_loading(oj){
			var res=decodeURIComponent(oj.responseText);
			
			eval('var _ret = '+res);

			switch(_ret.result){
				case -1:
					//alert(_ret.message);	// Device is disconnected
					alert("<?php echo lang_get('usb_msg_12');?>");
					return;
				break;
				case -99:
					alert("<?php echo lang_get('login_msg_6');?>");
					return;
				break;
				default:
				break;
			}
			
			folder_size_ext.update_list_size(_ret);			
			
			folder_size_ext.status = 'init';
			
			folder_size_ext.show_selected_total_size();
		}
	},
	make_folders_str : function(){
		var _ret = '';
		var _obj = gAllFileList_esata;
		for(i=0;_obj[i];i++){
			if(_obj[i].type == 'directory'){
				_ret += _obj[i].path+_obj[i].name+':';
			}
		}
		return _ret;
	},
	update_list_size : function(oj){
		var _oj = gAllFileList_esata;
		
		for(i=0;_oj[i];i++){
			
			var _tmp_oj = oj[_oj[i].name];
			
			if(_tmp_oj){
				
				if(_tmp_oj.path == _oj[i].path){
					_oj[i].size = _tmp_oj.size;
					document.getElementById('chk_esata_'+i+'_size').innerHTML = bytesHumanReadable(_oj[i].size);
				}
			}
		}
	},
	show_selected_total_size : function(){
		var _total_size = 0;
		for(i=0;gAllFileList_esata[i];i++){
			var _data = gAllFileList_esata[i];
			if(_data.selected == 'yes'){
				_total_size += _data.size;
			}
		}
		document.getElementById('idBurnSize').innerHTML = "<?php echo lang_get('usb_msg_15')?>" + " : " + bytesHumanReadable(_total_size);
	},
	show : function(id){
		if(!document.getElementById(id).checked){
			gAllFileList_esata[id.match(/\d+/)].selected = 'no';
			
		}else{
			gAllFileList_esata[id.match(/\d+/)].selected = 'yes';
		}
		folder_size_ext.show_selected_total_size();
		return;
	}
}
//-->