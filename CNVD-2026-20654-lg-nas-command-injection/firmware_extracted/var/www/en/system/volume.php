<?php include "../inc/top.php";  ?>
  
<?php

//Check : HDD1 or HDD2 FORCE OUT
$stat_file = '/var/run/hdd_event.log';
if(!file_exists($stat_file))
	exec("sudo touch '$stat_file' ; sudo chmod 777 '$stat_file' ; sudo echo no event > '$stat_file'");
else
	$hdd_force_out = trim(exec("sudo cat $stat_file | grep Removed"));

$isPreIndividual = false;
$cmd_volume1  = "sudo df -h /mnt/disk/volume1";
$cmd_volume2  = "sudo df -h /mnt/disk/volume2";

$vol_num = exec("sudo nas-storage get vol_num");
$vol_list = exec("sudo nas-storage get vol_list");

$dev_list = exec("sudo nas-storage get dev_list HDD");
$dev = explode(" ", $dev_list);
$cnt = count($dev);
if($cnt == 2)
{
	$tmp1 = explode("/dev/",$dev[0]);
	$hdd1 = $tmp1[1];
	$tmp2 = explode("/dev/",$dev[1]);
	$hdd2 = $tmp2[1];
	$hdd_index = 'all';	
}
else
{
	$tmp1 = explode("/dev/",$dev[0]);
	$hdd1 = $tmp1[1];
	$hdd2 = '';
	$hdd_index = '';
}

$sys_area_destroy = trim(exec("sudo cat /proc/mdstat | grep -B1 UU | grep md1"));
if($sys_area_destroy == '')
	$sys_md1 = 'destroy';		
else
	$sys_md1 = 'active';	

exec("sudo echo hdd test : $hdd1, $hdd2, $cnt >> /home/tmp.txt");

$str1_array=null;
$str2_array=null;

if ( $vol_num == 0 ) {
	$str1_array[0] = lang_get('volume_17');
	$str1_array[1] = "-";
	$str1_array[2] = "-";
	$str1_array[3] = "-";
	$str1_array[4] = "-";
}
else if ( $vol_num == 1 ) { // linear, raid0, raid1
	if($vol_list == 'volume2') 
	{
		$str1 = trim(exec ($cmd_volume2));
		//$md_index = 'md3';
		$str1_array = preg_split("/[\s,]+/", $str1);	
		$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $2}\''));			
		if($str1_array[0] == '')
			$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));
	}
	else
	{
		$str1 = trim(exec ($cmd_volume1));
		//$md_index = 'md2';
		$str1_array = preg_split("/[\s,]+/", $str1);	
		$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));		
	}

	// To distinguish raid between original linear and linear from individual  
	//$cnt_dev = trim(exec("cat /proc/mdstat | grep $hdd1 | grep $hdd2 | grep -v md1"));
	$cnt_dev = trim(exec("cat /proc/mdstat | grep linear | grep -v -E 'Personalities|md1|md4|md5' |awk '{print $6}'"));
	if($cnt_dev == '')
	{
		$isPreIndividual = true;
		if($str1_array[0]== 'linear')
			$str1_array[0] = 'No RAID';
	}
	else
		$isPreIndividual = false;	
}
else { // individual, linear+raid
	$str1 = trim(exec ($cmd_volume1));
	$str1_array = preg_split("/[\s,]+/", $str1);
	
	$str1_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md3 | cut -d: -f2 | awk \'{print $2}\''));
	
	$str2 = trim(exec ($cmd_volume2));
	$str2_array = preg_split("/[\s,]+/", $str2);
	
	$str2_array[0] = trim(shell_exec('sudo cat /proc/mdstat | grep md2 | cut -d: -f2 | awk \'{print $2}\''));			
	
	if ( $str2_array[0] != "raid1" ) {
		$str1_array[0] = "No RAID";
		$str2_array[0] = "No RAID";
	}
}
/*
$type1 = trim(exec("sudo nas-storage get vol_type $hdd1"));
$type2 = trim(exec("sudo nas-storage get vol_type $hdd2"));

if(!eregi('HDD',$type1)) 	$hdd_index = 'hdd2';	
else if(!eregi('HDD',$type2)) $hdd_index = 'hdd1';		
else						$hdd_index = 'all';		
*/
	
$hdd1_size = trim(exec("sudo cat /sys/block/$hdd1/size")) * 512;
$hdd2_size = trim(exec("sudo cat /sys/block/$hdd2/size")) * 512;

$resync_type = null;
$now_resync = trim(exec("sudo cat /proc/mdstat | grep -y -E 'resync|recovery'"));
if($now_resync == '')
	$now_resync=false;
else
{
	$now_resync=true;
	$system_resync = trim(exec("sudo cat /proc/mdstat | grep -y -E -B2 'resync|recovery'| grep md1"));
	
	if($system_resync == '')
		$resync_type = 'volume';
       else
       {
               $isDelayed = trim(exec("sudo cat /proc/mdstat | grep -y -E -B2 'DELAYED'| grep md1"));
               if($isDelayed == '')
                       $resync_type = 'system';
               else
                       $resync_type = 'volume';
       }
}

