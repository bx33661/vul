function check_click(chk_id)
{
    try{
        if(document.getElementById(chk_id)){
            if(chk_id == "check_list_col"){
              var treeObj = document.getElementById('root_list');
	            var images = treeObj.getElementsByTagName('IMG');
	            if(document.getElementById(chk_id).src.indexOf('cms_uncheck.')>-1){
	                document.getElementById(chk_id).src = "../images/comnso/cms_tristate.gif";
	                for(var no=0;no<images.length;no++){
		                if(images[no].id.indexOf('check_listit')>-1){
		                    images[no].src = "../images/comnso/cms_check.gif";
		                }else if(images[no].id.indexOf('check_listi')>-1){
		                    images[no].src = "../images/comnso/cms_uncheck.gif";
		                }
	                }
	            }else if(document.getElementById(chk_id).src.indexOf('cms_tristate.')>-1){			        
	                document.getElementById(chk_id).src = "../images/comnso/cms_check.gif";
	                for(var no=0;no<images.length;no++){
		                if(images[no].id.indexOf('check_listi')>-1){
		                    images[no].src = "../images/comnso/cms_check.gif";
		                }			            
	                }			        
	            }else{
	                document.getElementById(chk_id).src = "../images/comnso/cms_uncheck.gif";
	                for(var no=0;no<images.length;no++){
		                if(images[no].id.indexOf('check_listi')>-1){
		                    images[no].src = "../images/comnso/cms_uncheck.gif";
		                }
	                }
	            }              
            }else{
                if(document.getElementById(chk_id).src.indexOf('cms_uncheck.')>-1){
                    document.getElementById(chk_id).src = '../images/comnso/cms_check.gif';
                    //***************** Add **********************************************************/
                    chk_info.add(document.getElementById(chk_id), "1");
                    
                }else{
                    document.getElementById(chk_id).src = '../images/comnso/cms_uncheck.gif';
                    //***************** Add **********************************************************/
                    chk_info.add(document.getElementById(chk_id), "0");
                }
            }
        }
    }catch(e){
    
    }
}

function submit_restore()
{
	var rst_dirin=" ;";
	var rst_direx=" ;";
	g_document = document;
	var treeObj = document.getElementById('root_tree');
	var images = treeObj.getElementsByTagName('IMG');
	for(var no=0;no<images.length;no++){
	    if(images[no].id.indexOf('check')>-1){
	        var key;
	        key = images[no].id.substring(5);
		    if(images[no].src.indexOf('/cms_check.')>-1){
			    if(key){
				    rst_dirin += key;
				    rst_dirin += ";";
			    }
		    }else{
			    if(key){
				    rst_direx += key;
				    rst_direx += ";";
			    }				
		    }
	    }
	}
	
	// 기록된 디렉토리 체크값을 얻어 포함여부를 확인한다.
	for(var no=0; chk_info.skey[no]; no++){
		if(chk_info.value[chk_info.skey[no]] == "1"){
			var key;
			key = chk_info.skey[no].substring(5);
			if(rst_dirin.search(";"+key+";") == -1){
			    rst_dirin += key;
			    rst_dirin += ";";
			}
		}
	}

	var rst_filelist=" ;";
	treeObj = document.getElementById('root_list');
	images = treeObj.getElementsByTagName('IMG');
	for(var no=0;no<images.length;no++){
		if(images[no].id.indexOf('check_listi')>-1){
			if(images[no].src.indexOf('/cms_check.')>-1){
	            var key;
	            key = images[no].id.substring(12);			
				if(key){
					rst_filelist += key;
					rst_filelist += ";";
				}
			}
		}
	}

	$.post("../php/comnso_save_restoreinfo.php", {dirin: rst_dirin, direx: rst_direx, filelist: rst_filelist}, 
		function(data){
			if(data.indexOf('save ok!')>0){
			    try{
			    		// park94 : init restore
			    		sendRequest(on_sch_ini, '&mode=init_res', 'post', '../php/schedule_init.php', true, true);
			    		
				    var url;
				    url = "../php/restore_image_progress_pop.php";
					//var newWindow = window.open(url, 'RESTORE','scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no,width=432px,height=280px');
					var newWindow = window.open(url, '_blank','scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no,width=432px,height=280px');
					if (!newWindow) return false;
					
					var html = "";
					html += "<html><head></head><body><form id='formid' method='post' action='" + url + "'>";
					html += "</form><script type='text/javascript'>document.getElementById(\"formid\").submit()</script></body></html>";
					newWindow.document.write(html);
				    newWindow.focus();
				}catch(e){
				    debug(e.toString());
				}
			}
		}
	);
}

// park94 : init restore
function on_sch_ini(oj){
	var res=decodeURIComponent(oj.responseText);
	eval('var _res = '+res);
	switch(_res){
		case 1:
		break;
		case 0:
		break;
		default:
	}
	return;
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
//=======================================================//
// 
//=======================================================//
var chk_info = {
	"skey" : [],
	"value" : [],
	"add" : function(oj, val){
		if(oj.id.match("check_list")){
			return false;
		}
		this.skey.push(oj.id);
		this.value[oj.id] = val;
		
		if(val=="1"){
			this.all_check(oj.id);
		}else{
			this.all_uncheck(oj.id);
		}
	},
	"check" : function(arr,fid){
		var _oj = document.getElementById('check'+fid);
		if(_oj.src.search("cms_check.gif") > -1){
			this.all_check('check'+fid);
			return true;
		}
		for(var i=0;arr[i];i++){
			if( arr[i].id.search("check") > -1 ){
				if(this.value[arr[i].id] == "1"){
					arr[i].src = "../images/comnso/cms_check.gif";
				}
			}
		}
	},
	"all_check" : function(id){
		var _obj = document.getElementById(id.substr(5)+"Info");
		var _imgs = _obj.getElementsByTagName("IMG");
		for(var i=0; _imgs[i]; i++){
			if( _imgs[i].id.search("check") > -1 ){
				_imgs[i].src = "../images/comnso/cms_check.gif";
				this.skey.push(_imgs[i].id);
				this.value[_imgs[i].id] = "1";
			}
		}
	},
	"all_uncheck" : function(id){
		var _obj = document.getElementById(id.substr(5)+"Info");
		var _imgs = _obj.getElementsByTagName("IMG");
		for(var i=0; _imgs[i]; i++){
			if( _imgs[i].id.search("check") > -1 ){
				_imgs[i].src = "../images/comnso/cms_uncheck.gif";
				this.skey.push(_imgs[i].id);
				this.value[_imgs[i].id] = "0";
			}
		}
	}
}