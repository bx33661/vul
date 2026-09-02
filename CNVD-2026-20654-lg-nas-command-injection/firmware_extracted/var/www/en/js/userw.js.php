<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>



//////////////////////////////////////////
//         Create User Wizard           //    
//////////////////////////////////////////

//////////////////////////////////////////
//            PHP File List             //    
//////////////////////////////////////////
var gPhp = new Array("../php/share_get_user_info.php","../php/share_set_user_info.php","../php/userw_check_id.php");

//////////////////////////////////////////
//              Table ID                //    
//////////////////////////////////////////
var gIdTable=new Array('idTable_UserCreate','userw_POPUP','idTable_Info');

var checkDuplicate = false;
//////////////////////////////////////////
//         Button Click Event           //    
//////////////////////////////////////////
function Set_User_Info()
{
	if(checkDuplicate == false){
		alert("<?php echo lang_get('wizard_msg_1')?>");
		return;	
	}
	
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
	
			
}





//////////////////////////////////////////
//          Activate PHP                //    
//////////////////////////////////////////

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
	
	//alert(userID+"-"+userPass+"-"+userName+"-"+userDesc+"-"+userEmail+"-"+userEmailNotify);
	//showTable('idTable_Group_Create');	
	
	
	//debug(groupID+groupDesc+gnum_users+groupMembers);
	var _txText =	'&txtUserID='+userID
			+"&txtUserPassword="+userPass
			+"&txtUserName="+userName
			+"&txtUserMail="+userEmail
			+"&txtUserDesc="+userDesc
			+"&chkMailNotification="+userEmailNotify
			+"&txtMode="+'add';
			//alert(_txText);
	sendRequest(onLoadCreateUser,_txText,'post',gPhp[1],true,true);
		
	return true;
	
}
function onLoadCreateUser(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
	//alert(res);
	if(res == 'ok:user'){
					
		document.getElementById('newUserID').innerHTML = document.getElementById('txtUserID').value;
		document.getElementById('newUserName').innerHTML = document.getElementById('txtUserName').value;
		document.getElementById('newUserDesc').innerHTML = document.getElementById('txtUserDesc').value;
		document.getElementById('newUserEmail').innerHTML = document.getElementById('txtUserEmail').value;	
		
		showTable('idTable_Info');
		
	}
}

//////////////////////////////////////////
//             Display                  //    
//////////////////////////////////////////

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
	popup_button_header = "<tr><td align=\"center\"><a href=\"#\" onclick=\""; 
	popup_button_footer = "\"><img src=\"../images/btn/btn_confirm.gif\" border=\"0\"></a></td>"
        			+"</tr></table>";
	
	var popup = new String();
	//alert("this "+mode);
/////////User Create POPUP
	if(mode == 'ID_err'){
	popup_contents = "<?php echo lang_get('user_msg_2')?>";
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
		popup_button_link = "showTable('idTable_Info');";
		document.getElementById('newUserID').innerHTML = document.getElementById('txtUserID').value;
		document.getElementById('newUserName').innerHTML = document.getElementById('txtUserName').value;
		document.getElementById('newUserDesc').innerHTML = document.getElementById('txtUserDesc').value;
		document.getElementById('newUserEmail').innerHTML = document.getElementById('txtUserEmail').value;	
	}



	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	showTable('userw_POPUP');
	document.getElementById('system_message').innerHTML = popup;

}


/////////////////////
// Show table area //
/////////////////////

function showTable(id){	
	//debug(id);

	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	document.getElementById(gIdTable[2]).style.display = "none";

	if(id!=""){
		document.getElementById(id).style.display = "block";
	}
}


//////////////////////////////////////////
//       Duplicate ID Check             //    
//////////////////////////////////////////

// Define Prototype
String.prototype.replaceAll = function( searchStr, replaceStr )
{
var temp = this;

while( temp.indexOf( searchStr ) != -1 )
{
temp = temp.replace( searchStr, replaceStr );
}

return temp;
}


var txtUserID;
function IDCheck(){

		 txtUserID = 	document.getElementById('txtUserID');
 		 var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
 		
		 // Length & Character Check
		 if(txtUserID.value.length<3 || txtUserID.value.length > 12 || !containsCharsOnly(txtUserID,chars)) {
		 	_msg ="<?php echo lang_get('user_msg_2')?>"; 
		 	_msg = _msg.replaceAll('<BR />','\n');
		 	alert(_msg);
		 	return false;	
	   }
	  else if(txtUserID.value.toLowerCase() == 'root' || txtUserID.value.toLowerCase() == 'nobody'){
	  	alert("<?php echo lang_get('wizard_msg_2')?>");
		 	return false;
	   }
	   else{
	   		var _txText = "&txtUserID="+txtUserID.value;
	   		
	   		sendRequest(onLoadCheckUser,_txText,'post',gPhp[2],false,true);
	   		
		 }
	   
	    
}

function onLoadCheckUser(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
	if(res == 'Duplicated'){
		_msg = "<?php echo lang_get('user_msg_6')?>"; 
		alert(_msg.replaceAll('<BR />','\n'));
	}
	else if(res == 'Not Duplicated'){
			alert("<?php echo lang_get('wizard_msg_3')?> ["+txtUserID.value+"] <?php echo lang_get('wizard_msg_3_1')?>");
			//document.getElementById('txtUserID').disabled = true;
			checkDuplicate = true;
	
	}	
	//if(code[0] == 'ok') display_POPUP(code[1]);
}

function unCheck(){
			checkDuplicate = false;
}
//////////////////////////////////////////
//           Validation                 //    
//////////////////////////////////////////


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

function valid_name(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
	if(input.value.length<3) return false;	
	if(input.value.length>12) return false;
    	return containsCharsOnly(input,chars);
}

function valid_passwd(input) {
    	if(input.value.length<6) return false;	
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

//========================================================//
// show_help
//========================================================//

function show_help()
{

		var _win = window.open('../help/wizard/help_user_wizard.html','Help_System_wizard','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;

	}