function moveDiv(){
	$('showImage').style.top = document.body.scrollTop + 40;
	
}








var recTree = "recTree"; //트리 아이디
var recMenu = "recMenu"; //파일 정보 아이디

var count=0;

var html = ""; //트리의 구조가 저장된 변수
var pixel = 15; //트리의 왼쪽 여백

var o_f_path = "img/folder_open.gif"; //폴더 오픈
var c_f_path = "img/folder_close.gif"; //폴더 클로즈
var doc_path = "img/page.gif"; //폴더가 아닐때
var minus = "img/minus.gif"; //마이너스 
var plus = "img/plus.gif"; //플러스

var root = null; //사용자가 지정한 루트의 경로

//파일 이미지를 지원하는 확장자들
var imgType = "[zip] [ppt] [pdf] [exe] [asp] [htm] [html] [jpg] [gif] [png] [alz] [doc] [fla] [swf] ";
imgType += "[hwp] [rar] [txt] [xls] [php] [js] [ai] [xml] [css] [tar] [gz]";

function $(obj){
	return document.getElementById(obj);
}

function xmlHttp(url,fun){
	var oXmlHttp = zXmlHttp.createRequest();    
	oXmlHttp.open("get", url, true);
	oXmlHttp.onreadystatechange = function () {
		if (oXmlHttp.readyState == 4) {
			if (oXmlHttp.status == 200) {
				fun(oXmlHttp);
			}
		}
	}
	oXmlHttp.send(null);
}

