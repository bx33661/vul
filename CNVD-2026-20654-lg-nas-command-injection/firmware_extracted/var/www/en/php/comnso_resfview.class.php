<?php
   
/*
 * @package    Ajax File Browser
 * @author     Kevin Pentiah <admin@3scriptz.com>
 * @copyright  2006 3scriptz
 * @license    PHP License 
 * @version    Release: 1.0.0
 * @link       http://www.3scriptz.com
 * @since      Class available since Release 1.0.0
 * @desc	browser files and folders with ajax
 */
class Browse {
	var $folder = '.';
	var $file;
	var $files = array();
	var $handle;
	var $ext;
    
	public function BrowseAJAX($inputValue, $fieldID)	{
		return $this->dirList($fieldID, $inputValue);
	}

	public function LoadFileList($inputValue, $fieldID, $search)	{
		return $this->fileList($fieldID, $inputValue, $search);
	}
	
	//separate each folder with spaces or hyphens
	public function hyphens($deep) 
	{
	    $rtn = "";
	    for($cnt=0; $cnt<$deep-3; $cnt++){
	        $rtn .= "&nbsp&nbsp&nbsp";    
	    }
	    
	    return $rtn;
	}

	//get mime type
	public function get_mime_type($filename) {
		if (!function_exists ('mime_content_type')) {
			function mime_content_type ( $f ) {
				return exec ( trim( 'file -bi ' . escapeshellarg ( $f ) ) ) ;
			}
		}
		$type = mime_content_type($filename);
		return $type;
	}
	function htmlspecialchars_decode($text) {
		return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
	}

    public function getdirname($path, $token)
    {
        $npos = strrpos($path, $token);
        if($npos > 0)
        {
            return substr($path, $npos+1);
        }
        return $path;
    }	
    	
	//List all in directory
	private function dirList($folder, $state) 
	{
        try{
				if(file_exists("/etc/cms/cmsbackup.db")){
					$dbh = new PDO("sqlite:/etc/cms/cmsbackup.db"); // success
				}else{
					return htmlentities("BLANK_NOSUB", ENT_QUOTES, "UTF-8");
				}
        }catch( PDOException $exception ){
            //die($exception->getMessage());
            return htmlentities("BLANK_NOSUB", ENT_QUOTES, "UTF-8");
        }
        
        if($folder != "root"){
            $sqlGetView = 'SELECT * FROM tblDirList WHERE intIndex='.$folder;
            $sqlGetView .= ';';
            $result = $dbh->query($sqlGetView);
            
            if($result)
            {   
                $field = $result->fetch();
                $sqlList = 'SELECT * FROM tblDirList WHERE (strDir LIKE("';
                $sqlList .= $field['strDir'];
                $sqlList .= '/%")  OR strDir="';
                $sqlList .= $field['strDir'];
                $sqlList .= '") AND intDeep=';
                $sqlList .= $field['intDeep']+1;
                $sqlList .= ' ORDER BY strDir ASC;';
            }else{
                die("no folder\n".$sqlGetView);
                return;
            }
        }else{
					//$sqlList = "SELECT * FROM tblDirList WHERE intDeep=0 ORDER BY strDir ASC;";
				
					//Linux file system;
					//intDeep=3 초기 디렉토리의 / 갯수를 의미한다.
					//hyphens 함수에 -? 값도 수정..
          $sqlList = "SELECT * FROM tblDirList WHERE intDeep=3 ORDER BY strDir ASC;";
        }
        
        $result = $dbh->query($sqlList);

        $temp = "";
        while($row = $result->fetch()){
            $index = $row['intIndex'];
            
            //$dir   = getdirname($row['strDir'], "\\");
            
            //Linux file system;
            $dir = $this->getdirname($row['strDir'], "/");
            
            if(substr($dir, 0,1) == "/"){
					$dir = substr($dir, 1);
            }
            
		    $temp .= $this->hyphens($row['intDeep']).'
		    <img border="0" key="'.$index.'" id="check'.$index.'" src="../images/comnso/cms_uncheck.gif" onclick=\'check_click(this.id)\'/><img border="0" id="image'.$index.'" src="../images/comnso/cms_folder.gif"/>
		    <b><span id="'.$index.'" title="open" onclick=\'browse(this.title, this.id);document.getElementById("'.$index.'Info").innerHTML = "";\' />
		    <a href="javascript:void(0)">'.$dir.'</a></span></b><br><span id="'.$index.'Info" class=""></span>';            
        }
        
 		if($temp == "")
 		{
 			$temp = "BLANK_NOSUB";
 		}

		return htmlentities($temp, ENT_QUOTES, "UTF-8");
	}
	
	public function getfilesize($size)
	{
        $unim = array("B","KB","MB","GB","TB","PB");
        $c = 0;
        while ($size>=1024) {
            $c++;
            $size = $size/1024;
        }
        return number_format($size,($c ? 2 : 0),",",".")." ".$unim[$c];	
	}
	
