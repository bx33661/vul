<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end

$vol_fstab = trim(exec('sudo nas-storage get vol_fstab'));

?> 


//========================================================//
// System / EMail menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/share_get_folder_info.php","../php/share_set_folder_info.php","../php/share_get_user_info.php","../php/share_get_group_info.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_FolderList',
			'idTable_FolderCreate_LocalUser','idTable_FolderEdit_LocalUser','idTable_FolderCreate_LocalGroup','idTable_FolderEdit_LocalGroup',
			'idTable_FolderCreate_DomainUser','idTable_FolderEdit_DomainUser','idTable_FolderCreate_DomainGroup','idTable_FolderEdit_DomainGroup',
			'idTable_Folder_POPUP');

var gLocal_User_Full_List = new String();
var gLocal_User_Member_List = new String();
var gLocal_Group_Full_List = new String();
var gLocal_Group_Member_List = new String();
var gDomain_User_Full_List = new String();
var gDomain_User_Member_List = new String();
var gDomain_Group_Full_List = new String();
var gDomain_Group_Member_List = new String();

var gfolder_rw_DomainUsers = new String();
var gfolder_ro_DomainUsers = new String();
var gfolder_rw_DomainGroups = new String();
var gfolder_ro_DomainGroups = new String();

var gnum_users,gnum_groups,gnum_Domainusers,gnum_Domaingroups,gnum_folders,gMax_Volume,gDomain,gDomain_name; 
var gVolume_info=new Array('vol_num', 'vol_list1', 'vol_list2');

//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	////debug(id);
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";
	document.getElementById(gIdTable[4]).style.display = "none";
	document.getElementById(gIdTable[5]).style.display = "none";
	document.getElementById(gIdTable[6]).style.display = "none";
	document.getElementById(gIdTable[7]).style.display = "none";
	document.getElementById(gIdTable[8]).style.display = "none";
	document.getElementById(gIdTable[9]).style.display = "none";
	
	
	if ( id != ''){
		document.getElementById(id).style.display = "block";
	}
	
	
}

function check_volume_config()
{
	var config_check = "<?php echo $vol_fstab ?>";
	if(config_check == "Config First")
	{
		alert("<?php echo lang_get('config_first')?>");
		return;
	}
	clearForm('idTable_UserCreate');
	GetDomainType();
	GetMaxVolume();
	GetLocalUserList();
	GetLocalUserList();
	GetLocalGroupList();
	GetDomainUserList();
	GetDomainGroupList();

}

