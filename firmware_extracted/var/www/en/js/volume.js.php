<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>
// ======================================================//
// Define Prototype
// ======================================================//
String.prototype.replaceAll = function( searchStr, replaceStr )
{
var temp = this;

while( temp.indexOf( searchStr ) != -1 )
{
temp = temp.replace( searchStr, replaceStr );
}

return temp;
}


//========================================================//
// System / Volume menu

var fMigProgress = 0; 
var _vol_node="";
var _vol_level="";
var _vol_elenum="";
var _vol_level_flag=0;

//========================================================//
// PHP file list
//========================================================//


var gBAYCOUNTMAX = 4;
var gVOLCOUNTMAX = 4;
var gVolCnt=0;
var gBayCnt=0;
var gVolSetDelayTime=0;
var gVOLSETDT = 10000;
var gBayReverse=1;
//========================================================//
// Table ID Volume
//========================================================//
var gIdTable=new Array('vol_table0','vol_table1','vol_table2','vol_table3');
var gIdLine=new Array('vol_table0_line','vol_table1_line','vol_table2_line','vol_table3_line');


//========================================================//
// Output ID_Vol
//========================================================//
var gIdOutputVol = new Array(gVOLCOUNTMAX);

gIdOutputVol[0] = new Array('idVolName0','idVolDsks0','idVolLevl0','idVolStat0','idVolCapap0','idVolCapa0','idVolProg_bar0','idVolProg_width0');
gIdOutputVol[1] = new Array('idVolName1','idVolDsks1','idVolLevl1','idVolStat1','idVolCapap1','idVolCapa1','idVolProg_bar1','idVolProg_width1');
gIdOutputVol[2] = new Array('idVolName2','idVolDsks2','idVolLevl2','idVolStat2','idVolCapap2','idVolCapa2','idVolProg_bar2','idVolProg_width2');
gIdOutputVol[3] = new Array('idVolName3','idVolDsks3','idVolLevl3','idVolStat3','idVolCapap3','idVolCapa3','idVolProg_bar3','idVolProg_width3');

var gIdInputVolCheck = new Array('idVolCheck0','idVolCheck1','idVolCheck2','idVolCheck3');
var gVolName = new Array('Vol1','Vol2','Vol3','Vol4');
var gBayName = new Array('Bay1','Bay2','Bay3','Bay4');



//========================================================//
// Table ID Create
//========================================================//
var gIdTableCreate=new Array('idTableCrtBay1','idTableCrtBay2','idTableCrtBay3','idTableCrtBay4','idTableCrtSelect');

//========================================================//
// Output ID Create
//========================================================//
var gIdOutputCreate = new Array(gBAYCOUNTMAX);

gIdOutputCreate[0] = new Array('id_CrtNameBay1','id_CrtModelBay1','id_CrtSizeBay1');
gIdOutputCreate[1] = new Array('id_CrtNameBay2','id_CrtModelBay2','id_CrtSizeBay2');
gIdOutputCreate[2] = new Array('id_CrtNameBay3','id_CrtModelBay3','id_CrtSizeBay3');
gIdOutputCreate[3] = new Array('id_CrtNameBay4','id_CrtModelBay4','id_CrtSizeBay4');
//========================================================//
// Input ID Create
//========================================================//
var gIdInputCreateBay = new Array('idCbCrtBay1','idCbCrtBay2','idCbCrtBay3','idCbCrtBay4');
var gIdInputCreateRaid = new Array('idRdoCrtNone','idRdoCrtLinear','idRdoCrtRaid0','idRdoCrtRaid1','idRdoCrtRaid5','idRdoCrtRaid10');
var gIdInputCreateLevel = new Array('id_CrtLevelNone','id_CrtLevelLinear','id_CrtLevelRaid0','id_CrtLevelRaid1','id_CrtLevelRaid5','id_CrtLevelRaid10');
var gIdInputCreateLevelName = new Array('NONE','JBOD','RAID0','RAID1','RAID5','RAID10');
//========================================================//
// Table ID Edit
//========================================================//
var gIdTableEdit=new Array('idTableEdtBay1','idTableEdtBay2','idTableEdtBay3','idTableEdtBay4');

//========================================================//
// Output ID Edit
//========================================================//
var gIdOutputEdit = new Array(gBAYCOUNTMAX);

gIdOutputEdit[0] = new Array('id_EdtNameBay1','id_EdtModelBay1','id_EdtSizeBay1','id_EdtStateBay1');
gIdOutputEdit[1] = new Array('id_EdtNameBay2','id_EdtModelBay2','id_EdtSizeBay2','id_EdtStateBay2');
gIdOutputEdit[2] = new Array('id_EdtNameBay3','id_EdtModelBay3','id_EdtSizeBay3','id_EdtStateBay3');
gIdOutputEdit[3] = new Array('id_EdtNameBay4','id_EdtModelBay4','id_EdtSizeBay4','id_EdtStateBay4');

gIdOutputEditBtn = new Array('idVolEditBtnRemove','idVolEditBtnAdd');

//========================================================//
// Input ID Edit
//========================================================//
var gIdInputEditBay = new Array('idCbEdtBay1','idCbEdtBay2','idCbEdtBay3','idCbEdtBay4');

//========================================================//
// Output ID Delete
//========================================================//
var gIdOutputDel = new Array('idDelVolume');

//========================================================//
// Output ID Expand
//========================================================//
var gIdOutputExp = new Array('idExpVolume','idExpVolumeToBe');
var gdwToBeSize = 0;
//========================================================//
// Table ID Migration
//========================================================//
var gIdTableMigrate=new Array('idTableMgrBay1','idTableMgrBay2','idTableMgrBay3','idTableMgrBay4','idTableMgrSelect');

//========================================================//
// Output ID Migration
//========================================================//
var gIdOutputMigrate = new Array(gBAYCOUNTMAX);

gIdOutputMigrate[0] = new Array('id_MgrNameBay1','id_MgrModelBay1','id_MgrSizeBay1');
gIdOutputMigrate[1] = new Array('id_MgrNameBay2','id_MgrModelBay2','id_MgrSizeBay2');
gIdOutputMigrate[2] = new Array('id_MgrNameBay3','id_MgrModelBay3','id_MgrSizeBay3');
gIdOutputMigrate[3] = new Array('id_MgrNameBay4','id_MgrModelBay4','id_MgrSizeBay4');

var gIdInputMigrateBay = new Array('idCbMgrBay1','idCbMgrBay2','idCbMgrBay3','idCbMgrBay4');
var gIdInputMigrateRaid = new Array('idRdoMgrLinear','idRdoMgrRaid1','idRdoMgrRaid5');
var gIdInputMigrateRaidTxt = new Array('id_MgrLevelLinear','id_MgrLevelRaid1','id_MgrLevelRaid5');

//========================================================//
// Information variable
//========================================================//
var gVolInfo = new Array(gVOLCOUNTMAX);
gVolInfo[0] = new VolInfoSt("","","","","","","","","","","","");
gVolInfo[1] = new VolInfoSt("","","","","","","","","","","","");
gVolInfo[2] = new VolInfoSt("","","","","","","","","","","","");
gVolInfo[3] = new VolInfoSt("","","","","","","","","","","","");

var gBayInfo = new Array(gBAYCOUNTMAX);
gBayInfo[0] = new BayInfoSt("","","","","","");
gBayInfo[1] = new BayInfoSt("","","","","","");
gBayInfo[2] = new BayInfoSt("","","","","","");
gBayInfo[3] = new BayInfoSt("","","","","","");

//========================================================//
// ID list
//========================================================//
var gIdpop = new Array('idPopCreate','idPopDelete','idPopEdit','idPopExpand','idPopMigrate');

//========================================================//
// Create volume popup window
//========================================================//
function open_cre_vol()
{
	if(IsThereFormattingVol())
		return false;
	if(!IsThereFreeBay())
		return false;
	
	open_popup(gIdpop[0]);
	//gVolMode = 'create';
	ShowCreateBayList(gBayInfo);
	//GetBayInfo();
	return true;
}

function close_cre_vol()
{
	close_popup(gIdpop[0]);
}

//========================================================//
// Delete volume popup window
//========================================================//
function open_del_vol()
{
	if(!CheckVolUnchecked())
		return false;
	
	open_popup(gIdpop[1]);
	//gVolMode = 'delete';
	ShowDeleteVolume(gVolInfo);
	//GetBayInfo();
	return true;
}
function close_del_vol()
{
	close_popup(gIdpop[1]);
}
//========================================================//
// Edit volume popup window
//========================================================//
function open_edit_vol()
{

	if(!IsRaidVolumeChecked()){
		return false;
	}
	
	if(IsThereFormattingVol())
		return false;
		
	// Check raid volume
	open_popup(gIdpop[2]);
	//gVolMode = 'edit';
	ShowEditBayList(gBayInfo);
	//GetBayInfo();
	return true;
}
function close_edit_vol()
{
	close_popup(gIdpop[2]);
	document.getElementById('idVolEditBtnRemove').style.display="none";
	document.getElementById('idVolEditBtnAdd').style.display="none";
}
//========================================================//
// Expand volume popup window
//========================================================//
function open_expand_vol()
{
	
		
	if(!IsRaidVolumeChecked()){
		return false;
	}
	
	if(IsThereFormattingVol())
		return false;
		
	if(!IsExpandable()){
		return false;
	}
	open_popup(gIdpop[3]);
	//gVolMode = 'expand';
	ShowExpandVolume(gVolInfo);
	//GetBayInfo();
	return true;
}
function close_expand_vol()
{
	var _id = gIdpop[3];
	close_popup(_id);
}

