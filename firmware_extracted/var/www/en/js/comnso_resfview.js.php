<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>

var xmlHttp = createXmlHttpRequestObject();
var serverAddress = "../php/comnso_resfview.php";
var showErrors = true;
var cache = new Array();

//XMLHttpRequest instance
function createXmlHttpRequestObject() 
{
	var xmlHttp;
	try 
	{
		xmlHttp = new XMLHttpRequest();
	}catch(e) 
	{
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
		"MSXML2.XMLHTTP.5.0",
		"MSXML2.XMLHTTP.4.0",
		"MSXML2.XMLHTTP.3.0",
		"MSXML2.XMLHTTP",
		"Microsoft.XMLHTTP");
		// try every id until one works
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) 
		{
			try 
			{
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}catch (e) {}
		}
	}
	
	if (!xmlHttp)
	{
	    displayError("Error creating the XMLHttpRequest object.");
	}else 
	{
	    return xmlHttp;
	}
}

// error message
function displayError($message) 
{
	if (showErrors) 	
	{
		showErrors = false;
		debug("Error encountered: \n" + $message);
		setTimeout("browse();", 10000);
	}
}

// the function handles the validation for any form field
function browse(inputValue, fieldID) 
{
	if (xmlHttp) 
	{
		if (fieldID) 
		{
		    // swkim code
		    // 상단에 선택한 패스를 보여준다.
            var sel_path;
            sel_path = "<b>";
            sel_path += fieldID;
            sel_path += "</b>";
            if(document.getElementById("sel_path")){
                document.getElementById("sel_path").innerHTML = sel_path;
                document.getElementById("sel_path").path = fieldID;
            }
		
		    var opentag="dir_open";
		    if(inputValue == "fixed")
		    {
		        return;
		    }else if (inputValue == "close") 
			{
				document.getElementById(fieldID).title = "open";
				document.getElementById(fieldID + "Info").innerHTML = "";
				if (document.getElementById("image" + fieldID))
				{
				    document.getElementById("image" + fieldID).src = "../images/comnso/cms_folder.gif";
				}
				opentag="no";
			}else
			{
				document.getElementById(fieldID).title = "close";
			}
			
			inputValue = encodeURIComponent(inputValue);
			fieldID = encodeURIComponent(fieldID);
			
			cache.push("inputValue=" + inputValue + "&fieldID=" + fieldID + "&opentag=" + opentag);
		}
		
		// try to connect to the server
		try	
		{
			if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && cache.length > 0) 
			{
				// get a new set of parameters from the cache
				var cacheEntry = cache.shift();
				
				// make a server request to validate the extracted data
				xmlHttp.open("POST", serverAddress, true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(cacheEntry);
			}
			
			if (xmlHttp.readyState == 1)
			{
				//document.getElementById("busy").innerHTML = "<h1>Busy...</h1>";
			}else
			{
				//document.getElementById("busy").innerHTML = "<h1>ok</h1>";
			}
		}catch (e) 
		{
			displayError(e.toString());
		}
	}
}

String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
    return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
    return this.replace(/\s+$/,"");
}

function search() 
{
	if (xmlHttp) 
	{
		if (document.getElementById("cms_search_text")) 
		{
		    var strsearch=document.getElementById("cms_search_text").value;
		    if(strsearch.trim() == "")
		    {
		        debug("Empty search word!");
		        return;
		    }
			cache.push("inputValue=0&fieldID=0&search=" + strsearch + "&opentag=search_open");
		}
		
		// try to connect to the server
		try	
		{
			if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && cache.length > 0) 
			{
				// get a new set of parameters from the cache
				var cacheEntry = cache.shift();
				
				// make a server request to validate the extracted data
				xmlHttp.open("POST", serverAddress, true);
				xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(cacheEntry);
			}
			
			if (xmlHttp.readyState == 1)
			{
			    if(document.getElementById("busy")){
				    //document.getElementById("busy").innerHTML = "<h1>Search...</h1>";
				}
			}else
			{
			    if(document.getElementById("busy")){
				    //document.getElementById("busy").innerHTML = "<h1>ok</h1>";
				}
			}
		}catch (e) 
		{
			displayError(e.toString());
		}
	}
}