?>
<!-- Include Basic JS -->
	<!--<script type="text/javascript" src="../js/jquery.min.js"></script>-->
	<script type="text/javascript" src="../js/jquery-ui-1.7.1.custom.js"></script>
	
	<!-- Include Custom JS -->
	<script type="text/javascript" src="../js/volumeSlider.js"></script>
	
	<!-- Include Basic CSS -->
	<link type="text/css" href="../css/basic/ui.all.css" rel="stylesheet" />
		
	<!-- Custom CSS -->
	<style type="text/css">
			#sliderContainer{
				width:430px;
				margin-left:250px;
				
			}	
			#raid1_linear{
				width: 400px;
				margin: 15px;
				background-image : url('../images/icon/bg_slider_green.jpg');
			}
			
			#raid1_linear .ui-slider-range { background-image : url('../images/icon/bg_slider_blue.jpg'); }
			
			#raid1Slider{
				
				float:left;
			}
			
			#linearSlider{
				
				float:right;
			}
	</style>


<SCRIPT language="javascript1.2" charset="utf-8">


jQuery(document).ready(function(){
	 
	// For AJAX loading
	jQuery("#page_loading").ajaxStart(function(){
  	jQuery(this).show();
  	jQuery("#img_page_loading").attr("src","../images/Burn/file_box_loading.gif");
  }).ajaxStop(function(){
  	jQuery(this).hide();
  });

	// Button  
		jQuery("#same_uuid_apply").click(function(){
			var checkedVal = jQuery(".radio_add_type:checked").val();
			
			if(checkedVal == "hdd_add_remain_data"){
				raid_add_vol("add_no_format");
			}
			else if(checkedVal == "hdd_add_delete_data"){
				raid_add_vol();
			}
		
	});
		jQuery("#same_uuid_cancel").click(function(){
				jQuery("#div_same_uuid").hide();	
				jQuery("#volume_table").show();
	});
  
  
});






//=======================================================//
// Binary Semaphore
//=======================================================//


var gSemaphore_apply=false;


function getPageSizeWithScroll(){ 
                //Fix for IE7 (at then end)
                if( window.innerHeight && window.scrollMaxY ) // Firefox 
                {
                pageWidth = window.innerWidth + window.scrollMaxX;
                pageHeight = window.innerHeight + window.scrollMaxY;
                }
                else if( document.body.scrollHeight > document.body.offsetHeight ) // all but Explorer Mac
                {
                pageWidth = document.body.scrollWidth;
                pageHeight = document.body.scrollHeight;
                }
                else // works in Explorer 6 Strict, Mozilla (not FF) and Safari
                { pageWidth = document.body.offsetWidth + document.body.offsetLeft; 
                        pageHeight = document.body.offsetHeight + document.body.offsetTop; 
                }
                
                // 20090111 Min
                // In this time We only Consider about Page's Height. So Only Return Pages'Height 
                return pageHeight;
}       

function trashcan_vol(volIndex)
{
	var volnum = <?php echo $vol_num ?>;
	if(volnum == 1)
	{
		var volList = '<?php echo $vol_list ?>';
		if(volList == 'volume2')
			volIndex = 2;
	}	
	var VOL_NAME = ''; //(volIndex == 1)? "volume1":"volume2";
	
	if(volIndex == 1)
		VOL_NAME = "volume1";
	else
		VOL_NAME = "volume2";

	
	var _txText =	'&VOL_TASK_CMD='+"VOLNAME"
				+'&VOL_NAME='+VOL_NAME;

	//var result=confirm("    Do you empty [Trash can]?");
	var result = confirm("<?php echo lang_get('empty_trashcan'); ?>");
        if (!result)
	{
		return false;
	}	
	
	sendRequest(onLoadTC,_txText,'post',"../php/volume_task.php",true,true);

	return true;
	
}

function Format_Volume(volIndex)
{
	if(!confirm('Continue?')) return;
	
	var 	varFORMAT_TYPE,varFORMAT_VOL;

	var _select = document.getElementById('idSelect'+volIndex);
	//varFORMAT_VOL = (volIndex == 1)? <?php echo '\''.$str1_array[5].'\''; ?>:<?php echo '\''.$str2_array[5].'\''; ?>;
	
	if(volIndex == 1)
		varFORMAT_VOL="<?php echo $str1_array[5]?>";
	else
		varFORMAT_VOL="<?php echo $str2_array[5]?>";


	varFORMAT_TYPE = _select[_select.selectedIndex].value;

	var _txText =	'&VOL_TASK_CMD='+"FORMAT"
			+'&FORMAT_TYPE='+varFORMAT_TYPE
			+'&FORMAT_VOL='+varFORMAT_VOL;

	alert(_txText);

	sendRequest(onLoadST,_txText,'post',"../php/volume_task.php",true,true);
	
	return true;
}
 
function Confirm_Volume_Configuration()
{
	if(gSemaphore_apply == true)
		return false;

	open_popup('idPopDelete');
	ShowDeleteVolume();
	return true;
}

function Close_Volume_Configuration()
{
	close_popup('idPopDelete');
}

