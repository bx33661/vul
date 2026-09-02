<?php
include "../inc/lcdmsg.php";

//==================================================//
// LGE
// Park94
// 10/23/2008
//==================================================//
// Display BD message in LCD
//==================================================//
$mode = $_POST['bd_mode'];
//echo $mode;
switch($mode)
{
	// RIPPING
	case 'rip_audio_complete':
		msgjob('once','Complete Audio-CD Extraction');
		break;
	case 'rip_audio_cancel':
		msgjob('once','Cancel Audio-CD Extraction');
		break;
	case 'rip_dvd_complete':
		msgjob('once','Complete DVD-Title Extraction');
		break;
	case 'rip_dvd_cancel':
		msgjob('once','Cancel DVD-Title Extraction');
		break;
	// STORING
	case 'store_data_complete':
		msgjob('once','Complete Disc Copy');
		break;
	case 'store_data_cancel':
		msgjob('once','Cancel Disc Copy');
		break;
	case 'store_image_complete':
		msgjob('once','Complete Image Backup');
		break;
	case 'store_image_cancel':
		msgjob('once','Cancel Image Backup');
		break;
	// BURNING
	case 'burn_data_complete':
		msgjob('once','Complete Data Burn');
		break;
	case 'burn_data_cancel':
		msgjob('once','Cancel Data Burn');
		break;
	case 'burn_image_complete':
		msgjob('once','Complete Image Burn');
		break;
	case 'burn_image_cancel':
		msgjob('once','Cancel Image Burn');
		break;
	default:
		break;
}

?>