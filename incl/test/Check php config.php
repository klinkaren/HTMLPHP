<h1>PHP config test</h1>
<?php
if(class_exists('PDO') && in_array("sqlite", PDO::getAvailableDrivers())) {
    echo "<p style='color:green'>sqlite PDO driver IS enabled";
} else {
    echo "<p style='color:red'>sqlite PDO driver IS NOT enabled";
}