//========================================================//
// Migrate volume popup window
//========================================================// 
function open_migrate_vol()
{ 
	var vol_num=-1;
	for(var i=0; i<4; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num=i;
			break;
		}
	}  
	if(vol_num==-1){
		alert("<?php echo lang_get('volume_msg_2')?>");
		return false;
	}
		
	if(!IsThereFreeBay())
		return false; 
	if(!IsActiveVolume())
		return false;  
	if( ((gVolInfo[vol_num].level != 'raid1')||(gVolInfo[vol_num].baycnt != 2))&&
		((gVolInfo[vol_num].level != 'raid5')||(gVolInfo[vol_num].baycnt != 3))&&
		(gVolInfo[vol_num].level != 'none')&&
		(gVolInfo[vol_num].level != 'linear')){
		alert("<?php echo lang_get('volume_msg_3')?>");
		return false;
	}

	document.getElementById("idMigrateTable").style.display = "block"; 
	document.getElementById("idMigProgress").style.display = "none"; 
	ShowMigrateBayList(gBayInfo); 
	open_popup(gIdpop[4]);
	gVolMode = 'migrate';
	return true;
}
function close_migrate_vol()
{ 
	var _id = gIdpop[4];
	close_popup(_id); 
	document.getElementById("idMigrateTable").style.display = "block"; 
}

//========================================================//
// Popup window
//========================================================//
function open_popup(id)
{
	
	document.getElementById(id).style.display = 'block';
	//document.getElementById('create_table').style.display = 'block';
	document.getElementById('volume_table').style.display = 'none';

}
function close_popup(id)
{
	document.getElementById('volume_table').style.display = 'block';
	document.getElementById(id).style.display = 'none';
	//document.getElementById('create_table').style.display = 'none';
	
	GetVolInfo();
}

//========================================================//
// Get Info
//========================================================//
function GetVolInfo()
{
	for(var i=0; i<gVOLCOUNTMAX; i++){
		gVolInfo[i] = new VolInfoSt("","","","","","","","","","","","");
		gBayInfo[i] = new BayInfoSt("","","","","","");
	}
	
	for(var i=0; i<gVOLCOUNTMAX; i++){
		document.getElementById(gIdTable[i]).style.display = "none";
		document.getElementById(gIdLine[i]).style.display = "none";
		document.getElementById(gIdInputVolCheck[i]).style.display = "none";
		document.getElementById(gIdInputVolCheck[i]).checked = false;
		document.getElementById(gIdOutputVol[i][6]).style.display = "none";
	}
	
	document.getElementById(gIdTable[0]).style.display = "block";
	document.getElementById(gIdLine[0]).style.display = "block";
	
	
	document.getElementById(gIdOutputVol[0][0]).innerHTML = "";
	document.getElementById(gIdOutputVol[0][1]).innerHTML = "<?php echo lang_get('common_loading')?>";
	document.getElementById(gIdOutputVol[0][2]).innerHTML = "";
	document.getElementById(gIdOutputVol[0][3]).innerHTML = "";
	document.getElementById(gIdOutputVol[0][4]).innerHTML = "";
	document.getElementById(gIdOutputVol[0][5]).innerHTML = "";

	setTimeout("sendRequest(onGetBayVol,'&rdoVolBay='+'bay','post','../php/volume_get_info.php',true,true);", gVolSetDelayTime);
	gVolSetDelayTime=0;

	//var _txText = '&rdoVolBay='+'bay';
	//sendRequest(onGetBayVol,_txText,"post","../php/volume_get_info.php",true,true);
	
	return true;
}
function onGetBayVol(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var BayInfo = res.split('\n');

	//debug(BayInfo.length);
	for(var i=0; i<BayInfo.length-1; i++){

		var TmpBay = BayInfo[i].split(' ');
		
		gBayInfo[i].name = BayNameReverse(TmpBay[0]);
		gBayInfo[i].node = TmpBay[1];
		gBayInfo[i].stat = TmpBay[2];
		gBayInfo[i].size = TmpBay[3];
		gBayInfo[i].vendor = TmpBay[4];
		gBayInfo[i].model = TmpBay[5];
	}
	var _txText = '&rdoVolBay='+"vol";
	sendRequest(onGetVol,_txText,"post","../php/volume_get_info.php",true,true);
}
function onGetVol(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var VolInfo = res.split('\n');
	
	//debug(VolInfo.length);
	for(var i=0; i<VolInfo.length-1; i++){

		var TmpVol = VolInfo[i].split(' ');

		gVolInfo[i].name = TmpVol[0];
		gVolInfo[i].node = TmpVol[1];
		gVolInfo[i].stat = TmpVol[2];
		gVolInfo[i].size = TmpVol[3];
		gVolInfo[i].sizeu = TmpVol[4];
		gVolInfo[i].level = TmpVol[5];
		gVolInfo[i].mount = TmpVol[6];
		gVolInfo[i].baycnt = TmpVol[7];
		gVolInfo[i].baystat1 = BayNameReverse(TmpVol[8]);
		gVolInfo[i].baystat2 = BayNameReverse(TmpVol[9]);
		gVolInfo[i].baystat3 = BayNameReverse(TmpVol[10]);
		gVolInfo[i].baystat4 = BayNameReverse(TmpVol[11]);
	}
	ShowVolInfo(gVolInfo);
}
/*
function GetBayInfo()
{
	if(gVolMode == 'create' ){
		
		for(var i=0; i<4; i++){
			document.getElementById(gIdTableCreate[i]).style.display = "none";
			document.getElementById(gIdInputCreateBay[i]).style.display = "none";
			document.getElementById(gIdInputCreateLevel[i]).innerHTML = "";
			document.getElementById(gIdInputCreateBay[i]).checked = false;
		}
		for(var i=0; i<6; i++){
			document.getElementById(gIdInputCreateRaid[i]).style.display="none";//level radio
			document.getElementById(gIdInputCreateRaid[i]).checked = false;
		}
		document.getElementById(gIdTableCreate[0]).style.display = "block";
		document.getElementById(gIdOutputCreate[0][0]).innerHTML="";
		document.getElementById(gIdOutputCreate[0][1]).innerHTML="Loading...";
		document.getElementById(gIdOutputCreate[0][2]).innerHTML="";
		document.getElementById(gIdTableCreate[4]).style.display = "block";
	}else if(gVolMode == 'edit' ){
		for(var i=0; i<4; i++){
			document.getElementById(gIdTableEdit[i]).style.display = "none";
			document.getElementById(gIdInputEditBay[i]).style.display = "none";
			document.getElementById(gIdInputEditBay[i]).checked = false;
		}
		document.getElementById(gIdTableEdit[0]).style.display = "block";
		document.getElementById(gIdOutputEdit[0][0]).innerHTML="";
		document.getElementById(gIdOutputEdit[0][1]).innerHTML="Loading...";
		document.getElementById(gIdOutputEdit[0][2]).innerHTML="";
		document.getElementById(gIdOutputEdit[0][3]).innerHTML="";
	}else if(gVolMode == 'migrate' ){
		document.getElementById(gIdTableMigrate[0]).style.display = "block";
		document.getElementById(gIdTableMigrate[1]).style.display = "none";
		document.getElementById(gIdTableMigrate[2]).style.display = "none";
		document.getElementById(gIdTableMigrate[3]).style.display = "none";
		document.getElementById(gIdOutputMigrate[0][1]).innerHTML="";
		document.getElementById(gIdOutputMigrate[0][2]).innerHTML="Loading...";
		document.getElementById(gIdOutputMigrate[0][3]).innerHTML="";
		document.getElementById(gIdTableMigrate[4]).style.display = "block";
	}else if(gVolMode == 'delete' ){
		document.getElementById(gIdOutputDel[0]).innerHTML= "Loading...";
	}else if(gVolMode == 'expand' ){
		ShowExpandVolume(gVolInfo);
		return true;
	}

	//var _txText = '&rdoVolBay='+"bay";
	//sendRequest(onGetBay,_txText,"post","../php/volume_get_info.php",true,true);
	var _txText = '&rdoVolBay='+"vol";
	sendRequest(onGetVolBay,_txText,"post","../php/volume_get_info.php",true,true);
	return true;
}

function onGetVolBay(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var VolInfo = res.split('\n');
	
	//debug(VolInfo.length);
	for(var i=0; i<VolInfo.length-1; i++){

		var TmpVol = VolInfo[i].split(' ');

		gVolInfo[i].name = TmpVol[0];
		gVolInfo[i].node = TmpVol[1];
		gVolInfo[i].stat = TmpVol[2];
		gVolInfo[i].size = TmpVol[3];
		gVolInfo[i].sizeu = TmpVol[4];
		gVolInfo[i].level = TmpVol[5];
		gVolInfo[i].mount = TmpVol[6];
		gVolInfo[i].baycnt = TmpVol[7];
		gVolInfo[i].baystat1 = TmpVol[8];
		gVolInfo[i].baystat2 = TmpVol[9];
		gVolInfo[i].baystat3 = TmpVol[10];
		gVolInfo[i].baystat4 = TmpVol[11];
	}
	var _txText = '&rdoVolBay='+"bay";
	sendRequest(onGetBay,_txText,"post","../php/volume_get_info.php",true,true);
}
function onGetBay(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var BayInfo = res.split('\n');

	//debug(BayInfo.length);
	for(var i=0; i<BayInfo.length-1; i++){

		var TmpBay = BayInfo[i].split(' ');
		
		gBayInfo[i].name = TmpBay[0];
		gBayInfo[i].node = TmpBay[1];
		gBayInfo[i].stat = TmpBay[2];
		gBayInfo[i].size = TmpBay[3];
		gBayInfo[i].vendor = TmpBay[4];
		gBayInfo[i].model = TmpBay[5];
	}
	
	if(gVolMode == 'create' )
		ShowCreateBayList(gBayInfo);
	else if(gVolMode == 'edit' )
		ShowEditBayList(gBayInfo);
	else if(gVolMode == 'migrate' )
		ShowMigrateBayList(gBayInfo);
	else if(gVolMode == 'delete' )
		ShowDeleteVolume(gVolInfo);
}
*/
/*
function GetMigrateBayInfo()
{
	document.getElementById(gIdTableMigrate[0]).style.display = "block";
	document.getElementById(gIdTableMigrate[1]).style.display = "none";
	document.getElementById(gIdTableMigrate[2]).style.display = "none";
	document.getElementById(gIdTableMigrate[3]).style.display = "none";
	document.getElementById(gIdOutputMigrate[0][1]).innerHTML="";
	document.getElementById(gIdOutputMigrate[0][2]).innerHTML="Loading...";
	document.getElementById(gIdOutputMigrate[0][3]).innerHTML="";
	document.getElementById(gIdTableMigrate[4]).style.display = "block";
	
	var _txText = '&rdoVolBay='+"bay";
	sendRequest(onGetMigrateBay,_txText,"post","../php/volume_get_info.php",true,true);
	return true;
}


function onGetMigrateBay(oj)
{
	var res = decodeURIComponent(oj.responseText);
	//debug(res);
	var BayInfo = res.split('\n');
 
	//debug(BayInfo.length);
	for(var i=0; i<BayInfo.length-1; i++){

		var TmpBay = BayInfo[i].split(' ');
		
		gBayInfo[i].name = TmpBay[0];
		gBayInfo[i].node = TmpBay[1];
		gBayInfo[i].stat = TmpBay[2]; 
		gBayInfo[i].size = TmpBay[3];
		gBayInfo[i].vendor = TmpBay[4];
		gBayInfo[i].model = TmpBay[5];
	} 
	ShowMigrateBayList(gBayInfo);
	                     
	return true;
	
}
*/
//========================================================//
// Show Volume info
//========================================================//
function ShowVolInfo(volinfo)
{
	for(var i=0; i<gVOLCOUNTMAX; i++){
		// no volume list
		if(!volinfo[i].name) {
			if(i == 0){
				document.getElementById(gIdOutputVol[i][1]).innerHTML="<?php echo lang_get('volume_8')?>";
			}
			break;
		}
		//debug(i);

		var baystat="";
		if(gBayReverse){
			baystat += (volinfo[i].baystat4)? volinfo[i].baystat4.split(':')[0]+" ":"";
			baystat += (volinfo[i].baystat3)? volinfo[i].baystat3.split(':')[0]+" ":"";
			baystat += (volinfo[i].baystat2)? volinfo[i].baystat2.split(':')[0]+" ":"";
			baystat += (volinfo[i].baystat1)? volinfo[i].baystat1.split(':')[0]+" ":"";
		}else{
			baystat += (volinfo[i].baystat1)? volinfo[i].baystat1.split(':')[0]+" ":"";
			baystat += (volinfo[i].baystat2)? volinfo[i].baystat2.split(':')[0]+" ":"";
			baystat += (volinfo[i].baystat3)? volinfo[i].baystat3.split(':')[0]+" ":"";
			baystat += (volinfo[i].baystat4)? volinfo[i].baystat4.split(':')[0]+" ":"";
		}
		var volpercent=parseInt(volinfo[i].sizeu*10000/volinfo[i].size+0.5)/100;
		
		document.getElementById(gIdTable[i]).style.display = "block";
		document.getElementById(gIdLine[i]).style.display = "block";
		document.getElementById(gIdInputVolCheck[i]).style.display = "block";
		document.getElementById(gIdOutputVol[i][0]).innerHTML=volinfo[i].name;
		
		document.getElementById(gIdOutputVol[i][1]).innerHTML=baystat;
		if(volinfo[i].level == 'linear')
			document.getElementById(gIdOutputVol[i][2]).innerHTML= "jbod";
		else
			document.getElementById(gIdOutputVol[i][2]).innerHTML=volinfo[i].level;
		if(volinfo[i].stat.split('_')[0] =='synching')
			document.getElementById(gIdOutputVol[i][3]).innerHTML="syncing("+volinfo[i].stat.split('_')[1]+"%)";
		else if(volinfo[i].stat.split('_')[0] =='migrating'){
			document.getElementById(gIdOutputVol[i][3]).innerHTML="migrating("+volinfo[i].stat.split('_')[1]+"%)";
		}
		else
			document.getElementById(gIdOutputVol[i][3]).innerHTML=volinfo[i].stat;
		if(volinfo[i].stat == 'formatting'){
			document.getElementById(gIdOutputVol[i][4]).innerHTML="Formatting...";
			document.getElementById(gIdOutputVol[i][5]).innerHTML="---/---"; 
		}else if(volinfo[i].stat.split('_')[0] =='migrating'){
			document.getElementById(gIdOutputVol[i][4]).innerHTML="Migrating...";
			document.getElementById(gIdOutputVol[i][5]).innerHTML="---/---";
//		}else if(volinfo[i].stat == 'destroyed'){
//			document.getElementById(gIdInputVolCheck[i]).checked=true;
//			open_popup(gIdpop[1]);
//			ShowDeleteVolume(volinfo);
//			document.getElementById(gIdOutputDel[0]).innerHTML= 
//			"Warning message : "+volinfo[i].name+" is destroyed.<br>This volume is no more active. Delete?";
		}else{
			document.getElementById(gIdOutputVol[i][4]).innerHTML=volpercent+"%";
			document.getElementById(gIdOutputVol[i][5]).innerHTML=VolRep(volinfo[i].sizeu)+"/"+VolRep(volinfo[i].size);
			document.getElementById(gIdOutputVol[i][6]).style.display="block";
			var tmpVolProgWidth=parseInt(volpercent);
			if(tmpVolProgWidth<1)
				document.getElementById(gIdOutputVol[i][7]).width=1; 
			else
				document.getElementById(gIdOutputVol[i][7]).width=tmpVolProgWidth; 
		}
	}
}

