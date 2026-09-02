<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
?>



//=======================================================//
// Page initialization
//=======================================================//
var page = {
	name : "dlna",
	init : function(){
		read_xml("cmsbackup.xml");
		browse('open', 'root');
		setDetail();
	}
}
//=======================================================//
// Open popup window for folder selection
//=======================================================//
function popup_file_browser(id)
{
	document.getElementById("idInputFieldId").value=id;	
	document.getElementById('idPathMode').value= "dlna";
	var win = window.open('../mobile/usb_pop_brows.php','DIR_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
	//var win = window.open('../popup/browsing_pop_01.php','DIR_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=500px,height=500px'); 
	win.focus(); 
}

//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/service/help_dlna.html','Help_ddns','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}

