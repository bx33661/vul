<?php
	exec("cp ./mobile.ini /var/tmp");
	if( $_POST['set'] == "time" )
	{
		exec(". /usr/lib/nas/common.sh && replace_conf equal session.maxlifetime ".$_POST['val']." /var/tmp/mobile.ini");
	}
	else if( $_POST['set'] == "autologin" )
	{
		exec(". /usr/lib/nas/common.sh && replace_conf equal autologinmode ".$_POST['val']." /var/tmp/mobile.ini");
	}
	else if( $_POST['set'] == "sort" )
	{
		exec(". /usr/lib/nas/common.sh && replace_conf equal sortmode ".$_POST['val']." /var/tmp/mobile.ini");
	}
	exec("sudo mv  /var/tmp/mobile.ini ./mobile.ini");
?>
