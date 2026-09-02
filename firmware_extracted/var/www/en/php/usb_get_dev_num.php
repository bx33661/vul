<?php
//=======================================================//
// Get control number of USB
//=======================================================//


$cmd = $_POST['act'];

if($cmd == 'check_registered')
{
	$ctrl_num = $_POST['ctrl_num'];
	$usblist=trim(exec("sudo nas-storage get_usb_list one $ctrl_num"));
	$ret = explode('||',$usblist);

	
	exec("sudo echo check : $ret[0]  >> /home/phplog.txt");
	if($ret[0] == 'error')
	{	
		echo "No Device Info";
	}
	else
	{
		echo "Already Registered";
	}
}
else
{
	$usb_num = $_POST['usb_num'];
	exec("sudo echo $usb_num >> /home/phplog.txt");

	$USB_LIST = "/var/run/nas-usb.list";
	$USBDEV_INFO = exec("sudo cat $USB_LIST|grep $usb_num");
	$tmp = explode("->",$USBDEV_INFO);
	$USBDEV_INFO = $tmp[0];
	exec("sudo echo usbdev : $USBDEV_INFO >> /home/phplog.txt");

	$USBDEV=exec("sudo echo $USBDEV_INFO | cut -d ' ' -f 1");
	$USBDEV_PARENT= exec("sudo basename $USBDEV | sed 's/[0-9]*$//'");

	if(!file_exists("/home/tmp.txt"))
	{
		exec("sudo touch /home/tmp.txt");
		exec("sudo chmod -R 777 /home/tmp.txt");
	}

	$ctrlnum = trim(exec("sudo nas-storage get_control_num $USBDEV"));
	$name = trim(exec("sudo nas-storage get_device_name $USBDEV label"));

	echo $ctrlnum.":".$name;
}




//exec("sudo udevinfo --query=all -p /sys/block/$USBDEV_PARENT | grep 'ID_SERIAL=' | cut -d '=' -f 2 > /home/tmp.txt");
//$serial = exec("sudo cat /home/tmp.txt");
//$tmp = explode("-",$serial);
//$serial = $tmp[0];

//$path = exec("sudo grep -m1 '/mnt/disk/' '/etc/fstab' | awk '{print $2}'");
//exec("sudo echo path :$path >> /home/phplog.txt");


?>
