<?	 include "../inc/top.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' src='../js/common.js.php' charset='utf-8'></script>	<!--ajax lib-->
<script language='javascript' charset='utf-8'>
	gPage='time';		// set page name for language setting
</script>
<!----------------------------------->

          <!-- top ????? ???? -->
<script language='javascript' type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function validate_email(form_id) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.getElementById(form_id).value;
   if(document.getElementById(form_id).value=='') return true;
   if(reg.test(address) == false) {
	alert("<?php echo lang_get('mail_msg_5')?>");
      
      document.getElementById(form_id).focus(); 
      return false;
   }
}

function validate_server(form_id) {
   var reg = /^([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})+\:([0-9]{2,6})$/;
   var address = document.getElementById(form_id).value;
   if(document.getElementById(form_id).value=='') return true;
   if(reg.test(address) == false) {
      alert('Invalid Address Format');
      document.getElementById(form_id).focus(); 
      return false;
   }
}


-->
</script>

<script language="javascript1.2" src="../js/email.js.php" charset="utf-8"></script>


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
  <!-- ?????? ???? -->  <td style="padding:0 0 0 50px">

        
    <!-- Mail_Info List -->      
	  <div id="idTable_Mail_Info" style='display:block'>
                  <!-- 1. Page Title : Network -->	 				 
				 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
					   					<tr>
					    						<td height="50" valign="top"><img src="../images/headtitle/htit_mail.gif" /></td>
							  		</tr>
							  	</table>
                  
                  <table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     <tr>
                        <td class="header"><?php echo lang_get('mail_1'); ?></td>
                         <td class="header"><div id="id_email"><?php echo lang_get('common_loading'); ?></div></td>
                    </tr>
                    
                    <tr>
                        <td class="firstCol_250"><?php echo lang_get('mail_2'); ?></td>
                         <td class="otherCol_420"><div id="id_SMTP_SERVER"><?php echo lang_get('common_loading'); ?></div></td>
                    </tr>
                    
                    
                    <tr>
                        <td class="firstCol_250"><?php echo lang_get('mail_3'); ?></td>
                         <td class="otherCol_420"><div id="id_Subject"><?php echo lang_get('common_loading'); ?></div></td>
                    </tr>
                    
                    <tr>
                        <td class="firstCol_250"><?php echo lang_get('mail_4'); ?></td>
                         <td class="otherCol_420"><div id="id_Mailto"><?php echo lang_get('common_loading'); ?></div></td>
                    </tr>
                    
                    <tr>
                        <td class="firstCol_250"><?php echo lang_get('mail_5'); ?></td>
                         <td class="otherCol_420"><div id="id_HDD_Report"><?php echo lang_get('common_loading'); ?></div></td>
                    </tr>
                    
                    <tr>
                        <td class="firstCol_250"><?php echo lang_get('mail_6'); ?></td>
                         <td class="otherCol_420"><div id="id_HDD_Report_Term"><?php echo lang_get('common_loading'); ?></div></td>
                    </tr>
                  </table>   
                  <table width="670px" cellspacing="0px" cellpadding="0">
                    <tr>
                      <td align="right"><img src="../images/btn/btn_edit.gif" border="0" onclick="editMode();" class="buttons"/></td>
                    </tr>
                  </table>
    </div>
	  
	  <!-- Mail Setting -->
	  <div id="idTable_Mail_Edit" style='display:none'>
              <!-- 1. Page Title : Network -->	 				 
		 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
			   					<tr>
			    						<td height="50" valign="top"><img src="../images/headtitle/htit_mail.gif" /></td>
					  		</tr>
					  	</table>
							  	
	  					<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
	  								<!-- Mail Notification -->
                    <tr>
                      <td class="header"><?php echo lang_get('mail_1'); ?></td>
                      <td class="header"><input type="radio" name="rdo_Email" id="rdo_Email_Enable" value="on" onclick="check_email_form();"/><?php echo lang_get('common_enable'); ?>
                                         <input type="radio" name="rdo_Email" id="rdo_Email_Disable" value="off" onclick="check_email_form();"/><?php echo lang_get('common_disable'); ?>
                      </td>
                    </tr>
                    
                    <!-- SMTP Server Address -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_2'); ?></td>
											<td class="otherCol_420"><input name="SMTP_SERVER" type="text" class="inputtext" id="SMTP_SERVER" size="40" maxlength="40" onblur="FormCheck('SMTP_SERVER');"/></td>
										</tr>
										
										<!-- SMTP Authentication -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_edit_1'); ?></td>
											<td class="otherCol_420"><input type="checkbox" name="chkSMTP_AUTH" id="chkSMTP_AUTH" value="on" onclick="check_email_form();"/>
												                       <?php echo lang_get('mail_edit_2'); ?></td>
										</tr>    
										
										<!-- SMTP Server USER ID -->			
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_edit_3'); ?></td>
											<td class="otherCol_420"><input name="SMTP_USER" type="text" class="inputtext" id="SMTP_USER" size="40" maxlength="40" onblur="FormCheck('SMTP_USER');"/></td>
										</tr>
										
										<!-- SMTP Server USER PASSWORD -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_edit_4'); ?></td>
											<td class="otherCol_420"><input name="SMTP_PASS" type="password" class="inputtext" id="SMTP_PASS" size="40" maxlength="40" onblur="FormCheck('SMTP_PASS');"/></td>
										</tr>
										
										<!-- SMTP SSL Support -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_edit_5'); ?></td>
											<td class="otherCol_420"><input type="checkbox" name="chkSMTP_SSL" id="chkSMTP_SSL" value="on" onclick="check_email_form();"/>
                                               <?php echo lang_get('mail_edit_6'); ?></td>
										</tr>
										
										<!-- Subject -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_3'); ?></td>
											<td class="otherCol_420"><span id="SUBJECT">NAS Status Report</span></td>
											<!-- <input name="SUBJECT" type="text" class="inputtext" id="SUBJECT" size="40" maxlength="40" value="NAS STATUS REPORT" onblur="FormCheck('SUBJECT');" disabled/> -->
										</tr>
										
										<!-- Recipient Mail Address -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_4'); ?></td>
											<td class="otherCol_420"><input name="SMTP_MAILTO" type="text" class="inputtext" id="SMTP_MAILTO" size="40" maxlength="40" onblur="validate_email('SMTP_MAILTO');"/></td>
										</tr>
										
										<!-- Notification Trigger -->
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_5'); ?></td>
											<td class="otherCol_420"><input type="checkbox" name="chkHDD_Report" id="chkHDD_Report" value="on" onclick="check_email_form();"/>
                                               <?php echo lang_get('mail_edit_13'); ?></td>
										</tr>
										
										<!-- HDD Status Sending Time -->			
										<tr>
											<td class="firstCol_250"><?php echo lang_get('mail_6'); ?></td>
											<td class="otherCol_420"><select name="HDD_Report_Term" style="DISPLAY:block; WIDTH: 255px; HEIGHT: 20px;" class="inputtext" id="HDD_Report_Term" >
											                            <option value="day"><?php echo lang_get('mail_edit_8'); ?></option>
											                            <option value="week"><?php echo lang_get('mail_edit_9'); ?></option>
											                            <option value="month"><?php echo lang_get('mail_edit_10'); ?></option>
											                         </select></td>
										</tr>					
																																															
					</table>
					
					<!-- Buttons -->
					<table width="670px" cellspacing="0px" cellpadding="0px">
            <tr>
              <td width="670px"align="right"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_Email_Info('TEST');" class="buttons"/>
              	                             <img src="../images/btn/btn_cancel.gif" border="0" onclick="Get_Email_Info();showTable('idTable_Mail_Info');" class="buttons"/>
              </td>
            </tr>
          </table>
    </div>

		<!-- Mail Setting -->					
		<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" > 				 
		<table width="420" height="260" align="center" border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td width="420" height="54" background="../images/popup/txt_popup_bg_01.gif">
				<span class="popup_text" style="padding-left:20px;line-height:30px"><?php echo lang_get('mail_1')?></span>	
		</td>
		</tr>
		<tr>
		<td align="center" valign="top" style="padding:0 0 0 0px"><!-- �߾� ���� ���� -->
		  <div id="system_message">
		Loading...
		  </div>
		<!-- �߾ӳ��� �� --></td>
		</tr>
		</table> 			
		
		</div>




          


        <!-- ??? ????? ???? ??--></td>
    </tr>
  </table></td><!-- ?????? ??-->
  </tr>
</table></td>

            </tr>
          
          <!-- bottom ????? ???? -->
          <?	 include "../inc/bottom.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->

<script language='javascript' charset='utf-8'>
init();
function init()
{
	//debug('init : '+gPage+' menu');		// initialize
	// to do 
	// 1)language text
	// 2)
	Get_Email_Info();		// get timezone list from server
	
	//Show_Email_Info();
	//getServerTime();			// get server time & display it
}
</script>

