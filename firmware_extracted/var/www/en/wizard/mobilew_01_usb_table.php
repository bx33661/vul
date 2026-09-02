<!--Table : sync setting list-->
<table id="idTableUsb" style='display:block;'>
	
	<tr>
		<td>
			<!-- 테이블 영역 시작-->
			<table width="400" border="0" cellspacing="0" cellpadding="0" id="title_table_usb">
					<tr>
						<td width="100px" class="header"><?php echo lang_get('usb_sync_1'); ?></td>
						<td width="200px" class="header"><?php echo lang_get('usb_sync_2'); ?></td>
						<td width="100px" class="header"><?php echo lang_get('usb_sync_3'); ?></td>
					</tr>
			</table>
						
						<!-- Setting list-->
						<div id="idListBoxUsb">
								<table width="400" border="0" cellspacing="0" cellpadding="0">
									<tr><td class="firstCol"><?php echo lang_get('common_loading'); ?></td></tr>
								</table>
						</div>
						<!-- Setting list : end-->
			<table border="0" cellspacing="0" cellpadding="0" id="idButtonEdit" style="margin-top:20px">		
							<tr>
								<!--<td><a href="javascript:void(0)" onclick=";"><img src="../images/btn/btn_sync.gif" width="55" height="22" border="0"></a></td>-->
								<!--<td width='450'/>-->
								<td align="right" width="400px"><img src="../images/btn/btn_Setting.gif" border="0" onClick="open_create_table_usb();" style="cursor:pointer;"></td>
								<!--<td width="73" align="right"><a href="javascript:void(0)" onclick=''><img src="../images/btn/btn_delete.gif" width="63" height="22" border="0"></a></td>-->
							</tr>
			</table>
			<!-- 테이블 영역 : end-->
		</td>
	</tr>
</table>
<!--Table : sync setting list : end-->	