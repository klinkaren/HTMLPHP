<?php
include("incl/config.php"); 
$title = "BMO - Artiklar"; 
$pageId = "artiklar";
$asideLinkLenght=25; // Maximim length of Links in aside. 
include("incl/header.php");

// Check if the url contains a querystring with a page-part
if(isset($_GET["p"])) 
{
	$p = $_GET["p"];
}

// Check if the url contains a querystring with a search-part
if(isset($_GET["search"])) 
{
	$searchTerm = $_GET["search"];
}

?>

<div id="content">
	<!-- SIDEBAR -->
	<!-- Prints all articles titles and links em to the articles -->
	<aside class="right">
		<nav class="sidebar">

			<!-- Search box -->
		    <p>
		        <form>
		        	<input id="input" class="text searchbox" type="text" placeholder="Sök efter artikel"name="search" size="5">
		        </form>
		    </p>  
			
			<!-- Adds links to all articles -->
			<ul>
				<li class="header">Artiklar</li>
				<?php
				$query = 'SELECT * FROM Article WHERE category="article";';
				$res = getData($query); 
				foreach($res as $object)
				{
					// Adds class=selected if the link is being dispayed 
					if (isset($_GET["p"]) && ($object['id'] == $p)){
						echo '<li class="selectedItem">';
					} else {
						echo '<li>';
					}
					$title=adjustStrLength($object['title'], $asideLinkLenght);
					echo '<a title="'.$object['title'].'" href="?p='.$object['id'].'">'.$title.'</a></li>';
				}?>
			</ul>
		</nav>
	</aside>


	<?php 
		// ARTICLES
		// Check if the url contains a querystring with a page-part
		// If so, shows page. Else shows a page with short intros 
		// and links to all other pages.  
		if(isset($_GET["p"])){
			getArticle($p, "left");
			getRelatedObjects($p);
		}elseif(isset($_GET["search"])){
			generateSearchResult("Article", $searchTerm);
		}else{
			getArticleIntros();
		}
	?>




</div>

<?php include("incl/footer.php"); ?>