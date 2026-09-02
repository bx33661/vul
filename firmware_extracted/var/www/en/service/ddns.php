<?
$page=substr(__FILE__,strrpos(__FILE__,"/")+1);
	 include "../inc/top.php";  ?>
<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' charset='utf-8'>
	gPage='servers';		// set page name for language setting
</script>
<!----------------------------------->



          <!-- top ????? ???? -->
          <script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>

<script language="javascript1.2" src="../js/ddns.js.php" charset="utf-8"></script>


          <tr>
         
          <!-- ??ucenter ???? ????--> <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			
			<!-- left ????? ???? -->
			
             

<!-- left Navigation ???? ????-->
<td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
<!-- left ??-->
<td width="100%" valign="top"><!-- ?????? ??? -->
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
    <!-- ?????? ??? -->
  </tr>
  <tr>
  <!-- ?????? ???? -->  
	<td style="padding:0 0 0 50px">


	<div id="idTable_DDNS" style="display:block;">
		  <table width="670" border="0" cellspacing="0" cellpadding="0" >
		    <tr>
		      <td height="40"></td>
		    </tr>
		    <tr>
		      <td height="50" valign="top"><img src="../images/headtitle/htit_ddns.gif" width="78" height="29" /></td>
		    </tr>
		  </table>
		  
			<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
			<tr>
			<td class="header"><?php echo lang_get('ddns_1'); ?></td>
			<td class="header"><input type="radio" name="rdoDDNS" id="rdoDDNS_enable" value="on" onclick="radio_check('on');"/><label for="rdoDDNS_enable"><?php echo lang_get('common_enable'); ?></label>
			                 <input type="radio" name="rdoDDNS" id="rdoDDNS_disable" value="off" onclick="radio_check('off');"/><label for="rdoDDNS_disable"><?php echo lang_get('common_disable'); ?></label>
			</td>  
			</tr>    
			
			<tr>
		       	<td class="firstCol_250"><?php echo lang_get('ddns_7')?></td>
		       	<td class="otherCol_420"><select name="service" style="DISPLAY:block; WIDTH: 255px; HEIGHT: 20px;" class="inputtext" id="service" onchange="clearDomain();" <!--onclick="radio_check('on');"-->  >
						                            	<option value="lgnas"><?php echo lang_get('ddns_8')?></option>
						                            	<option value="dyndns"><?php echo lang_get('ddns_9')?></option>
						                         </select></td>
			</tr>			

			<tr>
			<td class="firstCol_250"><?php echo lang_get('ddns_5'); ?></td>
			<td class="otherCol_420"><input name="txtDDNS_USER" type="text" class="inputtext" id="txtDDNS_USER" onKeyUp="onUpdateUser();"size="20" value="" /><!--.lgnas.com--></td>    
			</tr>

			<tr>
			<td class="firstCol_250"><?php echo lang_get('ddns_3'); ?></td>
			<td class="otherCol_420"><input name="txtDDNS_PASS" type="password" class="inputtext" id="txtDDNS_PASS" size="20" value="" /></td>    
			</tr>

			<tr>
			<td class="firstCol_250"><?php echo lang_get('ddns_6'); ?></td>
			<td class="otherCol_420"><input name="txtDDNS_DOMAIN" type="text" class="inputtext" id="txtDDNS_DOMAIN" size="20" value="" /></td>    
			</tr>
									
			<tr>
			<td class="firstCol_250"><?php echo lang_get('common_status'); ?></td>
			<td class="otherCol_420"><div id="id_Status"><?php echo lang_get('common_loading'); ?></div></td>
			</tr>
      <tr>
    	<td colspan="2" class="otherCol_420" style="width:670px;height:40px;"><?php echo lang_get('ddns_msg_12'); ?></td>
      </tr>			
			</table>
											
				<!-- Buttons -->
				<table width="670" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width=670px" align="right"><input type="image" name="IMG_BUTTON" id="Button_Apply" src="../images/btn/btn_apply.gif" border="0" onclick="Set_DDNS();"></td>
					</tr>
				</table>
	</div>

	<div id="idTable_UPnP_P_FWD" style="display:block;">
		  <table width="670" border="0" cellspacing="0" cellpadding="0" >
		    <tr>
		      <td height="40"></td>
		    </tr>
		    <!--<tr>
		      <td height="50" valign="top"><img src="../images/headtitle/htit_ddns.gif" width="78" height="29" /></td>
		    </tr>-->
		  </table>
		  
			<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
			<tr>
			<td class="header"><?php echo lang_get('upnp_p_fwd_name'); ?></td>
			<td class="header">
			            <div id='id_set_en_dis' style='visibility:visible;'>
					<input type="radio" name="rdoUPnP_P_FWD" id="rdoUPnP_P_FWD_enable" value="on" onclick="radio_check('on');"/><label for="rdoUPnP_P_FWD_enable"><?php echo lang_get('common_enable'); ?></label>
			              <input type="radio" name="rdoUPnP_P_FWD" id="rdoUPnP_P_FWD_disable" value="off" onclick="radio_check('off');"/><label for="rdoUPnP_P_FWD_disable"><?php echo lang_get('common_disable'); ?></label>
				     </div>
			</td>  
			</tr>    
			<tr>
			<td class="firstCol_250"><?php echo lang_get('upnp_p_fwd_IGD'); ?></td>
			<td class="otherCol_420"><div id="id_IGD"><?php echo lang_get('common_loading'); ?></div></td>    
			</tr>

			</table>
											
				<!-- Buttons -->
				<table width="670" border="0" cellspacing="0" cellpadding="0">
					<tr>						
						<td align="right">
							<!--<input type="image" name="IMG_BUTTON" id="Button_Show_IGD" src="../images/btn/btn_search.gif" border="0" onclick="Get_UPnP_P_FWD_Info();">-->
							<input type="image" name="IMG_BUTTON" id="Button_Apply_UPnP_P_FWD" src="../images/btn/btn_apply.gif" border="0" onclick="Set_UPnP_P_FWD();">
					</tr>
				</table>
				
	</div>

	