function Change_Volume_Configuration()
{	
	close_popup('idPopDelete');
 	var 	varVOL_CONFIG_TYPE,varVOL_RAID1_SIZE,varVOL_VOL1_FORMAT,varVOL_VOL2_FORMAT;
	varVOL_VOL1_FORMAT = "ext3";
	varVOL_VOL2_FORMAT = "ext3";
	
	varVOL_CONFIG_TYPE = document.getElementById('idSelect3').value;
	
	//varVOL_RAID1_SIZE = document.getElementById('txtSIZEt').value;
	//varVOL_RAID0_SIZE = document.getElementById('txtSIZEt2').value;
	varVOL_RAID1_SIZE = 100;
	varVOL_RAID0_SIZE = 100;

	var VOL_RAID1_PERCENT = jQuery("#raid1Percent").text();
	var VOL_LINEAR_PERCENT = jQuery("#linearPercent").text();
	
	/*
	if ( ( varVOL_CONFIG_TYPE == "raidlinear" ) && ( varVOL_RAID1_SIZE == 0 ) ) {
		alert("<?php echo lang_get('volume_conf_11'); ?>");
		return;
	}
	*/
	var _txText =	'&VOL_TASK_CMD='+"CHANGE"
			+'&VOL_CONFIG_TYPE='+varVOL_CONFIG_TYPE
			+'&VOL_RAID1_SIZE='+varVOL_RAID1_SIZE
			+'&VOL_RAID0_SIZE='+varVOL_RAID0_SIZE
			+'&VOL_VOL1_FORMAT='+varVOL_VOL1_FORMAT
			+'&VOL_VOL2_FORMAT='+varVOL_VOL2_FORMAT
	
			+'&VOL_RAID1_PERCENT='+VOL_RAID1_PERCENT
			+'&VOL_LINEAR_PERCENT='+VOL_LINEAR_PERCENT;		

       document.getElementById('idSelect3').style.visibility='hidden';
	
	document.getElementById('idDisableBackground').style.height = getPageSizeWithScroll();	
	document.getElementById('idDisableBackground').style.display = 'block';
	
	layer.open();
	
	gSemaphore_apply = true;
	sendRequest(onLoadST,_txText,'post',"../php/volume_task.php",true,true);
 		
 	return true;
}

function onLoadST(oj)
{
	gSemaphore_apply = false;
	
	var res = new String();
	var code = new Array();
	res = decodeURIComponent(oj.responseText);
	
	code = res.split(':');
		
	//document.getElementById('idDisableBackground').style.display = "none";
	
	//document.getElementById('idPopSystemInit').style.visibility = 'hidden';
	document.getElementById('idDisableBackground').style.display = "none";
	layer.close();

	reloadPage();

	if(code[0] != 'ok') 
	{
		alert("<?php echo lang_get('volume_conf_failed')?>");
	}
}

function onLoadTC(oj)
{
	res = decodeURIComponent(oj.responseText);
	code = res.split(':');
	//alert(code[0]);
	//if(code[0] == 'ok') {display_POPUP(code[1]);}
	
}

function reloadPage()
{
	window.location.reload()
}


//=======================================================//
// Layer : show/hide warning message
//=======================================================//
var layer = {
        open : function(){
                document.getElementById('l_copy').style.display="block";
                var _oj = document.getElementById('l_copy');
                var _oji = document.getElementById('l_copy_img');

                _oji.src="../images/Burn/file_box_loading.gif";
		  setTimeout(refresh, 400);
                
        },
        close : function(){
                document.getElementById('l_copy').style.display="none";
        }
}

function refresh() {
        document.getElementById('l_copy').style.display="block";
        var _oj = document.getElementById('l_copy');
        var _oji = document.getElementById('l_copy_img');

        _oji.src="../images/Burn/file_box_loading.gif";

}

//========================================================//
// Volume popup window
//========================================================//
function open_popup(id)
{	
	document.getElementById(id).style.display = 'block';
	document.getElementById('volume_table').style.display = 'none';
}

function close_popup(id)
{
	document.getElementById('volume_table').style.display = 'block';
	document.getElementById(id).style.display = 'none';
}

//========================================================//
// Show volume info (Delete)
//========================================================//
function ShowDeleteVolume()
{
	var vol_num;

	var used_vol = "<?php echo $str1_array[2] ?>";	

	if("<?php echo $vol_num ?>" == 2)
		used_vol +="<?php echo $str2_array[2] ?>";

	document.getElementById('idDelVolume').innerHTML= 
	//"<?php echo lang_get('volume_msg_23')?> "+used_vol+" <?php echo lang_get('v_all_user_data_delete')?>";
	"<?php echo lang_get('volume_msg_23')?> "+" <?php echo lang_get('v_all_user_data_delete')?>";
}

//=======================================================//
// Resync Progress
//=======================================================//
var progress_show = false;
var resync = {
	per : 0,
	fFin : false,
	c_err : 0,
	c_err_max : 100,
	w_max : 180,
	timer : "",
	start_read : function(){
		this.timer = setInterval('resync.read()',200);
		//document.getElementById('resync').width = this.w_max / 100;
		//document.getElementById('resyncValue').innerHTML = "0 %";
		//document.getElementById('idresync_bar').style.visibility = "visible";
	},
	finish : function(){
		clearInterval(resync.timer);
		//document.getElementById('idresync_bar').style.visibility = "visible";
		//document.getElementById('resync').width = this.w_max;
		//document.getElementById('resyncValue').innerHTML = "100 %";		
		
	},
	stop : function(){
		if(this.timer) 
			clearInterval(this.timer);
	},
	read : function(){

		var cmd = '';
		var php = '../php/system_get_vol_resync.php';
		
		clearInterval(this.timer);
		this.timer = setInterval('resync.read()',5000);
		sendRequest(on_start_resync,cmd,'post',php,true,true);
		
		function on_start_resync(oj){
			var res=decodeURIComponent(oj.responseText);
			if(res=="no")
			{
				// Finish Resync			
				resync.stop();
				if(progress_show == true)
				{
					_w = 100;
					document.getElementById('idVolCapap0').innerHTML = _w+" %";
					document.getElementById('idVolProg_width0').width = resync.w_max/100*_w;
					progress_show = false;
					resync.finish_lcd();			
					
				}				
			}
			else
			{
				if(!document.getElementById('idVolCapap0')){
					return;
				}
							
				var rate = res.split(":");
				var _w = parseFloat(rate[1]);

				if(_w<1)	_w =1
				if(_w<99.9){
					progress_show = true;
					document.getElementById('idVolCapap0').innerHTML = _w+" %";
					resync.per = _w;
					document.getElementById('idVolProg_width0').width = resync.w_max/100*_w;					
					
				}
				else if(_w >=99.9)
				{
					_w = 100;
					document.getElementById('idVolCapap0').innerHTML = _w+" %";
					document.getElementById('idVolProg_width0').width = resync.w_max/100*_w;
					progress_show = false;
					resync.finish_lcd();
					
				}
				else
				{
					document.getElementById('idVolCapap0').innerHTML = "";
				}					
			}			
		}
	},
	finish_lcd : function(){
		this.finish();
		document.getElementById('idVolCapap0').innerHTML = "complete";
		window.location.href = '../system/volume.php';
 
	}
}

