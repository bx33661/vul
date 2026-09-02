// flash.js 파일로 만들어서 저장
function set_Embed() 
{ 
  var obj = new String; 
  var parameter = new String; 
  var embed = new String; 
  var html = new String; 
  var allParameter = new String; 
  var clsid = new String; 
  var codebase = new String; 
  var pluginspace = new String; 
  var embedType = new String; 
  var src = new String; 
  
  var width = new String;
  var height = new String;

  var ServerIp = new String;
  var UserId = new String;
  var PassiveMode = new String;
  var Port = new String;
  var Status = new String;
  var Banner = new String;
  var ECHosting = new String;
  var FilelinkService = new String;
  var FilelinkServer = new String;

  this.init = function( s ,w , h, getType ) { 
      getType = (getType != undefined)? getType :'flash'; 
      if ( getType == "flash") 
      { 
        clsid = "D27CDB6E-AE6D-11cf-96B8-444553540000";        
        codebase = "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0"; 
        pluginspage = "http://www.macromedia.com/go/getflashplayer"; 
        embedType = "application/x-shockwave-flash"; 

        parameter += "<param name='movie' value='"+ s + "'>\n";  
        parameter += "<param name='quality' value='high'>\n";    
        parameter += "<Param name='bgcolor' value=#FFFFFF>\n";

      }       
      else if ( getType == 'webftp') 
      { 
        clsid = "EF256D78-3982-4F12-900B-AD8B254A43BD";        
        codebase = "http://echosting.cafe24.com/ftpclient/Cafe24FtpCtl21.cab#version=1,0,2,7";               
      }
      else if ( getType == 'filelinkftp') 
      { 
        clsid = "EF256D78-3982-4F12-900B-AD8B254A43BD";        
        codebase = "http://echosting.cafe24.com/ftpclient/Cafe24FtpCtl14.cab#version=1,0,2,4";               
      }
      
            
      
      src = s; 
      width = w; 
      height = h; 
  } 
  
  this.parameter = function( parm , value ) {      
      parameter += "<param name='"+parm +"' value='"+ value + "'>\n";        
      allParameter += " "+parm + "='"+ value+"'";       
  }  
  
  this.show = function(getType) { 
      if ( clsid) 
      { 
        obj = "<object classid=\"clsid:"+ clsid +"\" codebase=\""+ codebase +"\""; 

        if (width) {
            obj += " width ='" + width + "' ";
        }

        if (height) {
            obj += " height ='" + height + "' ";
        }

        obj += ">\n";
      } 
      
      if ( getType == "flash") {
              embed = "<embed src='" + src + "' pluginspage='"+ pluginspage + "' type='"+ embedType + "'";

        if (width) {
            embed += " width ='" + width + "' ";
        }

        if (height) {
            embed += " height ='" + height + "' ";
        }

        embed += allParameter + " ></embed>\n";
      }

      if (getType == 'streaming') {
              embed = "<embed src='" + src + "' type='"+ embedType + "'";

        if (width) {
            embed += " width ='" + width + "' ";
        }

        if (height) {
            embed += " height ='" + height + "' ";
        }

        embed += allParameter + " ></embed>\n";
      }
      
      if ( obj ) 
      { 
        end_embed = "</object>\n"; 
      } 
      
      if(getType == 'streaming')
              html = embed; 
      else
              html = obj + parameter + embed + end_embed;       
      
      document.write( html );  
  } 
  
} 

//메뉴의 형태를 유지하기위해 쿠키를 사용함. 
function menuGet(){
	//저장된 값을 구해서 플래시에 전달한다.
	str = fgetCookie("fmenu");
	if(str == null){
		str = "0^0^0^0^0^0";
	}
	fthisMovie("leftMenu").setMenu(str);	
}
function menuSet(str){
    //값을 받아서 저장한다.
	fsetCookie( "fmenu", str);	
}

function fthisMovie(movieName) {
    if (navigator.appName.indexOf("Microsoft") != -1) {
        return window[movieName];
    }
    else {
        return document[movieName];
    }
}

function fgetCookie( cookieName )
 {
  var search = cookieName + "=";
  var cookie = document.cookie;
  // 현재 쿠키가 존재할 경우
  if( cookie.length > 0 )
  {
   // 해당 쿠키명이 존재하는지 검색한 후 존재하면 위치를 리턴.
   startIndex = cookie.indexOf( cookieName );
   // 만약 존재한다면
   if( startIndex != -1 )
   {
    // 값을 얻어내기 위해 시작 인덱스 조절
    startIndex += cookieName.length;
    // 값을 얻어내기 위해 종료 인덱스 추출
    endIndex = cookie.indexOf( ";", startIndex );
    // 만약 종료 인덱스를 못찾게 되면 쿠키 전체길이로 설정
    if( endIndex == -1) endIndex = cookie.length;
    // 쿠키값을 추출하여 리턴
    return unescape( cookie.substring( startIndex + 1, endIndex ) );
   }
   else
   {
    // 쿠키 내에 해당 쿠키가 존재하지 않을 경우
    return false;
   }
  }
  else
  {
   // 쿠키 자체가 없을 경우
   return false;
  }
 }
 
 /**
  * 쿠키 설정
  * @param cookieName 쿠키명
  * @param cookieValue 쿠키값
  * @param expireDay 쿠키 유효날짜
  */
 function fsetCookie( cookieName, cookieValue) {
  //var today = new Date();
  //today.setDate( today.getDate() + parseInt( expireDate ) );
  document.cookie = cookieName + "=" + escape( cookieValue ) + "; path=/;";
 }

// JavaScript Document ****************
function fmakeFlashObject(swfURL,w,h,id,flashVars){
	var swfHTML = ('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'+ w +'" height="'+h+'" id="'+id+'" align="middle">');
	swfHTML+=('<param name="allowScriptAccess" value="always" />');
	swfHTML+=('<param name="FlashVars" value="'+ flashVars +'"/>');
	swfHTML+=('<param name="menu" value="false"/>');
	swfHTML+=('<param name="wmode" value="opaque"/>');    // Jongmin	
	swfHTML+=('<param name="movie" value="'+swfURL+'" /><param name="quality" value="high" />');
	swfHTML+=('<embed base="../flash" menu="false" src="'+ swfURL +'"  quality="high" wmode="opaque" FlashVars="'+flashVars+'" bgcolor="#ffffff" width="'+w+'" height="'+h+'" name="'+id+'" align="middle"  allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
	swfHTML+=('</object>');
	document.write(swfHTML);
}