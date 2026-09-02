<html>
<head>
<title>LG NAS</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<link rel="apple-touch-icon" href="./iui/lgnas_icon.png" />
<style type="text/css" media="screen">@import "./iui/iui.css";</style>
<?php
	if(preg_match('/iPad/i',$_SERVER['HTTP_USER_AGENT'])){
		echo "<style type=\"text/css\" media=\"screen\">.toolbar > h1{left: 40%;width: 300px;}</style>";
	}
	if(preg_match('/Android 1.6/i',$_SERVER['HTTP_USER_AGENT'])){
		echo "<script type=\"application/x-javascript\" src=\"./iui/iui_unusehtml5.js\"></script>";
	}else{
		echo "<script type=\"application/x-javascript\" src=\"./iui/iui.js\"></script>";
	}
?>
</head>

<body>
	<div class="toolbar" id="toolbar">
		<h1 id="pageTitle"></h1>
		<?php
			if( $_SESSION['username'] == "admin" ){
				echo "<a id=\"op\" class=\"button leftButton\" href=\"\#option\">option</a>";
			}
		?>
		<a onclick="doAjax('./session_check.php','','on_check','get','0');" id="backButton" class="button" href="#"></a>
		<a id="logoutButton" class="button" href="#">Logout</a>
	</div>
    
	<div id="root" title="/" class="panel" selected="true">
		<h2>/</h2>
		<ul id="ul_list">
		<?php
			$RWList = $_SESSION['rw_dir'];
			$ROList = $_SESSION['ro_dir'];

			//NC2
			for($i = 0 ; $i < sizeof($RWList) ; $i++){
				$FrontDirName = trim(exec('dirname '.$RWList[$i]));
				$LastDirName = explode( $FrontDirName.'/', $RWList[$i]);
				$volume_value = substr( $FrontDirName, -1 );	//for volume
				
				if( $volume_value == "1" ){
					$nc2_root = "mnt/disk/volume1";

					echo "<form action=\"vol1.php\" method=\"POST\" selected=\"true\"><a onclick=\"doAjax('./session_check.php','','on_check','get','0');\" type=\"submit\" href=\"#\"><img src=\"./iui/folder.png\">".$LastDirName[1]."</a><input type=\"hidden\" name=\"full_dir\" value=\"".$RWList[$i]."\"><input type=\"hidden\" name=\"link_name\" value=\"".$LinkFileName."\"><input type=\"hidden\" name=\"last_dir\" value=\"".$LastDirName[1]."\"><input type=\"hidden\" name=\"root\" value=\"".$nc2_root."\"><input type=\"hidden\" name=\"sort_mode\" value=\"".$sort_mode."\"></form>";
				}
				
				if( $volume_value == "2" ){
					$nc2_root = "mnt/disk/volume2";

					echo "<form action=\"vol2.php\" method=\"POST\" selected=\"true\"><a onclick=\"doAjax('./session_check.php','','on_check','get','0');\" type=\"submit\" href=\"#\"><img src=\"./iui/folder.png\">".$LastDirName[1]."</a><input type=\"hidden\" name=\"full_dir\" value=\"".$RWList[$i]."\"><input type=\"hidden\" name=\"link_name\" value=\"".$LinkFileName."\"><input type=\"hidden\" name=\"last_dir\" value=\"".$LastDirName[1]."\"><input type=\"hidden\" name=\"root\" value=\"".$nc2_root."\"><input type=\"hidden\" name=\"sort_mode\" value=\"".$sort_mode."\"></form>";
				}
			}

			for($i = 0 ; $i < sizeof($ROList) ; $i++){
				$FrontDirName = trim(exec('dirname '.$ROList[$i]));
				$LastDirName = explode( $FrontDirName.'/', $ROList[$i]);
				$volume_value = substr( $FrontDirName, -1 );	//for volume
				
				if( $volume_value == "1" ){
					$nc2_root = "mnt/disk/volume1";

					echo "<form action=\"vol1.php\" method=\"POST\" selected=\"true\"><a onclick=\"doAjax('./session_check.php','','on_check','get','0');\" type=\"submit\" href=\"#\"><img src=\"./iui/folder.png\">".$LastDirName[1]."</a><input type=\"hidden\" name=\"full_dir\" value=\"".$ROList[$i]."\"><input type=\"hidden\" name=\"link_name\" value=\"".$LinkFileName."\"><input type=\"hidden\" name=\"last_dir\" value=\"".$LastDirName[1]."\"><input type=\"hidden\" name=\"root\" value=\"".$nc2_root."\"><input type=\"hidden\" name=\"sort_mode\" value=\"".$sort_mode."\"></form>";
				}
				
				if( $volume_value == "2" ){
					$nc2_root = "mnt/disk/volume2";

					echo "<form action=\"vol2.php\" method=\"POST\" selected=\"true\"><a onclick=\"doAjax('./session_check.php','','on_check','get','0');\" type=\"submit\" href=\"#\"><img src=\"./iui/folder.png\">".$LastDirName[1]."</a><input type=\"hidden\" name=\"full_dir\" value=\"".$ROList[$i]."\"><input type=\"hidden\" name=\"link_name\" value=\"".$LinkFileName."\"><input type=\"hidden\" name=\"last_dir\" value=\"".$LastDirName[1]."\"><input type=\"hidden\" name=\"root\" value=\"".$nc2_root."\"><input type=\"hidden\" name=\"sort_mode\" value=\"".$sort_mode."\"></form>";
				}
			}
		?>	
		</ul>
		<img src="./iui/img_footer.gif" width="100%" />
	</div>