//========================================================//
// Show Free Bay List (Create)
//========================================================//
function ShowCreateBayList(bayinfo)
{
	//debug('ShowCreateBayList');
	document.getElementById(gIdTableCreate[4]).style.display = "block";

	for(var i=0; i<gBAYCOUNTMAX; i++){
		document.getElementById(gIdTableCreate[i]).style.display = "none";
		document.getElementById(gIdInputCreateBay[i]).style.display = "none";
		document.getElementById(gIdInputCreateLevel[i]).innerHTML = "";
		document.getElementById(gIdInputCreateBay[i]).checked = false;
	}
	if(gBayReverse){
		for(var i=0; i<gBAYCOUNTMAX; i++){ 
			document.getElementById(gIdInputCreateBay[i]).checked = false;
			if(bayinfo[gBAYCOUNTMAX-1-i].stat == 'free'){
				document.getElementById(gIdTableCreate[i]).style.display = "block";
				document.getElementById(gIdInputCreateBay[i]).style.display = "block";
				document.getElementById(gIdOutputCreate[i][0]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].name;
				document.getElementById(gIdOutputCreate[i][1]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].vendor+" "+bayinfo[gBAYCOUNTMAX-1-i].model;
				document.getElementById(gIdOutputCreate[i][2]).innerHTML=VolRep(bayinfo[gBAYCOUNTMAX-1-i].size);
				
			}
			else
				document.getElementById(gIdTableCreate[i]).style.display = "none";
		}
	}else{
		for(var i=0; i<gBAYCOUNTMAX; i++){ 
			document.getElementById(gIdInputCreateBay[i]).checked = false;
			if(bayinfo[i].stat == 'free'){
				document.getElementById(gIdTableCreate[i]).style.display = "block";
				document.getElementById(gIdInputCreateBay[i]).style.display = "block";
				document.getElementById(gIdOutputCreate[i][0]).innerHTML=bayinfo[i].name;
				document.getElementById(gIdOutputCreate[i][1]).innerHTML=bayinfo[i].vendor+" "+bayinfo[i].model;
				document.getElementById(gIdOutputCreate[i][2]).innerHTML=VolRep(bayinfo[i].size);
				
			}
			else
				document.getElementById(gIdTableCreate[i]).style.display = "none";
		}
	}
	for(var i=0; i<6; i++){
		document.getElementById(gIdInputCreateRaid[i]).style.display="none";//level radio
		document.getElementById(gIdInputCreateRaid[i]).checked = false; 
		document.getElementById(gIdInputCreateLevel[i]).innerHTML = "";
	}
}
//========================================================//
// Show volume info (Delete)
//========================================================//
function ShowDeleteVolume(volinfo)
{
	var vol_num;
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num = i;
			break;
		}
	}
	document.getElementById(gIdOutputDel[0]).innerHTML= 
	"<?php echo lang_get('volume_msg_23')?> "+volinfo[vol_num].name+"("+VolRep(volinfo[vol_num].size)+") <?php echo lang_get('volume_msg_23_1')?>";
}
//========================================================//
// Show Free and Selected Raid Bay List (Edit)
//========================================================//
function ShowEditBayList(bayinfo)
{
	//debug('ShowCreateBayList');

	var vol_num, vol_baycnt, temp;
	
	for(var i=0; i<gBAYCOUNTMAX; i++){
		document.getElementById(gIdTableEdit[i]).style.display = "none";
		document.getElementById(gIdInputEditBay[i]).style.display = "none";
		document.getElementById(gIdInputEditBay[i]).checked = false;
	}
	
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num = i;
			break;
		}
	}
	vol_baycnt = gVolInfo[vol_num].baycnt; 

	temp = new Array(vol_baycnt);
	//debug(vol_baycnt);

	if(vol_baycnt)
		temp[0] = gVolInfo[vol_num].baystat1.split(':')[0];
	if(vol_baycnt >= 2)	
		temp[1] = gVolInfo[vol_num].baystat2.split(':')[0];
	if(vol_baycnt >= 3)	
		temp[2] = gVolInfo[vol_num].baystat3.split(':')[0];
	if(vol_baycnt >= 4)	
		temp[3] = gVolInfo[vol_num].baystat4.split(':')[0];
	
	for(var j=0; j<vol_baycnt; j++){
		if(temp[j]){
			if(gBayReverse){
				for(var i=0; i<gBAYCOUNTMAX; i++){
					if((gBayInfo[gBAYCOUNTMAX-1-i].name == temp[j])||(gBayInfo[gBAYCOUNTMAX-1-i].stat == 'free')){
						//debug("i: "+i+" j: "+j+" temp[j]="+temp[j]);
						document.getElementById(gIdInputEditBay[i]).style.display = "block";
						document.getElementById(gIdTableEdit[i]).style.display = "block";
						document.getElementById(gIdInputEditBay[i]).checked = false;
						document.getElementById(gIdOutputEdit[i][0]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].name;
						document.getElementById(gIdOutputEdit[i][1]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].vendor+" "+bayinfo[gBAYCOUNTMAX-1-i].model;
						document.getElementById(gIdOutputEdit[i][2]).innerHTML=VolRep(bayinfo[gBAYCOUNTMAX-1-i].size);
						document.getElementById(gIdOutputEdit[i][3]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].stat;
					}
				}
			}else{
				for(var i=0; i<gBAYCOUNTMAX; i++){
					if((gBayInfo[i].name == temp[j])||(gBayInfo[i].stat == 'free')){
						//debug("i: "+i+" j: "+j+" temp[j]="+temp[j]);
						document.getElementById(gIdInputEditBay[i]).style.display = "block";
						document.getElementById(gIdTableEdit[i]).style.display = "block";
						document.getElementById(gIdInputEditBay[i]).checked = false;
						document.getElementById(gIdOutputEdit[i][0]).innerHTML=bayinfo[i].name;
						document.getElementById(gIdOutputEdit[i][1]).innerHTML=bayinfo[i].vendor+" "+bayinfo[i].model;
						document.getElementById(gIdOutputEdit[i][2]).innerHTML=VolRep(bayinfo[i].size);
						document.getElementById(gIdOutputEdit[i][3]).innerHTML=bayinfo[i].stat;
					}
				}
			}
		}
	}
}
//========================================================//
// Show volume info (Expand)
//========================================================//
function ShowExpandVolume(volinfo)
{
	var bVolNum = VolNumChecked();
	if(bVolNum == 0xff){
		alert("<?php echo lang_get('volume_msg_2')?>");
		return false;
	}
	
	//volToBeSize="1.43TB";
	//ToDo;
	
	document.getElementById(gIdOutputExp[0]).innerHTML= volinfo[bVolNum].name+"("+VolRep(volinfo[bVolNum].size)+")";
	document.getElementById(gIdOutputExp[1]).innerHTML= volinfo[bVolNum].name+"("+VolRep(gdwToBeSize)+")";
}
//========================================================//
// Show Free Bay List (Migrate)
//========================================================//
function ShowMigrateBayList(bayinfo)
{  
	document.getElementById(gIdTableMigrate[4]).style.display = "block";

	for(var i=0; i<gBAYCOUNTMAX; i++){
		document.getElementById(gIdTableMigrate[i]).style.display = "none";
		document.getElementById(gIdInputMigrateBay[i]).style.display = "none"; 
		document.getElementById(gIdInputMigrateBay[i]).checked = false;
	}
	for(var i=0; i<3; i++){
		document.getElementById(gIdInputMigrateRaid[i]).style.display="none";//level radio
		document.getElementById(gIdInputMigrateRaid[i]).checked = false; 
		document.getElementById(gIdInputMigrateRaidTxt[i]).innerHTML = "";
	}
	if(gBayReverse){
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(bayinfo[gBAYCOUNTMAX-1-i].stat == 'free'){
				document.getElementById(gIdTableMigrate[i]).style.display = "block";
				document.getElementById(gIdInputMigrateBay[i]).style.display = "block";
				document.getElementById(gIdOutputMigrate[i][0]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].name;
				document.getElementById(gIdOutputMigrate[i][1]).innerHTML=bayinfo[gBAYCOUNTMAX-1-i].vendor+" "+bayinfo[gBAYCOUNTMAX-1-i].model;
				document.getElementById(gIdOutputMigrate[i][2]).innerHTML=VolRep(bayinfo[gBAYCOUNTMAX-1-i].size);
				
			}
			else
				document.getElementById(gIdTableMigrate[i]).style.display = "none";
		}
	}else{
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(bayinfo[i].stat == 'free'){
				document.getElementById(gIdTableMigrate[i]).style.display = "block";
				document.getElementById(gIdInputMigrateBay[i]).style.display = "block";
				document.getElementById(gIdOutputMigrate[i][0]).innerHTML=bayinfo[i].name;
				document.getElementById(gIdOutputMigrate[i][1]).innerHTML=bayinfo[i].vendor+" "+bayinfo[i].model;
				document.getElementById(gIdOutputMigrate[i][2]).innerHTML=VolRep(bayinfo[i].size);
				
			}
			else
				document.getElementById(gIdTableMigrate[i]).style.display = "none";
		}
	}
}
//========================================================//
// general function
//========================================================//
function VolRep(tmp)
{
	for(cnt=0;tmp>1024;cnt++){
		tmp=tmp/1024;
	}
	if (cnt==0){
		tmp=tmp+0.5;
		return(parseInt(tmp)+" B");
	} else if (cnt==1){
		tmp=tmp+0.5;
		return(parseInt(tmp)+" kB");
	} else if (cnt==2){
		tmp=tmp+0.5;
		return(parseInt(tmp)+" MB");
	} else if (cnt==3){
		if(tmp<10){ 
			tmp=parseInt(tmp*100+0.5)/100;
		}else{
			tmp=tmp+0.5;
			tmp=parseInt(tmp);
		}
		return(tmp+" GB");
	} else if (cnt==4){
		if(tmp<10){
			tmp=parseInt(tmp*100+0.5)/100;
		}else{
			tmp=tmp+0.5;
			tmp=parseInt(tmp);
		}
		return(tmp+" TB"); 
	}
}

