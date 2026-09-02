<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>





var esata = {
	txt_esata_copying : "Now copying from/to e-SATA\nBurning can take some time.",
	txt_esata_not_copying : "",
	mode : "",
	is_copying : function(mode){
		this.mode = mode;
		sendRequest(on_test,"&mode=check","post","../php/esata_copy_cancel.php",true,true);
		
		function on_test(oj){
			//debug("+"+oj.responseText+"+");
			var res=decodeURIComponent(oj.responseText);
			//debug("+"+res+"+");
			
			if(res.search("EsataIsCopying")>-1){
				alert(esata.txt_esata_copying);
				//return;
			}else{
				//debug("Esata is not copying");
				//return;
			}
			esata.mode = "";
			burning.burn();
		}
	}
}


//=======================================================//
// Page init
//=======================================================//
var page = {
	name : "burning",
	init : function(){
		startLoad("burn");
	}
}
//========================================================//
// System / Burning menu
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/burning_chk_blank.php",
"../php/burning_burn_disc.php",
"../php/burning_burn_image.php",
"../php/burning_get_dir.php","../php/burning_burn_image.php");
//========================================================//
// ID list
//========================================================//
var gIdTable=new Array('idTableBurn','idTableImage');
var gIdTab=new Array('idTabBurn','idTabImage');
var gIdInBurn=new Array("idBurnSize","idBurnVolume");
var gIdInImage=new Array("idBurnSizeImg","idBurnVolumeImg");
var gIdOutBurn=new Array("idBurnTitle");
var gIdOutImage=new Array("idBurnTitleImage");
var gIdBtn=new Array("idButtonBurnNext");
//=======================================================//
// Page status
//=======================================================//
var gStat=new Array("noDisc","burnReady","imageBurnReady","Disc Loading","Complete Disc Loading");
var fStat=gStat[0];
//=======================================================//
// File+folder List
//=======================================================//
var gAllFileList=new Array();
var gAllFileListImg=new Array();
var gSelectedFileList=new Array();
var gSelectedFileListImg=new Array();
var gSelectedList=new Array();
var gDirList=new Array();
var gFileList=new Array();
var gDirListImg=new Array();
var gFileListImg=new Array();
//=======================================================//
// Path
//=======================================================//
var gRootPath="/mnt/fs";
var gCurrentPath="";
//=======================================================//
// Message list
//=======================================================//
var gMsg=new Array("<?php echo lang_get('burning_2')?>",
"<?php echo lang_get('storing_msg_4')?>",
"<?php echo lang_get('storing_msg_5')?>",
"<?php echo lang_get('burning_3')?>",
"<?php echo lang_get('burning_msg_23')?>");
var gWarningMsg=new Array("<?php echo lang_get('burning_msg_27')?>");
//=======================================================//
// Binary Semaphore
//=======================================================//
var gSemaphore_refresh=false;
var gSemaphore_burn=false;
//=======================================================//
// Data type definition
//=======================================================//
function file_info(name,size,type,time,selected,path)
{
	this.name=name;
	this.size=size;
	this.type=type;
	this.time=time;
	this.selected=selected;
	this.path=path;
}
//=======================================================//
// Window handle
//=======================================================//
var hPopWin = "";


//========================================================//
// Open tab
//========================================================//
function open_tab_burn()
{
	setTimeout(showLoadingImage,500);
	
	close_tab_all();
	open_tab(gIdTable[0]);
	open_tab(gIdTab[0]);
	fStat=gStat[1];
	burning.set_mode(1);
	is_refresh = false;
	is_loading = false;
	document.getElementById(gIdOutBurn).innerHTML = "<?php echo lang_get('burning_msg_1')?>";
	startLoad("burn");
	
}
function open_tab_image()
{
	setTimeout(showLoadingImage,500);
	close_tab_all();
	open_tab(gIdTable[1]);
	open_tab(gIdTab[1]);
	fStat=gStat[2];
	burning.set_mode(2);
	is_refresh = false;
	is_loading = false;
	document.getElementById(gIdOutImage).innerHTML = "<?php echo lang_get('burning_msg_1')?>";
	startLoad("image burn");
}
function open_tab(id)
{
	document.getElementById(id).style.display='block';
}
function close_tab_all()
{
	document.getElementById(gIdTable[0]).style.display='none';
	document.getElementById(gIdTable[1]).style.display='none';
	document.getElementById(gIdTab[0]).style.display='none';
	document.getElementById(gIdTab[1]).style.display='none';
}
//=======================================================//
// ODD information
//=======================================================//
function load_disc(mode){
	//debug('load disc');
	show_text(gIdOutBurn[0],"<?php echo lang_get('extraction_cd_2')?>");
	show_text(gIdOutImage[0],"<?php echo lang_get('extraction_cd_2')?>");
	vis_ctl(gIdBtn[0],'hidden');
	fStat = "Disc Loading";
	var mode = 'burn';
	var cmd = '&mode='+mode;
	//var php = '../php/bd_odd_check.php';  NC1_KJS
	var php = '../php/capacity_check.php';
	sendRequest(on_load_disc,cmd,'post',php,true,true);
}

/**********************************************************/
/*function check_disc_info()
{
	sendRequest(on_1,"","post",gPhp[0],true,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	switch (res)
	{
	case "OK":
		show_text(gIdOutBurn[0],gMsg[0]);
		show_text(gIdOutImage[0],gMsg[0]);
		break;
	case "NG":
		show_text(gIdOutBurn[0],gMsg[1]);
		show_text(gIdOutImage[0],gMsg[1]);
		vis_ctl(gIdBtn[0],'visible');
		break;
	case "ODD BUSY":
		show_text(gIdOutBurn[0],gMsg[2]);
		show_text(gIdOutImage[0],gMsg[2]);
		vis_ctl(gIdBtn[0],'visible');
		break;
	default:
		alert(gMsg[3]);
		break;
	}
	vis_ctl(gIdBtn[0],'visible');
}*/
//=======================================================//
// Burning
//=======================================================//
function getSelectedEncodedFileList() 
{	
	var list=null;	
	for(var i=0;gSelectedFileList[i];i++)	
	{
		var _oj=gSelectedFileList[i];
		var encodedOj=gEncodedSelectedFilename[i];
		if(i==0) 
			var list=_oj.encodedPath+encodedOj;	
		if(i>0) 
			list+=(":"+_oj.encodedPath+encodedOj);	



	}	
	//alert(list);	
	return list;
}

