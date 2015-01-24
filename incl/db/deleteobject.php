<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the object if true.
//
if(isset($_POST['doDelete'])) {

  $object[] = $_POST["objects"];

  $stmt = $db->prepare("DELETE FROM object WHERE id=?");
  $stmt->execute($object);
  $output = "Raderade objektet.";
}


//
// Create a select/option-list of the objects
// 
$stmt = $db->prepare('SELECT * FROM Object;');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

$select = "<select id='input1' name='objects'>";
foreach(array_reverse($res) as $object) {
  $select .= "<option value='{$object['id']}'>{$object['title']} ({$object['id']})</option>";
}
$select .= "</select>";


?>

<h1>Ta bort ett objekt</h1>

<p>Välj ett objekt och klicka på knappen "Radera" för att ta bort objektet.</p>

<form method="post">
  <fieldset>
    <?php if(isset($output)): ?>
      <p><output class="success"><?php echo $output ?></output></p>
    <?php endif; ?>

    <p>
      <label for="input1">Befintliga objekt:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <input type="submit" name="doDelete" value="Radera">
    </p>

  </fieldset>
</form>