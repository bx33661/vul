<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>
 

//========================================================//
// JAVA Script code
//========================================================//
var gPhp = new Array("../php/system_get_network.php","../php/system_set_network.php", "../php/service_get_timemachine.php","../php/service_set_timemachine.php","../php/service_get_browserinfo.php");
var gIdTable=new Array('idTable_HOST_EDIT','idTable_INTERFACE_EDIT','idTable_DOMAIN_EDIT','idTable_POPUP');

var gTimeMachine;
var gAfpShareFolderList = new String();

function display_POPUP(mode)
{

	//alert("display_POPUP");
  	var popup_header = new String();
	var popup_footer = new String();
	var popup_contents = new String();
	var popup_button = new String();
	var popup_button_header = new String();
	var popup_button_link = new String();
	var popup_button_footer = new String();
	
	
	popup_header 	= "<table width=\"420\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >"
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">"
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><a href=\"#\" onclick=\""; 
	popup_button_footer = "\"><img src=\"../images/btn/btn_confirm.gif\" border=\"0\"></a></td>"
        			+"</tr></table>";
	
	var popup = new String();


	if(mode == 'samehost'){
		popup_contents = "<?php echo lang_get('network_msg_2')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";
	}
        
	if(mode == 'host'){

		popup_contents = "<?php echo lang_get('TimeMachine_3')?>"
		popup_button_link = "Get_TimeMachine_check_enabled();showTable('idTable_HOST_EDIT');";
	}

	if(mode == 'host_err'){

		popup_contents = "<?php echo lang_get('network_msg_5')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";
	}

	if(mode == 'desc_err'){

		popup_contents = "<?php echo lang_get('network_msg_6')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";
	}

	if(mode == 'apply_host'){

		popup_contents = "<?php echo lang_get('TimeMachine_2')?>"
		popup_button = 'off';
	}
	if(mode == 'timemachine'){
		popup_contents = "<?php echo lang_get('TimeMachine_2')?>"
		popup_button = 'off';	

	}
	if(mode == 'tm_err'){
		popup_contents = "<?php echo lang_get('TimeMachine_5')?>"
		popup_button_link = "showTable('idTable_HOST_EDIT');";

	}
	if(mode == 'tm_ok'){
		popup_contents = "<?php echo lang_get('TimeMachine_3')?>"
		popup_button_link = "Get_TimeMachine_check_enabled();showTable('idTable_HOST_EDIT');";

	}
	if(mode =='invalid_os'){
		popup_contents = "<?php echo lang_get('TimeMachine_4')?>"
		popup_button = 'off';
	}

	if(popup_button == 'off') 
		popup = popup_header + popup_contents + popup_footer + "</table>";
	else 
		popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;
		
	
	
	//if(mode =='invalid_os'){
	//	showTable('invalid_os');
	//}
	//else
	//{
		showTable('processing');
	//}

	document.getElementById('system_message').innerHTML = popup;
	
	//}
	
	//gprevious_mode = mode;

}

function showTable(id)
{
	if(id == 'processing')
     	{
     		document.getElementById('idTable_POPUP').style.display = "block";
     		document.getElementById('idTable_Title').style.display = "none";
		document.getElementById('idTable_box').style.display = "none";		
	}
	else if(id == 'invalid_os')
     	{
     		document.getElementById('idTable_POPUP').style.display = "none";
     		document.getElementById('idTable_Title').style.display = "block";
		document.getElementById('idTable_box').style.display = "none";		
	}	
	else
	{	
		document.getElementById('idTable_POPUP').style.display = "none";	
     		document.getElementById('idTable_Title').style.display = "block";
		document.getElementById('idTable_box').style.display = "block";
	}
		
}

function Set_Timemachine_Info()
{
/*
	var 	TIMEMACHINE;
	if (document.getElementsByName('rdoTimeMachine_enable').checked) 
		TIMEMACHINE = 'on';
	else  	
		TIMEMACHINE = 'off';	
*/
	checkedItem=jQuery('input:checkbox[name=chkFolderItem]:checked').length;
	checkedMode = jQuery(".rdoTimeMachine:checked").val();

	if(checkedItem < 1 && checkedMode == 'on'){
		alert("You should select at least one folder");
		return false;
	}
	display_POPUP('timemachine');				
	Set_TimeMachine('timemachine');

/*	if(gTimeMachine != TIMEMACHINE)
	{
		display_POPUP('apply_host');
		Set_TimeMachine('timemachine');
	}
*/	
}

function onLoadST(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	code = res.split(':');
	//alert(code);
	if(code[0] == 'ok') {display_POPUP(code[1]);}

	//display_POPUP('apply_host');
}

