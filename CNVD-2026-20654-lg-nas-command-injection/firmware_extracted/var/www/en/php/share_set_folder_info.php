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
include 'nas_comm.php';

	$folder_name	= $_POST["txtName"];
	$folder_desc	= $_POST["txtComment"];


	$folder_volume	= $_POST["txtVolume"];
	$folder_protocol_win = ($_POST["chkOSWin"]=="win")?"YES":"NO";
	$folder_protocol_mac = ($_POST["chkOSMac"]=="mac")?"YES":"NO";
	$folder_protocol_ftp = ($_POST["chkOSFTP"]=="ftp")?"YES":"NO";
	$folder_protocol_webdav = ($_POST["chkOSWebdav"]=="webdav")?"YES":"NO";

	
	$folder_attrib	= ($_POST["rdoAttrib"]=="on")?"NORMAL":"HIDDEN";
	$folder_recycle = ($_POST["rdoTrash"]=="on")?"YES":"NO";
	$folder_access =  ($_POST["rdoAccess"]=="on")?"YES":"NO";

	$num_user =  $_POST["txtNum_user"];
	$num_group =  $_POST["txtNum_group"];
	

	$folder_acl_RW_users = $_POST["chkUserRWPermission"];
	//$folder_acl_RW_users = urldecode($folder_acl_RW_users);


	$folder_acl_RO_users = $_POST["chkUserROPermission"];
	//$folder_acl_RO_users = urldecode($folder_acl_RO_users);


	$folder_acl_RW_user = explode(";",$folder_acl_RW_users);
	$num_acl_RW_user = sizeof($folder_acl_RW_user);

	$folder_acl_RO_user = explode(";",$folder_acl_RO_users);
	$num_acl_RO_user = sizeof($folder_acl_RO_user);


	$folder_acl_RW_groups = $_POST["chkGroupRWPermission"];
	$folder_acl_RO_groups = $_POST["chkGroupROPermission"];


	$folder_acl_RW_group = explode(";",$folder_acl_RW_groups);
	$num_acl_RW_group = sizeof($folder_acl_RW_group);

	$folder_acl_RO_group = explode(";",$folder_acl_RO_groups);
	$num_acl_RO_group = sizeof($folder_acl_RO_group);


	$num_Domainuser =  $_POST["txtNum_Domainuser"];
	$num_Domaingroup =  $_POST["txtNum_Domaingroup"];
	

	$folder_acl_RW_Domainusers = $_POST["chkDomainUserRWPermission"];
	$folder_acl_RW_Domainusers = urldecode($folder_acl_RW_Domainusers);

	$folder_acl_RO_Domainusers = $_POST["chkDomainUserROPermission"];
	$folder_acl_RO_Domainusers = urldecode($folder_acl_RO_Domainusers);

	$folder_acl_RW_Domainuser = explode(";",$folder_acl_RW_Domainusers);
	$num_acl_RW_Domainuser = sizeof($folder_acl_RW_Domainuser);

	$folder_acl_RO_Domainuser = explode(";",$folder_acl_RO_Domainusers);
	$num_acl_RO_Domainuser = sizeof($folder_acl_RO_Domainuser);


	$folder_acl_RW_Domaingroups = $_POST["chkDomainGroupRWPermission"];
	$folder_acl_RW_Domaingroups = urldecode($folder_acl_RW_Domaingroups);
	
	$folder_acl_RO_Domaingroups = $_POST["chkDomainGroupROPermission"];
	$folder_acl_RO_Domaingroups = urldecode($folder_acl_RO_Domaingroups);

	$folder_acl_RW_Domaingroup = explode(";",$folder_acl_RW_Domaingroups);
	$num_acl_RW_Domaingroup = sizeof($folder_acl_RW_Domaingroup);

	$folder_acl_RO_Domaingroup = explode(";",$folder_acl_RO_Domaingroups);
	$num_acl_RO_Domaingroup = sizeof($folder_acl_RO_Domaingroup);



	$SetupMode = $_POST["txtSetupMode"];
	

	$folder_path="/mnt/disk/".$folder_volume."/".$folder_name;

