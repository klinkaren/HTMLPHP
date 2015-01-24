<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the media if true.
//
if(isset($_POST['doCreate'])) {

  // Variable to keep track on wheather data was saved to database.
  // Helps with color coding messages to user.
  $success = false;

  // Add all form entries to an array
  $media[] = strip_tags($_POST["title"]);
  $media[] = strip_tags($_POST["link"]);
  $media[] = strip_tags($_POST["source"]);
  $media[] = strip_tags($_POST["pubdate"]);

  // Runs some tests to see if all fields has been filled out and if specified picture exists. 
  if(empty($_POST['title']))
  {
    $output = "Datat sparades ej, titeln kan ej vara tomt. Skriv in en titel.";
  }
  else if(empty($_POST['link']))
  {
    $output = "Datat sparades ej då ingen länk angivits. Skriv in en länk.";
  }
  else if(empty($_POST['source']))
  {
    $output = "Datat sparades ej då det saknas en källa. Skriv in en källa.";
  }
  else if(empty($_POST['pubdate']))
  {
    $output = "Artikeln sparades ej då inget publiceringsdatum angivits. Ange ett publiceringsdatum.";
  }
  else
  {
    // Saves the data to the database.
    $stmt = $db->prepare("INSERT INTO Media (title, link, source, pubdate) VALUES (?,?,?,?)");
    $stmt->execute($media);
    $output = "Lade till ny media till widget på förstasidan.";
  
    $success = true;
  }
 }

// Create a select/option-list of the media
$stmt = $db->prepare('SELECT * FROM Media');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Shows all media that exists in database. In reverse, to show new entries first.
$select = "<select id='input1' multiple name='articles'>";
foreach(array_reverse($res) as $media) {
  $select .= "<option value='{$media['id']}'>{$media['title']} ({$media['source']})</option>";
}
$select .= "</select>";

?>

<h1><?php echo $pageTitle ?></h1>

<p>Fyll i formuläret och klicka på knappen för att spara datat som kommer att visas i widget på förstasidan.</p>

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
  	
    <!-- Hidden input since all articles should be assigned the category article-->
    <input type="hidden" name="category" value="firstpage">
    
    <p>
        <label for="input1">Media i databasen:</label><br>
        <?php echo $select; ?>
    </p>
      
    <p>
        <label for="input2">Titel:</label><br>
        <input id="input2" class="text" name="title" placeholder="Titel på mediainslag" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["title"]; }; ?>">
    </p>    

    <p>
        <label for="input4">Källa:</label><br>
        <input type="text" class="text" name="source" placeholder="Namn på tidning/webbplats" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["source"]; }; ?>">
    </p>

    <p>
        <label for="input4">Länk:</label><br>
        <input type="text" class="text" name="link" placeholder="Länk till mediainslag" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["link"]; }; ?>">
    </p>

    <p>
        <label for="input5">Publiseringsdatum:</label><br>
        <input type="text" class="text" name="pubdate" placeholder="Enligt ÅÅÅÅ-MM-DD" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["pubdate"]; }; ?>">
    </p>  
      
    <p>
        <input type="submit" name="doCreate" value="Skapa">
        <input type="reset" value="Ångra">
    </p>
  </fieldset>
</form>