<div id="web_menu_container" style="width:245px; height:1000px;border-right:2px solid #f5f5f7">

<?php
	$NAS_MODEL="nc1";
	
	$user_type=( $_SESSION['username'] == "admin" )? "admin" : "user";
	
	$MENU_PAGE=$NAS_MODEL."_".$user_type."_menu.php";
	
	include("../web_menu/$MENU_PAGE");
?>
</div>