	public function getfiledate($date)
	{
	    return "&nbsp;".date("Y/m/d H:i", $date);
	}
	
	//List all files in dir
	private function fileList($folder, $state, $search) 
	{
        try{
				if(file_exists("/etc/cms/cmsbackup.db")){
					$dbh = new PDO("sqlite:/etc/cms/cmsbackup.db"); // success
				}
        }catch( PDOException $exception ){
            die($exception->getMessage());
            return "BLANK_NOLIST";
        }
        
			require_once ("../multilang/multilang_api.php");
  		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

  		lang_set_active_language($t_lang_from_url[1]);
   
        $txt_name = lang_get('common_name');
        $txt_size = lang_get('schedule_restore_1');
        $txt_file_date = lang_get('schedule_restore_11');
        $txt_backup_date = lang_get('schedule_restore_12');
        
        
        
        $temp = '<table width="383px" cellspacing="0" cellpadding="0" style="table-layout:fixed">';
        $temp .= '<tr height="23px"><td style="border-right: 1px solid #CCCCCC;" width="125px">'.
                 '<img border="0" id="check_list_col" src="../images/comnso/cms_uncheck.gif" onclick=\'check_click(this.id)\'/>'.
                 '&nbsp<b><span id="lng_name">'.'name'.'</span></b></td>'.
                 '<td width="60" align="center" style="border-right: 1px solid #CCCCCC;"><b><span id="lng_size">'.'Size'.'</span></b></td>'.
                 '<td width="97" align="center" style="border-right: 1px solid #CCCCCC;"><b><span id="lng_filedate">'.'File Date'.'</span></b></td>'.
                 '<td width="97" align="center" style="border-right: 1px solid #FFFFFF;"><b><span id="lng_backupdate">'.'Backup Date'.'</span></b></td></tr>'.
                 '<tr><td colspan="4" bgcolor="#CCCCCC" height="1"></td></tr>'.
                 '<tr><td colspan="4" bgcolor="#FFFFFF" height="2"></td></tr>';
        
        if(($folder != "root") && $dbh){
            if($search == ""){
                $sqlList = 'SELECT *, intIndex AS bckIndex FROM tblBackupList WHERE intDir='.$folder.' ORDER BY strFile ASC, intGroup ASC, numBackDate DESC;';
            }else{
                $search = '%'.$search.'%';
                $sqlList = 'SELECT *, tblBackupList.intIndex AS bckIndex FROM tblBackupList, tblDirList WHERE strFile LIKE("'.$search.'") AND tblDirList.intIndex=tblBackupList.intDir ORDER BY strDir ASC, strFile ASC, intGroup ASC, numBackDate DESC;';
            }
            
            $result = $dbh->query($sqlList);

            if($result)
            {
                $intcnt = 0;
                $oldgrp = -1;
                while($row = $result->fetch()){
                    $index = $row['bckIndex'];
                    $file  = $row['strFile'];
		            if($oldgrp != $row['intGroup']){
		                $temp .= '<tr><td colspan="4" height="3"></td></tr>';
		                
		                // 검색식에 의한 결과는 폴더를 보여준다.
                        if($search != ""){
                            $temp .= '<tr><td colspan="4"><img src="../images/comnso/cms_folder.gif" border="0">&nbsp;';
                            $temp .= $row['strDir'];
                            $temp .= '</td></tr>';
                        }
                        $intcnt++;		                
		            }

                    $temp .= '<tr>';
                    $temp .= '<td nowrap valign="top" style="text-overflow: ellipsis; overflow: hidden;">';
                    
                    if($oldgrp == $row['intGroup']){
                        $temp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                        $temp .= '<img border="0" key="'.$index.'" id="check_listis'.$index.'" src="../images/comnso/cms_uncheck.gif" onclick=\'check_click(this.id)\'/>';
                    }else{
                        $temp .= '<img border="0" key="'.$index.'" id="check_listit'.$index.'" src="../images/comnso/cms_uncheck.gif" onclick=\'check_click(this.id)\'/><img border="0" id="image'.$index.'" src="../images/comnso/cms_file.gif"/>';
                    }
		            $temp .= '<b><span id="'.$index.'" title="'.$file.'">'.$file.'</span></b></td>';
		            $temp .= '<td nowrap align="right">'.$this->getfilesize($row['numFileSize']).'&nbsp</td>';
		            $temp .= '<td nowrap>'.$this->getfiledate($row['numFileDate']).'</td>';
		            $temp .= '<td nowrap>'.$this->getfiledate($row['numBackDate']).'</td>';
		            $temp .= '</tr>';
		            
		            // 해당 그룹에 정보를 얻는다.
		            $oldgrp = $row['intGroup'];
		            
		            if($intcnt>1000){
		                $temp .= '<tr><td colspan="4">Too many files...</td></tr>';
		                break;
		            }
                }
            }
        }
        $temp .= '</table>';
    
		return htmlentities($temp, ENT_QUOTES, "UTF-8");
	}	
}
?>