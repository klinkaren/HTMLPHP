<?php

/*
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the object if true.
//
if(isset($_POST['doCreate'])) {

  // variable to keep track on wheather data was saved to database
  // helps with color coding messages to user.
  $success = false;

  // Strips tags and adds all form entries to an array.
  $allowed = "<b><i><p>";

  // Add all form entries to an array
  $object[] = strip_tags($_POST["title"]);
  $object[] = strip_tags($_POST["category"]);
  $object[] = strip_tags($_POST["text"], $allowed);
  $object[] = strip_tags($_POST["image"]);  
  $object[] = strip_tags($_POST["owner"]);

  // Runs some tests to see if all fields has been filled out and if specified picture exists. 
   if(empty($_POST['title']))
  {
    $output = "Datat sparades ej, titeln kan ej vara tomt. Skriv in en titel.";
  }
  else if(empty($_POST['category']))
  {
    $output = "Datat sparades ej då ingen kategori angivits. Skriv in en kategori.";
  }
  else if(empty($_POST['image']))
  {
    $output = "Datat sparades ej då ingen bild angivits. Skriv in bildens filnamn.";
  }
  else if(!pictureExists($object[3]))
  {
    $output = "Datat sparades ej då den angivna filen inte kunde hittas. Se till att filen som angivs finns i mappen /img/bmo.";
  }
  else if(empty($_POST['owner']))
  {
    $output = "Datat sparades ej då ingen ägare angivits. Skriv in en ägare.";
  }
  else if(empty($_POST['text']))
  {
    $output = "Datat sparades ej då det saknas förklarande text. Skriv in en förklarande text.";
  }
  else
  {
    // add full path to picture
    $object[3] = "img/bmo/".$object[3];

    // Saves the data to the database.
    $stmt = $db->prepare("INSERT INTO Object (title, category, text, image, owner) VALUES (?,?,?,?,?)");
    $stmt->execute($object);
    $output = "Lade till ett nytt objekt.";
    
    // Calls function to create different sizes of picture.
    createPictureSizes($object[3]);

    $success = true;
  }
}

// Create a select/option-list of the objects
$stmt = $db->prepare('SELECT * FROM Object;');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Shows all objects that exists in database. In reverse to show new entries first.
$select = "<select id='input1' multiple name='objects'>";
foreach(array_reverse($res) as $object) {
  $select .= "<option value='{$object['id']}'>{$object['title']} ({$object['id']})</option>";
}
$select .= "</select>";

?>

<h1><?php echo $pageTitle ?></h1>

<p>Fyll i formuläret och klicka på knappen för att spara informationen till databasen.</p>

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
    ?>

    <label for="input1">Objekt som finns i databasen:</label><br>
      <?php echo $select; ?>
    
    <p>
      <label for="input2">Titel:</label><br>
      <input id="input2" class="text" name="title" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["title"]; }; ?>">
    </p>    
        
    <p>
      <label for="input3">Kategori:</label><br>
      Kategorier som redan finns är: <?php echo getCategories("Object"); ?>
      <input type="text" class="text" name="category" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["category"]; }; ?>">
    </p>

    <p>
      <label for="input4">Bild:</label><br>
      <i>Säkerställ att bilden finns sparad i mappen /img/bmo/ innan objektet sparas</i><br>
      Bilder i mindre storlekar kommer genereras automatiskt när objektet läggs till i databasen.
      <input type="text" class="text" name="image" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["image"]; }; ?>">
    </p>

    <p>
      <label for="input5">Ägare:</label><br>
      <input type="text" class="text" name="owner" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["owner"]; }; ?>">
    </p>

    <p>
      <label for="input6">Information om objektet: </label><br>
      <textarea style="width:100%;" name="text"><?php if(isset($_POST['doCreate'])){ echo $_POST["text"]; }; ?></textarea>
    </p>    
    
    <p>
      <input type="submit" name="doCreate" value="Skapa">
      <input type="reset" value="Ångra">
    </p>       

  </fieldset>
</form>
*/
echo "First page<br>";


$incomingImage="img/bmo/test.gif";
$message = createPictures($incomingImage);
echo $message;
echo $message;

?>