function BayCount()
{
	for(var i=0; i<4; i++){
		if(gBayInfo[i].name)
			continue;
		else
			break;
	}
	return i;
}

function VolCount()
{
	for(var i=0; i<4; i++){
		if(gVolInfo[i].name)
			continue;
		else
			break;
	}
	return i;
}
function BayNameReverse(sBayName)
{
	if(!gBayReverse)
		return sBayName;
		
	if(!sBayName)
		return sBayName;
		
	if(sBayName.match('Bay1'))
		return sBayName.replace(/Bay1/,"B4");
	else if(sBayName.match('Bay2'))
		return sBayName.replace(/Bay2/,"B3");
	else if(sBayName.match('Bay3'))
		return sBayName.replace(/Bay3/,"B2");
	else if(sBayName.match('Bay4'))
		return sBayName.replace(/Bay4/,"B1");
	else
		return sBayName;
}
function VolIndexChecked()
{
	var bCheckIndex = 0; // 1,2,4,8

	for(var i=0; i<VolCount(); i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			bCheckIndex += (1 << i);
		}
	}
	return bCheckIndex;
}

function VolIncludeBayIndex(bVolNum)
{
	var bIncBayIndex = 0; // 1,2,4,8
	var bBayCnt = BayCount();
	var bVolBayCnt = gVolInfo[bVolNum].baycnt; 

	temp = new Array(bVolBayCnt);
	//debug(vol_baycnt);
	if(bVolBayCnt)
		temp[0] = gVolInfo[bVolNum].baystat1.split(':')[0];
	if(bVolBayCnt >= 2)	
		temp[1] = gVolInfo[bVolNum].baystat2.split(':')[0];
	if(bVolBayCnt >= 3)	
		temp[2] = gVolInfo[bVolNum].baystat3.split(':')[0];
	if(bVolBayCnt >= 4)	
		temp[3] = gVolInfo[bVolNum].baystat4.split(':')[0];
	//debug(temp[0]+"-"+temp[1]+"-"+temp[2]+"-"+temp[3]);

	for(var j=0; j<bVolBayCnt; j++){
		for(var i=0; i<bBayCnt; i++){
			if(gBayInfo[i].name == temp[j]){
				bIncBayIndex += (1<<i);
			}
		}
	}

	return bIncBayIndex;
}

