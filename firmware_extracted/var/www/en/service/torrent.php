<?	 include "../inc/top.php";  ?>
<!---------------------------------
// LGE NAS-SSS 
// By park94
// Language select
----------------------------------->
<script language='javascript' src='../js/debug.js' charset='utf-8'></script>		<!--debugging setting-->
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8'></script>	<!--ajax lib-->
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
          <script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>

          

          <tr>
          <!-- ??ucenter ???? ????--> <td valign="top">
          	<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
  	  	

   	<div id="idTable_Torrent" style='display:block'>
  	 				 		<!-- 1. Page Title : Network -->	 				 
			 				 	<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
				   					<tr><td height="50" valign="top"><img src="../images/headtitle/htit_torrent.gif" /></td></tr>
						  	</table>

						    
						     <!-- 2. Contents -->
						      <table width="670" border="0" cellspacing="0" cellpadding="0">
						        <tr>
						        	<td class="header" width="250px"><?php echo lang_get('torrent_1')?></td>
						        	<td class="header" width="420px"><input type="radio" name="rdoTorrent" id="rdoTorrent_enable" value="on" /><label for="rdoTorrent_enable"><?php echo lang_get('common_enable')?></label>	
						        		                               <input type="radio" name="rdoTorrent" id="rdoTorrent_disable" value="off" /><label for="rdoTorrent_disable"><?php echo lang_get('common_disable')?></label>
						        	</td>
						        </tr>
						       
						        <tr>
						          <td colspan="2" align="right" style="padding:20 0 0 0px"><img src="../images/btn/btn_apply.gif" border="0" onclick="Set_Torrent_Info();" class="buttons"/></td>
						         </tr>
						       </table>
     </div>         
		 <!-- Message Table : Start -->	
			<div id="id_popup_torrent" style="width:670px; margin-top:40px; display:none" >
					<table width="420" height="260" align="center" cellspacing="0" cellpadding="0">
							<tr>
									<td height="54px" background="../images/popup/txt_popup_bg_01.gif" style="padding-left:20px">
										<span class="popup_text" id="id_popup_title"><?php echo lang_get('torrent_1')?></span></td>
							</tr>
							<tr>
									<td align="center" valign="top" style="padding:0 0 0 0px">
										<div id="system_message">&nbsp;</div>
									</td>
							</tr>
				
					</table> 		
			</div>

      

  </td></tr>        
  
  
</table>


</td>
</tr>
</table>
</td>
</tr>
          <?	 include "../inc/bottom.php";  ?>

<!---------------------------------
// LGE NAS-SSS 
// By park94
----------------------------------->
<script language="javascript1.2" src="../js/torrent.js.php?lang=<?=$t_lang_from_url[1]?>" charset="utf-8"></script>
<script language='javascript' charset='utf-8'>
init();
function init()
{
	Get_Torrent_Info();		// get timezone list from server
}
</script>


