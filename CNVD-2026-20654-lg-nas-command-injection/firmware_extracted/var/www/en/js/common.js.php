<?php
	Header("content-type: application/x-javascript");
	require_once("../multilang/multilang_api.php");
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
	lang_set_active_language($t_lang_from_url[1]);
?>

<!--
//=======================================================//
// Refine String by Length
//=======================================================//
function to_str_by_form(str,form){
	var form_type = {
		type_list : ["file_name","file_size","file_time"],
		type_length : {
			file_name : 12,
			file_size : 6,
			file_time : 10
		}
	}
	switch(form){
		case form_type[0]:
		case form_type[0]:
		case form_type[0]:
		default:
		break;
	}
	
	function by_name(str,leng){
		return ret;
	}
	function by_size(str,leng){
		return ret;
	}
	function by_time(str,leng){
		return ret;
	}
}



function cutStrToArr(str,lNum,mLeng){
	//mLeng: max line length must be longer than 3
	if(mLeng<3 || !str) return str;
	var cLineLeng=mLeng,cutLines=[];
	for(var l=0;l<lNum;l++){
		if(str.length>mLeng){
			cLineLeng=mLeng;
			for(var li=0;li<mLeng;li++){
				if(escape(str.substr(li,1)).length > 4) cLineLeng-=0.5;
			}
			if(str.length>parseInt(cLineLeng)){
				if(l==lNum-1) cutLines[l]=str.slice(0,parseInt(cLineLeng-3))+"...";
				else cutLines[l]=str.slice(0,parseInt(cLineLeng));
				str=str.substr(parseInt(cLineLeng));
				continue;
			}else{
				cutLines[l]=str;
				break;
			}
		}else{
			cutLines[l]=str;
			break;
		}
	}
	return cutLines;
}



