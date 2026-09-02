<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	lang_set_active_language($_GET['lang']);
?>

//=======================================================//
// Information / Status
//=======================================================//

//=======================================================//
// Page Initiate
//=======================================================//
var page = {
	name : "status",
	init : function(){
		open_tab_network();
	}
}



var gBayReverse=0;


//=======================================================//
// HTTPRequest object for abort
//=======================================================//
var tmp_oj = '';


//=======================================================//
// ID list
//=======================================================//
var gIdTable = new Array("idTableNetwork","idTableVolume",
"idTableHard","idTableBluray","idTableUsb","idTableEsata",
"idTableUser");
var gIdNetwork = new Array("idNetworkMac","idNetworkIp",
"idNetworkSub","idNetworkGate","idNetworkDns1",
"idNetworkDns2","idNetworkFrmS","idNetworkLink",
"idNetworkPackRx","idNetworkPackRxErr","idNetworkPackTx",
"idNetworkPackTxErr");
var gIdTab = new Array("idTabNetwork","idTabVolume",
"idTabHard","idTabBluray","idTabUsb","idTabEsata","idTabUser");
var gIdBox = new Array("idTableVolumeBox","idTableHardBox",
"idTableBlurayBox","idTableUsbBox","idTableEsataBox",
"idTableUserBox");
//=======================================================//
// PHP file list
//=======================================================//
var gPhp = new Array("../php/status_get_info.php",
"../php/usb_get_dev_list.php");
//=======================================================//
// Page status
//=======================================================//
var gStat = new Array("network","volume","hard","bluray",
"usb","esata","user");
var fStat = gStat[0];
//=======================================================//
// Get status information
//=======================================================//

