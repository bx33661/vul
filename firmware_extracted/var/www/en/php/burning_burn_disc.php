<?
include "../inc/lcdmsg.php";
//=======================================================//
// 1.make a list
// 2.make an iso image
// 3.burn disc with the image
//=======================================================//

//****************** Progress ***************************//
$prog_file = "/etc/sss_script/burn/burn_disc_prog";
if(!file_exists($prog_file))
{
	$cmd = "sudo touch $prog_file";
	shell_exec($cmd);
	$cmd = "sudo chmod 666 $prog_file";
	shell_exec($cmd);
}else if(is_writable($prog_file))
{
	$cmd = "sudo chmod 666 $prog_file";
	shell_exec($cmd);
}
if(!$fd_prog = fopen($prog_file,"w") )
{
	echo "Error : cannot open file ($prog_file)\n";
	exit();
}
$flag = "start:";
fwrite($fd_prog, $flag);

//****************** Progress ***************************//
$flag = "list:";
fwrite($fd_prog, $flag);


// (0) Receive file list from web //
$list=$_POST["list"];
if(empty($list))
{
	echo "Error : $list is either 0, empty, or not set at all\n";
	exit();
}
$file_list=explode(":",$list);


// (1) Make list file for burning //
$root_path="/mnt/fs";
$root_path_list="/mnt/fs/Vol1/system/Share";
$tmp_file=".burn_file.lst";
$list_path=$root_path_list."/".$tmp_file;
$h_file=fopen($list_path,"w+");
$cnt=count($file_list);
for($i=0;$i<$cnt;$i++)
{
	fwrite($h_file,$root_path.$file_list[$i]."\n");
}
fclose($h_file);


$flag = "image:";
fwrite($fd_prog, $flag);


// (2) Make iso image file //
$vol_name=$_POST["vol_name"];
if(shell_exec("mount|grep /dev/sr0"))
{
	$ret=shell_exec("sudo umount /dev/sr0");
}
msgjob('add','Burning Disc...');
$img_path=$root_path."/Vol1/.burn.raw";
$cmd="sudo oddacsrt -u web -a burn -p /usr/local/bin/genisoimage -R -o $img_path -path-list $list_path -V $vol_name";
$ret=shell_exec($cmd);
msgjob('remove','Burning Disc...');
$pattern = "ODD Task Canceled";
if(ereg($pattern,$ret))
{
	$out = "Burning was canceled in genisoimage";
	echo $out;
	close_burn($fd_prog);
	msgjob('once','Cancel Disc Burn');
	exit();
}


$flag = "burn:";
fwrite($fd_prog, $flag);


// (3) Burn the image to disc //
$cmd="sudo oddacsrt -u web -a burn -p /usr/local/bin/mosilt $img_path /dev/sg4";
$ret=shell_exec($cmd);
if(ereg($pattern,$ret))
{
	$out = "Burning was canceled in mosilt";
	echo $out;
	close_burn($fd_prog);
	msgjob('once','Cancel Disc Burn');
	exit();
}
$out = "Disc burning was completed";
echo $out;
close_burn($fd_prog);
msgjob('once','Complete Disc Burn');

// (4) Remove all temp files
function close_burn($fd_prog)
{
	$tmp = array($list_path,$img_path);
	$cmd = "sudo rm -rf '$tmp[0]' '$tmp[1]'";
	shell_exec($cmd);

	$flag = "end:";
	fwrite($fd_prog, $flag);
	fclose($fd_prog);
	open_tray();
}

function open_tray()
{
	$cmd="sudo oddmngst -m tray";
	shell_exec($cmd);
}
?>