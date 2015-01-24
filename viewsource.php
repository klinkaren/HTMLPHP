<?php 
include("incl/config.php");

if(userIsAuthenticated()):

	$title = "Källkod"; 
	$pageId = "source";

	// Include code from source.php to display sourcecode-viewer
	$sourceBasedir=dirname(__FILE__);
	$sourceNoEcho=true;
	include("src/source.php");
	$pageStyle=$sourceStyle;
	include("incl/header.php"); 
	?>

	<!-- Sidans/Dokumentets huvudsakliga innehåll -->
	<div id="content">
		<article>
			<?php echo "$sourceBody"; ?>
		</article>
	</div>

	<?php include("incl/footer.php"); ?>

<?php else: 
	ob_start(); // ensures anything dumped out will be caught

	$url = 'login.php';

	// clear out the output buffer
	while (ob_get_status()) 
	{
	    ob_end_clean();
	}

	// no redirect
	header( "Location: $url");
endif; ?>  

