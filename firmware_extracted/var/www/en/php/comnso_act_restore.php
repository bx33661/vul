<?PHP
	header("Expires: -1");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	// 메세지를 주고받는 파일을 제거한다.
	$cms_msgfile="/etc/cms/~resdisccheck.msg";
 	if(file_exists($cms_msgfile))
 	{
 		@unlink($cms_msgfile);
 	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
	"http://www.3scriptz.com">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Restore</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="../js/jquery-1.2.6.pack.js"></script>
	<script type="text/javascript" src="../js/comnso_restore.js"></script>
	
	<style type="text/css">
		body { margin: 5px 5px 5px 5px; }
		h1 {font-family: Tahoma; font-size: 70%; font-weight: bold}
		p  {font-family: Tahoma; font-size: 100%;}
		a{text-decoration: none;font-family: Tahoma; font-size: 9pt;}
		td{text-decoration: none;font-family: Tahoma; font-size: 10pt;}
		a:link {color: #000}
		a:visited {color: #000}
		a:hover {font-size: 9pt; color: #005555}
		a:active {color: #000}
		.hidden {display: none;}
		.show{color: #cccccc;}
	</style>
	</head>
<?
 	$chkrefresh = 1;
 	if($chkrefresh){
 		echo '<body onload="restore();" onunload="restore_cancel()">';
 	}else{
 		echo '<body>';
 		echo 'no page';
 		echo '</body></html>';
 		return;
 	}	
?>	
<table  border="0" height="160px" width="290px" cellspacing="0" cellpadding="0" style="table-layout:fixed">
<tr height='50px'>
	<td width='50px' align='center'><div id='msgimg'><img src='../images/comnso/cms_icon_info.gif'></div></td>
	<td width='240px'><div id='phpmsg'><span id='lng_reading'>Reading restore files...</span></div></td>
</tr>
<tr height='35px'>
	<td colspan='2'><h><span id='lng_restore_progress'>Restore progress</span></h></td>
</tr>
<tr height='20px'>
	<td align='right'><div id='prsstxt' style="overflow:none; width:50px;">0%&nbsp;&nbsp;</div></td>
	<td bgcolor='#CCCCCC' ><div id='prssimg' style="overflow:none; width:0px; height:20px; padding:0px; background-color:#AACC11;"></div></td>
</tr>
<tr height='55px'>
<td colspan='2' align='center' valign='bottom'><input iid='lng_cancel' type='button' onclick='restore_cancel()' value='Cancel'></td>
</tr>
</table>
</body>
</html>