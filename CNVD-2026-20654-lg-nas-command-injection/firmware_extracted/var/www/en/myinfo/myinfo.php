<?	 include "../inc/top.php";  ?>
<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<!--Debugging message-->
<script language='javascript' src='../js/debug.js' ></script>
<!--ajax lib-->
<script language='javascript' src='../js/jslb_ajax.js' ></script>
<!---------------------------------------------------------------->
<script type="text/javascript" src="../js/common.js.php" charset="utf-8"></script>
<script type="text/javascript" src="../js/myinfo.js.php" charset="utf-8"></script>
<!---------------------------------------------------------------->



          <!-- top 자르는 영역 -->

          <tr>
          <!-- 전체center 영역 시작--> <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
			
			<!-- left 자르는 영역 -->
			
             

<!-- left Navigation 영역 시작-->
<td width="245" valign="top"><?	 include "../inc/left.php";  ?></td>
<!-- left 끝-->
<td width="100%" valign="top"><!-- 사이즈 수정 -->
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="7" background="../images/Top/utility_shadow.gif"></td>
    <!-- 사이즈 수정 -->
  </tr>
  <tr>
  
  <!-- 중앙내용 시작 -->
  
  <td style="padding:0 0 0 50px" valign="top">

  
  
  <!-- Info (1) -->
  <div id="id_table01" style="display:block;" >
  		<!-- 1. HeadTitle -->
		  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;">
		    <tr>
		      <td height="50" valign="top"><img src="../images/headtitle/htit_myinfo.gif" /></td>
		    </tr>
		   </table>
   
   		<!-- 2.Contents -->
		  <table width="670px" cellspacing="0" cellpadding="0" class="basicTable">
                
          <tr>
              <td class="firstCol_250" style="border-top:1px solid #e3e3e5;"><?php echo lang_get('user_list_2')?></td>
              <td class="otherCol_420" style="border-top:1px solid #e3e3e5;"><div id="idName"></div></td>
          </tr>
					
          <tr>
              <td class="firstCol_250"><?php echo lang_get('user_list_1')?></td>
              <td class="otherCol_420"><div id="idId"></div></td>
          </tr>
          
          <tr>
              <td class="firstCol_250"><?php echo lang_get('user_create_2')?></td>
              <td class="otherCol_420"><div id="idPw"></div></td>
          </tr>
          
          <tr>
              <td class="firstCol_250"><?php echo lang_get('user_create_5')?></td>
              <td class="otherCol_420"><div id="idEmail"></div></td>
          </tr>
          
          <tr>
              <td class="firstCol_250"><?php echo lang_get('user_create_4')?></td>
              <td class="otherCol_420"><div id="idDesc"></div></td>
          </tr>
      </table>
      
      <!-- 3. Buttons -->
      <table width="670px" cellspacing="0px" cellpadding="0px">
        <tr>
          <td align="right" valign="bottom"><img src="../images/btn/btn_edit.gif" border="0" onclick="table.open(1);" class="buttons"/></td>
        </tr>
      </table>
