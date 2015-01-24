<?php 
include("incl/config.php");
$title = "BMO - Galleri"; //changes page title (found in header.php)
$pageId = "galleri";
include("incl/header.php"); 

// The image viewer on this page is based on code from 
// http://www.lateralcode.com/create-a-simple-picture-gallery-using-php/

// Viewpage alternatives
$viewOptions = array(
	4 => "4",
	8 => "8",
	12 => "12",
	16 => "16",
	"all" => "Alla");

// Set defaults
$view="8";
$page="1";

// Check if the url contains a view-part
if(isset($_GET["view"])) 
{
	$view = $_GET["view"];
}
if(isset($_GET["p"])) 
{
	$page = $_GET["p"];
}

?>

<div id="content">
	<article>
		<nav class="galleryDropDown">
			<form method="get">
			<label for="input1">Visa:</label>
			<select id='input1' name='view' onchange='form.submit();'>
				<?php
					foreach($viewOptions as $value=>$name)
					{
					    if($value == $view)
					    {
					         echo "<option selected='selected' value='".$value."'>".$name."</option>";
					    }
					    else
					    {
					         echo "<option value='".$value."'>".$name."</option>";
					    }
					}
				?>
			</select>
			</form>
		</nav>
		<nav class="galleryPages">
		<?php 
			if($view!="all"){
				getGalleryNavigation($page, $view);
			}
		?>
		</nav>
	</article>
	<article>
		<?php
			if($view!="all"){
				putGalleryObjects($page,$view);
			}else{
				putAllGalleryObjects();
			}
		?>
	</article>
</div>
<script type="text/javascript" src="incl/gallery/js/prototype.js"></script>
<script type="text/javascript" src="incl/gallery/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="incl/gallery/js/lightbox.js"></script>
<?php include("incl/footer.php"); ?>