// function that handles the HTTP response
function handleRequestStateChange() 
{
	// when readyState is 4, we read the server response
	if (xmlHttp.readyState == 4) 
	{
		// continue only if HTTP status is "OK"
		if (xmlHttp.status == 200) 
		{
			try	
			{
				readResponse();
			}catch(e) 
			{
				//displayError(e.toString());
			}
		}else 
		{
			displayError(xmlHttp.statusText);
		}
	}
}

function replaceAll( str, from, to ) 
{
    var idx = str.indexOf( from );


    while ( idx > -1 ) 
    {
        str = str.replace( from, to ); 
        idx = str.indexOf( from );
    }

    return str;
}

// read server's response
function readResponse() 
{
	// retrieve the server's response
	var response = xmlHttp.responseText;
	//debug(response);
	//return;
	amp = new RegExp("&amp;", "g");

	// server error?
	if (response.indexOf("ERRNO") >= 0
			|| response.indexOf("error:") >= 0
			|| response.length == 0)
	{
	    throw(response.length == 0 ? "Server error." : response);
	}
    
	// get response in XML format (assume the response is valid XML)
	responseXml = xmlHttp.responseXML;
	
	// get the document element
	xmlDoc = responseXml.documentElement;
	
	/* Work around Firefox (Gecko?) limitation where it shows only the first 4096
	 * bytes of data. Ref: http://www.thescripts.com/forum/thread482760.html
	 */
	xmlDoc.normalize();
	
	try
	{
        rstate = xmlDoc.getElementsByTagName("state")[0].firstChild.data;
        rstate = rstate.substr(2); // object error 방지를 위해 -- 임시 문자를 삽입하였고 --를 제거한다.

        result = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
        result = result.substr(2); // object error 방지를 위해 -- 임시 문자를 삽입하였고 --를 제거한다.
        
        
        filelist = xmlDoc.getElementsByTagName("filelist")[0].firstChild.data;
        filelist = filelist.substr(2); // object error 방지를 위해 -- 임시 문자를 삽입하였고 --를 제거한다.
        
        fieldID = xmlDoc.getElementsByTagName("fieldid")[0].firstChild.data;
	    
    }catch(e) 
	{
	    debug(e.toString());
	}
    
    if (response.indexOf("BLANK_NOSUB") >= 0){
        //document.getElementById("busy").innerHTML = "<h1>ok</h1>";
        if(document.getElementById("image" + fieldID)){
            document.getElementById("image" + fieldID).src = "../images/comnso/cms_folder.gif";
        }        
    }else if (rstate.indexOf("BLANK_CLOSE") >= 0){
        //document.getElementById("busy").innerHTML = "<h1>ok</h1>";
        if(document.getElementById("image" + fieldID)){
            document.getElementById("image" + fieldID).src = "../images/comnso/cms_folder.gif";
        }    
    }else if (rstate.indexOf("BLANK_SEARCH") >= 0){
    }else{
        if(document.getElementById("image" + fieldID)){
            document.getElementById("image" + fieldID).src = "../images/comnso/cms_back.gif";
        }
        
	    // find the HTML element that displays the error
	    //result = result.replace("amp", "&");
	    message = document.getElementById(fieldID + "Info");
	    message.innerHTML = result;
	    
	    //***************** Add **********************************************************/
	    var _tmp = document.getElementById(fieldID + "Info").getElementsByTagName('IMG');
	    chk_info.check(_tmp,fieldID);
	    /*********************************************************************************/
    }
    
	// 해당 폴더에 파일을 보여준다.
	message = document.getElementById("reslist");
	//debug(filelist);
	
	message.innerHTML = filelist;
	document.getElementById("lng_name").innerHTML = "<?php echo lang_get('common_name')?>";
	document.getElementById("lng_size").innerHTML = "<?php echo lang_get('schedule_restore_1')?>";
	document.getElementById("lng_filedate").innerHTML = "<?php echo lang_get('schedule_restore_11')?>";
	document.getElementById("lng_backupdate").innerHTML = "<?php echo lang_get('schedule_restore_12')?>";
	
	//message.style = "block";
	
	// 테이블 상단에 캡션을 바꾼다.
	//lang_init();
	
	
	
	// show the error or hide the error
	//message.className = (result == "0") ? "error" : "hidden";
	// call validate() again, in case there are values left in the cache
	setTimeout("browse();", 500);
}