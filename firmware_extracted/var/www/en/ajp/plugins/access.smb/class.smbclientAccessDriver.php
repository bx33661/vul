<?php
/**
 * @package info.ajaxplorer.plugins
 * 
 * Copyright 2007-2009 sonmapsi@lge.com and LG Electronics
 * This file is part of AjaXplorer.
 * The latest code can be found at http://www.ajaxplorer.info/
 * 
 * This program is published under the LGPL Gnu Lesser General Public License.
 * You should have received a copy of the license along with AjaXplorer.
 * 
 * The main conditions are as follow : 
 * You must conspicuously and appropriately publish on each copy distributed 
 * an appropriate copyright notice and disclaimer of warranty and keep intact 
 * all the notices that refer to this License and to the absence of any warranty; 
 * and give any other recipients of the Program a copy of the GNU Lesser General 
 * Public License along with the Program. 
 * 
 * If you modify your copy or copies of the library or any portion of it, you may 
 * distribute the resulting library provided you do so under the GNU Lesser 
 * General Public License. However, programs that link to the library may be 
 * licensed under terms of your choice, so long as the library itself can be changed. 
 * Any translation of the GNU Lesser General Public License must be accompanied by the 
 * GNU Lesser General Public License.
 * 
 * If you copy or distribute the program, you must accompany it with the complete 
 * corresponding machine-readable source code or with a written offer, valid for at 
 * least three years, to furnish the complete corresponding machine-readable source code. 
 * 
 * Any of the above conditions can be waived if you get permission from the copyright holder.
 * AjaXplorer is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Description : smbclient cli tool access class
 */
define("SYSTEM_DEFAULT_LANG", "en_US.UTF8");
//define("SMBCLIENT_SHELL_CMD", "/usr/local/samba/bin/smbclient"); // NS1
define("SMBCLIENT_SHELL_CMD", "smbclient"); // NC1

// unknown error code
define("NT_STATUS_UNKNOWN", -255);
define("NT_STATUS_SHARING_VIOLATION", -1);
define("NT_STATUS_OBJECT_NAME_COLLISION", -2);
define("NT_STATUS_OBJECT_NAME_NOT_FOUND", -3);
define("NT_STATUS_DISK_FULL", -4);
define("NT_STATUS_OBJECT_PATH_NOT_FOUND", -5);
define("NT_STATUS_NO_SUCH_FILE", -6);
define("NT_STATUS_ACCESS_DENIED", -7);
define("NT_STATUS_MEDIA_WRITE_PROTECTED", -8);


class smbclientAccessDriver
{
	// Hold an instance of the class
	private static $instance;
	private static $ServerName;
	private static $ServiceName;
	private static $LoginUserName;
	private static $LoginUserPassword;
	private static $ErrorCode;
	private static $ErrorString;
	private static $WorkgroupName;
	
	// A private constructor; prevents direct creation of object
	private function __construct()
	{
		$this->ServerName = "localhost";
		$this->ServiceName = $_SESSION["REPO_ID"];
		$loggedUser = AuthService::getLoggedUser();
		$this->LoginUserName = $loggedUser->getId();
		$this->LoginUserPassword = $_SESSION["AJXP_REMOTE_PW"];
		$this->WorkgroupName = `hostname`;
		
		$this->ErrorCode = array(
			"NT_STATUS_SHARING_VIOLATION" => NT_STATUS_SHARING_VIOLATION,
			"NT_STATUS_OBJECT_NAME_COLLISION" => NT_STATUS_OBJECT_NAME_COLLISION,
			"NT_STATUS_OBJECT_NAME_NOT_FOUND" => NT_STATUS_OBJECT_NAME_NOT_FOUND,
			"NT_STATUS_DISK_FULL" => NT_STATUS_DISK_FULL,
			"NT_STATUS_OBJECT_PATH_NOT_FOUND" => NT_STATUS_OBJECT_PATH_NOT_FOUND,
			"NT_STATUS_NO_SUCH_FILE" => NT_STATUS_NO_SUCH_FILE,
			"NT_STATUS_ACCESS_DENIED" => NT_STATUS_ACCESS_DENIED,
			"NT_STATUS_MEDIA_WRITE_PROTECTED" => NT_STATUS_MEDIA_WRITE_PROTECTED,
		);
	}
	
	// The singleton method
	public static function singleton()
	{
		if( !isset(self::$instance) )
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		
		return self::$instance;
	}
	
	
	// Prevent users to clone the instance
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	private function find_error_code($error_string)
	{
		$this->ErrorString = $error_string;
		foreach($this->ErrorCode as $key => $value)
		{
			if ( strpos($error_string, $key) !== FALSE )
			{
				return $value;
			}
		}
		
		return NT_STATUS_UNKNOWN;
	}
	
