<?php

//=======================================================//
// Session Check
//=======================================================//
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
	//require_once "../php/msg_illegal_access.php";
	echo '-99';
	die();
}

//check whether disc is burning
exec("sudo pidof odd_burning odd_backup",$isBurning);
if($isBurning)
{
	echo "NG:DISC BURNING\n";
	return;
}

shell_exec('sudo /usr/sbin/odd_media');


$disc_type = shell_exec('sudo cat /tmp/mediatype');
$disc_status = shell_exec('sudo cat /tmp/mediastatus');

if (eregi('empty',$disc_status)) 
{
	if(eregi('BD-RE',$disc_type)){
		$capacity_str=shell_exec('sudo cat /tmp/minfo | grep -A3 Capacity | grep -E \'12288|24576\' | awk \'{print $1}\'');
	        $disc_cap = floatval($capacity_str)*2048 ;
	}
	else if(eregi('BD-R',$disc_type)){
		$capacity_str=shell_exec('sudo cat /tmp/minfo | grep -A2 Capacity | grep -E \'151552|413696\' | awk \'{print $1}\'');
	        $disc_cap = floatval($capacity_str)*2048 ;
	}
	else {
		$capacity_str=shell_exec('sudo cat /tmp/minfo | grep -A2 \'Size\' | grep \'Blank\' | awk \'{print $6}\'');
		$disc_cap = floatval($capacity_str)*2048;
	}

	if ( floatval($disc_cap) > floatval(35000000000) ) 		// 35GB BD DL
		$disc_cap = floatval($disc_cap) - floatval(440000000);
	else if ( floatval($disc_cap) > floatval(15000000000) )	//15GB BD SL
		$disc_cap = floatval($disc_cap) - floatval(220000000);
	else if ( floatval($disc_cap) > floatval(6000000000) )	//6GB DVD DL
		$disc_cap = floatval($disc_cap) - floatval(40000000);
	else if ( floatval($disc_cap) > floatval(3000000000) )	//3GB DVD SL
		$disc_cap = floatval($disc_cap) - floatval(20000000);
	else 					//CD
		$disc_cap = floatval($disc_cap) - floatval(5000000);

	echo "OK:BLANK DISC\n";
	echo "FREE SPACE:".$disc_cap." Bytes\n";
	//exit;	
}
else if (eregi('complete',$disc_status) || eregi('illegal',$disc_status))
{
	if(eregi('CD-RW',$disc_type)){
        	$capacity_str=shell_exec('sudo cat /tmp/minfo |  grep \'ATIP start of lead out\' | cut -d: -f2 | awk \'{print $1}\'');
	        $disc_cap = floatval($capacity_str)*2048;
		$disc_cap = floatval($disc_cap) - floatval(5000000);
		echo "OK:REWRITABLE DISC CONTAINING DATA\n";
	        echo "FREE SPACE:".$disc_cap." Bytes\n";
	}
	else if(eregi('RW',$disc_type) || eregi('RAM',$disc_type)|| eregi('RE',$disc_type)) {
		if(eregi('DVD-RW',$disc_type))
                        $capacity_str=shell_exec('sudo cat /tmp/minfo | grep -A2 Capacity | grep Reserved | awk \'{print $1}\'');
                else
                        $capacity_str=shell_exec('sudo cat /tmp/minfo | grep -A1 Capacity | grep -v Capacity | awk \'{print $1}\'');

	        $disc_cap = floatval($capacity_str)*2048;
		if ( floatval($disc_cap) > floatval(35000000000) ) 		// 35GB BD DL
			$disc_cap = floatval($disc_cap) - floatval(440000000);
		else if ( floatval($disc_cap) > floatval(15000000000) )	//15GB BD SL
			$disc_cap = floatval($disc_cap) - floatval(220000000);
		else if ( floatval($disc_cap) > floatval(6000000000) )	//6GB DVD DL
			$disc_cap = floatval($disc_cap) - floatval(40000000);
		else // ( floatval($disc_cap) > floatval(3000000000) )	//3GB DVD SL
			$disc_cap = floatval($disc_cap) - floatval(20000000);
		echo "OK:REWRITABLE DISC CONTAINING DATA\n";
	        echo "FREE SPACE:".$disc_cap." Bytes\n";
	}
	else
		echo "NG:NOT A WRITABLE DISC\n";
	//exit;	
}
else if (eregi('No disk',$disc_status))
{
	echo "WARNING:NO DISC\n";
	//exit;	
}
else {
	echo "ERROR:DISC CHECK FAIL\n";
	//echo "ERROR:".$disc_status."--\n";
	//exit;
}

//shell_exec('sudo rm -rf /tmp/minfo');

?>
