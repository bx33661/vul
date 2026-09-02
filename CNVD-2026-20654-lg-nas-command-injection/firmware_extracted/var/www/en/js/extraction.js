//========================================================//
// Blu-ray / Ripping menu
//========================================================//

//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array("../php/ripping_chk_audio.php",
"../php/ripping_chk_dvd.php",
"../php/ripping_rip_audio.php",
"../php/ripping_rip_dvd.php");
//========================================================//
// ID list
//========================================================//
var gIdTab=new Array('idTabAudio','idTabDvd');	// tab area
var gIdTable=new Array('idTableBasic','idTableAudio','idTableDvd');	// table area
var gIdButton=new Array('idButtonNext');
var gIdTxtArea=new Array("idTxtDiscReady");
var gIdIn=new Array("idAudioSavePath");
var gIdInAudio=new Array("idMode","idBit","idRate","idAudioSavePath","idAudioFilename");
var gIdInDvd=new Array("idModeDvd","idDvdSavePath","idDvdFilename");
//========================================================//
// Page status
//========================================================//
var gStat = new Array("ready_audio","ready_dvd","audio_rip_ready","dvd_rip_ready");
var fStat = gStat[0];
//========================================================//
// Message text
//========================================================//
var gMsgTxt = new Array('Please insert your audio CD to the Blu-ray Drive',
'Loading disc...',
'Please insert your DVD title to the Blu-ray drive',
"BD is busy. Wait until disc is loaded.<br>Try again.");
var gWMsgTxt = new Array("No input in file name or path to save!",
"No input in title name or path to save!");
//=======================================================//
// Root path
//=======================================================//
var gRootPath=new Array("/mnt/fs");
//=======================================================//
// Window handle for popup window
//=======================================================//
var hPopWin = "";
//========================================================//
// Open table
//========================================================//
function open_basic()
{
	close_table_all();
	document.getElementById(gIdTab[0]).style.display='block';
	document.getElementById(gIdTable[0]).style.display='block';
	vis_ctl(gIdButton[0],'visible');
}
function open_audio_ready()
{
	close_table_all();
	document.getElementById(gIdTab[0]).style.display='block';
	document.getElementById(gIdTable[0]).style.display='block';
	show_text(gIdTxtArea[0],gMsgTxt[0]);
	vis_ctl(gIdButton[0],'visible');
	fStat=gStat[0];
}
function open_dvd_ready()
{
	close_table_all();
	document.getElementById(gIdTab[1]).style.display='block';
	document.getElementById(gIdTable[0]).style.display='block';
	show_text(gIdTxtArea[0],gMsgTxt[2]);
	vis_ctl(gIdButton[0],'visible');
	fStat=gStat[1];
}
function open_audio()
{
	close_table_all();
	document.getElementById(gIdTab[0]).style.display='block';
	document.getElementById(gIdTable[1]).style.display='block';
	fStat=gStat[2];
}
function open_dvd()
{
	close_table_all();
	document.getElementById(gIdTab[1]).style.display='block';
	document.getElementById(gIdTable[2]).style.display='block';
	fStat=gStat[3];
}
function close_table_all()
{
	var _tmp='none';
	for(var i=0;i<gIdTab.length;i++)
	{
		dis_ctl(gIdTab[i],_tmp);
	}
	for(var i=0;i<gIdTable.length;i++)
	{
		dis_ctl(gIdTable[i],_tmp);
	}
}
//=======================================================//
// Disc load
//=======================================================//
function load_disc()
{
	//debug('load disc');
	show_text(gIdTxtArea[0],gMsgTxt[1]);
	vis_ctl(gIdButton[0],'hidden');
	if(fStat==gStat[0])
	{
		var mode = 'rip_audio';
	}else if(fStat==gStat[1])
	{
		var mode = 'rip_dvd';
	}else
	{
		alert("page status error!");
	}
	var cmd = '&mode='+mode;
	var php = '../php/bd_odd_check.php';
	sendRequest(on_load_disc,cmd,'post',php,true,true);
}
function on_load_disc(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'NG':
			break;
		case 'OK':
			if(fStat==gStat[0])
			{
				open_audio();
			}else if(fStat==gStat[1])
			{
				open_dvd();
			}else
			{
				alert('Page status error');
			}
			return true;
			break;
		case 'WARNING':
			if( ret[1]=="BD IS BUSY" ){
				var _msg = bdBusy.get_msg(tmp);
				show_text(gIdTxtArea[0],_msg);
				vis_ctl(gIdButton[0],'visible');
				return true;
			}
			break;
		case 'ERROR':
			break;
		default:
			break;
	}
	//alert(tmp[0]);
	if(fStat==gStat[0])
	{
		var msg = ret[1]+"<br>"+gMsgTxt[0];
	}else if(fStat==gStat[1])
	{
		var msg = ret[1]+"<br>"+gMsgTxt[2];
	}else
	{
		alert('Page status error');
	}
	show_text(gIdTxtArea[0],msg);
	vis_ctl(gIdButton[0],'visible');
}

