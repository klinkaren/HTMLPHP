<?php
$filename = "x2. Madonna, Maria och Jesus-barnet";
echo 'String in: '.$filename.'<br>';
$filename = ucfirst(preg_replace("/[^a-öA-Ö0-9, ]/", "", $filename));
echo 'String out: '.$filename.'<br><br>';

$filename2 = ", svart, änglar, 4 stycken";
echo 'String in: '.$filename2.'<br>';
$filename2 = ucfirst(preg_replace("/[^a-öA-Ö0-9, ]/", "", $filename2));
echo 'String out: '.$filename2.'<br><br>';

$filename3 = ", tro hopp och kärlek";
echo 'String in: '.$filename3.'<br>';

$filename3 = ucfirst(trim(preg_replace("/[^a-öA-Ö0-9 ]/", "", $filename3)));

echo 'String out: '.$filename3.'<br><br>';

?>