function Show_Read_HddUsedRate()
{
	var w_max =180;
	var HddUsed = "<?php echo $str1_array[4] ?>";
	var _w=0;

	if(HddUsed =='-')
		document.getElementById('idVolUsed0').innerHTML = "";	
	else
	{
		_w = parseFloat(HddUsed);

		document.getElementById('idVolUsed0').innerHTML = _w+" %";
		document.getElementById('idUseProg_width0').width = w_max/100*_w;
	}

	if("<?php echo $vol_num ?>" == 2)
	{
		HddUsed = "<?php echo $str2_array[4] ?>";

		if(HddUsed =='-')
			document.getElementById('idVolUsed1').innerHTML = "";	
		else
		{
			_w = parseFloat(HddUsed);

			document.getElementById('idVolUsed1').innerHTML = _w+" %";
			document.getElementById('idUseProg_width1').width = w_max/100*_w;
		}			
	}
}


function open_edit_vol()
{
	//check whether previous command was done	
	if(gSemaphore_apply == true)
		return false;

	document.getElementById('idCbEdtBay1').checked = false;
	document.getElementById('idCbEdtBay2').checked = false;
	
	gSemaphore_apply = true;
	
	jQuery.post('../php/status_get_info.php',{'mode' : 'hard_disk'}, function(result){
						gSemaphore_apply = false;
						
						var tmp = result.split(";");
						var tmp_mode = tmp.slice(0,1);
						tmp = tmp.slice(1);
						var vol=0;
						var cnt=1;
						var id=0;
					
						var bay = tmp[0].split("\n");
						for(var i=3;bay[i];i--)
						{		
							bay[i] = bay[i].split(" ");
					
							if ( bay[i][0] == 'Bay1' )
							{
								document.getElementById('id_EdtNameBay1').innerHTML = "B1";
								document.getElementById('id_EdtStateBay1').innerHTML =  bay[i][2];
								document.getElementById('id_EdtSizeBay1').innerHTML = VolRep(bay[i][3]);
								
							}
							else if ( bay[i][0] == 'Bay2' )
							{
								document.getElementById('id_EdtNameBay2').innerHTML = "B2";
								document.getElementById('id_EdtStateBay2').innerHTML = bay[i][2];
								document.getElementById('id_EdtSizeBay2').innerHTML = VolRep(bay[i][3]);
							}
						}
					
						document.getElementById('idVolEditBtnRemove').style.display="none";
						document.getElementById('idVolEditBtnAdd').style.display="none";
					
						if(("<?php echo $hdd2_size ?>" != 0) && ("<?php echo $hdd1_size ?>" != 0) )
						{
							document.getElementById('idTableEdtBay1').style.display = "block";
							document.getElementById('idTableEdtBay2').style.display = "block";
						
						}
						else
						{
							document.getElementById('idTableEdtBay1').style.display = "none";	
							document.getElementById('idTableEdtBay2').style.display = "block";
						
						}
					
						open_popup("idPopEdit");

	});
	

	return true;
}

