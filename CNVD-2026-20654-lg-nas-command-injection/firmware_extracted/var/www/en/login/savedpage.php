<html>
<head>
<title>LG NAS</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<link rel="apple-touch-icon" href="./iui/lgnas_icon.png" />
<style type="text/css" media="screen">@import "./iui/iui.css";</style>
<?php
	if(preg_match('/iPad/i',$_SERVER['HTTP_USER_AGENT']))
		echo "<style type=\"text/css\" media=\"screen\">.toolbar > h1{left: 40%;width: 300px;}</style>";
?>
<script type="application/x-javascript" src="./iui/iui.js"></script>
<script type="text/javascript">
	savedPage = sessionStorage.getItem( 'page_Content' );
	sessionStorage.removeItem( 'page_Content' );
	if( savedPage != null ){
		savedPageHistory = sessionStorage.getItem( 'page_History' );
		savedPageCount = sessionStorage.getItem( 'page_Count' );
		savedPageYScoroll = sessionStorage.getItem( 'page_YScoroll' );
		loadedPage = true;

		var saved_body = document.createElement("body");
		saved_body.innerHTML = savedPage;
		saved_body.style.display = "none";
		document.documentElement.appendChild( saved_body );
		savedPage = null;
		saved_body = null;
	
		var link = $("choosed");
		link.removeAttribute("selected");
		link.removeAttribute("id");
	}
</script>
</head>
</html>
