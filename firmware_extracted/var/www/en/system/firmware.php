<? 
header("Expires: -1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html; charset=utf-8');

include "../inc/top.php"; ?>

<!---------------------------------
// LGE NAS-SSS
// By oneshot97
// Language	select
----------------------------------->
<script	language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script	language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script	language='javascript' charset='utf-8'>
	gPage='power';		// set page	name for language setting
</script>
<script	language='javascript' src='../lang/lang.js'	charset='utf-8'></script>		<!--language setting under developing-->
<!----------------------------------->

<!-- top cutting area -->

<script	language='javascript' type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr;	for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages()	{ //v3.0
	var d=document; if(d.images){	if(!d.MM_p)	d.MM_p=new Array();
	var	i,j=d.MM_p.length,a=MM_preloadImages.arguments;	for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image;	d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) {	//v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length)	{
	d=parent.frames[n.substring(p+1)].document;	n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x	&& d.getElementById) x=d.getElementById(n);	return x;
}

function MM_swapImage()	{ //v3.0
	var i,j=0,x,a=MM_swapImage.arguments;	document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src;	x.src=a[i+2];}
}
//-->
<!-- checkbox ȏ³ª¸¸ ¼±ƃ -->
gTmp=-1; 
function check_only(chk){
	var obj = document.getElementsByName("nameCbConf");
	for(var i=0; i<obj.length; i++){
		if(obj[i] != chk){
			obj[i].checked = false;
		}else
		{
			gTmp=i; 
		}
	}
	// Check if a backup file from PC is selected
	if(document.getElementById('chkConfFromPc').checked) {
		document.getElementById('chkConfFromPc').checked = false;
		conf.openUpload('chkConfFromPc');
	}	
} 
</script>



