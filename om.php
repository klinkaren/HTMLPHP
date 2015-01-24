<?php
include("incl/config.php");
$title = "BMO - Om Begravningsmuseum online";
$pageId = "om";
include("incl/header.php");?>

<!-- The actual content -->
<div id="content">
	<?php getArticle("4", "full"); ?>
	<article>
	<a href="http://viktorkjellberg.com"><img class="right roundcorners space" src="img/me_small.png" alt="Bild på Viktor Kjellberg"></a>
	  <h2>Om webbdesignern</h2>


	  <p>Denna webbplats har skapats av Viktor Kjellberg som slutprojekt i kursen <i class="title">Databaser, HTML, CSS och 
	  skriptbaserad PHP-programmering</i> läst vid <a href="http://www.bth.se">Blekinge Tekniska Högskola</a> höstterminen 2014. Viktor är en IT-intresserad ekonom 
	  från <a href="http://www.hallstahammar.se" target="_blank">Hallstahammar</a> som efter studier inom ekonomi 
	  vid <a href="http://www.uu.se" target="_blank">Uppsala Universitet</a> och 
	  <a href="http://www.ucsd.edu" target="_blank">University of California</a> nu läser fristående kurser inom datavetenskap. </p>
	  <p>Mer info om Viktor hittar ni på <a href="http://www.viktorkjellberg.com">www.viktorkjellberg.com</a></p>

	</article>
</div>

<?php include("incl/footer.php"); ?>