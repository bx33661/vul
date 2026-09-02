<?php
	include "../session/session_manage.php";
	require_once("../multilang/multilang_api.php");
	
	if ( sm_session_check_on_popup() == FALSE )
	{
		//include "../php/msg_illegal_access.php";
		include "../php/msg_illegal_access_pop.php";
		//echo '-99';
		die();
	}
	
	
	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end


?>

<html>
<head>
<title>Welcome to LG Electronics</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript" src="../css/embed.js"></script>
<script language='javascript' src="../css/lg.js"></script>
<script language='javascript' src='../css/flash.js'></script>
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/css.css" rel="stylesheet" type="text/css">
<link href="../css/file_browser.css" rel="stylesheet" type="text/css">
<SCRIPT language='javascript' src="../css/selectBox.js"></SCRIPT>
<script language="javascript" src="../js/debug.js"></script>
<script language='javascript' src="../js/jslb_ajax.js.php"></script><!-- // Async communication //-->

<style type="text/css">
<!--
.style1 {
	color: #6E6F71;
	font-size: 10px;
}
//-->
</style>

<script type='text/javascript' src='../js/prototype.js'></script>
<script language='javascript' src='../js/common.js.php'></script>
<script language='javascript' src='../js/bd_pop_brows_display.js.php'></script>
<script language='javascript' src='../js/bd_pop_brows_act.js.php'></script>
<script>
<!--	
//=======================================================//
// Get browsing information from parent window
//=======================================================//
var path_mode = opener.document.getElementById('idPathMode').value;
var gOutId='idInputFieldId';	//ID to get output field ID in parent window
/*function send_selected_dir_names(){
	var fObj = $('files_slc_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = 0;
	for(var i=0;fObj.elements[i];i++){
		dir_obj = fObj.elements[i];
		if(dir_obj.type == 'radio' && dir_obj.checked == true){
			if(checked_cnt > 0){
				alert('Select only one folder.');
				return false;
			}else{
				checked_cnt++;
				checked_dir_name = dir_obj.value;
			}
		}
	} 
	if(checked_cnt == 0){
		alert("<?php echo lang_get('usb_msg_11')?>");
		return false;
	}else{
		var _id=opener.document.getElementById(gOutId).value;
		if(opener){
			opener.document.getElementById(_id).value = current_path+checked_dir_name;
		}else{
		}
		window.close();
	}
}*/

function send_selected_dir_names(){
		
	var fObj = $('files_slc_fm');
	var dir_obj = null;
	var checked_cnt = 0;
	var checked_dir_name = 0;

	var checkedEncodedDirName ='';
	
	for(var i=0;fObj.elements[i];i++){
		dir_obj = fObj.elements[i];
		if(dir_obj.type == 'radio' && dir_obj.checked == true){
			if(checked_cnt > 0){
				alert('Select only one folder.');
				return false;
			}else{
				checked_cnt++;
				checked_dir_name = dir_obj.value;

				checkedEncodedDirName=gEncodedFolderList[i];
			}
		}
	} 
	if(checked_cnt == 0){
		alert("<?php echo lang_get('usb_msg_11')?>");
		return false;
	}else{
		var _id=opener.document.getElementById(gOutId).value;
		if(opener){
			opener.document.getElementById(_id).value = current_path+checked_dir_name;
			if(opener.document.getElementById('cms_dest')) 
			{
				//path = gEncodedCurrentPath+checkedEncodedDirName;
				var path=decodeURIComponent(gEncodedCurrentPath+checkedEncodedDirName);
				opener.document.getElementById('cms_dest').value = path;//gEncodedCurrentPath+checkedEncodedDirName;
			}
		}else{
		}
		window.close();
	}
}


//-->
</script>
</head>
<body>

<table width="420" height="260" border="0" cellspacing="0" cellpadding="0" id="all_table">
  <tr>
    <td width="420" height="54" align="center" valign="center" background="../images/popup/txt_popup_bg_01.gif">
	<span class="popup_text"><?php echo lang_get('storing_msg_12')?></span>
    <!--<a href="javascript:void(0)"><img src="../images/popup/close_01.gif" width="28" height="16" border="0"></a>-->
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" style="padding:20 0 0 0px">
      <table width="380" border="0" cellspacing="0" cellpadding="0" id="ripp_table">
      <tr>
          <td align="center">
          <table width="370" border="0" cellpadding="0" cellspacing="0" bordercolor="#c9c9c9" id="folder">
           
            <tr>
              <td background="../images/Burn/c_line.gif"><table width="370" border="0" cellspacing="0" cellpadding="0" id="c_table_01">
                <tr>
                  <td width="370" height="23"><img src="../images/Burn/view_folder_list.gif" width="370" height="24"></td>
                </tr>
                <tr>
                  <td height="30" class="m_gray_04" style="padding:0 0 0 10px">
					<div id='idPath'></div>
				</td>
                </tr>
                <tr>
                  <td height="25" class="m_gray_04" style="padding:0 0 0 10px">
                  
                  	
					
					<input type="button" value="<?php echo lang_get('burning_msg_32')?>" onclick="move_up();">
		
					<table>
					<tr><td>
					<form name="new_directory_fm" id="new_directory_fm" method="post" onsubmit="return false;">
	<input type="text" name="new_directory_name" id="new_directory_name" maxlength='20' onblur='return FormCheck(this.id);'>
					<input type="button" value="<?php echo lang_get('esata_1')?>" onclick="create_dir();">
					</form>
					</td></tr>
					</table>
					
                  	<!-- File Box Start -->
                    <div>
						<form name="do_for_one_fm" id="do_for_one_fm" action="POST">
							<input type="hidden" value="" id="do_for_one">
						</form>
					</div>
					<div style="width:330px;height:200px;border:1px solid;">
						<div id="file_box_loading" style="display:none;"><?php echo lang_get('common_loading')?></div>
						<div id="file_box" style="width:325px;height:195px;border:0;display:block;"></div>
					</div>
					<div id="rename_box" style="display:none;">
						<div align="right" style="text-align:right">
							<a href="" onclick="hide_rename_box();return false;">x</a>
						</div>
						<div style="font-size:11px;">NewName</div>
						<form name="rename_fm" id="rename_fm" action="POST" onsubmit="return false;">
						<table class="simple_table">
							<tr><td>
								<input type="text" name="new_name" id="rename_fm_new_name" value="">
								<input type="hidden" name="old_name" id="rename_fm_old_name" value="">
								<input type="hidden" name="type" id="rename_fm_type" value="">
							</td><td>
								<input type="button" value="Submit" onclick="rename_submit();">
							</td></tr>
						</table>
						</form> 
					</div>
					<!-- File Box End -->
					<input type="button" onclick="send_selected_dir_names();" value="<?php echo lang_get('storing_msg_12')?>">
                  </td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="1" bgcolor="#c9c9c9"></td>
            </tr>
            
          </table>
</td>
        </tr>
        
        <tr>
          <td height="70" align="center" class="red_s2"></td>
        </tr>
      </table>
</td>
  </tr>
</table>

</body>
</html>

<script language="javascript">
function init()
{
	startLoad(path_mode);
}
init();
</script>
