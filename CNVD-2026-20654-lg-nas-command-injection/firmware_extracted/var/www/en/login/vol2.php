<?php
	$DirName = explode( $_POST[root], $_POST[full_dir] );

	echo "<div title=\"".$_POST[last_dir]."\" class=\"panel\">";
	echo "<h2>".$DirName[1]."</h2>";
	echo "<ul>";

	if( ($_POST[sort_mode] == "aod") || ($_POST[sort_mode] == "dod") ){
		$showlist = "sudo ./showlist_date";
	}else
		$showlist = "sudo ./showlist";

	if( strpos($_POST[full_dir], "'") === false ){
		$DirListFile = trim(exec("$showlist '".$_POST[full_dir]."'"));
	}else{
		$UnSafeCharInLinux = array("~","`","!","#","$","&","*","(",")","-",";",".","'"," ");
		$SafeCode = array("\\~","\\`","\\!","\\#","\\$","\\&","\\*","\\(","\\)","\\-","\\;","\\.","\\'","\\ ");
		$SafeFullDir = str_replace( $UnSafeCharInLinux, $SafeCode, $_POST[full_dir] );
		$DirListFile = trim(exec("$showlist $SafeFullDir"));
	}
	$FileListFile = $DirListFile;
	$LineDir = explode(':', $DirListFile);
	$LineFile = explode(':', $FileListFile);

	if( ($_POST[sort_mode] == "aot") || ($_POST[sort_mode] == "aod") ){
		sort($LineDir);
		sort($LineFile);
	}else if( ($_POST[sort_mode] == "dot") || ($_POST[sort_mode] == "dod") ){
		rsort($LineDir);
		rsort($LineFile);
	}

	$DontShowDirectoryList = array(".AppleDouble",".AppleDesktop",".AppleDB","Temporary Items","Network Trash Folder");
	$DontShowFileList = array(".DS_Store");
	for($i = 0 ; $i < sizeof($LineDir) ; $i++){
		$temp = explode('|', $LineDir[$i]);
		if( ($_POST[sort_mode] == "aot") || ($_POST[sort_mode] == "dot") || ($_POST[sort_mode] == "none") ){
			if( $temp[2] == "D" ){
				if( !(in_array($temp[1], $DontShowDirectoryList)) ){
					echo "<form action=\"vol2.php\" method=\"POST\" selected=\"true\"><a onclick=\"doAjax('./session_check.php','','on_check','get','0');\" type=\"submit\" href=\"#\"><img src=\"./iui/folder.png\">".$temp[1]."</a><input type=\"hidden\" name=\"full_dir\" value=\"".$temp[0]."\"><input type=\"hidden\" name=\"link_name\" value=\"".$_POST[link_name]."\"><input type=\"hidden\" name=\"last_dir\" value=\"".$temp[1]."\"><input type=\"hidden\" name=\"root\" value=\"".$_POST[root]."\"><input type=\"hidden\" name=\"sort_mode\" value=\"".$_POST[sort_mode]."\"></form>";
				}
			}
		}else if( ($_POST[sort_mode] == "aod") || ($_POST[sort_mode] == "dod") ){
			if( $temp[3] == "D" ){
				if( !(in_array($temp[2], $DontShowDirectoryList)) ){
					echo "<form action=\"vol2.php\" method=\"POST\" selected=\"true\"><a onclick=\"doAjax('./session_check.php','','on_check','get','0');\" type=\"submit\" href=\"#\"><img src=\"./iui/folder.png\">".$temp[2]."</a><input type=\"hidden\" name=\"full_dir\" value=\"".$temp[1]."\"><input type=\"hidden\" name=\"link_name\" value=\"".$_POST[link_name]."\"><input type=\"hidden\" name=\"last_dir\" value=\"".$temp[2]."\"><input type=\"hidden\" name=\"root\" value=\"".$_POST[root]."\"><input type=\"hidden\" name=\"sort_mode\" value=\"".$_POST[sort_mode]."\"></form>";
				}
			}
		}
	}
	$UnSafeChar = array("%", "#");
	$HexCode = array("%25", "%23");
	for($i = 0 ; $i < sizeof($LineFile) ; $i++){
		$temp = explode('|', $LineFile[$i]);
		if( ($_POST[sort_mode] == "aot") || ($_POST[sort_mode] == "dot") || ($_POST[sort_mode] == "none") ){
			if( $temp[2] == "F" ){
				if( !(in_array($temp[1], $DontShowFileList)) ){
					$href = $_POST[link_name]."/volume2".$DirName[1]."/".$temp[1];
					$href = str_replace( $UnSafeChar, $HexCode, $href );
					echo "<li><a target=\"_webapp\" href=\"".$href."\"><img src=\"./iui/file.png\">".$temp[1]."</a></li>";
				}
			}
		}else if( ($_POST[sort_mode] == "aod") || ($_POST[sort_mode] == "dod") ){
			if( $temp[3] == "F" ){
				if( !(in_array($temp[2], $DontShowFileList)) ){
					$href = $_POST[link_name]."/volume2".$DirName[1]."/".$temp[2];
					$href = str_replace( $UnSafeChar, $HexCode, $href );
					echo "<li><a target=\"_webapp\" href=\"".$href."\"><img src=\"./iui/file.png\">".$temp[2]."</a></li>";
				}
			}
		}
	}
	echo "</ul>";
	echo "</div>";
?>
