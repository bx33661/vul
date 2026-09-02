<?
//=======================================//
// ODD STATUS DETECTION
//=======================================//
// ODD status check
//	:	Busy//Idle
//=======================================//
//Juny

echo "OK:\n";
/*
$ret = shell_exec('sudo /usr/local/bin/oddmngst -m chk');
if(eregi('success to check resources',$ret))
{
	if(eregi('odd status : busy',$ret)){
		//echo "NG:BD IS BUSY\n";
		if(eregi("application id : [a-z_]+",$ret,$regs)){
			//echo $regs[0]."\n";
			$tmp = explode(" : ",$regs[0]);
			$tmp = trim($tmp[1]);
			if($tmp == "mosilt" || $tmp == "mopilt" || $tmp == "daemon" || $tmp == "daemonp" || $tmp == "store" || $tmp == "burn"){
				echo "NG:Drive is working\n";
			}else{
				echo "OK:\n";
			}
			
			//echo ":".$tmp.":";
		}else{
			echo "ERROR:no application id\n";
		}
		
	}else{
		echo "OK:\n";
	}
	
	
}else
{
	echo "ERROR:BD CHECK FAIL\n";
	
}
*/
?> 