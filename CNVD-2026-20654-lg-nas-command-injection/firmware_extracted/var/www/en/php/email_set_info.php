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


// Email notification enable/disable
$email			= $_POST["rdoMail"];
$SMTP_server		= $_POST["txtSMTP"];
$SMTP_AUTH		= $_POST["chkSMTPAuth"];
$SMTP_User		= $_POST["txtSMTP_User"];
$SMTP_Pass		= $_POST["txtSMTP_Pass"];
$SMTP_AUTH_SSL		= $_POST["chkSMTPAuthSSL"];
$subject		= $_POST["txtSubject"];
$mailto			= $_POST["txtMailTo"];
$HDD_report		= $_POST["chkMailSendHDDReport"];
$HDD_report_term	= $_POST["txtMailHDDStatusTime"];
$CMD			= $_POST["txtClickButton"];
$subject_file = trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SUBJECT="|cut -d "=" -f 2'));
/*
echo "1 : $email\n";
echo "2 : $SMTP_server\n";
echo "3 : $subject\n";
echo "4 : $mailto\n";
echo "5 : $HDD_report\n";
echo "8 : $HDD_report_term\n";
echo "9 : $CMD\n";
echo "10: $SMTP_User\n";
echo "11: $SMTP_Pass\n";
echo "12: $SMTP_AUTH\n";
echo "13: $SMTP_AUTH_SSL\n";
*/
if ($email == "OFF") {
	$SMTP_server = "NONE";
	$mailto = "NONE";
}
if ($SMTP_server == "") $SMTP_server = "NONE";
if ($subject == "") $subject = "NAS Status Report";
if ($mailto == "") $mailto = "NONE";
if ($HDD_report == "") $HDD_report = "OFF";
if ($SMTP_User 	=="" ) $SMTP_User = "NONE";
if ($SMTP_Pass 	=="" ) $SMTP_Pass = "NONE";
if ($SMTP_AUTH 	=="" ) 	$SMTP_AUTH = "OFF";
if ($SMTP_AUTH_SSL =="" ) $SMTP_AUTH_SSL = "OFF";

// NC1
if ($email == "OFF") {
	exec("sudo nas-system set email_hdd_report $HDD_report");	//mail notification off시  hdd_report는 off되지 않아 메일이 계속 전송되는 문제  (etc/nas/system.conf)
	exec("sudo nas-system email_alert off");
	echo "ok:email_off:";
} else {
	exec("sudo nas-system set email_to $mailto");
	exec("sudo nas-system set email_subject $subject");
	exec("sudo nas-system set email_term $HDD_report_term");
	exec("sudo nas-system set email_hdd_report $HDD_report");

	exec("sudo nas-system set smtp_server $SMTP_server");
	exec("sudo nas-system set smtp_auth $SMTP_AUTH");
	exec("sudo nas-system set smtp_user $SMTP_User");
	exec("sudo nas-system set smtp_pass $SMTP_Pass");
	exec("sudo nas-system set smtp_ssl $SMTP_AUTH_SSL");

	exec("sudo nas-system email_alert on");
	echo "ok:email_on:";
}


// NS1
/*
if ( $email=="OFF" ) {
	exec("sudo /etc/sss_script/email/mail-alert-smtp NOAUTH");
		$subject=$subject_file.":".$subject;
	exec("sudo /etc/sss_script/email/desc $subject");
	exec("sudo /etc/sss_script/email/mail-alert $email $SMTP_server $mailto $HDD_report $HDD_report_term $CMD");
	echo "ok:email_off:";
}else{

    if($SMTP_AUTH=="ON") {
			if($SMTP_AUTH_SSL == "ON") {
			exec("sudo /etc/sss_script/email/mail-alert-smtp SSL $SMTP_User $SMTP_Pass");
		} else {
		exec("sudo /etc/sss_script/email/mail-alert-smtp NOSSL $SMTP_User $SMTP_Pass");
		}
	}else exec("sudo /etc/sss_script/email/mail-alert-smtp NOAUTH");
	$subject=$subject_file.":".$subject;
	exec("sudo /etc/sss_script/email/desc $subject");
	exec("sudo /etc/sss_script/email/mail-alert $email $SMTP_server $mailto $HDD_report $HDD_report_term $CMD");
	echo "ok:email_on:";
 }
*/

?>