function CheckVolUnchecked()
{
	var bVolCheckedIndex = VolIndexChecked();
	if(!bVolCheckedIndex){
		alert("<?php echo lang_get('volume_msg_2')?>");
	}
	return bVolCheckedIndex;
}

function VolNumChecked()
{
	var bVolIndexCheck = VolIndexChecked();
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(bVolIndexCheck == (1<<i))
			return i;
	}
	return 0xff;
}

function IsRaidVolumeChecked()
{
	var bVolCnt = VolCount();
	var bVolCheckedIndex = CheckVolUnchecked();
	if(!bVolCheckedIndex)
		return false;
	
	for(var i=0; i<bVolCnt; i++){
		if(bVolCheckedIndex == (1<<i)){
			if(gVolInfo[i].level == 'none'){
				alert("<?php echo lang_get('volume_msg_5')?>");
				return false;
			}
			if( gVolInfo[i].stat == 'sys_synching'){
				alert("<?php echo lang_get('volume_msg_6')?>");
				return false;
			}
		}
	}
	return true;
}

function IsThereFormattingVol()
{
	var bVolCnt = VolCount();
		
	for(var i=0; i<bVolCnt; i++){
		if(gVolInfo[i].stat == 'formatting'){
			_msg = "<?php echo lang_get('volume_msg_14')?>";
			alert("Vol"+(i+1)+" : "+_msg.replaceAll('<BR />','\n'));
			return true;
		}
	}
	return false;
}

function IsThereFreeBay()
{
	var bBayCnt = BayCount();
	
	for(var i=0; i<bBayCnt; i++){
		if(gBayInfo[i].stat == 'free' ){
			return true;
		}
	}

	alert("<?php echo lang_get('volume_msg_8')?>");
	return false;
}

function IsExpandable()
{
	var bVolCheckedIndex = CheckVolUnchecked();
	var dwCmpSize=0;
	var bVolNum = VolNumChecked();
	
	if(bVolNum == 0xff){

		alert("<?php echo lang_get('volume_msg_2')?>");
		return false;
	}
	if( gVolInfo[bVolNum].stat == 'sys_synching'){
		alert("<?php echo lang_get('volume_msg_6')?>");
		return false;
	}
	
	// Check Expandable Raid level
	if( (gVolInfo[bVolNum].level != 'raid1')&&
		(gVolInfo[bVolNum].level != 'raid5')&&
		(gVolInfo[bVolNum].level != 'raid10')){

		alert("<?php echo lang_get('volume_msg_9')?>");
		return false;
	}
	// Check All Volume Bay Active
	var bVolIncBayIndex = VolIncludeBayIndex(bVolNum);
	for(var i=0; i<gBAYCOUNTMAX; i++){
		if(bVolIncBayIndex & (1<<i)){
			if(gBayInfo[i].stat != 'active'){
				_msg = "<?php echo lang_get('volume_msg_10')?>"; 
				alert(_msg.replaceAll('<BR />','\n'));
				return false;
			}
		}
	}
	
	// Check Expandable Volume Size
	// raid1: All Bay Size > Volume Size 						+ 3GB(sys:2GB + swap:256MB) 
	// raid5: All Bay Size > Volume Size / (VolIncBayCount-1) 	+ 3GB(sys:2GB + swap:256MB)
	// raid10:All Bay Size > Volume Size / (VolIncBayCount/2) 	+ 3GB(sys:2GB + swap:256MB)
	if(gVolInfo[bVolNum].level == 'raid1'){
		dwCmpSize = gVolInfo[bVolNum].size;
	}else if(gVolInfo[bVolNum].level == 'raid5'){
		dwCmpSize = gVolInfo[bVolNum].size/(gVolInfo[bVolNum].baycnt - 1);
	}else if(gVolInfo[bVolNum].level == 'raid10'){
		dwCmpSize = gVolInfo[bVolNum].size/(gVolInfo[bVolNum].baycnt / 2);
	}
	
	dwCmpSize = dwCmpSize * 1.1 + 2147483648 + 268245456;
	
	debug(dwCmpSize);
	var dwMinBaySize=0;
	for(var i=0; i<gBAYCOUNTMAX; i++){
		if(bVolIncBayIndex & (1<<i)){
			debug("gBayInfo["+i+"].size = "+gBayInfo[i].size);
			if(gBayInfo[i].size < dwCmpSize){
				alert("<?php echo lang_get('volume_msg_11')?>"+(i+1)+" <?php echo lang_get('volume_msg_11_1')?>"+VolRep(dwCmpSize) + "<?php echo lang_get('volume_msg_11_2')?>");
				return false;
			}else{
				if(!dwMinBaySize)
					dwMinBaySize = gBayInfo[i].size;
				else
					dwMinBaySize = Math.min(dwMinBaySize,gBayInfo[i].size);
			}
		}
	}
	// To Be Size
	// raid1	: minimum disk size 
	// raid5	: minimum disk size * (disk cnt - 1)
	// raid10	: minimum disk size * disk cnt /2  
	
	if(gVolInfo[bVolNum].level == 'raid1'){
		gdwToBeSize = dwMinBaySize;
	}else if(gVolInfo[bVolNum].level == 'raid5'){
		gdwToBeSize = dwMinBaySize * (gVolInfo[bVolNum].baycnt - 1);
	}else if(gVolInfo[bVolNum].level == 'raid10'){
		gdwToBeSize = dwMinBaySize * (gVolInfo[bVolNum].baycnt / 2);
	}
	
	return true;
}

function IsActiveVolume(){
	var vol_num=-1;
	for(var i=0; i<4; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num=i;
			break;
		}
	}
	var tmpMgrStat="inactive";
	
	if(vol_num>-1){ 
		if(gVolInfo[vol_num].baycnt ==1 ){
		tmpMgrStat=gVolInfo[vol_num].stat;
			if( (gVolInfo[vol_num].mount =="on") &&  (gVolInfo[vol_num].stat != "formatting") && (gVolInfo[vol_num].stat != "migrating") ){
 				tmpMgrStat="active";  
			}
		} else{
			tmpMgrStat=gVolInfo[vol_num].stat;
		}
	} 
	if(tmpMgrStat == "active"){
		return true;
	} else if(tmpMgrStat == "degraded") {
		_msg = "<?php echo lang_get('volume_msg_12')?>"
		alert(_msg.replaceAll('<BR />','\n')); 
		return false;
	} else if(tmpMgrStat == "removed") {
		_msg = "<?php echo lang_get('volume_msg_13')?>"
		alert(_msg.replaceAll('<BR />','\n')); 
		return false;
	} else if(tmpMgrStat == "formatting") {
		_msg = "<?php echo lang_get('volume_msg_14')?>"
		alert(_msg.replaceAll('<BR />','\n')); 
		return false;
	} else if(tmpMgrStat == "migrating") {
		_msg = "<?php echo lang_get('volume_msg_15')?>"
		alert(_msg.replaceAll('<BR />','\n')); 
		return false; 
	} else if(tmpMgrStat == "resizing") {
		_msg = "<?php echo lang_get('volume_msg_16')?>"
		alert(_msg.replaceAll('<BR />','\n')); 
		return false; 
	} else if(tmpMgrStat == "sys_synching"){
		alert("<?php echo lang_get('volume_msg_6')?>");
		return false;
	} else {
		_msg = "<?php echo lang_get('volume_msg_17')?>"
		alert(_msg.replaceAll('<BR />','\n')); 
		return false;
	}
}

function check_create(check)
{
	var cnt_check=0;
	
	for(var i=0; i<6; i++){
		document.getElementById(gIdInputCreateRaid[i]).style.display="none";//level radio
		document.getElementById(gIdInputCreateRaid[i]).checked = false;
		document.getElementById(gIdInputCreateLevel[i]).innerHTML = "";
	}

	for(var i=0; i<gBAYCOUNTMAX; i++){
		if(document.getElementById(gIdInputCreateBay[i]).checked){
			cnt_check++;
		}
	}
	
	if(cnt_check == 1){
		document.getElementById(gIdInputCreateRaid[0]).style.display="block";//none
		document.getElementById(gIdInputCreateRaid[0]).checked=true;
		document.getElementById(gIdInputCreateLevel[0]).innerHTML = gIdInputCreateLevelName[0];
	}else if(cnt_check >= 2){
		document.getElementById(gIdInputCreateRaid[0]).checked=false;
		document.getElementById(gIdInputCreateRaid[1]).style.display="block";//linear
		document.getElementById(gIdInputCreateRaid[1]).checked=true;
		document.getElementById(gIdInputCreateLevel[1]).innerHTML = gIdInputCreateLevelName[1];
		document.getElementById(gIdInputCreateRaid[2]).style.display="block";//raid0
		document.getElementById(gIdInputCreateLevel[2]).innerHTML = gIdInputCreateLevelName[2];
		document.getElementById(gIdInputCreateRaid[3]).style.display="block";//raid1
		document.getElementById(gIdInputCreateLevel[3]).innerHTML = gIdInputCreateLevelName[3];
	}
	
	if(cnt_check >= 3){
		document.getElementById(gIdInputCreateRaid[0]).checked=false;
		document.getElementById(gIdInputCreateRaid[4]).style.display="block";//raid5
		document.getElementById(gIdInputCreateLevel[4]).innerHTML = gIdInputCreateLevelName[4];
	}
	if(cnt_check == 4){
		document.getElementById(gIdInputCreateRaid[0]).checked=false;
		document.getElementById(gIdInputCreateRaid[5]).style.display="block";//raid10
		document.getElementById(gIdInputCreateLevel[5]).innerHTML = gIdInputCreateLevelName[5];
	}
	return true;
}

