<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>

//========================================================//
// System / EMail menu 
//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/share_get_group_info.php","../php/share_get_user_info.php","../php/share_set_group_info.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_Group_List','idTable_Group_Edit','idTable_Group_Create','idTable_Group_POPUP');

var gFull_User_List = new String();
var gGroup_Member_List = new String();
var gnum_users,gnum_groups; 

//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	//debug(id);
	if ( id == 'idTable_Group_List'){
	document.getElementById(gIdTable[0]).style.display = "block";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";
	
	}
	if ( id == 'idTable_Group_Edit'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "block";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";
	}
	if ( id == 'idTable_Group_Create'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "block";
	document.getElementById(gIdTable[3]).style.display = "none";
	}
	if ( id == 'idTable_Group_POPUP'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "block";
	}
}

function clearForm(id)
{
	//debug(id);
	if ( id == 'idTable_Group_Create'){
		document.getElementById('txtGroupName').value = '';
		document.getElementById('txtGroupDesc').value = '';
	
		
		//for(var i=0;i<gnum_users;i++){
		//	document.getElementById('checkbox'+i.toString()).checked = false; 
		//}
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
		popup_contents = "<?php echo lang_get('group_msg_1')?>";
		popup_button_link = "showTable('idTable_Group_Create');";
	}
	if(mode == 'Desc_err'){
		popup_contents = "<?php echo lang_get('group_msg_2')?>";
		popup_button_link = "showTable('idTable_Group_Create');";
	}
	if(mode == 'Desc_Edit_err'){
		popup_contents = "<?php echo lang_get('group_msg_2')?>";
		popup_button_link = "showTable('idTable_Group_Edit');";
	}
	if(mode == 'ID_conflict'){
		popup_contents = "<?php echo lang_get('group_msg_3')?>";
		popup_button_link = "showTable('idTable_Group_Create');";
	}
  
	if(mode == 'create_group'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";		
        	popup_button_header = "<tr><td align=\"center\">"		
		popup_contents = "<?php echo lang_get('group_msg_4')?>";
		popup_button = 'off';
	}

	if(mode == 'edit_group'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";		
        	popup_button_header = "<tr><td align=\"center\">"		
		popup_contents = "<?php echo lang_get('group_msg_5')?>";
		popup_button = 'off';
	}

	if(mode == 'delete_group'){
		popup_button_footer = "<img Id=\"img_page_loading\" src=\"../images/Burn/file_box_loading.gif\"/></td>"
        			+"</tr></table>";		
        	popup_button_header = "<tr><td align=\"center\">"		
		popup_contents = "<?php echo lang_get('group_msg_6')?>";
		popup_button = 'off';
	}

	if(mode == 'group'){
		popup_contents = "<?php echo lang_get('group_msg_7')?>";
		popup_button_link = "Get_Group_Info();showTable('idTable_Group_List');";
	}

	if(mode == 'groupedit'){
		popup_contents = "<?php echo lang_get('group_msg_8')?>";
		popup_button_link = "Get_Group_Info();showTable('idTable_Group_List');";
	}
	
	if(mode == 'groupdelete'){
		popup_contents = "<?php echo lang_get('group_msg_9')?>";
		popup_button_link = "Get_Group_Info();showTable('idTable_Group_List');";
	}

	if(mode == 'delete_none'){
		popup_contents = "<?php echo lang_get('group_msg_10')?>";
		popup_button_link = "Get_Group_Info();showTable('idTable_Group_List');";
	}


	//if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
	//	else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_footer;
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('idTable_Group_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}



function Set_Group_Info()
{

	
	if(IDCheck()){
		if(DescCheck()){
			//alert("IDOK");
			display_POPUP('create_group');
			createGroup();
		}else {
		display_POPUP('Desc_err');
		}
		
	}else {
		display_POPUP('ID_err');

	}		
	

}

function Edit_Group_Info()
{
	if(DescEditCheck()){
		display_POPUP('edit_group');
		editGroup();
	}else {
		display_POPUP('Desc_Edit_err');
		}
}

function Delete_Group_Info()
{
	if(entry_check()){
		display_POPUP('delete_group');
		DeleteGroup();
	}else display_POPUP('delete_none');
}





//========================================================//
// Get server time
//========================================================//
function Get_Group_Info()
{

	sendRequest(onLoadDT,'','post',gPhp[0],true,true);
	return true;
}
function onLoadDT(oj)
{
	var res = decodeURIComponent(oj.responseText);
//	debug(res);
	var _item = res.split(':');
	_item = _item.sort();

	ShowGroupInfo(_item);
	showTable('idTable_Group_List');	

}

function ShowGroupInfo(_item)
{

	var index = _item.length;
	gnum_groups=index;
	var i;
	var group_table_entry = new String();
	var group = new Array ();
	var disabled = new String();

	for(i=1;i<index;i++){
		
		group = _item[i].split(';');
		disabled = '';
		if(group[0] == 'users') disabled = 'disabled';


		group_table_entry=	group_table_entry +"<tr><td class='firstCol_250' style='width:200px;'>"
                                     			+"<input type=\"checkbox\" name=\"checkboxGroup"+i+"\" id=\"checkboxGroup"+i+"\" value="+group[0]+" "+disabled+">"
																					+"<a href=\"#\" onclick=\"ShowGroupEdit('"+group[0]+"','"+group[1]+"')\">"
                                        	+group[0]+"</a></td>"
        	                                +"<td class='otherCol_420' style='width:450px;'>"+group[1]+"</td>"
																					+"</tr>";
	}
	
	
	group_table_entry = "<table width=\"650\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +group_table_entry
											 +"</table>";	
	
		
	document.getElementById('groupList').innerHTML = group_table_entry;

	
}

function ShowGroupEdit(group_name,group_desc)
{
	showTable('idTable_Group_Edit');
	document.getElementById('txtGroupEdit').innerHTML = group_name;
	document.getElementById('txtGroupDescEdit').value = group_desc;
	GetGroupMember(group_name);
	return true;
	
}


function GetGroupMember(group_name)
{

	var mode;
		
	var _txText =	'&key='+group_name
			+"&mode="+"GroupMember";
	
	sendRequest(onLoadGroupMember,_txText,'post',gPhp[1],true,true);
		
	return true;
	
}
function onLoadGroupMember(oj)
{
	gGroup_Member_List = decodeURIComponent(oj.responseText);
	GetGroupMemberList();
}

function GetGroupMemberList()
{

	var _txText =	"&mode="+"FullList";
	
	
	sendRequest(onLoadFullList,_txText,'post',gPhp[1],true,true);
		
	return true;
	
}
function onLoadFullList(oj)
{
	gFull_User_List = decodeURIComponent(oj.responseText);

	var users = gFull_User_List.split(':');
	users = users.sort();

	var num_users = users.length;
	gnum_users = num_users;
	
	var user = new String();

	var members = gGroup_Member_List.split(':');
	var member = new String();

	var num_members = members.length;

	
	var check = new String();

	var user_table_entry = new String();

	var i,j;
	
	for(i=1;i<num_users;i++){
		user = users[i].split(';');

		check ="";

		for(j=0;j<num_members;j++){
			member = members[j].split(';');
			if (user[0] == member[0]) check = "checked";

		}
		
		user_table_entry=user_table_entry+"<tr><td class='firstCol_250' style='width:150px;'>"
                                        +"<input type='checkbox' name='checkboxEdit"+i+"' id='checkboxEdit"+i+"'"+check+" value="+user[0]+">"
                                       //+"<a href='#' onclick='editUser('"+i+"')'></a>"
																				+user[0]+"</td>"
                                        +"<td class='otherCol_420' style='width:200px;'>"+user[2]+"&nbsp;</td>"
                                        +"<td class='thirdCol_100' style='width:300px;'>"+user[4]+"&nbsp;</td>"
                                        +"</tr>";
	}
	
	user_table_entry = "<table width='650' border='0' cellspacing='0' cellpadding='0'>"
			 +user_table_entry
			 +"</table>";	

	document.getElementById('userList').innerHTML = user_table_entry;

	
}


function GetUserList()
{

	var _txText =	"&mode="+"FullList";
	
	
	sendRequest(onLoadFullUserList,_txText,'post',gPhp[1],true,true);
		
	return true;
	
}
function onLoadFullUserList(oj)
{
	var Full_User_List = decodeURIComponent(oj.responseText);
	//debug("userlist "+res);

	var users = Full_User_List.split(':');
	users = users.sort();

	var num_users = users.length;
	gnum_users = num_users;

	var user = new String();

	var user_table_entry = new String();

	var i,j;
	
	for(i=1;i<num_users;i++){
		user = users[i].split(';');
	//	debug(user);
		
		user_table_entry=user_table_entry+"<tr><td class='firstCol_250' style='width:150px;'>"
                                        +"<input type=\"checkbox\" name=\"checkbox"+i+"\" id=\"checkbox"+i+"\" value="+user[0]+">"
                                        //+"<a href=\"#\" onclick=\"editUser('"+i+"')\"></a>"
																				+user[0]+"</td>"
                                        +"<td class='otherCol_420' style='width:200px;'>"+user[2]+"&nbsp;</td>"
                                        +"<td class='thirdCol_100' style='width:300px;'>"+user[4]+"&nbsp;</td>"
                                        +"</tr>";
		//debug(user_table_entry);
	}
	
	user_table_entry = "<table width=\"650\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +user_table_entry
											 +"</table>";	
	
	document.getElementById('userListCreate').innerHTML = user_table_entry;
	
	
}



function createGroup()
{

	//showTable('idTable_Group_Create');	
	var groupID = document.getElementById('txtGroupName').value;
	var groupDesc = document.getElementById('txtGroupDesc').value;
	var groupMembers = new String();
	
	//debug(gnum_users);	
	for(var i=1;i<gnum_users;i++){
		//debug(document.getElementById('checkbox'+i.toString()).checked);
	
		if(document.getElementById('checkbox'+i.toString()).checked) groupMembers = document.getElementById('checkbox'+i.toString()).value+";"+ groupMembers; 
	}
	
	//alert(groupID+groupDesc+gnum_users+groupMembers);
	var _txText =	'&txtName='+groupID
			+"&txtComment="+groupDesc
			+"&txtMember="+groupMembers
			+"&txtMode="+'add';
	//alert(_txText);
	sendRequest(onLoadCreateGroup,_txText,'post',gPhp[2],true,true);
		
	return true;
	
}
function onLoadCreateGroup(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);
	
}

function editGroup()
{

	//showTable('idTable_Group_Create');	
	var groupID = document.getElementById('txtGroupEdit').innerHTML;
	var groupDesc = document.getElementById('txtGroupDescEdit').value;
	var groupMembers = new String();
	
	//debug(groupID);
	
	for(var i=1;i<gnum_users;i++){
		if(document.getElementById('checkboxEdit'+i.toString()).checked) groupMembers = document.getElementById('checkboxEdit'+i.toString()).value+";"+ groupMembers; 
	}
	
	//debug(groupID+groupDesc+gnum_users+groupMembers);
	var _txText =	'&txtName='+groupID
			+"&txtComment="+groupDesc
			+"&txtMember="+groupMembers
			+"&txtMode="+'edit';
	
	sendRequest(onLoadEditGroup,_txText,'post',gPhp[2],true,true);
		
	return true;
	
}
function onLoadEditGroup(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);
	
}

