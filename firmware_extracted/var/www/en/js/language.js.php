<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	lang_set_active_language($_GET['lang']);
?>

//=======================================================//
// System / Language
// 11/06/2008
// LGE
// park94
//=======================================================//

var edit = {
	"language" : ["English","한국어","Deutsch","Español","Français","Swedish","Dutch","Danish","Norwegian","Finnish"],
	"make_select" : function(){
		var _tmp = document.getElementById('idTxt_lang').innerHTML;
		
		var _sel = "<select id='id_sel_lang'>";
		_sel += (_tmp == this["language"][0]) ? "<option selected='selected' value='en'>"+this["language"][0]+"</option>":"<option value='en'>"+this["language"][0]+"</option>";
		_sel += (_tmp == this["language"][1]) ? "<option selected='selected' value='kr'>"+this["language"][1]+"</option>":"<option value='kr'>"+this["language"][1]+"</option>";
		_sel += (_tmp == this["language"][2]) ? "<option selected='selected' value='ge'>"+this["language"][2]+"</option>":"<option value='ge'>"+this["language"][2]+"</option>";
		_sel += (_tmp == this["language"][3]) ? "<option selected='selected' value='sp'>"+this["language"][3]+"</option>":"<option value='sp'>"+this["language"][3]+"</option>";
		_sel += (_tmp == this["language"][4]) ? "<option selected='selected' value='fr'>"+this["language"][4]+"</option>":"<option value='fr'>"+this["language"][4]+"</option>";
		_sel += (_tmp == this["language"][5]) ? "<option selected='selected' value='sw'>"+this["language"][5]+"</option>":"<option value='sw'>"+this["language"][5]+"</option>";
		_sel += (_tmp == this["language"][6]) ? "<option selected='selected' value='nl'>"+this["language"][6]+"</option>":"<option value='nl'>"+this["language"][6]+"</option>";
		_sel += (_tmp == this["language"][7]) ? "<option selected='selected' value='dk'>"+this["language"][7]+"</option>":"<option value='dk'>"+this["language"][7]+"</option>";
		_sel += (_tmp == this["language"][8]) ? "<option selected='selected' value='no'>"+this["language"][8]+"</option>":"<option value='no'>"+this["language"][8]+"</option>";
		_sel += (_tmp == this["language"][9]) ? "<option selected='selected' value='fl'>"+this["language"][9]+"</option>":"<option value='fl'>"+this["language"][9]+"</option>";
		_sel += "</select>";
		document.getElementById('idTxt_lang').innerHTML = _sel;
		
		// Client Language
		var _tmp2 = document.getElementById('idTxt_client_lang').innerHTML;
		var _sel2 = "<select id='id_sel_client_lang'>";
	      _sel2 += "<option value='CP437' id='CP437'>CP437 (United States, Canada)</option>";
	      _sel2 += "<option value='CP850' id='CP850'>CP850 (Europe, Latin 1)</option>";
	      _sel2 += "<option value='CP860' id='CP860'>CP860 (Portuguese)</option>";
	      _sel2 += "<option value='CP932' id='CP932'>CP932 (Japanese Shift-JIS)</option>";
	      _sel2 += "<option value='CP936' id='CP936'>CP936 (Simplified Chinese)</option>";
	      _sel2 += "<option value='CP949' id='CP949'>CP949 (Korean)</option>";
	      _sel2 += "<option value='CP950' id='CP950'>CP950 (Traditional Chinese)</option>";
	      _sel2 += "<option value='CP1250' id='CP1250'>CP1250 (Central/Eastern Europe, Latin 2)</option>";
	      _sel2 += "<option value='CP1251' id='CP1251'>CP1251 (Cyrillic)</option>";
	      _sel2 += "<option value='CP1253' id='CP1253'>CP1253 (Greek)</option>";
	      _sel2 += "<option value='CP1254' id='CP1254'>CP1254 (Turkish)</option>";
	      _sel2 += "<option value='CP1255' id='CP1255'>CP1255 (Hebrew)</option>";
	      _sel2 += "<option value='CP1256' id='CP1256'>CP1256 (Arabic)</option>";
	      _sel2 += "<option value='CP1257' id='CP1257'>CP1257 (Baltic Rim)</option>";
	      _sel2 += "<option value='ISO8859-1' id='ISO8859-1'>ISO8859-1 (Latin 1; Western European Languages)</option>";
	      _sel2 += "<option value='ISO8859-2' id='ISO8859-2'>ISO8859-2 (Latin 2; Slavic/Central European Languages)</option>";
	      _sel2 += "<option value='ISO8859-9' id='ISO8859-9'>ISO8859-9 (Latin 5; Turkish)</option>";
	      _sel2 += "<option value='ISO8859-13' id='ISO8859-13'>ISO8859-13 (Latin 7; Baltic)</option>";
	      _sel2 += "<option value='ISO8859-15' id='ISO8859-15'>ISO8859-15 (Latin 9; Western European Languages with Euro)</option>";
	      _sel2 += "<option value='UTF-8' id='UTF-8'>UTF-8 (UTF-8; Unicode)</option>";
	      _sel2 += "</select>";
	      
		  
		  document.getElementById('idTxt_client_lang').innerHTML = _sel2;                             
		  document.getElementById(_tmp2).selected = true;
		
		// Change Button Edit -> Apply
		document.getElementById('table_btn_edit').style.display = "none";
		document.getElementById('table_btn_apply').style.display = "block";
		
		
	} ,
	"select_language" : function(){
		var oj = document.getElementById('id_sel_lang');
		var _i = oj.selectedIndex;
		
		var oj2 = document.getElementById('id_sel_client_lang');
		var _i2 = oj2.selectedIndex;
		//alert(oj.options[_i].text);
		init.set_lang(oj.options[_i].value,oj2.options[_i2].value);
		//init.get_lang();
	}
}
var init = {
	"set_lang" : function(language,client_language){
		var cmd = "&language="+language+"&client_language="+client_language;
		var php = "../php/language_set.php";
		
		// Show Loading Image
		document.getElementById('page_loading').style.display = "block";
		document.getElementById('img_page_loading').src = "../images/Burn/file_box_loading.gif";
		sendRequest(on_set,cmd,'post',php,true,true);
		function on_set(oj){
			var res=decodeURIComponent(oj.responseText);
			//var new_url = "../../"+res+"/system/language.php";
			location.reload();
			//location.href = new_url;
			
			//alert(res);
		}
	},
	"get_lang" : function(){
		<?php 
		
		// get login window language
		$_login_language = trim(exec("sudo cat /var/www/index.html | grep -i 'URL' | cut -d '/' -f 2"));
		
		// Define Permitted Language
		$permitted_language = array("en","kr","sp","ge","fr","sw","dk","nl","no","fl");

		$language_array = array("en" => "English",
														"sp" => "Español",
														"fr" => "Français",
														"kr" => "한국어",
														"ge" => "Deutsch",
														"sw" => "Swedish",
														"nl" => "Dutch",
														"dk" => "Danish", 
														"no" => "Norwegian", 
														"fl" => "Finnish"
														);

		if(!in_array($_login_language,$permitted_language)) {
				$web_default_language = $language_array["en"];
		}
		else{
				$web_default_language = $language_array[$_login_language];
		}
		
		// Get Client Language Information

		// NS1
		//$_client_language = exec("sudo cat /etc/sss_script/share/client_codepage.conf | grep -i 'codepage' | cut -d '=' -f 2");
		// NC1
		$_client_language = exec("sudo nas-system get codepage");
		
		?>
		
		
		var _tmp = "<?=$web_default_language?>";
		var _client_language = "<?=$_client_language?>";
		
		document.getElementById('idTxt_lang').innerHTML = _tmp;
		document.getElementById('idTxt_client_lang').innerHTML = _client_language;
	}
}
/***********************************************************/
//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2','3');
function show_help()
{
	//debug(help);
	switch(help)
	{
		case 1:
		var _win = window.open('../help/system/help_language.html','Help_language','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_language.html','Help_language','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		case 3:
		
		var _win = window.open('../help/system/help_language.html','Help_language','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		default:
		break;
	}

} 