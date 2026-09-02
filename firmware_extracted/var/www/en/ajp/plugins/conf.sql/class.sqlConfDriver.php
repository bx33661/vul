<?php
/**
 * @package info.ajaxplorer
 * 
 * Copyright 2007-2009 Charles du Jeu
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
 * Description : Serialized Files implementation of AbstractConfDriver
 */
require_once(INSTALL_PATH."/server/classes/class.AbstractConfDriver.php");
class sqlConfDriver extends AbstractConfDriver
{
	var $dbDriver;
	var $dbFile;
	var $defaultShare;
	
	function init($options)
	{
		parent::init($options);
		//park94 09/18/09
		$this->dbDriver = $options["DRIVER"];
		$this->dbFile = $options["DB"];
		
		$this->defaultShare = strtolower($options["DEFAULT_SHARE"]);
	}
	
	// SAVE / EDIT / CREATE / DELETE REPOSITORY
	function listRepositories()
	{
		$shared_folders = $this->getSharedFolderListFromDB();
		$repositories = array();
		foreach($shared_folders as $one_shared_folder)
		{
			$one_repository = ConfService::createRepositoryFromArray(0, $one_shared_folder);
			$repositories[$one_repository->uuid] = $one_repository;
		}
		return $repositories;
	}
	
	/**
	 * Unique ID of the repositor
	 *
	 * @param String $repositoryId
	 * @return Repository
	 */
	function getRepositoryById($repositoryId)
	{
		return null;
	}
	/**
	 * Store a newly created repository 
	 *
	 * @param Repository $repositoryObject
	 * @param Boolean $update 
	 * @return -1 if failed
	 */
	function saveRepository($repositoryObject, $update = false)
	{
		return 0;	
	}
	/**
	 * Delete a repository, given its unique ID.
	 *
	 * @param String $repositoryId
	 */	
	function deleteRepository($repositoryId)
	{
	}
	
	// SAVE / EDIT / CREATE / DELETE USER OBJECT (except password)
	/**
	 * Instantiate the right class
	 *
	 * @param AbstractAjxpUser $userId
	 */
	function instantiateAbstractUserImpl($userId)
	{
		return new AJXP_User($userId, $this);
	}	
	
	function getUserClassFileName()
	{
		return INSTALL_PATH."/plugins/conf.sql/class.AJXP_User.php";
	}

	//park94 09/21/09
	//Desc:Access NAS DB
	function getSharedFolderListFromDB()
	{
		$repository_default_form = array (
					'DISPLAY' => 'volume1_public',// repository display name
					'DRIVER' => 'smb',
					'DRIVER_OPTIONS' => array (
						'PATH' => '/mnt/disk/volume1/volume1_public', // folder path on fs
						'CREATE' => 'false',
						'RECYCLE_BIN' => '',
						'CHMOD_VALUE' => '0766',
						'DEFAULT_RIGHTS' => '', // folder permission r or rw
						'CHARSET' => '',
						'PAGINATION_THRESHOLD' => '500',
						'PAGINATION_NUMBER' => '200'
					)
				 );
				
		try
		{
			$dbh = new PDO($this->dbDriver.":".$this->dbFile);
			$sth = $dbh->prepare("SELECT folder, path, attr, windows, recycle FROM folder_info");
			$sth->execute();
			$folders = $sth->fetchAll();
			$dbh = null;
		}
		catch(PDOException $e)
		{
			die("can not access share db file on". $this->dbFile);
		}
		
		$repository = array();
		
		foreach($folders as $one_folder)
		{
			//error_log("folder:".var_export($one_folder,true),3,"/tmp/ajaxplorer.log");
			// usb, e-sata, cdrom 폴더 필터링, hidden folder, smb 프로토콜 지원하지 않는 폴더 필터링 추가
			$tmp_Str = substr($one_folder["path"], 0, 12);
			//error_log("\n***tmp_Str:".$tmp_Str."\n",3,"/tmp/ajaxplorer.log");
			
			if(($tmp_Str != "/mnt/device/") && ($one_folder["attr"] != "HIDDEN") && ($one_folder["windows"] == "YES")){
				$repository_default_form["DISPLAY"] = $one_folder["folder"];
				$repository_default_form["DRIVER_OPTIONS"]["PATH"] = $one_folder["path"];
				if ( $one_folder["recycle"] == "YES" )
				{
					$repository_default_form["DRIVER_OPTIONS"]["RECYCLE_BIN"] = "trashbox";
				}
				else
				{
					$repository_default_form["DRIVER_OPTIONS"]["RECYCLE_BIN"] = "";
				}
				$repository[] = $repository_default_form;
			}
		}
		
		uasort($repository, array($this, "sort_repo_lists"));
		
		return $repository;
	}
	
	function sort_repo_lists($a, $b)
	{
		$a_base = strtolower($a["DISPLAY"]);
		$b_base = strtolower($b["DISPLAY"]);
		
		// volume1을 default share folder로
		if ( $a_base == $this->defaultShare )
		{
			return -1;
		}
		
		if ( $b_base == $this->defaultShare )
		{
			return 1;
		}
		
		return strcasecmp($a_base, $b_base);
	}
}
?>