function showLoadingImage(){
	document.getElementById('img_page_loading').src = "../images/Burn/file_box_loading.gif";
}
function get_status_info(mode)
{
	//debug("get status info : "+mode);
	var cmd = "&mode="+mode;
	var php = gPhp[0];
	//debug(cmd);
	if(mode=="usb")
	{
		php=gPhp[1];
		cmd="&dev_type=usb";
	}
	if(tmp_oj){
		tmp_oj.abort();
	}
	tmp_oj = sendRequest(on_1,cmd,"post",php,true,true);

	document.getElementById('page_loading').style.display = 'block';
	setTimeout(showLoadingImage,100);
}
function on_1(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);

	
	if(res.match(/usb/)|| res.match(/memcard/))
	{			
		//debug("usb received");
		var arr = to_array_usb(res);
		//debug(arr);
		show_usb_list(arr);
		return "usb";
	}
	if(res=="No USB device")
	{	
			var tmp_txt_total ="<table width='670' border='0' cellspacing='0' cellpadding='0'><tr>";

			tmp_txt_total += "<td width='670' class='firstCol'>" + "<?php echo lang_get('usb_sync_13')?>" + "</td>";

			tmp_txt_total += "</tr></table>";
			
		document.getElementById('idTableUsbBox').innerHTML=tmp_txt_total;
		document.getElementById('page_loading').style.display = 'none';
		return false;
	}
	
	var tmp = get_array(res);
	//debug(tmp);
	var tmp_mode = tmp.slice(0,1);
	tmp = tmp.slice(1);
	
	if(tmp_mode=="network")
	{
		//debug("if network");
		show_network_info(tmp);
	}else if(tmp_mode=="volume")
	{
		show_volume_info2(tmp);
	}else if(tmp_mode=="hard_disk")
	{
		show_hard_info(tmp);
	}else if(tmp_mode=="blu_ray")
	{
		show_bluray_info(tmp);
	}else if(tmp_mode=="e_sata")
	{
		show_esata_info(tmp);
	}
	
}
function to_array_usb(str)
{
	var cnt=0;
	var b=new Array();
	var a=str.split("\n");
	for(var i=0;a[i];i++)
	{
		b[i]=a[i].split("|");
		for(var j=0;b[i][j];j++)
		{
			var c=b[i][j].split(";");
			if(c[0]=="INCLUDE"||c[0]=="EXCLUDE")
			{
				c[1]=c[1].replace(/\//g,";");
			}
			b[i][c[0]]=c[1];
		}
	}
	return b;
}
function get_array(str)
{
	var tmp = str.split(";");
	return tmp;
}
function show_network_info(arr)
{
	var cnt = arr.length;
	for(var i=0;i<cnt;i++)
	{
		//debug(i);
		if(arr[i]=="") arr[i] = "&nbsp;";
		document.getElementById(gIdNetwork[i]).innerHTML = arr[i];
	}
	document.getElementById('page_loading').style.display = 'none';
}
function VolRep(tmp)
{
	for(cnt=0;tmp>1024;cnt++){
		tmp=tmp/1024;
	}
	if (cnt==0){
		return(parseInt(tmp+0.5)+" B");
	} else if (cnt==1){
		return(parseInt(tmp+0.5)+" kB");
	} else if (cnt==2){
		return(parseInt(tmp+0.5)+" MB");
	} else if (cnt==3){
		if(tmp<10)
			tmp=parseInt(tmp*100+0.5)/100;
		else
			tmp=parseInt(tmp+0.5);
		return(tmp+" GB");
	} else if (cnt==4){
		if(tmp<10)
			tmp=parseInt(tmp*100+0.5)/100;
		else
			tmp=parseInt(tmp+0.5);
		return(tmp+" TB"); 
	}
}

// Integraion Init Function 2008/12/09
function show_info_init(id)
{
	var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'><tr>";
	tmp_txt_total += "<td class='firstCol' width>" + "<?php echo lang_get('common_loading')?>" + "</td>"

	tmp_txt_total += "</tr></table>";
	document.getElementById(id).innerHTML = tmp_txt_total;
	
}

function show_volume_info2(arr)
{
	//debug(arr);
	//alert(arr);
	
	var _tmp = arr[0].split("\n");
	var vol =  _tmp[0].split(" ");
	var vol_cnt = vol.length;
	var vol_item = new Array();

	var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'>";

	if ( (vol[0] == "") || (vol_cnt == 0) || ( vol_cnt > 2 ) ) {
		tmp_txt_total += "</table>";
		document.getElementById(gIdBox[0]).innerHTML = tmp_txt_total;		
		document.getElementById('page_loading').style.display = 'none';		
		return;		
	}
			
	for(var i=0;i<vol_cnt;i++) {
	
		vol_item[i] = vol[i].split(":");
		
		var tmp_v_position = 300 + 30*i;
		var tmp_capa = parseInt(vol_item[i][5]);
		var tmp_used = parseInt(vol_item[i][4]);
		//var tmp_percent = parseInt((tmp_used / tmp_capa)*100);
		var tmp_percent = parseInt(vol_item[i][6]);
		
		var tmp = "<tr>";
		tmp += "<td width='60px' class='firstCol'>";
		tmp += vol_item[i][0];
		tmp += "</td>";
		tmp += "<td width='210px' class='otherCol'>";
		tmp += vol_item[i][1];
		tmp += "</td>";
		tmp += "<td width='60px' class='thirdCol'>";
		tmp += vol_item[i][2];
		tmp += "</td>";
		tmp += "<td width='100px' class='otherCol'>";
		tmp += vol_item[i][3];
		tmp += "</td>";
		tmp += "<td width='220px' class='thirdCol'>";
	
		tmp += "<table width='218' border='0' cellspacing='0' cellpadding='0'><tr><td width='110'>";
			tmp += "<table width='100' border='0' cellspacing='0' cellpadding='0'><tr><td width='100' background='../images/Burn/img_burn_bg_middle.gif'>";
			tmp += "<img src='../images/Burn/img_burn_bar_middle.gif' width='"
			var tmpWidth=parseInt(tmp_percent);
			if (tmpWidth<1) {
				tmp += 1;
			}
			else if ( tmpWidth>100) {
				tmp += 100;
			}
			else {
				tmp += tmpWidth;
			}
			tmp += "' height='17'/></td>";
		tmp += "<td align='center' width='100' height='17' style='position:absolute;top:";
		tmp += tmp_v_position;
		
		tmp +=";left:745;padding:0pt 0pt 0pt 0pt;'><strong>";
			tmp += tmp_percent+"%";
			tmp += "</strong></td></tr></table>";
			tmp += "</td><td align='center'>";
			tmp += vol_item[i][4]+"/"+vol_item[i][5];
			//tmp += VolRep(vol_item[i][4])+"/"+VolRep(vol_item[i][5]);

		tmp += "</td></tr></table>";
		tmp += "</td>";
		tmp += "</tr>";
		tmp_txt_total += tmp;
	}

	tmp_txt_total += "</table>";
	document.getElementById(gIdBox[0]).innerHTML = tmp_txt_total;
	document.getElementById('page_loading').style.display = 'none';	

	return;	
	

}

function show_volume_info(arr)
{
	//debug(arr);
	var bay = arr[0].split("\n");
	//debug(bay);
	
var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'>";
	
	var cnt = bay.length;
	for(var i=0;bay[i];i++)
	{
		bay[i] = bay[i].split(" ");
		//debug(bay[i]);
		if ((bay[i][2] != "formatting")&&(bay[i][2].split('_')[0] != "migrating") )
			var tmp_cap = parseInt(bay[i][4]*10000/bay[i][3]+0.5)/100;
		else
			var tmp_cap = 0;
		var tmp_cnt = parseInt(bay[i][7]);
		var tmp_disk = "";
		for(var j=(8+tmp_cnt-1);j>7;j--)
		{
			var tmp = bay[i][j].split(":");
			if(gBayReverse){
				if ( tmp[0] == 'Bay1' )
					tmp_disk += "B4";
				else if ( tmp[0] == 'Bay2' )
					tmp_disk += "B3";
				else if ( tmp[0] == 'Bay3' )
					tmp_disk += "B2";
				else if ( tmp[0] == 'Bay4' )
					tmp_disk += "B1";
				else if ( tmp[0] == 'removed' )
					tmp_disk += tmp[0];
			}
			else
				tmp_disk += tmp[0];
			tmp_disk += " ";
		}
		var tmp_v_position = 300 + 30*i;
		var tmp = "<tr>";
		tmp += "<td width='60px' class='firstCol'>";
		tmp += bay[i][0];
		tmp += "</td>";
		tmp += "<td width='210px' class='otherCol'>";
		tmp += tmp_disk;
		tmp += "</td>";
		tmp += "<td width='60px' class='thirdCol'>";
		if( bay[i][5] == 'linear' )
			tmp += "jbod";
		else
			tmp += bay[i][5];
		tmp += "</td>";
		tmp += "<td width='100px' class='otherCol'>";
		if( bay[i][2].split('_')[0] =='synching')
			tmp += "syncing("+bay[i][2].split('_')[1]+"%)";
		else if( bay[i][2].split('_')[0] =='migrating')
			tmp += "migrating("+bay[i][2].split('_')[1]+"%)";
		else
			tmp += bay[i][2];
		tmp += "</td>";
		tmp += "<td width='220px' class='thirdCol'>";
		tmp += "<table width='218' border='0' cellspacing='0' cellpadding='0'><tr><td width='110'>";
		if( (bay[i][2] != "formatting") && (bay[i][2].split('_')[0] != "migrating") ) {
			tmp += "<table width='100' border='0' cellspacing='0' cellpadding='0'><tr><td width='100' background='../images/Burn/img_burn_bg_middle.gif'>";
			tmp += "<img src='../images/Burn/img_burn_bar_middle.gif' width='"
			var tmpWidth=parseInt(tmp_cap);
			if (tmpWidth<1)
				tmp += 1;
			else
				tmp += tmpWidth;
			tmp += "' height='17'/></td>";
		}
		else {
			tmp += "<table width='100' border='0' cellspacing='0' cellpadding='0'><tr><td width='100' >";
//			tmp += "<img src='../images/Burn/img_burn_bar_middle.gif' width='"
//			var tmpWidth=0;
//			tmp += tmpWidth;
//			tmp += "' height='17'/>";
			tmp += "</td>";
		}
		tmp += "<td align='center' width='100' height='17' style='position:absolute;top:"
		tmp += tmp_v_position;
		tmp +=";left:745;padding:0pt 0pt 0pt 0pt;'><strong>";
		if( bay[i][2] == "formatting" )
			tmp += "Formatting</strong></td></tr></table></td><td align='center'>---/---"; 
		else if( bay[i][2].split('_')[0] =='migrating')
			tmp += "Migrating</strong></td></tr></table></td><td align='center'>---/---";
		else {
			tmp += tmp_cap+"%";
			tmp += "</strong></td></tr></table>";
			tmp += "</td><td align='center'>"
			tmp += VolRep(bay[i][4])+"/"+VolRep(bay[i][3]);
		}
		tmp += "</td></tr></table>";
		tmp += "</td>";
		tmp += "</tr>";
		
		tmp_txt_total += tmp;
	}
	tmp_txt_total += "</table>";
	document.getElementById(gIdBox[0]).innerHTML = tmp_txt_total;
	document.getElementById('page_loading').style.display = 'none';
}

function show_hard_info(arr)
{
	//debug(arr);
	var bay = arr[0].split("\n");
	//debug(bay);
	var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'>"
	if(gBayReverse){
		for(var i=3;bay[i];i--)
		{
			bay[i] = bay[i].split(" ");
			//debug(bay[i]);
			if(bay[i][2]=="none") continue;	
			if(bay[i][2]=="formatting") {	
				var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'><tr>";
				tmp_txt_total += "<td width='670' class='firstCol'>" + "<?php echo lang_get('status_msg_1')?>" + "</td>";
				tmp_txt_total += "</tr></table>";
				document.getElementById(gIdBox[1]).innerHTML = tmp_txt_total;
				return true;
			}
			var tmp = "<tr>";
			tmp += "<td width='80' class='firstCol'>";
			if ( bay[i][0] == 'Bay1' )
				tmp += "B1";
			else if ( bay[i][0] == 'Bay2' )
				tmp += "B2";
			else continue;		
			tmp += "</td>";
			
			tmp += "<td width='120' class='otherCol'>";
			tmp += bay[i][4];
			tmp += "</td>";
			
			tmp += "<td width='230' class='thirdCol'>";
			tmp += bay[i][5];
			tmp += "</td>";

			tmp += "<td width='100' class='otherCol'>";
			tmp += bay[i][2];
			tmp += "</td>";
			
			tmp += "<td width='140' class='thirdCol'>";
			tmp += VolRep(bay[i][3]);
			tmp += "</td>";
			tmp += "</tr>";
		
			tmp_txt_total += tmp;
		}
	}
	else {
		for(var i=0;bay[i];i++)
		{
			bay[i] = bay[i].split(" ");
			//debug(bay[i]);
			if(bay[i][2]=="none") continue;					
			if(bay[i][2]=="formatting") {	
				var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'><tr>";
				tmp_txt_total += "<td width='670' class='firstCol'>" + "<?php echo lang_get('status_msg_1')?>" + "</td>";
				tmp_txt_total += "</tr></table>";
				
				document.getElementById(gIdBox[1]).innerHTML = tmp_txt_total;
				return true;
			}		
			var tmp = "<tr>";
			tmp += "<td width='80' class='firstCol'>";
			if ( bay[i][0] == 'Bay1' )
				tmp += "B1";
			else if ( bay[i][0] == 'Bay2' )
				tmp += "B2";
			tmp += "</td>";
			tmp += "<td width='120' class='firstCol'>";
			tmp += bay[i][4];
			tmp += "</td>";
			tmp += "<td width='230' class='firstCol'>";
			tmp += bay[i][5];
			tmp += "</td>";
			tmp += "<td width='100' class='firstCol'>";
			tmp += bay[i][2];
			tmp += "</td>";
			tmp += "<td width='140' class='firstCol'>";
			tmp += VolRep(bay[i][3]);
			tmp += "</td>";
			tmp += "</tr>";
			
			tmp_txt_total += tmp;
		}
	}
	tmp_txt_total += "</table>";
	document.getElementById(gIdBox[1]).innerHTML = tmp_txt_total;
	document.getElementById('page_loading').style.display = 'none';
}
function show_bluray_info(arr)
{
	//debug(arr);
	var bay = arr[0].split(" ");
	//debug(bay);

	var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'>";
	
	var tmp = "<tr>";
	tmp += "<td width='250' class='firstCol'>" + bay[3] + "</td>";
	tmp += "<td width='420' class='otherCol'>" + bay[5]+" "+ bay[6]+" "+bay[7] + "</td></tr>";

	tmp_txt_total += tmp;
	tmp_txt_total += "</table>";

	document.getElementById(gIdBox[2]).innerHTML = tmp_txt_total;
	document.getElementById('page_loading').style.display = 'none';
}
function show_usb_list(arr)
{
	//debug("@show_usb_list, "+arr);
	var info=arr;
	var _table=new Array();
	var tmp_txt_total ="<table width='670' border='0' cellspacing='0' cellpadding='0'>";

	for(var i=0;info[i];i++)
	{
		var tmp = info[i][0].split(" ");
		var name = tmp[0];

	//juny : 090318 => changed :info[i][2]/info[i][1] -> info[i][1]/info[i][2]  
	tmp_txt_total += "<tr><td width='170' class='firstCol'>" + name + "</td>"
		+"<td width='250' class='otherCol'>" + info[i][1] + "</td>"
		+"<td width='250' class='thirdCol'>" + info[i][2]+ "</td>"
		+"</tr>";
	
	}
	tmp_txt_total += "</table>";
	document.getElementById(gIdBox[3]).innerHTML=tmp_txt_total;
	document.getElementById('page_loading').style.display = 'none';
}
function show_esata_info(arr)
{
	//debug(arr);
	var bay = arr;
	//debug(arr[0]);
	//debug(bay);
	var tmp_txt_total = "<table width='670' height='25' border='0' cellspacing='0' cellpadding='0'><tr>";
  var tmp;
  
	if( bay[0] == "" ) {
		bay[0] = "<?php echo lang_get('esata_msg_5')?>";
		tmp =  "<td width='670' class='firstCol'>" + bay[0] + "</td>";
  }
	else{
		tmp =  "<td width='220' class='firstCol'>" + bay[0] + "</td>";
		tmp += "<td width='220' class='otherCol'>" + bay[1] + "</td>";
		tmp += "<td width='220' class='thirdCol'>" + bytesHumanReadable(parseFloat(bay[2],10)) + "</td>";
  }
	tmp_txt_total += tmp;
	tmp_txt_total += "</tr></table>";
	document.getElementById(gIdBox[4]).innerHTML = tmp_txt_total;
	document.getElementById('page_loading').style.display = 'none';
}
//=======================================================//
// Table control
//=======================================================//
function open_tab_network()
{
	get_status_info("network");
	fStat = gStat[0];
	set_tab_button(gStat[0]);
	close_tab_all();
	var _tmp = new Array(12);
	for(var i=0;i<12;i++){
		_tmp[i]="<?php echo lang_get('common_loading')?>";
	}
	show_network_info(_tmp);
	document.getElementById(gIdTable[0]).style.display = "block";
	document.getElementById('page_loading').style.display = 'block';
}
function open_tab_volume()
{
	get_status_info("volume");
	fStat = gStat[1];
	set_tab_button(gStat[1]);
	close_tab_all(); 
	show_info_init(gIdBox[0]);
//	document.getElementById(gIdBox[0]).innerHTML = "Loading...";
	document.getElementById(gIdTable[1]).style.display = "block";
}
function open_tab_hard()
{
	//debug('a');
	get_status_info("hard_disk");
	fStat = gStat[2];
	set_tab_button(gStat[2]);
	close_tab_all();
	show_info_init(gIdBox[1]);
//	document.getElementById(gIdBox[1]).innerHTML = "Loading...";
	document.getElementById(gIdTable[2]).style.display = "block";
}
function open_tab_bluray()
{
	get_status_info("blu_ray");
	fStat = gStat[3];
	set_tab_button(gStat[3]);
	close_tab_all();
	show_info_init(gIdBox[2]);
//	document.getElementById(gIdBox[1]).innerHTML = "Loading...";
	document.getElementById(gIdTable[3]).style.display = "block";
}
function open_tab_usb()
{
	get_status_info("usb");
	fStat = gStat[4];
	set_tab_button(gStat[4]);
	close_tab_all();
	show_info_init(gIdBox[3])
//	document.getElementById(gIdBox[3]).innerHTML = "Loading...";
	document.getElementById(gIdTable[4]).style.display = "block";
}
function open_tab_esata()
{
	get_status_info("e_sata");
	fStat = gStat[5];
	set_tab_button(gStat[5]);
	close_tab_all();
	show_info_init(gIdBox[4]);
//	document.getElementById(gIdBox[4]).innerHTML = "Loading";
	document.getElementById(gIdTable[5]).style.display = "block";
}
function set_tab_button(stat)
{
	//debug(stat);
	var cnt = gIdTab.length;
	for(var i=0;i<cnt;i++)
	{
		var tmp_on = gIdTab[i]+"On";
		var tmp_off = gIdTab[i]+"Off";
		//debug(i+" "+stat+" : "+gStat[i]);
		if(stat==gStat[i])
		{
			var set1 = "inline";
			var set2 = "none";
			//debug(stat);
		}else
		{
			var set1 = "none";
			var set2 = "inline";
		}
		document.getElementById(tmp_on).style.display = set1;
		document.getElementById(tmp_off).style.display = set2;
	}
}
function close_tab_all()
{
	for(var i=0;gIdTable[i];i++)
	{
		document.getElementById(gIdTable[i]).style.display = "none";
	}
}


//========================================================//
// show_help
//========================================================//
function show_help()
{
		var _win = window.open('../help/information/help_status.html','Help_status','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;
}


//=======================================================//
// Show User Access Information
//=======================================================//
var user_access = {
	mode : "user",
	php_file : "../php/status_get_info.php",
	box_id : "idTableUserBox",
	info : [],
	get : function(){
		if(tmp_oj){
			tmp_oj.abort();
		}
		tmp_oj = sendRequest(on_user_info,"&mode="+this.mode,"post",this.php_file,true,true);
		
		document.getElementById('page_loading').style.display = 'block';
		setTimeout(showLoadingImage,100);
		
		function on_user_info(oj){
			var res=decodeURIComponent(oj.responseText);
			debug(res);
			//user_access.show(arr);
			var _tmp = res.split(";");
			eval("user_access.info="+_tmp[1]);
			//debug(user_access.info[0].id);
			user_access.show_info(user_access.info);
		}
	},
	tab_open : function(){
		this.get();
		fStat = gStat[6];
		set_tab_button(gStat[6]);
		close_tab_all();
		this.show_loading();
		document.getElementById(gIdTable[6]).style.display = "block";
	},
	show_loading : function(){
		var tmp_txt_total ="<table width='670' border='0' cellspacing='0' cellpadding='0'><tr>";
		tmp_txt_total += "<td width='670px' class='firstCol'>" + "<?php echo lang_get('common_loading')?>" + "</td>";
		tmp_txt_total += "</tr></table>";
		document.getElementById(this.box_id).innerHTML=tmp_txt_total;
	},
	show_info : function(arr){
		
		var tmp_txt_total;
		
		if(!arr[0]){
			debug("no info");

				tmp_txt_total ="<table width='670' border='0' cellspacing='0' cellpadding='0'><tr>";
				tmp_txt_total += "<td width='670px' class='firstCol'>" + "<?php echo lang_get('status_msg_2')?>" + "</td>";
				tmp_txt_total += "</tr></table>";
			
				
		}else{
        
        var info = arr;
				//var _table=new Array();
				tmp_txt_total ="<table width='670' border='0' cellspacing='0' cellpadding='0'>";
				for(var i=0;info[i];i++){
					
					
					tmp_txt_total += "<tr><td width='1' height='25' bgcolor='#e3e3e3'></td>"
						+"<td width='170' class='firstCol'>" + info[i].service + "</td>"
						+"<td width='250' class='otherCol'>" + info[i].id + "</td>"
						+"<td width='250' class='thirdCol'>" + info[i].ip + "</td></tr>"
						
				}
				tmp_txt_total += "</table>";
				
		}
		document.getElementById(this.box_id).innerHTML=tmp_txt_total;
		document.getElementById('page_loading').style.display = 'none';
	}
}


//convert size to byte
function bytesHumanReadable(bytes){
	var ret_val = '';
	var K = 1024;
	var M = 1024 * 1024;
	var G = 1024 * 1024 * 1024;
	var T = 1024 * 1024 * 1024 * 1024;
	bytes = parseInt(bytes);
	if(bytes<K){ 
		ret_val = bytes;
	}else if(bytes<M){ //K
		ret_val = soft_round(bytes/K,2)+'K';
	}else if(bytes<G){ //M
		ret_val = soft_round(bytes/M,2)+'M';
	}else if(bytes<T){ //G
		ret_val = soft_round(bytes/G,2)+'G';
	}else{ //T
		ret_val = soft_round(bytes/T,2)+'T';
	}
	return ret_val;
}