</div>

  <!-- Info (2) -->
  <div id="id_table02" style="display:none;">
  
		  <!-- 1. HeadTitle -->
		  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;">
		    <tr>
		      <td height="50" valign="top"><img src="../images/headtitle/htit_edit_myinfo.gif" /></td>
		    </tr>
		  </table>
		    
			<!-- 2. Contents -->			   
			<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
			
			      <tr>
			          <td class="firstCol_250" style="border-top:1px solid #e3e3e5;"><?php echo lang_get('user_list_2')?></td>
			          <td class="otherCol_420" style="border-top:1px solid #e3e3e5;"><input onblur="FormCheck(this.id);" name="textfield" type="text" class="inputtext" id="idNameIn" size="30" maxlength="30"/></td>
			      </tr>
						
			      <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_list_1')?></td>
			          <td class="otherCol_420"><div id="idIdIn" ></div></td>
			      </tr>
			      
			     <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_create_2')?> *</td>
			          <td class="otherCol_420"><input onblur="FormCheck(this.id);" type="password" class="inputtext" id="idPwIn" size="30" maxlength="20"/></td>
			      </tr>
			      
			      
			      <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_create_6')?></td>
			          <td class="otherCol_420"><input onblur="FormCheck(this.id);" type="password" class="inputtext" id="idPwNew" size="30" maxlength="20"/></td>
			      </tr>
			      
			      <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_create_7')?></td>
			          <td class="otherCol_420"><input onblur="FormCheck(this.id);" type="password" class="inputtext" id="idPwNew2" size="30" maxlength="20"/></td>
			      </tr>
			      
			      <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_create_5')?></td>
			          <td class="otherCol_420"><input onblur="validate_email(this.id);" name="textfield3" type="text" class="inputtext" id="idEmailIn" size="30" maxlength="30"/></td>
			      </tr>
			      
			      <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_create_4')?></td>
			          <td class="otherCol_420"><input onblur="FormCheck(this.id);" name="textfield4" type="text" class="inputtext" id="idDescIn" size="30" maxlength="40"/></td>
			      </tr>
			</table>
			
			<!-- 3. Buttons -->
			<table width="670px" cellspacing="0px" cellpadding="0px">
			  <tr>
			    <td align="right"><img onclick="table.open(0);" src="../images/btn/btn_back.gif" id="id_btn_back" border="0" class="buttons"/>
			    									<img onclick="user.confirm_pw();" src="../images/btn/btn_apply.gif" id="id_btn_app" border="0" class="buttons"/></td>
			  </tr>
			</table>
 </div>
          
  		 <!-- Message Table : Start -->	
			<div id="id_popup_my_info" style="width:670px; margin-top:40px; display:none" >
					<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
							<tr>
									<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px">
										<span class="popup_text" id="id_popup_title"><?php echo lang_get('my_info_1');?></span>
								  </td>
							</tr>
							<tr>
									<td align="center" valign="top" style="padding:0 0 0 0px">
										<div id="system_message">&nbsp;</div>
									</td>
							</tr>

					</table> 		
			</div>
  <!-- Info (3) -->
	<!--  
  <div id="id_table03" style="display:none;">
  

		  <table width="670" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;">
		    <tr>
		      <td height="50" valign="top"><img src="../images/headtitle/htit_edit_myinfo.gif" /></td>
		    </tr>
		     <tr>
      		<td height="20" class="red_s2">Please enter your password again</td>
    		 </tr>
		  </table>
		    
		   
			<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable" style="border:17px solid #dcdcdc;">
			
			      <tr>
			          <td class="firstCol_250" style="border-top:1px solid #e3e3e5;"><?php echo lang_get('user_list_2')?></td>
			          <td class="otherCol_420" style="border-top:1px solid #e3e3e5;"><input onblur="FormCheck(this.id);" name="textfield" type="text" class="inputtext" id="idNameIn" size="30" maxlength="20"/></td>
			      </tr>
						
			      <tr>
			          <td class="firstCol_250"><?php echo lang_get('user_list_1')?></td>
			          <td class="otherCol_420"><div id="idIdIn" ></div></td>
			      </tr>

			</table>
			

			<table width="670px" cellspacing="0px" cellpadding="0px">
			  <tr>
			    <td align="right"><img src="../images/btn/btn_back.gif" id="id_btn_back" onclick="table.open(3);" border="0" class="buttons"/>
			    									<img src="../images/btn/btn_apply.gif" id="id_btn_app" onclick="user.confirm_pw();" border="0" class="buttons"></td>
			  </tr>
			</table> 	
  </div>	
  -->


  </td><!-- 중앙내용 끝-->
  
  </tr>
  
</table>
</td>

            </tr>
          </table></td>
          <!-- 전체center 영역 끝-->
          
          </tr>
          <!-- bottom 자르는 영역 -->
          <?	 include "../inc/bottom.php";  ?>
<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language='javascript' charset='utf-8'>
	page.init();
</script>