<?php
include("incl/config.php"); 
$title = "BMO - Objekt"; 
$pageId = "objekt";
$asideLinkLenght=25; // Maximim length of Links in aside. 
include("incl/header.php");

// Check if the url contains a querystring with a page-part
if(isset($_GET["p"])) 
{
	$p = strip_tags($_GET["p"]);
}
// Check if the url contains a querystring with a category-part
if(isset($_GET["category"])) 
{
	$category = strip_tags($_GET["category"]);
}

// Check if the url contains a querystring with a search-part
if(isset($_GET["search"])) 
{
	$searchTerm = strip_tags($_GET["search"]);
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
		        	<input id="input" class="searchbox" type="text" placeholder="Sök efter objekt" name="search">
		        </form>
		    </p>  

		    <!-- Adds links to all pages and subpages if category selected -->
			<ul>
				<li class="header">Kategori</li>
				<?php

				$query = 'SELECT DISTINCT category FROM Object;';
				$res = getData($query); 
				foreach($res as $item)
				{
					// For a choosen category:
					if ((isset($_GET["category"])) && ($item['category'] == $category)){
						
						// add tag to highlight choosen option with css
						echo '<li class="selectedItem">';
						$title=adjustStrLength($item['category'], $asideLinkLenght);
						echo '<a title="'.$item['category'].'" href="?category='.$item['category'].'">'.$title.'</a></li>';

						// populate chosen category with objects 
						$query = 'SELECT * FROM Object WHERE category = "'.$item['category'].'";';
						$res2 = getData($query);
						foreach($res2 as $object)
						{
							if ((isset($_GET["p"])) && ($object['id'] == $p)){
								echo '<li class="selectedSubItem">';
							}else{
								echo '<li class="subItem">';
							}

							// Remove category from title string if in it.
							$title=removeSubstringFromString($object['category'], $object['title']);
							
							// Shorten if too long to fit.
							$title=adjustStrLength($title, $asideLinkLenght);
							echo '<a title="'.$object['title'].'" href="?category='.$object['category'].'&p='.$object['id'].'">'.$title.'</a></li>';		

						}

					} else {
						echo '<li>';
						$title=adjustStrLength($item['category'], $asideLinkLenght);
						echo '<a title="'.$item['category'].'" href="?category='.$item['category'].'">'.$title.'</a></li>';
					}
					
				}?>
			</ul>
			<a href="?p=all">Visa alla objekt</a>
		</nav>
	</aside>


	<?php 
		// Handles creation of articles containing containing objects. 
		// 
		if(isset($p)) 
		// If a certain page is asked for, shows the corresponding object.  
		{
			getObject($p);
		}
		elseif(isset($searchTerm))
		{
			generateSearchResult("Object", $searchTerm);
		}
		else
		{
			if(isset($category)){
				putObjects($category, "intro");
			}else{
				?>
				<article class="left descriptive">
					<h1>Objekt</h1>
					<p>Denna sektion visar BMO's objekt, som är ett urval av de objekt som går att beskåda
					på plats i museumet i Ljungby. </p>
					<p>För att se objekten, använd sidomenyn och välj en kategori eller välj att visa alla objekt. Det är också möjligt att söka efter objekt.</p>
				</article>
				<?php		
				getRandomObjects(3);
			}
		}
	?>




</div>

<?php include("incl/footer.php"); ?>