function check_edit(check)
{
	//gIdOutputEditBtn
	var bay_num;
	
	var obj = document.getElementsByName("CbEdtBay");
	for(var i=0; i<obj.length; i++){
		if(obj[i] != check){
			obj[i].checked = false;
		}
	}

	for(var i=0; i<2; i++){
		document.getElementById(gIdOutputEditBtn[i]).style.display="none";
	}
	if(gBayReverse){
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputEditBay[i]).checked){
				bay_num=gBAYCOUNTMAX-1-i;
				break;
			}
		}
	}else{
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputEditBay[i]).checked){
				bay_num=i;
				break;
			}
		}
	}
	if( gBayInfo[bay_num].stat == 'free' )
		document.getElementById(gIdOutputEditBtn[1]).style.display="block";
	else
		document.getElementById(gIdOutputEditBtn[0]).style.display="block";

	return true;
}

function check_migrate(check)
{
	var cnt_check=0;
	var vol_num=-1;
	
	for(var i=0; i<3; i++){
		document.getElementById(gIdInputMigrateRaid[i]).style.display="none";//level radio
		document.getElementById(gIdInputMigrateRaid[i]).checked = false;
		document.getElementById(gIdInputMigrateRaidTxt[i]).innerHTML = "";
	}
		
		
	for(var i=0; i<4; i++){
		if(document.getElementById(gIdInputMigrateBay[i]).checked){
			cnt_check++;
		}
	}
	for(var i=0; i<4; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num=i;
			break;
		}
	}			
	var str_vol_lvl_crt="disable"; 
	
	if(vol_num>-1 && cnt_check>0 && gVolInfo[vol_num].baycnt >0){
		str_vol_lvl_crt=gVolInfo[vol_num].level;  
		if(gVolInfo[vol_num].baycnt == 1){ 
//			document.getElementById(gIdInputMigrateRaid[0]).style.display="block";//linear
//			document.getElementById(gIdInputMigrateRaidTxt[0]).innerHTML="JBOD";
			document.getElementById(gIdInputMigrateRaid[1]).style.display="block";//raid1
			document.getElementById(gIdInputMigrateRaidTxt[1]).innerHTML="RAID1";
			if(cnt_check>1){
				document.getElementById(gIdInputMigrateRaid[2]).style.display="block";//raid5
				document.getElementById(gIdInputMigrateRaidTxt[2]).innerHTML="RAID5";
			}
		} else {	
//			if(str_vol_lvl_crt=="linear") {
//				document.getElementById(gIdInputMigrateRaid[0]).style.display="block";//linear
//				document.getElementById(gIdInputMigrateRaidTxt[0]).innerHTML="JBOD"; 
//				document.getElementById(gIdInputMigrateRaid[0]).checked=true; 
//			} else 
			if(str_vol_lvl_crt=="raid1" && gVolInfo[vol_num].baycnt ==2){ 
				document.getElementById(gIdInputMigrateRaid[2]).style.display="block";//raid5 
				document.getElementById(gIdInputMigrateRaidTxt[2]).innerHTML="RAID5"; 
				document.getElementById(gIdInputMigrateRaid[2]).checked=true; 
			} else if(str_vol_lvl_crt=="raid5"){
				document.getElementById(gIdInputMigrateRaid[2]).style.display="block";//raid5
				document.getElementById(gIdInputMigrateRaidTxt[2]).innerHTML="RAID5";  
				document.getElementById(gIdInputMigrateRaid[2]).checked=true; 
			}	
		}  
	}  
}

function aa(chk_index,out_id)
{
	var in_id = "idVolName"+chk_index;
	var _tmp = document.getElementById(in_id).innerHTML;
	gTranVolName= _tmp;
	document.getElementById(out_id).innerHTML = _tmp;
}

function VolInfoSt(name,node,stat,size,sizeu,level,mount,baycnt,baystat1,baystat2,baystat3,baystat4)
{
	this.name = name;
	this.node = node;
	this.stat = stat;
	this.size = size;
	this.sizeu = sizeu;
	this.level = level;
	this.mount = mount;
	this.baycnt = baycnt;
	this.baystat1 = baystat1;
	this.baystat2 = baystat2;
	this.baystat3 = baystat3;
	this.baystat4 = baystat4;
}

function BayInfoSt(name,node,stat,size,vendor,model)
{
	this.name = name;
	this.node = node;
	this.stat = stat;
	this.size = size;
	this.vendor = vendor;
	this.model = model;
}

//vol or bay
function init_vol() {
	GetVolInfo();
}


//========================================================//
// (1)Create volume
//========================================================//
function create_vol()
{
	//debug('create volume');
	var volname, volcnt, vol_node, level,disk_nodes,disk_node;
	
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(gVolInfo[i].name)
			continue;
		else{
			volcnt = i;
			break;
		}
	}
	
	//search unused volume name
	for(var j=0; j<gVOLCOUNTMAX; j++){
		for(var i=0; i<volcnt; i++){
			if(gVolInfo[i].name == gVolName[j]){
				break;
			}
		}
		if(i==volcnt){
			volname = gVolName[j];
			break;
		}
	}

	//disk node
	disk_nodes = "";
	disk_cnt=0;
	if(gBayReverse){
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputCreateBay[i]).checked){
				disk_cnt++;
				disk_node = gBayInfo[gBAYCOUNTMAX-1-i].node;
				disk_nodes += gBayInfo[gBAYCOUNTMAX-1-i].node+" ";
			}
		}
	}else{
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputCreateBay[i]).checked){
				disk_cnt++;
				disk_node = gBayInfo[i].node;
				disk_nodes += gBayInfo[i].node+" ";
			}
		}
	}	
	//volume node
	if(disk_cnt == 1){
		vol_node = disk_node+"3";
	}else if(disk_cnt > 1){
		vol_node = "";
		for(var i=0; i<4; i++){
			if(gVolInfo[i].node == 'md2' ){
				vol_node = 'md3';
				break;
			}
		}
		if(!vol_node)
			vol_node = 'md2';
	}else{
		alert("<?php echo lang_get('volume_msg_18')?>");
		return false;
	}

	
	//raid level		
	if(disk_cnt == 1){
		level = 'NONE';
	}else if(disk_cnt > 1){
		for(var i=0; i<6; i++){
			if(document.getElementById(gIdInputCreateRaid[i]).checked){
				level = document.getElementById(gIdInputCreateRaid[i]).value
				break;
			}
		}
	}else{
		alert('error : no level check');
		return false;
	}
	debug("volname:"+volname+"vol_node:"+vol_node+"disk_cnt:"+disk_cnt+"level:"+level+"disk_nodes:"+disk_nodes);
	
	var _txText = '&rdoVolSet='+'create'
		+"&rdoVolName="+volname
		+"&rdoVolNode="+vol_node
		+"&rdoVolLevel="+level
		+"&rdoVolDiskNode="+disk_nodes;
		
	/*
	document.getElementById(gIdOutputVol[1][0]).innerHTML = "Vol1";
	document.getElementById(gIdOutputVol[1][1]).innerHTML = "<?php echo lang_get('common_setting')?>";
	document.getElementById(gIdOutputVol[1][2]).innerHTML = "";
	document.getElementById(gIdOutputVol[1][3]).innerHTML = "";
	document.getElementById(gIdOutputVol[1][4]).innerHTML = "";
	document.getElementById(gIdOutputVol[1][5]).innerHTML = "";
	*/
	sendRequest(onSetVol,_txText,"post","../php/volume_set_info.php",true,true);
        gVolSetDelayTime=0;
        document.getElementById('idCreateBayList').style.display="none";
        document.getElementById('idCreateWaitTxt').innerHTML=
        "<?php echo lang_get('volume_msg_30')?> <BR />"+"<?php echo lang_get('volume_msg_32')?> "+volname+" <?php echo lang_get('volume_msg_32_1')?>";
        document.getElementById('idCreateWait').style.display="block";
        setTimeout("set_create_closing()",15000);
        return true;
}
function set_create_closing(){
        close_popup(gIdpop[0]);        
        document.getElementById('idCreateWait').style.display="none";
        document.getElementById('idCreateBayList').style.display="block";
}
//========================================================//
// (2)Delete volume
//========================================================//
function delete_vol() 
{
	//debug('delete_vol');
	var volname, vol_node, level ,disk_nodes;
	var vol_num, temp;
			
	for(var i=0; i<4; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num = i;
			volname = gVolInfo[i].name;
			vol_node = gVolInfo[i].node;
			level = gVolInfo[i].level;
			break;
		}
	}
	
	document.getElementById(gIdOutputDel[0]).innerHTML= 
	"<?php echo lang_get('volume_msg_23')?> : "+gVolInfo[vol_num].name+"("+VolRep(gVolInfo[vol_num].size)+") : <?php echo lang_get('volume_msg_24')?>";
	//debug(volname);debug(vol_node);debug(level);
	
	vol_baycnt = gVolInfo[vol_num].baycnt; 

	temp = new Array(vol_baycnt);
	//debug(vol_baycnt);

	if(vol_baycnt)
		temp[0] = gVolInfo[vol_num].baystat1.split(':')[0];
	if(vol_baycnt >= 2)	
		temp[1] = gVolInfo[vol_num].baystat2.split(':')[0];
	if(vol_baycnt >= 3)	
		temp[2] = gVolInfo[vol_num].baystat3.split(':')[0];
	if(vol_baycnt >= 4)	
		temp[3] = gVolInfo[vol_num].baystat4.split(':')[0];
	
	//debug(temp[0]+"-"+temp[1]+"-"+temp[2]+"-"+temp[3]);
	disk_nodes = "";
	for(var j=0; j<vol_baycnt; j++){
		if(temp[j]){
			for(var i=0; i<gBAYCOUNTMAX; i++){
				if(gBayInfo[i].name == temp[j]){
					disk_nodes += gBayInfo[i].node+" ";
				}
			}
		}
	}
	debug(volname+" "+disk_nodes+" "+vol_node+" "+level+" "+disk_nodes);

	var _txText = '&rdoVolSet='+'delete'
		+"&rdoVolName="+volname
		+"&rdoVolNode="+vol_node
		+"&rdoVolLevel="+level
		+"&rdoVolDiskNode="+disk_nodes;

	sendRequest(onSetVol,_txText,"post","../php/volume_set_info.php",true,true);
	gVolSetDelayTime=0;
	document.getElementById('bt_delete').style.display="none";
	document.getElementById(gIdOutputDel[0]).innerHTML= 
	"<?php echo lang_get('volume_msg_25')?> "+gVolInfo[vol_num].name+"<?php echo lang_get('volume_msg_25_1')?>";
	setTimeout("set_delete_closing()",10000);
	return true;
}
function set_delete_closing(){
	close_popup(gIdpop[1]);
	document.getElementById('bt_delete').style.display="block";
}
//========================================================//
// (3)Edit volume
//========================================================//