function burn_data(){
	if(check_condition()){
		//var _list = get_selected_file_list();
		//var _cap = get_total_selected_file_size();
		//document.getElementById('file_list_to_pop').value = _list;

		var encodeFileList = getSelectedEncodedFileList();		
		var _cap = get_total_selected_file_size();	
		document.getElementById('file_list_to_pop').value = encodeFileList;
		
		document.getElementById('file_cap_to_pop').value = _cap;
		show_text(gIdOutBurn[0],"<?php echo lang_get('burning_msg_14')?>");
		show_text(gIdOutImage[0],"<?php echo lang_get('burning_msg_14')?>");
		document.getElementById('id_btn_refr_data').style.visibility= 'hidden';
		//document.getElementById('id_btn_refr_data').disabled = true;
		burning["status"]=="init";
		document.getElementById('id_btn_burn_data').disabled = true;
		//document.getElementById('idDisableBackground').style.display='block'; 
		var _win = window.open('burning_disc_progress_pop.php','_blank','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
		_win.focus();
		hPopWin = _win;
	}
	return false;
}
// ======================================================//
// Define Prototype
// ======================================================//
String.prototype.replaceAll = function( searchStr, replaceStr )
{
var temp = this;

while( temp.indexOf( searchStr ) != -1 )
{
temp = temp.replace( searchStr, replaceStr );
}

return temp;
}
//=======================================================//
// Check burning conditions
//=======================================================//
function check_condition(){
	//juny
	if(burning["status"]=="loading"){
		alert("<?php echo lang_get('burning_msg_2')?>");
		return false;
	}
	// (1) Check now burning or disc loading
	if(document.getElementById('id_btn_refr_data').style.visibility=='hidden'){
		if( fStat == "Disc Loading" ){
			alert("<?php echo lang_get('burning_msg_3')?>");
			return false;
		}
		var msg = "<?php echo lang_get('burning_msg_4')?>";
		alert(msg);
		return false;
	}
	
	// (2) Check volume name
	var tmpA = document.getElementById('idBurnTitle').innerHTML;
	var tmpB = document.getElementById(gIdInBurn[1]).value;
	if( tmpB == "" ){
		alert("<?php echo lang_get('burning_msg_5')?>");
		return false;
	}else if( !check_name(tmpB) ){
		alert("<?php echo lang_get('burning_msg_6')?>");
		return flase;
	}
		
	// (3) Juny : Show warning msg that previous data will be erased
	//if(tmpA.match('REWRITABLE DISC CONTAINING DATA'))
	if(tmpA.match("<?php echo lang_get('burning_msg_46')?>"))	
	{
		//alert("The recorded data will be erased\nWould you like to continue?");
		//document.write(confirm("The recorded data will be erased\nWould you like to continue?"));
		
		var result=confirm("<?php echo lang_get('erase_recorded_data')?>");		
		if (!result)
		{
			return false;
		}

	}
		
	
	// (4) Check disc type
	if( tmpA == "<?php echo lang_get('burning_msg_44')?>" || tmpA == "<?php echo lang_get('burning_msg_46')?>" ){
		if( chk_free_cap() ) return true;
	}else if(tmpA.search("<?php echo lang_get('burning_msg_1')?>")>-1){
		_msg = "<?php echo lang_get('burning_msg_8')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else if( tmpA.search("<?php echo lang_get('schedule_msg_17')?>") > -1 ){
		_msg = "<?php echo lang_get('schedule_msg_17')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else if(tmpA.search("<?php echo lang_get('storing_msg_2')?>") > -1){
		_msg = "<?php echo lang_get('storing_msg_5')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}
	/*else if(tmpA.search("<?php echo lang_get('burning_msg_52')?>") > -1){
		_msg = "<?php echo lang_get('burning_msg_52')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}*/	
	else{
		_msg = "<?php echo lang_get('burning_msg_9')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}	


/*	if( tmpA == "<?php echo lang_get('burning_msg_44')?>" || tmpA == "<?php echo lang_get('burning_msg_45')?>" ){
		if( chk_free_cap() ) return true;
	}else if( tmpA == "<?php echo lang_get('burning_msg_46')?>" ){
		if( chk_free_cap() ){
		  _msg = "<?php echo lang_get('burning_msg_7')?>";
			if( !confirm(_msg.replaceAll('<BR />','\n')) ){
				return false;
			}
			return true;
		}
	}else if(tmpA.search("<?php echo lang_get('burning_msg_1')?>")>-1){
		_msg = "<?php echo lang_get('burning_msg_8')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else if( tmpA.search("<?php echo lang_get('schedule_msg_17')?>") > -1 ){
		_msg = "<?php echo lang_get('schedule_msg_17')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else if(tmpA.search("<?php echo lang_get('storing_msg_2')?>") > -1){
		_msg = "<?php echo lang_get('storing_msg_5')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else{
		_msg = "<?php echo lang_get('burning_msg_9')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}
*/

	// (4) Check disc capacity & selected files's capacity
	function chk_free_cap(){
		if(folder_size.status == 'loading'){
			var _msg = "<?php echo lang_get('common_loading')?>";
			alert(_msg);
			return false;
		}
		var _tmp = document.getElementById('idBurnSize').value.split(" /");
		var sel_cap = get_size_info(_tmp[0]);
		var disc_cap = get_size_info(_tmp[1]);
		var _sel_cap = get_basic_size(sel_cap);
		var _disc_cap = get_basic_size(disc_cap);
		if( _disc_cap == 0 ){
			alert("<?php echo lang_get('burning_msg_22')?>");
			return false;
		}else if( _sel_cap == 0 ){
			alert("<?php echo lang_get('burning_msg_23')?>");
			return false;
		}else if( _sel_cap > _disc_cap ){
			alert("<?php echo lang_get('burning_msg_24')?>");
			return false;
		}
		return true;
	}
}
function check_name(name)
{
	return true;
}
function check_burn_image_condition(){
	// (1) Check now burning or disc loading
	if(document.getElementById('id_btn_refr_img').style.visibility=='hidden'){
		if( fStat == "Disc Loading" ){
			alert("<?php echo lang_get('burning_msg_3')?>");
			return false;
		}
		var msg = "<?php echo lang_get('burning_msg_4')?>";
		alert(msg);
		return false;
	}
	// (3) Check disc capacity & selected files's capacity
	var _tmp = document.getElementById('idBurnSizeImg').value.split(" /");
	var sel_cap = get_size_info(_tmp[0]);
	var disc_cap = get_size_info(_tmp[1]);
	var _sel_cap = get_basic_size(sel_cap);
	var _disc_cap = get_basic_size(disc_cap);
	if( _sel_cap == 0 ){
		alert("<?php echo lang_get('burning_msg_23')?>");
		return false;
	}
	if( _disc_cap != 0 && _sel_cap > _disc_cap ){
		alert("<?php echo lang_get('burning_msg_24')?>");
		return false;
	}
	// (2) Check disc type
	var tmp = document.getElementById('idBurnTitleImage').innerHTML;
	//debug(tmp);
	if( tmp == "<?php echo lang_get('burning_msg_44')?>" || tmp == "<?php echo lang_get('burning_msg_45')?>" ){
	}else if( tmp == "<?php echo lang_get('burning_msg_46')?>" ){
		_msg = "<?php echo lang_get('burning_msg_7')?>";
		if( !confirm(_msg.replaceAll('<BR />','\n')) ){
			return false;
		}
	}else if( tmp.search("TRAY OPENED") > -1 ){
		_msg = "<?php echo lang_get('schedule_msg_17')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else if(tmp.search("DRIVE IS BUSY") > -1){
		_msg = "<?php echo lang_get('storing_msg_5')?>";
		alert(_msg.replaceAll('<BR />','\n'));
		return false;
	}else{
		alert("<?php echo lang_get('burning_msg_25')?>");
		return false;
	}
	
	
	if( _disc_cap == 0 ){
		alert("<?php echo lang_get('burning_msg_22')?>");
		return false;
	}
	
	
	
	return true;
}
//========================================================//
// Image burn
//========================================================//
function image_burn(){
	
	if( !check_burn_image_condition() ) return false;
	debug(gSelectedFileListImg);
	//send_selected_dir_names_image();
	if(gSelectedFileListImg)
	{
		fBurning = true;
		
		document.getElementById('id_btn_refr_img').style.visibility='hidden';
		var _data=gSelectedFileListImg;
		document.getElementById('file_list_to_pop').value = _data.path+_data.name;
		var _cap = gSelectedFileListImg.size;
		document.getElementById('file_cap_to_pop').value = _cap;
		
		show_text(gIdOutBurn[0],"<?php echo lang_get('burning_msg_26')?>");
		show_text(gIdOutImage[0],"<?php echo lang_get('burning_msg_26')?>");
		//document.getElementById('idDisableBackground').style.display='block'; 
		var _win = window.open('burning_img_progress_pop.php','_blank','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
		_win.focus();
		hPopWin = _win;
		return true;
	}else
	{
		var msg = "No image file was selected!";
		alert(msg);
		return false;
	}
}



/***************************************************/
function get_all_list()
{
	var _tmp=new Array();
	var _oj=null;
	var _time=null;
	for(var i=0;gDirList[i];i++)
	{
		 _oj=gDirList[i];
		 _time=_oj.date+" "+_oj.time;
		 
		_tmp[i]=new file_info(_oj.file_name,_oj.size,"directory",_time,"no",gCurrentPath);
	}
	for(var j=0;gFileList[j];j++)
	{	
		var _oj=gFileList[j];
		_time=_oj.date+" "+_oj.time;
		_tmp[i+j]=new file_info(_oj.file_name,_oj.size,_oj.subtype,_time,"no",gCurrentPath);
	}
	return _tmp;
}
function get_all_list_img()
{
	var _tmp=new Array();
	var _oj=null;
	var _time=null;
	/*for(var i=0;gDirListImg[i];i++)
	{
		 _oj=gDirListImg[i];
		 _time=_oj.date+" "+_oj.time;
		 
		_tmp[i]=new file_info(_oj.file_name,_oj.size,"directory",_time,"no",gCurrentPath);
	}*/
	var i = 0;
	for(var j=0;gFileListImg[j];j++)
	{	
		var _oj=gFileListImg[j];
		_time=_oj.date+" "+_oj.time;
		_tmp[i+j]=new file_info(_oj.file_name,_oj.size,_oj.subtype,_time,"no",gCurrentPath);
	}
	return _tmp;
}
//=======================================================//
// Selected file manipulation
//=======================================================//
var gEncodedSelectedFilename = new Array();
function send_selected_dir_names(){
		//juny
		//alert("send_selected_dir_names()");
		var fObj = $('files_slc_fm');
		var dir_obj = null;
		var checked_cnt = 0;
		var checked_dir_name = new Array();
		var _checked_list = new Array();
		
		// Check same full path & same folder name
		for(var i=1;fObj.elements[i];i++){
			dir_obj = fObj.elements[i];
			if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
				var _fSame="no";
				var k = i - 1;
				if(gAllFileList[k].selected=="no")
				{
					gAllFileList[k].selected="yes";
					
					//compare to the previous selected list
					var _selected_path=gAllFileList[k].path+gAllFileList[k].name;
					for(var j=0;gSelectedFileList[j];j++)
					{
						var _path=gSelectedFileList[j].path+gSelectedFileList[j].name;
						if(_selected_path==_path)
						{
							_fSame="yes";
							break;
						}else if(gAllFileList[k].name == gSelectedFileList[j].name){
							alert("<?php echo lang_get('burning_msg_50')?>"+'\n'+"<?php echo lang_get('burning_msg_51')?>"+' : '+gAllFileList[k].name);
							//alert('Same name folder was already selected.\nFolder name : '+gAllFileList[k].name);
							gAllFileList[k].selected = 'no';
							_fSame="yes";
							break;
						}
					}
					if(_fSame=="no")
					{
						gAllFileList[k].encodedPath=gEncodedCurrentPath;
						gSelectedFileList=gSelectedFileList.concat(gAllFileList[k]);
						gEncodedSelectedFilename = gEncodedSelectedFilename.concat(document.getElementById('chk_'+k).value);
					}
					if(gSelectedFileList[j]==null)
					{
						gAllFileList[k].encodedPath=gEncodedCurrentPath;
						gSelectedFileList=gSelectedFileList.concat(gAllFileList[k]);
						gEncodedSelectedFilename = gEncodedSelectedFilename.concat(document.getElementById('chk_'+k).value);
					}
				}
			}
		} 
		display_selected_files_list_mode();
		//display_selected_files_size(gIdInBurn[0]);
		
		// Test
		// Loading selected folder size
		folder_size.loading();
		
	}
function delete_selected_dir_names()
{
	var _list=gSelectedFileList;
	var _listEn = gEncodedSelectedFilename;
	if(_list.length==0) return false;
	
	var fObj = $('files_aaa_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = new Array();
	var _checked_list=new Array();
	
	for(var j=1;fObj.elements[j];j++){
		var i = j - 1;
		dir_obj = fObj.elements[j];
		if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
			_list[i].selected="no";
			_checked_list[checked_cnt]=i;
			checked_cnt++;
		}else
		{
			_list[i].selected="yes";
		}
	} 
	for(var i=(_list.length-1);i>=0;i--)
	{
		if(_list[i].selected=="no")
		{
			_list.splice(i,1);
			_listEn.splice(i,1);
		}
	}
	gSelectedFileList=_list;
	gEncodedSelectedFilename=_listEn;
	display_selected_files_list_mode();
	display_selected_files_size(gIdInBurn[0]);
}	
function send_selected_dir_names_image(id){
	
	var _num = parseInt(id.substr(id.search(/\d+/)));
	
	
	gSelectedFileListImg = gAllFileListImg[_num];
	display_selected_files_size_img();	
}
function delete_selected_dir_names_img()
{
	var _list=gSelectedFileListImg;
	if(_list.length==0) return false;
	//debug("a");
	var fObj = $('files_simg_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = new Array();
	var _checked_list=new Array();
	
	for(var i=0;fObj.elements[i];i++){
		dir_obj = fObj.elements[i];
		if(dir_obj.type == 'checkbox' && dir_obj.checked == true){
			//debug(i);
			_list[i].selected="no";
			_checked_list[checked_cnt]=i;
			checked_cnt++;
		}else
		{
			_list[i].selected="yes";
		}
	} 
	for(var i=(_list.length-1);i>=0;i--)
	{
		if(_list[i].selected=="no")
		{
			_list.splice(i,1);
		}
	}
	gSelectedFileListImg=_list;
	display_selected_files_list_mode_img();
	display_selected_files_size_img(gIdInImage[0]);
	//debug("b");
}

function display_selected_files_list_mode(){
	
	var tmp = document.getElementById('idBurnSize').value.split("/");
	document.getElementById('idBurnSize').value = "<?php echo lang_get('common_loading');?>"+" /"+tmp[1];
	
	
	var info = gSelectedFileList;
	var i=0;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var total_size = 0;
	var table_frame_html = '<form name="files_slc_fm" id="files_aaa_fm" method="POST" onsubmit="return false;">'
							+"<table width='300' style='word-break:break-all;white-space:normal;table:fixed;'>"
							+'<thead><tr style="background:#DDDDDD;">'
							+"<td width='20' align='center'><input type='checkbox' id='id_chkbx_sel_burn' onclick='check_all_burn_list();'/></td>"
							+"<td style='max-width:200;' ><?php echo lang_get('common_name')?></td>"
							+"<td width='40' align='center'><?php echo lang_get('schedule_restore_1')?></td>"
							+"<td width='40' align='center'><?php echo lang_get('common_time')?></td>"
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
						+'</tr>';
	var _dirs_length = 0;
	var _files_length = 0;
	
	for(var i=0;info[i];i++){
		body_row = body_row_html;
		var data=info[i];
		if(data.type=="directory"){		
		//if(data.type == 'd'){
			//debug("directory");
			//body_row = body_row.replace('#size#','');
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_burn'+obj_cnt+'" value="'+data.file_name+'">'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
			
			if(data.size == ''){
				var _size = "<?php echo lang_get('common_loading');?>";
			}else if(data.size < 0){
				var _size = 'removed';	// Multi-language
				
			}else{
				var _size = bytesHumanReadable(data.size);
			}
			_dirs_length++;
		}else{
		//}else if(data.type == '-'){
			//debug("file");
			//body_row = body_row.replace('#size#',data.size);
			checkbox_html = '<input type="checkbox" name="directory[]" id="chk_burn'+obj_cnt+'" value="'+data.file_name+'">';
			var _size = bytesHumanReadable(data.size)
			
			_files_length++;
		}
		var _total_size = parseFloat(data.size);
		if(!isNaN(_total_size)){
			total_size += parseFloat(data.size);
		}
		
		
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',char_leng.get_filename(data.name));
		body_row = body_row.replace('#size#',_size);
		body_row = body_row.replace('#time#',data.time);
		body_row_total += body_row;
		obj_cnt++;
	}
	
	if( i==0 ){
		$('selected_file_box').innerHTML = "&nbsp;";
		$('directory_info_selected').innerHTML = "&nbsp;";
	}else{
		$('selected_file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
		$('directory_info_selected').innerHTML = _dirs_length+" <?php echo lang_get('burning_4')?>&nbsp;&nbsp;&nbsp;&nbsp;"+_files_length+" <?php echo lang_get('burning_5')?>&nbsp;&nbsp;&nbsp;&nbsp;"+bytesHumanReadable(total_size);

		//JUNY
		//var tmp = document.getElementById('idBurnSize').value.split("/");
		//document.getElementById('idBurnSize').value = bytesHumanReadable(total_size) +" /"+tmp[1];
	
	}
	
	
	//display_selected_files_size(gIdInBurn[0]);
}
function get_selected_file_list()
{
	var _list=null;
	for(var i=0;gSelectedFileList[i];i++)
	{
		var _oj=gSelectedFileList[i];
		if(i==0) var _list=_oj.path+_oj.name;
		if(i>0) _list+=(":"+_oj.path+_oj.name);
		//debug(_list);
	}
	return _list;
}
/*function display_selected_files_list_mode_img(){
	var info = gSelectedFileListImg;
	var i=0;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	var table_frame_html = '<form name="files_simg_fm" id="files_simg_fm" method="POST" onsubmit="return false;">'
							+'<table>'
							+'<thead><tr style="background:#DDDDDD;">'
							+'<td>select</td>'
							+'<td>name</td>'
							+'<td>size</td>'
							+'<td>time</td>'
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
						+'</tr>';
	
	for(var i=0;info[i];i++){
		body_row = body_row_html;
		var data=info[i];
		if(data.type=="directory")
		{
			//debug("directory");
			body_row = body_row.replace('#size#','');
		}else
		{
			//debug("file");
			body_row = body_row.replace('#size#',data.size);
		}
		checkbox_html = '<input type="checkbox" name="directory[]" id="chk_img_'+obj_cnt+'" value="'+data.file_name+'">';
		body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',data.name);
		body_row = body_row.replace('#size#',data.size);
		body_row = body_row.replace('#time#',data.time);
		body_row_total += body_row;
		obj_cnt++;
	}
	$('selected_file_box_img').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
}*/

//=======================================================//
// Burning information display
//=======================================================//
function display_selected_files_size(id){
	var _size=get_total_selected_file_size();
	var tmp = document.getElementById(id).value.split("/");
	document.getElementById(id).value=_size+" /"+tmp[1];
}
function display_selected_files_size_img(){
	var id = "idBurnSizeImg";
	var _size = gSelectedFileListImg.size;
	if(isNaN(_size)){
		_size = parseFloat(_size);
	}
	if(gSelectedFileListImg.name.substr(gSelectedFileListImg.name.length-3) == 'cue'){
		//var _tmp = toByte(_size);
		//_tmp = _tmp * 2048 / 2352;
		//_tmp = bytesHumanReadable(_tmp);
		var _tmp = bytesHumanReadable(_size * 2048 /2352);
		//_size = _tmp;
		// Please Don't Remove Below Message
		//alert("A image file of audio CD has larger capacity than a data CD.\nThe real capacity will be burned into the disc is smaller than the image file capacity.");
	}else{
		var _tmp = bytesHumanReadable(_size);
	}
	var tmp = document.getElementById(id).value.split("/");
	document.getElementById(id).value = _tmp+" /"+tmp[1];
	
}
//=======================================================//
// Size information calculation
//=======================================================//
function get_total_selected_file_size()
{
	var __total_size = 0;
	for(var i=0;gSelectedFileList[i];i++)
	{
		__total_size += gSelectedFileList[i].size;
		if(i==0) var _total_size=new size_info(0,"B");		
		var _size=get_size_info(gSelectedFileList[i].size);	
		//debug//
		//	
		debug('total size info : current size info => '+_total_size.size+' '+_size.size);
		_total_size=sum_size_info(_total_size,_size);		
	}
	return bytesHumanReadable(__total_size);
	
	if(_total_size)
	{
		var _ret=_total_size.size+" "+_total_size.unit;
		debug('total size info: size unit => '+_total_size.size+' '+_total_size.unit);
	}else
	{
		var _ret="0 B";
	}
	return _ret;
}
function get_total_selected_file_size_img(){
	
	for(var i=0;gSelectedFileListImg[i];i++)
	{
		if(i==0) var _total_size=new size_info(0,"B");
		var _size=get_size_info(gSelectedFileListImg[i].size);
		_total_size=sum_size_info(_total_size,_size);
	}
	if(_total_size)
	{
		var _ret=_total_size.size+" "+_total_size.unit;
	}else
	{
		var _ret="0 B";
	}
	return _ret;
}
function get_size_info(size)
{
	//debug(size)
	if(size==null || size == '')
	{
		debug("get_size_info : "+size);
		size="0K";
	}
	// Unit detect
	var _tmp = String(size);
	var _unit = _tmp.substr(size.length-1);
	if(!_unit.match(/[BKkMGT]/)){
		var _unit = 'B';
	}
	
	var _size=new size_info(parseFloat(size),_unit);
	//debug("+"+_size.size+"+"+_size.unit+"+");
	return _size;
}
function sum_size_info(sInfo1,sInfo2)
{
	var _size1=get_basic_size(sInfo1);
	var _size2=get_basic_size(sInfo2);
	var _size3=parseFloat(_size1)+parseFloat(_size2);
	var _size4=convert_size_to_info(_size3);
	debug("size1 : size2 : sum size : converted sum size : unit\n"+_size1+" : "+_size2+" : "+_size3+" : "+_size4.size+" : "+_size4.unit);
	return _size4;
}
function get_basic_size(sInfo)
{
	switch (sInfo.unit)
	{
	case "K":
		sInfo.size*=1024;
		break;
	case "k":
		sInfo.size*=1024;
		break;
	case "M":
		sInfo.size*=1048576;
		break;
	case "G":
		sInfo.size*=1073741824;
		break;
	case "T":
		sInfo.size*=133143986176;
		break;
	default:
		break;
	}
	return sInfo.size;
}
function convert_size_to_info(size)
{
	var _size=parseFloat(size);
	if(isNaN(_size))
	{
		debug("convert_size_to_info : "+size+"==>"+_size);
		_size=0;
	}
	var _K=1024;
	var _M=1048576;
	var _G=1073741824;
	var _T=133143986176;
	if(_size<_K)
	{
		var _info=new size_info(_size,"B");
	}else if(_size<_M)
	{
		var _info=new size_info(_size/_K,"K");
	}else if(_size<_G)
	{
		var _info=new size_info(_size/_M,"M");
	}else if(_size<_T)
	{
		var _info=new size_info(_size/_G,"G");
	}else
	{
		var _info=new size_info(_size/_T,"T");
	}
	/*if(!_info.size.toFixed(3))
	{
		debug("convert_size_to_info : toFixed ==>"+_info.size.toFixed(3));
	}*/
	if(_info.size==0){
		_info.size = 0;
	}else{
		var _tmp = Math.round(_info.size * 100)/100;
		
		//_info.size=_info.size.toFixed(2);
		_info.size=_tmp.toFixed(2);
	}
	return _info;
}
function size_info(num,unit)
{
	this.size=num;
	this.unit=unit;
}
//=======================================================//
// Check box control
//=======================================================//
function check_all_nas_list(){
	
	//debug("nas list");
	var _oj = document.getElementById('id_chkbx_sel_nas');
	var cnt = 0;
	if( _oj.checked ){		
		for( var i=0;document.getElementById('chk_'+i);i++ ){
			if(checkPath(i)) {				
				document.getElementById('chk_'+i).checked = true;				
				cnt++;			
			}
			else break;		
		}		
		if(cnt==0) _oj.checked=false;
	}else{
		for( var i=0;document.getElementById('chk_'+i);i++ ){
			//debug(document.getElementById('chk_'+i).value);
			document.getElementById('chk_'+i).checked = false;
		}
	}
}
function check_all_burn_list(){
	//debug("burn list");
	var _oj = document.getElementById('id_chkbx_sel_burn');
	if( _oj.checked ){
		
		for( var i=0;document.getElementById('chk_burn'+i);i++ ){
			document.getElementById('chk_burn'+i).checked = true;
		}
	}else{
		for( var i=0;document.getElementById('chk_burn'+i);i++ ){
			document.getElementById('chk_burn'+i).checked = false;
		}
	}
}
//=======================================================//
// Burning
//=======================================================//
var burning = {
	"mode_list" : ["init","data burn","image burn"],
	"mode" : "data burn",
	"set_mode" : function(index){
		this["mode"] = this["mode_list"][index];
	},
	"burn" : function(){
		//debug(this.mode);
		if(this.mode=="data burn"){
			burn_data();
		}else if(this.mode=="image burn"){
			image_burn();
		}
	},
	odd_busy_chk : function(){

		//check whether previous command was done
		if(gSemaphore_burn == true)
			return false;

				
		var _mode = 'burn';
		var _cmd = '&mode='+_mode;
		var _php = '../php/capacity_check.php';

		gSemaphore_burn = true;
		sendRequest(on_odd_busy_chk,_cmd,'post',_php,true,true);

		function on_odd_busy_chk(oj){
			var res=decodeURIComponent(oj.responseText);
			gSemaphore_burn = false;

			burning["status"] = "burning";
			var obj = {
				"field" : new Array() ,
				"show_field" : function(i){
					return this["field"][i];
				} ,
				"show_field_value" : function(i){
					return this[this["field"][i]];
				} ,
				"show_value" : function(field_name){
					return this[field_name];
				}
			}
				
			var tmp = res.split("\n");
			var ret = new Array();
			/*for(var i=0; tmp[i];i++)
			{
				ret[i] = tmp[i].split(":");
				//obj[ret[i][0]] = ret[i][1];
				
				obj["field"].push(ret[i][0]); 
			}*/
			for(var i=0; tmp[i];i++)
			{
				ret[i] = tmp[i].split(":");
				
				// Message MultiLanguage Support
				if(ret[i][1].match('DISC BURNING')){
					alert("<?php echo lang_get('burning_msg_52')?>");
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_52')?>";	
					
				}				
				else if(ret[i][1].match('TRAY OPENED')){
					obj[ret[i][0]] = "<?php echo lang_get('schedule_msg_17')?>";	
				}
				else if(ret[i][1].match('NO DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('schedule_msg_18')?>";	
			       }
				else if(ret[i][1].match('DRIVE IS BUSY')){
					obj[ret[i][0]] = "<?php echo lang_get('storing_msg_2')?>";	
				}
				else if(ret[i][1].match('BLANK DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_44')?>";	
				}else if(ret[i][1].match('REWRITABLE DISC CONTAINING DATA')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_46')?>";	
				}else if(ret[i][1].match('REWRITABLE DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_45')?>";	
				}else if(ret[i][1].match('NOT A WRITABLE DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_47')?>";	
				}else{
					obj[ret[i][0]] = ret[i][1];
				}

				obj["field"].push(ret[i][0]); 
			}
		
			//debug(obj["field"][0]);
			switch(obj["field"][0]){
				case 'NG':
					document.getElementById('idBurnTitle').innerHTML = obj.show_field_value(0);
					//alert(_msg);
					break;
				case 'OK':
					//esata.is_copying();
					document.getElementById('idBurnTitle').innerHTML = obj.show_field_value(0);
					document.getElementById('id_btn_refr_data').visibility="visible";
					if( obj["FREE SPACE"] ) show_disc_free_space("idBurnSize");
					burning.burn();
					
					return true;
					break;
				case 'WARNING':
					//debug(obj.show_value("USER ID"));
					//obj.show_value("USER ID")
					//obj.show_value("APPLICATION ID")
					var _msg = {
						usr_id_list : ["myself", "root", "cms", "web"],
						app_id_list : ["internal", "mosilt", "mopilt", "daemon", "daemonp", "rip","store","burn"],
						task_name_list : ["data_copy","image_backup","burn_data","burn_image"],
						usr_id : "",
						app_id : "",
						task : {
							mosilt : "button burning",
							mopilt : "button backuping",
							daemon : "BD schedule backuping",
							daemonp : "BD schedule backuping",
							rip : "ripping",
							store : "storing",
							burn : "burning"
						},
						show_msg : function(user_id,app_id){
							this.usr_id = user_id;
							this.app_id = app_id;
							var _msg = (this.task[this.app_id])? this.task[this.app_id] : null;
							if(_msg) alert( "Drive is "+_msg );
						}						
					}
					
					_msg.show_msg(obj.show_value("USER ID"),obj.show_value("APPLICATION ID"));
					burning["status"] = "init";
					return false;
					break;
				case 'ERROR':
					document.getElementById('idBurnTitle').innerHTML = obj.show_field_value(0);
					break;
				default:
					break;
			}

			debug(res);
			burning["status"] = "init";

			function show_disc_free_space(id){
				var tmp = document.getElementById(id).value.split("/");
				var _tmp = get_size_info(obj["FREE SPACE"]);
				_tmp = get_basic_size(_tmp);
				_tmp = convert_size_to_info(_tmp);
				document.getElementById(id).value = tmp[0]+"/ "+_tmp.size+" "+_tmp.unit;
			}
		
			return false;
		}
	},
	
	"id_div" : ["id_btn_refr_data","id_btn_refr_img","idBurnTitle","idBurnTitleImage"],
	"status" : "init",
	"status_list" : ["init","loading","burning"],
	"load_disc" : function(mode){

		//check whether previous command was done
		if(gSemaphore_refresh == true)
			return false;
	
		if(mode=="data burn"){
			document.getElementById('idBurnTitle').innerHTML = "<?php echo lang_get('extraction_cd_2')?>";
			document.getElementById('id_btn_refr_data').visibility="hidden";
			this.mode = mode;
		}else if(mode=="image burn"){
			document.getElementById('idBurnTitleImage').innerHTML = "<?php echo lang_get('extraction_cd_2')?>";
			document.getElementById('id_btn_refr_img').visibility="hidden";
			this.mode = mode;
		}else{
			alert("err");
		}
		this["status"] = "loading";
		
		var _mode = 'burn';
		var _cmd = '&mode='+_mode;
		//NC1_KJS var _php = '../php/bd_odd_check.php';
		var _php = '../php/capacity_check.php';		//NC1_KJS
		//document.getElementById('idBurnSize').value = "0 Byte/ 0 Byte"; //NC1_KJS
		gSemaphore_refresh = true;
		sendRequest(on_load_disc,_cmd,'post',_php,true,true);
		
		function on_load_disc(oj){
			var res=decodeURIComponent(oj.responseText);
			gSemaphore_refresh = false;

			burning["status"] = "init";
			var obj = {
				"field" : new Array() ,
				"show_field" : function(i){
					return this["field"][i];
				} ,
				"show_field_value" : function(i){
					return this[this["field"][i]];
				} ,
				"show_value" : function(field_name){
					return this[field_name];
				}
			};
				
			var tmp = res.split("\n");
			var ret = new Array();
			for(var i=0; tmp[i];i++)
			{
				ret[i] = tmp[i].split(":");
				
				// Message MultiLanguage Support
				if(ret[i][1].match('DISC BURNING')){
					alert("<?php echo lang_get('burning_msg_52')?>");
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_52')?>";					
				}				
				else if(ret[i][1].match('TRAY OPENED')){
					obj[ret[i][0]] = "<?php echo lang_get('schedule_msg_17')?>";	
				}
				else if(ret[i][1].match('NO DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('schedule_msg_18')?>";	
			       }
				else if(ret[i][1].match('DRIVE IS BUSY')){
					obj[ret[i][0]] = "<?php echo lang_get('storing_msg_2')?>";	
				}
				else if(ret[i][1].match('BLANK DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_44')?>";	
				}else if(ret[i][1].match('REWRITABLE DISC CONTAINING DATA')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_46')?>";	
				}else if(ret[i][1].match('REWRITABLE DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_45')?>";	
				}else if(ret[i][1].match('NOT A WRITABLE DISC')){
					obj[ret[i][0]] = "<?php echo lang_get('burning_msg_47')?>";	
				}else{
					obj[ret[i][0]] = ret[i][1];
				}

				obj["field"].push(ret[i][0]); 
			}
			//debug(obj["field"][0]);
			switch(obj["field"][0]){
				case 'NG':
					break;
				case 'OK':
					break;
				case 'WARNING':
					//debug(obj.show_value("USER ID"));
					//obj.show_value("USER ID")
					//obj.show_value("APPLICATION ID")
					var _msg = {
						usr_id_list : ["myself", "root", "cms", "web"],
						app_id_list : ["internal", "mosilt", "mopilt", "daemon", "daemonp", "rip","store","burn"],
						task_name_list : ["data_copy","image_backup","burn_data","burn_image"],
						usr_id : "",
						app_id : "",
						task : {
							mosilt : "button burning",
							mopilt : "button backuping",
							daemon : "BD schedule backuping",
							daemonp : "BD schedule backuping",
							rip : "ripping",
							store : "storing",
							burn : "burning"
						},
						show_msg : function(user_id,app_id){
							this.usr_id = user_id;
							this.app_id = app_id;
							var _msg = (this.task[this.app_id])? this.task[this.app_id] : null;
							if(_msg) alert( "Drive is "+_msg );
						}						
					}
					
					_msg.show_msg(obj.show_value("USER ID"),obj.show_value("APPLICATION ID"));
					break;
				case 'ERROR':
					break;
				default:
					break;
			}
			if(mode=="data burn"){
				document.getElementById('idBurnTitle').innerHTML = obj.show_field_value(0);
				document.getElementById('id_btn_refr_data').visibility="visible";
				if( obj["FREE SPACE"] ) show_disc_free_space("idBurnSize");
			}else if(mode=="image burn"){
				document.getElementById('idBurnTitleImage').innerHTML = obj.show_field_value(0);
				document.getElementById('id_btn_refr_img').visibility="visible";
				if( obj["FREE SPACE"] ) show_disc_free_space("idBurnSizeImg");
			}else{
				alert("err");
			}
			this.status = "init";
			fStat = "Complete Disc Loading";
			
			function show_disc_free_space(id){
				var tmp = document.getElementById(id).value.split("/");
				var _tmp = get_size_info(obj["FREE SPACE"]);
				_tmp = get_basic_size(_tmp);
				_tmp = convert_size_to_info(_tmp);
				document.getElementById(id).value = tmp[0]+"/ "+_tmp.size+" "+_tmp.unit;
			}
		}
	}
}
//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2');
function show_help()
{
	switch(help)
	{
		case 1:
		var _win = window.open('../help/blu-ray/help_burning.html#burndisc','Help_burning','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/blu-ray/help_burning#burnimage','Help_burning','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;

		default:
		break;
	}
}


//=======================================================//
// Disc 
// 1. Format   -> Burning / Erease Disc
//=======================================================//
var disc ={
	txt_finish : "Complete Erase Disc",
	format_sts : 'init',
	"format" : function(){
		if(this.format_sts == 'format'){
			alert("<?php echo lang_get('volume_format_1')?>");
			return;
		}
		var cmd = "&mode=format_disc";
		//var php = "../php/schedule_init.php";
		var php = "../php/format_disc.php"; //NC1 KJS
		if(!confirm("<?php echo lang_get('schedule_msg_26')?>")){
				return 0;
		}
		
		sendRequest(on_disc_format,cmd,'post',php,true,true);
		this.format_sts = 'format';
		document.getElementById('burning_erase_disc').style.display = "block";
		
		function on_disc_format(oj){
			var res=decodeURIComponent(oj.responseText);
			
			/* New error handling */
			if(res.search('{')>-1){
				/* In case of tray is not closed */
				eval('var _ret = '+res);
				if(_ret.result == '-4'){
					/* Restore is working */
					//var _msg = _ret.message;
					var _msg = "<?php echo lang_get('storing_msg_2')?>";	// Multi-language conversion
					//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
				}else if(_ret.result == '-5'){
					/* Tray is not closed */
					//var _msg = _ret.message;
					var _msg = "<?php echo lang_get('schedule_msg_17')?>";	// Multi-language conversion
					//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
				}else if(_ret.result == '-6'){
					/* No disc in drive */
					//var _msg = _ret.message;
				  var _msg = "<?php echo lang_get('schedule_msg_18')?>";	// Multi-language conversion
					//document.getElementById("phpmsg").innerHTML = _msg;	// Message in popup window
				}else if(_ret.result == '-99'){
					var _msg = "<?php echo lang_get('login_msg_6')?>";
				}
					alert(_msg);	// Currently message is english
					//document.getElementById('idButtonBurnNext').innerHTML = "<input type='image' src='../images/btn/btn_confirm.gif' onclick='close_backup();'/>";
					//document.getElementById('idButtonBurnNext').style.visibility = "visible";
					//clearTimeout(timerA);
					document.getElementById('burning_erase_disc').style.display = "none";
					disc.format_sts = 'init';
					return;
				/************************/
			}
			
			
			var _tmp = res.split(":");
			var _code = _tmp[0];
			var _msg = _tmp[1];

			disc.format_sts = 'init';
			if(_code=="OK"){
				document.getElementById('burning_erase_disc').style.display = "none";
				alert("<?php echo lang_get('schedule_msg_25')?>");
				return;
			}else if(_code=="BUSY"){
				document.getElementById('burning_erase_disc').style.display = "none";
				alert("<?php echo lang_get('storing_msg_2')?>");
				return;
			}else{
				// Multi Language Apply
					if(_msg.search("NOT FORMATTABLE MEDIA") != -1)
					{
						_msg = "<?php echo lang_get('schedule_msg_34')?>";
					}
				
				alert("<?php echo lang_get('common_error')?> : "+_msg.replace('<BR />','\n'));
				document.getElementById('burning_erase_disc').style.display = "none";
				return;
			}
			
		}
	}
	
}
//=======================================================//
// Browsing Box Control
// will be done later
//=======================================================//
/*var brw_box = {
	mode : "",
	mode_list : ["data_burn","img_burn"],
	stat : {
		data : false,
		img : false
	},
	box_id : "",
	box_id_list : [],
	work : function(op,mode){
		this.mode = mode;
	},
	check : function({
		
	}
}*/


// Loading selected folder size
var folder_size = {
	status : 'init',
	loading_php : '../php/burning_get_folder_size.php',
	loading : function(){
		this.status = 'loading';
		var _folders = this.make_folders_str();
		sendRequest(on_loading, '&folders='+_folders, 'post', this.loading_php, true, true);
		
		function on_loading(oj){
			//var res=decodeURIComponent(oj.responseText);
			var res = oj.responseText;

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
			
			display_selected_files_list_mode();
			
			display_selected_files_size(gIdInBurn[0]);
			
			folder_size.status = 'init';
		}
	},
	make_folders_str : function(){
	/*
		var _ret = '';
		var _obj = gSelectedFileList;
		for(i=0;_obj[i];i++){
			if(_obj[i].type == 'directory'){
				_ret += _obj[i].path+_obj[i].name+':';
			}
		}
		return _ret;
	*/
		var str = '';		
		for(var i=0 ; document.getElementById('chk_'+i) ; i++) {			
			if(document.getElementById('chk_'+i).checked) str += document.getElementById('chk_'+i).value + ':';		
		}		
		return str;
	
	},
	update_list_size : function(oj){
		var _oj1 = gSelectedFileList;		
		var _oj = gEncodedSelectedFilename;
		
		for(i=0;_oj[i];i++){
			
			var _tmp_oj = oj[_oj[i]];
			if(_tmp_oj){		
				//alert(_tmp_oj.path.substr(9));				
				//alert(_oj1[i].path);
				var cmp = _tmp_oj.path.split('/');
				//alert(cmp[4]);
				
				//if(_tmp_oj.path.substr(9) == _oj1[i].path){		
					_oj1[i].size = _tmp_oj.size;
					//alert(_oj1[i].size);
					if(_tmp_oj.size<0){
						alert("<?php echo lang_get('folder_create_1');?> : "+_oj[i].name+' : removed');						
					}
				//}
			}
		}
	}
}
/*	Check '=' exists in path	*/
/*	Error 01		*/
function checkPath(index) {	
	if(gCurrentPath.search('=')>-1) {		
		alert('Current path includes "=".\n"=" is not supported for burning!');		
		if(document.getElementById('chk_'+index).checked) document.getElementById('chk_'+index).checked = false;		
		return false;	
	}	
	//alert(index+' : file name => '+gAllFileList[index].name);	
	if(gAllFileList[index].name.search('=')>-1) {		
		//alert(index+' : file name => '+gAllFileList[index].name);		
		alert('"=" is not supported for burning!');		
		if(document.getElementById('chk_'+index).checked) document.getElementById('chk_'+index).checked = false;		
		return false;	
	}	
	return true;
}
//========================================================//
// Volume popup window
//========================================================//
function open_popup(id)
{	
	document.getElementById(id).style.display = 'block';
	document.getElementById('burning_table').style.display = 'none';
}

function close_popup(id)
{
	document.getElementById('burning_table').style.display = 'block';
	document.getElementById(id).style.display = 'none';
}

//========================================================//
// Show volume info (Delete)
//========================================================//
function ShowDeleteVolume()
{
	var vol_num;

	var used_vol = "<?php echo $str1_array[2] ?>";	

	if("<?php echo $valid_vol_count ?>" == 2)
		used_vol +="<?php echo $str2_array[2] ?>";

	document.getElementById('idDelVolume').innerHTML= 
	"<?php echo lang_get('volume_msg_23')?> "+used_vol+" <?php echo lang_get('volume_msg_23_1')?>";
}

