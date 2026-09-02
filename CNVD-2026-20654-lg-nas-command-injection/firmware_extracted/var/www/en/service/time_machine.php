<?
$page=substr(__FILE__,strrpos(__FILE__,"/")+1);
	 include "../inc/top.php";  ?>

<script language="javascript1.2" src="./timemachine.js.php" charset="utf-8"></script>

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


 			<!-- Message Table : Start -->	
			<div id="idTable_POPUP" style="width:670px; margin-top:40px; display:none" >
					<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
							<tr>
									<td height="54px"  background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px"><span class="popup_text"><?php echo lang_get('TimeMachine_1')?></span></td>
							</tr>
							<tr>
									<td align="center" valign="top" style="padding:0 0 0 0px">
										<div id="system_message"><?php echo lang_get('common_loading')?></div>
									</td>
							</tr>
					</table> 		
			</div>

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

	  		
			<!-- 1. Page Title : Network -->
			<div id="idTable_Title" style="display:block" >
				<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
						<tr>
						  <td height="50" valign="top"><img src="../images/headtitle/htit_time_machine.gif"/></td>
						</tr>
				</table>
			</div>
                     
			<div id="idTable_box" style="display:block;" >
				<table width="670" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="header" width="250px"><?php echo lang_get('TimeMachine_1')?></td>
						<td class="header" width="420px">
							<div id="idCheck_enable" style="display:block;">
								<input type="radio" name="rdoTimeMachine" id="rdoTimeMachine_enable" value="on" /><label for="rdoTimeMachine_enable"><?php echo lang_get('common_enable')?></label>	
								<input type="radio" name="rdoTimeMachine" id="rdoTimeMachine_disable" value="off" /><label for="rdoTimeMachine_disable"><?php echo lang_get('common_disable')?></label>
							</div>
						</td>
					</tr>
				</table>
				
				<div id="idAfpDirList" style="display:block;"></div>

				<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
					<tr>
					<td colspan="2" align="right" style="padding:20 0 0 0px"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_Timemachine_Info();" class="buttons"/></td>
					</tr>				
                    	
				</table>   
			</div>
		</td>
		</tr>	
		<!--<tr>
		<td colspan="2" align="right" style="padding:20 0 0 0px"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_TimeMachine();" class="buttons"/></td>
		</tr>-->				
  		</table>
  	</td>
  	</tr>
	</table>		
</td>
</tr>        





<?php include "../inc/bottom.php";  ?>


<script language='javascript' charset='utf-8'>
       Get_TimeMachine_info();	
       
</script>

 
