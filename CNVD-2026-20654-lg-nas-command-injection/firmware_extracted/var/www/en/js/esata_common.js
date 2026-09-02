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

function containsCharsAny(input,chars) {

    	for (var inx = 0; inx < input.value.length; inx++) {
		//alert(chars.indexOf(input.value.charAt(inx)));
       		if (chars.indexOf(input.value.charAt(inx)) != -1)
           	return false;
    	}
    	return true;
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

var current_path = ''; //currently refered directory path
//var is_loading = false;
var remote_php_path = '../php/burning_brows_remote.php';

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
	//alert('Request was failed.');
	is_loading_nas = false;
	is_loading_esata = false;
	is_refresh_esata = false;
	is_refresh_nas = false;
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