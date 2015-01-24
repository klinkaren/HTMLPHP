<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the media if true.
//
if(isset($_POST['doSave'])) {
  
  // variable to keep track on wheather data was saved to database
  // helps with color coding messages to user.
  $success = false;

  // Add all form entries to an array
  $media[] = strip_tags($_POST["title"]);
  $media[] = strip_tags($_POST["link"]);
  $media[] = strip_tags($_POST["source"]);
  $media[] = strip_tags($_POST["pubdate"]);
  $media[] = strip_tags($_POST["id"]);

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
    $stmt = $db->prepare("UPDATE Media SET title=?, link=?, source=?, pubdate=? WHERE id=?");
    $stmt->execute($media);
    $output = "Datat uppdaterades.";
    $success = true;
  }
}

//
// Create a select/option-list of the media
// 
$stmt = $db->prepare('SELECT * FROM Media;');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current = null;

$select = "<select id='input1' name='media' onchange='form.submit();'>";
$select .= "<option value='-1'>Välj Media</option>";
foreach(array_reverse($res) as $media) {
  $selected = "";
  if(isset($_POST['media']) && $_POST['media'] == $media['id']) {
    $selected = "selected";
    $current = $media;
  }
  $select .= "<option value='{$media['id']}' {$selected}>{$media['id']}: {$media['title']}</option>";
}
$select .= "</select>";


?>

<h1><?php echo $pageTitle ?></h1>

<p>Välj den artikel som du vill ändra.</p>

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
    
    <input type="hidden" name="id" value="<?php echo $current['id']; ?>">

    <p>
      <label for="input1">Artiklar:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <label for="input1">Titel:</label><br>
      <input type="text" class="text" name="title" value="<?php echo $current['title']; ?>">
    </p>    

    <p>
      <label for="input1">Källa:</label><br>
      <input type="text" class="text" name="source" value="<?php echo $current['source']; ?>">
    </p>  

    <p>
      <label for="input1">Länk:</label><br>
      <input type="text" class="text" name="link" value="<?php echo $current['link']; ?>">
    </p>  

    <p>
      <label for="input1">Publiseringsdatum:</label><br>
      <input type="text" class="text" name="pubdate" value="<?php echo $current['pubdate']; ?>">
    </p>       
    
    <p>
      <input type="submit" name="doSave" value="Spara" <?php if(!isset($current['id'])) echo "disabled";  ?>>
      <input type="reset" value="Ångra">
    </p>       
  </fieldset>
</form>