//=======================================================//
// Input Character Check
//=======================================================//
function chkForm(oj_or_str,type,optRemoveBlank,optAlert,optFocus){
	var trgStr,chkPtt,alertMsg="<?=lang_get('time_msg_2')?>",fLength=true;
	optRemoveBlank=(optRemoveBlank)? optRemoveBlank : false;
	optAlert= optAlert || false;
	optFocus= optFocus || false;


	if(typeof oj_or_str =="object"){
		trgStr=(optRemoveBlank)? oj_or_str.value.replace(/^\s+|\s+$/g,"") : oj_or_str.value;
	}else{
		trgStr=(optRemoveBlank)? oj_or_str.replace(/^\s+|\s+$/g,"") : oj_or_str;
	}
	if(!trgStr) return false;


	switch(type){
		case "ip":
			var ipStrRegex="(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.){2}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)";
			strRegex="^" + ipStrRegex + "$";
			chkPtt=new RegExp(strRegex);
			var ipMatch=trgStr.match(ipStrRegex);
			if(ipMatch===null) break;
			if(ipMatch[1]==0 || parseInt(ipMatch[1],10)>223 || ipMatch[2]==0 || ipMatch[2]==255) fLength=false;
			//chkPtt=/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
			break;
		case "ipNfs":
			if(trgStr=='*') return true;
			var ipStrRegex="(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.){2}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)";
			strRegex="^" + ipStrRegex + "$";
			chkPtt=new RegExp(strRegex);
			var ipMatch=trgStr.match(ipStrRegex);
			if(ipMatch===null) break;
			if(ipMatch[1]==0 || ipMatch[2]==0 || ipMatch[2]==255){
				fLength=false;
				alertMsg="<?=lang_get('nfs_msg_2')?>";
			}
			//chkPtt=/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
			break;
		case "url":
			var ipStrRegex="(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)";
			var urlStrRegex = "(https?:\/\/)"
				+ "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //user@
				+ "("
				+ ipStrRegex // IP
				+ "|" // allows either IP or domain
				+ "([0-9a-z_!~*'()-]+\.)*" // tertiary domain(s)- www.
				+ "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // second level domain
				+ "[a-z]{2,6})" // first level domain- .com or .museum
				+ "(:[0-9]{1,4})?" // port number- :80
				+ "((\/?)|" // a slash isn't required if there is no file name
				+ "(\/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+\/?)";
			strRegex="^" + urlStrRegex + "$";
			chkPtt=new RegExp(strRegex);
			//chkPtt=/^(https?:\/\/)?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|([0-9a-z_!~*'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((\/?)|(\/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+\/?)$/;
			break;
		case "email":
			strRegex = "^([a-zA-Z0-9_.-]+)@((\[[0-9]{1,3}"
				+ "\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\\"
				+ ".)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$";
			chkPtt=new RegExp(strRegex);
			break;
		case "smtpemail":
			strRegex = "^([a-zA-Z0-9_.-]+)@((\[[0-9]{1,3}"
				+ "\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\\"
				+ ".)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$";
			chkPtt=new RegExp(strRegex);
			if(trgStr.length>40) fLength=false;
			break;
		case "id":
			chkPtt=/^\w+$/;
			if(trgStr.length>12) fLength=false;
			break;
		case "userid":
			chkPtt=/^[a-z0-9_-]+$/;
			if(trgStr.length>12) fLength=false;
			break;
		case "smtpid":
			chkPtt=/^(\w+)$|^(([a-zA-Z0-9_.-]+)@(([[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?))$/;
			if(trgStr.length>36) fLength=false;
			break;
		case "ddnsid":
            chkPtt=/^[a-zA-Z0-9]+(?:[a-zA-Z0-9]|-(?!-))*$/;
		    if(trgStr.substr(trgStr.length-1)=='-') fLength=false;
			if(trgStr.length<3||trgStr.length>24) fLength=false;
			break;
		case "pw":
			chkPtt=/^[^%&\\'"#=;: ]+$/;
			break;
		case "createpw":
			chkPtt=/^[^%&\\'"#=;: ]+$/;
			if(trgStr.length<6||trgStr.length>20) fLength=false;
			break;
		case "workgroup":
			chkPtt=/^\w+$/;
			if(trgStr.length<3 || trgStr.length>15) fLength=false;
			break;
		case "domain":
			chkPtt=/^\w+$/;
			if(trgStr.length<3 || trgStr.length>25) fLength=false;
			break;
		case "ddnsdomain":
			chkPtt=/^([0-9a-z-]{1,24}\.)(?:[0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6}$/;
			break;
		case "desc":
			chkPtt=/^\w[a-zA-Z0-9 _-]+$/;
			//chkPtt=/^[^`!@#$%^&*()_=+|\\\[\]\{\};:'"<>\/-]+$/;
			if(trgStr.length>128) fLength=false;
			break;
		case "userdesc":
			chkPtt=/^[^%&\\'"#=;:]+$/;
			if(trgStr.length>128) fLength=false;
			break;
		case "name":
			chkPtt=/^[^`~!@#$%^&*()_=+|\\\[\]\{\};:'"<>\/-]+$/;
			if(trgStr.length>64) fLength=false;
			break;
		case "groupname":
			chkPtt=/^\w+$/;
			break;
		case "foldername":
			chkPtt=/^[^%&\\'"#=;:.]+$/;
			//chkPtt=/^\w+$/;
			if(trgStr.length>128 || trgStr.substr(0,1)==" " || trgStr.substr(trgStr.length-1)==" ") fLength=false;
			alertMsg="<?=lang_get('user_msg_14')?>".replace('&quot;','\",=,;,#,.');
			break;
		case "dirpathNfs":
			if(trgStr.charAt(0)=='/') trgStr=trgStr.substr(1);
			var dirs=trgStr.split('/');
			chkPtt=/^[a-zA-Z0-9_\/-]+$/;
			chkPttDir=/^[a-zA-Z0-9_-]+$/;
			for(var i=0;dirs[i];i++){
				if(dirs[i]==='') continue;
				if(!chkPttDir.test(dirs[i]) || dirs[i].length<3 || dirs[i].length>24){
					fLength=false;
					alertMsg="<?=lang_get('nfs_msg_3')?>";
					break;
				}
			}
			break;
		case "rsyncpath":
			chkPtt=/^.+$/;
			if(trgStr.length>64) fLength=false;
			break;
		case "hostname":
			chkPtt=/^[a-zA-Z0-9][a-zA-Z0-9_-]+$/;
			if(trgStr.length<3 || trgStr.length>12) fLength=false;
			break;
		case "taskname":
			chkPtt=/^\w+$/;
			if(trgStr.length<3 || trgStr.length>15) fLength=false;
			break;
		case "discname":
			chkPtt=/^\w+$/;
			break;
		case "number":
			chkPtt=/^\d+$/;
			break;
		default:
	}


	if(!fLength || !chkPtt.test(trgStr)){
		if(optAlert) alert(alertMsg);
		if(optFocus && typeof oj_or_str == "object") oj_or_str.focus();
		return false;
	}else{
		return true;
	}
}

function FormCheckFolder(id) {
	if(!(not_valid_desc(document.getElementById(id)))) {
		var msg = "<?php echo lang_get('user_msg_14')?>".replace('&quot;','\",=,;,:,#');
		alert(msg);
		document.getElementById(id).value = "";
		document.getElementById(id).focus();
		return false;
	}
	if(document.getElementById(id).value.search(/^\s+|\s+$/)!=-1){
		alert("A folder name starting or finishing with blank is not allowed!");
		document.getElementById(id).value = "";
		document.getElementById(id).focus();
		return false;
	}
	if(document.getElementById(id).value.length>128){
		alert("Long filename!");
		document.getElementById(id).focus();
		return false;
	}
	return true;
}








function FormCheck(id) {

	if(id=="SMTP_SERVER"){
		//Desc.: checking input string is right as service server address
		//	":" is required to input a port number
		if(!document.getElementById(id).value) return false;
		if(!(not_valid_desc_for_mode("server",document.getElementById(id)))) {
			var msg = "<?php echo lang_get('user_msg_14')?>".replace('&quot;','\",=,;,#');
			alert(msg);
			document.getElementById(id).value = "";
			document.getElementById(id).focus();
			return false;
		}
		return true;
	}

	if(!(not_valid_desc(document.getElementById(id)))) {
		var msg = "<?php echo lang_get('user_msg_14')?>".replace('&quot;','\",=,;,:,#');
		if(id!="idInPw") alert(msg);
		document.getElementById(id).value = "";
		document.getElementById(id).focus();
		return false;
	}
	return true;
}
function not_valid_desc(input) {
    	var chars = "%&\\'\"#=\;\:";
	return containsCharsAny(input,chars);
}
function not_valid_desc_for_mode(mode,input) {
	switch(mode){
		case "server":
			var pattern=/^([a-z0-9]+\.)*([a-z0-9]+)(\:\d+)?$/;
			if(input.value.search(pattern)==-1) return false;
			else return true;
			break;
		case "port":
			pattern=/^\d+$/;
			if(input.value.search(pattern)>-1){
				var port=input.value.match(pattern);
				if(port>=0 && port<=65535) return true;
			}
			return false;
			break;
		case "email":
			var pattern=/^[a-z0-9]+@([a-z0-9]+\.)*([a-z0-9]+)(\:\d+)?$/;
			if(input.value.search(pattern)==-1) return false;
			else return true;
			break;
		case "number":
			var pattern=/^\d+$/;
			if(input.value.search(pattern)==-1) return false;
			else return true;
			break;
	}
}

function containsCharsAny(input,chars) {

    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) != -1)
           	return false;
    	}
    	return true;
}

function FormCheckNumeric(id) {

   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;

   if(document.getElementById(id).value.charAt(0)=='0' && 1 < document.getElementById(id).value.length ) {
      document.getElementById(id).value = "";
      IsNumber = false;
      return IsNumber;
   }

   for (i = 0; i < document.getElementById(id).value.length && IsNumber == true; i++)
      {
      Char = document.getElementById(id).value.charAt(i);
      if (ValidChars.indexOf(Char) == -1)
         {
         document.getElementById(id).value = "";
         IsNumber = false;
         }
      }
   return IsNumber;
}

function FormCheck_PW(id) {
	if(!(not_valid_passwd(document.getElementById(id)))) {
		_msg = "<?php echo lang_get('user_msg_14')?>";
		alert(_msg.replace('&quot;','\"'));	
		document.getElementById(id).value = "";
		return false;
	}
	//alert('The entered Desc Ok');	
	return true;
}
function not_valid_passwd(input) {
    	var chars = " %&\\'\"";
	return containsCharsAny(input,chars);
}



//=======================================================//
// Control elements
//=======================================================//
function vis_ctl(oj_id,op)
{
	document.getElementById(oj_id).style.visibility=op;
}
function dis_ctl(oj_id,op)
{
	document.getElementById(oj_id).style.display=op;
}
function show_text(id,msg)
{
	document.getElementById(id).innerHTML = msg;
}


/*************** File Browsing ***************************************/
//=======================================================//
// File Browsing
//=======================================================//
var current_path = ''; //currently refered directory path
var is_loading = false;
var remote_php_path = '../php/browsing_remote.php';

Array.prototype.in_array = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}

Array.prototype.reallen = function() {
	var count = 0;
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i]) {
			count++;
		}
	}
	return count;
}

Array.prototype.el_sum = function(el_name) {
	var sum = 0;
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i]) {
			sum += parseInt(eval('this[i].'+el_name));
		}
	}
	return sum;
}

String.prototype.escapeHTML = function () {                                       
    return(                                                                 
        this.replace(/&/g,'&amp;').                                         
            replace(/>/g,'&gt;').                                           
            replace(/</g,'&lt;').                                           
            replace(/\"/g,'&quot;').                                       
            replace(/\s/g,'&nbsp;')                                        
    );                                                                     
};

//extract only file name from path
function getBaseName(filepath){
	var last_backslash = filepath.lastIndexOf("\\");
	var last_slash = filepath.lastIndexOf("\/");
	var last_separator = null;
	if(last_backslash>last_slash){
		last_separator = last_backslash;
	}else{
		last_separator = last_slash;
	}
	if(last_separator<0){
		return 'unknown file';
	}
	var file_name = filepath.substr(last_separator+1);
	return file_name;
}

//position of given object(Y-cordinate)
function getObjTopPos(Obj)
{
  var total_height = window.innerHeight;
  var returnValue = Obj.offsetTop;
  while((Obj = Obj.offsetParent) != null){returnValue += Obj.offsetTop-Obj.scrollTop;}
  
  return returnValue;
}

//position of given object(X-cordinate)
function getObjLeftPos(Obj)
{ 
  var total_width = window.innerWidth;
  var returnValue = Obj.offsetLeft+Obj.offsetWidth;
  while((Obj = Obj.offsetParent) != null)returnValue += Obj.offsetLeft;
  return returnValue;
}

function sg_quote_escape(str){
	return str.replace('\'','\\\'');
}

function displayError(){
	_msg = "<?php echo lang_get('burning_msg_30')?>";
	alert(_msg);
	is_loading = false;
}

//convert size to byte
function bytesHumanReadable(bytes){
	var ret_val = '';
	var K = 1024;
	var M = 1024 * 1024;
	var G = 1024 * 1024 * 1024;
	var T = 1024 * 1024 * 1024 * 1024;
	bytes = parseInt(bytes);
	if(bytes<K){ 
		ret_val = bytes;
	}else if(bytes<M){ //K
		ret_val = soft_round(bytes/K,2)+'K';
	}else if(bytes<G){ //M
		ret_val = soft_round(bytes/M,2)+'M';
	}else if(bytes<T){ //G
		ret_val = soft_round(bytes/G,2)+'G';
	}else{ //T
		ret_val = soft_round(bytes/T,2)+'T';
	}
	return ret_val;
}

//round-up
function soft_round(val,precision){
	var result = Math.round(val*Math.pow(10,precision));
	result = result/Math.pow(10,precision);
	return result;
}


//display loading image
function showLoadingImage(){
	/*
	$('loading_image').style.display = 'block';
	is_loading = true;
	return true;
	*/
}


//when request finished, hide loading image
function hideLoadingImage(){
	/*
	setTimeout("$('loading_image').style.display = 'none'", 500);
	is_loading = false;
	return true;
	*/
}

//bylee class name find
//apply schedule backup - backup history 'filename' class finding
function byClass(name, type){
	var r = [];
	var t = document.getElementsByTagName(type || "*");
	for(var i = 0; i < t.length; i++){
		if(t[i].className == name){
			r.push(t[i]);
		}
	}
	return r;
}
// next, parent function used for nfs
//apply : function get_nfs_set_list_values() nfs.js.php
function next( ele ){
	ele = ele || document;
	do{
		ele = ele.nextSibling;
	}
	while ( ele != null && ele.nodeType != 1);
	return ele;
}
function parent( elem, num ) {
    num = num || 1;
    for ( var i = 0; i < num; i++ )
        if ( elem != null ) elem = elem.parentNode;
    return elem;
}
//-->
