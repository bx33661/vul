<html>
<head>
<title>LG NAS</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<link rel="apple-touch-icon" href="./iui/lgnas_icon.png" />
<style type="text/css" media="screen">@import "./iui/iui.css";</style>
<script type="application/x-javascript" src="./iui/iui_unusehtml5.js"></script>
</head>
<body>
<div id="temp" style='display:none'>
	<p id="pagehistory"><?php echo $_SESSION['page_history'] ?></p>
	<p id="pagecount"><?php echo $_SESSION['page_count'] ?></p>
	<p id="pageyscoroll"><?php echo $_SESSION['page_yscoroll'] ?></p>
</div>

<script type="text/javascript">
	savedPageHistory = document.getElementById("pagehistory").innerHTML;
	savedPageCount = document.getElementById("pagecount").innerHTML;
	savedPageYScoroll = document.getElementById("pageyscoroll").innerHTML;
	document.body.removeChild(document.getElementById("temp"));
	loadedPage = true;
</script>

<?php echo $_SESSION['page_content']; ?>

<script type="text/javascript">
	var link = $("choosed");
	link.removeAttribute("selected");
	link.removeAttribute("id");
</script>
</body>
</html>
