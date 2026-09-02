<?php
    include ("../session/session_manage.php");
	
    sm_session_check("admin", "../login/login.php");
?>

<html>
<head>
<title>:::::::: Welcome to LG Electronics ::::::::</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/css.css" rel="stylesheet" type="text/css">

<style type="text/css">
.style1 {color: #6E6F71;font-size: 10px;}
</style>

</head>

<body leftmargin="0" topmargin="0" onLoad="">



<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><!-- ??\xFC ?????????-->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><!-- ??\xFC GNB ????????-->
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <!-- Top Area ????-->
                    <td width="965" height="75" align="right">
			<table id="Table_01" width="1100" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td rowspan="2">
						<img id="NC1_new_top0203_01" src="../images/nc1/NC1_new_top0203_01.gif" width="620" height="74" alt="" /></td>
					<td>
					
					
						<?php if ($_SESSION['username'] == "admin"){ ?>
						<a href="../system/system.php"><img id="NC1_new_top0203_02" src="../images/nc1/NC1_new_top0203_02.gif" width="55" height="48" alt="" /></a></td>
						<? } else{ ?>  <!-- User Only -->
						<a href="../system/system.php"><img id="NC1_new_top0203_02" src="../images/nc1/NC1_new_top0203_02.gif" width="55" height="48" alt="" /></a></td>
						<? } ?>
						
					<td>
						<a href="../../Album/index.php"><img id="NC1_new_top0203_03" src="../images/nc1/NC1_new_top0203_03.gif" width="71" height="48" alt="" /></a></td>
					<td>
						<a href="../download/download.php"><img id="NC1_new_top0203_04" src="../images/nc1/NC1_new_top0203_04.gif" width="68" height="48" alt="" /></a></td>
					<td>
						<a href="../system/main.php"><img id="NC1_new_top0203_05" src="../images/nc1/NC1_new_top0203_05.gif" width="73" height="48" border="0" alt="" usemap="#NC1_new_top0203_05_Map" /></a></td>
					<td rowspan="2">
						<img id="NC1_new_top0203_06" src="../images/nc1/NC1_new_top0203_06.gif" width="213" height="74" alt="" /></td>
				</tr>
				<tr>
					<td colspan="4">
						<img id="NC1_new_top_0203_07" src="../images/nc1/NC1_new_top0203_07.gif" width="267" height="26" alt="" /></td>
				</tr>
			</table>                    
		    </td>
                    <td align="right" valign="top">&nbsp;</td>
                    <!-- Top Area ??-->
                  </tr>
                </table>
              <!-- ??\xFC GNB ???? ??--></td>
          </tr>
        </table>
    </td>
  </tr>
</table>

