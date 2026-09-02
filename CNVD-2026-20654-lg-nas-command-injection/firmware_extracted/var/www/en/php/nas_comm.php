<?php
//=======================================================//
//	Convert a folder/file name into available form for shell command	//
//=======================================================//

function encode_filename_xml($filename){
	$characters = array( '&' , "'" , '<' , '>' , '"' );
	$replace_characters = array( '&amp;' , '&apos;' , '&lt;' , '&gt;' , '&quot;' );
	$result = str_replace($characters, $replace_characters , $filename);
	return $result;
}


function encode_filename($folder_name) {
	$characters = array( '`' , '!' , '@' , '$' , '^' , '&' , '(' , ')' , '=' , '[' , ']' , '{' , '}' , ',' , "'" , ';' , ' ' );
	$replace_characters = array( '\`' , '\!' , '\@' , '\$' , '\^' , '\&' , '\(' , '\)' , '\=' , '\[' , '\]' , '\{' , '\}' , '\,' , "\'" , '\;' , '\ ' );
	$result = str_replace($characters, $replace_characters , $folder_name);
	return $result;
}
?>
