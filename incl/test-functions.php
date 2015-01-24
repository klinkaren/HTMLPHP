<?php

// Checks if requested file ($link) exists in specified directory ($path).
// Need a $link to check and a $path to a folder to search for it.
// Returns TRUE if file is found, FALSE if not.
function LinkInDir($link, $path){

	// Removes slashes and double dots.
	$trimmed = str_replace('/','',$link);
	$trimmed = str_replace('..','',$trimmed);
	$trimmed = stripslashes($trimmed);
	//$trimmed = utf8_decode($trimmed);
	$filename = $path.'/'.$trimmed;
	if(file_exists($filename)){
		return true;
	} else {
		return false;
	}
}