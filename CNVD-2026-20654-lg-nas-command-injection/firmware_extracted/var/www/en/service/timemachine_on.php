<?	 include "../inc/top.php";  ?>
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
			<table width="670" cellspacing="0" cellpadding="0" style="margin:40px 0 10px 0;">
			<tr>
			<td height="70" valign="center"><H3><b>Time Machine</b></H3></td>
			</tr>
			</table>
                                    
                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
			<td class="header" width="250px"><?php echo lang_get('TimeMachine_1')?></td>
			<td class="header" width="420px">
			<input type="radio" name="rdoTimeMachine" id="rdoTimeMachine_enable" value="on" checked /><label for="rdoTimeMachine_enable"><?php echo lang_get('common_enable')?></label>	
			<input type="radio" name="rdoTimeMachine" id="rdoTimeMachine_disable" value="off"  /><label for="rdoTimeMachine_disable"><?php echo lang_get('common_disable')?></label>
			</td>
                    	</tr>
                    	</table>

                  	<table width="670" border="0" cellspacing="0" cellpadding="0" class="basicTable">
                     	<tr>
                     	
<script language="javascript">
			var _info = navigator.userAgent;
	
			if( _info.indexOf("Macintosh") != -1 )
			{
				document.writeln('<object classid = "clsid:CAFEEFAC-0015-0000-0017-ABCDEFFEDCBA" width="400" height="350"');
				document.writeln('codebase = "http://java.sun.com/update/1.5.0/jinstall-1_5_0_17-windows-i586.cab#Version=5,0,170,4">');
				document.writeln('<param name = "code" value = "TMApplet.class">');
				document.writeln('<param name = "archive" value = "TMApplet.jar">');
				document.writeln('<param name = "type" value = "application/x-java-applet;jpi-version=1.5.0_17">');
				document.writeln('<param name = "scriptable" value = "true">');
				document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" >');
				document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" >');
				
				document.writeln('<object classid="java:TMApplet" archive="TMApplet.jar" type="application/x-java-applet" width="400" height="350" codebase="." code="java:TMApplet.class">');	 
				document.writeln('<param name="codebase" value=".">');
				document.writeln('<param name="archive" value="TMApplet.jar">');
				document.writeln('<param name="code" value="TMApplet.class">');
				document.writeln('<param name="type" value="application/x-java-applet;jpi-version=1.5.0_17">');
				document.writeln('<param name = "scriptable" value = "true"> ');
				document.writeln('<param name = "id" value ="<?php echo $_SESSION['username'];?>" > ');
				document.writeln('<param name = "pw" value ="<?php echo $_pw;?>" > ');
				document.writeln('</object>');
				
				document.writeln('</object>');	
			}
</script>		

                    	</tr>
                    	</table>
                    
		</td>
		</tr>	
  		</table>
  	</td>
  	</tr>
	</table>
</td>
</tr>        

<?php include "../inc/bottom.php";  ?>


 