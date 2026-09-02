<?php

$path = $_POST["path"];

$regex = "/^\./";
if($dir_handle = opendir($path) )
{
	while(false!== ($file = readdir($dir_handle) ) )
	{
		if(preg_match($regex, $file) )
		{
			//echo "HIDDEN FILE\n";
		}else
		{
			if(is_dir($path.$file) )
			{
				$size = dir_size($path.$file);
				$file .= (":".$size);
				$file .= ":d";
			}else
			{
				$size = filesize($path.$file);	// to do
				$file .= (":".$size);
				$file .= ":f";
			}
			echo "$file\n";
		}
	}
	closedir($dir_handle);
}

function dir_size( $dir ) 
{ 
    if( !$dir or !is_dir( $dir ) ) 
    { 
        return 0; 
    } 
    $ret = 0; 
    $sub = opendir( $dir ); 
    while( $file = readdir( $sub ) ) 
    { 
        if( is_dir( $dir . '/' . $file ) && $file !== ".." && $file !== "." ) 
        { 
            $ret += dir_size( $dir . '/' . $file ); 
            unset( $file ); 
        } 
        elseif( !is_dir( $dir . '/' . $file ) ) 
        { 
            $stats = stat( $dir . '/' . $file ); 
            $ret += $stats['size']; 	// to do

            unset( $file ); 
        } 
    } 
    closedir( $sub ); 
    unset( $sub ); 
    return $ret; 
}
function byteConvert($bytes)
{
	$s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
	if($bytes == 0)
	{
		$e = 0;
	}else
	{
		$e = floor(log($bytes)/log(1024));
	}
	
	return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
}

?>