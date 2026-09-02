<?php
//=======================================================//
// Session Check
//=======================================================//
include ("../session/session_manage.php");
if ( sm_session_check_on_popup() == FALSE )
{
include "../php/msg_illegal_access.php";
die();
}
session_write_close();


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
}

function read_xml()
{
    $xmlfile="/etc/cms/cmsbackup.xml";
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
        
        $strrtn .= "NAME;".$params[$task]['NAME']."|";
        $strrtn .= "DESCRIPTION;".$params[$task]['DESCRIPTION']."|";
        $strrtn .= "SRCDEF;".$params[$task]['SRC']['SRCDEF']."|";
        $strrtn .= "SRCPATH;".$params[$task]['SRC']['SRCPATH']."|";
        $strrtn .= "INCLUDE;".str_replace(";","/",$params[$task]['FILTER']['INCLUDE'])."|";
        $strrtn .= "EXCLUDE;".str_replace(";","/",$params[$task]['FILTER']['EXCLUDE'])."|";
        $strrtn .= "CYCLE;".$params[$task]['SCHEDULE']['CYCLE']."|";
        $strrtn .= "WEEK;".$params[$task]['SCHEDULE']['WEEK']."|";
        $strrtn .= "DATE;".$params[$task]['SCHEDULE']['DATE']."|";
        $strrtn .= "TIME;".$params[$task]['SCHEDULE']['TIME']."|";
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

	// xml 파일을 읽어온다.
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
    $xmlfile = "/etc/cms/cmsbackup.xml";
    $xmlobj = DOMDocument::Load($xmlfile);
    if(!$xmlobj){
        $xmlobj = new DomDocument('1.0', 'UTF-8');
        $backup = $xmlobj->createElement("BACKUP_SETTING");
        $backup->setAttribute("Description", "Backup Setting Infomation");
        $xmlobj->appendChild($backup);
    }else{
        $bcklist= $xmlobj->getElementsByTagName("BACKUP_SETTING");
        $backup = $bcklist->item(0);    
    }

    $task   = $xmlobj->createElement("TASK");
    $task->setAttribute("Description", $_POST['desc']);
    
    $name   = $xmlobj->createElement("NAME", $_POST['name']);
    $src    = $xmlobj->createElement("SRC");
    $srcdef     = $xmlobj->createElement("SRCDEF", $_POST['srcdef']);
    $srcpath    = $xmlobj->createElement("SRCPATH", $_POST['srcpath']);
    $filter     = $xmlobj->createElement("FILTER");
    $include    = $xmlobj->createElement("INCLUDE", $_POST['include']);
    $exclude    = $xmlobj->createElement("EXCLUDE", $_POST['exclude']);
    $schedule   = $xmlobj->createElement("SCHEDULE");
    $cycle      = $xmlobj->createElement("CYCLE", $_POST['cycle']);
    $week       = $xmlobj->createElement("WEEK", $_POST['week']);
    $date       = $xmlobj->createElement("DATE", $_POST['date']);
    $time       = $xmlobj->createElement("TIME", $_POST['time']);
    $direction  = $xmlobj->createElement("DIRECTION", $_POST['direc']);
    
    $src->appendChild($srcdef);
    $src->appendChild($srcpath);
    $filter->appendChild($include);
    $filter->appendChild($exclude);
    $schedule->appendChild($cycle);
    $schedule->appendChild($week);
    $schedule->appendChild($date);
    $schedule->appendChild($time);
    
    $task->appendChild($name);
    $task->appendChild($src);
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
    $xmlfile = "/etc/cms/cmsbackup.xml";
    $xmlobj = DOMDocument::Load($xmlfile);
    if(!$xmlobj){
        echo "file not found!";
        return;
    }
    
    $bcklist= $xmlobj->getElementsByTagName("BACKUP_SETTING");
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
    
    if(!$taskold){
        echo $taskname."not found!";
        return;
    }
    
    $task   = $xmlobj->createElement("TASK");
    $task->setAttribute("Description", $_POST['desc']);
    
    $name   = $xmlobj->createElement("NAME", $_POST['name']);
    $src    = $xmlobj->createElement("SRC");
    $srcdef     = $xmlobj->createElement("SRCDEF", $_POST['srcdef']);
    $srcpath    = $xmlobj->createElement("SRCPATH", $_POST['srcpath']);
    $filter     = $xmlobj->createElement("FILTER");
    $include    = $xmlobj->createElement("INCLUDE", $_POST['include']);
    $exclude    = $xmlobj->createElement("EXCLUDE", $_POST['exclude']);
    $schedule   = $xmlobj->createElement("SCHEDULE");
    $cycle      = $xmlobj->createElement("CYCLE", $_POST['cycle']);
    $week       = $xmlobj->createElement("WEEK", $_POST['week']);
    $date       = $xmlobj->createElement("DATE", $_POST['date']);
    $time       = $xmlobj->createElement("TIME", $_POST['time']);
    $direction  = $xmlobj->createElement("DIRECTION", $_POST['direc']);
    
    $src->appendChild($srcdef);
    $src->appendChild($srcpath);
    $filter->appendChild($include);
    $filter->appendChild($exclude);
    $schedule->appendChild($cycle);
    $schedule->appendChild($week);
    $schedule->appendChild($date);
    $schedule->appendChild($time);
    
    $task->appendChild($name);
    $task->appendChild($src);
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
    $xmlfile = "/etc/cms/cmsbackup.xml";
    $xmlobj = DOMDocument::Load($xmlfile);
    if(!$xmlobj){
        echo "file not found!";
        return;
    }
    
    $bcklist= $xmlobj->getElementsByTagName("BACKUP_SETTING");
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
?>