function raid_add_vol()
{
	debug('raid_add_vol');
	var volname, vol_node, level,disk_nodes;
	
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num = i;
			volname = gVolInfo[i].name;
			vol_node = gVolInfo[i].node;
			level = gVolInfo[i].level;
			break;
		}
	}
	
	//disk node
	disk_nodes = "";
	if(gBayReverse){
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputEditBay[i]).checked){
				disk_nodes = gBayInfo[gBAYCOUNTMAX-1-i].node;
				break;
			}
		}
	}else{
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputEditBay[i]).checked){
				disk_nodes = gBayInfo[i].node;
				break;
			}
		}
	}

	debug("volname:"+volname+"vol_node:"+vol_node+"level:"+level+"disk_nodes:"+disk_nodes);
	var _txText = '&rdoVolSet='+'add'
		+"&rdoVolName="+volname
		+"&rdoVolNode="+vol_node
		+"&rdoVolLevel="+level
		+"&rdoVolDiskNode="+disk_nodes;

	sendRequest(onSetVol,_txText,"post","../php/volume_set_info.php",true,true);
	gVolSetDelayTime=0;
	i = i+1;
	document.getElementById('idEditWaitAddTxt').innerHTML="<?php echo lang_get('volume_msg_26')?>"+i+" <?php echo lang_get('volume_msg_26_1')?>"; 
	document.getElementById('idEditBayList').style.display="none";
	document.getElementById('idEditWaitAdd').style.display="block";
	setTimeout("set_edit_closing()",50000);
	return true;
}
function raid_remove_vol()
{
	debug('raid_remove_vol');
	var volname, vol_node, level,disk_nodes;
	
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num = i;
			volname = gVolInfo[i].name;
			vol_node = gVolInfo[i].node;
			level = gVolInfo[i].level;
			break;
		}
	}

	//disk node
	disk_nodes = "";
	if(gBayReverse){
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputEditBay[i]).checked){
				disk_nodes = gBayInfo[gBAYCOUNTMAX-1-i].node;
				break;
			}
		}
	}else{
		for(var i=0; i<gBAYCOUNTMAX; i++){
			if(document.getElementById(gIdInputEditBay[i]).checked){
				disk_nodes = gBayInfo[i].node;
				break;
			}
		}
	}

	debug("volname:"+volname+"vol_node:"+vol_node+"level:"+level+"disk_nodes:"+disk_nodes);
	var _txText = '&rdoVolSet='+'remove'
		+"&rdoVolName="+volname
		+"&rdoVolNode="+vol_node
		+"&rdoVolLevel="+level
		+"&rdoVolDiskNode="+disk_nodes;

	sendRequest(onSetVol,_txText,"post","../php/volume_set_info.php",true,true);
	gVolSetDelayTime=0;
	i = i+1;
	document.getElementById('idEditWaitRemoveTxt').innerHTML="<?php echo lang_get('volume_msg_27')?>"+i+" <?php echo lang_get('volume_msg_27_1')?>"; 
	document.getElementById('idEditBayList').style.display="none";
	document.getElementById('idEditWaitRemove').style.display="block";
	setTimeout("set_edit_closing()",10000);
	return true;
}
function set_edit_closing(){
	close_popup(gIdpop[2]);
	document.getElementById('idEditBayList').style.display="block";
	document.getElementById('idEditWaitRemove').style.display="none";
	document.getElementById('idEditWaitAdd').style.display="none";
	document.getElementById('idEditWaitRemoveTxt').innerHTML=""
	document.getElementById('idEditWaitAddTxt').innerHTML=""
	document.getElementById('idVolEditBtnRemove').style.display="none";
	document.getElementById('idVolEditBtnAdd').style.display="none";
}
//========================================================//
// (4)Expand volume
//========================================================//
function expand_vol()
{
	debug('expand_vol');
	var volname, vol_node, level,disk_nodes;
	
	for(var i=0; i<gVOLCOUNTMAX; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num = i;
			volname = gVolInfo[i].name;
			vol_node = gVolInfo[i].node;
			level = gVolInfo[i].level;
			break;
		}
	}
	
	var _txText = '&rdoVolSet='+'expand'
		+"&rdoVolName="+volname
		+"&rdoVolNode="+vol_node
		+"&rdoVolLevel="+level
		+"&rdoVolDiskNode="+disk_nodes;

	sendRequest(onSetVol,_txText,"post","../php/volume_set_info.php",true,true);
	gVolSetDelayTime=gVOLSETDT;
	close_popup(gIdpop[3]);
	return true;
}
//========================================================//
// (5)Migrate volume
//========================================================//
function get_progress()
{
	var cmd = "&md="+_vol_node; 
	document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_msg_28')?>"
	if(fMigProgress>1){
		sendRequest(on_getmig,cmd,"post","../php/volume_migrate_get_progress.php",true,true);
	} else{
		document.getElementById("idProgressPer").innerHTML = "<?php echo lang_get('common_wait')?>";			
	}
	fMigProgress++;
}
function on_getmig(oj)
{
	var res = decodeURIComponent(oj.responseText);
	var tmpVarPrc=res.split(' / ');
	var cmd = "&md="+_vol_node; 
		
	var cnt_time = 0;
	var w = 0;
	var prog_max = 488; 
	var prog_per = 0;

	if( _vol_level == 'raid1' ) {   
		tmpVarPrc[0] = tmpVarPrc[0].replace(/^\s+|\s+$/g,"");
		tmpVarPrc[1] = tmpVarPrc[1].replace(/^\s+|\s+$/g,"");
		tmpVarPrc[2] = tmpVarPrc[2].replace(/^\s+|\s+$/g,"");
		var tmpRatio=tmpVarPrc[0]/tmpVarPrc[1]*100;
		var tmp = parseFloat(tmpRatio);
		tmpRatio = tmp.toFixed(1);
		document.getElementById("idProgressPer").innerHTML = tmpRatio+"%";
		if(fMigProgress>2&&res.substr(0,1)=="0") {
					tmpRatio=100;
			document.getElementById("idProgressPer").innerHTML = "100%";
			document.getElementById('idMigProg').width = prog_max;	
			document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_format_2')?>"		
			clearInterval(intervalId);
			fMigProgress=0;
			setTimeout("set_closing()",5000);
			return true;
		}
	} else if( _vol_level == 'raid5' ) {   
		tmpVarPrc[0] = tmpVarPrc[0].replace(/^\s+|\s+$/g,"");
		tmpVarPrc[1] = tmpVarPrc[1].replace(/^\s+|\s+$/g,"");
		tmpVarPrc[2] = tmpVarPrc[2].replace(/^\s+|\s+$/g,"");
		var tmpRatio=tmpVarPrc[0]/tmpVarPrc[1]*100; 
		if ( _vol_elenum == 3 ){
			var tmpRatio=tmpVarPrc[0]/tmpVarPrc[1]*100;
			var tmp = parseFloat(tmpRatio);
			tmpRatio = tmp.toFixed(1);
			document.getElementById("idProgressPer").innerHTML = tmpRatio+"%";
			if(fMigProgress>2&&res.substr(0,1)=="0") {
					tmpRatio=100;
				document.getElementById("idProgressPer").innerHTML = "100%";
				document.getElementById('idMigProg').width = prog_max;	
				document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_format_2')?>"		
				clearInterval(intervalId);
				fMigProgress=0;
				setTimeout("set_closing()",5000);
				return true;
			}
		}
		else {		
			if( tmpVarPrc[2] == 'recover' ) { 
				tmpRatio=tmpRatio*0.3;
				var tmp = parseFloat(tmpRatio);
				tmpRatio = tmp.toFixed(1);
				document.getElementById("idProgressPer").innerHTML = tmpRatio+"%";
			} else if (tmpVarPrc[2] == 'reshape' ) { 
				_vol_level_flag=1;
				tmpRatio=tmpRatio*0.7 +30;
				var tmp = parseFloat(tmpRatio);
				tmpRatio = tmp.toFixed(1);
				document.getElementById("idProgressPer").innerHTML = tmpRatio+"%";
			} else{ 
				if(fMigProgress>1 && tmpVarPrc[2] == 'idle' &&_vol_level_flag=="1") {
					tmpRatio=100;
					document.getElementById("idProgressPer").innerHTML = "100%";
					document.getElementById('idMigProg').width = prog_max;	
					document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_format_2')?>"		
					clearInterval(intervalId);
					fMigProgress=0;
					setTimeout("set_closing()",5000);
					return true;
				}
			}
		} 
	} else if( _vol_level == 'linear' ) {
		if(fMigProgress>2 ) {
			document.getElementById("idProgressPer").innerHTML = "100%";
			document.getElementById('idMigProg').width = prog_max;	
			document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_migrate_3')?>"
			clearInterval(intervalId);
			document.getElementById("idMigConfirm").style.display = "block";
			fMigProgress=0; 
			return true;
		}
	}
	w = prog_max * tmpRatio /100;
	if(w>=prog_max)
	{
		w = prog_max;
	}
	if(w>0) {
		document.getElementById('idMigProg').width = w;
		document.getElementById('idMigProg_bar').style.display = "block";
	}
	fMigProgress++;
}

