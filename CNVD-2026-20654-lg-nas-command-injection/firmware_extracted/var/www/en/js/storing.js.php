<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>

//=======================================================//
// Blu-ray / Storing
//=======================================================//

//========================================================//
// PHP file list
//========================================================//
var gPhp = new Array(
"../php/storing_chk_data.php",
"../php/storing_chk_image.php",
"../php/storing_data_copy.php",
"../php/storing_image_backup.php");

//=======================================================//
// ID list
//=======================================================//
var gIdTab=new Array('idTab1','idTab2');
var gIdTable=new Array('idTable1','idTable2','idTable3');
var gIdTxt=new Array('idTxt1');
var gIdBtn=new Array('idButtonNext');
var gIdOut=new Array('idOut1');
var gIdInData=new Array("idInDataPath");
var gIdInImage=new Array("idInImagePath");

//=======================================================//
// Page status
//=======================================================//
var gStat=new Array('data ready','image ready','data copy','image backup');
var fStat=gStat[0];
//=======================================================//
// Root path
//=======================================================//
var gRootPath=new Array("/mnt/fs");
//=======================================================//
// Popup window handle
//=======================================================//
var hPopWin = "";
//=======================================================//
// Text list
//=======================================================//
var gTxt=new Array("<?php echo lang_get('storing_copy_1')?>",
"<?php echo lang_get('storing_backup_1')?>",
"<?php echo lang_get('storing_msg_5')?>",
"<?php echo lang_get('storing_msg_6')?>",
"<?php echo lang_get('common_error')?>");

