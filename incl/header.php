<!doctype html>
<html lang="sv">

<head>
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
  
  
  
<!-- Links to stylesheets -->
<?php if(isset($_SESSION['stylesheet'])): ?>
	<link rel="stylesheet" href="style/<?php echo $_SESSION['stylesheet']; ?>">  
<?php else: ?>
  <link rel="stylesheet" href="style/stylesheet.css" title="General stylesheet">
  <link rel="alternate stylesheet" href="style/debug.css" title="Debug stylesheet">
<?php endif; ?>

  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.ico">

<!-- Font for Title of site (in header) -->
<link href='http://fonts.googleapis.com/css?family=Roboto:300,500,700' rel='stylesheet' type='text/css'>
  
  <!-- Each page can set $pageStyle to create an ingternal stylesheet -->
  <?php if(isset($pageStyle)) : ?>
  <style type="text/css">
    <?php echo $pageStyle; ?>
  </style>
  <?php endif; ?>
  
</head>

<!-- The body id helps with highlighting current menu choice -->
<body<?php if(isset($pageId)) echo " id='$pageId' "; ?>>

<!-- Header -->

<!-- Container for page. End of container can be found in footer -->
<div id="container"> 


<!-- Top header with logo and navigation -->
<header id="top">
  <div class="homeTitle">
    <a href="index.php">BMO</a>    
  </div>
  <!-- Navigation menu -->
  <nav class="navmenu">
    <a id="hem-"        href="index.php">Hem</a>
    <a id="artiklar-"   href="artiklar.php">Artiklar</a>
    <a id="objekt-"     href="objekt.php">Objekt</a> 
    <a id="galleri-"    href="galleri.php">Galleri</a>
    <a id="om-"         href="om.php">Om BMO</a>
  </nav>
</header>
<header id="admin">
  <nav class="navadmin">
    <!-- part of menu only visable to logged in users -->
    <?php if(userIsAuthenticated()): ?>
      <a id="db-"       href="db.php">Databashantering</a> 
      <a id="test-"     href="test.php">Testsida</a> 
      <a id="source-"   href="viewsource.php">Källkod</a>
      <a id="style-"    href="style.php">Stilhanterare</a>
    <?php endif; ?>
		
    </nav>
 </header>