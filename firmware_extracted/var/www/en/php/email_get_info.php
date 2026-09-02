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

// NC1
$email			= strtoupper(trim(exec ('sudo nas-system get email_alert')));
$SMTP_SERVER		= trim(exec ('sudo nas-system get smtp_server'));
$SMTP_AUTH		= strtoupper(trim(exec ('sudo nas-system get smtp_auth')));
$SMTP_SSL		= strtoupper(trim(exec ('sudo nas-system get smtp_ssl')));
$SMTP_USER		= trim(exec ('sudo nas-system get smtp_user'));
$SMTP_PASS		= trim(exec ('sudo nas-system get smtp_pass'));
$SUBJECT		= trim(exec ('sudo nas-system get email_subject'));
$MAILTO			= trim(exec ('sudo nas-system get email_to'));
$HDD_REPORT		= strtoupper(trim(exec ('sudo nas-system get email_hdd_report')));
$HDD_REPORT_TERM	= trim(exec ('sudo nas-system get email_term'));

// NS1
/*
$email			= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "EMAIL="|cut -d "=" -f 2'));
$SMTP_SERVER		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_SERVER="|cut -d "=" -f 2'));
$SMTP_AUTH		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_AUTH="|cut -d "=" -f 2'));
$SMTP_SSL		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_SSL="|cut -d "=" -f 2'));
$SMTP_USER		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_USER="|cut -d "=" -f 2'));
$SMTP_PASS		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SMTP_PASS="|cut -d "=" -f 2'));
$SUBJECT		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "SUBJECT="|cut -d "=" -f 2'));
$MAILTO			= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "MAILTO="|cut -d "=" -f 2'));
$HDD_REPORT		= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "HDD_REPORT="|cut -d "=" -f 2'));
$HDD_REPORT_TERM	= trim(exec ('sudo cat /etc/sss_script/email/alert.conf | grep "REPORT_TERM="|cut -d "=" -f 2'));
*/

echo "$email;$SMTP_SERVER;$SMTP_AUTH;$SMTP_SSL;$SMTP_USER;$SMTP_PASS;$SUBJECT;$MAILTO;$HDD_REPORT;$HDD_REPORT_TERM;";

?>
