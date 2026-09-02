var res_xmlHttp = res_createXmlHttpRequestObject();
var res_serverAddress = "../php/comnso_app_restore.php";
var res_showErrors = true;
var res_cache = new Array();
var g_document;
var msgtime=-1;
var g_press=10;

//XMLHttpRequest instance
function res_createXmlHttpRequestObject() 
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
	    res_displayError("Error creating the XMLHttpRequest object.");
	}else 
	{
	    return xmlHttp;
	}
}

// message check
function msgcheck()
{
	// 진행 상황을 얻는다.
	$.get("../php/comnso_res_keyread.php?key=runmsg", function(strmsg){
		var nidx;
		nidx = strmsg.indexOf("ing:");
		if(nidx != -1){
			var percent;
			percent = parseInt(strmsg.substr(nidx+4));
			
			strmsg  = (percent/100)*240;
			strmsg  = strmsg.toFixed(0);
			strmsg  += "px";
			
			percent = percent.toFixed(0);
			percent += "%&nbsp;&nbsp;";
			
			document.getElementById("prssimg").style.width=strmsg;
			document.getElementById("prsstxt").innerHTML=percent;
		}
	});
	
	// 메세지 또는 질문을 얻어 보여준다.
	$.get("../php/comnso_res_keyread.php?key=phpmsg", function(strmsg){
		var bRet;
		var nidx;
		nidx = strmsg.indexOf("qus:");
		if(nidx != -1){
			strmsg = strmsg.substr(nidx+4);
			document.getElementById("phpmsg").innerHTML = strmsg;
			document.getElementById("msgimg").innerHTML = "<img src='../images/comnso/cms_icon_qus.gif'>";
		}else{
			nidx = strmsg.indexOf("msg:");
			if(nidx != -1){
				strmsg = strmsg.substr(nidx+4);
				document.getElementById("phpmsg").innerHTML = strmsg;
				document.getElementById("msgimg").innerHTML = "<img src='../images/comnso/cms_icon_info.gif'>";
			}
		}

		if(msgtime != -1){
			msgtime = setTimeout("msgcheck();", 500);
		}
	});
}

function restore_cancel(){
	clearTimeout(msgtime);
	msgtime = -1;
	setcancelmsg();
	window.close();
}

function setcancelmsg()
{
	$.get("../php/comnso_res_keywrite.php?k0=phpmsg&v0=&k1=appmsg&v1=cancel", function(strmsg){
	});
}

// error message
function res_displayError($message) 
{
	if (res_showErrors) 	
	{
		res_showErrors = false;
		alert("Error encountered: \n" + $message);
		setTimeout("browse();", 10000);
		
		clearTimeout(msgtime);
		msgtime = -1;		
	}
}

// the function handles the validation for any form field
function restore() 
{
	if (res_xmlHttp) 
	{
		// try to connect to the server
		try	
		{
			res_cache.push("chkval=cms");
			if ((res_xmlHttp.readyState == 4 || res_xmlHttp.readyState == 0) && res_cache.length > 0) 
			{
				var cacheEntry = res_cache.shift();
				
				// make a server request to validate the extracted data
				res_xmlHttp.open("POST", res_serverAddress, true);
				res_xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				res_xmlHttp.onreadystatechange = res_handleRequestStateChange;
				res_xmlHttp.send(cacheEntry);
				msgtime = setTimeout("msgcheck();", 1000);
			}
		}catch (e) 
		{
			res_displayError(e.toString());
		}
	}
}

function getCenterWinStr(width, height)
{
	var str = "";
	str = "height=" + height + ",innerHeight=" + height;
	str += ",width=" + width + ",innerWidth=" + width;

	if (window.screen) 
	{
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;

		var xc = (aw - width) / 2;
		var yc = (ah - height) / 2;

		str += ",left=" + xc + ",screenX=" + xc;
		str += ",top=" + yc + ",screenY=" + yc;
	}

	return str;
}

// function that handles the HTTP response
function res_handleRequestStateChange() 
{
	// when readyState is 4, we read the server response
	if (res_xmlHttp.readyState == 4) 
	{
		// continue only if HTTP status is "OK"
		if (res_xmlHttp.status == 200) 
		{
			try	
			{
				res_readResponse();
			}catch(e) 
			{
				//displayError(e.toString());
			}
		}else 
		{
			res_displayError(res_xmlHttp.statusText);
		}
	}
}

// read server's response
function res_readResponse() 
{
	// 마지막 호출.
	msgcheck();
	
	// 타이머를 제거한다.
	clearTimeout(msgtime);
	msgtime = -1;
	
	// 복원 100%
	document.getElementById("prssimg").style.width="240px";
	document.getElementById("prsstxt").innerHTML="100%&nbsp;&nbsp";	
	
	// retrieve the server's response
	//var response = res_xmlHttp.responseText;
	//alert(response);
}