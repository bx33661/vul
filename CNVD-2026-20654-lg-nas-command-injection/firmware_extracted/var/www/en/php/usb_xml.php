<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
//include "../php/msg_illegal_access.php";
echo '-99';
die();
}
session_write_close();


include "../inc/lcdmsg.php";

$act = $_POST['act'];

if($act == "new"){
    xml_save_new();
}else if($act == "modify"){
    xml_save_modify();
}else if($act == "delete"){
    xml_delete();
}else if($act == "read")
{
    read_xml();
}else if($act == "sync")
{
	/* swkim 2008-11-16
	$tasknum = $_POST['task_number'];
	msgjob('add','Syncing USB...');
	cms_sync($tasknum);
	msgjob('remove','Syncing USB...');
	*/
}

function read_xml()
{
    $xmlfile="/etc/cms/cmssync.xml";
    $taskcnt = load_xml($params, $xmlfile);
    $ret=to_array($params);
    header('Content-Type: text/html; charset=utf-8');
    echo $ret;
}

function to_array($params)
{
    $idx=0;
    $strrtn;
    while(1){
        $task;
        $task = "TASK".$idx;
        if(!$params[$task]){
            break;
        }
        $idx++;
        if($idx>999){
            break;
        }
        
        $strrtn .= "DESCRIPTION;".$params[$task]['DESCRIPTION']."|";
        $strrtn .= "USER;".$params[$task]['USER']."|";
        $strrtn .= "NAME;".$params[$task]['NAME']."|";
        $strrtn .= "CTRLNUM;".$params[$task]['CTRLNUM']."|";
        $strrtn .= "SRCDEF;".$params[$task]['SRC']['SRCDEF']."|";
        $strrtn .= "SRCPATH;".$params[$task]['SRC']['SRCPATH']."|";
        $strrtn .= "DSTDEF;".$params[$task]['DST']['DSTDEF']."|";
        $strrtn .= "DSTPATH;".$params[$task]['DST']['DSTPATH']."|";
        $strrtn .= "CRTFLD_DATE;".$params[$task]['DST']['CRTFLD_DATE']."|";        
        $strrtn .= "INCLUDE;".str_replace(";","/",$params[$task]['FILTER']['INCLUDE'])."|";
        $strrtn .= "EXCLUDE;".str_replace(";","/",$params[$task]['FILTER']['EXCLUDE'])."|";
        $strrtn .= "CYCLE;".$params[$task]['SCHEDULE']['CYCLE']."|";
        $strrtn .= "WEEK;".$params[$task]['SCHEDULE']['WEEK']."|";
        $strrtn .= "DATE;".$params[$task]['SCHEDULE']['DATE']."|";
        $strrtn .= "TIME;".$params[$task]['SCHEDULE']['TIME']."|";
        $strrtn .= "USBATT;".$params[$task]['SCHEDULE']['USBATT']."|";
        $strrtn .= "DIRECTION;".$params[$task]['DIRECTION']."\n";
    }
    return $strrtn;
}

function load_xml(&$params, $file)
{
	$xml_parser = xml_parser_create("UTF-8");

	if(!file_exists($file))
	{
		return;
	}

	if (!($fp = fopen($file, "r"))) 
	{
		die("could not open XML input");
	}

	// xml \xED뙆\xEC씪\xEC쓣 \xEC씫\xEC뼱\xEC삩\xEB떎.
	$data = fread($fp, filesize($file));
	fclose($fp);

	xml_parse_into_struct($xml_parser, $data, $vals, $index);
	xml_parser_free($xml_parser);

	$level = array();

	$taskcnt = 0;
	foreach ($vals as $xml_elem) 
	{
		if ($xml_elem['type'] == 'open') 
		{
			if($xml_elem['tag'] == 'TASK')
			{
				if($xml_elem['level'] == 2)
				{
					$level[$xml_elem['level']] = "TASK".$taskcnt;
					$taskcnt++;

					if (array_key_exists('attributes', $xml_elem)) 
					{
						if (array_key_exists('DESCRIPTION', $xml_elem['attributes'])) 
						{
							$start_level = 2;
							$php_stmt = '$params';
							while($start_level <= $xml_elem['level']) 
							{
								$php_stmt .= '[$level['.$start_level.']]';
								$start_level++;
							}
							$php_stmt .= '[\'DESCRIPTION\'] = $xml_elem[\'attributes\'][\'DESCRIPTION\'];';
							eval($php_stmt);
						}
					}
					continue;
				}
			}

			$level[$xml_elem['level']] = $xml_elem['tag'];
		}

		if ($xml_elem['type'] == 'complete') 
		{
			$start_level = 2;
			$php_stmt = '$params';
			while($start_level < $xml_elem['level']) 
			{
				$php_stmt .= '[$level['.$start_level.']]';
				$start_level++;
			}
			$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
			eval($php_stmt);
		}
	}
	return $taskcnt;
}

