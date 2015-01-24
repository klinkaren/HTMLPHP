<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the article if true.
//
if(isset($_POST['doDelete'])) {

  $article[] = $_POST["articles"];

  $stmt = $db->prepare("DELETE FROM Article WHERE id=?");
  $stmt->execute($article);
  $output = "Raderade artikeln.";
}


//
// Create a select/option-list of the articles
// 
$stmt = $db->prepare('SELECT * FROM Article WHERE category="article";');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

$select = "<select id='input1' name='articles'>";
foreach(array_reverse($res) as $article) {
  $select .= "<option value='{$article['id']}'>{$article['title']} ({$article['id']})</option>";
}
$select .= "</select>";


?>

<h1><?php echo $pageTitle ?></h1>

<p>Välj en artikel och klicka knappen "Radera" för att ta bort artikeln.</p>

<form method="post">
  <fieldset>
    <?php if(isset($output)): ?>
      <p><output class="success"><?php echo $output ?></output></p>
    <?php endif; ?>

    <p>
      <label for="input1">Befintliga artiklar:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <input type="submit" name="doDelete" value="Radera">
    </p>

  </fieldset>
</form>