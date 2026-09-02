
// flashWrite(파일경로, 가로, 세로, 아이디, 배경색, 윈도우모드)
function flashWrite(url,w,h,id,bg,win,scale,salign){ 

 // 플래시 코드 정의
var flashStr=
"<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' width='"+w+"' height='"+h+"' id='"+id+"' align='middle'>"+
"<param name='movie' value='"+url+"' />"+
"<param name='wmode' value='transparent'>"+
"<param name='menu' value='false'>"+
"<param name='quality' value='high'>"+
"<param name='bgcolor' value='"+bg+"' />"+
"<param name='scale' value='"+scale+"'>"+
"<param name='salign' value='"+salign+"'>"+
"<param name='allowScriptAccess' value='always'>"+
"<embed src='fla/main_visual.swf' width='"+w+"' height='"+h+"' type='application/x-shockwave-flash' allowScriptAccess='always' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed>"+
"</object>";
 
 // 플래시 코드 출력
 document.write(flashStr);
 
}


///////////////////////////////////////////////
///////////////// 플리시 링크 /////////////////
///////////////////////////////////////////////


// MCG 자사 사이트
function GNB(num)
{
	switch(num)
	{
		// 상단 유틸리티
		case "0": document.location.href="/"; //홈으로 home
			break;
		case "012": document.location.href="/contact/contact_01.asp"; //어바웃 about mcg 
			break;
		case "013": document.location.href="/sitemap/sitemap_01.asp"; //상단   sitemap
			break;



		// MCG	
		case "11": document.location.href="/about/about_01.asp";// overview
			break;
		case "12": document.location.href="/about/about_02.asp";//조직도
			break;
		case "13": document.location.href="/about/about_03.asp";//사업영역
			break;
		case "14": document.location.href="/about/about_04.asp";// Partner/Clients
			break;
		case "15": document.location.href="/about/about_05.asp";//위치
			break;
		
	
		// business
		case "20": document.location.href="/biz/biz_01.asp";//Metagate
			break;
		case "21": document.location.href="/biz/biz_02.asp";//C3
			break;
		case "22": document.location.href="/biz/biz_03.asp";//Consulting
			break;
		case "23": document.location.href="/biz/biz_04.asp";//EWS
			break;
		case "24": document.location.href="/biz/biz_05.asp";//Credit Doctor
			break;
		case "25": document.location.href="/biz/biz_06.asp";//거래처관리시스템
			break;

			// information
		case "26": document.location.href="/infor/info_01.asp";//공지사항
			break;
		case "27": document.location.href="/infor/info_view.asp";//공지사항 글보기
			break;
		case "28": document.location.href="/infor/info_write.asp";//공지사항 글쓰기
			break;
		case "29": document.location.href="/infor/info_02.asp";//자료실
			break;
		case "30": document.location.href="/infor/info_03.asp";//채용정보
			break;
		

		// contact us
		case "31": document.location.href="/contact/contact_01.asp";//연락처
			break;
		case "32": document.location.href="/contact/contact_02.asp";//고객지원
			break;
		
		
	
		// clients
		case "41": document.location.href="/clients_01.asp";//고객기업
			break;
		case "42": document.location.href="/_Customer/Inquiry.aspx";//고객의소리
			break;
		case "43": document.location.href="/_Board/List.aspx?s=QnA";//레이크힐스 Q&A
			break;
		case "44": document.location.href="/_Board/FAQ.aspx?s=FAQ";//FAQ
			break;

		// 로그인
		case "51": document.location.href="/_Member/Login.aspx";//로그인
			break;
		case "52": document.location.href="/_Member/Join.aspx";//회원가입
			break;
		case "53": document.location.href="/_Member/Join.aspx?m=Find";//아이디/패스워드 찾기
			break;
		case "54": document.location.href="/etc/protect.html";//개인정보보호정책
			break;
			
		
			

	}
}



///////////////////////////////////////////////
///////////////// 팝업창 설정 /////////////////
///////////////////////////////////////////////

// 해당쿠키값 가져오기
function GetCookie(name) {
	var nameOfCookie=name+'=';
	var x=0;
	while(x<=document.cookie.length) {
		var y=(x+nameOfCookie.length);
		if(document.cookie.substring(x,y)==nameOfCookie ) {
			if((endOfCookie=document.cookie.indexOf(';',y))==-1) endOfCookie = document.cookie.length;
			return unescape(document.cookie.substring(y,endOfCookie));
		}
		x=document.cookie.indexOf(' ',x)+1;
		if(x==0) break;
	}
	return '';
}

function openPopUp(ckName, filename,p_width,p_height,scrll,l,t) {
	var left = l ? l : 0;
	var top = t ? t : 0;

	if (GetCookie(ckName) != "done") {
		window.open(filename, ckName,"resizable=no,scrollbars="+scrll+",width="+p_width+",height="+p_height+",left=" + left + ", top=" + top);
	}
}


// 웰페이퍼 이미지 사이즈 조절
function WinOpen(filename,p_width,p_height,scrll)
{
	
	var filename,p_width,p_height,scrll,search,winLeft,winTop	
	var winLeft = (screen.width - p_width) / 2; 
	var winTop = (screen.height - p_height) / 2; 

	win = window.open(filename, "","resizable=no,scrollbars="+scrll+",width="+p_width+",height="+p_height+",left="+winLeft+",top="+winTop);
}


//웰페이퍼 이미지 팝업_새창 여는 스크립트///
winObj=null; // 초기화
function WinOpen(imgname)
{
 img = new Image();
 img.src=imgname; 
 draw();
}
     
function draw()
{
 if(img.complete == false) 
 {
  setTimeout("draw()", "100");
  return;
 }
     
 width=img.width //+ 20; //이미지 너비값
 height=img.height// + 25; //이미지 높이 값
      
 var attr ="width="+width + ",height="+height
      
// 윈도우를 열고 이미지 출력.
 if(winObj != null && winObj.closed == false) 
 { 
  winObj.close(); 
 }
      
 winObj = window.open("","imgwindow",attr + ',top=200' + ',left=400' );
 winObj.document.open()
 winObj.document.write("<html><body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>")
 winObj.document.write("<img alt='닫기' src=" + img.src + " style='cursor: hand;'" + " onClick='self.close()'" )
 winObj.document.write("<body><html>")
 winObj.document.close()
} 



//////////////////////
// 2007-05-29 
// 모든개체 가져오기
// ////////////////////
function GetObject(ObjectId) {
	 if (document.getElementById && document.getElementById(ObjectId)) {
	  return document.getElementById(ObjectId);
	 } else if (document.getElementByName && document.getElementByName(ObjectId)) {
	  return document.getElementByName(ObjectId);
	 } else if (document.all && document.all(ObjectId)) {
	  return document.all(ObjectId);
	 } else if (document.layers && document.layers[ObjectId]) {
	  return document.layers[ObjectId];
	 } else {
	  return false;
	 }
}