function close_edit_vol()
{
	close_popup("idPopEdit");
	document.getElementById('idVolEditBtnRemove').style.display="none";
	document.getElementById('idVolEditBtnAdd').style.display="none";
}
function get_array(str)
{
	var tmp = str.split(";");
	return tmp;
}
function on_hdd_edit(oj)
{
	gSemaphore_apply = false;
	
	var res=decodeURIComponent(oj.responseText);
	var tmp = get_array(res);
	var tmp_mode = tmp.slice(0,1);
	tmp = tmp.slice(1);
	var vol=0;
	var cnt=1;
	var id=0;

	var bay = tmp[0].split("\n");
	for(var i=3;bay[i];i--)
	{		
		bay[i] = bay[i].split(" ");

		if ( bay[i][0] == 'Bay1' )
		{
			document.getElementById('id_EdtNameBay1').innerHTML = "B1";
			document.getElementById('id_EdtStateBay1').innerHTML =  bay[i][2];
			document.getElementById('id_EdtSizeBay1').innerHTML = VolRep(bay[i][3]);
			
		}
		else if ( bay[i][0] == 'Bay2' )
		{
			/*if(bay[i][1] == 'none')
			{
				continue;
			}*/
			document.getElementById('id_EdtNameBay2').innerHTML = "B2";
			document.getElementById('id_EdtStateBay2').innerHTML = bay[i][2];
			document.getElementById('id_EdtSizeBay2').innerHTML = VolRep(bay[i][3]);
		}
	}

	document.getElementById('idVolEditBtnRemove').style.display="none";
	document.getElementById('idVolEditBtnAdd').style.display="none";

	if(("<?php echo $hdd2_size ?>" != 0) && ("<?php echo $hdd1_size ?>" != 0) )
	{
		document.getElementById('idTableEdtBay1').style.display = "block";
		document.getElementById('idTableEdtBay2').style.display = "block";
		//document.getElementById('idVolEditBtnRemove').style.display="block";
		//document.getElementById('idVolEditBtnAdd').style.display="none";
	}
	else
	{
		document.getElementById('idTableEdtBay1').style.display = "none";	
		document.getElementById('idTableEdtBay2').style.display = "block";
		//document.getElementById('idVolEditBtnAdd').style.display="block";
		//document.getElementById('idVolEditBtnRemove').style.display="none";
	}

	open_popup("idPopEdit");

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


//========================================================//
// Popup window
//========================================================//
function open_popup(id)
{

	document.getElementById(id).style.display = 'block';
	document.getElementById('volume_table').style.display = 'none';

}
function close_popup(id)
{
	document.getElementById('volume_table').style.display = 'block';
	document.getElementById(id).style.display = 'none';
	
	//GetVolInfo();
}


function check_edit(check)
{
	var obj = document.getElementsByName("CbEdtBay");
	for(var i=0; i<obj.length; i++){
		if(obj[i] != check){
			obj[i].checked = false;
		}
	}

	if("<?php echo $now_resync ?>" == true)
	{
		alert("<?php echo lang_get('volume_msg_system_resync')?>");
		return;
	}

	if(("<?php echo $hdd2_size ?>" != 0) && ("<?php echo $hdd1_size ?>" != 0))
	{
		var vol=0;
		if(document.getElementById('idCbEdtBay1').checked)	
		{
			checked_vol =1;
			another_vol =2;
		}
		else	if(document.getElementById('idCbEdtBay2').checked)
		{
			checked_vol =2;
			another_vol =1;
		}
		else
		{
			document.getElementById('idVolEditBtnRemove').style.display="none";
			document.getElementById('idVolEditBtnAdd').style.display="none";	

			return;
		}

		var status = document.getElementById('id_EdtStateBay'+checked_vol).innerHTML;
		if(status == 'inactive' )
		{
			document.getElementById('idVolEditBtnRemove').style.display="none";
			document.getElementById('idVolEditBtnAdd').style.display="block";
		}
		else if(status == 'degrade' )
		{
			document.getElementById('idVolEditBtnRemove').style.display="none";
			document.getElementById('idVolEditBtnAdd').style.display="none";				
		}
		else if(status == 'active' )
		{
			var status_other = document.getElementById('id_EdtStateBay'+another_vol).innerHTML;
			if(status_other == 'active')
			{
				document.getElementById('idVolEditBtnRemove').style.display="block";
				document.getElementById('idVolEditBtnAdd').style.display="none";
			}
			else
			{
				document.getElementById('idVolEditBtnRemove').style.display="none";
				document.getElementById('idVolEditBtnAdd').style.display="none";			
			}			
		}
	
	}
	else
	{
		document.getElementById('idVolEditBtnAdd').style.display="none";
		document.getElementById('idVolEditBtnRemove').style.display="none";
	}

	return true;
}

function raid_remove_vol()
{

	var target, mode;
	if(document.getElementById('idCbEdtBay1').checked)	
		target = 'B1';		
	else if(document.getElementById('idCbEdtBay2').checked)	
		target = 'B2';
	else
	{
		alert("<?php echo lang_get('volume_msg_18')?>");
		return;
	}		
	
	var raid_type = "<?php echo $str1_array[0] ?>";
	
	var _txText = '&mode='+'remove'
		+"&target="+target
		+"&raid_type="+raid_type;

	sendRequest(onVolume,_txText,"post","../php/volume_set_info.php",true,true);

		
	document.getElementById('idDisableBackground').style.height = getPageSizeWithScroll();	
	document.getElementById('idDisableBackground').style.display = 'block';
	

	layer.open();

	return true;
}
function add_pre_check()
{
	var raid_type = "<?php echo $str1_array[0] ?>";
	//var _txText = '&mode='+'add_pre_check'
		//+"&raid_type="+raid_type;

	jQuery.post('../php/volume_set_info.php',{'mode' : 'add_pre_check','raid_type' : raid_type}, function(result){
			temp=result.split(" ");
			
			if(temp[0] == 'same' && raid_type != "raid1") 
			{
				// [NEED_MODIFY]
				jQuery("#idPopEdit").hide();
				jQuery("#div_same_uuid").show();
			}
			else{
				raid_add_vol();
			}
	});
	//sendRequest(onPreCheck,_txText,"post","../php/volume_set_info.php",true,true);


}

function onPreCheck(oj)
{
	var res = decodeURIComponent(oj.responseText);
	
	
	if(res == 'same UUID') 
	{
		// [NEED_MODIFY]
		jQuery("#idPopEdit").hide();
		jQuery("#div_same_uuid").show();
	}
	else{
		raid_add_vol();
	}
/*
	document.getElementById('idDisableBackground').style.display = "none";
	layer.close();

	reloadPage();
	*/

	
}

function raid_add_vol(add_mode)
{
	var target, mode;
	if(document.getElementById('idCbEdtBay1').checked)	
		target = 'B1';		
	else if(document.getElementById('idCbEdtBay2').checked)	
		target = 'B2';
	else
	{
		alert("<?php echo lang_get('volume_msg_18')?>");
		return;
	}
	
	if(add_mode == "add_no_format"){
		mode="add_no_format";
	}else{
		mode="add";
	}
	
	var raid_type = "<?php echo $str1_array[0] ?>";
	var _txText = '&mode='+mode
		+"&target="+target
		+"&raid_type="+raid_type;

		
	sendRequest(onVolume,_txText,"post","../php/volume_set_info.php",true,true);

       	
	document.getElementById('idDisableBackground').style.height = getPageSizeWithScroll();	
	document.getElementById('idDisableBackground').style.display = 'block';
	
	layer.open();


	return true;
}

function onVolume(oj)
{
	var res = decodeURIComponent(oj.responseText);

	if(res != 'ok') 
	{
		alert("Volume modification failed");
	}

	document.getElementById('idDisableBackground').style.display = "none";
	layer.close();

	reloadPage();


	
}

//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/system/help_volume.html','Help_ddns','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}


</SCRIPT>


<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="245" valign="top"><?php include "../inc/left.php";  ?></td>	<!-- left Navigation -->
	<td width="100%" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
		</tr>
		<tr>
		<td style="padding:0 0 0 50px">
	  		<div id="volume_table" style='display:block'>
	  		
                  	<!-- 1. Page Title : Network -->	 				 
			<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
			<tr>
			<td height="50" valign="top"><img src="../images/headtitle/htit_volume.gif" /></td>
			<!--<td width="51"><a href="javascript:void(0)"><img src="../images/btn/btn_add.gif" border="0" onclick='add_pre_check();' id="idVolEditBtnAdd" style='display:block'/></a></td>-->	
			</tr>
			</table>


                                   
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
                        <td class="header" style='height:25px;'><?php echo lang_get('volume_9'); ?></td>
                        <td class="header" style='height:25px;'>
                        <?php if ( $vol_num == 1 && $vol_list == 'volume2') { ?>
                        	volume2
			            <? } else{ ?>
			   	            volume1
                  	    <? } ?>

			            </td>                        
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_22'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str1_array[0] ?></td>
                    	</tr>                
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_10'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str1_array[1] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_11'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str1_array[2] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_12'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str1_array[3] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_13'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
					<table width="180" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="180" background="../images/Burn/img_burn_bg_middle.gif" id="idVolProg_bar0" style="display:block;">
							<img id="idUseProg_width0" src="../images/Burn/img_burn_bar_middle.gif" width="0" height="17"/>
							</td>
							<td align="left" height="17" width="100" style="position:absolute;top:352;left:570;">
								<strong><div id="idVolUsed0" ></div></strong>
							</td>
						</tr>               
					</table>

			</td>		
                       
                    	</tr>
                    
                    	<!--<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_14'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str1_array[5] ?></td>
                    	</tr>-->


		       <?php if ($str1_array[0] != lang_get('volume_17')) { ?>
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('trashcan'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
                            <a href="javascript:void(0)" onclick="trashcan_vol('1');" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_06','','../images/btn/btn_burn_06_on.gif',1)"><img src="../images/btn/btn_burn_06.gif" name="burning_tab_06" width="22" height="22" border="0" id="burning_tab_06" TITLE="<?php echo "trash can" ?>"/>
                            </a>
                             
                        </td>
         	       </tr>         	
                  	<? } ?>
                  	
		       <?php if ($now_resync == true && $vol_num == 1) { ?>
                    	<tr>
			   <td class="firstCol_250" style='height:25px;'>
			   <?php if($resync_type == 'volume') echo lang_get('volume_resync_volume'); else  echo lang_get('volume_resync_system');?>
			   </td>                        
			   <td class="otherCol_420" style='height:25px;'>	
					<table width="180" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="180" background="../images/Burn/img_burn_bg_middle.gif" id="idVolProg_bar0" style="display:block;">
							<img id="idVolProg_width0" src="../images/Burn/img_burn_bar_middle.gif" width="1" height="17"/>
							</td>
							<td align="left" height="17" width="100" style="position:absolute;top:404;left:570;">
								<strong><div id="idVolCapap0" ></div></strong>
							</td>
						</tr>               
					</table>
			  </td>	
    	
                  	<? } ?>

                 	
                  	
                    	<!--<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_18'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
                        	<select id='idSelect1'>
                        		<option <?php if($str1_array[6] == 'ext2'){ ?> selected <?php }?> value='ext2'>ext2</option>
                        		<option <?php if($str1_array[6] == 'ext3'){ ?> selected <?php }?> value='ext3'>ext3</option>
                        		<option <?php if($str1_array[6] == 'xfs'){ ?> selected <?php }?> value='xfs'>xfs</option>
                        	</select>
                        	<input type='button' value='Format' onclick="Format_Volume('1');"/>
                        </td>
                    	</tr>-->

                  	</table>   

                  	
                  	<?php if ( $vol_num == 2 ){ ?> 
                  	
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
                        <td class="header" style='height:25px;'><?php echo lang_get('volume_9'); ?></td>
                        <td class="header" style='height:25px;'>volume2</td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_22'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str2_array[0] ?></td>
                    	</tr>                    
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_10'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str2_array[1] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_11'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str2_array[2] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_12'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str2_array[3] ?></td>
                    	</tr>
                    
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_13'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
					<table width="180" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="180" background="../images/Burn/img_burn_bg_middle.gif" id="idVolProg_bar1" style="display:block;">
							<img id="idUseProg_width1" src="../images/Burn/img_burn_bar_middle.gif" width="0" height="17"/>
							</td>
							<td align="left" height="17" width="100" style="position:absolute;top:557;left:570;">
								<strong><div id="idVolUsed1" ></div></strong>
							</td>
						</tr>               
					</table>

                        </td>
                    	</tr>
                    
                    	<!--<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_14'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><?php echo $str2_array[5] ?></td>
                    	</tr>-->
                    	
                    	<!-- <tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('volume_18'); ?></td>
                        <td class="otherCol_420" style='height:25px;'>
                        	<select id='idSelect2'>
                        		<option <?php if($str2_array[6] == 'ext2'){ ?> selected <?php }?> value='ext2'>ext2</option>
                        		<option <?php if($str2_array[6] == 'ext3'){ ?> selected <?php }?> value='ext3'>ext3</option>
                        		<option <?php if($str2_array[6] == 'xfs'){ ?> selected <?php }?> value='xfs'>xfs</option>
                        	</select>
                        	<input type='button' value='Format' onclick="Format_Volume('2');"/>
                        </td>
                    	</tr> -->
 			<?php if ($str2_array[0] != lang_get('volume_17')) { ?> 
                    	<tr>
                        <td class="firstCol_250" style='height:25px;'><?php echo lang_get('trashcan'); ?></td>
                        <td class="otherCol_420" style='height:25px;'><a href="javascript:void(0)" onclick="trashcan_vol('2');" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('burning_tab_06','','../images/btn/btn_burn_06_on.gif',1)"><img src="../images/btn/btn_burn_06.gif" name="burning_tab_06" width="22" height="22" border="0" id="burning_tab_06" TITLE="<?php echo "trash can" ?>"/></a></td>
         	       </tr>     
                    	<? } ?>
                    	
		       <?php if ($now_resync == true && $vol_num == 2) { ?>
                    	<tr>
			   <td class="firstCol_250" style='height:25px;'>
			   <?php if($resync_type == 'volume') echo lang_get('volume_resync_volume'); else  echo lang_get('volume_resync_system');?>
			   </td>                          
			   <td class="otherCol_420" style='height:25px;'>	
					<table width="180" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="180" background="../images/Burn/img_burn_bg_middle.gif" id="idVolProg_bar0" style="display:block;">
							<img id="idVolProg_width0" src="../images/Burn/img_burn_bar_middle.gif" width="1" height="17"/>
							</td>
							<td align="left" height="17" width="100" style="position:absolute;top:606;left:570;">
								<strong><div id="idVolCapap0" ></div></strong>
							</td>
						</tr>               
					</table>
			  </td>	
    	
                  	<? } ?>
                    	
                  	</table>   
                  	
                  	<? } else{ ?>
                  	
                  	<? } ?>
                  	

			<?php if ( $hdd_index == 'all') { ?>
			<?php if ( $str1_array[0] == 'raid1' ||  $str1_array[0] == 'No RAID'){ ?> 		
			<!-- Volume Add/Remove -->
			<table width="670px" cellpadding="0px" cellspacing="0px"  style="margin-top:20px;">
				<tr>
					<td width="30px"><img src="../web_menu/images/icon_volume.gif"></td>
					<td width="640px" style="font-weight:bold;"><a href="javascript:void(0)" onclick='open_edit_vol()'><?php echo lang_get('volume_title_add_remove'); ?></a></td>
				</tr>
			</table>	
			<? } ?>
			<? } ?>


                  	<table width="670" border="0" cellspacing="0" cellpadding="0">
                     	<tr>
                        <td class="header" style='height:50px;'><?php echo lang_get('volume_20'); ?></td>
                        <td class="header" style='height:50px;'></td>
                    	</tr>


			<tr>
			<td class="firstCol_250"><?php echo lang_get('volume_21'); ?></td>
			<td class="otherCol_420">
			<?php 
			if($vol_num==0) {
				echo lang_get('volume_conf_10');
			}
			else if ($vol_num==1) {
				if ($str1_array[0]=="linear") {
					echo lang_get('volume_conf_2');
				}
				else if ($str1_array[0]=="raid0") {
					echo lang_get('volume_conf_4');
				}
				else if ($str1_array[0]=="raid1") {
					echo lang_get('volume_conf_3');
				}
				else {
					echo lang_get('volume_conf_1');
				}
			}
			else {
				if ($str2_array[0]=="raid1") {
					echo lang_get('volume_conf_6');
				}
				else {
					echo lang_get('volume_conf_1');
				}
			}
			?>
			</td>
			</tr>

                    	<tr>
                        <td class="firstCol_250" style='height:50px;'><?php echo lang_get('volume_19'); ?></td>
                        <td class="otherCol_420" style='height:50px;'>
                        	<table border="0" padding="0" margin="0">
                        		<tr><td>
			                       	<select id='idSelect3' style="visibility:visible;" onChange="showSlider();">
			                      	
			 <?php if ( $hdd_index == 'all') { ?>
			 	<?php if ($sys_md1!= "destroy"){ ?>

			                        		<option value='raid0'><?php echo lang_get('volume_conf_4'); ?></option>
			                        		<option value='raid1'><?php echo lang_get('volume_conf_3'); ?></option>
			 					<option value='linear'><?php echo lang_get('volume_conf_2'); ?></option>
			                        		<option value='raidlinear'><?php echo lang_get('volume_conf_6'); ?></option>
				<? } ?>
			<? } ?>
			<?php if ($hdd_force_out !='HDD is Removed') { ?>
			                        		<!-- <option value='raid01'><?php echo lang_get('volume_conf_5'); ?></option> -->
			                        		<option value='individual'><?php echo lang_get('volume_conf_1'); ?></option>
			                        		<!-- <option value='init'><?php echo lang_get('volume_conf_9'); ?></option> -->
			<? } ?>	
		                        	</select>
			                      	</td>

                           	<!--<input type='button' value='Change' onclick="Change_Volume_Configuration();"/>-->   
                           	  <td>
                        <?php if ($hdd_force_out != 'HDD is Removed') { ?>  
				<img src="../images/btn/btn_apply.gif"  border="0" onclick="Confirm_Volume_Configuration();" class="buttons"/>
			<? } ?>

			 											</td></tr></table>
                        </td>
                        </tr>
      <!--    
			<tr>
			<td class="firstCol_250"><?php echo lang_get('volume_conf_7'); ?></td>
			<td class="otherCol_420"><?php echo lang_get('volume_conf_7'); ?> : <input type="text" name="txtSIZEt" class="inputtext" id="txtSIZEt" size="10" maxlength="6"> GB </td>
			</tr>

			<tr>
			<td class="firstCol_250"><?php echo lang_get('volume_conf_8'); ?></td>
			<td class="otherCol_420"><?php echo lang_get('volume_conf_8'); ?> : <input type="text" name="txtSIZEt2" class="inputtext" id="txtSIZEt2" size="10" maxlength="6"> GB </td>
			</tr>
      -->                  
                    	</table>
											<div id="sliderContainer">
													<div id="raid1_linear"></div>
													
													<div id="raid1Slider"></div>
													<div id="linearSlider"></div>
											</div>

      	
                  	
    			</div>

									<!--popup windows-->
										<? include 'volume_delete.php' ?>
										<? include 'volume_edit.php' ?>
										


			<?php if( $hdd_force_out != '')  { ?>
			<!--<div id="hard_removed_abnormally" style='display:none'>-->
			<table width="670" border="0" cellspacing="0" cellpadding="0" >
				<tr>
					<td style="vertical-align:middle;" width="80px" height="40px" align="center"><img src="../images/comnso/cms_icon_exc.gif" border="0"/></td>
					<!--<td style="vertical-align:middle;" width="80px" height="40px" align="center"><img src="../images/comnso/cms_icon_error.gif" border="0"/></td>-->					
					<td height="30" align="left" class="red_s1">
						<?php echo lang_get('volume_msg_force_out'); ?>
					</td>
				<tr>
			</table>	
			<? } ?>
			<!--</div>-->

			<div id="div_same_uuid" style="display:none;margin-top:40px;width:670px; ">
				
				<table align="center" width="540" height="54"	border="0" cellspacing="0" cellpadding="0" id="all_table">
					<tr>
						<td	width="540"	height="54"	background="../images/popup/txt_popup_bg02.gif">
								<span class="popup_text" style="padding-left:20px;"><?php echo lang_get('hdd_add'); ?></span>
						</td>
					</tr>	
					
				</table>
				<table align="center" width="540" cellspacing="0" cellpadding="0" style="border:1px solid #e3e3e3;padding-left:10px;" >
					<tr height="30px">
						<td><?php echo lang_get('hdd_add_same_uuid'); ?></td>
					</tr>
					<tr height="30px">
						<td><?php echo lang_get('select_add_type'); ?></td>
					</tr>
					<tr height="40px">
						<td><input type='radio' class="radio_add_type" name="radio_add_type" value="hdd_add_remain_data" checked><?php echo lang_get('hdd_add_remain_data'); ?></td>
					</tr>
					<tr height="40px">
						<td><input type='radio' class="radio_add_type" name="radio_add_type" value="hdd_add_delete_data"><?php echo lang_get('hdd_add_delete_data'); ?></td>
					</tr>
				</table>
						<!-- 타이틀	테이블 끝-->
					
						
						<!-- Buttons -->
						<table align="center" width="540" border="0" cellspacing="0" cellpadding="0" style="margin-top:20px">
							<tr><td align="right">
									<img src="../images/btn/btn_apply.gif" id="same_uuid_apply" class="buttons"/>
									<img src="../images/btn/btn_cancel.gif" id="same_uuid_cancel" class="buttons"/>
									
						</td></tr>
				  	</table>
				<!-- 테이블	영역 끝-->
			</div>
			
			<!-- Page Loading Layer -->
									<div id="page_loading" align="center" style="position:absolute;left:450px;top:330px;width:300px;height:100px;display:none;background-color:#fff;">
			                                
			                            <table border="0" cellspacing="0" cellpadding="0" width="300px">	
																			<tr>
																				<td colspan="2" style="backgRound-color:#742625;color:#fff;height:25px;font-size:15px;font-weight:bold;padding-left:20px;"><?php echo lang_get('common_loading')?></td>
																			</tr>
																			<tr>
																			  <td style="border:1px solid #5d5d5d;border-right:none;height:75px;width:100px;" align="center">
																			  	<img Id="img_page_loading" src="../images/Burn/file_box_loading.gif"/>
																			  </td>
																			  <td style="border:1px solid #5d5d5d;border-left:none;height:75px;width:200px;"><?php echo lang_get('common_wait')?></td>
																			  
																			</tr>
																	</table>
			         		</div>    
    			
		</td>
		</tr>
  		</table>
  	</td>
  	</tr>
	</table>
</td>
</tr>          

<?php include "../inc/bottom.php";  ?>

<!--popup windows-->
<? include 'volume_init.php' ?>
<script type="text/javascript" charset='utf-8'>

Show_Read_HddUsedRate();
resync.start_read();

</script>




