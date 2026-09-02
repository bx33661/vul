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


//=======================================================//
// System / Language
// 11/06/2008
// LGE
// park94
//=======================================================//
$lang = $_POST["language"];
echo $lang;
?>