<!-- Message Table : Start -->	
	
	<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" >
		<table width="420" height="260" align="center" cellspacing="0" cellpadding="0" >
		<tr>
			<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px;"><span class="popup_text"><div id='idMsgTitle'>DDNS</div></span></td>
		</tr>
		<tr>
			<td align="center" valign="top" style="padding:0 0 0 0px">
				<div id="system_message"><?php echo lang_get('common_loading')?></div>
			</td>
		</tr>
		</table> 		
	</div>

	</td><!-- ?????? ??-->

            </tr>

      </table></td>



            </tr>
          </table></td>
          <!-- ??ucenter ???? ??-->
          



          </tr>

          <!-- bottom ????? ???? -->
          <?	 include "../inc/bottom.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language='javascript' charset='utf-8'>

function DomainCheck() {

	if(!(valid_domain(document.getElementById('txtDDNS_USER')))) {
		if(document.getElementById('txtDDNS_USER') =='') return true;
			else{
				//alert("The entered domain name is not valid\n may include at least 3 and up to 20 alphabet characters only"); 
				alert("<? echo lang_get('network_msg_21') ?>");				
				document.getElementById('txtDDNS_USER').value = "";
			}
	return false;
		
	}

	return true;
}

function containsCharsOnly(input,chars) {

    	var non_start_char = "-";
    	if(!(non_start_char.indexOf(input.value.charAt(0)) == -1)) return false;

    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1) 
           	return false;
    	}
    	return true;
}

function valid_domain(input) {
    	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	if(input.value.length<3) return false;
	if(input.value.length>20) return false;
    	return containsCharsOnly(input,chars);
}

function radio_check(mode)
{
	if( mode == 'on' && gDDNS == 'off' ) {
		document.getElementById('service').disabled = false;
		document.getElementById('txtDDNS_USER').disabled = false;
		document.getElementById('txtDDNS_PASS').disabled = false;
		
		if(document.getElementById('service').options[0].selected == true)
			document.getElementById('txtDDNS_DOMAIN').disabled = true;
		else
		{
			document.getElementById('txtDDNS_DOMAIN').disabled = false;
		}
	}
	else {
		document.getElementById('service').disabled = true;
		
		document.getElementById('txtDDNS_USER').disabled = true;
		document.getElementById('txtDDNS_PASS').disabled = true;
	}

}

function init()
{
	Get_DDNS_Info();		// get timezone list from server
	Get_UPnP_P_FWD_Info();
}

init();
</script>
