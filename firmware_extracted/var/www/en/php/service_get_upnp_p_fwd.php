<?

//create temp file to list dlna list up
$_tmpfile ='/tmp/upnp_devices';
if(!file_exists($_tmpfile)){
	exec("sudo touch '$_tmpfile' ; sudo chmod 777 '$_tmpfile'");
}

$igd = trim(exec("sudo nas-network port_forwarding discovery"));
if($igd == '') 
{
	echo "No IGD*";
	return;
}

echo $igd;
echo "*";

//in file
$port_fwd = trim(exec("sudo cat /etc/nas/upnp.conf | grep enable | cut -d '=' -f 2"));
echo $port_fwd;
echo "*";

//status
$port_fwd_now = trim(exec("sudo nas-network port_forwarding status '$_tmpfile'"));
echo $port_fwd_now;
echo "*";

/*
$fd = fopen($_tmpfile,"r");
exec("sudo echo 'fd : $fd' >> /home/phplog.txt");

$cnt = 0;
while(!feof($fd)) {
	$str = fgets($fd,1024);
	$tmp = trim($str);
	$mid = explode("->",$tmp);	

	$left = explode(" ",trim($mid[0]));
	$protocol[$cnt] = trim($left[1]);	
	$portnum[$cnt] = trim($left[2]);

	$right = explode(" ",trim($mid[1]));
	$ip_address[$cnt] = trim($right[0]);	

	
	//exec("sudo echo $cnt ip:$ip_address[$cnt] protocol:$protocol[$cnt] portnum:$portnum[$cnt] ;
	echo $ip_address[$cnt];
	echo "*";
	echo $protocol[$cnt];
	echo "*";
	echo $portnum[$cnt];
	echo "*";
	$cnt++;	
}
fclose($fd);
*/

/*
$IGD		= trim(exec ('sudo cat $_tmpfile | grep "EMAIL="|cut -d "=" -f 2'));
$ip_address	= trim(exec ('sudo cat $_tmpfile | grep "SMTP_SERVER="|cut -d "=" -f 2'));
$portnum		= trim(exec ('sudo cat $_tmpfile | grep "SMTP_AUTH="|cut -d "=" -f 2'));
$protocol        = trim(exec ('sudo cat $_tmpfile | grep "SMTP_SSL="|cut -d "=" -f 2'));
*/

//echo "$upnp_p_fwd:$upnp_port";
//echo "on:8080";

?>