// Input debugging code
/*
echo $folder_name."<br>";
echo $folder_desc."<br>";
echo $folder_volume."<br>";
echo $folder_protocol_win."<br>";
echo $folder_protocol_mac."<br>";
echo $folder_protocol_ftp."<br>";
echo $folder_attrib."<br>";
echo $folder_recycle."<br>";
echo $folder_access."<br>";
echo $SetupMode."<br>";

echo $folder_path;

echo "</br>RW User:</br>";
for($i=0 ; $i < $num_acl_RW_user ; $i++){
echo $folder_acl_RW_user[$i];
}
echo "</br>RO User:</br>";
for($i=0 ; $i < $num_acl_RO_user ; $i++){
echo $folder_acl_RO_user[$i];
}
echo "</br>RW Group:</br>";
for($i=0 ; $i < $num_group ; $i++){
echo $folder_acl_RW_group[$i];
}
echo "</br>RO Group:</br>";
for($i=0 ; $i < $num_group ; $i++){
echo $folder_acl_RO_group[$i];
}
*/

//echo $folder_acl_RW_Domainuser[0];



// End of input debugging code 

	try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select * from folder_info");
	$sth->execute();
	$folders=$sth->fetchAll();
	$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$num_folder=sizeof($folders);





if($SetupMode == "add"){
	//folder add
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from folder_info");
		$sth->execute();
		$DB_folder_info=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$num_DB_folder=sizeof($DB_folder_info);

	//echo $DB_user_info[0][0];
	$ID_conflict = 'FALSE';

	for($i=0;$i<$num_DB_folder;$i++){
		if(strtolower($folder_name) == strtolower($DB_folder_info[$i][0])) $ID_conflict = 'TRUE';
	}

	//echo $DB_group_info[0][0];
	if(($ID_conflict == 'TRUE') 
		|| (strtolower($folder_name) == 'system' )
		|| (strtolower($folder_name) == 'disk1' )
		|| (strtolower($folder_name) == 'disk2' )
		|| (strtolower($folder_name) == 'raid' )
		|| (strtolower($folder_name) == 'linear' )) { 
		echo "ok:ID_conflict";
	} else { 


	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into folder_info values('$folder_name','$folder_desc','$folder_path','$folder_attrib','$folder_recycle','$folder_protocol_win','$folder_protocol_mac','$folder_protocol_ftp','$folder_protocol_webdav','$folder_access')");
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}
	
	//add users to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_user ; $i++){
		if($folder_acl_RW_user[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_user[$i]','','user')");
		}
		if($folder_acl_RO_user[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_user[$i]','','','user')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	//add groups to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_group ; $i++){
		if($folder_acl_RW_group[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_group[$i]','','group')");
		}
		if($folder_acl_RO_group[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_group[$i]','','','group')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}
	

	//add Domainusers to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_Domainuser ; $i++){
		if($folder_acl_RW_Domainuser[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_Domainuser[$i]','','Domainuser')");
		}
		if($folder_acl_RO_Domainuser[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_Domainuser[$i]','','','Domainuser')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	//add Domaingroups to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_Domaingroup ; $i++){
		if($folder_acl_RW_Domaingroup[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_Domaingroup[$i]','','Domaingroup')");
		}
		if($folder_acl_RO_Domaingroup[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_Domaingroup[$i]','','','Domaingroup')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}
	
	// NC1
	exec("sudo nas-share add_folder $folder_path");
	sleep(2);

	// NS1
	/*
	exec("sudo mkdir $folder_path");
	exec("sudo chmod 777 $folder_path");

	//Rewrite Default Samba entry
	$LineStart=exec("sudo cat /etc/samba/smb.conf | grep -n BEGINOF_DEFAULT### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/samba/smb.conf | grep -n ENDOF_DEFAULT### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/samba/smb.conf /tmp/smb.conf");
		exec("sudo chmod 777 /tmp/smb.conf");
		delLineFromFile("/tmp/smb.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/smb.conf /etc/samba/smb.conf");
		exec('sudo /etc/sss_script/share/gen_default_share.sh >> /etc/samba/smb.conf');
	}
	
	exec("sudo /etc/sss_script/share/query_share.sh add $folder_name");
	exec("sudo /usr/bin/smbcontrol smbd reload-config");
	*/

	echo "ok:folder";
	}


}else if($SetupMode == "edit"){
	//folder edit
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from folder_info where folder='$folder_name'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from folder_member where folder='$folder_name'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("insert into folder_info values('$folder_name','$folder_desc','$folder_path','$folder_attrib','$folder_recycle','$folder_protocol_win','$folder_protocol_mac','$folder_protocol_ftp','$folder_protocol_webdav','$folder_access')");
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	//add users to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_user ; $i++){
		if($folder_acl_RW_user[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_user[$i]','','user')");
		}
		if($folder_acl_RO_user[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_user[$i]','','','user')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	//add groups to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_group ; $i++){
		if($folder_acl_RW_group[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_group[$i]','','group')");
		}
		if($folder_acl_RO_group[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_group[$i]','','','group')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	//add Domainusers to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_Domainuser ; $i++){
		if($folder_acl_RW_Domainuser[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_Domainuser[$i]','','Domainuser')");
		}
		if($folder_acl_RO_Domainuser[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_Domainuser[$i]','','','Domainuser')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}

	//add Domaingroups to folder
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		for($i=0 ; $i < $num_Domaingroup ; $i++){
		if($folder_acl_RW_Domaingroup[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','','$folder_acl_RW_Domaingroup[$i]','','Domaingroup')");
		}
		if($folder_acl_RO_Domaingroup[$i]!=''){
			$dbh->exec("insert into folder_member values('$folder_name','$folder_acl_RO_Domaingroup[$i]','','','Domaingroup')");
		}
		}
	}
	catch(PDOException $e) {
		print "DB insert err";
		die();
	}
	
	// NC1
	exec("sudo nas-share mod_folder $folder_path");
	sleep(2);

	// NS1
	/*
	//Rewrite Default Samba entry
	$LineStart=exec("sudo cat /etc/samba/smb.conf | grep -n BEGINOF_DEFAULT### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/samba/smb.conf | grep -n ENDOF_DEFAULT### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/samba/smb.conf /tmp/smb.conf");
		exec("sudo chmod 777 /tmp/smb.conf");
		delLineFromFile("/tmp/smb.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/smb.conf /etc/samba/smb.conf");
		exec('sudo /etc/sss_script/share/gen_default_share.sh >> /etc/samba/smb.conf');
	}

	
	//Delete Samba entry
	$LineStart=exec("sudo cat /etc/samba/smb.conf | grep -n BEGINOF_$folder_name### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/samba/smb.conf | grep -n ENDOF_$folder_name### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/samba/smb.conf /tmp/smb.conf");
		exec("sudo chmod 777 /tmp/smb.conf");
		delLineFromFile("/tmp/smb.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/smb.conf /etc/samba/smb.conf");
	}
	
	//Delete FTP entry
	$LineStart=exec("sudo cat /etc/proftpd/proftpd.conf | grep -n BEGINOF_$folder_name### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/proftpd/proftpd.conf | grep -n ENDOF_$folder_name### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/proftpd/proftpd.conf /tmp/proftpd.conf");
		exec("sudo chmod 777 /tmp/proftpd.conf");
		delLineFromFile("/tmp/proftpd.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/proftpd.conf /etc/proftpd/proftpd.conf");
	
	}

	//Delete AFP entry
	$LineStart=exec("sudo cat /usr/local/netatalk/etc/netatalk/AppleVolumes.default | grep -n BEGINOF_$folder_name### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /usr/local/netatalk/etc/netatalk/AppleVolumes.default | grep -n ENDOF_$folder_name### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /usr/local/netatalk/etc/netatalk/AppleVolumes.default /tmp/AppleVolumes.default");
		exec("sudo chmod 777 /tmp/AppleVolumes.default");
		delLineFromFile("/tmp/AppleVolumes.default", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/AppleVolumes.default /usr/local/netatalk/etc/netatalk/AppleVolumes.default");
	}
		
	exec("sudo /etc/sss_script/share/query_share.sh add $folder_name");
	exec("sudo /usr/bin/smbcontrol smbd reload-config");
	*/

	echo "ok:folder_edit";

}else {
	//$target = explode(":",$SetupMode);
	//echo $target[1];

	$folders = explode(";",$folder_name);
	$count = sizeof($folders);
	
	//$folder_name	=iconv($display_charset,$system_charset,$folder_name);
	for($i=0;$i<$count;$i++){
	
	//delete folder from system 
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select * from folder_info where folder='$folders[$i]'");
		$sth->execute();
		$path=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
	print "";
	die();
	}
	$delete_folder=$path[0][2];
	//echo $delete_folder;



	//echo "delete mode".$folder_name;
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from folder_info where folder='$folders[$i]'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}

	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$dbh->exec("delete from folder_member where folder='$folders[$i]'");
	}
	catch(PDOException $e) {
		print "DB delete err";
		die();
	}
	
	// NC1
	exec("sudo nas-share del_folder $delete_folder");
	
	// NS1
	/*
  	exec("sudo /usr/bin/smbcontrol smbd close-share $folders[$i]");
	exec("sudo rm -rf $delete_folder");
	//echo $result[1];

	//Rewrite Default Samba entry
	$LineStart=exec("sudo cat /etc/samba/smb.conf | grep -n BEGINOF_DEFAULT### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/samba/smb.conf | grep -n ENDOF_DEFAULT### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/samba/smb.conf /tmp/smb.conf");
		exec("sudo chmod 777 /tmp/smb.conf");
		delLineFromFile("/tmp/smb.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/smb.conf /etc/samba/smb.conf");
		exec('sudo /etc/sss_script/share/gen_default_share.sh >> /etc/samba/smb.conf');
	}
	
	//Delete Samba entry
	$LineStart=exec("sudo cat /etc/samba/smb.conf | grep -n BEGINOF_$folders[$i]### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/samba/smb.conf | grep -n ENDOF_$folders[$i]### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/samba/smb.conf /tmp/smb.conf");
		exec("sudo chmod 777 /tmp/smb.conf");
		delLineFromFile("/tmp/smb.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/smb.conf /etc/samba/smb.conf");
 		exec("sudo /usr/bin/smbcontrol smbd reload-config");
	
	}
	
	//Delete FTP entry
	$LineStart=exec("sudo cat /etc/proftpd/proftpd.conf | grep -n BEGINOF_$folders[$i]### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /etc/proftpd/proftpd.conf | grep -n ENDOF_$folders[$i]### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /etc/proftpd/proftpd.conf /tmp/proftpd.conf");
		exec("sudo chmod 777 /tmp/proftpd.conf");
		delLineFromFile("/tmp/proftpd.conf", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/proftpd.conf /etc/proftpd/proftpd.conf");
	
	}

	//Delete AFP entry
	$LineStart=exec("sudo cat /usr/local/netatalk/etc/netatalk/AppleVolumes.default | grep -n BEGINOF_$folders[$i]### | head -1 ");
	if($LineStart != ''){
		$Line=explode(":",$LineStart);
		$LineStart=$Line[0];
		$LineEnd=exec("sudo cat /usr/local/netatalk/etc/netatalk/AppleVolumes.default | grep -n ENDOF_$folders[$i]### | tail -1 ");
		$Line=explode(":",$LineEnd);
		$LineEnd=$Line[0];
		exec("sudo cp /usr/local/netatalk/etc/netatalk/AppleVolumes.default /tmp/AppleVolumes.default");
		exec("sudo chmod 777 /tmp/AppleVolumes.default");
		delLineFromFile("/tmp/AppleVolumes.default", $LineStart, $LineEnd);
 		exec("sudo mv /tmp/AppleVolumes.default /usr/local/netatalk/etc/netatalk/AppleVolumes.default");
	}
	//exec("sudo /usr/bin/smbcontrol smbd reload-config");
	*/
	}
	echo "ok:folder_delete";

}


//	header("Location:$base_dir/admin/share_folder.php");


function delLineFromFile($fileName, $lineBegin, $lineEnd){
// check the file exists 
  if(!is_writable($fileName))
    {
    // print an error
    print "The file $fileName is not writable";
    // exit the function
    exit;
    }
  else
      {
    // read the file into an array    
    $arr = file($fileName);
    }

  // the line to delete is the line number minus 1, because arrays begin at zero
  $lineToDeleteBegin = $lineBegin-1;
  $lineToDeleteEnd = $lineEnd-1;
 
  // check if the line to delete is greater than the length of the file
  if($lineToDeleteEnd > sizeof($arr))
    {
      // print an error
    print "You have chosen a line number, <b>[$lineNum]</b>,  higher than the length of the file.";
    // exit the function
    exit;
    }

  //remove the line
  for($i=$lineToDeleteBegin;$i<=$lineToDeleteEnd;$i++){
  	unset($arr["$i"]);
  }

  // open the file for reading
  if (!$fp = fopen($fileName, 'w+'))
    {
    // print an error
        print "Cannot open file ($fileName)";
      // exit the function
        exit;
        }
  
  // if $fp is valid
  if($fp)
    {
        // write the array to the file
        foreach($arr as $line) { fwrite($fp,$line); }

        // close the file
        fclose($fp);
        }

//echo "done";
}


/* 01/05/09
 * Added by Park94
 * For Setting user permission for folders changed or created
 */
//=======================================================//
// Set folder list for login user
// with full path of folder
//=======================================================//
// User
$_usr = $_SESSION['username'];
try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select gid from group_user where uid='$_usr'");
	$sth->execute();
	$_grps=$sth->fetchAll();
	$dbh=null;
}
catch(PDOException $e) {
	print "";
	die();
}
$usr_grps = array();
$_cGrps = 0;
foreach($_grps as $value){
	$usr_grps[] = $value[0];
	$_cGrps++;
}
$_cMax = $_cGrps+1;


// All web shared directories
try{
	$dbh=new PDO("sqlite:/etc/nas/db/share.db");
	$sth=$dbh->prepare("select folder from folder_info");
	$sth->execute();
	$_dirs=$sth->fetchAll();
	$dbh=null;
}
catch(PDOException $e) {
	print "";
	die();
}
$web_dirs = array();
foreach($_dirs as $value){
	$web_dirs[] = trim($value[0]);
}


//permission
// Temp dirs for permission
$_rw_dirs = array();
$_ro_dirs = array();
foreach($web_dirs as $index => $value){
	$_cRW = 0;
	$_cRO = 0;
	$_fRW = false;
	$_fRO = false;
	
	// RW : User
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select folder from folder_member where attr='user' and rw='$_usr' and folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	if($_tmp){
		$_fRW = true;
		$_rw_dirs[] = $value;
		continue;
	}
	
	
	// RW : Group
	foreach($usr_grps as $_grp_value){
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select folder from folder_member where attr='group' and rw='$_grp_value' and folder='$value'");
			$sth->execute();
			$_tmp=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		if($_tmp){
			$_cRW++;
			break;
		}
	}
	/* New decision rule */
	if($_cRW>0){
		$_fRW = true;
		$_rw_dirs[] = $value;
		continue;
	}
	
	// RO : User
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select folder from folder_member where attr='user' and ro='$_usr' and folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	if($_tmp){
		$_fRO = true;
		$_ro_dirs[] = $value;
		continue;
	}
	
	
	// RO : Group
	foreach($usr_grps as $_grp_value){
		try{
			$dbh=new PDO("sqlite:/etc/nas/db/share.db");
			$sth=$dbh->prepare("select folder from folder_member where attr='group' and ro='$_grp_value' and folder='$value'");
			$sth->execute();
			$_tmp=$sth->fetchAll();
			$dbh=null;
		}
		catch(PDOException $e) {
			print "";
			die();
		}
		if($_tmp){
			$_cRO++;
			break;
		}
	}
	if($_cRO>0){
		$_fRO = true;
		$_ro_dirs[] = $value;
		continue;
	}	
}


// rw dirs : full path
$web_rw_dirs = array();
$web_ro_dirs = array();
foreach($_rw_dirs as $value){
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select path from folder_info where folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$web_rw_dirs[] = $_tmp[0][0];
}


// ro dirs : full path
foreach($_ro_dirs as $value){
	try{
		$dbh=new PDO("sqlite:/etc/nas/db/share.db");
		$sth=$dbh->prepare("select path from folder_info where folder='$value'");
		$sth->execute();
		$_tmp=$sth->fetchAll();
		$dbh=null;
	}
	catch(PDOException $e) {
		print "";
		die();
	}
	$web_ro_dirs[] = $_tmp[0][0];
}

$_SESSION['rw_dir'] = $web_rw_dirs;
$_SESSION['ro_dir'] = $web_ro_dirs;
$_SESSION['share_dir'] = array_merge($_SESSION['mount_dir'],$_SESSION['rw_dir'],$_SESSION['ro_dir']);
session_write_close();
return;
?>