function xml_save_new()
{
    $xmlfile = "/etc/cms/cmssync.xml";
    $xmlobj = DOMDocument::Load($xmlfile);
    if(!$xmlobj){
        $xmlobj = new DomDocument('1.0', 'UTF-8');
        $backup = $xmlobj->createElement("SYNC_SETTING");
        $backup->setAttribute("Description", "Backup Setting Infomation");
        $xmlobj->appendChild($backup);
    }else{
        $bcklist= $xmlobj->getElementsByTagName("SYNC_SETTING");
        $backup = $bcklist->item(0);    
    }

    $task   = $xmlobj->createElement("TASK");
    $task->setAttribute("Description", $_POST['desc']);
    
    $user   = $xmlobj->createElement("USER", $_POST['user']);
    $name   = $xmlobj->createElement("NAME", $_POST['name']);
    $ctrlnum   = $xmlobj->createElement("CTRLNUM", $_POST['ctrlnum']);
    
    $src        = $xmlobj->createElement("SRC");
    $srcdef     = $xmlobj->createElement("SRCDEF", $_POST['srcdef']);
    $srcpath    = $xmlobj->createElement("SRCPATH", $_POST['srcpath']);

    $dst        = $xmlobj->createElement("DST");
    $dstdef     = $xmlobj->createElement("DSTDEF", $_POST['dstdef']);
    $dstpath    = $xmlobj->createElement("DSTPATH", $_POST['dstpath']);
    $crtfld_date= $xmlobj->createElement("CRTFLD_DATE", $_POST['crtfld_date']);
    
    $filter     = $xmlobj->createElement("FILTER");
    $include    = $xmlobj->createElement("INCLUDE", $_POST['include']);
    $exclude    = $xmlobj->createElement("EXCLUDE", $_POST['exclude']);
    
    $schedule   = $xmlobj->createElement("SCHEDULE");
    $cycle      = $xmlobj->createElement("CYCLE", $_POST['cycle']);
    $week       = $xmlobj->createElement("WEEK", $_POST['week']);
    $date       = $xmlobj->createElement("DATE", $_POST['date']);
    $time       = $xmlobj->createElement("TIME", $_POST['time']);
    $usbatt     = $xmlobj->createElement("USBATT", $_POST['usbatt']);
    
    $direction  = $xmlobj->createElement("DIRECTION", $_POST['direc']);
    
    $src->appendChild($srcdef);
    $src->appendChild($srcpath);
    
    $dst->appendChild($dstdef);
    $dst->appendChild($dstpath);
    $dst->appendChild($crtfld_date);
    
    $filter->appendChild($include);
    $filter->appendChild($exclude);
    
    $schedule->appendChild($cycle);
    $schedule->appendChild($week);
    $schedule->appendChild($date);
    $schedule->appendChild($time);
    $schedule->appendChild($usbatt);
    
    $task->appendChild($user);
    $task->appendChild($name);
    $task->appendChild($ctrlnum);
    $task->appendChild($src);
    $task->appendChild($dst);
    $task->appendChild($filter);
    $task->appendChild($schedule);
    $task->appendChild($direction);
    
    $backup->appendChild($task);
    
    $fp = fopen($xmlfile, "wt");
    if($fp){
        fwrite($fp, $xmlobj->saveXML());
        fclose($fp);
    }
}

