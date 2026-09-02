<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

	lang_set_active_language($t_lang_from_url[1]);
  	// language information by url end
?>

//=======================================================//
// Login
//=======================================================//
//=======================================================//
// ID list
//=======================================================//
var gIdTable=new Array('idTable1','idTable2','idTable3');
var gIdIn=new Array('idInId','idInPw','idInIdRequest','idInIdSent','idInPwSent');
//=======================================================//
// PHP file list
//=======================================================//
var gPhp = new Array('../php/login_check.php');
//=======================================================//
// User information
//=======================================================//
var gUser = new userInfo('','');
function userInfo(username,password)
{
	this.name = username;
	this.pw = password;
}
//=======================================================//
// Page status
//=======================================================//
var gStat = new Array("log","req","re_log");
var fStat = gStat[0];
//=======================================================//
// Check user ID & password
//=======================================================//
var is_logging = {
	"status" : false ,
	"operation" : ""
};
function check_info1()
{
	FormCheck('idInPw');
	var flag = false;
	if( document.getElementById("idInId").value == "" ){
		var msg = "<?php echo lang_get('login_msg_1')?>";
		//flag = true;
		
		alert(msg);
		return false;
	}
	
	if( document.getElementById("idInPw").value == "" ){
		
			var msg = "<?php echo lang_get('login_msg_2')?>";
			alert(msg);
			return false;
			//flag = true;
	}
	
	if( is_logging.status ){
		alert("<?php echo lang_get('login_msg_4')?>");
		return false;
	}
	var _user = new userInfo(document.getElementById('idInId').value,document.getElementById('idInPw').value);

	var op_mode = 'login';

	var tog = document.getElementById('system');
	if( tog.getAttribute('toggled')=='true' ){
		var _tx='&op_mode='+op_mode+'&id='+_user.name+'&password='+_user.pw+'&mobile=true';
	}else{
		var _tx='&op_mode='+op_mode+'&id='+_user.name+'&password='+_user.pw+'&mobile=false';
    }
	
	is_logging.status = true;
	is_logging["operation"] = "login1";
	sendRequest(on_1,_tx,'post',gPhp[0],true,true);
	gUser = _user;
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug("+"+res);
	var tmp = res.split("\n");
	res = tmp[0].split(":");
	//debug(res[0]);
		
	if(res[0]=='OK1')
	{
		//debug(res[1]);
		//debug(gUser.name);
		
		//document.location.href='../system/system.php';
		// Setting user permission for each folder
		//document.location.href='../php/login_set_prms.php';
		//document.location.href='../system/main.php';		
		
		if(gUser.name==='admin')
		{
			var tog = document.getElementById('system');
			if( tog.getAttribute('toggled')=='true' ){
				document.location.href='./root_dir.php';
            }else{
				document.location.href='../../passmobile_index.php';
            }
		}else
		{
			//var msg = "Not Admin\nTo User Page";
			//alert(msg);
			//document.location.href='../system/system_user.php';
			var tog = document.getElementById('system');
			if( tog.getAttribute('toggled')=='true' ){
				document.location.href='./root_dir.php';
            }else{
				document.location.href='../../passmobile_index.php';
            }
		}
	}else if(res[0]=='OK2')
	{
		//alert("You are in the different network");	
		//if ( confirm("You are in the different network. If you want direct access to the server, press [YES]") ) {
			//document.location.href='../system/main.php';
			if(gUser.name==='admin')
			{
				var tog = document.getElementById('system');
				if( tog.getAttribute('toggled')=='true' ){
					document.location.href='./root_dir.php';
			    }else{
					document.location.href='../../passmobile_index.php';
				}
			}else
			{
				//var msg = "Not Admin\nTo User Page";
				//alert(msg);
				//document.location.href='../system/system_user.php';
				var tog = document.getElementById('system');
				if( tog.getAttribute('toggled')=='true' ){
					document.location.href='./root_dir.php';
				}else{
					document.location.href='../../passmobile_index.php';
				}
			}			
		//}
		//else {
		//	document.location.href='../system/remote.php';
		//}
		
	}else if(res[0]=='NG')
	{
		var msg = "<?php echo lang_get('login_msg_3')?>";
		alert(msg);
		//debug(res[1]);
	}else{
		alert("<?php echo lang_get('login_msg_5')?>");
	}
	is_logging.status = false;
	is_logging["operation"] = "";
}
function check_info2()
{
	var flag = false;
	if( document.getElementById("idInIdSent").value == "" ){
		var msg = "No user ID!";
		flag = true;
	}
	if( document.getElementById("idInPwSent").value == "" ){
		if( flag ){
			msg += "\nNo password!";
		}else{
			var msg = "No password!";
			flag = true;
		}
	}
	if(flag){
		alert(msg);
		return false;
	}
	if( is_logging.status ){
		alert("Now waiting server's response");
		return false;
	}
	var _user = new userInfo(document.getElementById('idInIdSent').value,document.getElementById('idInPwSent').value);
	var op_mode = 'login';
	
	var tog = document.getElementById('system');
	if( tog.getAttribute('toggled')=='true' ){
		var _tx='&op_mode='+op_mode+'&id='+_user.name+'&password='+_user.pw+'&mobile=true';
	}else{
		var _tx='&op_mode='+op_mode+'&id='+_user.name+'&password='+_user.pw+'&mobile=false';
    }
		
	is_logging.status = true;
	is_logging["operation"] = "login1";
	sendRequest(on_2,_tx,'post',gPhp[0],true,true);
	gUser = _user;
	/*var _user = new userInfo(document.getElementById('idInIdSent').value,document.getElementById('idInPwSent').value);
	var _tx='&id='+_user.name+'&password='+_user.pw;
	sendRequest(on_2,_tx,'post',gPhp[0],true,true);*/
}
function on_2(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	var tmp = res.split("\n");
	res = tmp[0].split(":");
	//debug(res[0]);
	if(res[0]=='OK')
	{
		//debug(res[1]);
		//debug(gUser.name);
		if(gUser.name==='admin')
		{
			var tog = document.getElementById('system');
			if( tog.getAttribute('toggled')=='true' ){
				document.location.href='./root_dir.php';
            }else{
				document.location.href='../../passmobile_index.php';
            }
		}else
		{
			//var msg = "Not Admin\nTo User Page";
			//alert(msg);
			document.location.href='../user/system/system.php';
		}
	}else if(res[0]=='NG')
	{
		var msg = 'Wrong ID or password!';
		alert(msg);
		//debug(res[1]);
	}else{
		alert("Cannot login");
	}
	is_logging.status = false;
	is_logging["operation"] = "";
	/*if(res.match('Right user')=='Right user')
	{
		document.location.href='../system/system.php';
	}*/
}
//=======================================================//
// Table control
//=======================================================//
function open_table_basic()
{
	close_table_all();
	dis_ctl('idTable1','block');
	fStat = gStat[0];
}
function open_table_request()
{
	close_table_all();
	dis_ctl('idTable2','block');
	document.getElementById('idInIdRequest').focus();
	fStat = gStat[1];
}
function open_table_sent()
{
	close_table_all();
	dis_ctl('idTable3','block');
	document.getElementById('idInIdSent').focus();
	fStat = gStat[2];
}
function close_table_all()
{
	document.getElementById('idTable1').style.display='none';
	document.getElementById('idTable2').style.display='none';
	document.getElementById('idTable3').style.display='none';
}

//=======================================================//
// Password request
//=======================================================//
function request_pw()
{
	var _id=document.getElementById('idInIdRequest').value;
	// to do
	// send request for password
	var msg = (_id) ? 'Password request was sent to server for user ['+_id+']' : "No ID!";
	alert(msg);
	if(_id) open_table_sent();
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
			switch(fStat)
			{
				case gStat[0]:
				check_info1();
				break;
				case gStat[1]:
				request_pw();
				break;
				case gStat[2]:
				check_info2();
				break;
				default:
				//debug(fStat);
				break;
			}
		}
		return !(theKey==13);
	}
}

//=======================================================//
// Load message
//=======================================================//
//debug('login.js'); 