function DeleteGroup()
{

	var groups = new String();
	//debug(gnum_users);

	for(var i=1;i<gnum_groups;i++){
		if(document.getElementById('checkboxGroup'+i.toString()).checked) groups = document.getElementById('checkboxGroup'+i.toString()).value+";"+ groups; 
	}

	
	var _txText =	'&txtName='+groups
			+"&txtMode="+'delete';
	sendRequest(onLoadDeleteGroup,_txText,'post',gPhp[2],true,true);
	return true;
	
}

function onLoadDeleteGroup(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);

}

function entry_check()
{
	var groups = new String();
	//debug(gnum_users);

	for(var i=1;i<gnum_groups;i++){
		if(document.getElementById('checkboxGroup'+i.toString()).checked) groups = document.getElementById('checkboxGroup'+i.toString()).value+";"+ groups; 
	}

	if(groups == ''){
		return false;
	} else return true;
}

function IDCheck() {
	if(!(valid_name(document.getElementById('txtGroupName')))) {
		//alert('The entered username is not valid\nusername may include at least 3 and up to 12 alphanumeric character including hypen and underscore');	
		return false;
	}
	return true;
}

function DescCheck() {
	if(!(not_valid_desc(document.getElementById('txtGroupDesc')))) {
		//alert('The entered Desc err');	
		return false;
	}
	//alert('The entered Desc Ok');	
	return true;
}

function DescEditCheck() {
	if(!(not_valid_desc(document.getElementById('txtGroupDescEdit')))) {
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
	if(input.value.length>12) return false;
    	return containsCharsOnly(input,chars);
}

function not_valid_desc(input) {
    	var chars = "%&\\'\":";
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

//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/share/help_group.html','Help_group','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
	hPopWin = _win;
}