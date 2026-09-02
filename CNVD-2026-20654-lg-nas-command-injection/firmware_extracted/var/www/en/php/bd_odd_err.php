<?php
//=======================================================//
// ODD error definition
//=======================================================//
$_err_mosil = array(
			128 => 'SCSI Open Error',
			129 => 'Buffer Open Error',
			130 => 'Check Drive',
			131 => 'Not Blank Disc',
			132 => 'Not Enough Space',
			133 => 'Invalid Cue',
			134 => 'File Open Error',
			135 => 'Write Error',
			136 => 'File Access Error',
			137 => 'Format Error'
			);
$_err_mopil = array(
			128 => 'SCSI Open Error',
			129 => 'Buffer Open Error',
			130 => 'Check Drive',
			131 => 'Blank Disc',
			132 => 'Protected Disc',
			133 => 'Open Session Disc',
			134 => 'File Open Error',
			135 => 'Read Error',
			136 => 'Unknown Profile',
			137 => 'File Write Error'
			);
// Data Disc Buring
$_err_motil = array(
			'-90': 'Disc Format Error',
			'-91': 'Disc Write Error' ,
			'-92': 'Disc Full'        ,
			'-93': 'Scsi Open Error'  ,
			'-94': 'Disc Read Error'
			);
$_err = array( 'mosilt' => $_err_mosil , 'mopilt' => $_err_mopil );
$_err_file = '/etc/sss_script/burn/odd_sts';

// Input
// $app : [mopilt/mosilt]
function odd_chk_err($app){
	global $_err_file, $_err;
	$_ret = array();
	$_arr = file($_err_file);
	// $_err_file contents
	// sts: [start/complete/cancel/error]
	// app: ok[000]/ng[xxx]
	if( preg_match_all('/\b\w+\b/',$_arr[0],$matches) ){
		$_ret[] = trim($matches[0][1]);
	}else{
		return false;
	}
	
	switch($_ret[0]){
		case 'error':
			if(preg_match_all('/\d+/',$_arr[1],$matches)){
				$_ret[] = $_err[$app][intval($matches[0][0])];
			}else{
				return false;
			}
		break;
		default:
		break;
	}
	return $_ret;
}

// Test
//exec('sudo oddacsrt -u web -a odd -p mopilt -i');
//echo json_encode(odd_chk_err('mopilt'));
?> 