    private function shell_exec_smbclient($smbclientCommand)
    {
        $ShellCmd = 'LANG='.SYSTEM_DEFAULT_LANG.' '.SMBCLIENT_SHELL_CMD.' //'.$this->ServerName.'/"'.$this->ServiceName.'" -c "'.$smbclientCommand.'" -U '.$this->LoginUserName.'%'.$this->LoginUserPassword.' -W '.$this->WorkgroupName;
        $ShellResult = shell_exec($ShellCmd);
        //error_log("cmd: ".$ShellCmd."\n", 3, "/tmp/ajaxplorer.log");
	//error_log("out: ".$ShellResult."\n", 3, "/tmp/ajaxplorer.log");
        return $ShellResult;
    }
    
    private function exec_smbclient($smbclientCommand, &$Output)
    {
		$Cmd = 'LANG='.SYSTEM_DEFAULT_LANG.' '.SMBCLIENT_SHELL_CMD.' //'.$this->ServerName.'/"'.$this->ServiceName.'" -c "'.$smbclientCommand.'" -U '.$this->LoginUserName.'%'.$this->LoginUserPassword.' -W '.$this->WorkgroupName;
        
		exec($Cmd, $Output, $Result);
		//error_log("cmd: ".$Cmd." result: ".$Result."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result:\n", 3, "/tmp/ajaxplorer.log");
		//foreach($Output as &$OneLine)
		//{
		//	error_log($OneLine."\n", 3, "/tmp/ajaxplorer.log");
		//}
        
        return $Result;
    }
	
	public function mput_smbclient($TempDirInSystem, $DestDirInSmb)
	{
		$TempDirInSystem = Utils::shell_escape_args($TempDirInSystem);
		$DestDirInSmb = Utils::shell_escape_args($DestDirInSmb);
		//by soo.
		//if($_SESSION["DST_REPO"]){
			$this->ServiceName = $_SESSION["DST_REPO"];
		//}
		$smbclientCommand = 'lcd \"'.$TempDirInSystem.'\";';
		$smbclientCommand .= 'cd \"'.$DestDirInSmb.'\";';
		$smbclientCommand .= 'prompt; recurse; mput *';
		
		$Cmd = 'LANG='.SYSTEM_DEFAULT_LANG.' '.SMBCLIENT_SHELL_CMD.' //'.$this->ServerName.'/"'.$this->ServiceName.'" -c "'.$smbclientCommand.'" -U '.$this->LoginUserName.'%'.$this->LoginUserPassword.' -W '.$this->WorkgroupName;		
		
		exec($Cmd, $Output, $Result);
		
		//error_log("cmd: ".$Cmd." result: ".$Result."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result:\n", 3, "/tmp/ajaxplorer.log");
		//foreach($Output as &$OneLine)
		//{
		//	error_log($OneLine."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( $Result === 0 )
		{
			return TRUE;
		}
		else
		{
			$this->ErrorString = $Output[0];
			return FALSE;
		}
		//$_SESSION["DST_REPO"] = "";
	}
	
	public function put_smbclient($TempFileInSystem, $DestFileInSmb)
	{
		$TempFileInSystem = Utils::shell_escape_args($TempFileInSystem);
		$DestFileInSmb = Utils::shell_escape_args($DestFileInSmb);
		//by soo.
		$this->ServiceName = $_SESSION["DST_REPO"];
		
		
		$smbclientCommand = 'put \"'.$TempFileInSystem.'\" \"'.$DestFileInSmb.'\"';
		
		//error_log("in smb client DST_REPO!!:".$this->ServiceName."\n",3,"/tmp/ajaxplorer.log");
		$shell_cmd = 'sudo LANG='.SYSTEM_DEFAULT_LANG.' '.SMBCLIENT_SHELL_CMD.' //'.$this->ServerName.'/"'.$this->ServiceName.'" -c "'.$smbclientCommand.'" -U '.$this->LoginUserName.'%'.$this->LoginUserPassword.' -W '.$this->WorkgroupName;		
		
		exec($shell_cmd, $output, $result);
		
		//error_log("cmd: ".$shell_cmd." result: ".$result."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result:\n", 3, "/tmp/ajaxplorer.log");
		//foreach($output as &$oneline)
		//{
		//	error_log($oneline."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( $result === 0 )
		{
			return 0;
		}
		else
		{
			return $this->find_error_code($output[0]);
		}
		//$_SESSION["DST_REPO"] = "";
	}
	