function xml_save_modify()
{
    $taskname = $_POST['task'];
    $xmlfile = "/etc/cms/cmssync.xml";
    $xmlobj = DOMDocument::Load($xmlfile);
    if(!$xmlobj){
        echo "file not found!";
        return;
    }
    
    $bcklist= $xmlobj->getElementsByTagName("SYNC_SETTING");
    $backup = $bcklist->item(0);      
    
    $tasklist= $xmlobj->getElementsByTagName("TASK");
    foreach($tasklist as $node){
        $names=$node->getElementsByTagName("NAME");
        if($names){
            $name = $names->item(0);
            if($name->nodeValue == $taskname){
                $taskold = $node;
                break;
            }            
        }
    }
    /*$bcklist= $xmlobj->getElementsByTagName("SYNC_SETTING");
    $backup = $bcklist->item(0);      
    
    $tasklist= $xmlobj->getElementsByTagName("TASK");
    foreach($tasklist as $node){
        $names=$node->getElementsByTagName("NAME");
        if($names){
            $name = $names->item(0);
            //echo $name->nodeValue;
            if($name->nodeValue == $taskname){
                $taskold = $node;
                //var_dump($taskold);
                break;
            }            
        }
    }*/
    if(!$taskold){
        echo $taskname."not found!";
        return;
    }
    $task   = $xmlobj->createElement("TASK");
    $task->setAttribute("Description", $_POST['desc']);
    
    $user   = $xmlobj->createElement("USER", $_POST['user']);
    $name   = $xmlobj->createElement("NAME", $_POST['name']);
    $ctrlnum   = $xmlobj->createElement("CTRLNUM", $_POST['ctrlnum']);
    
    $src        = $xmlobj->createElement("SRC");
    $srcdef     = $xmlobj->createElement("SRCDEF", $_POST['srcdef']);
    $srcpath    = $xmlobj->createElement("SRCPATH", $_POST['srcpath']);

    $dst        = $xmlobj->createElement("DST");
    $dstdef     = $xmlobj->createElement("DSTDEF", $_POST['dstdef']);
    $dstpath    = $xmlobj->createElement("DSTPATH", $_POST['dstpath']);
    $crtfld_date= $xmlobj->createElement("CRTFLD_DATE", $_POST['crtfld_date']);
    
    $filter     = $xmlobj->createElement("FILTER");
    $include    = $xmlobj->createElement("INCLUDE", $_POST['include']);
    $exclude    = $xmlobj->createElement("EXCLUDE", $_POST['exclude']);
    
    $schedule   = $xmlobj->createElement("SCHEDULE");
    $cycle      = $xmlobj->createElement("CYCLE", $_POST['cycle']);
    $week       = $xmlobj->createElement("WEEK", $_POST['week']);
    $date       = $xmlobj->createElement("DATE", $_POST['date']);
    $time       = $xmlobj->createElement("TIME", $_POST['time']);
    $usbatt     = $xmlobj->createElement("USBATT", $_POST['usbatt']);
    
    $direction  = $xmlobj->createElement("DIRECTION", $_POST['direc']);
    
    $src->appendChild($srcdef);
    $src->appendChild($srcpath);
    
    $dst->appendChild($dstdef);
    $dst->appendChild($dstpath);
    $dst->appendChild($crtfld_date);
    
    $filter->appendChild($include);
    $filter->appendChild($exclude);
    
    $schedule->appendChild($cycle);
    $schedule->appendChild($week);
    $schedule->appendChild($date);
    $schedule->appendChild($time);
    $schedule->appendChild($usbatt);
    
    $task->appendChild($user);
    $task->appendChild($name);
    $task->appendChild($ctrlnum);
    $task->appendChild($src);
    $task->appendChild($dst);
    $task->appendChild($filter);
    $task->appendChild($schedule);
    $task->appendChild($direction);
    
    $backup->replaceChild($task, $taskold);
    
    $fp = fopen($xmlfile, "wt");
    if($fp){
        fwrite($fp, $xmlobj->saveXML());
        fclose($fp);
    }
}

function xml_delete()
{
    $taskname = $_POST['task'];
    $xmlfile = "/etc/cms/cmssync.xml";
    $xmlobj = DOMDocument::Load($xmlfile);
    if(!$xmlobj){
        echo "file not found!";
        return;
    }
    
    $bcklist= $xmlobj->getElementsByTagName("SYNC_SETTING");
    $backup = $bcklist->item(0);      
    
    $tasklist= $xmlobj->getElementsByTagName("TASK");
    foreach($tasklist as $node){
        $names=$node->getElementsByTagName("NAME");
        if($names){
            $name = $names->item(0);
            //echo $name->nodeValue;
            if($name->nodeValue == $taskname){
                $taskold = $node;
                var_dump($taskold);
                break;
            }            
        }
    }
    if(!$taskold){
        echo $taskname." not found!";
        return;
    }
    
    $backup->removeChild($taskold);
        
    $fp = fopen($xmlfile, "wt");
    if($fp){
        fwrite($fp, $xmlobj->saveXML());
        fclose($fp);
    }
}

function cms_sync($tasknum)
{
	/* swkim 2008-11-16
    $cmd = "sudo /usr/bin/cmssync -s /usb -d /mnt/fs/Vol1/system/Backup/USB -p php -o ".$tasknum;
	exec($cmd);
	*/
	
}
?>