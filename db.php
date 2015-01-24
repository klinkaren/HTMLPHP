<?php
include("incl/config.php");
if(userIsAuthenticated())
{
	$title = "BMO: Databashantering"; //changes page title (found in header.php)
	$pageId = "db";

	// Path to the SQLite database file

	$dbPath = dirname(__FILE__) . "/incl/data/bmo.sqlite";

	// Check if the url contains a querystring with a page-part.
	$p = null;
	if(isset($_GET["p"])) 
	{
	  $p = $_GET["p"];
	}


	// Is the page known?
	$path = "incl/db";
	$file = null;
	switch($p) 
	{

		// Firstpage
			case "createfirstpage":
			{
				$pageTitle   = "Skapa text";
				$file        = "createfirstpage.php";
			}
			break;
			
			case "editfirstpage":
			{
				$pageTitle   = "Editera text";
				$file        = "editfirstpage.php";
			}
			break;
			
			case "deletefirstpage":
			{
				$pageTitle   = "Ta bort text";
				$file        = "deletefirstpage.php";
			}
			break;

		// Media
			case "createmedia":
			{
				$pageTitle   = "Skapa text";
				$file        = "createmedia.php";
			}
			break;
			
			case "editmedia":
			{
				$pageTitle   = "Editera text";
				$file        = "editmedia.php";
			}
			break;
			
			case "deletemedia":
			{
				$pageTitle   = "Ta bort text";
				$file        = "deletemedia.php";
			}
			break;

		// ARTICLES
			case "createarticle":
			{
				$pageTitle   = "Skapa artikel";
				$file        = "createarticle.php";
			}
			break;
			
			case "editarticle":
			{
				$pageTitle   = "Editera artikel";
				$file        = "editarticle.php";
			}
			break;
			
			case "deletearticle":
			{
				$pageTitle   = "Ta bort en artikel";
				$file        = "deletearticle.php";
			}
			break;

		// OBJECTS
			case "createobject":
			{
				$pageTitle   = "Skapa objekt";
				$file        = "createobject.php";
			}
			break;
			
			case "editobject":
			{
				$pageTitle   = "Editera objekt";
				$file        = "editobject.php";
			}
			break;
			
			case "deleteobject":
			{
				$pageTitle   = "Ta bort ett objekt";
				$file        = "deleteobject.php";
			}
			break;
		  
		// DEFAULT
			default:
			{
				// FIX THIS LATER TO DEFAULT ---------------------------------------------------------------------!!!
				$pageTitle   = "Default";
				$file        = "default.php";
			}
	}


	?>

	<?php include("incl/header.php"); ?>

	<div id="content">
		<aside class="right" style="width:20%">
			<?php
				include('incl/db/aside.php');
			?>
		</aside>
	  <article class="left">
	    <?php include("$path/$file"); ?>
	  </article>
	</div>
	<?php include("incl/footer.php"); ?>
<?php
} else {
	header('Location: login.php');
} ?>