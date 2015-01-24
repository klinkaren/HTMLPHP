<?php
// ===========================================================================================
//
// Filename: functions.php
//
// Description: Provide a set of functions to enable the website.
//
// Author: Viktor Kjellberg, web@viktorkjellberg.com
//


// --- DATABASE -------------------------------------------------------------------------------------
//

// Takes in sql-query, connects to database, retrieves info and returns result.
function getData($query){
	// path to database
	$dbPath = dirname(__FILE__) . "/../incl/data/bmo.sqlite";

	// Connect to database
	$db = new PDO("sqlite:$dbPath");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script

	// Get all items from database
	$stmt = $db->prepare($query);
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

//
// --- STRING HANDING -------------------------------------------------------------------------------
//

// Takes in a string and its maximum length. Cuts and add '...' if too long, then returns it.
function adjustStrLength($string, $len){
	if (strlen($string)>$len){
		$string=substr($string,0,$len-3);
		$string .="...";
	}
	return $string;
}


function removeSubstringFromString($subString, $fullString){

	// only modify if substring is not equal to fullstring
	if($subString!=$fullString){

		// delete substring
		$modifiedString=str_replace($subString, "", $fullString);

		// Replace all chars thats not a-ö, A-Ö or 0-9. 
		// Trim leading and trailing whitespaces.
		// Make first letter upper case.  
		$modifiedString=ucfirst(trim(preg_replace("/[^a-öA-Ö0-9 ]/", "", $modifiedString)));

		return $modifiedString;

	} else {
		return $fullString;
	}
}

//
// --- ARTICLES -------------------------------------------------------------------------------------
//

// Takes in the article number and position on layout (right/left/full) and prints the article
// IN: artile number, position of the article 
function getArticle($articleNumber, $position){
	$query = "SELECT * FROM Article WHERE id=".$articleNumber.";";
	$res = getData($query);

	foreach($res as $object)
	{	
		// Set article class, if right or left
		if ($position == "full") 
		{
			echo '<article>';
		} else {
			echo '<article class="'.$position.'">';
		}

		echo '<h1>'.$object['title'].'</h1>';

		// Only echo author and publish date if both exists
		if (($object['author'] != "") && ($object['pubdate'] != "")){
			echo '<div class="published">'.$object['author'].', publicerad '.$object['pubdate'].'</div>';
		}

		echo $object['content'];

		// Get prev/next navigation if article (not for category="about")
		if($object['category']=="article"){
			getPrevNextNavigation("Article", $articleNumber);
		}
		echo '</article>';	
	}
}

function getRandomObjects($numOfObjects){

	// get width of every object
	switch ($numOfObjects) {
		case '2':
			$width="half";
			break;
		case '3':
			$width="third";
			break;
		case '4':
			$width="fourth";
			break;
		case '5':
			$width="fifth";
			break;
		default:
			$width="third";
			break;
	}

	// connects to database and query for data
	$query = 'SELECT * FROM Object ORDER BY RANDOM() LIMIT '.$numOfObjects.';';
	$res = getData($query);

	// return article with up to three random related articles.
	echo '<article class="left">';
	echo '<div class="full">';
	$i = 0;
	foreach($res as $object)
	{
		$i = $i+1;
		// Makes the whole div a link.
		echo '<a title="'.$object['text'].'" href="objekt.php?p='.$object['id'].'&category='.$object['category'].'">';
			echo '<div class="'.$width.'">';
				echo '<div class="randomObject object'.$i.'">';

					// Places a cropped version of the objects image.
					echo '<img src="'.imageSize($object['image'], "cropped").'" alt="Bild av '.$object['title'].'">';
					

					// Remove category from title if in it.
					$title=removeSubstringFromString($object['category'], $object['title']);

					// Adjusts the string length if too long to fit.
					echo '<br>'.adjustStrLength($title,30);
				echo '</div>';
			echo '</div>';
		echo '</a>';
	}
	echo '<div class="full">';
	echo '<div class="infoText">Ett smakprov av de objekt som finns att se och läsa mer om.</div>';
	echo '</article>';
	
}

// Takes in an article number and prints up to three related objects if it has any.
// IN: article number
function getRelatedObjects($p){
	
	// checks if article has any related objects.
	switch ($p) {
		case '1':
			$category="Begravningskonfekt";
			break;
		case '2':
			$category="Minnestavla";
			break;
		case '3':
			$category="Pärlkrans";
			break;
		case '5':
			$category="Begravningsfest";
			break;
		default:
			$category="Nothing";
			break;
	}


	// Shows related objects if category is one which has any.
	if($category!="Nothing"){
		
		// connects to database and query for data
		$query = 'SELECT * FROM Object WHERE category="'.$category.'" ORDER BY RANDOM() LIMIT 3;';
		$res = getData($query);

		// return article with up to three random related articles.
		echo '<article class="left">';
		
		// Counter for foreach loop.
		$i = 0;
		
		// Creates the objects.
		foreach($res as $object)
		{
			// Adds one for each lap. Gives classnumber (i.e. object1) to objects. Makes reachable from css.
			$i = $i+1;

			// Makes the whole div a link.
			echo '<a title="'.$object['text'].'" href="objekt.php?p='.$object['id'].'&category='.$object['category'].'">';
				echo '<div class="third">';
					echo '<div class="relatedObject object'.$i.'">';

						// Places a cropped version of the objects image.
						echo '<img src="'.imageSize($object['image'], "cropped").'" alt="Bild av '.$object['title'].'">';
						

						// Remove category from title if in it.
						$title=removeSubstringFromString($category, $object['title']);

						// Adjusts the string length if too long to fit.
						echo '<br>'.adjustStrLength($title,30);
					echo '</div>';
				echo '</div>';
			echo '</a>';
		}
		echo '<div class="infoText">Objekt med en relation till artikeln. Klicka på ett objekt för att läsa mer om det.</div>';
		echo '</article>';
	}
}

// Prints intros to all articles.
function getArticleIntros(){
	$query = "SELECT * FROM Article WHERE category='article';";
	$res = getData($query);
	
	foreach($res as $object)
	{
		echo '<article class="left">';
		echo '<h2>'.$object['title'].'</h2>';

		// Finds start of firts paragraph
		$findme = '<p';
		$startPos = strpos($object['content'], $findme);

		// Checks if article is long enough for showing an intro
		if(strlen($object['content'])>$startPos+250){
			
			// Finds first dot after 200 characters from first paragraph
			$findme = '.';
			$endPos= strpos($object['content'], $findme, $startPos+300);

			// Prints the article content from first paragraph to first dot that appears after 200 characters.
			echo substr($object['content'], $startPos, $endPos-$startPos+1);
		} else {

			// if article is not long enough for intro, prints whole article
			echo $object['content'];
		}
		
		echo '<p class="readMore"><a href="?p='.$object['id'].'">Läs mer...</a></p>';
		echo '</article>';
	}
}

//
// --- OBJECTS -------------------------------------------------------------------------------------
//

// Takes in the article number and prints the article.
// IN: article number
function getObject($objectNumber){

	// Check if all objects have been asked for and if so calls another function.
	if($objectNumber=="all"){

		//call another function to put all objects.
		putObjects("all", "intro");
	
	}else{

		// put the asked for article
		$query = "SELECT * FROM Object WHERE id=".$objectNumber.";";
		$res = getData($query);

		foreach($res as $object)
			{	

				echo '<article class="left">';
				echo '<h2>'.$object['title'].'</h2>';
				echo '<a href="'.$object['image'].'"><img class="roundcorners" src="'.imageSize($object['image'], "large").'" alt="Bild av '.$object['title'].'"></a>';
				
				echo '<p>'.$object['text'].'</p>';
				echo '<p class="owner">Ägare: '.$object['owner'].'</p>';
				getPrevNextNavigation("Object", $objectNumber);
				echo '</article>';
			}
	}
}


// IN: Scope of the request (certain category or all) and layout (pics)
function putObjects($scope, $size){



	$res = getObjects($scope);

	if($size=="intro"){
		// Print the data
		foreach($res as $object){
			if ($scope!="all"){
				// Prepares page-part for prev/next-links
				$category='&category='.$object['category'];
			}
			echo '<article class="objectList left">';
			echo '<div class="object">';
			echo '<a href="?p='.$object['id'].'&category='.$object['category'].'"><img src="'.imageSize($object['image'], "cropped").'" alt="Bild av '.$object['title'].'"></a></div>';
			echo '<div><h3 class="objectlist">'.$object['title'].'</h3>';
			echo $object['text'];
			echo '</div></article>';
		}	
	}elseif ($size=="infobox") {
		foreach($res as $object)
		{
			// Makes the whole div a link.
			echo '<a title="'.$object['text'].'" href="objekt.php?p='.$object['id'].'">';
				echo '<div class="fourth">';
					echo '<div class="gallery">';

						// Places a cropped version of the objects image.
						echo '<img src="'.imageSize($object['image'], "cropped").'" alt="Bild av '.$object['title'].'">';
						

						// Remove category from title if in it.
						//$title=removeSubstringFromString($object['category'], $object['title']);

						// Adjusts the string length if too long to fit.
						echo '<br>'.adjustStrLength($object['title'],45);
						//echo '<br>'.$object['title'];
					echo '</div>';
				echo '</div>';
			echo '</a>';
		}
	}

}

// Creates navigation for pages depending on how many object per page to show.
// IN: Which page and how many objects to show per page
function getGalleryNavigation($page, $view){
	$res = getObjects("all");

	// Total number of objects in database.
	$numOfObjects = count($res);
	
	// Number of pages depending on how many objects to show per page.
	$numOfPages = ceil($numOfObjects/$view);

	// Print at which page we are and how many there is 
	echo '<div class="pagesinfo"> - sida '.$page.' av '.$numOfPages.' - </div>';

	// Generate links to all pages. Current page gets class to reach from css. 
	for ($i=1; $i <= $numOfPages; $i++) {
		if($i==$page){
			echo '<a class="current page" href="?view='.$view.'&amp;p='.($i).'">'.($i).'</a>';
		}else{
			echo '<a class="page" href="?view='.$view.'&amp;p='.($i).'">'.($i).'</a>';
		}
	}
}

// Counts how many objects there are in the database and then calls another 
// function with the full number of objects.
function putAllGalleryObjects(){
	
	// Gets objects from database.
	$res = getObjects("all");

	// Count the objects.
	$length = count($res);

	// Call putGalleryObject saying start from page one and show all there is.
	putGalleryObjects(1, $length);
}

// Puts cropped version of gallery objects with links to galleryview. 
// IN: Takes in which page to show and the length (decides how many objects to show) of the page.
function putGalleryObjects($page, $length){ 
	// Gets objects from database.
	$res = getObjects("all");

	$lightbox = rand();

	// Gets objects from database.
	$res = getObjects("all");

	// Generates starting position for loop.
	$start=($page-1)*$length;

	// Puts objects based on pagenumber ($page) and limit ($length).
	for ($i=$start; $i < ($start+$length); $i++) {
		
		// Only try to echo if there is something to echo.
		if(isset($res[$i])){

			// Makes the whole div a link.
			echo '<a title="'.$res[$i]['text'].'" href="'.imageSize($res[$i]['image'], "large").'" rel="lightbox['.$lightbox.']">';



				echo '<div class="fourth">';
					echo '<div class="gallery">';

						// Places a cropped version of the objects image.
						echo '<img src="'.imageSize($res[$i]['image'], "cropped").'" alt="Bild av '.$res[$i]['title'].'">';

						// Adjusts the string length if too long to fit.
						echo '<br>'.adjustStrLength($res[$i]['title'],55);

					echo '</div>';
				echo '</div>';
			echo '</a>';
		}
	}
}

// Gets reguested objects from database.
// IN: Scope of the request (certain category or all).
// OUT: Array containing data about the objects.
function getObjects($scope){

	// Get data from database. All data or just data for selected category (scope).
	if ($scope == "all"){
		$query = 'SELECT * FROM Object;';
	}else{
		$query = 'SELECT * FROM Object WHERE category="'.$scope.'";';
	}
	$res = getData($query);

	return $res;
}

// Creates string of all distinct categories, in table specified by caller, separated by |.
// IN: Table to get categories from
function getCategories($table){
	// Create list of existing categories
	$query = 'SELECT DISTINCT category FROM '.$table.' ORDER BY category;';
	$res = getData($query);
	$allCategories ="";
	foreach($res as $cat){
		$allCategories .= " | <i>" . $cat['category'] . "</i>";
	}

	// Remove first |
	$allCategories = substr($allCategories, 3);
	return $allCategories;
}

//
// --- IMAGES -------------------------------------------------------------------------------------
//

// Takes in an image-path and a size (small/medium/large).
// Returns path to image of requested size.
function imageSize($picture, $size){
	
	// Gets just the filename (basename) from path
	// Ex: gets 'picture.jpg' from 'img/bmo/picture.jpg'
	$filename=basename($picture);
	switch ($size) {
		case 'small':
			$path="img/bmo/80/";
			break;
		case 'medium':
			$path="img/bmo/250/";
			break;
		case 'large':
			$path="img/bmo/550/";
			break;
		case 'cropped':
			$path="img/bmo/crop/";
			break;
		default:
			$path="img/bmo/";
			break;
	}
	return($path.$filename);
}

// Checks if a given filename is located in /img/bmo
// IN: filename of a picture
// OUT: TRUE/FALSE
function pictureExists($file){
	$path = dirname(__FILE__) . "/../img/bmo";
	if(file_exists("$path/$file")){
		return TRUE;
	}else{
		return FALSE;
	}
}

// Created a resized and centrally cropped version of the source-file.
// IN: Int for max width, Int for max height, path to source file, path to destination, and quality (optional).
// Source: http://polyetilen.lt/en/resize-and-crop-image-from-center-with-php
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    // info to return to user.
    $message ="";
    
    // Only run if file doesn't yet exist. 
    if(!is_file($dst_dir)) {
    
	    $imgsize = getimagesize($source_file);
	    $width = $imgsize[0];
	    $height = $imgsize[1];
	    $mime = $imgsize['mime'];
	 
	    switch($mime){
	        case 'image/gif':
	            $image_create = "imagecreatefromgif";
	            $image = "imagegif";
	            break;
	 
	        case 'image/png':
	            $image_create = "imagecreatefrompng";
	            $image = "imagepng";
	            $quality = 7;
	            break;
	 
	        case 'image/jpeg':
	            $image_create = "imagecreatefromjpeg";
	            $image = "imagejpeg";
	            $quality = 80;
	            break;
	 
	        default:
	            $message .= "<p>Filen skalades inte då den inte är av korrekt format (Tillåtna format är jpeg, png och gif.)</p>";
	            return $message;
	            break;
	    }
	     
	    $dst_img = imagecreatetruecolor($max_width, $max_height);
	    $src_img = $image_create($source_file);
	     
	    $width_new = $height * $max_width / $max_height;
	    $height_new = $width * $max_height / $max_width;
	    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
	    if($width_new > $width){
	        //cut point by height
	        $h_point = (($height - $height_new) / 2);
	        //copy image
	        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
	    }else{
	        //cut point by width
	        $w_point = (($width - $width_new) / 2);
	        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
	    }
	     
	    $image($dst_img, $dst_dir, $quality);
	 
	    if($dst_img)imagedestroy($dst_img);
	    if($src_img)imagedestroy($src_img);
	    $message .= "<p>Skapade en beskuren version av filen.</p>";

	} else {
		$message .= "<p>En beskuren version finns redan.</p>";
	}
	return $message;
}
// Creates images of different sizes based on an image send in. 
// Images will be saved in seperate folders in /img/bmo/(image size).
// IN: filename of a jpeg-, png- or gif-file located in /img/bmo/  
function createImageSizes($file){
	
	// Saves info that will be returned.
	$message ="";

	// Rescale image (777) to these sizes ($scale) and stores them in own directory
	$scale = Array(550, 250, 80);
	$dir=dirname(__FILE__) . '/../img/bmo/';
	include($dir.'Class_Resize_Image.php');

	// Is GD enabled on the system?
	if(function_exists('gd_info')) {
		foreach($scale as $val) {

			//Checks if $val is a directory. Creates one if not.
			if(!is_dir($dir.$val)) {
				mkdir($dir.$val);
			}
			$image = new Resize_Image;
			$image->ratio 			= true; 
			$image->save_folder = $dir.$val."/";

			$pathParts = pathinfo("$dir/$file");
			$extension = isset($pathParts['extension']) ? strtolower($pathParts['extension']) : '-';

			if(is_file("$dir/$file") && $extension != 'php') {	
				$image->image_to_resize = "$dir/$file";
				$image->new_image_name =$pathParts['filename'];
				if(!is_file($image->save_folder . $file)) {
					$image->new_width 	= $val;
					$image->new_height 	= (float)$val * 0.75;			
					$process = $image->resize();
					if($process['result']) {
						$message .= "<p>({$process['new_file_path']}) skapades som ny bild.</p>";
					} else {
						$message .="<p>Lyckades inte skala om filen <code>$dir/$file</code>.</p>";
					}
				} else {
					$message .= "<p><code>{$image->save_folder}{$image->new_image_name} </code> finns redan.</p>"; 
				}
			}		
		}
	} else {
		$message .= "GD är inte ativerat.";
	}
	return $message;
}
// Creates different sizes of picture and a cropped version.
// IN: filename of a jpeg-, png- or gif-file located in /img/bmo/  
function createPictures($file){

	//Infomessage to return to user.
	$message ="<h3>Bildinfo</h3>";

	$path=dirname(__FILE__) . '/../img/bmo/';
	$image=basename($file);

	// Create cropped image
    $croppedImage=$path.'crop/'.basename($image);
    $message .= resize_crop_image(160, 90, $file, $croppedImage,100);

	// Create different sizes
	$message .= createImageSizes($image);


	return $message;
}

