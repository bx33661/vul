<?
require_once ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE ) exit('-99');


$dyndns_service	= trim(exec('sudo nas-service get_ddns service'));
// NC1
$dyndns			= trim(exec('sudo nas-service get_ddns enabled'));
$dyndns_user_file 	= trim(exec('sudo nas-service get_ddns username'));
$dyndns_pass_file 	= trim(exec('sudo nas-service get_ddns password'));
$mydomain		= trim(exec('sudo nas-service get_ddns alias'));

if($dyndns=='on') {
	$myip		= trim(exec('sudo nas-service get_ddns registered_ip')); 
	$confirm_ip	= trim(exec('sudo nas-service get_ddns confirm_ip'));

	if ($myip==$confirm_ip && $confirm_ip != '' )
		$status = "OK";
	else
		$status = "NG";

} else $status="STOP";

exit(json_encode(array('d'=>$dyndns,'du'=>$dyndns_user_file,'dp'=>$dyndns_pass_file,'dd'=>$mydomain,'di'=>$confirm_ip,'ds'=>$status,'dv'=>$dyndns_service)));
//echo "$dyndns:$dyndns_user_file:$dyndns_pass_file:$mydomain:$confirm_ip:$status:$dyndns_service");
?>
