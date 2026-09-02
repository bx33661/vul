<?php

function msgjob($mode,$msg)
{
	if(eregi("info",$mode)){
		shell_exec(`sudo nas-common lcd $msg`);
	}
	else if(eregi("err",$mode)){
	}
	else if(eregi("warn",$mode)){
	}
	return;
}

?>
