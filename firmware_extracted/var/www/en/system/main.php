<? include "../inc/main_top.php";  ?>
<?php
include "../session/session_info.php";
	//=======================================================//
	// Access DB for user information
	//=======================================================//
	$in_id=$_SESSION['username']; 
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from user where uid='$in_id'");
		$sth->execute();
		$users=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		echo "die";
		die();
		
	}
	if(!$users)
	{
		echo " no_user";
		return "NG:NO USER\n";
	}
	
	//print_r($users);
	$_id = $users[0]['uid'];
	$_pw = $users[0]['passwd'];
	//echo $_id;
	//echo $_pw;
	
?>

<script language="javascript">
var _info = navigator.userAgent;
var ie = (_info.indexOf("MSIE") > 0);
var win = (_info.indexOf("Win") > 0);

 var sUserAgent = navigator.userAgent;
var fAppVersion = parseFloat(navigator.appVersion);

if(win)
{
	// Internet Explorer
	var isIE = sUserAgent.indexOf("compatible") > -1 && sUserAgent.indexOf("MSIE") > -1;
	var isFF =(sUserAgent.indexOf("Mozilla") == 0) && (navigator.appName == "Netscape");
	
	if (isIE) {
		document.writeln('<object classid = "clsid:CAFEEFAC-0015-0000-0017-ABCDEFFEDCBA" width="800" height="550"');
		document.writeln('codebase = "http://java.sun.com/update/1.5.0/jinstall-1_5_0_17-windows-i586.cab#Version=5,0,170,4">');
		document.writeln('<param name = "code" value = "Nc003e.class">');
		document.writeln('<param name = "archive" value = "Nc003e.jar">');
		document.writeln('<param name = "type" value = "application/x-java-applet;jpi-version=1.5.0_17">');
		document.writeln('<param name = "scriptable" value = "true">');
		document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" >');
		document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" >');
		document.writeln('</object>');
	}
	else if(isFF){
		document.writeln('<object classid = "clsid:CAFEEFAC-0015-0000-0017-ABCDEFFEDCBA" width="800" height="550"');
		document.writeln('codebase = "http://java.sun.com/update/1.5.0/jinstall-1_5_0_17-windows-i586.cab#Version=5,0,170,4">');
		document.writeln('<param name = "code" value = "Nc003e.class">');
		document.writeln('<param name = "archive" value = "Nc003e.jar">');
		document.writeln('<param name = "type" value = "application/x-java-applet;jpi-version=1.5.0_17">');
		document.writeln('<param name = "scriptable" value = "true">');
		document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" >');
		document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" >');
		
		document.writeln('<object classid="java:Nc003e" archive="Nc003e.jar" type="application/x-java-applet" width="800" height="550" codebase="." code="java:Nc003e.class">');	 
		document.writeln('<param name="codebase" value=".">');
		document.writeln('<param name="archive" value="Nc003e.jar">');
		document.writeln('<param name="code" value="Nc003e.class">');
		document.writeln('<param name="type" value="application/x-java-applet;jpi-version=1.5.0_17">');
		document.writeln('<param name = "scriptable" value = "true"> ');
		document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" > ');
		document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" > ');
		document.writeln('</object>');
		
		document.writeln('</object>');	
	}

}else
{
		document.writeln('<object classid = "clsid:CAFEEFAC-0015-0000-0017-ABCDEFFEDCBA" width="800" height="550"');
		document.writeln('codebase = "http://java.sun.com/update/1.5.0/jinstall-1_5_0_17-windows-i586.cab#Version=5,0,170,4">');
		document.writeln('<param name = "code" value = "Nc003e.class">');
		document.writeln('<param name = "archive" value = "Nc003e.jar">');
		document.writeln('<param name = "type" value = "application/x-java-applet;jpi-version=1.5.0_17">');
		document.writeln('<param name = "scriptable" value = "true">');
		document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" >');
		document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" >');
		
		document.writeln('<object classid="java:Nc003e" archive="Nc003e.jar" type="application/x-java-applet" width="800" height="550" codebase="." code="java:Nc003e.class">');	 
		document.writeln('<param name="codebase" value=".">');
		document.writeln('<param name="archive" value="Nc003e.jar">');
		document.writeln('<param name="code" value="Nc003e.class">');
		document.writeln('<param name="type" value="application/x-java-applet;jpi-version=1.5.0_17">');
		document.writeln('<param name = "scriptable" value = "true"> ');
		document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" > ');
		document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" > ');
		document.writeln('</object>');
		
		document.writeln('</object>');	
}
</script>




<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="30" bgcolor="#d3d3d3" style="padding:0 0 0 39px"><img src="../images/btn/img_footer.gif" width="392" height="19" border="0"></td>
	</tr>
	</table> 
</tr>
</table>
</body>
</html>