//
// --- SEARCH ------------------------------------------------------------------------------------
//

// Searches database for string supplied by user in table specified by user.
// IN: Which table to search in database & what to search for
function generateSearchResult($table, $term){
	//strip potenital tags
	$table = strip_tags($table);
	$term = strip_tags($term);

	// Prepare query for database.
	if ($table=="Article"){
		$items="artiklar";
		$query = 'SELECT * FROM '.$table.' WHERE content LIKE "%' . $term . '%" OR title LIKE "%' . $term . '%";';
	}else{
		$items="objekt";
		$query = 'SELECT * FROM '.$table.' WHERE text LIKE "%' . $term . '%" OR title LIKE "%' . $term . '%";';
	}
	
	// Search the database.
	$res = getData($query);

	// Checks if search generated anything.
	if ($res==NULL){

		// Tells user that nothing was found.
		echo '<article class="left">';
		echo '<h1>Sökresultat</h1>';
		echo '<p>Inga '.$items.' som matchade din sökning "'.$term.'" hittades.</p>';
		echo '</article>';		
	} else{

		// Tells user how many results was found. 
		echo '<article class="left">';
		echo '<h1>Sökresultat</h1>';
		echo '<p>Hittade '.count($res).' '.$items.' som matchade din sökning "'.$term.'".</p>';
		echo '</article>';	

		// Print the found data
		if($table=="Article") {

			foreach($res as $object)
			{
				echo '<article class="left">';
				echo '<h2>'.$object['title'].'</h2>';

				// Finds start of firts paragraph
				$findme = '<p';
				$startPos = strpos($object['content'], $findme);

				// Checks if article is long enough for showing an intro
				if(strlen($object['content'])>$startPos+250){
					
					// Finds first dot after 200 characters from first paragraph
					$findme = '.';
					$endPos= strpos($object['content'], $findme, $startPos+200);

					// Prints the article content from first paragraph to first dot that appears after 200 characters.
					echo substr($object['content'], $startPos, $endPos-$startPos+1);
				} else {

					// if article is not long enough for intro, prints whole article
					echo $object['content'];
				}
			
				echo '<p class="readMore"><a href="?p='.$object['id'].'">Läs mer...</a></p>';
				echo '</article>';
			}
		} elseif($table=="Object") {
			foreach($res as $object){
				echo '<article class="objectList left">';
				echo '<div class="object">';
				echo '<a href="?p='.$object['id'].'"><img src="'.imageSize($object['image'], "medium").'" alt="Bild av '.$object['title'].'"></a></div>';
				echo '<h3 class="objectlist">'.$object['title'].'</h3>';
				echo $object['text'];
				echo '</article>';
	}
		}

	}
}

