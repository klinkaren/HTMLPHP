<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the object if true.
//
if(isset($_POST['doSave'])) {
  
  // variable to keep track on wheather data was saved to database
  // helps with color coding messages to user.
  $success = false;

  $allowed = "<b><i><p><img>";

  // Add all form entries to an array
  $object[] = strip_tags($_POST["title"]);
  $object[] = strip_tags($_POST["category"]);
  $object[] = strip_tags($_POST["text"], $allowed);
  $object[] = strip_tags($_POST["image"]);
  $object[] = strip_tags($_POST["owner"]);
  $object[] = strip_tags($_POST["id"]);

  // Runs some tests to see if all fields has been filled out and if specified picture exists. 
  if(empty($_POST['title']))
  {
    $output = "Objektet sparades ej, titeln kan ej vara tomt. Skriv in en titel.";
  }
  else if(empty($_POST['category']))
  {
    $output = "Objektet sparades ej då ingen kategori angivits. Skriv in objektets kategori.";
  }
  else if(empty($_POST['text']))
  {
    $output = "Objektet sparades ej då ingen förklarande text angivits. Skriv in en kort förklarande text.";
  }
  else if(empty($_POST['image']))
  {
    $output = "Objektet sparades ej inget filnamn för bilden angivits. Skriv in filnamnet på den bild som ska användas.";
  }
  else if(!pictureExists($object[3]))
  {
    $output = "Datat sparades ej då den angivna filen inte kunde hittas. Se till att filen som angivs finns i mappen /img/bmo.";
  }
  else if(empty($_POST['owner']))
  {
    $output = "Antikeln sparades ej då den saknar innehåll. Skriv in Objektets innehåll.";
  }
  else
  {
        // add full path to picture
    $object[3] = "img/bmo/".$object[3];

    $stmt = $db->prepare("UPDATE object SET title=?, category=?, text=?, image=?, owner=? WHERE id=?");
    $stmt->execute($object);
    $output = "Objektet uppdaterades.";
    $pictureInfo = createPictures($object[3]);
    $success = true;


    // Create images of different sizes, if new image ----------------------------------------NEEDS TO BE ADDED !!!
    // Also, if image was changed, add message about that no images will be deleted to $output
  }
}

//
// Create a select/option-list of the objects
// 
$stmt = $db->prepare('SELECT * FROM Object;');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current = null;

$select = "<select id='input1' name='objects' onchange='form.submit();'>";
$select .= "<option value='-1'>Välj Objekt</option>";
foreach(array_reverse($res) as $object) {
  $selected = "";
  if(isset($_POST['objects']) && $_POST['objects'] == $object['id']) {
    $selected = "selected";
    $current = $object;
  }
  $select .= "<option value='{$object['id']}' {$selected}>{$object['id']}: {$object['title']}</option>";
}
$select .= "</select>";


?>

<h1><?php echo $pageTitle ?></h1>

<p>Välj det objekt som du vill ändra.</p>

<form method="post">
  <fieldset>

    <?php 
      // Color code feedback to user.
      if(isset($output)){
        if ($success){
          echo '<p><output class="success">' . $output . '</output></p>';
        }else{
          echo '<p><output class="error">' . $output . '</output></p>';
        }
      }
      if(isset($pictureInfo)){
        echo '<br><output class="info">' . $pictureInfo . '</output>';
      }
    ?>
    
    <input type="hidden" name="id" value="<?php echo $current['id']; ?>">

    <p>
      <label for="input1">Objekt:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <label for="input1">Titel:</label><br>
      <input type="text" class="text" name="title" value="<?php echo $current['title']; ?>">
    </p>    
    
    <p>
      <label for="input1">Kategori:</label><br>
      Kategorier som redan finns är <?php echo getCategories("Object"); ?><br>
      <input type="text" class="text" name="category" value="<?php echo $current['category']; ?>">
    </p>

    <p>
      <label for="input1">Ägare:</label><br>
      <input type="text" class="text" name="owner" value="<?php echo $current['owner']; ?>">
    </p>  

    <p>
      <label for="input1">Bild:</label><br>
      <i>Säkerställ att bilden finns sparad i mappen /img/bmo/ innan objektet sparas</i><br>
      Bilder i mindre storlekar kommer automatiskt att genereras när objektet skapas.
      <input type="text" class="text" name="image" value="<?php echo basename($current['image']); ?>">

    </p>     
    
    <p>
      <textarea style="width:100%;" name="text"><?php echo $current['text']; ?></textarea>
    </p>    
    
    <p>
      <input type="submit" name="doSave" value="Spara" <?php if(!isset($current['id'])) echo "disabled";  ?>>
      <input type="reset" value="Ångra">
    </p>       
  </fieldset>
</form>