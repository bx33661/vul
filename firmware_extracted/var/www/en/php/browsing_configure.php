<?php

	/*
	 * $_SESSION['user_id'] : 유저 ID (나중에 로그인처리가 되면 로그인한 유저의 ID)
	 * $_SESSION['current_dir'] : 각 유저별 디렉토리를 루트로 가정했을때의 현재 디렉토리 위치
	 * 								(유저의 디렉토리가 /usr/local/apache/htdocs/test/이고
	 * 									/usr/local/apache/htdocs/test/mydir_1 이 현재위치일때,
	 * 									/mydir_1이 된다.)
	 * $_SESSION['storage_url'] : 유저 디렉토리의 외부에서 접속 가능한 url www.browser.com/test 의 형식이 된다.
	 * $_SESSION['user_directory'] : 유저 디렉토리의 실제 시스템상의 Path (/usr/local/apache/htdocs/test)
	 */

	session_start();
	
	$_SESSION['user_id'] = 'admin';
	
	$curr_dir = dirname(__FILE__);
	$project_url = substr($curr_dir,strlen($_SERVER['DOCUMENT_ROOT']));
	if(substr($project_url,0,1) != '/'){
		$project_url = '/'.$project_url;
	}
	if(substr($project_url,-1) != '/'){
		$project_url .= '/';
	}
	
	$_SESSION['storage_url'] = $project_url.$_SESSION['user_id'];
	$_SESSION['user_directory'] = "/mnt/fs";
	
	
	// Blu-ray path setting
	$BD_mode=array('rip','store','schedule','data copy','image backup',
	'burn','image burn','usb');
	$BD_path=array('rip' => '/Vol1/system/Backup/RIP', 'store' => '/Vol1/system/Backup',
	'schedule' => '/Vol1/system/Backup',
	'data copy' => '/Vol1/system/Backup/COPY', 'image backup' => '/Vol1/system/Backup/IMG',
	'burn' => '/Vol1/system/Share/','image burn' => '/Vol1/system/Backup/IMG',
	'usb' => '/Vol1/system/Backup/USB');	
	@$path_mode = $_GET['path_mode'];
	
	if(substr($_SESSION['current_dir'],-1) != '/'){
		$_SESSION['current_dir'] .= '/';
	}
	if(substr($_SESSION['current_dir_esata'],-1) != '/'){
		$_SESSION['current_dir_esata'] .= '/';
	}
	if(in_array($_GET['path_mode'],$BD_mode))
	{
		$_SESSION['current_dir'] = $BD_path[$path_mode];
	}
?>
