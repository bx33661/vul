<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>


//=======================================================//
// Page information
//=======================================================//
var page = {
	"name" : "myinfo",
	"init" : function(){
		// To do
		user.get_info();
	}
}
//=======================================================//
// 
//=======================================================//
var user = {
	"info" : {},
	"get_info" : function(){
		
		sendRequest(on_get_info,"&mode=user_info","post","../php/myinfo.php",true,true);
		var _msg = "<?php echo lang_get('common_loading')?>";
		document.getElementById('idName').innerHTML = _msg;
		document.getElementById('idId').innerHTML = _msg;
		document.getElementById('idPw').innerHTML = _msg;
		document.getElementById('idEmail').innerHTML = _msg;
		document.getElementById('idDesc').innerHTML = _msg;
		
		function on_get_info(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
			eval("user.info="+res);
			//if(user.info.email == "") user.info.email = " ";
			//if(user.info.desc == "") user.info.desc = " ";
			//debug(user.info.name);
			user.show_info();
		}
	},
	"show_info" : function(){
		document.getElementById('idName').innerHTML = (user.info.name == "")? "&nbsp;" : this.info.name;
		document.getElementById('idId').innerHTML = this.info.id;
		document.getElementById('idPw').innerHTML = "xxxxxxxxxxxx";
		document.getElementById('idEmail').innerHTML = (user.info.email == "")? "&nbsp;" : this.info.email;
		document.getElementById('idDesc').innerHTML = (user.info.desc == "")? "&nbsp;" : this.info.desc;
	},
	"confirm_pw" : function(){

		var _id = document.getElementById('idIdIn').innerHTML;
		var _pw = document.getElementById('idPwIn').value;
		
		//debug(_pw);
		
		if(_pw == ""){
			alert("<?php echo lang_get('login_msg_2')?>");
			return false;
		}
		
	
			
		
		var _new_pw = document.getElementById('idPwNew').value;
		var _new_pw_confirm = document.getElementById('idPwNew2').value;
		
		if(_new_pw != _new_pw_confirm){
			alert("<?php echo lang_get('user_msg_13')?>");
			return false;
		}else if(_new_pw.length >1 && _new_pw.length <6 ){
			//alert("too short");
			table.open(2);
			display_POPUP('short_pw');
			return false;
		}
		
		
		//disable_buttons();
		var op_mode = "login";
		var _cmd='&op_mode='+op_mode+'&id='+_id+'&password='+_pw;
		table.open(2);
		display_POPUP('setting');

		sendRequest(on_confirm_pw,_cmd,"post","../php/login_check.php",true,true);
		//document.getElementById('idLogTxtOut').innerHTML = "Checking...";

	
		
		function on_confirm_pw(oj){
			
			var res=decodeURIComponent(oj.responseText);
			//debug(res);
			
			var tmp = res.split("\n");
			res = tmp[0].split(":");
			if(res[0]=='OK1' || res[0]=='OK2')
			{
				// TO DO
				user.set_info()
				//document.getElementById('idLogTxtOut').innerHTML = "Setting information...";
			}else
			{
				
				//var _msg = 'Wrong password!';
				//debug(msg);
				//document.getElementById('idLogTxtOut').innerHTML = _msg;
				display_POPUP('error_pw');
			}
		}
		/*
		function disable_buttons(){
			document.getElementById('id_btn_app').disabled = true;
			document.getElementById('id_btn_back').disabled = true;
		}*/
	},
	"set_info" : function (){
		
		var _cmd = "&mode=set_info";
		_cmd += "&name="+document.getElementById('idNameIn').value;
		_cmd += "&id="+document.getElementById('idIdIn').innerHTML;
		//_cmd += "&pw="+document.getElementById('idPwNew').value;
		_cmd += "&email="+document.getElementById('idEmailIn').value;
		_cmd += "&desc="+document.getElementById('idDescIn').value;
		
		
		if(document.getElementById('idPwNew').value != ''){
			_cmd += "&pw="+document.getElementById('idPwNew').value;
	  }
	  else{
			_cmd += "&pw="+document.getElementById('idPwIn').value;
	  }	
		
		sendRequest(on_set_info,_cmd,"post","../php/myinfo.php",true,true);
		
		function on_set_info(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
			
			var _tmp = res.split(":");
			if(_tmp[0]=="ok"){
				
				//alert("Information is changed!");
				//location.href = "../index.php";
				display_POPUP('complete');
			}
			/*
			else{
				document.getElementById('idLogTxtOut').innerHTML = res;
			}*/
			// TO DO
		}
	}
}
//=======================================================//
// 
//=======================================================//
var table = {
	"table_id" : ["id_table01","id_table02","id_popup_my_info"],
	"table_name" : "",
	"open" : function(index){

		this.close_all();
		if(index==2){
			document.getElementById(this.table_id[2]).style.display = "block";
			return true;
		}
		if(index>0){
			var _msg = this.check(index-1)
			if ( _msg ){
				alert(_msg);
				//return false;
			}
		}
		this.write(index);
		document.getElementById(this.table_id[index]).style.display = "block";
	},
	"close_all" : function(){
		for( var i=0;this.table_id[i];i++ ){
			document.getElementById(this.table_id[i]).style.display = "none";
		}
	},
	"write" : function(index){
		switch(index){
			case 0:
			case 1:
				edit_table();
				break
			case 2:
				log_table();
			case 3:
			default:
			break;
		}
		
		function edit_table(){
			document.getElementById('idNameIn').value = user.info.name;
			document.getElementById('idIdIn').innerHTML = user.info.id;
			document.getElementById('idPwIn').value = "";
			document.getElementById('idPwNew').value = "";
			document.getElementById('idPwNew2').value = "";
			
			document.getElementById('idEmailIn').value = user.info.email;
			document.getElementById('idDescIn').value = user.info.desc;
		}
		function log_table(){
			//document.getElementById('idIdLog').value = user.info.id;
			//document.getElementById('idPwLog').value = "";
			//document.getElementById('idLogTxtOut').innerHTML = "";
		}
	},
	"check" : function(index){
		switch(index){
			case 0:
				break;
			case 1:
				return check_table02_input();
				break;
			case 2:
			case 3:
			case 4:
			default:
			break;
		}
		return false;
		
	}
}
//=======================================================//
// Enter key detection
//=======================================================//
function processOnEnter(evt)
{
	//debug(evt);
	evt = (evt) ? evt : (Window.Event) ? Window.Event : "";
	if(evt)
	{
		var theKey = (evt.which) ? evt.which : evt.keyCode;
		//debug(!(theKey==13));
		if(theKey==13)
		{
			user.confirm_pw();
		}
		return !(theKey==13);
	}
}
//user.get_info();
//alert(user["name"]);
//=======================================================//
// Input validation check
//=======================================================//
function validate_email(form_id) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById(form_id).value;
   if(document.getElementById(form_id).value=='') return true;
   if(reg.test(address) == false) {
      document.getElementById(form_id).value=''; 
      alert('Invalid Email Address Format');

      return false;
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
			+"<tr><td height=\"120\" align=\"center\" class=\"red_s2\">";
	popup_footer 	= "</td></tr>";
	popup_button_header = "<tr><td align=\"center\"><img class=\"buttons\" border=\"0\" onclick=\""; 
	popup_button_footer = "\" src=\"../images/btn/btn_confirm.gif\"></td>"
        			+"</tr></table>";
	
	var popup = new String();
	//alert("this "+mode);
/////////User Create POPUP
	//alert(":"+mode+":");
	
	if(mode == 'setting'){
		popup_contents = "<?php echo lang_get('common_setting')?>";
		popup_button = 'off';
	}	
  
  else if(mode == 'error_pw'){

  	popup_contents = "<?php echo lang_get('user_msg_4')?>";
		popup_button_link = "table.open(1)";
  }
  else if(mode == 'short_pw'){

  	popup_contents = "<?php echo lang_get('user_msg_3')?>";
		popup_button_link = "table.open(1)";
  }
  
	else if(mode == 'complete'){
		popup_contents = "<?php echo lang_get('network_servers_msg_3')?>";
		popup_button_link = "table.open(0);user.get_info();";
	}

	if(popup_button == 'off') popup = popup_header + popup_contents + popup_footer + "</table>";
		else popup = popup_header + popup_contents + popup_footer + popup_button_header + popup_button_link + popup_button_footer;

	
	document.getElementById('system_message').innerHTML = popup;


}

function FormCheck(id) {
	if(!(not_valid_desc(document.getElementById(id)))) {
		alert("'%','&','\\',',\" are not allowed");	
		document.getElementById(id).value = "";
		return false;
	}
	//alert('The entered Desc Ok');	
	return true;
}
function not_valid_desc(input) {
    	var chars = "%&\\'\"";
	return containsCharsAny(input,chars);
}

//========================================================//
// show_help
//========================================================//

function show_help()
{

		var _win = window.open('../help/system/help_myinfo.html','Help_System_wizard','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;

	}