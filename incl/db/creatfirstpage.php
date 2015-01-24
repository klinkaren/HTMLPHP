<?php
//
// Connect to the database
//
$db = new PDO("sqlite:$dbPath");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // Display errors, but continue script


//
// Check if Save-button was pressed, save the article if true.
//
if(isset($_POST['doCreate'])) {

  // Variable to keep track on wheather data was saved to database.
  // Helps with color coding messages to user.
  $success = false;

  // Tags that are allowed in some fields.
  $allowed = "<b><i><p><img>";

  // Add all form entries to an array
  $article[] = strip_tags($_POST["title"]);
  $article[] = strip_tags($_POST["content"], $allowed);
  $article[] = strip_tags($_POST["category"]);
  $article[] = strip_tags($_POST["author"]);
  $article[] = strip_tags($_POST["pubdate"]);

  // Runs some tests to see if all fields has been filled out and if specified picture exists. 
  if(empty($_POST['title']))
  {
    $output = "Artikeln sparades ej, titeln kan ej vara tomt. Skriv in en titel.";
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
    // Saves the data to the database.
    $stmt = $db->prepare("INSERT INTO Article (title, content, category, author, pubdate) VALUES (?,?,?,?,?)");
    $stmt->execute($article);
    $output = "Lade till en ny artikel";
  
    $success = true;
  }
 }

// Create a select/option-list of the articles
$stmt = $db->prepare('SELECT * FROM Article WHERE category="firstpage";');
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Shows all articles that exists in database. In reverse to show new entries first.
$select = "<select id='input1' multiple name='articles'>";
foreach(array_reverse($res) as $article) {
  $select .= "<option value='{$article['id']}'>{$article['title']} ({$article['id']})</option>";
}
$select .= "</select>";

?>

<h1><?php echo $pageTitle ?></h1>

<p>Ange ett unikt namn på en annons och klicka på knappen för att spara den.</p>

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
        <label for="input1">Artiklar i databasen:</label><br>
        <?php echo $select; ?>
    </p>
      
    <p>
        <label for="input2">Titel:</label><br>
        <input id="input2" class="text" name="title" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["title"]; }; ?>">
    </p>    

    <p>
        <label for="input4">Författare:</label><br>
        <input type="text" class="text" name="author" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["author"]; }; ?>">
    </p>

    <p>
        <label for="input5">Publiseringsdatum (enligt ÅÅÅÅ-MM-DD):</label><br>
        <input type="text" class="text" name="pubdate" value="<?php if(isset($_POST['doCreate'])){ echo $_POST["pubdate"]; }; ?>">
    </p>

    <p>
        <label for="input6">Innehåll:</label><br>
        <p>För att artiklar ska läsas korrekt, använda html-taggar.</p><p> Ex: För nytt stycke inled med &lt;p&gt; och avsluta stycket med &lt;/p&gt; 
        <textarea style="width:100%;" name="content"><?php if(isset($_POST['doCreate'])){ echo $_POST["content"]; }; ?></textarea>
    </p>    
      
    <p>
        <input type="submit" name="doCreate" value="Skapa">
        <input type="reset" value="Ångra">
    </p>
  </fieldset>
</form>