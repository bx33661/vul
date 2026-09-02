<!-- Mobile Version -->
<?php
	$auto_login = (trim(exec("sudo cat ./mobile.ini | grep autologinmode | cut -d '=' -f 2")));
?>

<html>
<head>
<title>LG NAS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language='javascript' src='../js/debug.js' charset='utf-8' type='text/javascript'></script>		
<script language='javascript' src='../js/jslb_ajax.js.php' charset='utf-8' type='text/javascript'></script>	
<script language="javascript1.2" src="../js/common.js.php" charset="utf-8" type='text/javascript'></script>
<script src="../common_js/jquery.min.js" type="text/javascript"></script>
<script language="javascript1.2" src="../js/login_mobile.js.php" charset="utf-8" type='text/javascript'></script>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<link rel="apple-touch-icon" href="./iui/lgnas_icon.png" />
<style type="text/css" media="screen">@import "./iui/iui.css";</style>
<script type="application/x-javascript" src="./iui/iuix.js"></script>

<script type="text/javascript">    
	jQuery('#idInId').focus();
</script>
</head>

<body>
	<div class="toolbar">
		<h1 id="pageTitle"></h1>
	</div>
    
	<div id="login" title="LG NAS" class="panel" selected="true">
		<h2>Welcome to LG Network Storage</h2>

		<fieldset>
   			<div class="row">
                <label>User ID</label>
                <input autocorrect="off" autocapitalize="off" onblur="FormCheck('idInId');" name="textfield" type="text" id="idInId" value=
				<?php 
					if( ($auto_login == 'true') && (isset($_COOKIE["username"])) )
						echo "\"".$_COOKIE['username']."\"";
					else
						echo "\"\"";
				?>
				 size="18" maxlength="12">
			</div>	

			<div class="row">
                <label>Password</label>
                <input onfocus="this.value='';" name="textfield2" type="password" id="idInPw" value=
				<?php
					if( ($auto_login == 'true') && (isset($_COOKIE["pw"])) )
						echo "\"".$_COOKIE['pw']."\"";
					else
						echo "\"\"";
				?>
				 size="18" maxlength="20">
			</div>
		
			<div class="row">
                <label>View Mode</label>
                <div id="system" class="toggle" onclick="" toggled="true">
					<span class="thumb"></span>
					<span class="toggleOn">ON</span>
					<span class="toggleOff">OFF</span>
				</div>
            </div>
		</fieldset>
	
		<a class="whiteButton" type="submit" href="##" onClick="check_info1();" style="cursor:pointer;">Login</a>
	</div>		
</body>
</html>
