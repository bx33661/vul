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


<STYLE>
A {behavior:url(#default#AnchorClick);}
</STYLE>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td height="30" style="padding:0 0 0 39px"><A HREF = "http://10.177.194.57:80/dav" FOLDER = "http://10.177.194.57:80/dav" target="_top" >To connect to the server, click here.</A></td>
  	</tr>
	</table> 
</tr>
</table>

<BR>

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