/***********************************************/
/*function check_disc_audio()
{
	sendRequest(on_1,'','POST',gPhp[0],true,true);
	show_text(gIdTxtArea[0],gMsgTxt[1]);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	switch (res)
	{
	case "CDA OK":
		debug(res);
		open_audio();
		break;
	case "ODD BUSY":
		debug("odd is busy");
		show_text(gIdTxtArea[0],gMsgTxt[3]);
		vis_ctl(gIdButton[0],'visible');
		break;
	case "NG":
		debug("not audio cd");
		show_text(gIdTxtArea[0],gMsgTxt[0]);
		vis_ctl(gIdButton[0],'visible');
		break;
	default:
		debug("cda check error!");
	}
}
function check_disc_dvd()
{
	sendRequest(on_2,'','POST',gPhp[1],true,true);
	show_text(gIdTxtArea[0],gMsgTxt[1]);
}
function on_2(oj)
{
	var res=decodeURIComponent(oj.responseText);
	switch (res)
	{
	case "DVD OK":
		debug(res);
		open_dvd();
		break;
	case "ODD BUSY":
		debug("odd is busy");
		show_text(gIdTxtArea[0],gMsgTxt[3]);
		vis_ctl(gIdButton[0],'visible');
		break;
	case "NG":
		debug("not dvd movie");
		show_text(gIdTxtArea[0],gMsgTxt[2]);
		vis_ctl(gIdButton[0],'visible');
		break;
	case "Protected Disc":
		var msg = "Protected disc<br>";
		msg += gMsgTxt[2];
		show_text(gIdTxtArea[0],msg);
		vis_ctl(gIdButton[0],'visible');
		break;
	default:
		debug("dvd movie check error!");
	}
}*/