function Set_TimeMachine(mode)
{	
	var TIMEMACHINE, HOSTNAME, MACADDR;
	var curItem = new String();
	var checkedItem = new String();
	var baseName = new String();
	var arr;
	
	display_POPUP('timemachine');

	if (document.getElementById('rdoTimeMachine_enable').checked) 
		TIMEMACHINE = 'on';
	else 
		TIMEMACHINE = 'off';	
	
	for(var i = 0; i < gAfpShareFolderList.length; i++)
	{
		arr = gAfpShareFolderList[i].split('/');
		baseName = arr[arr.length - 1];
    if(baseName == "service" && arr.length == 5) continue;

		if(document.getElementById('chkFolderItem'+i).checked)
			checkedItem = checkedItem + arr[arr.length - 1] + ':';
	}
	
	var _txText = '&timemachinOnOff='+TIMEMACHINE
				+ '&afpDirList='+checkedItem;

	sendRequest(onLoad_SetTM,_txText,'post',gPhp[3],true,true);

	return true;
}


function onLoad_SetTM(oj)
{
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	code = res.split(':');
	//alert(code);
	if(code[0] == 'ok') 
		display_POPUP(code[1]);
	else
	{
		document.getElementById('rdoTimeMachine_enable').checked = false;
		document.getElementById('rdoTimeMachine_disable').checked = true;

		display_POPUP(code[1]);
	}

	//display_POPUP('apply_host');	
}


function Get_TimeMachine_info()
{
	//Check valid OS (Mac)
	sendRequest(onLoad_GetWB,'','post',gPhp[4],true,true);
	return true;
}

function onLoad_GetWB(oj)
{
	var res = decodeURIComponent(oj.responseText);

	if(res.match('mac'))
	{
		Get_TimeMachine_check_enabled();
	}
	else
	{
		display_POPUP('invalid_os');
	}
}

function Get_TimeMachine_check_enabled()
{
	sendRequest(onLoad_GetTM,'','post',gPhp[2],true,true);
	return true;
}

function radioControl(mode){
	
	if(mode == "all_disable"){
		jQuery(".chkbox").attr("disabled", true); 
	}
	else{
		jQuery(".chkbox").attr("disabled", false); 
		
	}
	

}

function onLoad_GetTM(oj)
{
	var res = decodeURIComponent(oj.responseText);
	var _item = res.split('=>');
	var folderList = new String();
	var htmlToBeInserted = new String();
	var avahiConf = new String();
	var baseName = new String();
	var afpPath = new String();
	var checked;
	var i, j;
	var arr;
	var disable_flag;
	
	if(_item[1] == 'on')
	{
		document.getElementById('rdoTimeMachine_enable').checked = true;
		document.getElementById('rdoTimeMachine_disable').checked = false;
		disable_flag = "enabled";
	}
	else
	{
		document.getElementById('rdoTimeMachine_enable').checked = false;
		document.getElementById('rdoTimeMachine_disable').checked = true;;
		disable_flag = "disabled";
	}
	document.getElementById('idCheck_enable').style.display = "block";
	
	
	gAfpShareFolderList = _item[2].split(' ');
	avahiConf			= _item[3].split(' ');
	
	//alert("["+_item[2]+"]");
	//alert(gAfpShareFolderList.length);
		
	htmlToBeInserted="<table width='670' border='0' cellspacing='0' cellpadding='0'>";
	for(i = 0; i < gAfpShareFolderList.length /*&& i < gAfpShareFolderList[i] != ""*/; i++)
	{
		arr = gAfpShareFolderList[i].split('/');
		baseName = arr[arr.length - 1];

		if(baseName == "service" && arr.length == 5) continue;

		arr.splice(0,3);
		afpPath = arr.join('/');

		checked  = 'unchecked';
		
		for(j = 0; j < avahiConf.length ; j++)
			if(avahiConf[j] == baseName)
			{
				checked = 'checked';
				break;
			}

		//htmlToBeInserted = htmlToBeInserted + "<tr><td colspan='2' class='firstCol_250'><input type='checkbox' name='chkFolderItem' id='chkFolderItem"+i+"' "+checked+"/>&nbsp;"+gAfpShareFolderList[i].slice(7)+"</td></tr>";
		htmlToBeInserted =	htmlToBeInserted
							+ "<tr><td colspan='2' class='firstCol_250'><input type='checkbox' class='chkbox' name='chkFolderItem' id='chkFolderItem"+i+"' "+checked+" />&nbsp;"
							+ afpPath + "</td></tr>";
							//+ (gAfpShareFolderList[i].split('/'))[3] + "/" + baseName + "</td></tr>";
	}
	htmlToBeInserted = htmlToBeInserted +"</table>";
	
	document.getElementById('idAfpDirList').innerHTML = htmlToBeInserted;
	
//	if(disable_flag == "disabled"){
//		radioControl("all_disable");
	
//	}
}

//=======================================================//
// Help : dummy
//=======================================================//
function show_help()
{
	var _win = window.open('../help/service/help_machine.html','Help_system','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=500px,left=540,top=240');
    _win.focus();
	hPopWin = _win;
}
