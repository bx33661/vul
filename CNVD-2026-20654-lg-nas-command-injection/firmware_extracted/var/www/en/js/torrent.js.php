<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	lang_set_active_language($_GET['lang']);
?>



//========================================================//
// System / EMail menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/service_get_torrent.php","../php/service_set_torrent.php");
var gTorrent;

//========================================================//
// Get server time
//========================================================//
function Get_Torrent_Info()
{
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
		
	gTorrent 			= res;
	
	ShowTorrentInfo();

}
//========================================================//
// Show server time
//========================================================//
function ShowTorrentInfo()
{	
	if (gTorrent == 'off') 		document.getElementById('rdoTorrent_disable').checked 	= true;
		else 			document.getElementById('rdoTorrent_enable').checked 	= true;

}

//========================================================//
// Set time to server
//========================================================//
function Set_Torrent_Info()
{
	//=======================================================//
	// Popup
	//=======================================================//
	document.getElementById('idTable_Torrent').style.display = "none";
  	document.getElementById('id_popup_torrent').style.display = "block";	
  	
 	display_POPUP('setting_Torrent');
  	
	var 	torrent;
		
	if (document.getElementsByName('rdoTorrent')[0].checked) torrent='on'
	 	else torrent='off'
			
	var _txText =	'&rdoTorrent='+torrent;
	
	sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);

	return true;
	
}
function onLoadST(oj)
{
	var res = decodeURIComponent(oj.responseText);	
	//alert(res);
	Get_Torrent_Info();
	
	//=======================================================//
	// Popup
	//=======================================================//
       display_POPUP('complete');

}

//=======================================================//
// Popup
//=======================================================//
function close_popup(){

	//for checking $torrent in top.php 
	//window.location.reload();

	document.getElementById('id_popup_torrent').style.display = "none";
	document.getElementById('idTable_Torrent').style.display = "block";
}
//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/service/help_torrent.html','Help_torrent','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}


function display_POPUP(mode)
{

	var popup_header = new String();
	var popup_footer = new String();
	var popup_contents = new String();
	var popup_button = new String();
	var popup_button_header = new String();
	var popup_button_link = new String();
	var popup_button_footer = new String();

	popup_header 	= "<table width=\"420\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >"
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">";
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();
	//alert("this "+mode);
/////////User Create POPUP
	//alert(":"+mode+":");
	
	if(mode == 'setting_Torrent'){
		popup_contents = "<?php echo lang_get('torrent_msg_2')?>";
		popup_button = 'off';
	}	


	if(mode == 'complete'){
		popup_contents = "<?php echo lang_get('torrent_msg_1')?>";
		popup_button_link = "close_popup();";
	}

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	
	document.getElementById('system_message').innerHTML = popup;


}