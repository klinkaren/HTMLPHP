<?php
	$maxlength = 30;
?>
<aside class="right">
<div class="widget">
	<h1>Nya artiklar</h1>
	<?php 
		$query = 'SELECT * FROM ARTICLE WHERE category="article" ORDER BY pubdate DESC LIMIT 3;';
		$res = getData($query);
		foreach ($res as $item) {
			echo '<p><a href="artiklar.php?p='.$item['id'].'">'.adjustStrLength($item['title'], $maxlength).'</a><br>'.$item['pubdate'].'</p>';
		}
	?>
</div>
<div class="widget">
	<h1>BMO i pressen</h1>
	<?php 
		$query = 'SELECT * FROM Media ORDER BY pubdate DESC LIMIT 5;';
		$res = getData($query);
		foreach ($res as $item) {
			echo '<p><a href="'.$item['link'].'" target="_blank">'.adjustStrLength($item['title'], $maxlength).'</a><br>'.adjustStrLength($item['source'], $maxlength).', '.$item['pubdate'].'</p>';
		}
	?>
</div>
</aside>