<script language="javascript1.2" src="../js/firmware.js.php" charset="utf-8"></script>
<tr>
<!-- All center area start-->
	<td valign="top">
	<table width="100%"	border="0" cellspacing="0" cellpadding="0">
		<tr>
		<!-- left cutting area -->
		<!-- left Navigation area start-->
			<td width="245" valign="top"><? include "../inc/left.php"; ?></td>
			<!--left End-->
			<td	width="100%" valign="top">
				<!-- Size Modify -->
			<table width="100%"	border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td	width="100%" height="7"	background="../images/Top/utility_shadow.gif"></td>
					<!-- Size  Modify -->
				</tr>
				<tr>
				<!--  Main Contents start -->
					<td valign="top" style="padding:0	0 0	50px">

						<!-- Head Title -->
						<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
		   					<tr>
		    						<td height="50" valign="top"><img src="../images/headtitle/htit_firmware.gif" /></td>
				  		</tr>
				  	</table>		
							
							
			<!-- Main Table area1 start-->
		 <div id="idTable_FirmwareUp" style="display:block">
	         <!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="#" onclick="openFirmUp();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('firmware_tab_Up01','','../images/tab/tab_upgrade_on.gif',1)"><img src="../images/tab/tab_upgrade_on.gif" name="firmware_tab_Up01" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="#" onclick="openFirmInit();" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('firmware_tab_Init01','','../images/tab/tab_initialization_over.gif',1)"><img src="../images/tab/tab_initialization.gif" name="firmware_tab_Init01" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td class="tab"><a href="#" onclick="openFirmConf();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('firmware_tab_Conf01','','../images/tab/tab_configuration_over.gif',1)"><img src="../images/tab/tab_configuration.gif" name="firmware_tab_Conf01" border="0"></a></td>
                </tr>
           </table>
           
           <!-- 2. Subtitle -->
					<div valign="top" style="height:33px;"><img src="../images/subtitle/stit_sys_info.gif"/></div>
					<div valign="top" style="height:25px;" class="red_text_8" ><img src="../images/icon/bullet.gif" style="margin-right:5px;"/><?php echo lang_get('firmware_msg_12')?></div>
					
           <!-- 3. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>                   
                    <td class="header" style="text-align:center;padding:0px;"><?php echo lang_get('firmware_upgrade_1'); ?></td>
                    <td class="header" style="text-align:center;padding:0px;"><?php echo lang_get('firmware_upgrade_2'); ?></td>
                    <td class="header" style="text-align:center;padding:0px;"><?php echo lang_get('firmware_upgrade_3'); ?></td>
                    <td class="header" style="text-align:center;padding:0px;">&nbsp;</td>
                    
                </tr>
                
                <!-- system Firmware -->
                <form name="frmSys" id="idfrmSys" method="post" enctype="multipart/form-data" action="../php/firmware_up_set.php">
                <tr>
                    <td class="firstCol" style="width:120px;"><?php echo lang_get('firmware_upgrade_4'); ?></td>
                    <td class="otherCol" style="width:120px;"><div id="id_FirmUpVer"></div></td>
                    <td class="thirdCol" style="width:160px;"><div id="id_FirmUpDate"></div></td>
                    <td class="otherCol" style="width:270px;text-align:left;padding-left:10px;">
                    		
                    				<input type="hidden" name="txtUpgrade" id="idUpgrade"/>
		                    		<input type="file" name="system" id="system" style="height:20;"/> 
		                    		<a href="#" onclick="setFirmSysUp();"><img src="../images/btn/btn_upgrade.gif" border="0"/></a>
                    </td>
                </tr>
                <!--</form>-->
                <!-- Odd Firmware -->
                <!--<form name="frmOdd" id="idfrmOdd" method="post" enctype="multipart/form-data" action="../php/firmware_up_set.php">-->
                <tr>
                    <td class="firstCol" style="width:120px;"><?php echo lang_get('odd'); ?></td>
                    <td class="otherCol" style="width:120px;"><div id="id_FirmUpOddVer"></div></td>
                    <td class="thirdCol" style="width:160px;"><div id="id_FirmUpOddDate"></div></td>
                    <td class="otherCol" style="width:270px;text-align:left;padding-left:10px;">
                    		<input type="file" name="odd" id="odd" style="height:20;"/>	
                    		<a href="#" onclick="setFirmOddUp();"><img src="../images/btn/btn_upgrade.gif"  border="0"/></a>    	
                    </td>
                </tr>
                </form>
                <tr>
                	<td colspan="4" class="otherCol_420" style="width:670px;height:40px;"><?php echo lang_get('firmware_upgrade_7'); ?></td>
                </tr>
              </table>  
 
     	</div>	  
							
							
			<!--
									<td style="padding:0 0 0 20px" class="m_gray_12">Upgrade File Download</td>
									<td width="1" height="25" bgcolor="#e3e3e3"></td>
									<td style="padding:0 0 0 20px" class="m_gray_12">http://www.nas.com/upgrade/download.html</td>					
			-->
							
							
		 <!-- Main Table area2 start-->						
		 <div id="idTable_FirmwareInit" style="display:none">
	         <!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="#" onclick="openFirmUp();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('firmware_tab_Up02','','../images/tab/tab_upgrade_over.gif',1)"><img src="../images/tab/tab_upgrade.gif" name="firmware_tab_Up02" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="#" onclick="openFirmInit();" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('firmware_tab_Init02','','../images/tab/tab_initialization_on.gif',1)"><img src="../images/tab/tab_initialization_on.gif" name="firmware_tab_Init02" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td class="tab"><a href="#" onclick="openFirmConf();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('firmware_tab_Conf02','','../images/tab/tab_configuration_over.gif',1)"><img src="../images/tab/tab_configuration.gif" name="firmware_tab_Conf02" border="0"></a></td>
                </tr>
           </table>
           
           <!-- 2. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0" class="basicTable">
                <tr>
                	<td background="../images/icon/img_system_02.jpg" valign="top" width="670px" height="170px" style="padding:40 0 0 140;" class="red_text_9"><?php echo lang_get('firmware_upgrade_8'); ?></td>
                </tr>
              </table>
                
 					<!-- 3. Buttons --> 
       			<table width="670" cellspacing="0" cellpadding="0">
       		 		<tr>
       		   		<td align="right"><img src="../images/btn/btn_initialization.gif" border="0" onclick="open_system_init_alert();" class="buttons"/></td>
          			
              </tr>
            </table>			
     	</div>								
			<!-- Main Table area2 End-->				
							
							
							
			<!-- Main Table area3 start-->
		 <div id="idTable_FirmwareConf" style="display:none">
	         <!-- 1. Tab images-->
	         <table width="670" cellspacing="0" cellpadding="0" class="tabTable">
                <tr >
                    <td width="50px" class="tab"><a href="#" onclick="openFirmUp();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('firmware_tab_Up03','','../images/tab/tab_upgrade_over.gif',1)"><img src="../images/tab/tab_upgrade.gif" name="firmware_tab_Up03" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td width="50px" class="tab"><a href="#" onclick="openFirmInit();" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('firmware_tab_Init03','','../images/tab/tab_initialization_over.gif',1)"><img src="../images/tab/tab_initialization.gif" name="firmware_tab_Init03" border="0"></a></td>
                    <td width="2" class="tab">&nbsp;</td>
                    <td class="tab"><a href="#" onclick="openFirmConf();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('firmware_tab_Conf03','','../images/tab/tab_configuration_on.gif',1)"><img src="../images/tab/tab_configuration_on.gif" name="firmware_tab_Conf03" border="0"></a></td>
                </tr>
           </table>
           
           <!-- 2. Subtitle -->
					<div valign="top" style="height:33px;"><img src="../images/subtitle/stit_backup.gif"/></div>
					
					
           <!-- 3. Contents -->   
              <table width="670" cellspacing="0" cellpadding="0">
                <tr>
                	  <td class="header" style="width:20px;">&nbsp;</td>
                    <td class="header" style="width:200px;"><?php echo lang_get('firmware_backup_1'); ?></td>
                    <td class="header" style="width:220px;"><?php echo lang_get('firmware_backup_2'); ?></td>
                    <td class="header" style="width:230px;"><?php echo lang_get('common_date'); ?></td>
                </tr>
               </table>
                
                <!-- Config File List -->
                <table width="670" cellspacing="0" cellpadding="0" id="idTableConf1">
		                <tr>
		                	  <td class="firstCol_250" style="width:20px;border-right:none;"><input type="checkbox" name="nameCbConf" onclick="check_only(this)" value="0" id="idCbConf1"></td>
		                    <td class="firstCol_250" style="width:200px;"><div id="id_FirmConfFile1"></div></td>
		                    <td class="otherCol_420" style="width:220px;"><div id="id_FirmConfVer1"></div></td>
		                    <td class="thirdCol_100" style="width:230px;"><div id="id_FirmConfDate1"></div></td>
		                </tr>
              	</table>
              	
              	<table width="670" cellspacing="0" cellpadding="0" id="idTableConf2">
                <tr>
                	  <td class="firstCol_250" style="width:20px;border-right:none;"><input type="checkbox" name="nameCbConf" onclick="check_only(this)" value="0" id="idCbConf2"></td>
                    <td class="firstCol_250" style="width:200px;"><div id="id_FirmConfFile2"></div></td>
                    <td class="otherCol_420" style="width:220px;"><div id="id_FirmConfVer2"></div></td>
                    <td class="thirdCol_100" style="width:230px;"><div id="id_FirmConfDate2"></div></td>
                </tr>
                </table>
              	
                <table width="670" cellspacing="0" cellpadding="0" id="idTableConf3">
                <tr>
                	  <td class="firstCol_250" style="width:20px;border-right:none;"><input type="checkbox" name="nameCbConf" onclick="check_only(this)" value="0" id="idCbConf3"></td>
                    <td class="firstCol_250" style="width:200px;"><div id="id_FirmConfFile3"></div></td>
                    <td class="otherCol_420" style="width:220px;"><div id="id_FirmConfVer3"></div></td>
                    <td class="thirdCol_100" style="width:230px;"><div id="id_FirmConfDate3"></div></td>
                </tr>
                </table>
              	
                <table width="670" cellspacing="0" cellpadding="0" id="idTableConf4">
                <tr>
                	  <td class="firstCol_250" style="width:20px;border-right:none;"><input type="checkbox" name="nameCbConf" onclick="check_only(this)" value="0" id="idCbConf4"></td>
                    <td class="firstCol_250" style="width:200px;"><div id="id_FirmConfFile4"></div></td>
                    <td class="otherCol_420" style="width:220px;"><div id="id_FirmConfVer4"></div></td>
                    <td class="thirdCol_100" style="width:230px;"><div id="id_FirmConfDate4"></div></td>
                </tr>
                </table>
              	
                <table width="670" cellspacing="0" cellpadding="0" id="idTableConf5">
                <tr>
                	  <td class="firstCol_250" style="width:20px;border-right:none;"><input type="checkbox" name="nameCbConf" onclick="check_only(this)" value="0" id="idCbConf5"></td>
                    <td class="firstCol_250" style="width:200px;"><div id="id_FirmConfFile5"></div></td>
                    <td class="otherCol_420" style="width:220px;"><div id="id_FirmConfVer5"></div></td>
                    <td class="thirdCol_100" style="width:230px;"><div id="id_FirmConfDate5"></div></td>
                </tr>
              </table>  


              <!-- -->
              <div style='font-weight:bold;'>
	              <input type='checkbox' id='chkConfFromPc' onclick='conf.openUpload(this.id);' /><?php echo lang_get('restore_conf_from_pc')?>
	              <div id='inputFromPc' style='display:none;padding-left:50px;'>
					<form name="frmSys" id="idfrmUpFromPc" method="post" enctype="multipart/form-data" action="../php/firmware_up_file_from_pc.php" target='uploadTrgt0'>
						<iframe name='uploadTrgt0' id='uploadTrgt0' style='display:none;'><html>test</html>
						</iframe>
						<input type='file' id='fileFromPc' name='upFileFromPc' style='height:20px;' />
					</form>
	              </div>
              </div>
              <!-- -->


              
              <!-- 4. Buttons -->
              <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:30px;">  
                <tr>
                	<td width="670px" align="right">
                			<img src="../images/btn/btn_save.gif" border="0" onclick="conf.save(this);" class="buttons"/>
                			<img src="../images/btn/btn_backup.gif" border="0" onclick="open_system_conf_backup();" class="buttons"/>
                			<img src="../images/btn/btn_restore.gif" border="0" onclick="open_system_conf_alert();" class="buttons"/>
                	</td>
                </tr>
              </table>  

		<!-- Download a config file to PC -->
		<form id="idForm" name="frmTS" method="post" action="../php/firmware_get_conf_file.php" >
		<input type="hidden" id="idInputLogMode" name="confFile" value="" />
		</form>
		<!-- -->
              
 
     	</div>								
							
							
							
							
							
							
							
							
							
							</td>
						</tr>
					</table></td>
					<!-- Main Contents	End-->
				</tr>
			</table>
			</td>
		</tr>
	</table></td>
<!-- All center area End-->
</tr>
<!-- bottom cutting area -->
<? include "../inc/bottom.php"; ?>

<!--popup windows-->
<? include 'firmware_init.php' ?>

<!--popup windows-->
<? include 'firmware_conf.php' ?>

<!--
<div id='idDisableBackground' style="position:absolute;width:100%;height:100%;top:0px;left:0px;z-index:200;border:none;background-color:#FFFFFF; opacity:0.2;moz-opacity:0.2;filter:alpha(opacity=20); display:none">
<table width="100%" height="100%" ><tr><td width="100%" height="100%" align="center" valign="center" >
	
</td></tr></table>
</div>
-->

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script language='javascript' charset='utf-8'>
init();
function init()
{
	debug('init : '+gPage+' menu');		// initialize
	// to do 
	// 1)language text
	// 2)
	GetFirmUpInfo();
	//Get_Ups_Info();
	conf.init();
}
</script>

