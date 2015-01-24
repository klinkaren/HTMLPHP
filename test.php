<?php 
include("incl/config.php");
include("incl/test-functions.php");
$title = "Test"; //changes page title (found in header.php)
$pageId = "test";

// Sets initial values.
$path = "incl/test";
$p = "default";

// Check if the url contains a querystring with a page-part.
if(isset($_GET["p"])) 
{
	$p = $_GET["p"];
}

// Checks if session should be destroyed and destroys it if so.
if($p=="Forstor-SESSION"){
destroySession();
}

// Holds the full page name.
$file = $p.".php"; 
include("incl/header.php"); 

?>

<!-- The actual content -->
<div id="content">
	<aside class="left">
		<?php include("$path/aside.php");?>
	</aside>
		
	<article class="right justify-para">
		<?php 
			if(LinkInDir($file, $path) && $file!="aside.php"){
				include($path."/".$file);
			}else{
				echo '<p>Filen kunde inte hittas. Vänligen kontrollera sökvägen.<p>';
				include($path."/default.php"); 
			}
		?>
	</article>
</div>
<?php include("incl/footer.php"); ?>