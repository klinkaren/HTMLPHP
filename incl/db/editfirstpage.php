<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the article if true.
//
if(isset($_POST['doSave'])) {
  
  // variable to keep track on wheather data was saved to database
  // helps with color coding messages to user.
  $success = false;

  $allowed = "<b><i><p><img>";

  // Add all form entries to an array
  $article[] = strip_tags($_POST["title"]);
  $article[] = strip_tags($_POST["content"], $allowed);
  $article[] = strip_tags($_POST["category"]);
  $article[] = strip_tags($_POST["author"]);
  $article[] = strip_tags($_POST["pubdate"]);
  $article[] = strip_tags($_POST["id"]);

  // Runs some tests to see if all fields has been filled out and if specified picture exists. 
  if(empty($_POST['title']))
  {
    $output = "Artikeln sparades ej, titeln kan ej vara tomt. Skriv in en titel.";
  }
  else if(empty($_POST['category']))
  {
    $output = "Artikeln sparades ej då ingen kategori angivits. Skriv in artikelns kategori.";
  }
  else if(empty($_POST['author']))
  {
    $output = "Artikeln sparades ej då ingen författare angivits. Skriv in författarens namn.";
  }
  else if(empty($_POST['pubdate']))
  {
    $output = "Artikeln sparades ej då inget publiceringsdatum angivits. Skriv in publiceringsdatum.";
  }
  else if(empty($_POST['content']))
  {
    $output = "Antikeln sparades ej då den saknar innehåll. Skriv in artikelns innehåll.";
  }
  else
  {
    $stmt = $db->prepare("UPDATE Article SET title=?, content=?, category=?, author=?, pubdate=? WHERE id=?");
    $stmt->execute($article);
    $output = "Artikeln uppdaterades.";
    $success = true;
  }
}


//
// Create a select/option-list of the articles
// 
$stmt = $db->prepare('SELECT * FROM Article WHERE category="firstpage";');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current = null;

$select = "<select id='input1' name='articles' onchange='form.submit();'>";
$select .= "<option value='-1'>Välj Artikel</option>";
foreach(array_reverse($res) as $article) {
  $selected = "";
  if(isset($_POST['articles']) && $_POST['articles'] == $article['id']) {
    $selected = "selected";
    $current = $article;
  }
  $select .= "<option value='{$article['id']}' {$selected}>{$article['id']}: {$article['title']}</option>";
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
    <input type="hidden" name="category" value="firstpage">

    <p>
      <label for="input1">Artiklar:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <label for="input1">Titel:</label><br>
      <input type="text" class="text" name="title" value="<?php echo $current['title']; ?>">
    </p>    

    <p>
      <label for="input1">Författare:</label><br>
      <input type="text" class="text" name="author" value="<?php echo $current['author']; ?>">
    </p>  

    <p>
      <label for="input1">Publiseringsdatum:</label><br>
      <input type="text" class="text" name="pubdate" value="<?php echo $current['pubdate']; ?>">
    </p>     
    
    <p>
      <textarea style="width:100%;" name="content"><?php echo $current['content']; ?></textarea>
    </p>    
    
    <p>
      <input type="submit" name="doSave" value="Spara" <?php if(!isset($current['id'])) echo "disabled";  ?>>
      <input type="reset" value="Ångra">
    </p>       
  </fieldset>
</form>