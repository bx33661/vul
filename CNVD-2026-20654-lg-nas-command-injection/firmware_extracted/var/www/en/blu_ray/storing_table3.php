<!-- 전체 탭 테이블 끝 -->
<div id='idTable3' style='display:none;'>
<table width="670px" cellspacing="0px" cellpadding="0px" style="margin-top:20px">
	<tr>
		<td width="180px"><img src="../images/icon/img_data_disc.gif" width="140" height="145"></td>
		<td width="490px">
			
  			<!-- 오른쪽테이블내용시작-->
			  <table width="490" border="0" cellspacing="0" cellpadding="0" class="basicTable">
			    <tr>
			      <td class="header" colspan="2"><?php echo lang_get('storing_backup_2')?></td>
			    </tr>
			    <tr>
			    	<td class="firstCol_250" style="width:130px"><?php echo lang_get('storing_copy_3')?></td>
			      <td class="otherCol_420" style="width:360px"><div id='ripping_Txt01'><?php echo lang_get('storing_backup_2')?></div></td>        
					</tr>
					<tr>
			    	<td class="firstCol_250" style="width:130px"><?php echo lang_get('storing_copy_8')?></td>
			      <td class="otherCol_420" style="width:360px">
			      	<table border="0" cellspacing="0px" cellpadding="0px">
			      		<tr>
			      			<td width="40px"><img src="../images/btn/btn_root.gif" border="0" onclick="popup_file_browser('idInImagePath');return false;" style="cursor:pointer;"></td>
			      			<td><input name="textfield2" type="text" class="inputtext" id="idInImagePath" size="30" value='/Vol1/system/Backup/IMG' disabled/></td>
			      		</tr>
			      	</table>
			      </td>        
					</tr>
			  </table>
			  <!-- 오른쪽테이블내용 끝-->
			  
			  <!-- Button -->
			  <table width="490px" border="0" cellspacing="0px" cellpadding="0px">
			  	<tr>
			  		<td align="right">       
					      <div id='id_btn_backup' style='visibility:visible;'><!--<a href="javascript:void(0)" onclick="copy_data();"><img  src="../images/btn/btn_copy.gif" width="61" height="22" border="0" /></a>-->
					      		<input type="image" onclick="backup_image();" src="../images/btn/btn_backup.gif"/>
      					</div>
      			</td>
   			 </tr>
  			</table>
  	</td>
	</tr>
</table>
<!-- 테이블 영역 끝-->
</div>