function clearForm(id)
{
	//debug(id);
	if ( id == 'idTable_UserCreate'){

		document.getElementById('txtFolderNameLocalUser').value = '';
		document.getElementById('txtFolderDescLocalUser').value = '';
		document.getElementById('chkFolderWinLocalUser').checked = true;
		document.getElementById('chkFolderAFPLocalUser').checked = false;
		document.getElementById('chkFolderFTPLocalUser').checked = false;
		document.getElementById('chkFolderWebdavLocalUser').checked = false;		
		
		document.getElementById('rdoFolderAttrLocalUser_normal').checked = true;
		document.getElementById('rdoFolderRecyleLocalUser_enable').checked = true;
		document.getElementById('rdoFolderACLLocalUser_enable').checked = true;
	}

	
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
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">"
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();
	//alert("this "+mode);
/////////User Create POPUP
	if(mode == 'ID_err'){
		popup_contents = "<?php echo lang_get('folder_msg_1')?>";
		popup_button_link = "showTable('idTable_FolderCreate_LocalUser');";
	}
	if(mode == 'Desc_err'){
		popup_contents = "The entered Group Description is not valid<br>'%','&','\\',',\" are not allowed";
		popup_button_link = "showTable('idTable_FolderCreate_LocalUser');";
	}
	if(mode == 'DescEdit_err'){
		popup_contents = "The entered Group Description is not valid<br>'%','&','\\',',\" are not allowed";
		popup_button_link = "showTable('idTable_FolderEdit_LocalUser');";
	}

	if(mode == 'ID_conflict'){
		popup_contents = "<?php echo lang_get('folder_msg_3')?>";
		popup_button_link = "showTable('idTable_FolderCreate_LocalUser');";
	}
	if(mode == 'ACL_err'){
		popup_contents = "<?php echo lang_get('folder_msg_4')?>";
		popup_button_link = "showTable('idTable_FolderCreate_LocalUser');";
	}

	if(mode == 'ACLEdit_err'){
		popup_contents = "<?php echo lang_get('folder_msg_4')?>";
		popup_button_link = "showTable('idTable_FolderEdit_LocalUser');";
	}

	if(mode == 'Proto_err'){
		popup_contents = "<?php echo lang_get('folder_msg_5')?>";
		popup_button_link = "showTable('idTable_FolderCreate_LocalUser');";
	}

	if(mode == 'ProtoEdit_err'){
		popup_contents = "<?php echo lang_get('folder_msg_5')?>";
		popup_button_link = "showTable('idTable_FolderEdit_LocalUser');";
	}  

	if(mode == 'create_folder'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
    			+"</tr></table>";	
    		popup_button_header = "<tr><td align=\"center\">"
		popup_contents = "<?php echo lang_get('folder_msg_6')?>";
		popup_button = 'off';
	}

	if(mode == 'edit_folder'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";	
        	popup_button_header = "<tr><td align=\"center\">"		
		popup_contents = "<?php echo lang_get('folder_msg_8')?>";
		popup_button = 'off';
	}

	if(mode == 'delete_folder'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";		
        	popup_button_header = "<tr><td align=\"center\">"		
		popup_contents = "<?php echo lang_get('folder_msg_9')?>";
		popup_button = 'off';
	}

	if(mode == 'folder'){
		popup_contents = "<?php echo lang_get('folder_msg_7')?>";
		popup_button_link = "Get_Folder_Info();showTable('idTable_FolderList');";
	}

	if(mode == 'folder_edit'){
		popup_contents = "<?php echo lang_get('folder_msg_10')?>";
		popup_button_link = "Get_Folder_Info();showTable('idTable_FolderList');";
	}
	
	if(mode == 'folder_delete'){
		popup_contents = "<?php echo lang_get('folder_msg_11')?>";
		popup_button_link = "Get_Folder_Info();showTable('idTable_FolderList');";
	}

	if(mode == 'delete_none'){
		popup_contents = "<?php echo lang_get('folder_msg_12')?>";
		popup_button_link = "Get_Folder_Info();showTable('idTable_FolderList');";
	}

	//if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
	//	else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('idTable_Folder_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}



function Set_Folder_Info()
{

	
	if(IDCheck()){
		if(ACLCheck()){	
			if(ProtocolCheck()){
				if(DescCheck()){
					display_POPUP('create_folder');
					createFolder();
				}else{
					display_POPUP('Desc_err');
				}
			}else{
				display_POPUP('Proto_err');
			}	 
			
		} else{
		display_POPUP('ACL_err');
		}	 
			
		
	}else {
		display_POPUP('ID_err');

	}		

}

function Edit_Folder_Info()
{
	if(ACLCheckEdit()){	
			if(ProtocolEditCheck()){
				if(DescEditCheck()){
					display_POPUP('edit_folder');
					editFolder();
				}else{
					display_POPUP('DescEdit_err');
				}
			}else{
				display_POPUP('ProtoEdit_err');
			}	 
			
		} else{
		display_POPUP('ACLEdit_err');
	}	 
	
}

function Delete_Folder_Info()
{
	if(entry_check()){

		if(!confirm("<?php echo lang_get('folder_msg_folder_remove')?>")){
				return 0;
		}	
		display_POPUP('delete_folder');
		deleteFolder();
	}else display_POPUP('delete_none');
}

function ProtocolCheck(){
	if(document.getElementById('chkFolderWinLocalUser').checked == false && document.getElementById('chkFolderAFPLocalUser').checked == false && document.getElementById('chkFolderFTPLocalUser').checked == false 
		&& document.getElementById('chkFolderWebdavLocalUser').checked == false ){
		return false;
	} else {
		return true;	
	}
}
function ProtocolEditCheck(){
	if(document.getElementById('chkFolderWinEditLocalUser').checked == false && document.getElementById('chkFolderAFPEditLocalUser').checked == false && document.getElementById('chkFolderFTPEditLocalUser').checked == false ){
		return false;
	} else {
		return true;	
	}
}

function ACLCheck(){
  //return true;
	var folder_rw_localUsers = new String();
	var folder_ro_localUsers = new String();

	var folder_rw_localGroups = new String();
	var folder_ro_localGroups = new String();

	var folder_rw_DomainUsers = new String();
	var folder_ro_DomainUsers = new String();

	var folder_rw_DomainGroups = new String();
	var folder_ro_DomainGroups = new String();
	
	if(document.getElementById('rdoFolderACLLocalUser_enable').checked == true){	
	
	for(var i=1;i<gnum_users;i++){
		if(document.getElementById('checkboxRWUser'+i.toString()).checked) folder_rw_localUsers = document.getElementById('checkboxRWUser'+i.toString()).value+";"+ folder_rw_localUsers; 
		if(document.getElementById('checkboxROUser'+i.toString()).checked) folder_ro_localUsers = document.getElementById('checkboxROUser'+i.toString()).value+";"+ folder_ro_localUsers; 
		
	}

	for(var i=1;i<gnum_groups;i++){
		if(document.getElementById('checkboxRWGroup'+i.toString()).checked) folder_rw_localGroups = document.getElementById('checkboxRWGroup'+i.toString()).value+";"+ folder_rw_localGroups; 
		if(document.getElementById('checkboxROGroup'+i.toString()).checked) folder_ro_localGroups = document.getElementById('checkboxROGroup'+i.toString()).value+";"+ folder_ro_localGroups; 
		
	}


	for(var i=1;i<gnum_Domainusers;i++){
		if(document.getElementById('checkboxRWDomainUser'+i.toString()).checked) folder_rw_DomainUsers = document.getElementById('checkboxRWDomainUser'+i.toString()).value+";"+ folder_rw_DomainUsers; 
		if(document.getElementById('checkboxRODomainUser'+i.toString()).checked) folder_ro_DomainUsers = document.getElementById('checkboxRODomainUser'+i.toString()).value+";"+ folder_ro_DomainUsers; 
		
	}

	for(var i=1;i<gnum_Domaingroups;i++){
		if(document.getElementById('checkboxRWDomainGroup'+i.toString()).checked) folder_rw_DomainGroups = document.getElementById('checkboxRWDomainGroup'+i.toString()).value+";"+ folder_rw_DomainGroups; 
		if(document.getElementById('checkboxRODomainGroup'+i.toString()).checked) folder_ro_DomainGroups = document.getElementById('checkboxRODomainGroup'+i.toString()).value+";"+ folder_ro_DomainGroups; 
		
	}

	if((folder_rw_localUsers=='')&&(folder_ro_localUsers=='')&&(folder_rw_localGroups=='')&&(folder_ro_localGroups=='')&&(folder_rw_DomainUsers=='')&&(folder_ro_DomainUsers=='')&&(folder_rw_DomainGroups=='')&&(folder_ro_DomainGroups=='')){
		return false;
	} else {
	return true;
	}
	}else return true;
}



function ACLCheckEdit(){
  //return true;
	var folder_rw_localUsers = new String();
	var folder_ro_localUsers = new String();

	var folder_rw_localGroups = new String();
	var folder_ro_localGroups = new String();
		
	var folder_rw_DomainUsers = new String();
	var folder_ro_DomainUsers = new String();

	var folder_rw_DomainGroups = new String();
	var folder_ro_DomainGroups = new String();
	//var test = document.getElementById('checkboxRO1').value;
	//debug(gnum_users);
	if(document.getElementById('rdoFolderACLEditLocalUser_enable').checked == true){
	for(var i=1;i<gnum_users;i++){
		//debug( document.getElementById('checkboxEditRWUser'+i.toString()).value);	
	
		if(document.getElementById('checkboxEditRWUser'+i.toString()).checked) folder_rw_localUsers = document.getElementById('checkboxEditRWUser'+i.toString()).value+";"+ folder_rw_localUsers; 
		
		if(document.getElementById('checkboxEditROUser'+i.toString()).checked) folder_ro_localUsers = document.getElementById('checkboxEditROUser'+i.toString()).value+";"+ folder_ro_localUsers; 
		
	}

	for(var i=1;i<gnum_groups;i++){
		if(document.getElementById('checkboxEditRWGroup'+i.toString()).checked) folder_rw_localGroups = document.getElementById('checkboxEditRWGroup'+i.toString()).value+";"+ folder_rw_localGroups; 
		
		if(document.getElementById('checkboxEditROGroup'+i.toString()).checked) folder_ro_localGroups = document.getElementById('checkboxEditROGroup'+i.toString()).value+";"+ folder_ro_localGroups; 
		
	}


	for(var i=1;i<gnum_Domainusers;i++){
		if(document.getElementById('checkboxEditRWDomainUser'+i.toString()).checked) folder_rw_DomainUsers = document.getElementById('checkboxEditRWDomainUser'+i.toString()).value+";"+ folder_rw_DomainUsers; 
		
		if(document.getElementById('checkboxEditRODomainUser'+i.toString()).checked) folder_ro_DomainUsers = document.getElementById('checkboxEditRODomainUser'+i.toString()).value+";"+ folder_ro_DomainUsers; 
		
	}

	for(var i=1;i<gnum_Domaingroups;i++){
		if(document.getElementById('checkboxEditRWDomainGroup'+i.toString()).checked) folder_rw_DomainGroups = document.getElementById('checkboxEditRWDomainGroup'+i.toString()).value+";"+ folder_rw_DomainGroups; 
		
		if(document.getElementById('checkboxEditRODomainGroup'+i.toString()).checked) folder_ro_DomainGroups = document.getElementById('checkboxEditRODomainGroup'+i.toString()).value+";"+ folder_ro_DomainGroups; 
		
	}

	if((folder_rw_localUsers=='')&&(folder_ro_localUsers=='')&&(folder_rw_localGroups=='')&&(folder_ro_localGroups=='')&&(folder_rw_DomainUsers=='')&&(folder_ro_DomainUsers=='')&&(folder_rw_DomainGroups=='')&&(folder_ro_DomainGroups=='')){
		return false;
	} else {
	return true;
	}
	}else return true;
}


function SyncFormCreate(id)
{
	if ( id == 'idTable_FolderCreate_LocalUser'){

	document.getElementById('txtFolderNameLocalGroup').value = document.getElementById('txtFolderNameLocalUser').value;
	document.getElementById('txtFolderNameDomainUser').value = document.getElementById('txtFolderNameLocalUser').value;
	document.getElementById('txtFolderNameDomainGroup').value = document.getElementById('txtFolderNameLocalUser').value;

	document.getElementById('txtFolderDescLocalGroup').value = document.getElementById('txtFolderDescLocalUser').value;
	document.getElementById('txtFolderDescDomainUser').value = document.getElementById('txtFolderDescLocalUser').value;
	document.getElementById('txtFolderDescDomainGroup').value = document.getElementById('txtFolderDescLocalUser').value;
	
	for(var i=0;i<gMax_Volume;i++){	
		if(document.getElementById('VolLocalUser').options[i].selected == true){
			document.getElementById('VolLocalGroup').options[i].selected = true;
			document.getElementById('VolDomainUser').options[i].selected = true;
			document.getElementById('VolDomainGroup').options[i].selected = true;
		}
	}
	
	if(document.getElementById('chkFolderWinLocalUser').checked == true) {
		document.getElementById('chkFolderWinLocalGroup').checked = true;
		document.getElementById('chkFolderWinDomainUser').checked = true;
		document.getElementById('chkFolderWinDomainGroup').checked = true;

		}else{
			 document.getElementById('chkFolderWinLocalGroup').checked = false;
			 document.getElementById('chkFolderWinDomainUser').checked = false;
			 document.getElementById('chkFolderWinDomainGroup').checked = false;
		}


	if(document.getElementById('chkFolderAFPLocalUser').checked == true) {
		document.getElementById('chkFolderAFPLocalGroup').checked = true;
		document.getElementById('chkFolderAFPDomainUser').checked = true;
		document.getElementById('chkFolderAFPDomainGroup').checked = true;

		}else{
			document.getElementById('chkFolderAFPLocalGroup').checked = false;
			document.getElementById('chkFolderAFPDomainUser').checked = false;
			document.getElementById('chkFolderAFPDomainGroup').checked = false;
		}
	
	if(document.getElementById('chkFolderFTPLocalUser').checked == true) {
		document.getElementById('chkFolderFTPLocalGroup').checked = true;
		document.getElementById('chkFolderFTPDomainUser').checked = true;
		document.getElementById('chkFolderFTPDomainGroup').checked = true;
		}
			else{
			document.getElementById('chkFolderFTPLocalGroup').checked = false;	
			document.getElementById('chkFolderFTPDomainUser').checked = false;	
			document.getElementById('chkFolderFTPDomainGroup').checked = false;	
		}

		
	if(document.getElementById('chkFolderWebdavLocalUser').checked == true) {
		document.getElementById('chkFolderWebdavLocalGroup').checked = true;
		document.getElementById('chkFolderWebdavDomainUser').checked = true;
		document.getElementById('chkFolderWebdavDomainGroup').checked = true;

              document.getElementById('rdoFolderACLLocalGroup_disable').checked = true;
              document.getElementById('rdoFolderACLLocalGroup_enable').checked = false;
                     
              document.getElementById('rdoFolderACLLocalGroup_enable').disabled = true;
		document.getElementById('rdoFolderACLDomainUser_enable').disabled = true;
		document.getElementById('rdoFolderACLDomainGroup_enable').disabled = true;
		
	}
	else{
		document.getElementById('chkFolderWebdavLocalGroup').checked = false;	
		document.getElementById('chkFolderWebdavDomainUser').checked = false;	
		document.getElementById('chkFolderWebdavDomainGroup').checked = false;	

		document.getElementById('rdoFolderACLLocalGroup_enable').disabled = false;
              document.getElementById('rdoFolderACLDomainUser_enable').disabled = false;
		document.getElementById('rdoFolderACLDomainGroup_enable').disabled = false;
		
	}
	

	if(document.getElementById('rdoFolderAttrLocalUser_normal').checked == true) {
		document.getElementById('rdoFolderAttrLocalGroup_normal').checked = true;
		document.getElementById('rdoFolderAttrDomainUser_normal').checked = true;
		document.getElementById('rdoFolderAttrDomainGroup_normal').checked = true;
		}
			else {
			document.getElementById('rdoFolderAttrLocalGroup_hidden').checked = true
			document.getElementById('rdoFolderAttrDomainUser_hidden').checked = true
			document.getElementById('rdoFolderAttrDomainGroup_hidden').checked = true
		}
	
	if(document.getElementById('rdoFolderRecyleLocalUser_enable').checked == true) {
		document.getElementById('rdoFolderRecyleLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderRecyleDomainUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleDomainGroup_enable').checked = true;
		}
			else{ 
			document.getElementById('rdoFolderRecyleLocalGroup_disable').checked = true;		
			document.getElementById('rdoFolderRecyleDomainUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleDomainGroup_disable').checked = true;		

		}

	if(document.getElementById('rdoFolderACLLocalUser_enable').checked == true) {
		document.getElementById('rdoFolderACLLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderACLDomainUser_enable').checked = true;
		document.getElementById('rdoFolderACLDomainGroup_enable').checked = true;
		}
			else {
			document.getElementById('rdoFolderACLLocalGroup_disable').checked = true;
			document.getElementById('rdoFolderACLDomainUser_disable').checked = true;
			document.getElementById('rdoFolderACLDomainGroup_disable').checked = true;
		}

	}

	if ( id == 'idTable_FolderCreate_LocalGroup'){
	document.getElementById('txtFolderNameLocalUser').value = document.getElementById('txtFolderNameLocalGroup').value;
	document.getElementById('txtFolderNameDomainUser').value = document.getElementById('txtFolderNameLocalGroup').value;
	document.getElementById('txtFolderNameDomainGroup').value = document.getElementById('txtFolderNameLocalGroup').value;

	document.getElementById('txtFolderDescLocalUser').value = document.getElementById('txtFolderDescLocalGroup').value;
	document.getElementById('txtFolderDescDomainUser').value = document.getElementById('txtFolderDescLocalGroup').value;
	document.getElementById('txtFolderDescDomainGroup').value = document.getElementById('txtFolderDescLocalGroup').value;
	
	for(var i=0;i<gMax_Volume;i++){	
		if(document.getElementById('VolLocalGroup').options[i].selected == true){
			document.getElementById('VolLocalUser').options[i].selected = true;
			document.getElementById('VolDomainUser').options[i].selected = true;
			document.getElementById('VolDomainGroup').options[i].selected = true;
		}
	}
	
	if(document.getElementById('chkFolderWinLocalGroup').checked == true) {
		document.getElementById('chkFolderWinLocalUser').checked = true;
		document.getElementById('chkFolderWinDomainUser').checked = true;
		document.getElementById('chkFolderWinDomainGroup').checked = true;

		}else{
			 document.getElementById('chkFolderWinLocalUser').checked = false;
			 document.getElementById('chkFolderWinDomainUser').checked = false;
			 document.getElementById('chkFolderWinDomainGroup').checked = false;
		}


	if(document.getElementById('chkFolderAFPLocalGroup').checked == true) {
		document.getElementById('chkFolderAFPLocalUser').checked = true;
		document.getElementById('chkFolderAFPDomainUser').checked = true;
		document.getElementById('chkFolderAFPDomainGroup').checked = true;

		}else{
			document.getElementById('chkFolderAFPLocalUser').checked = false;
			document.getElementById('chkFolderAFPDomainUser').checked = false;
			document.getElementById('chkFolderAFPDomainGroup').checked = false;
		}
	
	if(document.getElementById('chkFolderFTPLocalGroup').checked == true) {
		document.getElementById('chkFolderFTPLocalUser').checked = true;
		document.getElementById('chkFolderFTPDomainUser').checked = true;
		document.getElementById('chkFolderFTPDomainGroup').checked = true;
		}
			else{
			document.getElementById('chkFolderFTPLocalUser').checked = false;	
			document.getElementById('chkFolderFTPDomainUser').checked = false;	
			document.getElementById('chkFolderFTPDomainGroup').checked = false;	
		}

	if(document.getElementById('chkFolderWebdavLocalGroup').checked == true) {
		document.getElementById('chkFolderWebdavLocalUser').checked = true;
		document.getElementById('chkFolderWebdavDomainUser').checked = true;
		document.getElementById('chkFolderWebdavDomainGroup').checked = true;

	       document.getElementById('rdoFolderACLLocalUser_disable').checked = true;
	       document.getElementById('rdoFolderACLLocalUser_enable').checked = false;

	       document.getElementById('rdoFolderACLLocalUser_enable').disabled = true;
		document.getElementById('rdoFolderACLDomainUser_enable').disabled = true;
		document.getElementById('rdoFolderACLDomainGroup_enable').disabled = true;       
		
	}
	else{
		document.getElementById('chkFolderWebdavLocalUser').checked = false;	
		document.getElementById('chkFolderWebdavDomainUser').checked = false;	
		document.getElementById('chkFolderWebdavDomainGroup').checked = false;	

		document.getElementById('rdoFolderACLLocalUser_enable').disabled = false;	
		document.getElementById('rdoFolderACLDomainUser_enable').disabled = false;	
		document.getElementById('rdoFolderACLDomainGroup_enable').disabled = false;	
	}

	if(document.getElementById('rdoFolderAttrLocalGroup_normal').checked == true) {
		document.getElementById('rdoFolderAttrLocalUser_normal').checked = true;
		document.getElementById('rdoFolderAttrDomainUser_normal').checked = true;
		document.getElementById('rdoFolderAttrDomainGroup_normal').checked = true;
		}
			else {
			document.getElementById('rdoFolderAttrLocalUser_hidden').checked = true
			document.getElementById('rdoFolderAttrDomainUser_hidden').checked = true
			document.getElementById('rdoFolderAttrDomainGroup_hidden').checked = true
		}
	
	if(document.getElementById('rdoFolderRecyleLocalGroup_enable').checked == true) {
		document.getElementById('rdoFolderRecyleLocalUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleDomainUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleDomainGroup_enable').checked = true;
		}
			else{ 
			document.getElementById('rdoFolderRecyleLocalUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleDomainUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleDomainGroup_disable').checked = true;		

		}

	if(document.getElementById('rdoFolderACLLocalGroup_enable').checked == true) {
		document.getElementById('rdoFolderACLLocalUser_enable').checked = true;
		document.getElementById('rdoFolderACLDomainUser_enable').checked = true;
		document.getElementById('rdoFolderACLDomainGroup_enable').checked = true;
		}
			else {
			document.getElementById('rdoFolderACLLocalUser_disable').checked = true;
			document.getElementById('rdoFolderACLDomainUser_disable').checked = true;
			document.getElementById('rdoFolderACLDomainGroup_disable').checked = true;
		}






	
	}

	if ( id == 'idTable_FolderCreate_DomainUser'){


	document.getElementById('txtFolderNameLocalUser').value = document.getElementById('txtFolderNameDomainUser').value;
	document.getElementById('txtFolderNameLocalGroup').value = document.getElementById('txtFolderNameDomainUser').value;
	document.getElementById('txtFolderNameDomainGroup').value = document.getElementById('txtFolderNameDomainUser').value;

	document.getElementById('txtFolderDescLocalUser').value = document.getElementById('txtFolderDescDomainUser').value;
	document.getElementById('txtFolderDescLocalGroup').value = document.getElementById('txtFolderDescDomainUser').value;
	document.getElementById('txtFolderDescDomainGroup').value = document.getElementById('txtFolderDescDomainUser').value;
	
	for(var i=0;i<gMax_Volume;i++){	
		if(document.getElementById('VolDomainUser').options[i].selected == true){
			document.getElementById('VolLocalUser').options[i].selected = true;
			document.getElementById('VolLocalGroup').options[i].selected = true;
			document.getElementById('VolDomainGroup').options[i].selected = true;
		}
	}
	
	if(document.getElementById('chkFolderWinDomainUser').checked == true) {
		document.getElementById('chkFolderWinLocalUser').checked = true;
		document.getElementById('chkFolderWinLocalGroup').checked = true;
		document.getElementById('chkFolderWinDomainGroup').checked = true;

		}else{
			 document.getElementById('chkFolderWinLocalUser').checked = false;
			 document.getElementById('chkFolderWinLocalGroup').checked = false;
			 document.getElementById('chkFolderWinDomainGroup').checked = false;
		}


	if(document.getElementById('chkFolderAFPDomainUser').checked == true) {
		document.getElementById('chkFolderAFPLocalUser').checked = true;
		document.getElementById('chkFolderAFPLocalGroup').checked = true;
		document.getElementById('chkFolderAFPDomainGroup').checked = true;

		}else{
			document.getElementById('chkFolderAFPLocalUser').checked = false;
			document.getElementById('chkFolderAFPLocalGroup').checked = false;
			document.getElementById('chkFolderAFPDomainGroup').checked = false;
		}
	
	if(document.getElementById('chkFolderFTPDomainUser').checked == true) {
		document.getElementById('chkFolderFTPLocalUser').checked = true;
		document.getElementById('chkFolderFTPLocalGroup').checked = true;
		document.getElementById('chkFolderFTPDomainGroup').checked = true;
		}
			else{
			document.getElementById('chkFolderFTPLocalUser').checked = false;	
			document.getElementById('chkFolderFTPLocalGroup').checked = false;	
			document.getElementById('chkFolderFTPDomainGroup').checked = false;	
		}

	if(document.getElementById('chkFolderWebdavDomainUser').checked == true) {
		document.getElementById('chkFolderWebdavLocalUser').checked = true;
		document.getElementById('chkFolderWebdavLocalGroup').checked = true;
		document.getElementById('chkFolderWebdavDomainGroup').checked = true;

		document.getElementById('rdoFolderACLDomainGroup_disable').checked = true;
              document.getElementById('rdoFolderACLDomainGroup_enable').checked = false;

		document.getElementById('rdoFolderACLLocalGroup_enable').disabled = true;
		document.getElementById('rdoFolderACLLocalUser_enable').disabled = true;
		document.getElementById('rdoFolderACLDomainGroup_enable').disabled = true;

		
	}
	else{
		document.getElementById('chkFolderWebdavLocalUser').checked = false;	
		document.getElementById('chkFolderWebdavLocalGroup').checked = false;	
		document.getElementById('chkFolderWebdavDomainGroup').checked = false;	

		document.getElementById('rdoFolderACLLocalUser_enable').disabled = false;
              document.getElementById('rdoFolderACLLocalGroup_enable').disabled = false;
              document.getElementById('rdoFolderACLDomainGroup_enable').disabled = false;
		
	}

	if(document.getElementById('rdoFolderAttrDomainUser_normal').checked == true) {
		document.getElementById('rdoFolderAttrLocalUser_normal').checked = true;
		document.getElementById('rdoFolderAttrLocalGroup_normal').checked = true;
		document.getElementById('rdoFolderAttrDomainGroup_normal').checked = true;
		}
			else {
			document.getElementById('rdoFolderAttrLocalUser_hidden').checked = true
			document.getElementById('rdoFolderAttrLocalGroup_hidden').checked = true
			document.getElementById('rdoFolderAttrDomainGroup_hidden').checked = true
		}
	
	if(document.getElementById('rdoFolderRecyleDomainUser_enable').checked == true) {
		document.getElementById('rdoFolderRecyleLocalUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderRecyleDomainGroup_enable').checked = true;
		}
			else{ 
			document.getElementById('rdoFolderRecyleLocalUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleLocalGroup_disable').checked = true;		
			document.getElementById('rdoFolderRecyleDomainGroup_disable').checked = true;		

		}

	if(document.getElementById('rdoFolderACLDomainUser_enable').checked == true) {
		document.getElementById('rdoFolderACLLocalUser_enable').checked = true;
		document.getElementById('rdoFolderACLLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderACLDomainGroup_enable').checked = true;
		}
			else {
			document.getElementById('rdoFolderACLLocalUser_disable').checked = true;
			document.getElementById('rdoFolderACLLocalGroup_disable').checked = true;
			document.getElementById('rdoFolderACLDomainGroup_disable').checked = true;
		}



	
	}

	if ( id == 'idTable_FolderCreate_DomainGroup'){

	document.getElementById('txtFolderNameLocalUser').value = document.getElementById('txtFolderNameDomainGroup').value;
	document.getElementById('txtFolderNameLocalGroup').value = document.getElementById('txtFolderNameDomainGroup').value;
	document.getElementById('txtFolderNameDomainUser').value = document.getElementById('txtFolderNameDomainGroup').value;

	document.getElementById('txtFolderDescLocalUser').value = document.getElementById('txtFolderDescDomainGroup').value;
	document.getElementById('txtFolderDescLocalGroup').value = document.getElementById('txtFolderDescDomainGroup').value;
	document.getElementById('txtFolderDescDomainGroup').value = document.getElementById('txtFolderDescDomainGroup').value;
	
	for(var i=0;i<gMax_Volume;i++){	
		if(document.getElementById('VolDomainGroup').options[i].selected == true){
			document.getElementById('VolLocalUser').options[i].selected = true;
			document.getElementById('VolLocalGroup').options[i].selected = true;
			document.getElementById('VolDomainUser').options[i].selected = true;
		}
	}
	
	if(document.getElementById('chkFolderWinDomainGroup').checked == true) {
		document.getElementById('chkFolderWinLocalUser').checked = true;
		document.getElementById('chkFolderWinLocalGroup').checked = true;
		document.getElementById('chkFolderWinDomainUser').checked = true;

		}else{
			 document.getElementById('chkFolderWinLocalUser').checked = false;
			 document.getElementById('chkFolderWinLocalGroup').checked = false;
			 document.getElementById('chkFolderWinDomainUser').checked = false;
		}


	if(document.getElementById('chkFolderAFPDomainGroup').checked == true) {
		document.getElementById('chkFolderAFPLocalUser').checked = true;
		document.getElementById('chkFolderAFPLocalGroup').checked = true;
		document.getElementById('chkFolderAFPDomainUser').checked = true;

		}else{
			document.getElementById('chkFolderAFPLocalUser').checked = false;
			document.getElementById('chkFolderAFPLocalGroup').checked = false;
			document.getElementById('chkFolderAFPDomainUser').checked = false;
		}
	
	if(document.getElementById('chkFolderFTPDomainGroup').checked == true) {
		document.getElementById('chkFolderFTPLocalUser').checked = true;
		document.getElementById('chkFolderFTPLocalGroup').checked = true;
		document.getElementById('chkFolderFTPDomainUser').checked = true;
		}
			else{
			document.getElementById('chkFolderFTPLocalUser').checked = false;	
			document.getElementById('chkFolderFTPLocalGroup').checked = false;	
			document.getElementById('chkFolderFTPDomainUser').checked = false;	
		}

	if(document.getElementById('chkFolderWebdavDomainGroup').checked == true) {
		document.getElementById('chkFolderWebdavLocalUser').checked = true;
		document.getElementById('chkFolderWebdavLocalGroup').checked = true;
		document.getElementById('chkFolderWebdavDomainUser').checked = true;

		document.getElementById('rdoFolderACLDomainUser_disable').checked = true;
	       document.getElementById('rdoFolderACLDomainUser_enable').checked = false;

		document.getElementById('rdoFolderACLLocalUser_enable').disabled = true;
		document.getElementById('rdoFolderACLLocalGroup_enable').disabled = true;
		document.getElementById('rdoFolderACLDomainUser_enable').disabled = true;    

	}
	else{
		document.getElementById('chkFolderWebdavLocalUser').checked = false;	
		document.getElementById('chkFolderWebdavLocalGroup').checked = false;	
		document.getElementById('chkFolderWebdavDomainUser').checked = false;	


		document.getElementById('rdoFolderACLLocalUser_enable').disabled = false;	
		document.getElementById('rdoFolderACLLocalGroup_enable').disabled = false;	
		document.getElementById('rdoFolderACLDomainUser_enable').disabled = false;	
		
	}



	if(document.getElementById('rdoFolderAttrDomainGroup_normal').checked == true) {
		document.getElementById('rdoFolderAttrLocalUser_normal').checked = true;
		document.getElementById('rdoFolderAttrLocalGroup_normal').checked = true;
		document.getElementById('rdoFolderAttrDomainUser_normal').checked = true;
		}
			else {
			document.getElementById('rdoFolderAttrLocalUser_hidden').checked = true
			document.getElementById('rdoFolderAttrLocalGroup_hidden').checked = true
			document.getElementById('rdoFolderAttrDomainUser_hidden').checked = true
		}
	
	if(document.getElementById('rdoFolderRecyleDomainGroup_enable').checked == true) {
		document.getElementById('rdoFolderRecyleLocalUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderRecyleDomainUser_enable').checked = true;
		}
			else{ 
			document.getElementById('rdoFolderRecyleLocalUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleLocalGroup_disable').checked = true;		
			document.getElementById('rdoFolderRecyleDomainUser_disable').checked = true;		

		}

	if(document.getElementById('rdoFolderACLDomainGroup_enable').checked == true) {
		document.getElementById('rdoFolderACLLocalUser_enable').checked = true;
		document.getElementById('rdoFolderACLLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderACLDomainUser_enable').checked = true;
		}
			else {
			document.getElementById('rdoFolderACLLocalUser_disable').checked = true;
			document.getElementById('rdoFolderACLLocalGroup_disable').checked = true;
			document.getElementById('rdoFolderACLDomainUser_disable').checked = true;
		}

	
	}
	
	

//
}


function SyncFormEdit(id)
{
	if ( id == 'idTable_FolderEdit_LocalUser'){
	
	
	document.getElementById('txtFolderDescEditLocalGroup').value = document.getElementById('txtFolderDescEditLocalUser').value;
	document.getElementById('txtFolderDescEditDomainUser').value = document.getElementById('txtFolderDescEditLocalUser').value;
	document.getElementById('txtFolderDescEditDomainGroup').value = document.getElementById('txtFolderDescEditLocalUser').value;
		
	if(document.getElementById('chkFolderWinEditLocalUser').checked == true) {
			document.getElementById('chkFolderWinEditLocalGroup').checked = true;
			document.getElementById('chkFolderWinEditDomainUser').checked = true;
			document.getElementById('chkFolderWinEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderWinEditLocalGroup').checked = false;
			document.getElementById('chkFolderWinEditDomainUser').checked = false;
			document.getElementById('chkFolderWinEditDomainGroup').checked = false;	
			}


	if(document.getElementById('chkFolderAFPEditLocalUser').checked == true) {
			document.getElementById('chkFolderAFPEditLocalGroup').checked = true;
			document.getElementById('chkFolderAFPEditDomainUser').checked = true;
			document.getElementById('chkFolderAFPEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderAFPEditLocalGroup').checked = false;
			document.getElementById('chkFolderAFPEditDomainUser').checked = false;
			document.getElementById('chkFolderAFPEditDomainGroup').checked = false;
			}
	
	if(document.getElementById('chkFolderFTPEditLocalUser').checked == true) {
			document.getElementById('chkFolderFTPEditLocalGroup').checked = true;
			document.getElementById('chkFolderFTPEditDomainUser').checked = true;
			document.getElementById('chkFolderFTPEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderFTPEditLocalGroup').checked = false;	
			document.getElementById('chkFolderFTPEditDomainUser').checked = false;	
			document.getElementById('chkFolderFTPEditDomainGroup').checked = false;	
			}
			
	if(document.getElementById('chkFolderWebdavEditLocalUser').checked == true) {
			document.getElementById('chkFolderWebdavEditLocalGroup').checked = true;
			document.getElementById('chkFolderWebdavEditDomainUser').checked = true;
			document.getElementById('chkFolderWebdavEditDomainGroup').checked = true;

                     document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true;
                     document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = false;
                     
                     document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = true;
			document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = true;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = true;
								     	

			}
			else {
			document.getElementById('chkFolderWebdavEditLocalGroup').checked = false;	
			document.getElementById('chkFolderWebdavEditDomainUser').checked = false;	
			document.getElementById('chkFolderWebdavEditDomainGroup').checked = false;	

			document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = false;
                     document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = false;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = false;
			

			}
			

	if(document.getElementById('rdoFolderAttrEditLocalUser_normal').checked == true) {
			document.getElementById('rdoFolderAttrEditLocalGroup_normal').checked = true;
			document.getElementById('rdoFolderAttrEditDomainUser_normal').checked = true;
			document.getElementById('rdoFolderAttrEditDomainGroup_normal').checked = true;

			}
			else {
			document.getElementById('rdoFolderAttrEditLocalGroup_hidden').checked = true
			document.getElementById('rdoFolderAttrEditDomainUser_hidden').checked = true
			document.getElementById('rdoFolderAttrEditDomainGroup_hidden').checked = true
			}
			
	
	if(document.getElementById('rdoFolderRecyleEditLocalUser_enable').checked == true) {
			document.getElementById('rdoFolderRecyleEditLocalGroup_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditDomainUser_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditDomainGroup_enable').checked = true;
			}
			else {
			document.getElementById('rdoFolderRecyleEditLocalGroup_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditDomainUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditDomainGroup_disable').checked = true;		
			}

	if(document.getElementById('rdoFolderACLEditLocalUser_enable').checked == true) {
			document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = true;
			document.getElementById('rdoFolderACLEditDomainUser_enable').checked = true;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = true;
			}
			else {
				document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true;
				document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true;
				document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true;
			}

	}

	if ( id == 'idTable_FolderEdit_LocalGroup'){


	document.getElementById('txtFolderDescEditLocalUser').value = document.getElementById('txtFolderDescEditLocalGroup').value;
	document.getElementById('txtFolderDescEditDomainUser').value = document.getElementById('txtFolderDescEditLocalGroup').value;
	document.getElementById('txtFolderDescEditDomainGroup').value = document.getElementById('txtFolderDescEditLocalGroup').value;
		
	if(document.getElementById('chkFolderWinEditLocalGroup').checked == true) {
			document.getElementById('chkFolderWinEditLocalUser').checked = true;
			document.getElementById('chkFolderWinEditDomainUser').checked = true;
			document.getElementById('chkFolderWinEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderWinEditLocalUser').checked = false;
			document.getElementById('chkFolderWinEditDomainUser').checked = false;
			document.getElementById('chkFolderWinEditDomainGroup').checked = false;	
			}


	if(document.getElementById('chkFolderAFPEditLocalGroup').checked == true) {
			document.getElementById('chkFolderAFPEditLocalUser').checked = true;
			document.getElementById('chkFolderAFPEditDomainUser').checked = true;
			document.getElementById('chkFolderAFPEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderAFPEditLocalUser').checked = false;
			document.getElementById('chkFolderAFPEditDomainUser').checked = false;
			document.getElementById('chkFolderAFPEditDomainGroup').checked = false;
			}
	
	if(document.getElementById('chkFolderFTPEditLocalGroup').checked == true) {
			document.getElementById('chkFolderFTPEditLocalUser').checked = true;
			document.getElementById('chkFolderFTPEditDomainUser').checked = true;
			document.getElementById('chkFolderFTPEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderFTPEditLocalUser').checked = false;	
			document.getElementById('chkFolderFTPEditDomainUser').checked = false;	
			document.getElementById('chkFolderFTPEditDomainGroup').checked = false;	
			}

	if(document.getElementById('chkFolderWebdavEditLocalGroup').checked == true) {

			document.getElementById('chkFolderWebdavEditLocalUser').checked = true;
			document.getElementById('chkFolderWebdavEditDomainUser').checked = true;
			document.getElementById('chkFolderWebdavEditDomainGroup').checked = true;

                     document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true;
                     document.getElementById('rdoFolderACLEditLocalUser_enable').checked = false;

                     document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = true;
			document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = true;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = true;              

			
			}
			else {
			document.getElementById('chkFolderWebdavEditLocalUser').checked = false;	
			document.getElementById('chkFolderWebdavEditDomainUser').checked = false;	
			document.getElementById('chkFolderWebdavEditDomainGroup').checked = false;	

			document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = false;	
			document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = false;	
			document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = false;	
	
			}

	if(document.getElementById('rdoFolderAttrEditLocalGroup_normal').checked == true) {
			document.getElementById('rdoFolderAttrEditLocalUser_normal').checked = true;
			document.getElementById('rdoFolderAttrEditDomainUser_normal').checked = true;
			document.getElementById('rdoFolderAttrEditDomainGroup_normal').checked = true;

			}
			else {
			document.getElementById('rdoFolderAttrEditLocalUser_hidden').checked = true
			document.getElementById('rdoFolderAttrEditDomainUser_hidden').checked = true
			document.getElementById('rdoFolderAttrEditDomainGroup_hidden').checked = true
			}
			
	
	if(document.getElementById('rdoFolderRecyleEditLocalGroup_enable').checked == true) {
			document.getElementById('rdoFolderRecyleEditLocalUser_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditDomainUser_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditDomainGroup_enable').checked = true;
			}
			else {
			document.getElementById('rdoFolderRecyleEditLocalUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditDomainUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditDomainGroup_disable').checked = true;		
			}

	if(document.getElementById('rdoFolderACLEditLocalGroup_enable').checked == true) {
			document.getElementById('rdoFolderACLEditLocalUser_enable').checked = true;
			document.getElementById('rdoFolderACLEditDomainUser_enable').checked = true;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = true;
			}
			else {
				document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true;
				document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true;
				document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true;
			}


	

	
	}

	if ( id == 'idTable_FolderEdit_DomainUser'){

	document.getElementById('txtFolderDescEditLocalUser').value = document.getElementById('txtFolderDescEditDomainUser').value;
	document.getElementById('txtFolderDescEditLocalGroup').value = document.getElementById('txtFolderDescEditDomainUser').value;
	document.getElementById('txtFolderDescEditDomainGroup').value = document.getElementById('txtFolderDescEditDomainUser').value;
		
	if(document.getElementById('chkFolderWinEditDomainUser').checked == true) {
			document.getElementById('chkFolderWinEditLocalGroup').checked = true;
			document.getElementById('chkFolderWinEditLocalUser').checked = true;
			document.getElementById('chkFolderWinEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderWinEditLocalGroup').checked = false;
			document.getElementById('chkFolderWinEditLocalUser').checked = false;
			document.getElementById('chkFolderWinEditDomainGroup').checked = false;	
			}


	if(document.getElementById('chkFolderAFPEditDomainUser').checked == true) {
			document.getElementById('chkFolderAFPEditLocalGroup').checked = true;
			document.getElementById('chkFolderAFPEditLocalUser').checked = true;
			document.getElementById('chkFolderAFPEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderAFPEditLocalGroup').checked = false;
			document.getElementById('chkFolderAFPEditLocalUser').checked = false;
			document.getElementById('chkFolderAFPEditDomainGroup').checked = false;
			}
	
	if(document.getElementById('chkFolderFTPEditDomainUser').checked == true) {
			document.getElementById('chkFolderFTPEditLocalGroup').checked = true;
			document.getElementById('chkFolderFTPEditLocalUser').checked = true;
			document.getElementById('chkFolderFTPEditDomainGroup').checked = true;
			}
			else {
			document.getElementById('chkFolderFTPEditLocalGroup').checked = false;	
			document.getElementById('chkFolderFTPEditLocalUser').checked = false;	
			document.getElementById('chkFolderFTPEditDomainGroup').checked = false;	
			}

	if(document.getElementById('chkFolderWebdavEditDomainUser').checked == true) {
			document.getElementById('chkFolderWebdavEditLocalGroup').checked = true;
			document.getElementById('chkFolderWebdavEditLocalUser').checked = true;
			document.getElementById('chkFolderWebdavEditDomainGroup').checked = true;

                     document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true;
                     document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = false;
                     
                     document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = true;
			document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = true;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = true;
			
			}
			else {
			document.getElementById('chkFolderWebdavEditLocalGroup').checked = false;	
			document.getElementById('chkFolderWebdavEditLocalUser').checked = false;	
			document.getElementById('chkFolderWebdavEditDomainGroup').checked = false;	
		
			document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = false;
                 	document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = false;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = false;
			
			}


	if(document.getElementById('rdoFolderAttrEditDomainUser_normal').checked == true) {
			document.getElementById('rdoFolderAttrEditLocalGroup_normal').checked = true;
			document.getElementById('rdoFolderAttrEditLocalUser_normal').checked = true;
			document.getElementById('rdoFolderAttrEditDomainGroup_normal').checked = true;

			}
			else {
			document.getElementById('rdoFolderAttrEditLocalGroup_hidden').checked = true
			document.getElementById('rdoFolderAttrEditLocalUser_hidden').checked = true
			document.getElementById('rdoFolderAttrEditDomainGroup_hidden').checked = true
			}
			
	
	if(document.getElementById('rdoFolderRecyleEditDomainUser_enable').checked == true) {
			document.getElementById('rdoFolderRecyleEditLocalGroup_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditLocalUser_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditDomainGroup_enable').checked = true;
			}
			else {
			document.getElementById('rdoFolderRecyleEditLocalGroup_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditLocalUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditDomainGroup_disable').checked = true;		
			}

	if(document.getElementById('rdoFolderACLEditDomainUser_enable').checked == true) {
			document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = true;
			document.getElementById('rdoFolderACLEditLocalUser_enable').checked = true;
			document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = true;
			}
			else {
				document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true;
				document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true;
				document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true;
			}

	

	
	}
	if ( id == 'idTable_FolderEdit_DomainGroup'){

	document.getElementById('txtFolderDescEditLocalGroup').value = document.getElementById('txtFolderDescEditDomainGroup').value;
	document.getElementById('txtFolderDescEditDomainUser').value = document.getElementById('txtFolderDescEditDomainGroup').value;
	document.getElementById('txtFolderDescEditLocalUser').value = document.getElementById('txtFolderDescEditDomainGroup').value;
		
	if(document.getElementById('chkFolderWinEditDomainGroup').checked == true) {
			document.getElementById('chkFolderWinEditLocalGroup').checked = true;
			document.getElementById('chkFolderWinEditDomainUser').checked = true;
			document.getElementById('chkFolderWinEditLocalUser').checked = true;
			}
			else {
			document.getElementById('chkFolderWinEditLocalGroup').checked = false;
			document.getElementById('chkFolderWinEditDomainUser').checked = false;
			document.getElementById('chkFolderWinEditLocalUser').checked = false;	
			}


	if(document.getElementById('chkFolderAFPEditDomainGroup').checked == true) {
			document.getElementById('chkFolderAFPEditLocalGroup').checked = true;
			document.getElementById('chkFolderAFPEditDomainUser').checked = true;
			document.getElementById('chkFolderAFPEditLocalUser').checked = true;
			}
			else {
			document.getElementById('chkFolderAFPEditLocalGroup').checked = false;
			document.getElementById('chkFolderAFPEditDomainUser').checked = false;
			document.getElementById('chkFolderAFPEditLocalUser').checked = false;
			}
	
	if(document.getElementById('chkFolderFTPEditDomainGroup').checked == true) {
			document.getElementById('chkFolderFTPEditLocalGroup').checked = true;
			document.getElementById('chkFolderFTPEditDomainUser').checked = true;
			document.getElementById('chkFolderFTPEditLocalUser').checked = true;
			}
			else {
			document.getElementById('chkFolderFTPEditLocalGroup').checked = false;	
			document.getElementById('chkFolderFTPEditDomainUser').checked = false;	
			document.getElementById('chkFolderFTPEditLocalUser').checked = false;	
			}

	if(document.getElementById('chkFolderWebdavEditDomainGroup').checked == true) {
			document.getElementById('chkFolderWebdavEditLocalGroup').checked = true;
			document.getElementById('chkFolderWebdavEditDomainUser').checked = true;
			document.getElementById('chkFolderWebdavEditLocalUser').checked = true;

                     document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true;
                     document.getElementById('rdoFolderACLEditDomainUser_enable').checked = false;

                     document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = true;
			document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = true;
			document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = true;   

			
			}
			else {
			document.getElementById('chkFolderWebdavEditLocalGroup').checked = false;	
			document.getElementById('chkFolderWebdavEditDomainUser').checked = false;	
			document.getElementById('chkFolderWebdavEditLocalUser').checked = false;	


			document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = false;	
			document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = false;	
			document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = false;

			
			}			

	if(document.getElementById('rdoFolderAttrEditDomainGroup_normal').checked == true) {
			document.getElementById('rdoFolderAttrEditLocalGroup_normal').checked = true;
			document.getElementById('rdoFolderAttrEditDomainUser_normal').checked = true;
			document.getElementById('rdoFolderAttrEditLocalUser_normal').checked = true;

			}
			else {
			document.getElementById('rdoFolderAttrEditLocalGroup_hidden').checked = true
			document.getElementById('rdoFolderAttrEditDomainUser_hidden').checked = true
			document.getElementById('rdoFolderAttrEditLocalUser_hidden').checked = true
			}
			
	
	if(document.getElementById('rdoFolderRecyleEditDomainGroup_enable').checked == true) {
			document.getElementById('rdoFolderRecyleEditLocalGroup_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditDomainUser_enable').checked = true;
			document.getElementById('rdoFolderRecyleEditLocalUser_enable').checked = true;
			}
			else {
			document.getElementById('rdoFolderRecyleEditLocalGroup_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditDomainUser_disable').checked = true;		
			document.getElementById('rdoFolderRecyleEditLocalUser_disable').checked = true;		
			}

	if(document.getElementById('rdoFolderACLEditDomainGroup_enable').checked == true) {
			document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = true;
			document.getElementById('rdoFolderACLEditDomainUser_enable').checked = true;
			document.getElementById('rdoFolderACLEditLocalUser_enable').checked = true;
			}
			else {
				document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true;
				document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true;
				document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true;
			}

	

	
	}

}



//========================================================//
// Get server time
//========================================================//
function Get_Folder_Info()
{
	
	showTable('idTable_FolderList');
	var _txText ='&txtMode='+'FolderFullList';
	
	sendRequest(onLoadFolderList,_txText,'post',gPhp[0],true,true);
	return true;
}
function onLoadFolderList(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var _item = res.split(':');
        _item = _item.sort();
	
	gnum_folders = _item.length;
	ShowFolderInfo(_item);
//	showTable('idTable_Group_List');	

}


function ShowFolderInfo(_item)
{

	var index = _item.length;
	var i;
	var folder_table_entry = new String();
	var folder = new Array ();
	var disabled = new String();

	//if(index == 1) folder_table_entry = 'No folder exists';
	//	else {	

		//for(i=0;i<index-1;i++){
		for(i=0;i<index;i++){
		folder = _item[i].split(';');
		if(folder[0]!=''){ 

		// NC1
		if (folder[0] == 'service' 
			|| folder[0] == 'disk1' || folder[0] == 'disk2' || folder[0] == 'linear' || folder[0] == 'raid') {
			disabled = 'disabled';
		} else {
			disabled = '';
		}
		
		folder_table_entry=	folder_table_entry +"<tr><td class='firstCol_250' style='width:200px;'>"
                           		+"<input type=\"checkbox\" name=\"checkbox"+i+"\" id=\"checkbox"+i+"\" value="+folder[0]+" "+disabled+">"
                           		+"<a href=\"#\" onclick=\"ShowFolderEdit('"+folder[0]+"','"+folder[1]+"','"+folder[2]+"','"+folder[3]+"','"
					+folder[4]+"','"+folder[5]+"','"+folder[6]+"','"+folder[7]+"','"+folder[8]+"','"+folder[9]+"');GetFolderLocalUserMember('"+folder[0]+"');GetFolderLocalGroupMember('"+folder[0]+"');GetFolderDomainUserMember('"+folder[0]+"');GetFolderDomainGroupMember('"+folder[0]+"');GetDomainType();\">"+folder[0]+"</a></td>"
                           		+"<td class='otherCol_420' style='width:450px;'>"+folder[1]+"&nbsp;</td></tr>";
                           		
		}
		}
	//}
	
	folder_table_entry = "<table width='650' border='0' cellspacing='0' cellpadding='0'>"
											 +folder_table_entry
											 +"</table>";	
	
	
	
	document.getElementById('FolderList').innerHTML = folder_table_entry;

	
}

function ShowFolderEdit(folder_name,folder_desc,folder_path,folder_attr,folder_recycle,folder_win,folder_atalk,folder_ftp,folder_webdav,folder_acl)
{
	showTable('idTable_FolderEdit_LocalUser');
//	//debug(folder_name+":"+folder_desc+":"+folder_path+":"+folder_attr+":"+folder_recycle+":"+folder_win+":"+folder_atalk+":"+folder_ftp+":"+folder_acl);
	
	var folder_volume = folder_path.split('/');
	
	
	document.getElementById('txtFolderNameEditLocalUser').innerHTML = folder_name;
	document.getElementById('txtFolderNameEditLocalGroup').innerHTML = folder_name;
	document.getElementById('txtFolderNameEditDomainUser').innerHTML = folder_name;
	document.getElementById('txtFolderNameEditDomainGroup').innerHTML = folder_name;


	document.getElementById('txtFolderDescEditLocalUser').value = folder_desc;
	document.getElementById('txtFolderDescEditLocalGroup').value = folder_desc;
	document.getElementById('txtFolderDescEditDomainUser').value = folder_desc;
	document.getElementById('txtFolderDescEditDomainGroup').value = folder_desc;



	document.getElementById('txtFolderVolumeEditLocalUser').innerHTML = folder_volume[3];
	document.getElementById('txtFolderVolumeEditLocalGroup').innerHTML = folder_volume[3];
	document.getElementById('txtFolderVolumeEditDomainUser').innerHTML = folder_volume[3];
	document.getElementById('txtFolderVolumeEditDomainGroup').innerHTML = folder_volume[3];

	
	
	if(folder_attr =='NORMAL'){
		document.getElementById('rdoFolderAttrEditLocalUser_normal').checked = true;
		document.getElementById('rdoFolderAttrEditLocalGroup_normal').checked = true;
		document.getElementById('rdoFolderAttrEditDomainUser_normal').checked = true;
		document.getElementById('rdoFolderAttrEditDomainGroup_normal').checked = true;
		Check_box('normal_edit');
		}
		else {
		document.getElementById('rdoFolderAttrEditLocalUser_hidden').checked = true;
		document.getElementById('rdoFolderAttrEditLocalGroup_hidden').checked = true;
		document.getElementById('rdoFolderAttrEditDomainUser_hidden').checked = true;
		document.getElementById('rdoFolderAttrEditDomainGroup_hidden').checked = true;
		Check_box('hidden_edit');
		}
	


	if(folder_recycle == 'YES'){
		document.getElementById('rdoFolderRecyleEditLocalUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleEditLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderRecyleEditDomainUser_enable').checked = true;
		document.getElementById('rdoFolderRecyleEditDomainGroup_enable').checked = true;

		}
		else {
		document.getElementById('rdoFolderRecyleEditLocalUser_disable').checked = true;
		document.getElementById('rdoFolderRecyleEditLocalGroup_disable').checked = true;
		document.getElementById('rdoFolderRecyleEditDomainUser_disable').checked = true;
		document.getElementById('rdoFolderRecyleEditDomainGroup_disable').checked = true;


		}
	if(folder_win == 'YES'){
		document.getElementById('chkFolderWinEditLocalUser').checked = true;
		document.getElementById('chkFolderWinEditLocalGroup').checked = true;
		document.getElementById('chkFolderWinEditDomainUser').checked = true;
		document.getElementById('chkFolderWinEditDomainGroup').checked = true;


		}
		else {
		document.getElementById('chkFolderWinEditLocalUser').checked= false;
		document.getElementById('chkFolderWinEditLocalGroup').checked= false;
		document.getElementById('chkFolderWinEditDomainUser').checked= false;
		document.getElementById('chkFolderWinEditDomainGroup').checked= false;

		}



	if(folder_atalk== 'YES') {
		document.getElementById('chkFolderAFPEditLocalUser').checked = true;
		document.getElementById('chkFolderAFPEditLocalGroup').checked = true;
		document.getElementById('chkFolderAFPEditDomainUser').checked = true;
		document.getElementById('chkFolderAFPEditDomainGroup').checked = true;

		}
		else {
		document.getElementById('chkFolderAFPEditLocalUser').checked= false;
		document.getElementById('chkFolderAFPEditLocalGroup').checked= false;
		document.getElementById('chkFolderAFPEditDomainUser').checked= false;
		document.getElementById('chkFolderAFPEditDomainGroup').checked= false;


		}



	if(folder_ftp == 'YES' ) {
		document.getElementById('chkFolderFTPEditLocalUser').checked = true;
		document.getElementById('chkFolderFTPEditLocalGroup').checked = true;
		document.getElementById('chkFolderFTPEditDomainUser').checked = true;
		document.getElementById('chkFolderFTPEditDomainGroup').checked = true;



		}
		else {
		document.getElementById('chkFolderFTPEditLocalUser').checked= false;
		document.getElementById('chkFolderFTPEditLocalGroup').checked= false;
		document.getElementById('chkFolderFTPEditDomainUser').checked= false;
		document.getElementById('chkFolderFTPEditDomainGroup').checked= false;


		}

	if(folder_webdav == 'YES' ) {
		document.getElementById('chkFolderWebdavEditLocalUser').checked = true;
		document.getElementById('chkFolderWebdavEditLocalGroup').checked = true;
		document.getElementById('chkFolderWebdavEditDomainUser').checked = true;
		document.getElementById('chkFolderWebdavEditDomainGroup').checked = true;

		//JUNY
		document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true; 
		document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true; 
		document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true; 
		document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true; 

		document.getElementById('rdoFolderACLEditLocalUser_enable').checked = false; 
		document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = false; 
		document.getElementById('rdoFolderACLEditDomainUser_enable').checked = false; 
		document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = false; 

		document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = true;
		document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = true;
		document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = true;
		document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = true;
		//~JUNY

		}
		else {
		document.getElementById('chkFolderWebdavEditLocalUser').checked= false;
		document.getElementById('chkFolderWebdavEditLocalGroup').checked= false;
		document.getElementById('chkFolderWebdavEditDomainUser').checked= false;
		document.getElementById('chkFolderWebdavEditDomainGroup').checked= false;

		//JUNY
		document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = false;
		document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = false;
		document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = false;
		document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = false;
		//~JUNY
		}
		

	if(folder_acl == 'YES') {
		document.getElementById('rdoFolderACLEditLocalUser_enable').checked = true;
		document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = true;
		document.getElementById('rdoFolderACLEditDomainUser_enable').checked = true;
		document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = true;
		//Check_box('acl_on_edit');  //timing problem 

		}
		else {
		
		document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true; 
		document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true; 
		document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true; 
		document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true; 
		//Check_box('acl_off_edit');  //timing problem 
		}
		
	return true;
	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function GetFolderLocalUserMember(folder_name)
{

	var mode;
		
	var _txText =	'&folder='+folder_name
			+"&txtMode="+"LocalUserMember";
	
	sendRequest(onLoadLocalUserMember,_txText,'post',gPhp[0],true,true);
		
	return true;
	
}
function onLoadLocalUserMember(oj)
{
	gLocal_User_Member_List = decodeURIComponent(oj.responseText);
	////debug(gLocal_User_Member_List);

	GetFolderLocalUserList();
}

function GetFolderLocalUserList()
{

	var _txText =	"&mode="+"FullList";
	
	sendRequest(onLoadLocalUserFullList,_txText,'post',gPhp[2],true,true);
		
	return true;
	
}
function onLoadLocalUserFullList(oj)
{
	gLocal_User_Full_List = decodeURIComponent(oj.responseText);
	////debug(gLocal_User_Full_List);

	
	var all_users = gLocal_User_Full_List.split(':');
	////debug(all_users[0]);
	all_users = all_users.sort();
	
	var num_all_users = all_users.length;
	gnum_users = num_all_users;
	
	var user = new String();

	var users = gLocal_User_Member_List.split(';');
	var ro_users = users[1].split(':');
	var rw_users = users[0].split(':');	
	
	
	var ro_user = new String();
	var rw_user = new String();

	var num_ro_users = ro_users.length;
	var num_rw_users = rw_users.length;
	
	var ro_check = new String();
	var rw_check = new String();

	var folder_table_entry = new String();

	var i,j;

//	//debug(users[1]+users[0]);

  var LocalUserFullListArr = new Array();
	
	for(i=1;i<num_all_users;i++){
		user = all_users[i].split(';');
		////debug(user[0]);

		ro_check ="";
		rw_check ="";
		
		

		for(j=0;j<num_ro_users;j++){
			ro_user = ro_users[j].split(';');
			if (user[0] == ro_user[0]) ro_check = "checked";

		}

		for(j=0;j<num_rw_users;j++){
			rw_user = rw_users[j].split(';');
			if (user[0] == rw_user[0]) rw_check = "checked";

		}

		LocalUserFullListArr[LocalUserFullListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+user[0]+"</td>"
                           	  +"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWUser"+i+"\" id=\"checkboxEditRWUser"+i+"\" "+rw_check+" value="+user[0]+" onclick=\"rdo_checkbox('checkboxEditRWUser',"+i+");\"></td>"
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditROUser"+i+"\" id=\"checkboxEditROUser"+i+"\" "+ro_check+" value="+user[0]+" onclick=\"rdo_checkbox('checkboxEditROUser',"+i+");\"></td>"
                           		+"</tr>";

	}
	
	var LocalUserFullList = LocalUserFullListArr.join("");
	
	folder_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +LocalUserFullList
											 +"</table>";	
	//alert(folder_table_entry);
	
	document.getElementById('Permission_Edit_Local_User').innerHTML = folder_table_entry;

	if(document.getElementById('rdoFolderACLEditLocalUser_disable').checked ==true){
		Check_box('acl_off_edit');
		
	}

	
}








////////////////////////////////////////////////////////////////////////////


function GetFolderLocalGroupMember(folder_name)
{

	var mode;
		
	var _txText =	'&folder='+folder_name
			+"&txtMode="+"LocalGroupMember";
	
	sendRequest(onLoadLocalGroupMember,_txText,'post',gPhp[0],true,true);
		
	return true;
	
}
function onLoadLocalGroupMember(oj)
{
	gLocal_Group_Member_List = decodeURIComponent(oj.responseText);
	//debug(gLocal_Group_Member_List);

	GetFolderLocalGroupList();
}

function GetFolderLocalGroupList()
{

	sendRequest(onLoadLocalGroupFullList,'','post',gPhp[3],true,true);
		
	return true;
	
}
function onLoadLocalGroupFullList(oj)
{
	gLocal_Group_Full_List = decodeURIComponent(oj.responseText);
	//debug(gLocal_Group_Full_List);

	
	var all_groups = gLocal_Group_Full_List.split(':');
	////debug(all_users[0]);
	all_groups = all_groups.sort();
	
	var num_all_groups = all_groups.length;
	gnum_groups = num_all_groups;
	
	var group = new String();

	var groups = gLocal_Group_Member_List.split(';');
	var ro_groups = groups[1].split(':');
	var rw_groups = groups[0].split(':');	
	
	
	var ro_group = new String();
	var rw_group = new String();

	var num_ro_groups = ro_groups.length;
	var num_rw_groups = rw_groups.length;
	
	var ro_check = new String();
	var rw_check = new String();

	var folder_table_entry = new String();

	var i,j;

//	//debug(users[1]+users[0]);

  var LocalGroupFullListArr = new Array();
	
	for(i=1;i<num_all_groups;i++){
		group = all_groups[i].split(';');
		////debug(group[0]);

		ro_check ="";
		rw_check ="";
		
		

		for(j=0;j<num_ro_groups;j++){
			ro_group = ro_groups[j].split(';');
			if (group[0] == ro_group[0]) ro_check = "checked";
			////debug("rogr"+ro_group[0]);

		}

		for(j=0;j<num_rw_groups;j++){
			rw_group = rw_groups[j].split(';');
			if (group[0] == rw_group[0]) rw_check = "checked";
			////debug("rwgr"+rw_group[0]);
		}

		LocalGroupFullListArr[LocalGroupFullListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+group[0]+"</td>"
                          		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWGroup"+i+"\" id=\"checkboxEditRWGroup"+i+"\" "+rw_check+" value="+group[0]+" onclick=\"rdo_checkbox('checkboxEditRWGroup',"+i+");\"></td>"
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditROGroup"+i+"\" id=\"checkboxEditROGroup"+i+"\" "+ro_check+" value="+group[0]+" onclick=\"rdo_checkbox('checkboxEditROGroup',"+i+");\"></td>"
                           		+"</tr>";
    /*                       		
		folder_table_entry=	folder_table_entry
					
                           		+"<tr><td class='firstCol_250' style='width:200px'>"+group[0]+"</td>"
                          		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWGroup"+i+"\" id=\"checkboxEditRWGroup"+i+"\" "+rw_check+" value="+group[0]+" onclick=\"rdo_checkbox('checkboxEditRWGroup',"+i+");\"></td>"
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditROGroup"+i+"\" id=\"checkboxEditROGroup"+i+"\" "+ro_check+" value="+group[0]+" onclick=\"rdo_checkbox('checkboxEditROGroup',"+i+");\"></td>"
                           		+"</tr>";
		*/		

	}
	
	var LocalGroupFullList = LocalGroupFullListArr.join("");
	
	folder_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +LocalGroupFullList
											 +"</table>";	
	
	
	
	document.getElementById('Permission_Edit_Local_Group').innerHTML = folder_table_entry;
	if(document.getElementById('rdoFolderACLEditLocalUser_disable').checked ==true){
		Check_box('acl_off_edit');
		
	}
	
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function GetFolderDomainUserMember(folder_name)
{

	var mode;
		
	var _txText =	'&folder='+folder_name
			+"&txtMode="+"DomainUserMember";
	
	document.getElementById('Permission_Edit_Domain_User').innerHTML = "Loading..."
	
	sendRequest(onLoadDomainUserMember,_txText,'post',gPhp[0],true,true);
		
	return true;
	
}
function onLoadDomainUserMember(oj)
{
	//gDomain_User_Member_List = decodeURIComponent(oj.responseText);
	gDomain_User_Member_List=oj.responseText;
	
	//alert("domain user member: "+gDomain_User_Member_List);

	GetFolderDomainUserList();
}

function GetFolderDomainUserList()
{

	var _txText =	"&txtMode="+"GetDomainUser";
	
	sendRequest(onLoadDomainUserFullList,_txText,'post',gPhp[0],true,true);
		
	return true;
	
}
function onLoadDomainUserFullList(oj)
{
	//gDomain_User_Full_List = decodeURIComponent(oj.responseText);
	gDomain_User_Full_List = oj.responseText;

	
	////debug(gLocal_User_Full_List);
	//alert("domain userfull : "+gDomain_User_Full_List);


	
	var all_users = gDomain_User_Full_List.split(';');
	////debug(all_users[0]);
	all_users = all_users.sort();
	
	var num_all_users = all_users.length;
	gnum_Domainusers = num_all_users;
	
	var user = new String();

	var users = gDomain_User_Member_List.split(';');
	var ro_users = users[1].split(':');
	var rw_users = users[0].split(':');	

  gfolder_rw_DomainUsers = rw_users.join(';');
  gfolder_ro_DomainUsers = ro_users.join(';');
  
	//alert(rw_users[1]);
	
	
	var ro_user = new String();
	var rw_user = new String();

	var num_ro_users = ro_users.length;
	var num_rw_users = rw_users.length;
	
	var ro_check = new String();
	var rw_check = new String();

	var folder_table_entry = new String();

	var i,j;

       
  var FullDomainUserListArr = new Array();
	
	for(i=1;i<num_all_users;i++){
		user = all_users[i].split(';');
		//alert(user[0]);

		ro_check ="";
		rw_check ="";
		
		
		
		for(j=0;j<num_ro_users;j++){
		//	ro_user = ro_users[j].split(';');
		//	alert(ro_users[j]);
			if (user[0] == ro_users[j]) ro_check = "checked";

		}

		for(j=0;j<num_rw_users;j++){
			//rw_user = rw_users[j].split(';');
			//alert(user[0]+":"+rw_users[j].replace(/\\/,''));
			if (user[0] == rw_users[j]) rw_check = "checked";

		}

		FullDomainUserListArr[FullDomainUserListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+user[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWDomainUser"+i+"\" id=\"checkboxEditRWDomainUser"+i+"\" "+rw_check+" value="+user[0]+" onclick=\"rdo_checkbox('checkboxEditRWDomainUser',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRODomainUser"+i+"\" id=\"checkboxEditRODomainUser"+i+"\" "+ro_check+" value="+user[0]+" onclick=\"rdo_checkbox('checkboxEditRODomainUser',"+i+");\"></td>"
                           		+"</tr>";
	/*	
		folder_table_entry=	folder_table_entry
					
                           		+"<tr><td class='firstCol_250' style='width:200px'>"+user[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWDomainUser"+i+"\" id=\"checkboxEditRWDomainUser"+i+"\" "+rw_check+" value="+user[0]+" onclick=\"rdo_checkbox('checkboxEditRWDomainUser',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRODomainUser"+i+"\" id=\"checkboxEditRODomainUser"+i+"\" "+ro_check+" value="+user[0]+" onclick=\"rdo_checkbox('checkboxEditRODomainUser',"+i+");\"></td>"
                           		+"</tr>";
	*/
	}
	var FullDomainUserList = FullDomainUserListArr.join("");
	
	folder_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +FullDomainUserList
											 +"</table>";	
	
	//alert(folder_table_entry);
	document.getElementById('Permission_Edit_Domain_User').innerHTML = folder_table_entry;

	if(document.getElementById('rdoFolderACLEditLocalUser_disable').checked ==true){
		Check_box('acl_off_edit');
		
	}

	
}








////////////////////////////////////////////////////////////////////////////


function GetFolderDomainGroupMember(folder_name)
{

			
	var _txText =	'&folder='+folder_name
			+"&txtMode="+"DomainGroupMember";
			
	document.getElementById('Permission_Edit_Domain_Group').innerHTML = "Loading...";
	
	sendRequest(onLoadDomainGroupMember,_txText,'post',gPhp[0],true,true);
	
		
	return true;
	
}
function onLoadDomainGroupMember(oj)
{
	//gDomain_Group_Member_List = decodeURIComponent(oj.responseText);
	gDomain_Group_Member_List = oj.responseText;
	//alert(gDomain_Group_Member_List);

	GetFolderDomainGroupList();
}

function GetFolderDomainGroupList()
{

	var _txText =	"&txtMode="+"GetDomainGroup";
	
	sendRequest(onLoadDomainGroupFullList,_txText,'post',gPhp[0],true,true);
		
		
	return true;
	
}
function onLoadDomainGroupFullList(oj)
{
	//gDomain_Group_Full_List = decodeURIComponent(oj.responseText);
	gDomain_Group_Full_List = oj.responseText;	
	//alert(gDomain_Group_Full_List);

	
	var all_groups = gDomain_Group_Full_List.split(';');
	////debug(all_users[0]);
	all_groups = all_groups.sort();
	
	var num_all_groups = all_groups.length;
	gnum_Domaingroups = num_all_groups;
	
	var group = new String();

	var groups = gDomain_Group_Member_List.split(';');
	var ro_groups = groups[1].split(':');
	var rw_groups = groups[0].split(':');	
	
	gfolder_rw_DomainGroups = rw_groups.join(';').replace(/\s+/g,'\*');
  gfolder_ro_DomainGroups = ro_groups.join(';').replace(/\s+/g,'\*');
	
	var ro_group = new String();
	var rw_group = new String();

	var num_ro_groups = ro_groups.length;
	var num_rw_groups = rw_groups.length;
	
	var ro_check = new String();
	var rw_check = new String();

	var folder_table_entry = new String();

	var i,j;

//	//debug(users[1]+users[0]);

  var FullDomainGroupListArr = new Array();
	
	for(i=1;i<num_all_groups;i++){
		group = all_groups[i].split(';');
		//alert(group[0]);

		ro_check ="";
		rw_check ="";
		
		

		for(j=0;j<num_ro_groups;j++){
			//alert(group[0]+":"+ro_groups[j].replace(/\\/,''));
			if (group[0] == ro_groups[j]) ro_check = "checked";
			////debug("rogr"+ro_group[0]);

		}

		for(j=0;j<num_rw_groups;j++){
			//alert(group[0]+":"+rw_groups[j].replace(/\\/,''));
			if (group[0] == rw_groups[j]) rw_check = "checked";
			////debug("rwgr"+rw_group[0]);
		}

    FullDomainGroupListArr[FullDomainGroupListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+group[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWDomainGroup"+i+"\" id=\"checkboxEditRWDomainGroup"+i+"\" "+rw_check+" value="+group[0].replace(/\s+/g,'*')+" onclick=\"rdo_checkbox('checkboxEditRWDomainGroup',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRODomainGroup"+i+"\" id=\"checkboxEditRODomainGroup"+i+"\" "+ro_check+" value="+group[0].replace(/\s+/g,'*')+" onclick=\"rdo_checkbox('checkboxEditRODomainGroup',"+i+");\"></td>"
                           		+"</tr>";
		/*
		folder_table_entry=	folder_table_entry
					
                           		+"<tr><td class='firstCol_250' style='width:200px'>"+group[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRWDomainGroup"+i+"\" id=\"checkboxEditRWDomainGroup"+i+"\" "+rw_check+" value="+group[0]+" onclick=\"rdo_checkbox('checkboxEditRWDomainGroup',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxEditRODomainGroup"+i+"\" id=\"checkboxEditRODomainGroup"+i+"\" "+ro_check+" value="+group[0]+" onclick=\"rdo_checkbox('checkboxEditRODomainGroup',"+i+");\"></td>"
                           		+"</tr>";
		*/			

	}
	
	var FullDomainGroupList = FullDomainGroupListArr.join("");
	
	folder_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +FullDomainGroupList
											 +"</table>";	
	
	
	
	document.getElementById('Permission_Edit_Domain_Group').innerHTML = folder_table_entry;

	if(document.getElementById('rdoFolderACLEditLocalUser_disable').checked ==true){
		Check_box('acl_off_edit');
		
	}
}









////////////////////////////////////////////////////////////////////////////

function GetMaxVolume()
{
	var _txText =	'&txtMode='+'GetMaxVolume';
	
	sendRequest(onLoadMaxVolume,_txText,'post',gPhp[0],true,true);
		
	return true;
}

function onLoadMaxVolume(oj)
{
	var res = decodeURIComponent(oj.responseText);
	gVolume_info = res.split( ' ' );
	Max_Volume = parseInt(gVolume_info[0]);
	gMax_Volume = Max_Volume;

	var Volume = new String;
	for(var i=1 ; i<Max_Volume+1 ;i++) {
		// NS1
		//Volume = Volume + "<option value=\"Vol"+i+"\">Vol"+i+"</option>";

		// NC1
		Volume = Volume + "<option value=\""+gVolume_info[i]+"\">"+gVolume_info[i]+"</option>";
	}
	Volume = "<select name=\"VolumeUser\" class=\"SELECT\" id=\"VolLocalUser\">"+Volume+"</select>"
	
	document.getElementById('VolumeSelectUser').innerHTML = Volume;
	
	
	Volume = '';
	for(var i=1 ; i<Max_Volume+1 ;i++) {
		// NS1		
		//Volume = Volume + "<option value=\"Vol"+i+"\">Vol"+i+"</option>";

		// NC1
		Volume = Volume + "<option value=\""+gVolume_info[i]+"\">"+gVolume_info[i]+"</option>";
	}
	Volume = "<select name=\"VolumeGroup\" class=\"SELECT\" id=\"VolLocalGroup\">"+Volume+"</select>"

	document.getElementById('VolumeSelectGroup').innerHTML = Volume;

	Volume = '';
	for(var i=1 ; i<Max_Volume+1 ;i++) {
		// NS1		
		//Volume = Volume + "<option value=\"Vol"+i+"\">Vol"+i+"</option>";

		// NC1
		Volume = Volume + "<option value=\""+gVolume_info[i]+"\">"+gVolume_info[i]+"</option>";
	}
	Volume = "<select name=\"VolumeDomainUser\" class=\"SELECT\" id=\"VolDomainUser\">"+Volume+"</select>"
	
	document.getElementById('VolumeSelectDomainUser').innerHTML = Volume;
	
	
	Volume = '';
	for(var i=1 ; i<Max_Volume+1 ;i++) {
		// NS1		
		//Volume = Volume + "<option value=\"Vol"+i+"\">Vol"+i+"</option>";

		// NC1
		Volume = Volume + "<option value=\""+gVolume_info[i]+"\">"+gVolume_info[i]+"</option>";
	}
	Volume = "<select name=\"VolumeDomainGroup\" class=\"SELECT\" id=\"VolDomainGroup\">"+Volume+"</select>"

	document.getElementById('VolumeSelectDomainGroup').innerHTML = Volume;
}


function GetDomainType()
{
	var _txText =	'&txtMode='+'Domain';
	
	sendRequest(onLoadDomainType,_txText,'post',gPhp[0],true,true);
		
	return true;
}

function onLoadDomainType(oj)
{
	var res = decodeURIComponent(oj.responseText);
	domain_info = res.split(';');
	gDomain = domain_info[0];
	gDomain_name = domain_info[1];
	//res = 'domain';
	if(gDomain == 'workgroup'){
		document.getElementById('Edit_LocalGroup_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Edit_LocalGroup_Domain_group_tab_01').style.display 	= "none";
		document.getElementById('Create_LocalGroup_Domain_group_tab_01').style.display 	= "none";
		document.getElementById('Create_LocalGroup_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Edit_LocalUser_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Edit_LocalUser_Domain_group_tab_01').style.display 	= "none";
		document.getElementById('Create_LocalUser_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Create_LocalUser_Domain_group_tab_01').style.display 	= "none";
		document.getElementById('Edit_DomainGroup_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Edit_DomainGroup_Domain_group_tab_01').style.display 	= "none";
		document.getElementById('Create_DomainGroup_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Create_DomainGroup_Domain_group_tab_01').style.display = "none";
		document.getElementById('Edit_DomainUser_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Edit_DomainUser_Domain_group_tab_01').style.display	= "none";
		document.getElementById('Create_DomainUser_Domain_user_tab_01').style.display 	= "none";
		document.getElementById('Create_DomainUser_Domain_group_tab_01').style.display 	= "none";

	

	} else {
/*
		document.getElementById('Edit_LocalGroup_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Edit_LocalGroup_Domain_group_tab_01').style.display 	= "block";
		document.getElementById('Create_LocalGroup_Domain_group_tab_01').style.display 	= "block";
		document.getElementById('Create_LocalGroup_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Edit_LocalUser_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Edit_LocalUser_Domain_group_tab_01').style.display 	= "block";
		document.getElementById('Create_LocalUser_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Create_LocalUser_Domain_group_tab_01').style.display 	= "block";
		document.getElementById('Edit_DomainGroup_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Edit_DomainGroup_Domain_group_tab_01').style.display 	= "block";
		document.getElementById('Create_DomainGroup_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Create_DomainGroup_Domain_group_tab_01').style.display = "block";
		document.getElementById('Edit_DomainUser_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Edit_DomainUser_Domain_group_tab_01').style.display	= "block";
		document.getElementById('Create_DomainUser_Domain_user_tab_01').style.display 	= "block";
		document.getElementById('Create_DomainUser_Domain_group_tab_01').style.display 	= "block";
*/
	}
}




function GetLocalUserList()
{
	
	showTable('idTable_FolderCreate_LocalUser');	

	var _txText =	"&mode="+"FullList";
	
	sendRequest(onLoadFullUserList,_txText,'post',gPhp[2],true,true);
		
	return true;
	
}
function onLoadFullUserList(oj)
{
	//var Full_User_List = decodeURIComponent(oj.responseText);
	var Full_User_List = oj.responseText;

	////debug("userlist "+res);

	var users = Full_User_List.split(':');
	users=users.sort();	

	var num_users = users.length;
	gnum_users = num_users;

	var user = new String();

	var user_table_entry = new String();

	var i,j;

	var checked = new String();
	
	var FullUserListArr = new Array();
	
	for(i=1;i<num_users;i++){
		user = users[i].split(';');
	//	//debug(user);
		checked = '';
		if(user[0] == 'admin') {
			checked = 'checked';
		}
		
		FullUserListArr[FullUserListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+user[0]+"</td>"
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRWUser"+i+"\" id=\"checkboxRWUser"+i+"\" value="+user[0]+" "+checked+"   onclick=\"rdo_checkbox('checkboxRWUser',"+i+");\"></td>"
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxROUser"+i+"\" id=\"checkboxROUser"+i+"\" value="+user[0]+" onclick=\"rdo_checkbox('checkboxROUser',"+i+");\"></td>"
                           		+"</tr>";
		/*
		user_table_entry	=	user_table_entry
                           		+"<tr><td class='firstCol_250' style='width:200px'>"+user[0]+"</td>"
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRWUser"+i+"\" id=\"checkboxRWUser"+i+"\" value="+user[0]+" "+checked+"   onclick=\"rdo_checkbox('checkboxRWUser',"+i+");\"></td>"
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxROUser"+i+"\" id=\"checkboxROUser"+i+"\" value="+user[0]+" onclick=\"rdo_checkbox('checkboxROUser',"+i+");\"></td>"
                           		+"</tr>";
		*/				
		////debug(user_table_entry);
	}
	var FullUserList = FullUserListArr.join("");

	user_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +FullUserList
											 +"</table>";	
	
	
	
	document.getElementById('Permission_Local_User').innerHTML = user_table_entry;

	
}


function GetLocalGroupList()
{

	sendRequest(onLoadFullGroupList,'','post',gPhp[3],true,true);
	return true;
}

function onLoadFullGroupList(oj)
{
	//var Full_Group_List = decodeURIComponent(oj.responseText);
	 Full_Group_List = oj.responseText;
	 
//	//debug(res);
	var groups = Full_Group_List.split(':');
	groups=groups.sort();
	
	var num_groups = groups.length;
	gnum_groups = num_groups;
	
	var group = new String();
	
	var group_table_entry = new String();

	var i,j;
	
	var FullGroupListArr = new Array();
	
	for(i=1;i<num_groups;i++){
		group = groups[i].split(';');
	//	//debug(user);
		
		FullGroupListArr[FullGroupListArr.length] = "<tr><td class='firstCol_250' style='width:200px;'>"+group[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRWGroup"+i+"\" id=\"checkboxRWGroup"+i+"\" value="+group[0]+" onclick=\"rdo_checkbox('checkboxRWGroup',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px;'>"
                           		+"<input type=\"checkbox\" name=\"checkboxROGroup"+i+"\" id=\"checkboxROGroup"+i+"\" value="+group[0]+" onclick=\"rdo_checkbox('checkboxROGroup',"+i+");\"></td>"
                           		+"</tr>";
		/*
		group_table_entry	=	group_table_entry
				
                           		+"<tr><td class='firstCol_250' style='width:200px;'>"+group[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRWGroup"+i+"\" id=\"checkboxRWGroup"+i+"\" value="+group[0]+" onclick=\"rdo_checkbox('checkboxRWGroup',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px;'>"
                           		+"<input type=\"checkbox\" name=\"checkboxROGroup"+i+"\" id=\"checkboxROGroup"+i+"\" value="+group[0]+" onclick=\"rdo_checkbox('checkboxROGroup',"+i+");\"></td>"
                           		+"</tr>";
			*/			
		////debug(user_table_entry);
	}
	var FullGroupList = FullGroupListArr.join("");
	
	group_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +FullGroupList
											 +"</table>";	
	
	
	
	document.getElementById('Permission_Local_Group').innerHTML = group_table_entry;

	
	
	

}



function GetDomainUserList()
{
	
	//showTable('idTable_FolderCreate_LocalUser');	

	var _txText =	"&txtMode="+"GetDomainUser";
	
	document.getElementById('Permission_Domain_User').innerHTML = "Loading...";
	
	sendRequest(onLoadFullDomainUserList,_txText,'post',gPhp[0],true,true);
		
	return true;
	
}
function onLoadFullDomainUserList(oj)
{
	//var Full_User_List = decodeURIComponent(oj.responseText);
	var Full_User_List = oj.responseText;
	
	//alert("userlist "+Full_User_List);

	var users = Full_User_List.split(';');
	users=users.sort();	

	var num_users = users.length;
	gnum_Domainusers = num_users;

	var user = new String();

	var user_table_entry = new String();

	var i,j;
	
	var FullDomainUserListArr = new Array();
	
	for(i=1;i<num_users;i++){
		user = users[i].split(';');
	
		FullDomainUserListArr[FullDomainUserListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+user[0]+"</td>"
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRWDomainUser"+i+"\" id=\"checkboxRWDomainUser"+i+"\" value="+user[0]+" onclick=\"rdo_checkbox('checkboxRWDomainUser',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRODomainUser"+i+"\" id=\"checkboxRODomainUser"+i+"\" value="+user[0]+" onclick=\"rdo_checkbox('checkboxRODomainUser',"+i+");\"></td>"
                           		+"</tr>";
					
						
		////debug(user_table_entry);
	}
	
	var FullDomainUserList = FullDomainUserListArr.join("");//alert(test);//return;
	user_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +FullDomainUserList
											 +"</table>";	
	
	
	
	document.getElementById('Permission_Domain_User').innerHTML = user_table_entry;

	
}


function GetDomainGroupList()
{
	
	//showTable('idTable_FolderCreate_LocalUser');	

	var _txText =	"&txtMode="+"GetDomainGroup";
	
	document.getElementById('Permission_Domain_Group').innerHTML = "Loading...";
	
	sendRequest(onLoadFullDomainGroupList,_txText,'post',gPhp[0],true,true);
		
	return true;
	
}
function onLoadFullDomainGroupList(oj)
{
	//var Full_Group_List = decodeURIComponent(oj.responseText);
	var Full_Group_List = oj.responseText;
	
	//alert(Full_Group_List);
	var groups = Full_Group_List.split(';');
	groups=groups.sort();
	
	var num_groups = groups.length;
	gnum_Domaingroups = num_groups;
	
	var group = new String();
	
	var group_table_entry = new String();

	var i,j;
	
	var valid_group = new String();
	
	var FullDomainGroupListArr = new Array();
	
	for(i=1;i<num_groups;i++){
		group = groups[i].split(';');

	  FullDomainGroupListArr[FullDomainGroupListArr.length] = "<tr><td class='firstCol_250' style='width:200px'>"+group[0]+"</td>"
                          		
                           		+"<td class='otherCol_420' style='width:200px;border-right:1px solid #e3e3e5'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRWDomainGroup"+i+"\" id=\"checkboxRWDomainGroup"+i+"\" "+valid_group+" value="+group[0].replace(/\s+/g,'*')+" onclick=\"rdo_checkbox('checkboxRWDomainGroup',"+i+");\"></td>"
                           		
                           		+"<td class='otherCol_420' style='width:210px'>"
                           		+"<input type=\"checkbox\" name=\"checkboxRODomainGroup"+i+"\" id=\"checkboxRODomainGroup"+i+"\" "+valid_group+" value="+group[0].replace(/\s+/g,'*')+" onclick=\"rdo_checkbox('checkboxRODomainGroup',"+i+");\"></td>"
                           		+"</tr>";
					
						
		////debug(user_table_entry);
	}
	var FullDomainGroupList = FullDomainGroupListArr.join("");
	
	group_table_entry = "<table width='610px' border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +FullDomainGroupList
											 +"</table>";	
	
	
	
	document.getElementById('Permission_Domain_Group').innerHTML = group_table_entry;

	
	
}










function createFolder()
{

	//showTable('idTable_Group_Create');	
	var folderName = document.getElementById('txtFolderNameLocalUser').value;
	var folderDesc = document.getElementById('txtFolderDescLocalUser').value;
	
	var folderVol = new String; 
	
	//debug(gMax_Volume);

	var j = 0;
	for(var i=0;i<gMax_Volume;i++){
		j = i+1;
		if (document.getElementById('VolLocalUser').options[i].selected) {
			// NS1
			//folderVol ='Vol'+j.toString();

			// NC1
			folderVol = gVolume_info[i + 1];			
			break;
		}
	}
	
	var chkOSWin = new String; 
		if(document.getElementById('chkFolderWinLocalUser').checked == true) chkOSWin = 'win';

	var chkOSMac = new String;
		if(document.getElementById('chkFolderAFPLocalUser').checked == true) chkOSMac = 'mac';
	
	var chkOSFTP = new String;
		if(document.getElementById('chkFolderFTPLocalUser').checked == true) chkOSFTP = 'ftp';

	var chkOSWebdav = new String;
		if(document.getElementById('chkFolderWebdavLocalUser').checked == true) chkOSWebdav = 'webdav';

	var folderAttr	= new String;
		if(document.getElementById('rdoFolderAttrLocalUser_normal').checked == true) folderAttr = 'on';
			else folderAttr = 'off';
	
	var folderRecyle = new String;
		if(document.getElementById('rdoFolderRecyleLocalUser_enable').checked == true) folderRecyle = 'on';
			else folderRecyle = 'off';		

	var folderAccess = new String; 
		if(document.getElementById('rdoFolderACLLocalUser_enable').checked == true) folderAccess = 'on';
			else folderAccess = 'off';
	

	var folder_rw_localUsers = new String();
	var folder_ro_localUsers = new String();

	var folder_rw_localGroups = new String();
	var folder_ro_localGroups = new String();

	var folder_rw_DomainUsers = new String();
	var folder_ro_DomainUsers = new String();

	var folder_rw_DomainGroups = new String();
	var folder_ro_DomainGroups = new String();

		
	//var test = document.getElementById('checkboxRO1').value;
	////debug(test);
	
	for(var i=1;i<gnum_users;i++){
		if(document.getElementById('checkboxRWUser'+i.toString()).checked) folder_rw_localUsers = document.getElementById('checkboxRWUser'+i.toString()).value+";"+ folder_rw_localUsers; 
		
		if(document.getElementById('checkboxROUser'+i.toString()).checked) folder_ro_localUsers = document.getElementById('checkboxROUser'+i.toString()).value+";"+ folder_ro_localUsers; 
		
	}

	for(var i=1;i<gnum_groups;i++){
		if(document.getElementById('checkboxRWGroup'+i.toString()).checked) folder_rw_localGroups = document.getElementById('checkboxRWGroup'+i.toString()).value+";"+ folder_rw_localGroups; 
		
		if(document.getElementById('checkboxROGroup'+i.toString()).checked) folder_ro_localGroups = document.getElementById('checkboxROGroup'+i.toString()).value+";"+ folder_ro_localGroups; 
		
	}

/*
	for(var i=1;i<gnum_Domainusers;i++){
		if(document.getElementById('checkboxRWDomainUser'+i.toString()).checked) folder_rw_DomainUsers = document.getElementById('checkboxRWDomainUser'+i.toString()).value+";"+ folder_rw_DomainUsers; 
		
		if(document.getElementById('checkboxRODomainUser'+i.toString()).checked) folder_ro_DomainUsers = document.getElementById('checkboxRODomainUser'+i.toString()).value+";"+ folder_ro_DomainUsers; 
		
	}

	for(var i=1;i<gnum_Domaingroups;i++){
		if(document.getElementById('checkboxRWDomainGroup'+i.toString()).checked) folder_rw_DomainGroups = document.getElementById('checkboxRWDomainGroup'+i.toString()).value+";"+ folder_rw_DomainGroups; 
		
		if(document.getElementById('checkboxRODomainGroup'+i.toString()).checked) folder_ro_DomainGroups = document.getElementById('checkboxRODomainGroup'+i.toString()).value+";"+ folder_ro_DomainGroups; 
		
	}
*/

	//alert("name:"+folderName+" desc:"+folderDesc+" vol:"+folderVol+" win:"+chkOSWin+" mac:"+chkOSMac+" ftp:"+chkOSFTP+" attr:"+folderAttr+" rec:"+folderRecyle+" acl:"+folderAccess+" rwuser:"+folder_rw_localUsers+" rouser:"+folder_ro_localUsers+" rwgroup:"+folder_rw_localGroups+" rogroup:"+folder_ro_localGroups+" rwdomainuser:"+folder_rw_DomainUsers+" roDomainuser:"+folder_ro_DomainUsers+" rwdomaingroup:"+folder_rw_DomainGroups+" rodomaingroup:"+folder_ro_DomainGroups);
	
	gfolder_rw_DomainGroups = gfolder_rw_DomainGroups.replace(/\*+/g,' ')
	gfolder_ro_DomainGroups = gfolder_ro_DomainGroups.replace(/\*+/g,' ')
	
	var _txText =	'&txtName='+folderName
			+"&txtComment="+folderDesc
			+"&txtVolume="+folderVol
			+"&chkOSWin="+chkOSWin
			+"&chkOSMac="+chkOSMac
			+"&chkOSFTP="+chkOSFTP
			+"&chkOSWebdav="+chkOSWebdav
			+"&rdoAttrib="+folderAttr
			+"&rdoTrash="+folderRecyle
			+"&rdoAccess="+folderAccess
			+"&txtNum_user="+gnum_users
			+"&chkUserRWPermission="+folder_rw_localUsers
			+"&chkUserROPermission="+folder_ro_localUsers
			+"&txtNum_group="+gnum_groups
			+"&chkGroupRWPermission="+folder_rw_localGroups
			+"&chkGroupROPermission="+folder_ro_localGroups
			+"&txtNum_Domainuser="+gnum_Domainusers
			+"&chkDomainUserRWPermission="+encodeURIComponent(gfolder_rw_DomainUsers)
			+"&chkDomainUserROPermission="+encodeURIComponent(gfolder_ro_DomainUsers)
			+"&txtNum_Domaingroup="+gnum_Domaingroups
			+"&chkDomainGroupRWPermission="+encodeURIComponent(gfolder_rw_DomainGroups)
			+"&chkDomainGroupROPermission="+encodeURIComponent(gfolder_ro_DomainGroups)
			+"&txtSetupMode="+'add';
				
	sendRequest(onLoadCreateFolder,_txText,'post',gPhp[1],true,true);
		gfolder_rw_DomainUsers ="";
		gfolder_ro_DomainUsers ="";
		gfolder_rw_DomainGroups ="";
		gfolder_ro_DomainGroups ="";
		
	return true;
	
}
function onLoadCreateFolder(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);
	
}

function editFolder()
{

	//showTable('idTable_Group_Create');	
	var folderName = document.getElementById('txtFolderNameEditLocalUser').innerHTML;
	var folderDesc = document.getElementById('txtFolderDescEditLocalUser').value;
	
	var folderVol = document.getElementById('txtFolderVolumeEditLocalUser').innerHTML;
	
	var chkOSWin = new String; 
		if(document.getElementById('chkFolderWinEditLocalUser').checked == true) chkOSWin = 'win';

	var chkOSMac = new String;
		if(document.getElementById('chkFolderAFPEditLocalUser').checked == true) chkOSMac = 'mac';
	
	var chkOSFTP = new String;
		if(document.getElementById('chkFolderFTPEditLocalUser').checked == true) chkOSFTP = 'ftp';

	var chkOSWebdav = new String;
		if(document.getElementById('chkFolderWebdavEditLocalUser').checked == true) chkOSWebdav = 'webdav';		
	
	var folderAttr	= new String;
		if(document.getElementById('rdoFolderAttrEditLocalUser_normal').checked == true) folderAttr = 'on';
			else folderAttr = 'off';
	
	var folderRecyle = new String;
		if(document.getElementById('rdoFolderRecyleEditLocalUser_enable').checked == true) folderRecyle = 'on';
			else folderRecyle = 'off';		

	var folderAccess = new String; 
		if(document.getElementById('rdoFolderACLEditLocalUser_enable').checked == true) folderAccess = 'on';
			else folderAccess = 'off';
	

	var folder_rw_localUsers = new String();
	var folder_ro_localUsers = new String();

	var folder_rw_localGroups = new String();
	var folder_ro_localGroups = new String();
		
	var folder_rw_DomainUsers = new String();
	var folder_ro_DomainUsers = new String();

	var folder_rw_DomainGroups = new String();
	var folder_ro_DomainGroups = new String();
	//var test = document.getElementById('checkboxRO1').value;
	//debug(gnum_users);
	
	for(var i=1;i<gnum_users;i++){
		//debug( document.getElementById('checkboxEditRWUser'+i.toString()).value);	
	
		if(document.getElementById('checkboxEditRWUser'+i.toString()).checked) folder_rw_localUsers = document.getElementById('checkboxEditRWUser'+i.toString()).value+";"+ folder_rw_localUsers; 
		
		if(document.getElementById('checkboxEditROUser'+i.toString()).checked) folder_ro_localUsers = document.getElementById('checkboxEditROUser'+i.toString()).value+";"+ folder_ro_localUsers; 
		
	}

	for(var i=1;i<gnum_groups;i++){
		if(document.getElementById('checkboxEditRWGroup'+i.toString()).checked) folder_rw_localGroups = document.getElementById('checkboxEditRWGroup'+i.toString()).value+";"+ folder_rw_localGroups; 
		
		if(document.getElementById('checkboxEditROGroup'+i.toString()).checked) folder_ro_localGroups = document.getElementById('checkboxEditROGroup'+i.toString()).value+";"+ folder_ro_localGroups; 
		
	}

/*
	for(var i=1;i<gnum_Domainusers;i++){
		if(document.getElementById('checkboxEditRWDomainUser'+i.toString()).checked) folder_rw_DomainUsers = document.getElementById('checkboxEditRWDomainUser'+i.toString()).value+";"+ folder_rw_DomainUsers; 
		
		if(document.getElementById('checkboxEditRODomainUser'+i.toString()).checked) folder_ro_DomainUsers = document.getElementById('checkboxEditRODomainUser'+i.toString()).value+";"+ folder_ro_DomainUsers; 
		
	}

	for(var i=1;i<gnum_Domaingroups;i++){
		if(document.getElementById('checkboxEditRWDomainGroup'+i.toString()).checked) folder_rw_DomainGroups = document.getElementById('checkboxEditRWDomainGroup'+i.toString()).value+";"+ folder_rw_DomainGroups; 
		
		if(document.getElementById('checkboxEditRODomainGroup'+i.toString()).checked) folder_ro_DomainGroups = document.getElementById('checkboxEditRODomainGroup'+i.toString()).value+";"+ folder_ro_DomainGroups; 
		
	}
*/
	//alert("name:"+folderName+" desc:"+folderDesc+" vol:"+folderVol+" win:"+chkOSWin+" mac:"+chkOSMac+" ftp:"+chkOSFTP+" attr:"+folderAttr+" rec:"+folderRecyle+" acl:"+folderAccess+" rwuser:"+folder_rw_localUsers+" rouser:"+folder_ro_localUsers+" rwgroup:"+folder_rw_localGroups+" rogroup:"+folder_ro_localGroups+" Drwuser:"+folder_rw_DomainUsers+" Drouser:"+folder_ro_DomainUsers+" Drwgroup:"+folder_rw_DomainGroups+" Drogroup:"+folder_ro_DomainGroups);
	
	gfolder_rw_DomainGroups = gfolder_rw_DomainGroups.replace(/\*+/g,' ')
	gfolder_ro_DomainGroups = gfolder_ro_DomainGroups.replace(/\*+/g,' ')
	
	var _txText =	'&txtName='+folderName
			+"&txtComment="+folderDesc
			+"&txtVolume="+folderVol
			+"&chkOSWin="+chkOSWin
			+"&chkOSMac="+chkOSMac
			+"&chkOSFTP="+chkOSFTP
			+"&chkOSWebdav="+chkOSWebdav
			+"&rdoAttrib="+folderAttr
			+"&rdoTrash="+folderRecyle
			+"&rdoAccess="+folderAccess
			+"&txtNum_user="+gnum_users
			+"&chkUserRWPermission="+folder_rw_localUsers
			+"&chkUserROPermission="+folder_ro_localUsers
			+"&txtNum_group="+gnum_groups
			+"&chkGroupRWPermission="+folder_rw_localGroups
			+"&chkGroupROPermission="+folder_ro_localGroups
			+"&txtNum_Domainuser="+gnum_Domainusers
			+"&chkDomainUserRWPermission="+encodeURIComponent(gfolder_rw_DomainUsers)
			+"&chkDomainUserROPermission="+encodeURIComponent(gfolder_ro_DomainUsers)
			+"&txtNum_Domaingroup="+gnum_Domaingroups
			+"&chkDomainGroupRWPermission="+encodeURIComponent(gfolder_rw_DomainGroups)
			+"&chkDomainGroupROPermission="+encodeURIComponent(gfolder_ro_DomainGroups)
			+"&txtSetupMode="+'edit';
			
	
	sendRequest(onLoadEditFolder,_txText,'post',gPhp[1],true,true);
		gfolder_rw_DomainUsers ="";
		gfolder_ro_DomainUsers ="";
		gfolder_rw_DomainGroups ="";
		gfolder_ro_DomainGroups ="";
		
	return true;
	
}
function onLoadEditFolder(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);
	
}

function deleteFolder()
{
	var folders = new String();
 
	for(var i=1;i<gnum_folders;i++){
		if(document.getElementById('checkbox'+i.toString()).checked) folders = document.getElementById('checkbox'+i.toString()).value+";"+ folders; 
	}

	////debug(folders);
	var _txText =	'&txtName='+folders
			+"&txtSetupMode="+'delete';
	sendRequest(onLoadDeleteGroup,_txText,'post',gPhp[1],true,true);
	return true;
	
}

function onLoadDeleteGroup(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);

}

function entry_check()
{
	var folders = new String();
	//for(var i=0;i<gnum_folders-1;i++){
	for(var i=1;i<gnum_folders;i++){
		//alert(i);
		//alert(document.getElementById('checkbox'+i.toString()).value);
		if(document.getElementById('checkbox'+i.toString()).checked) folders = document.getElementById('checkbox'+i.toString()).value+";"+ folders; 
	}

	//alert(folders)
	if(folders == ''){
		return false;
	} else return true;
}

function rdo_checkbox(key,index){
	if(key == 'checkboxRWUser' ) {
		document.getElementById('checkboxROUser'+index).checked = false;
	}
	if (key == 'checkboxROUser' ){
		document.getElementById('checkboxRWUser'+index).checked = false;
	}
	if(key == 'checkboxRWGroup' ) {
		document.getElementById('checkboxROGroup'+index).checked = false;
	}
	if (key == 'checkboxROGroup' ){
		document.getElementById('checkboxRWGroup'+index).checked = false;
	}
	if(key == 'checkboxRWDomainUser' ) {
		var id = 'checkboxRWDomainUser'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;

			return;		
		}


		document.getElementById('checkboxRODomainUser'+index).checked = false;
		
		var match_res = gfolder_ro_DomainUsers.search(document.getElementById('checkboxRWDomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				//gfolder_ro_DomainUsers = gfolder_ro_DomainUsers+document.getElementById('checkboxRWDomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRODomainUser'+index).checked == false) {
		    	gfolder_ro_DomainUsers = gfolder_ro_DomainUsers.replace(document.getElementById('checkboxRWDomainUser'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_rw_DomainUsers.search(document.getElementById('checkboxRODomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				gfolder_rw_DomainUsers = gfolder_rw_DomainUsers+document.getElementById('checkboxRODomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRWDomainUser'+index).checked == false) {
		    	gfolder_rw_DomainUsers = gfolder_rw_DomainUsers.replace(document.getElementById('checkboxRWDomainUser'+index).value+';','');
		    }	
		}
		//alert(gfolder_rw_DomainUsers+':::::'+gfolder_ro_DomainUsers);
		
	}
	if (key == 'checkboxRODomainUser' ){
		var id = 'checkboxRODomainUser'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}

		document.getElementById('checkboxRWDomainUser'+index).checked = false;
		
		var match_res = gfolder_rw_DomainUsers.search(document.getElementById('checkboxRODomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				//gfolder_ro_DomainUsers = gfolder_ro_DomainUsers+document.getElementById('checkboxRWDomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRWDomainUser'+index).checked == false) {
		    	gfolder_rw_DomainUsers = gfolder_rw_DomainUsers.replace(document.getElementById('checkboxRODomainUser'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_ro_DomainUsers.search(document.getElementById('checkboxRWDomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				gfolder_ro_DomainUsers = gfolder_ro_DomainUsers+document.getElementById('checkboxRWDomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRODomainUser'+index).checked == false) {
		    	gfolder_ro_DomainUsers = gfolder_ro_DomainUsers.replace(document.getElementById('checkboxRODomainUser'+index).value+';','');
		    }	
		}
		//alert(gfolder_rw_DomainUsers+':::::'+gfolder_ro_DomainUsers);
		
	}
	if(key == 'checkboxRWDomainGroup' ) {
		var id = 'checkboxRWDomainGroup'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}


		document.getElementById('checkboxRODomainGroup'+index).checked = false;
		
		var match_res = gfolder_ro_DomainGroups.search(document.getElementById('checkboxRWDomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				//gfolder_ro_DomainGroups = gfolder_ro_DomainGroups+document.getElementById('checkboxRWDomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRODomainGroup'+index).checked == false) {
		    	gfolder_ro_DomainGroups = gfolder_ro_DomainGroups.replace(document.getElementById('checkboxRWDomainGroup'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_rw_DomainGroups.search(document.getElementById('checkboxRODomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				gfolder_rw_DomainGroups = gfolder_rw_DomainGroups+document.getElementById('checkboxRODomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRWDomainGroup'+index).checked == false) {
		    	gfolder_rw_DomainGroups = gfolder_rw_DomainGroups.replace(document.getElementById('checkboxRWDomainGroup'+index).value+';','');
		    }	
		}
		//alert(gfolder_rw_DomainGroups+':::::'+gfolder_ro_DomainGroups);
	}
	if (key == 'checkboxRODomainGroup' ){
		var id = 'checkboxRODomainGroup'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}

		document.getElementById('checkboxRWDomainGroup'+index).checked = false;
		
		var match_res = gfolder_rw_DomainGroups.search(document.getElementById('checkboxRODomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				//gfolder_ro_DomainGroups = gfolder_ro_DomainGroups+document.getElementById('checkboxRWDomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRWDomainGroup'+index).checked == false) {
		    	gfolder_rw_DomainGroups = gfolder_rw_DomainGroups.replace(document.getElementById('checkboxRODomainGroup'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_ro_DomainGroups.search(document.getElementById('checkboxRWDomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				gfolder_ro_DomainGroups = gfolder_ro_DomainGroups+document.getElementById('checkboxRWDomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxRODomainGroup'+index).checked == false) {
		    	gfolder_ro_DomainGroups = gfolder_ro_DomainGroups.replace(document.getElementById('checkboxRODomainGroup'+index).value+';','');
		    }	
		}
		  //alert(gfolder_rw_DomainGroups+':::::'+gfolder_ro_DomainGroups);
	}


	if(key == 'checkboxEditRWUser' ) {
		document.getElementById('checkboxEditROUser'+index).checked = false;
	}
	if (key == 'checkboxEditROUser' ){
		document.getElementById('checkboxEditRWUser'+index).checked = false;
	}
	if(key == 'checkboxEditRWGroup' ) {
		document.getElementById('checkboxEditROGroup'+index).checked = false;
	}
	if (key == 'checkboxEditROGroup' ){
		document.getElementById('checkboxEditRWGroup'+index).checked = false;
	}
	if(key == 'checkboxEditRWDomainUser' ) {
		var id ='checkboxEditRWDomainUser'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}


		document.getElementById('checkboxEditRODomainUser'+index).checked = false;
		
		var match_res = gfolder_ro_DomainUsers.search(document.getElementById('checkboxEditRWDomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				//gfolder_ro_DomainUsers = gfolder_ro_DomainUsers+document.getElementById('checkboxEditRWDomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRODomainUser'+index).checked == false) {
		    	gfolder_ro_DomainUsers = gfolder_ro_DomainUsers.replace(document.getElementById('checkboxEditRWDomainUser'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_rw_DomainUsers.search(document.getElementById('checkboxEditRODomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				gfolder_rw_DomainUsers = gfolder_rw_DomainUsers+document.getElementById('checkboxEditRODomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRWDomainUser'+index).checked == false) {
		    	gfolder_rw_DomainUsers = gfolder_rw_DomainUsers.replace(document.getElementById('checkboxEditRWDomainUser'+index).value+';','');
		    }	
		}
		//alert(gfolder_rw_DomainUsers+':::::'+gfolder_ro_DomainUsers);
		
	}
	if (key == 'checkboxEditRODomainUser' ){
		var id = 'checkboxEditRODomainUser'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}


		document.getElementById('checkboxEditRWDomainUser'+index).checked = false;
		
		var match_res = gfolder_rw_DomainUsers.search(document.getElementById('checkboxEditRODomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				//gfolder_ro_DomainUsers = gfolder_rw_DomainUsers+document.getElementById('checkboxEditRODomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRWDomainUser'+index).checked == false) {
		    	gfolder_rw_DomainUsers = gfolder_rw_DomainUsers.replace(document.getElementById('checkboxEditRODomainUser'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_ro_DomainUsers.search(document.getElementById('checkboxEditRWDomainUser'+index).value.replace('\\','\\\\'));
		
		if(match_res == -1){
				gfolder_ro_DomainUsers = gfolder_ro_DomainUsers+document.getElementById('checkboxEditRWDomainUser'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRODomainUser'+index).checked == false) {
		    	gfolder_ro_DomainUsers = gfolder_ro_DomainUsers.replace(document.getElementById('checkboxEditRODomainUser'+index).value+';','');
		    }	
		}
		//alert(gfolder_rw_DomainUsers+':::::'+gfolder_ro_DomainUsers);
		
	}
	if(key == 'checkboxEditRWDomainGroup' ) {
		var id = 'checkboxEditRWDomainGroup'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}

		document.getElementById('checkboxEditRODomainGroup'+index).checked = false;
		
			var match_res = gfolder_ro_DomainGroups.search(document.getElementById('checkboxEditRWDomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				//gfolder_ro_DomainGroups = gfolder_ro_DomainGroups+document.getElementById('checkboxEditRWDomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRODomainGroup'+index).checked == false) {
		    	gfolder_ro_DomainGroups = gfolder_ro_DomainGroups.replace(document.getElementById('checkboxEditRWDomainGroup'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_rw_DomainGroups.search(document.getElementById('checkboxEditRODomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				gfolder_rw_DomainGroups = gfolder_rw_DomainGroups+document.getElementById('checkboxEditRODomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRWDomainGroup'+index).checked == false) {
		    	gfolder_rw_DomainGroups = gfolder_rw_DomainGroups.replace(document.getElementById('checkboxEditRWDomainGroup'+index).value+';','');
		    }	
		}
		//alert(gfolder_rw_DomainGroups+':::::'+gfolder_ro_DomainGroups);
	}
	if (key == 'checkboxEditRODomainGroup' ){
		var id = 'checkboxEditRODomainGroup'+index;
		var res = DomainUserCheck(id);		
		if(res == false)		
		{			
			alert("<?php echo lang_get('special_char_restriction_name')?>");	
			document.getElementById(id).checked = false;
			return;		
		}


		document.getElementById('checkboxEditRWDomainGroup'+index).checked = false;
		
		var match_res = gfolder_rw_DomainGroups.search(document.getElementById('checkboxEditRODomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				//gfolder_ro_DomainGroups = gfolder_ro_DomainGroups+document.getElementById('checkboxEditRWDomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRWDomainGroup'+index).checked == false) {
		    	gfolder_rw_DomainGroups = gfolder_rw_DomainGroups.replace(document.getElementById('checkboxEditRODomainGroup'+index).value+';','');
		    }	
		}
		
		match_res = gfolder_ro_DomainGroups.search(document.getElementById('checkboxEditRWDomainGroup'+index).value.replace('\\','\\\\').replace(/\*+/g,'\\*'));
		
		if(match_res == -1){
				gfolder_ro_DomainGroups = gfolder_ro_DomainGroups+document.getElementById('checkboxEditRWDomainGroup'+index).value+';';
		}else {
		    if(document.getElementById('checkboxEditRODomainGroup'+index).checked == false) {
		    	gfolder_ro_DomainGroups = gfolder_ro_DomainGroups.replace(document.getElementById('checkboxEditRODomainGroup'+index).value+';','');
		    }	
		}
	}

}



function Check_box(VALUE) {
	var index;
	
	if(VALUE=='acl_off_create'){
		
		/*
		document.frmTS.chkAllUserRW.disabled = true;
		document.frmTS.chkAllUserRO.disabled = true;
		document.frmTS.chkAllGroupRW.disabled = true;
		document.frmTS.chkAllGroupRO.disabled = true;
		
		document.frmTS.chkAllUserRW.checked==false;
		document.frmTS.chkAllUserRO.checked==false;
		document.frmTS.chkAllGroupRW.checked==false;
		document.frmTS.chkAllGroupRO.checked==false;
		*/
		//var gnum_users,gnum_groups,gnum_Domainusers,gnum_Domaingroups,gnum_folders,gMax_Volume; checkboxRWUser"+i+"
		
		
		for(index = 1 ; index < gnum_users ; index++) {
		
			document.getElementById('checkboxRWUser'+index).disabled = true;
			document.getElementById('checkboxROUser'+index).disabled = true;
			document.getElementById('checkboxRWUser'+index).checked = false;
			document.getElementById('checkboxROUser'+index).checked = false;
		}
		for(index = 1 ; index < gnum_groups ; index++) {
			
			document.getElementById('checkboxRWGroup'+index).disabled = true;
			document.getElementById('checkboxROGroup'+index).disabled = true;	
			document.getElementById('checkboxRWGroup'+index).checked = false;
			document.getElementById('checkboxROGroup'+index).checked = false;	
		}

		for(index = 1 ; index < gnum_Domainusers ; index++) {
		
			document.getElementById('checkboxRWDomainUser'+index).disabled = true;
			document.getElementById('checkboxRODomainUser'+index).disabled = true;
			document.getElementById('checkboxRWDomainUser'+index).checked = false;
			document.getElementById('checkboxRODomainUser'+index).checked = false;
		}
		gfolder_rw_DomainUsers ="";
		gfolder_ro_DomainUsers = "";

		
		for(index = 1 ; index < gnum_Domaingroups ; index++) {
			
			document.getElementById('checkboxRWDomainGroup'+index).disabled = true;
			document.getElementById('checkboxRODomainGroup'+index).disabled = true;
			document.getElementById('checkboxRWDomainGroup'+index).checked = false;
			document.getElementById('checkboxRODomainGroup'+index).checked = false;		
		
		}
		gfolder_rw_DomainGroups ="";
		gfolder_ro_DomainGroups = "";


		
	}else if(VALUE=='acl_on_create')
	{
	
		for(index = 1 ; index < gnum_users ; index++) {
		
			document.getElementById('checkboxRWUser'+index).disabled = false;
			document.getElementById('checkboxROUser'+index).disabled = false;
		}
		for(index = 1 ; index < gnum_groups ; index++) {
			
			document.getElementById('checkboxRWGroup'+index).disabled = false;
			document.getElementById('checkboxROGroup'+index).disabled = false;	
		
		}

		for(index = 1 ; index < gnum_Domainusers ; index++) {
		
			document.getElementById('checkboxRWDomainUser'+index).disabled = false;
			document.getElementById('checkboxRODomainUser'+index).disabled = false;
		}
		for(index = 1 ; index < gnum_Domaingroups ; index++) {
			
			document.getElementById('checkboxRWDomainGroup'+index).disabled = false;
			document.getElementById('checkboxRODomainGroup'+index).disabled = false;	
		
		}

	}else if(VALUE=='acl_off_edit'){
		
				
		for(index = 1 ; index < gnum_users ; index++) {
		
			document.getElementById('checkboxEditRWUser'+index).disabled = true;
			document.getElementById('checkboxEditROUser'+index).disabled = true;
			document.getElementById('checkboxEditRWUser'+index).checked = false;
			document.getElementById('checkboxEditROUser'+index).checked = false;
		}
		for(index = 1 ; index < gnum_groups ; index++) {
			
			document.getElementById('checkboxEditRWGroup'+index).disabled = true;
			document.getElementById('checkboxEditROGroup'+index).disabled = true;	
			document.getElementById('checkboxEditRWGroup'+index).checked = false;
			document.getElementById('checkboxEditROGroup'+index).checked = false;	
		}

		for(index = 1 ; index < gnum_Domainusers ; index++) {
		
			document.getElementById('checkboxEditRWDomainUser'+index).disabled = true;
			document.getElementById('checkboxEditRODomainUser'+index).disabled = true;
			document.getElementById('checkboxEditRWDomainUser'+index).checked = false;
			document.getElementById('checkboxEditRODomainUser'+index).checked = false;

		}
		gfolder_rw_DomainUsers ="";
		gfolder_ro_DomainUsers = "";
		for(index = 1 ; index < gnum_Domaingroups ; index++) {
			
			document.getElementById('checkboxEditRWDomainGroup'+index).disabled = true;
			document.getElementById('checkboxEditRODomainGroup'+index).disabled = true;
			document.getElementById('checkboxEditRWDomainGroup'+index).checked = false;
			document.getElementById('checkboxEditRODomainGroup'+index).checked = false;		
		
		}
		gfolder_rw_DomainGroups ="";
		gfolder_ro_DomainGroups = "";		
		
	}else if(VALUE=='acl_on_edit')
	{
	
		for(index = 1 ; index < gnum_users ; index++) {
		
			document.getElementById('checkboxEditRWUser'+index).disabled = false;
			document.getElementById('checkboxEditROUser'+index).disabled = false;
		}
		for(index = 1 ; index < gnum_groups ; index++) {
			
			document.getElementById('checkboxEditRWGroup'+index).disabled = false;
			document.getElementById('checkboxEditROGroup'+index).disabled = false;	
		
		}

		for(index = 1 ; index < gnum_Domainusers ; index++) {
		
			document.getElementById('checkboxEditRWDomainUser'+index).disabled = false;
			document.getElementById('checkboxEditRODomainUser'+index).disabled = false;
		}
		for(index = 1 ; index < gnum_Domaingroups ; index++) {
			
			document.getElementById('checkboxEditRWDomainGroup'+index).disabled = false;
			document.getElementById('checkboxEditRODomainGroup'+index).disabled = false;	
		
		}

	}else if(VALUE=='hidden_create')
	{
		
		document.getElementById('chkFolderAFPLocalGroup').disabled = true;
		document.getElementById('chkFolderFTPLocalGroup').disabled = true;
		document.getElementById('chkFolderWebdavLocalGroup').disabled = true;
		
		document.getElementById('chkFolderAFPLocalGroup').checked = false;
		document.getElementById('chkFolderFTPLocalGroup').checked = false;
		document.getElementById('chkFolderWebdavLocalGroup').checked = false;		
		document.getElementById('chkFolderWinLocalGroup').checked = true;
		
		document.getElementById('chkFolderAFPLocalUser').disabled = true;
		document.getElementById('chkFolderFTPLocalUser').disabled = true;
		document.getElementById('chkFolderWebdavLocalUser').disabled = true;
		
		document.getElementById('chkFolderAFPLocalUser').checked = false;
		document.getElementById('chkFolderFTPLocalUser').checked = false;
		document.getElementById('chkFolderWebdavLocalUser').checked = false;		
		document.getElementById('chkFolderWinLocalUser').checked = true;

		document.getElementById('chkFolderAFPDomainGroup').disabled = true;
		document.getElementById('chkFolderFTPDomainGroup').disabled = true;
		document.getElementById('chkFolderWebdavDomainGroup').disabled = true;

		document.getElementById('chkFolderAFPDomainGroup').checked = false;
		document.getElementById('chkFolderFTPDomainGroup').checked = false;
		document.getElementById('chkFolderWebdavDomainGroup').checked = false;
		document.getElementById('chkFolderWinDomainGroup').checked = true;
		
		document.getElementById('chkFolderAFPDomainUser').disabled = true;
		document.getElementById('chkFolderFTPDomainUser').disabled = true;
		document.getElementById('chkFolderWebdavDomainUser').disabled = true;
		document.getElementById('chkFolderAFPDomainUser').checked = false;
		document.getElementById('chkFolderFTPDomainUser').checked = false
		document.getElementById('chkFolderWebdavDomainUser').checked = false
		document.getElementById('chkFolderWinDomainUser').checked = true;;

		
	}else if(VALUE=='hidden_edit'){
		document.getElementById('chkFolderAFPEditLocalGroup').disabled = true;
		document.getElementById('chkFolderFTPEditLocalGroup').disabled = true;
		document.getElementById('chkFolderWebdavEditLocalGroup').disabled = true;
		document.getElementById('chkFolderAFPEditLocalGroup').checked = false;
		document.getElementById('chkFolderFTPEditLocalGroup').checked = false;
		document.getElementById('chkFolderWebdavEditLocalGroup').checked = false;
		document.getElementById('chkFolderWinEditLocalGroup').checked = true;

		document.getElementById('chkFolderAFPEditLocalUser').disabled = true;
		document.getElementById('chkFolderFTPEditLocalUser').disabled = true;
		document.getElementById('chkFolderWebdavEditLocalUser').disabled = true;
		document.getElementById('chkFolderAFPEditLocalUser').checked = false;
		document.getElementById('chkFolderFTPEditLocalUser').checked = false;
		document.getElementById('chkFolderWebdavEditLocalUser').checked = false;		
		document.getElementById('chkFolderWinEditLocalUser').checked = true;

		document.getElementById('chkFolderAFPEditDomainGroup').disabled = true;
		document.getElementById('chkFolderFTPEditDomainGroup').disabled = true;
		document.getElementById('chkFolderWebdavEditDomainGroup').disabled = true;
		document.getElementById('chkFolderAFPEditDomainGroup').checked = false;
		document.getElementById('chkFolderFTPEditDomainGroup').checked = false;
		document.getElementById('chkFolderWebdavEditDomainGroup').disabled = true;
		document.getElementById('chkFolderWinEditDomainGroup').checked = true;

		document.getElementById('chkFolderAFPEditDomainUser').disabled = true;
		document.getElementById('chkFolderFTPEditDomainUser').disabled = true;
		document.getElementById('chkFolderWebdavEditDomainUser').disabled = true;
		document.getElementById('chkFolderAFPEditDomainUser').checked = false;
		document.getElementById('chkFolderFTPEditDomainUser').checked = false;
		document.getElementById('chkFolderWebdavEditDomainUser').checked = false;		
		document.getElementById('chkFolderWinEditDomainUser').checked = true;

				
	}else if(VALUE=='normal_create')
	{
		document.getElementById('chkFolderAFPLocalGroup').disabled = false;
		document.getElementById('chkFolderFTPLocalGroup').disabled = false;
		document.getElementById('chkFolderWebdavLocalGroup').disabled = false;
		
		document.getElementById('chkFolderAFPLocalUser').disabled = false;
		document.getElementById('chkFolderFTPLocalUser').disabled = false;
		document.getElementById('chkFolderWebdavLocalUser').disabled = false;
		
		document.getElementById('chkFolderAFPDomainGroup').disabled = false;
		document.getElementById('chkFolderFTPDomainGroup').disabled = false;
		document.getElementById('chkFolderWebdavDomainGroup').disabled = false;
		
		document.getElementById('chkFolderAFPDomainUser').disabled = false;
		document.getElementById('chkFolderFTPDomainUser').disabled = false;
		document.getElementById('chkFolderWebdavDomainUser').disabled = false;
		
	}else if(VALUE=='normal_edit')
	{
		document.getElementById('chkFolderAFPEditLocalGroup').disabled = false;
		document.getElementById('chkFolderFTPEditLocalGroup').disabled = false;
		document.getElementById('chkFolderWebdavEditLocalGroup').disabled = false;
		
		document.getElementById('chkFolderAFPEditLocalUser').disabled = false;
		document.getElementById('chkFolderFTPEditLocalUser').disabled = false;
		document.getElementById('chkFolderWebdavEditLocalUser').disabled = false;
		
		document.getElementById('chkFolderAFPEditDomainGroup').disabled = false;
		document.getElementById('chkFolderFTPEditDomainGroup').disabled = false;
		document.getElementById('chkFolderWebdavEditDomainGroup').disabled = false;
		
		document.getElementById('chkFolderAFPEditDomainUser').disabled = false;
		document.getElementById('chkFolderFTPEditDomainUser').disabled = false;
		document.getElementById('chkFolderWebdavEditDomainUser').disabled = false;
		
	}
	return;
}

function IDCheck() {
	if(!(valid_name(document.getElementById('txtFolderNameLocalUser')))) {
		//alert('The entered username is not valid\nusername may include at least 3 and up to 12 alphanumeric character including hypen and underscore');	
		return false;
	}
	return true;
}
function DomainUserCheck(ID) 
{	
	if(!(valid_DomainUserName(document.getElementById(ID)))) {						
		return false;	
	}	
	return true;
}


function valid_DomainUserName(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_*\\.";
    	return containsCharsOnly(input,chars);
}



function DescCheck() {
	if(!(not_valid_desc(document.getElementById('txtFolderDescLocalUser')))) {
		//alert('The entered Desc err');	
		return false;
	}
	//alert('The entered Desc Ok');	
	return true;
}

function DescEditCheck() {
	if(!(not_valid_desc(document.getElementById('txtFolderDescEditLocalUser')))) {
		//alert('The entered Desc err');	
		return false;
	}
	//alert('The entered Desc Ok');	
	return true;
}

function containsCharsOnly(input,chars) {

    	var non_start_char = "-_";
    	if(!(non_start_char.indexOf(input.value.charAt(0)) == -1)) return false;

    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           	return false;
    	}
    	return true;
}

function valid_name(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
	if(input.value.length<3) return false;	
	if(input.value.length>24) return false;
    	return containsCharsOnly(input,chars);
}

function not_valid_desc(input) {
    	var chars = "%&\\'\"";
	return containsCharsAny(input,chars);
}

function containsCharsAny(input,chars) {

    	for (var inx = 0; inx < input.value.length; inx++) {
		//alert(chars.indexOf(input.value.charAt(inx)));
       		if (chars.indexOf(input.value.charAt(inx)) != -1)
           	return false;
    	}
    	return true;
}

function webdav_folderAccess(VALUE) {

	 if(VALUE == 'LocalUser')
	 {
	        if(document.getElementById('chkFolderWebdavLocalUser').checked == true)
	        {
	                document.getElementById('rdoFolderACLLocalUser_enable').checked = false;
	                document.getElementById('rdoFolderACLLocalUser_disable').checked = true;

	                document.getElementById('rdoFolderACLLocalUser_enable').disabled = true;
	                //document.getElementById('rdoFolderACLLocalGroup_enable').disabled = true;
			  //document.getElementById('rdoFolderACLDomainUser_enable').disabled = true;
			  //document.getElementById('rdoFolderACLDomainGroup_enable').disabled = true;

			  Check_box('acl_off_create');		
	  
	        }
	        else if(document.getElementById('chkFolderWebdavLocalUser').checked == false)
	        {
	                document.getElementById('rdoFolderACLLocalUser_enable').disabled = false;
	  		  //document.getElementById('rdoFolderACLLocalGroup_enable').disabled = false;
	                //document.getElementById('rdoFolderACLDomainUser_enable').disabled = false;
			  //document.getElementById('rdoFolderACLDomainGroup_enable').disabled = false;     
			  if(document.getElementById('rdoFolderACLLocalUser_disable').checked ==false)
				Check_box('acl_on_create');                  

	        }
	 }
	 if(VALUE == 'LocalGroup')
	 {
	        if(document.getElementById('chkFolderWebdavLocalGroup').checked == true)
	        {
	                document.getElementById('rdoFolderACLLocalGroup_enable').checked = false;
	                document.getElementById('rdoFolderACLLocalGroup_disable').checked = true;

	                document.getElementById('rdoFolderACLLocalGroup_enable').disabled = true;
	                Check_box('acl_off_create');	
	        }
	        else if(document.getElementById('chkFolderWebdavLocalGroup').checked == false)
	        {
	                document.getElementById('rdoFolderACLLocalGroup_enable').disabled = false;
			  if(document.getElementById('rdoFolderACLLocalGroup_disable').checked ==false)
				Check_box('acl_on_create');         
	        }
	}
	 if(VALUE == 'DomainUser')
	 {	

	        if(document.getElementById('chkFolderWebdavDomainUser').checked == true)
	        {
	                document.getElementById('rdoFolderACLDomainUser_enable').checked = false;
	                document.getElementById('rdoFolderACLDomainUser_disable').checked = true;

	                document.getElementById('rdoFolderACLDomainUser_enable').disabled = true;
			  Check_box('acl_off_create');			
	        }
	        else if(document.getElementById('chkFolderWebdavDomainUser').checked == false)
	        {
	                document.getElementById('rdoFolderACLDomainUser_enable').disabled = false;
			  if(document.getElementById('rdoFolderACLDomainUser_disable').checked ==false)
				Check_box('acl_on_create');         

	        }
	 }       

	 if(VALUE == 'DomainGroup')
	 {	
	        if(document.getElementById('chkFolderWebdavDomainGroup').checked == true)
	        {
	                document.getElementById('rdoFolderACLDomainGroup_enable').checked = false;
	                document.getElementById('rdoFolderACLDomainGroup_disable').checked = true;

	                document.getElementById('rdoFolderACLDomainGroup_enable').disabled = true;
	                Check_box('acl_off_create');	
	        }
	        else if(document.getElementById('chkFolderWebdavDomainGroup').checked == false)
	        {
	                document.getElementById('rdoFolderACLDomainGroup_enable').disabled = false;
			  if(document.getElementById('rdoFolderACLDomainGroup_disable').checked ==false)
				Check_box('acl_on_create');         
	        }        
	}
        
}

function webdav_edit_folderAccess(VALUE) {

	 if(VALUE == 'EditLocalUser')
	 {
	        if(document.getElementById('chkFolderWebdavEditLocalUser').checked == true)
	        {
	                document.getElementById('rdoFolderACLEditLocalUser_enable').checked = false;
	                document.getElementById('rdoFolderACLEditLocalUser_disable').checked = true;

	                document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = true;
	                //document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = true;
			  //document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = true;
			  //document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = true;
	                Check_box('acl_off_edit');	
	        }
	        else if(document.getElementById('chkFolderWebdavEditLocalUser').checked == false)
	        {
	                document.getElementById('rdoFolderACLEditLocalUser_enable').disabled = false;
	  		  //document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = false;
	                //document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = false;
			  //document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = false;  
			  if(document.getElementById('rdoFolderACLEditLocalUser_disable').checked ==false)
				Check_box('acl_on_edit');         

	        }
	 }

	 if(VALUE == 'EditLocalGroup')
	 {
	        if(document.getElementById('chkFolderWebdavEditLocalGroup').checked == true)
	        {
	                document.getElementById('rdoFolderACLEditLocalGroup_enable').checked = false;
	                document.getElementById('rdoFolderACLEditLocalGroup_disable').checked = true;

	                document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = true;
	                Check_box('acl_off_edit');	
	        }
	        else if(document.getElementById('chkFolderWebdavEditLocalGroup').checked == false)
	        {
	                document.getElementById('rdoFolderACLEditLocalGroup_enable').disabled = false;
			  if(document.getElementById('rdoFolderACLEditLocalGroup_disable').checked ==false)
				Check_box('acl_on_edit');           

	        }
	 }



	 if(VALUE == 'EditDomainUser')
	 {	 

	        if(document.getElementById('chkFolderWebdavEditDomainUser').checked == true)
	        {
	                document.getElementById('rdoFolderACLEditDomainUser_enable').checked = false;
	                document.getElementById('rdoFolderACLEditDomainUser_disable').checked = true;

	                document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = true;
	                Check_box('acl_off_edit');	
	        }else if(document.getElementById('chkFolderWebdavEditDomainUser').checked == false)
	        {
	                document.getElementById('rdoFolderACLEditDomainUser_enable').disabled = false;
			  if(document.getElementById('rdoFolderACLEditDomainUser_disable').checked ==false)
				Check_box('acl_on_edit');            

	        }
	 }

	 if(VALUE == 'EditDomainGroup')
	 {	
	        if(document.getElementById('chkFolderWebdavEditDomainGroup').checked == true)
	        {
	                document.getElementById('rdoFolderACLEditDomainGroup_enable').checked = false;
	                document.getElementById('rdoFolderACLEditDomainGroup_disable').checked = true;

	                document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = true;
	                Check_box('acl_off_edit');	
	        }else if(document.getElementById('chkFolderWebdavEditDomainGroup').checked == false)
	        {
	                document.getElementById('rdoFolderACLEditDomainGroup_enable').disabled = false;
			  if(document.getElementById('rdoFolderACLEditDomainGroup_disable').checked ==false)
				Check_box('acl_on_edit');              

	        }
	 }
}


function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}



//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/share/help_folder.html','Help_folder','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  	_win.focus();
	hPopWin = _win;
}