	public function mget_smbclient($SrcDirInSmb, $DestDirInSystem)
	{
		$DestDirInSystem = Utils::shell_escape_args($DestDirInSystem);
		$SrcDirInSmb = Utils::shell_escape_args($SrcDirInSmb);
		
		$smbclientCommand = 'lcd \"'.$DestDirInSystem.'\";';
		$smbclientCommand .= 'cd \"'.$SrcDirInSmb.'\";';
		$smbclientCommand .= 'prompt; recurse; mget *';
		
		$Cmd = 'LANG='.SYSTEM_DEFAULT_LANG.' '.SMBCLIENT_SHELL_CMD.' //'.$this->ServerName.'/"'.$this->ServiceName.'" -c "'.$smbclientCommand.'" -U '.$this->LoginUserName.'%'.$this->LoginUserPassword.' -W '.$this->WorkgroupName;		
		
		exec($Cmd, $Output, $Result);
		
		//error_log("cmd: ".$Cmd." result: ".$Result."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result:\n", 3, "/tmp/ajaxplorer.log");
		//foreach($Output as &$OneLine)
		//{
		//	error_log($OneLine."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( $Result === 0 )
		{
			return TRUE;
		}
		else
		{
			$this->ErrorString = $Output[0];
			return FALSE;
		}
	}
	
	public function get_smbclient($SrcFileInSmb , $DestTempFileInSystem)
	{
		$SrcFileInSmb = Utils::shell_escape_args($SrcFileInSmb);
		$DestTempFileInSystem = Utils::shell_escape_args($DestTempFileInSystem);
		
		$smbclientCommand = 'get \"'.$SrcFileInSmb.'\" \"'.$DestTempFileInSystem.'\"';
		$Cmd = 'LANG='.SYSTEM_DEFAULT_LANG.' '.SMBCLIENT_SHELL_CMD.' //'.$this->ServerName.'/"'.$this->ServiceName.'" -c "'.$smbclientCommand.'" -U '.$this->LoginUserName.'%'.$this->LoginUserPassword.' -W '.$this->WorkgroupName;		
		
		exec($Cmd, $Output, $Result);
		
		//error_log("cmd: ".$Cmd." result: ".$Result."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result:\n", 3, "/tmp/ajaxplorer.log");
		//foreach($Output as &$OneLine)
		//{
		//	error_log($OneLine."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( $Result === 0 )
		{
			return TRUE;
		}
		else
		{
			$this->ErrorString = $Output[0];
			return FALSE;
		}
	}
	
	public function ls_smbclient($SmbPath = "", $need_change_directory = FALSE, &$Files, &$DiskUsageInfo)
	{
		if ( $SmbPath == "" )
		{
			$SmbPath = "/";
		}
		
		$SmbPath = Utils::shell_escape_args($SmbPath);
		
		$SmbclientCmd = 'showacls; lg_cmds;';
		if ( $need_change_directory == TRUE )
		{
			$SmbclientCmd .= ' cd \"'.$SmbPath.'\"; ls';
		}
		else
		{			
			$SmbclientCmd .= ' ls \"'.$SmbPath.'\"';
		}
		$ShellResult = self::exec_smbclient($SmbclientCmd, $lsResult);
		//error_log("cmd: ".$SmbclientCmd." result: ".$ShellResult."\n", 3, "/tmp/ajaxplorer.log");
		
		if ( $ShellResult !== 0 )
		{
			$Files = NULL;
			$DiskUsageInfo = "";
			
			$this->ErrorString = "Unknown Error";
			return NT_STATUS_UNKNOWN;
		}
		
		if ( strpos($lsResult[0], "NT_STATUS_OBJECT_NAME_NOT_FOUND") !== FALSE )
		{
			// error code of cd command
			// Directory does not exist
			$this->ErrorString = $lsResult[0];
			return NT_STATUS_OBJECT_NAME_NOT_FOUND;
		}
		if ( strpos($lsResult[0], "NT_STATUS_OBJECT_PATH_NOT_FOUND") !== FALSE )
		{
			// error code of cd command
			$this->ErrorString = $lsResult[0];
			return NT_STATUS_OBJECT_PATH_NOT_FOUND;
		}
		if ( $need_change_directory == FALSE && strpos($lsResult[0], "NT_STATUS_NO_SUCH_FILE") !== FALSE )
		{
			// error code of ls command
			$this->ErrorString = $lsResult[0];
			return NT_STATUS_NO_SUCH_FILE;
		}

		$Files = array();
		$LineCount = count($lsResult);
		$aclInfo = "";
		$FileExists = FALSE;
		
		for ( $i = 0; $i < ($LineCount - 1); $i++ )
		{
			$OneLine = $lsResult[$i];
			
			preg_match("/^(FILENAME|MODE|SIZE|MTIME):(.*)$/u", $OneLine, $Matches);
			if ( isset($Matches[1]) )
			{
				$FileExists = TRUE;
				
				if ( ($Matches[1] == "FILENAME") )
				{
					$FileName = $Matches[2];
				}
				
				$Files[$FileName][$Matches[1]] = $Matches[2];
				//error_log("file ".$FileName." ".$Matches[1]." ".$Matches[2]."\n", 3, "/tmp/ajaxplorer.log");
			}
			/*
			preg_match("/^(FILENAME|MODE|SIZE|MTIME|revision|type):(.*)$/u", $OneLine, $Matches);
			if ( isset($Matches[1]) )
			{
				$FileExists = TRUE;
				
				if ( ($Matches[1] == "FILENAME") )
				{
					$FileName = $Matches[2];
					$Files[$FileName]["type"] = (isset($Files[$FileName]["type"]) ? $Files[$FileName]["type"].$aclInfo : $aclInfo);

					$aclInfo = "";
				}
				
				$Files[$FileName][$Matches[1]] = $Matches[2];
				//error_log("file ".$FileName." ".$Matches[1]." ".$Matches[2]."\n", 3, "/tmp/ajaxplorer.log");
			}
			else
			{
				$aclInfo .= "\n".$OneLine;
			}
			*/
		}
		
		/*
		if ( $FileExists == TRUE )
		{
			$Files[$FileName]["type"] .= $aclInfo;
		}
		*/
		
		$DiskUsageInfo = trim($lsResult[$LineCount - 1]);
		
		return 0;
	}
	