var _common = {
	sort : function(obj){
		var i= -2; //제일 상단 루트는 제외하므로 -2로 설정한다
		while(obj.parentNode != null){
			obj = obj.parentNode;
			i++;
		}
		return i;
	}
		
}

 
var menu = {
	get : function(path){
		var temp_path = path.split('/');
		var temp_length = temp_path.length
		var str_path = "";
		
		if(temp_path[temp_length-1] == ".."){
				for(i=1;i<temp_length-2;i++) {
					str_path = str_path + "/" + temp_path[i];
					
		  				$('imgEng').innerHTML = "&nbsp;";	
		  				$('imgKor').innerHTML = "&nbsp;";
			  			$('imgSpa').innerHTML = "&nbsp;";
				  		$('imgFre').innerHTML = "&nbsp;";
					  	$('imgGer').innerHTML = "&nbsp;";
							$('imgSwe').innerHTML = "&nbsp;";
							$('imgDen').innerHTML = "&nbsp;";
							$('imgNl').innerHTML = "&nbsp;";
							$('imgNo').innerHTML = "&nbsp;";
							$('imgFl').innerHTML = "&nbsp;";
									

				}
											
		}
		else str_path = path;
		count = 0;
		if(str_path == '/var/www/en') alert("You can Explorer only Images folder");
		else{
			xmlHttp("./php/process.php?path="+str_path,menu.view);
			$('src_folder').innerHTML = str_path.replace('/var/www/en/','');
		}
		$('src_file').innerHTML = "";
	},
	
	view : function(obj){
		
		var xmlData = obj.responseXML;
		var menuNode = xmlData.getElementsByTagName("menu");
		var menuLength = menuNode[0].childNodes.length;
		
		
		tbody = null; //tbody 전역변수로 초기화
		tbody = document.createElement("tbody");
		
		
		for(var i=0; i<menuLength; i++ ){
			if( menuNode[0].childNodes[i].nodeType == 1 ){ //객체일때
				
				menu.parse(menuNode[0].childNodes[i]);				
				
			}
		}
		
		
		//테이블에 자식들 변경/새로추가
		if($(recMenu).childNodes.length > 0){
			$(recMenu).replaceChild(tbody,$(recMenu).firstChild);
		}else{
			$(recMenu).appendChild(tbody);
		}
		
	},
	
	parse : function(obj){
		var title = (obj.nodeName == "menu_a") ? menu.folder(obj) : menu.doc(obj);		
		var view = (obj.nodeName == "menu_a") ? "&nbsp;" : "미리보기";
		
		
		var tr = document.createElement("tr");
		
		var td = document.createElement("td");
		td.innerHTML = title;
			
		tr.appendChild(td);
		
		
		tbody.appendChild(tr);			
	},
	
	folder : function(obj){
		//alert(obj.getAttribute("url"));
		var img = (obj.getAttribute("title") == "..") ? "" : "<img src='icon/_folder.gif' align='absmiddle'> ";

		if( (obj.getAttribute("title") == "..") ){
			var objTitle = "<img src='icon/_up.gif' align='absmiddle'> <strong>..</strong>";
		}else{
			var objTitle = obj.getAttribute("title");
		}
		
		var title = ( obj.getAttribute("url") != null ) ? "<span style='cursor:pointer;cursor:hand;' onclick=\"menu.get('"+obj.getAttribute('url')+"')\">" + img + objTitle +"</span>" : objTitle;
		return title;
	},
	
	doc : function(obj){
		var str = obj.getAttribute("title");
		var hat = str.split(".");
		hat = hat[hat.length - 1];
		
		var img = ( imgType.indexOf("["+hat+"]") != -1 ) ? "<img src='icon/_"+hat+".gif' align='absmiddle'> " : "<img src='icon/_etc.gif' align='absmiddle'> ";
		count++;
		//alert(count);
		
		return "<span style='cursor:pointer;cursor:hand;' onclick=\"_image.show('"+obj.getAttribute('url')+"',"+count+")\">" + img + "<span id='img_"+count+"'>"+obj.getAttribute("title") + "</span></span>";
	}	
	
}
 
 
var _image = {
	
	show : function(path,num){
		
		//alert(count);
		for(i=1;i<=count;i++){
			var imgId = "img_"+i;

			$(imgId).style.color = 'black';
			$(imgId).style.fontWeight = '';
		}
		
		
		var imgId = "img_"+num;
		$(imgId).style.color = 'red';
		$(imgId).style.fontWeight = 'bold';
		
		
		
		
		var fileName = path.split("/");
		$('src_file').innerHTML = fileName[fileName.length-1];
		
		
		tempPath = path.substring(path.indexOf("/en/"));
	
		var imgEng = tempPath;
		var imgKor = tempPath.replace('/en/','/kr/');
    var imgSpa = tempPath.replace('/en/','/sp/');
    var imgFre = tempPath.replace('/en/','/fr/');
    var imgGer = tempPath.replace('/en/','/ge/');
    var imgSwe = tempPath.replace('/en/','/sw/');
    var imgDen = tempPath.replace('/en/','/dk/');
    var imgNl = tempPath.replace('/en/','/nl/');
    var imgNo = tempPath.replace('/en/','/no/');
    var imgFl = tempPath.replace('/en/','/fl/');
    
    $('imgEng').innerHTML = "<img src='" + imgEng + "' name='imgEng'/>";	
		$('imgKor').innerHTML = "<img src='" + imgKor + "' name='imgKor'/>";	
		$('imgSpa').innerHTML = "<img src='" + imgSpa + "' name='imgSpa'/>";	
		$('imgFre').innerHTML = "<img src='" + imgFre + "' name='imgFre'/>";	
		$('imgGer').innerHTML = "<img src='" + imgGer + "' name='imgGer'/>";	
		$('imgSwe').innerHTML = "<img src='" + imgSwe + "' name='imgSwe'/>";	
		$('imgDen').innerHTML = "<img src='" + imgDen + "' name='imgDen'/>";	
		$('imgNl').innerHTML = "<img src='" + imgNl + "' name='imgNl'/>";	
		$('imgNo').innerHTML = "<img src='" + imgNo + "' name='imgNo'/>";	
		$('imgFl').innerHTML = "<img src='" + imgFl + "' name='imgFl'/>";	
			
		setTimeout(_image.detail,500);
		return false;
	},
	
	detail : function(){
		
		$('imgEng').innerHTML += "<br/><br/>  W : "+document.imgEng.width+"* H : "+document.imgEng.height;	
		$('imgKor').innerHTML += "<br/><br/>  W : "+document.imgKor.width+"* H : "+document.imgKor.height;	
		$('imgSpa').innerHTML += "<br/><br/>  W : "+document.imgSpa.width+"* H : "+document.imgSpa.height;	
		$('imgFre').innerHTML += "<br/><br/>  W : "+document.imgFre.width+"* H : "+document.imgFre.height;	
		$('imgGer').innerHTML += "<br/><br/>  W : "+document.imgGer.width+"* H : "+document.imgGer.height;	
		$('imgSwe').innerHTML += "<br/><br/>  W : "+document.imgSwe.width+"* H : "+document.imgSwe.height;	
		$('imgDen').innerHTML += "<br/><br/>  W : "+document.imgDen.width+"* H : "+document.imgDen.height;	
		$('imgNl').innerHTML += "<br/><br/>  W : "+document.imgNl.width+"* H : "+document.imgNl.height;	
		$('imgNo').innerHTML += "<br/><br/>  W : "+document.imgNo.width+"* H : "+document.imgNo.height;	
		$('imgFl').innerHTML += "<br/><br/>  W : "+document.imgFl.width+"* H : "+document.imgFl.height;	
		
		
		
	}
}	