//
// --- Navigation --------------------------------------------------------------------------------
//

// Creates an article with navigation to previous and next item (if there is any)
// IN: Table it concerns in database & id of the idem that navigation is based on
function getPrevNextNavigation($table, $id){

	$category="";

	// Alters sql-query for Article and for Object w/ category set.
	if($table=="Article"){
		$id = $id.' AND category="article"'; 
	}

	// Alters sql-querystring if url contains a category-part.
	if(isset($_GET["category"])){
		$id = $id.' AND category="'.$_GET['category'].'"';

		// Prepares page-part for prev/next-links
		$category='&category='.$_GET["category"];
	}

	// Prepare query
	$prev = 'SELECT * FROM '.$table.' WHERE id<'.$id.' ORDER BY id DESC LIMIT 1;';
	$next = 'SELECT * FROM '.$table.' WHERE id >'.$id.' ORDER BY id ASC LIMIT 1;';
	
	// Get data from database.
	$prev = getData($prev);
	$next = getData($next);

	
	foreach ($prev as $item) {
		echo '<nav class=prev>';
		echo '<a title="'.$item['title'].'" href="?p='.$item['id'].$category.'">Föregående</a>';
		echo '</nav>';
	}
	foreach ($next as $item) {
		echo '<nav class=next>';
		echo '<a title="'.$item['title'].'" href="?p='.$item['id'].$category.'">Nästa</a>';
		echo "</nav>";
	}
	
}

//
// --- First page --------------------------------------------------------------------------------
//

//
function getFirstPage($position){
	$query = "SELECT * FROM Article WHERE category='firstpage';";
	$res = getData($query);
	$counter = 0;

	foreach($res as $object)
	{
		echo '<article class='.$position.' firstpage>';
		echo '<h1>'.$object['title'].'</h1>';
		echo $object['content'];
		echo '</article>';
		$counter = $counter+1;

		// Insert random objects after every other article
		if($counter % 2 != 0){
			getRandomObjects(3);
		}
	}
}


//
// --- OTHER --------------------------------------------------------------------------------------
//