<!-- Admin Option Menu -->
	<form id="option" class="dialog" action="#">
        <fieldset>
            <h1>Setting</h1>
            <a class="button leftButton" type="cancel">Confirm</a>
			<br/>
			<br/>
			<div class="row">
                <label>Auto Login Mode</label>
                <div id="autologin" class="toggle" onclick="" toggled=
				<?php
					if( $auto_login == "true" ){
						echo "\"true\"";}
					else{
						echo "\"false\"";}
				?>
				>
					<span class="thumb"></span>
					<span class="toggleOn">ON</span>
					<span class="toggleOff">OFF</span>
				</div>
            </div>
			<br/>
			<div class="row">
                <label>Session Time</label>
				<select style="font-size:17px" id="session_time">
				<?php
					if( $max_lifetime == 60 )
						echo "<option value=\"1\" selected>1 hour</option>";
					else
						echo "<option value=\"1\">1 hour</option>";

					if( $max_lifetime == 120 )
						echo "<option value=\"2\" selected>2 hour</option>";
					else
						echo "<option value=\"2\">2 hour</option>";

					if( $max_lifetime == 240 )
						echo "<option value=\"4\" selected>4 hour</option>";
					else
						echo "<option value=\"4\">4 hour</option>";

					if( $max_lifetime == 480 )
						echo "<option value=\"8\" selected>8 hour</option>";
					else
						echo "<option value=\"8\">8 hour</option>";

					if( $max_lifetime == 720 )
						echo "<option value=\"12\" selected>12 hour</option>";
					else
						echo "<option value=\"12\">12 hour</option>";

					if( $max_lifetime == 1440 )
						echo "<option value=\"24\" selected>24 hour</option>";
					else
						echo "<option value=\"24\">24 hour</option>";
				?>
				</select>
			</div>
			<br/>
			<div class="row">
				<label>Sort mode</label>
				<select style="font-size:17px" id="sort_mode">
				<?php
					if( $sort_mode == "none" )
						echo "<option value=\"none\" selected>None</option>";
					else
						echo "<option value=\"none\">None</option>";

					if( $sort_mode == "aot" )
						echo "<option value=\"aot\" selected>Ascending(Title)</option>";
					else
						echo "<option value=\"aot\">Ascending(Title)</option>";

					if( $sort_mode == "dot" )
						echo "<option value=\"dot\" selected>Descending(Title)</option>";
					else
						echo "<option value=\"dot\">Descending(Title)</option>";

					if( $sort_mode == "aod" )
						echo "<option value=\"aod\" selected>Ascending(Date)</option>";
					else
						echo "<option value=\"aod\">Ascending(Date)</option>";

					if( $sort_mode == "dod" )
						echo "<option value=\"dod\" selected>Descending(Date)</option>";
					else
						echo "<option value=\"dod\">Descending(Date)</option>";
				?>
				</select>
			</div>
		</fieldset>
	</form>
</body>
</html>
