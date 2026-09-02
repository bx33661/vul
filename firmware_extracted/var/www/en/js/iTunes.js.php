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
var gPhp = new Array("../php/service_get_itunes.php","../php/service_set_itunes.php");
var giTunes;
var giTunes_Update;

//========================================================//
// Get server time
//========================================================//
function Get_iTunes_Info()
{
	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var _item = res.split(':');
	
	giTunes	 			= _item[0];
	giTunes_Update	 		= _item[1];
	ShowiTunesInfo(giTunes,giTunes_Update);

}
//========================================================//
// Show server time
//========================================================//
function ShowiTunesInfo(giTunes,giTunes_Update)
{
	if (giTunes == 'off') {
				document.getElementById('Update_Term').disabled = true;
				document.getElementById('rdoiTunes_disable').checked 	= true;
		}else 		document.getElementById('rdoiTunes_enable').checked 	= true;
	if (giTunes_Update == 'force') 	document.getElementById('Update_Term').options[0].selected	= true;
		else 			document.getElementById('Update_Term').options[1].selected	= true;

}

//========================================================//
// Set time to server
//========================================================//
function Set_iTunes_Info()
{
	//=======================================================//
	// Popup
	//=======================================================//

	document.getElementById('idTable_Itunes').style.display = "none";
	document.getElementById('id_popup_itunes').style.display = "block";
	
	display_POPUP('setting_Itunes');
	
	var 	iTunes, Update;
		
	if (document.getElementById('Update_Term').options[0].selected) Update='force'
	 	else Update ='5min'
	if (document.getElementById('rdoiTunes_disable').checked) iTunes='off'
	 	else iTunes='on'

			
	var _txText =	'&rdoDAAP='+ iTunes
			+'&txtDAAPUpdate='+Update;
			
 
	sendRequest(onLoadST,_txText,'post',gPhp[1],true,true);
		

	return true;
	
}
function onLoadST(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//if(res != "") alert(res);
	Get_iTunes_Info();
	
	//=======================================================//
	// Popup
	//=======================================================//
	
	display_POPUP('complete');
	
}

//=======================================================//
// Popup
//=======================================================//
function close_popup(){
	document.getElementById('idTable_Itunes').style.display = "block";
	document.getElementById('id_popup_itunes').style.display = "none";
}

//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/service/help_itunes.html','Help_itunes','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}

// POPUP //
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
	
	if(mode == 'setting_Itunes'){
		popup_contents = "<?php echo lang_get('itunes_msg_2')?>";
		popup_button = 'off';
	}	


	if(mode == 'complete'){
		popup_contents = "<?php echo lang_get('itunes_msg_1')?>";
		popup_button_link = "close_popup();";
	}

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	
	document.getElementById('system_message').innerHTML = popup;


}


function ListEnable(){
 	document.getElementById('Update_Term').disabled = false;
}
function ListDisable(){
	document.getElementById('Update_Term').disabled = true;
}