//=======================================================//
// Open table
//=======================================================//
function open_table_ready(stat)
{
	if(stat==gStat[0])
	{
		var _txt=gTxt[0];
		fStat=stat;
		var _tab=gIdTab[0];
	}else if(stat==gStat[1])
	{
		var _txt=gTxt[1];
		fStat=stat;
		var _tab=gIdTab[1];
	}
	close_table_all();
	var tmp='block';
	dis_ctl(_tab,tmp);
	dis_ctl(gIdTable[0],tmp);
	show_text(gIdTxt[0],_txt);
	vis_ctl(gIdBtn[0],'visible');
}
function open_table_data()
{
	close_table_all();
	var tmp='block';
	dis_ctl(gIdTab[0],tmp);
	dis_ctl(gIdTable[1],tmp);
	fStat=gStat[2];
}
function open_table_image()
{
	close_table_all();
	var tmp='block';
	dis_ctl(gIdTab[1],tmp);
	dis_ctl(gIdTable[2],tmp);
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
	show_text(gIdTxt[0],"<?php echo lang_get('extraction_cd_2')?>");
	vis_ctl(gIdBtn[0],'hidden');
	if(fStat==gStat[0])
	{
		var mode = 'store_data';
	}else if(fStat==gStat[1])
	{
		var mode = 'store_image';
	}
	var cmd = '&mode='+mode;
	var php = '../php/bd_odd_check.php';
	sendRequest(on_load_disc,cmd,'post',php,true,true);
}
function on_load_disc(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	
	// OK Status
	if(res.match('OK:DATA DISC')) {
		open_table_data();
		return;
	}
	else if(res.match('OK:AVAILABLE DISC')) {
		open_table_image();
		return;
	}
	
	// Error Handling

	var msg ="";
	
		if(res.match('WARNING')){
			if(res.match('TRAY OPENED')){
				msg = "<?php echo lang_get('schedule_msg_17')?>";

			}else if(res.match('NO DISC')){
				msg = "<?php echo lang_get('schedule_msg_18')?>";
				
				if(fStat==gStat[0]) msg += "<BR><BR>" + gTxt[0];
				else if(fStat==gStat[1]) msg += "<BR><BR>" + gTxt[1];

			}else if(res.match('DRIVE IS BUSY')){
				msg = "<?php echo lang_get('storing_msg_5')?>";
			}
		}
		else if(res.match('ERROR:BD')){
				msg = "BD CHECK FAIL";
		}
		else if(res.match('NG:')){
			if(res.match('CDA/CDX/BLANK')){
				msg = "CDA / CDX / BLANK<BR /><BR />";
			}
			else if(res.match('DVD TITLE')){
				msg = "DVD TITLE<BR /><BR />";
			}
			else if(res.match('BLANK DISC')){
				msg = "BLANK DISC<BR /><BR />";
			}
			else if(res.match('PROTECTED DISC')){
				msg = "PROTECTED DISC<BR /><BR />";
			}
			msg += "<?php echo lang_get('storing_msg_6')?>";
		}

			


	show_text(gIdTxt[0],msg);
	vis_ctl(gIdBtn[0],'visible');
}
/************************/
/*function check_disc(mode)
{
	var cmd = '&mode='+mode;
	var php = '../php/storing_chk_disc.php';
	sendRequest(on_chk_disc,cmd,'post',php,true,true);
}
function on_chk_disc(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res+"+"+res.lastIndexOf("\n"));
	res = res.substring(0, res.lastIndexOf("\n"));
	//debug("+"+res+"+");
	var tmp = res.split(":");
	//debug("+"+tmp+"+");
	switch(tmp[0])
	{
	case 'DATA DISC':
		if(fStat==gStat[0])
		{
			open_table_data();
		}else if(fStat==gStat[1])
		{
			open_table_image();
		}
		return true;
		break;
	case "NOT DATA DISC":
		alert('Not data disc : '+tmp[1]);
		break;
	case "ODD BUSY":
		var msg = "Blu-ray Drive is busy.";
		alert(msg);
		break;
	case "ERROR":
		alert(tmp[1]);
		break;
	default:
		var msg = "Disc check error!";
		alert(msg);
		break;
	}
	open_table_ready(fStat);
}
function check_disc_data()
{
	debug("check data disc");
	sendRequest(on_1,"","post",gPhp[0],true,true);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	switch (res)
	{
	case "DATA DISC":
		//alert("Data disc");
		open_table_data();
		return true;
		break;
	case "NG":
		alert("Not data disc");
		break;
	case "ODD BUSY":
		alert("BD is busy.");
		break;
	default:
		alert("Disc check error!");
		break;
	}
	open_table_ready(fStat);
}
function check_disc_image()
{
	debug("check data disc for image backup");
	sendRequest(on_3,"","post",gPhp[1],true,true);
}
function on_3(oj)
{
	var res=decodeURIComponent(oj.responseText);
	debug(res);
	switch (res)
	{
	case "OK":
		//alert("Data disc");
		open_table_image();
		return true;
		break;
	case "NG":
		alert("Not data disc");
		break;
	case "ODD BUSY":
		alert("BD is busy.");
		break;
	default:
		alert("Disc check error!");
		break;
	}
	open_table_ready(fStat);
}*/
//=======================================================//
// Open popup window for folder selection
//=======================================================//
function popup_file_browser(id)
{
	document.getElementById("idInputFieldId").value=id;
	if(id=="idInDataPath")
	{
		var tmp="data copy";
	}else
	{
		var tmp="image backup";
	}
	document.getElementById('idPathMode').value=tmp;
	var win = window.open('bd_pop_brows.php','_blank','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
	win.focus(); 
}
//=======================================================//
// Data copy
//=======================================================//
function copy_data()
{
	if(!copy_data_check())
	{
		var msg = 'Check \'path to save\' field!';
		alert(msg);
		return false;
	}
	//var id = 'id_btn_copy';
	//document.getElementById(id).style.visibility='hidden';  
	//document.getElementById('idDisableBackground').style.display='block'; 
	var _win = window.open('storing_data_progress_pop.php','_blank','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
	_win.focus();
	hPopWin = _win;
}
function copy_data_check()
{
	// Check conditions for data copy here
	var id = 'idInDataPath';
	if(document.getElementById(id).value) return true;
	return false;
}


//=======================================================//
// Image backup
//=======================================================//
function backup_image()
{
	if(!backup_image_check())
	{
		var msg = 'Check \'path to save\' field!';
		alert(msg);
		return false;
	}	
	var id = 'id_btn_backup';
	document.getElementById(id).style.visibility='hidden';
	//document.getElementById('idDisableBackground').style.display='block';
	var _win = window.open('storing_image_progress_pop.php','_blank','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=280px');
	_win.focus();
	hPopWin = _win;
}
function backup_image_check()
{
	// Check conditions for data copy here
	var id = 'idInImagePath';
	if(document.getElementById(id).value) return true;
	return false;
}





function dataC()
{
	sendRequest(on_loaded_dc,'&txtCopyPathDVD='+document.frmTScd.txtCopyPathDVD.value,
		'POST','./setting/cd/setting_cd_data.php',true,true);
}
function on_loaded_dc(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//alert(res);
}

//===========================================================================//
// (4)ODD IMAGE BACKUP
//===========================================================================//
// MOSILT ERROR CODES
//===========================================================================//
var gErr = new Array();
gErr[80] = "SCSI Open Error";
gErr[81] = "Buffer Open Error";
gErr[82] = "Check Drive";
gErr[83] = "Blank Disc";
gErr[84] = "Protected Disc";
gErr[85] = "Open Session Disc";
gErr[86] = "File Open Error";
gErr[87] = "Read Error";
gErr[88] = "Unknown Profile";
//===========================================================================//
function appImageBackup()
{
	//show layer//
	if(checkBlank(document.frmTS1.txtFileName)==false)
	{
	}
	else
	{
		var cmd = "Media Type";
		sendRequest(on_loaded_oddIB, '&txtType='+cmd, 'post', './setting/cd/cdstat_ref.php', true, true);
	}
}
function on_loaded_oddIB(oj)
{
	var res = decodeURIComponent(oj.responseText)
	//alert(res)
	var _oddstat = res.substring(0, res.indexOf("\n"));
	//alert('*'+_oddstat+"*");
	res = res.substring(res.indexOf("\n")+1, res.length-1);
	//alert("*"+res+"*");
	var newText = document.createTextNode(_oddstat);
	document.getElementById('oddState').removeChild( document.getElementById('oddState').lastChild );
	document.getElementById('oddState').appendChild(newText);
	newText = document.createTextNode(res);
	document.getElementById('idMediaType').removeChild( document.getElementById('idMediaType').lastChild );
	document.getElementById('idMediaType').appendChild(newText);
	if(_oddstat == 'CD-ROM BUSY')
	{
		// check ODD
		alert('ODD IS BUSY\nCHECK ODD');
		return false;
	}else if(_oddstat == 'CD-ROM IDLE' && res != "NO MEDIA INFO")
	{
		// 1)check ODD
		// 2)start IMAGE BACKUP
		alert('START IMAGE BACKUP');
		imageB();
		return true;
	}else
	{
		alert("NO MEDIA\nCHECK ODD");
	}
}
function imageB()
{
	//start image backup//
	sendRequest(on_loaded_aIB,'&opCode='+document.frmTS1.opCode.value+'&txtFileName='+document.frmTS1.txtFileName.value,'POST','./setting/cd/setting_cd_image.php',true,true);
}
function on_loaded_aIB(oj)
{
	//==================================================//
	// Error Messages //
	var _errmsg = new Array(
	'Same file exist!\nInput other file name!',
	'No file name. Enter file name.');
	//==================================================//
	var res = decodeURIComponent(oj.responseText);
	//alert("Response:\n"+res);
	if(res == _errmsg[0])
	{
		// Error
		alert(_errmsg[0]);
	}else if(res == _errmsg[1])
	{
		// Error
		alert(_errmsg[1]);
	}else
	{
		// SEARCH RETURN MESSSAGE //
		myRE = RegExp("Error : \\w+");
		var _tmp = res.match(myRE);
		_tmp = _tmp.toString();
		_tmp = _tmp.substring( _tmp.indexOf(":")+2 );
		var _i = parseInt(_tmp);
		if(_i>=80 && _i<=88)
		{
			alert(gErr[_i]);
		}
	}
}	



//=======================================================//
// Load check
//=======================================================//
debug('storing.js');
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
		var _win = window.open('../help/blu-ray/help_storing.html#copy','Help_storing','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/blu-ray/help_storing#image','Help_storing','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;

		default:
		break;
	}
}