//=======================================================//
// Audio advanced setting
//=======================================================//
function open_adv_set()
{
	dis_ctl('idAudioAdv','block');
	dis_ctl('idButtonAdvOpen','none');
	dis_ctl('idButtonAdvClose','block');
}
function close_adv_set()
{
	dis_ctl('idAudioAdv','none');
	dis_ctl('idButtonAdvOpen','block');
	dis_ctl('idButtonAdvClose','none');
}
//=======================================================//
// Open popup window for folder selection
//=======================================================//
function popup_file_browser(id)
{
	document.getElementById("idInputFieldId").value=id;
	var win = window.open('bd_pop_brows.php','RIP_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
	//var win = window.open('../popup/browsing_pop_01.php','RIP_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
	win.focus(); 
}
//=======================================================//
// Audio ripping
//=======================================================//
function rip_audio()
{
	if(!rip_audio_check())
	{
		alert(gWMsgTxt[0]);
		return false;
	}
	document.getElementById('id_btn_rip_aud').style.visibility ="hidden"; 
	var _win = window.open('extraction_audio_progress_pop.php','AUDIO_EXTRACTION_PROGRESS','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
	_win.focus();
	hPopWin = _win;
}
/*function rip_audio()
{
	//debug("rip audio cd");
	if(!rip_audio_check())
	{
		alert(gWMsgTxt[0]);
		return false;
	}
	var _tmp = read_audio_setting();
	var op_mode = 'rip_audio';
	var cmd = "&op_mode="+op_mode+"&mode="+_tmp[0]+"&bit="+_tmp[1]+"&rate="+_tmp[2]+"&path="+gRootPath+_tmp[3]+"&filename="+_tmp[4];
	var php = '../php/bd_do_task.php';
	sendRequest(on_rip_audio,cmd,"post",php,true,true);
	
	document.getElementById('id_btn_rip_aud').style.visibility ="hidden"; 
	var _win = window.open('ripping_audio_progress_pop.php','Audio_ripping_progress','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
	_win.focus();
	hPopWin = _win;
}
function on_rip_audio(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'OK':
			var msg = ret[1];
			hPopWin.close();
			break;
		case 'NG':
			var msg = ret[1];
			hPopWin.close();
			break;
		case 'WARNING':
			var msg = res;
			hPopWin.close();
			break;
		case 'ERROR':
			var msg = res;
			hPopWin.close();
			break;
		case 'EXCEPTION':
			// complete or canceled
			debug('D : Exception');
			return false;
			break;
		default:
			// Timeout or cancel
			debug('D : No return (Timeout/Cancel)');
			return false;
			break;
	}
	//alert(msg);
	var id = 'id_btn_rip_aud';
	document.getElementById(id).style.visibility = "visible";
}
function read_audio_setting()
{
	//debug("read audio setting");
	var _tmp=new Array();
	if(document.getElementById('idAudioAdv').style.display=="block")
	{
		for(var i=0;i<3;i++)
		{
			_tmp[i]=document.getElementById(gIdInAudio[i]).selectedIndex;
			_tmp[i]=document.getElementById(gIdInAudio[i]).options[_tmp[i]].value;
			//debug(_tmp[i]);
		}
	}else
	{
		_tmp[0]="s";
		_tmp[1]="16";
		_tmp[2]="44100";
	}
	for(var i=3;i<5;i++)
	{
		_tmp[i]=document.getElementById(gIdInAudio[i]).value;
		//debug(_tmp[i]);
	}
	return _tmp;
}*/

//=======================================================//
// DVD advanced setting
//=======================================================//
function open_adv_set_dvd()
{
	dis_ctl('idDvdAdv','block');
	dis_ctl('idButtonAdvOpenDvd','none');
	dis_ctl('idButtonAdvCloseDvd','block');
}
function close_adv_set_dvd()
{
	dis_ctl('idDvdAdv','none');
	dis_ctl('idButtonAdvOpenDvd','block');
	dis_ctl('idButtonAdvCloseDvd','none');
}
//=======================================================//
// DVD ripping
//=======================================================//
function rip_dvd()
{
	//debug("rip dvd movie");
	if(!rip_dvd_check())
	{
		alert(gWMsgTxt[1]);
		return false;
	}	
	document.getElementById('id_btn_rip_dvd').style.visibility ="hidden";
	var _win = window.open('extraction_dvd_progress_pop.php','DVD_EXTRACTION_PROGRESS','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
	_win.focus();
	hPopWin = _win;
}
/*function on_rip_dvd(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	var tmp = res.split("\n");
	var ret = tmp[0].split(":");
	switch(ret[0])
	{
		case 'OK':
			var msg = ret[1];
			hPopWin.close();
			break;
		case 'NG':
			var msg = ret[1];
			hPopWin.close();
			break;
		case 'WARNING':
			var msg = res;
			hPopWin.close();
			break;
		case 'ERROR':
			var msg = res;
			hPopWin.close();
			break;
		case 'EXCEPTION':
			// complete or canceled
			debug('D : Exception');
			return false;
			break;
		default:
			// Timeout or cancel
			debug('D : No return (Timeout/Cancel)');
			return false;
			break;
	}
	//alert(msg);
	document.getElementById('id_btn_rip_dvd').style.visibility = "visible"; 
}
function read_dvd_setting()
{
	var _tmp=new Array();
	var _oj=document.getElementById(gIdInDvd[0]);
	_tmp[0]=_oj.selectedIndex;
	_tmp[0]=_oj.options[_tmp[0]].value;
	_tmp[1]=document.getElementById(gIdInDvd[1]).value;
	_tmp[2]=document.getElementById(gIdInDvd[2]).value;
	return _tmp;
}*/
/*function on_4(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	var err = 'Access Denied';
	if(res.match(err))
	{
		hPopWin.close();
		alert(res);
	}else if(res)
	{
		// Not session out //
	}else
	{
		var Msg = "Session timeout!\n";
		Msg += "Reconnect NAS\n";
		Msg += "Progress window is alive, Don't close it."
		alert(Msg);
	}
}*/
//=======================================================//
// Check functions
//=======================================================//
function rip_audio_check()
{
	if(document.getElementById(gIdInAudio[3]).value &&
	document.getElementById(gIdInAudio[4]).value)
	{
		return true;
	}
	return false;
}
function rip_dvd_check()
{
	if(document.getElementById(gIdInDvd[1]).value &&
	document.getElementById(gIdInDvd[2]).value)
	{
		return true;
	}
	return false;
}
//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2');
function show_help()
{
	switch(help)
	{
		case 1:
		var _win = window.open('../help/blu-ray/help_ripping.html#audio','Help_ripping','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/blu-ray/help_ripping.html#video','Help_ripping','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;

		default:
		break;
	}
}