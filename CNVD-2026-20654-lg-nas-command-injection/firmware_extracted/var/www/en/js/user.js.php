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
var gPhp = new Array("../php/share_get_user_info.php","../php/share_set_user_info.php");
//========================================================//
// Table ID
//========================================================//
var gIdTable=new Array('idTable_UserList','idTable_UserCreate','idTable_UserEdit','idTable_USER_POPUP');

var gFull_User_List = new String();
var gGroup_Member_List = new String();
var gnum_users; 
var gUserPass = new String();


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
		popup_contents = "<?php echo lang_get('user_id_restriction')?>";
		popup_button_link = "showTable('idTable_UserCreate');";
	}

	if(mode == 'PASS_err'){
		popup_contents = "<?php echo lang_get('user_msg_3')?>";
		popup_button_link = "showTable('idTable_UserCreate');";
	}

	if(mode == 'cfmPASS_err'){
		popup_contents = "<?php echo lang_get('user_msg_13')?>";
		popup_button_link = "showTable('idTable_UserCreate');";
	}

	if(mode == 'PASSEdit_err'){
		popup_contents = "<?php echo lang_get('user_msg_3')?>";
		popup_button_link = "showTable('idTable_UserEdit');";
	}

	if(mode == 'cfmPASSEdit_err'){
		popup_contents = "<?php echo lang_get('user_msg_13')?>";
		popup_button_link = "showTable('idTable_UserEdit');";
	}
  
	if(mode == 'create_user'){
		popup_contents = "<?php echo lang_get('user_msg_5')?>";
		popup_button = 'off';
	}

	if(mode == 'ID_conflict'){
		popup_contents = "<?php echo lang_get('user_msg_6')?>";
		popup_button_link = "showTable('idTable_UserCreate');";
	}

	if(mode == 'user'){
		popup_contents = "<?php echo lang_get('user_msg_7')?>";
		popup_button_link = "GetUserList();showTable('idTable_UserList');";
	}


	if(mode == 'edit_user'){
		popup_contents = "<?php echo lang_get('user_msg_8')?>";
		popup_button = 'off';
	}	

	if(mode == 'edit_done'){
		popup_contents = "<?php echo lang_get('user_msg_12')?>";
		popup_button_link = "GetUserList();showTable('idTable_UserList');";
	}

	if(mode == 'delete_user'){
		popup_contents = "<?php echo lang_get('user_msg_9')?>";
		popup_button = 'off';
	}	

	if(mode == 'delete_done'){
		popup_contents = "<?php echo lang_get('user_msg_10')?>";
		popup_button_link = "GetUserList();showTable('idTable_UserList');";
	}
	
	if(mode == 'delete_none'){
		popup_contents = "<?php echo lang_get('user_msg_11')?>";
		popup_button_link = "GetUserList();showTable('idTable_UserList');";
	}

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('idTable_USER_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}



function Set_User_Info()
{

	if(IDCheck()){
		//alert("IDOK");
		if(PASSCheck()){
			if(cfmPASSCheck()){
				display_POPUP('create_user');
				createUser();
	
			}else {
				display_POPUP('cfmPASS_err');
			}		
		}else{
			display_POPUP('PASS_err');
		}		
	}else {
		display_POPUP('ID_err');

	}		
	

}

function Edit_User_Info()
{
	
	if(PASSEditCheck()){
		if(cfmPASSEditCheck()){
			display_POPUP('edit_user');
			editUser();
		}else {
		display_POPUP('cfmPASSEdit_err');
		}
	}else{
		display_POPUP('PASSEdit_err');
	}
}

function Delete_User_Info()
{
	if(entry_check()){
		display_POPUP('delete_user');
		DeleteUser();
	}else display_POPUP('delete_none');	

}


//========================================================//
// Show table area
//========================================================//
function showTable(id)
{
	//debug(id);
	if ( id == 'idTable_UserList'){
	document.getElementById(gIdTable[0]).style.display = "block";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";	
	}
	if ( id == 'idTable_UserCreate'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "block";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "none";	
	}
	if ( id == 'idTable_UserEdit'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "block";
	document.getElementById(gIdTable[3]).style.display = "none";	
	}
	
	if ( id == 'idTable_USER_POPUP'){
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";
	document.getElementById(gIdTable[3]).style.display = "block";
	}

	
}

function clearForm(id)
{
	//debug(id);
	if ( id == 'idTable_UserCreate'){
	
		document.getElementById('txtUserID').value = '';
		document.getElementById('txtUserPass1').value = '';
		document.getElementById('txtUserPass2').value = '';
		document.getElementById('txtUserName').value = '';
		document.getElementById('txtUserDesc').value = '';
		document.getElementById('txtUserEmail').value = '';
	
		document.getElementById('checkboxCreate').checked = false;
	}

	if ( id == 'idTable_UserEdit'){
		document.getElementById('checkboxEmailEdit').checked = false;
	}

}


function GetUserList()
{

	var _txText =	"&mode="+"FullList";
	
	
	sendRequest(onLoadFullUserList,_txText,'post',gPhp[0],false,true);
	
	return true;
	
}



function onLoadFullUserList(oj)
{
	var Full_User_List = decodeURIComponent(oj.responseText);
  
	var users = Full_User_List.split(':');

	var num_users = users.length;
	gnum_users = num_users;

	var user = new String();

	var user_table_entry = new String();

	var i,j;
	var disabled = new String();
	
	for(i=0;i<num_users-1;i++){
		user = users[i].split(';');
	//	debug(user);
		disabled = '';
		if(user[0] == 'admin') disabled = 'disabled';
		
		
		user_table_entry=user_table_entry +"<tr>"
																+"<td class='firstCol_250' style='width:150px'>" 
																+ "<input type='checkbox' name='chkUserList"+i+"' id='chkUserList"+i+"' value="+user[0]+" "+disabled+">"
				                        +"<a href=\"#\" onclick=\"ShowUserEdit('"+user[0]+"','"+user[1]+"','"+user[2]+"','"+user[3]+"','"+user[4]+"')\">" +user[0]+"</a></td>"
																+"<td class='otherCol_420' style='width:150px'>" + user[2] + "&nbsp;</td>"
																+"<td class='thirdCol_100' style='width:170px'>" + user[3] + "&nbsp;</td>"
																+"<td class='otherCol_420' style='width:180px'>" + user[4] + "&nbsp;</td>"
																+"</tr>"

		//debug(user_table_entry);
	}
	
	user_table_entry = "<table width=\"650\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">"
											 +user_table_entry
											 +"</table>";		
	
	
	document.getElementById('userList').innerHTML = user_table_entry;

	showTable('idTable_UserList');	
	
}



function createUser()
{
	
	var userID = document.getElementById('txtUserID').value;
	var userPass = document.getElementById('txtUserPass1').value;
	var userName = document.getElementById('txtUserName').value;
	var userDesc = document.getElementById('txtUserDesc').value;
	var userEmail = document.getElementById('txtUserEmail').value;
	var userEmailNotify = new String; 
		if (document.getElementById('checkboxCreate').checked) userEmailNotify="on";
			else userEmailNotify="off";


	//showTable('idTable_Group_Create');	
	
	
	//debug(groupID+groupDesc+gnum_users+groupMembers);
	var _txText =	'&txtUserID='+userID
			+"&txtUserPassword="+userPass
			+"&txtUserPasswordChanged="+"true"
			+"&txtUserName="+userName
			+"&txtUserMail="+userEmail
			+"&txtUserDesc="+userDesc
			+"&chkMailNotification="+userEmailNotify
			+"&txtMode="+'add';
	
	sendRequest(onLoadCreateUser,_txText,'post',gPhp[1],true,true);
		
	return true;
	
}
function onLoadCreateUser(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	//alert(res);
	if(code[0] == 'ok') display_POPUP(code[1]);
}


function ShowUserEdit(userID,userPass,userName,userEmail,userDesc)
{
	showTable('idTable_UserEdit');
	clearForm('idTable_UserEdit');
	document.getElementById('txtUserIDEdit').innerHTML = userID;
	document.getElementById('txtUserPass1Edit').value = "**********";
	document.getElementById('txtUserPass2Edit').value = "**********";
	document.getElementById('txtUserNameEdit').value = userName;
	document.getElementById('txtUserDescEdit').value = userDesc;
	document.getElementById('txtUserEmailEdit').value = userEmail;
	
	gUserPass = userPass;

//	GetGroupMember(group_name);
	return true;
}
var isChangedPW = false;
function editUser()
{

	var userID = document.getElementById('txtUserIDEdit').innerHTML;
	var userPass = document.getElementById('txtUserPass1Edit').value;
	if(userPass == '**********') {
		userPass = gUserPass;
		isChangedPW = false;
	}
	else
	{
		isChangedPW = true;
	}

	var userName = document.getElementById('txtUserNameEdit').value;
	var userDesc = document.getElementById('txtUserDescEdit').value;
	var userEmail = document.getElementById('txtUserEmailEdit').value;
	var userEmailNotify = new String; 
		if (document.getElementById('checkboxEmailEdit').checked) userEmailNotify="on";
			else userEmailNotify="off";


	//showTable('idTable_Group_Create');	
	
	
	//debug(groupID+groupDesc+gnum_users+groupMembers);
	var _txText =	'&txtUserID='+userID
			+"&txtUserPassword="+userPass
			+"&txtUserPasswordChanged="+isChangedPW
			+"&txtUserName="+userName
			+"&txtUserMail="+userEmail
			+"&txtUserDesc="+userDesc
			+"&chkMailNotification="+userEmailNotify
			+"&txtMode="+'edit';
	
	sendRequest(onLoadEditUser,_txText,'post',gPhp[1],true,true);
		
	return true;
	
}
function onLoadEditUser(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	
	if(code[0] == 'ok') display_POPUP(code[1]);
	
}

function DeleteUser()
{
	var users = new String();
	//debug(gnum_users);

	for(var i=0;i<gnum_users-1;i++){
		if(document.getElementById('chkUserList'+i.toString()).checked) users = document.getElementById('chkUserList'+i.toString()).value+";"+ users; 
	}

	
	var _txText =	'&txtUserID='+users
			+"&txtMode="+'delete';
	sendRequest(onLoadDeleteUser,_txText,'post',gPhp[1],true,true);
	return true;
	
	
}

function onLoadDeleteUser(oj)
{
	var res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	if(code[0] == 'ok') display_POPUP(code[1]);

}

function entry_check()
{
	var users = new String();
	
	for(var i=0;i<gnum_users-1;i++){
		if(document.getElementById('chkUserList'+i.toString()).checked) users = document.getElementById('chkUserList'+i.toString()).value+";"+ users; 
	}

	if(users == ''){
		return false;
	} else return true;
}



function IDCheck() {
	if(!(valid_name_w_dot(document.getElementById('txtUserID')))) {
		//alert('The entered username is not valid\nusername may include at least 3 and up to 12 alphanumeric character including hypen and underscore');	
		
		return false;
	}
	return true;
}

function PASSCheck() {
	if(!(valid_passwd(document.getElementById('txtUserPass1')))) {
		//alert('The entered password is too short\npassword should be longer than 6 characters');	
		
		return false;
	}
	return true;
}

function cfmPASSCheck() {
	if(document.getElementById('txtUserPass1').value != document.getElementById('txtUserPass2').value){
		//alert('Passwords are not matched');
		
		return false;
	}
	return true;
}

function PASSEditCheck() {
	if(!(valid_passwd(document.getElementById('txtUserPass1Edit')))) {
		//alert('The entered password is too short\npassword should be longer than 6 characters');	
		
		return false;
	}
	return true;
}

function cfmPASSEditCheck() {
	if(document.getElementById('txtUserPass1Edit').value != document.getElementById('txtUserPass2Edit').value){
		//alert('Passwords are not matched');
		
		return false;
	}
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

function valid_name_w_dot(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	if(input.value.length<3) return false;	
	if(input.value.length>12) return false;
    	return containsCharsOnly(input,chars);
}

function valid_passwd(input) {
    	if(input.value.length<6) return false;	
	return true;
}

//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/share/help_user.html','Help_user','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
  hPopWin = _win;
}