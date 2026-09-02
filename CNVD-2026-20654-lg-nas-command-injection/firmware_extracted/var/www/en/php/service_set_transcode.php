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

//check whether transcoding is running
exec("sudo pidof ffmpeg",$isBurning);
if($isBurning)
{
	echo "NG:NOW TRANSCODING\n";
	return;
}


include "../inc/lcdmsg.php";

$list		       = $_POST['list'];
$options		= $_POST['options'];
$extension	= $_POST['extension'];

echo transcode_files_save($list, $options, $extension, $vol_name, $fd_stat);
exec("sudo nohup nas-service start_transcoding 1> /tmp/tmp 2>&1 &");
echo "OK:START TRANSCODING";

//=======================================================//
// Transcoding start
//=======================================================//
function transcode_files_save($list, $options, $extension, $vol_name, $fd_stat){

	// Make a list file
	if(empty($list))
	{
		return "ERROR:NO SELECTED FILE OR FOLDER\n";
	}
	
	$root_path = '/mnt/disk';
	$list_file = '/var/www/run/trans_list';
	while(!file_exists($list_file)){
		shell_exec("sudo touch '$list_file'");
	}
	shell_exec("sudo chmod 666 '$list_file'");

	// Save File List 
	if(!$handle = fopen($list_file,'w+')) 
		return "ERROR:FAIL TO MAKE TRANS LIST\n";
	$lists = explode(":",$list);
	$cnt = count($lists);
	for($i=0;$i<$cnt;$i++)
	{
		$lists[$i] = urldecode($lists[$i]); //It's only for NC1

		$root_smb = explode("/",$lists[$i]);
		$current_path = get_currDirectory($root_smb[1]);

		if($root_smb[2] == '')
			$fullpath = $current_path;
		else
		{
			//$current_path = str_replace($root_smb[1], "", $current_path);			
			$name = $lists[$i];

			$position = strpos( $name , "/" );
			$post_path = substr($name, $position+1);
			
			$position = strpos( $post_path , "/" );
			$post_path = substr($post_path, $position);	

			exec("sudo echo juny2 : $post_path , $current_path >> /home/tmp.txt");

			$fullpath = $current_path.$post_path;			
		}
		
		exec("sudo echo last : $fullpath , $current_path >> /home/tmp.txt");
		if(is_dir($fullpath))
		{
			$tmp = explode("/",$lists[$i]);
			//var_dump($tmp);
			if($lists[$i][0]=="/"){
				$lists[$i] = substr($lists[$i],1);
			}
			//$tmp = "/".$tmp[count($tmp)-1]."/"."=".$root_path."/".$lists[$i]."\n";
			$tmp = "/".$tmp[count($tmp)-1]."/"."=".$fullpath."\n";		
			
		}
		else
		{
			$tmp = $fullpath."\n";	
		}
				
		fwrite($handle,$tmp);
	}
	fclose($handle);

	$option_file = '/var/www/run/trans_options';
	while(!file_exists($option_file)){
		shell_exec("sudo touch '$option_file'");
	}
	shell_exec("sudo chmod 666 '$option_file'");	
	if(!$handle = fopen($option_file,'w+')) 
		return "ERROR:FAIL TO MAKE TRANS LIST\n";
	// Save Options
	$opt = 'option:'.$options."\n";	
	$ext = 'extension:'.$extension."\n";

	fwrite($handle,$opt);
	fwrite($handle,$ext);

	// Make a iso file
	$flag = 'burn:';
	//write_file($fd_stat, $flag);
	return "OK:COMPLETE\n";
	
}


function write_file($fd_stat, $flag)
{
	if(!fwrite($fd_stat, $flag))
	{
		echo "ERROR:FAIL TO WRITE STAT FILE\n";
		end_task($fd_stat);
		exit;
	}
}


//=======================================================//
// Return current directory
//=======================================================//
function get_currDirectory($folder) {

		exec("sudo echo folder : $folder >> /home/tmp.txt");	

	//if ( $_SESSION['current_dir'] == "/" ) 
	//{
		//$current_path = $prefix_path.$post_path;
		//exec("sudo echo post_path : $post_path >> /home/tmp.txt");

		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select path from folder_info where folder='$folder'");
			$sth->execute();
			$DB_folder_info1=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
	
		$prefix_path=$DB_folder_info1[0][0];
		$_post_path=$_SESSION['current_dir'];

		exec("sudo echo prefix_dir : $prefix_path >> /home/tmp.txt");	
		
		$position = strpos( $_post_path , "/" );
		$post_path = substr($_post_path, $position+1);
		$position = strpos( $post_path , "/" );
		$post_path = substr($post_path, $position);	
		
		$current_path = $prefix_path;
		
	//}
	return $current_path;	
}


?>