function set_closing(){
	document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_migrate_3')?>"
	document.getElementById("idMigConfirm").style.display = "block";
}

function migration_confirm()
{ 
	close_popup(gIdpop[4]);
	document.getElementById("idMigConfirm").style.display = "none";
	document.getElementById("idProgressPer").innerHTML = "";
	document.getElementById("idTxtMigProgress").innerHTML = "<?php echo lang_get('volume_msg_28')?>"
	document.getElementById('idMigProg_bar').style.display = "none";
	document.getElementById('idMigProg').width = 0;
}

function migrate_vol() 
{
	var str_mod = "migrate";
	var str_vol, str_vol_dev, str_vol_lvl_crt, str_vol_lvl_tgt, cnt_vol_ele_act, cnt_vol_ele_new, arr_vol_ele_act, arr_vol_ele_new;
	var vol_num=-1;
	for(var i=0; i<4; i++){
		if(document.getElementById(gIdInputVolCheck[i]).checked){
			vol_num=i;
			break;
		}
	}  
	str_vol = "/mnt/fs/"+gVolInfo[vol_num].name; 

	arr_vol_ele_new = "";
	cnt_vol_ele_new = 0; 
	for(var i=0; i<4; i++){ 
		if(document.getElementById(gIdInputMigrateBay[i]).checked)	{	
			if(gBayReverse)
				arr_vol_ele_new += "/dev/"+gBayInfo[gBAYCOUNTMAX-1-i].node+"3 ";
			else
				arr_vol_ele_new += "/dev/"+gBayInfo[i].node+"3 ";			
			cnt_vol_ele_new +=1;
		}
	}  
	if(cnt_vol_ele_new==0){ 
		alert("<?php echo lang_get('volume_msg_20')?>")
		close_popup(gIdpop[4]);	  
		return false	
	} 
		
	str_vol_lvl_tgt="";
	for(var i=0; i<3; i++){ 
		if(document.getElementById(gIdInputMigrateRaid[i]).checked){
			str_vol_lvl_tgt = document.getElementById(gIdInputMigrateRaid[i]).value; 
			break;
		}
	} 
	_vol_level=str_vol_lvl_tgt;
	if(!str_vol_lvl_tgt){
		alert("<?php echo lang_get('volume_msg_21')?>");
		return false;
	}  
	str_vol_lvl_crt = gVolInfo[vol_num].level; 
	
	var dwCmpSize=0;
	if( (str_vol_lvl_crt == 'raid1') || (str_vol_lvl_crt == 'none') ){
		dwCmpSize = parseFloat(gVolInfo[vol_num].size) + 2250000000;
	}else if(str_vol_lvl_crt == 'raid5'){
		dwCmpSize = parseFloat(gVolInfo[vol_num].size)/(gVolInfo[vol_num].baycnt - 1) + 2250000000;
	}
	for(var i=0; i<4; i++){ 
		if(document.getElementById(gIdInputMigrateBay[i]).checked)	{	
			if(gBayReverse){
				if( gBayInfo[gBAYCOUNTMAX-1-i].size <dwCmpSize ){
					var j=gBAYCOUNTMAX-i;
					var _txtWarn="<?php echo lang_get('volume_msg_22')?>"+j+"("+gBayInfo[gBAYCOUNTMAX-1-i].size+"<"+dwCmpSize+") <?php echo lang_get('volume_msg_22_1')?>";
					alert(_txtWarn);
					return false;
				}
			}
			else{
				if( gBayInfo[i].size <dwCmpSize ){
					var j=i+1;
					var _txtWarn="<?php echo lang_get('volume_msg_22')?>"+j+"("+gBayInfo[i].size+"<"+dwCmpSize+") <?php echo lang_get('volume_msg_22_1')?>";
					alert(_txtWarn);
					return false;
				}
			} 
		}
	}  
	
	str_vol_dev = ""; 
	vol_node = "";
	if(gVolInfo[vol_num].baycnt == 1){
		for(var i=0; i<4; i++){
			if(gVolInfo[i].node == 'md2' ){
				_vol_node='md3'
				str_vol_dev = '/dev/md3';
				break;
			}
		}
		if(!str_vol_dev){
			_vol_node='md2'
			str_vol_dev = '/dev/md2';
		} 
	} else{
		str_vol_dev='/dev/'+gVolInfo[vol_num].node;
		_vol_node=gVolInfo[vol_num].node;
	}
	 
	cnt_vol_ele_act = gVolInfo[vol_num].baycnt; 
	arr_vol_ele_act = "";
	var k=0, m=0; 
	if(gBayReverse){
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat4)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat4.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){	
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		}
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat3)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat3.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		}
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat2)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat2.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		}
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat1)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat1.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		} 
	} else {
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat1)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat1.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){	
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		}
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat2)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat2.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		}
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat3)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat3.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		}
		if ((k<cnt_vol_ele_act)&&(gVolInfo[vol_num].baystat4)){
			var vol_ele_tmp = gVolInfo[vol_num].baystat4.split(':');
			for(m=0;m<4;m++){
				if(vol_ele_tmp[0] == gBayInfo[m].name){
					arr_vol_ele_act+= "/dev/"+gBayInfo[m].node+"3 "; 
					break;
				}	
			} 
			k+=1;
		} 
	}
	var _txText ="&rdoVolSet="+'migrate'
		+"&rdoVolName="+str_vol
		+"&rdoVolNode="+str_vol_dev
		+"&rdoVolLevel="+str_vol_lvl_tgt
		+"&rdoVolDiskNode="+arr_vol_ele_new
		+"&rdoVolLevelCrt="+str_vol_lvl_crt
		+"&rdoCntDiskNode="+cnt_vol_ele_new
		+"&rdoCntDiskNodeAct="+cnt_vol_ele_act
		+"&rdoVolDiskNodeAct="+arr_vol_ele_act;
	_vol_elenum=cnt_vol_ele_act; 
	sendRequest(onSetVol,_txText,"post","../php/volume_set_info.php",true,true);
	document.getElementById("idMigrateTable").style.display = "none"; 
	document.getElementById("idMigProgress").style.display = "block";  
	intervalId = setInterval("get_progress()",5000);
	return true;
}
function onSetVol(oj)
{
	var res = decodeURIComponent(oj.responseText);
}
//========================================================//
// show_help
//========================================================//
var help = 1;
var help_value = new Array('1','2','3');
function show_help()
{
	debug(help);
	switch(help)
	{
		case 1:
		var _win = window.open('../help/system/help_volume.html','Help_volume','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;
		break;
		
		case 2:
		var _win = window.open('../help/system/help_volume.html','Help_volume','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		case 3:
		
		var _win = window.open('../help/system/help_volume.html','Help_volume','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
		_win.focus();
		hPopWin = _win;
		break;
		
		default:
		break;
	}

}