	public function rename_smbclient($old_filename, $new_filename)
	{
		$old_filename = Utils::shell_escape_args($old_filename);
		$new_filename = Utils::shell_escape_args($new_filename);
		
		$smbclient_cmd = 'rename \"'.$old_filename.'\" \"'.$new_filename.'\"';
		
		$shell_result = self::exec_smbclient($smbclient_cmd, $output);
		//error_log("sub_cmd: ".$smbclient_cmd."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result: $shell_result\n", 3, "/tmp/ajaxplorer.log");
		//foreach($output as &$oneline)
		//{
		//	error_log($oneline."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( ($shell_result === 0) && count($output) == 0 )
		{
			return 0;
		}
		else
		{
			return $this->find_error_code($output[0]);
		}
	}
	
	public function cp_mkdir_smbclient($dirname)
	{
		$dirname = Utils::shell_escape_args($dirname);
		
		//by soo.
		//if($_SESSION["DST_REPO"]){
			$this->ServiceName = $_SESSION["DST_REPO"];
		//}
		
		$smbclient_cmd = 'mkdir \"'.$dirname.'\"';
		
		$shell_result = self::exec_smbclient($smbclient_cmd, $output);
		//error_log("sub_cmd: ".$smbclient_cmd."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result: $shell_result\n", 3, "/tmp/ajaxplorer.log");
		//foreach($output as &$oneline)
		//{
		//	error_log("mkdir: ".$oneline."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( ($shell_result === 0) && count($output) == 0 )
		{
			return 0;
		}
		else
		{
			return $this->find_error_code($output[0]);
		}
		//$_SESSION["DST_REPO"] = "";
	}
	
	public function mkdir_smbclient($dirname)
	{
		$dirname = Utils::shell_escape_args($dirname);
		
		$smbclient_cmd = 'mkdir \"'.$dirname.'\"';
		
		$shell_result = self::exec_smbclient($smbclient_cmd, $output);
		//error_log("sub_cmd: ".$smbclient_cmd."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result: $shell_result\n", 3, "/tmp/ajaxplorer.log");
		//foreach($output as &$oneline)
		//{
		//	error_log($oneline."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( ($shell_result === 0) && count($output) == 0 )
		{
			return 0;
		}
		else
		{
			return $this->find_error_code($output[0]);
		}
	}
	
	public function rm_smbclient($filename, $is_directory = FALSE, $wildcard_string = "")
	{
		$filename = Utils::shell_escape_args($filename);
		$this->ServiceName = $_SESSION["REPO_ID"];
		
		$smb_cmd = 'rm';
		if ( $is_directory == TRUE )
		{
			$smb_cmd .= 'dir';
		}
		else if ( $wildcard_string != "" )// file인 경우만 wild card 적용
		{
			$filename .= ('\\'.$wildcard_string);
		}
		
		$smbclient_cmd = $smb_cmd.' \"'.$filename.'\"';
		
		$shell_result = self::exec_smbclient($smbclient_cmd, $output);
		//error_log("sub_cmd: ".$smbclient_cmd."\n", 3, "/tmp/ajaxplorer.log");
		//error_log("result: $shell_result\n", 3, "/tmp/ajaxplorer.log");
		//foreach($output as &$oneline)
		//{
		//	error_log($oneline."\n", 3, "/tmp/ajaxplorer.log");
		//}
		
		if ( ($shell_result === 0) && count($output) == 0 )
		{
			return 0;
		}
		else
		{
			return $this->find_error_code($output[0]);
		}
	}
	
	public function get_last_error_string()
	{
		return $this->ErrorString;
	}
}

?>
