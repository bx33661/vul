<?php 
$upnp_p_fwd	= $_POST['rdoUPnP_P_FWD'];
//if($upnp_p_fwd != $cur_status)
//{
	if($upnp_p_fwd == 'on')
	{
		exec('sudo nas-network port_forwarding on');
		echo "ok:upnp_on:";
	}
	else if($upnp_p_fwd == 'off')
	{
		exec('sudo nas-network port_forwarding off');
		echo "ok:upnp_off:";
	}	
//}
//else
//{
//	echo "ok:no_chg";
//}


?>







