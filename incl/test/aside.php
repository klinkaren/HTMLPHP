<!-- Aside -->
<?php
//$working_directory = basename(getcwd());
//echo '<h2>'.$working_directory.'</h2>';
echo '<h2>'.getCurrentFolder().'</h2>';
?>

<?php

// Returns list of links to files in test directory 
if ($handle = opendir('./incl/test/')) {
    while (false !== ($entry = readdir($handle))) {
        
		// Exception for default and aside.
		if ($entry != "." && $entry != ".." && $entry != "aside.php" && $entry != "default.php" && $entry != "jetty.sqlite" && $entry != "jetty1.sqlite" && $entry != "jetty2.sqlite") {
				$entry = substr($entry,0,$entry-4); //removes last 4 characters (.php) Source: http://www.thestudentroom.co.uk/showthread.php?t=426784
				$title = str_replace('-',' ',$entry);
				//$entry = htmlentities($entry);
				//$entry = utf8_encode($entry);
				//$entry = utf8_decode($entry);
				echo (' :: <a href="test.php?p='.($entry).'">'.$title.'</a><BR>');  //prints link			
        }
		
    }
    closedir($handle);
}
?>

<!-- First version
	<p><a href="test.php?p=kmom03_get">Get</a></p>
	<p><a href="test.php?p=kmom03_getform">Get Form</a></p>
	<p><a href="test.php?p=kmom03_postform">Post Form</a></p>
	<p><a href="test.php?p=kmom03_validate">Validera</a></p>
-->
