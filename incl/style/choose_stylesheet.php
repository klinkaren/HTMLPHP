<?php
$stylesheets = readDirectory("style/");

$select = "<select id='input1' name='stylesheet' onchange='form.submit();'>";
$select .= "<option value='-1'>Webbplatsens standard stylesheet</option>";
foreach($stylesheets as $val) 
{
  $selected = "";
  if(isset($_SESSION['stylesheet']) && $_SESSION['stylesheet'] == $val) 
  {
    $selected = "selected";
  }
  $select .= "<option value='{$val}' {$selected}>{$val}</option>";
}
$select .= "</select>";


?>

<h1>Välj stylesheet</h1>

<p>Välj den stylesheet som du vill använda. Du kan välja bland de stylesheets som ligger
i katalogen <code>style/</code>.</p>

<form method="post" action="?p=choose-stylesheet-process">
  <fieldset>
    <!-- <legend>Välj Stylesheet</legend> -->
    <p>
      <label for="input1">Stylesheet:</label><br>
      <?php echo $select; ?>
    </p>
    
    <p>
      <?php 
      if(isset($_SESSION['stylesheet']))
      {
        echo "Din nuvarande stylesheet är '{$_SESSION['stylesheet']}'.";
      }
      else
      {
        echo "Du använder webbplatsens standard stylesheet.";
      }
      ?>
    </p>

  </fieldset>
</form>

<p>Detta formulär använder en funktion i <code>src/common.php</code>, funktionen, <code>readDirectory()</code>, läser 
innehållet i en katalog på disk och returnerar en array med de filer som ligger i katalogen. 
<a href="viewsource.php?dir=src&amp;file=common.php#file">Källkoden hittar du här</a>.</p>

<p>När formuläret postas så är det en speciell processing-sida som tar hand om det. 
<a href="viewsource.php?dir=incl/style&amp;file=choose_stylesheet_process.php#file">Studera källkoden för processing-sidan</a>.</p>