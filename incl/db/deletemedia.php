<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the media if true.
//
if(isset($_POST['doDelete'])) {

  $media[] = $_POST["media"];

  $stmt = $db->prepare("DELETE FROM Media WHERE id=?");
  $stmt->execute($media);
  $output = "Raderade artikeln.";
}


//
// Create a select/option-list of the media
// 
$stmt = $db->prepare('SELECT * FROM Media;');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

$select = "<select id='input1' name='media'>";
foreach(array_reverse($res) as $media) {
  $select .= "<option value='{$media['id']}'>{$media['title']} - {$media['source']})</option>";
}
$select .= "</select>";


?>

<h1><?php echo $pageTitle ?></h1>

<p>Välj ett mediaobjekt och klicka knappen "Radera" för att ta bort det.</p>

<form method="post">
  <fieldset>
    <?php if(isset($output)): ?>
      <p><output class="success"><?php echo $output ?></output></p>
    <?php endif; ?>

    <p>
      <label for="input1">Befintliga mediaobjekt:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <input type="submit" name="doDelete" value="Radera">
    </p>

  </fieldset>
</form>