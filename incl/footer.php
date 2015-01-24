<!-- Footer -->
<?php if(userIsAuthenticated()): ?>
  <footer id="admin">
    <div>
      <p>Verktyg:
        <a href="http://validator.w3.org/check/referer">HTML5</a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
        <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS3</a>
        <a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">Unicorn</a>
        <a href="http://validator.w3.org/i18n-checker/check?uri=<?php echo getCurrentUrl(); ?>">i18n</a>
        <a href="http://validator.w3.org/checklink?uri=<?php echo getCurrentUrl(); ?>">Links</a>
        <a href="viewsource.php">Källkod</a>
      </p>
    
      <p>Manualer:
        <a href="http://www.w3.org/2009/cheatsheet/">Cheatsheet</a>
        <a href="http://dev.w3.org/html5/spec/">HTML5</a> 
        <a href="http://www.w3.org/TR/CSS2/">CSS2</a> 
        <a href="http://www.w3.org/Style/CSS/current-work#CSS3">CSS3</a> 
        <a href="http://php.net/manual/en/index.php">PHP</a> 
      </p>
        
      <?php if(isset($pageTimeGeneration)) : ?>
        <p class="generation-time">Page generated in <?php echo round(microtime(true)-$pageTimeGeneration, 5); ?> seconds</p>
      <?php endif; ?>
    </div>
  </footer>
<?php endif; ?>

<footer id="bottom">

	<div class="footerpart">
    <h3>Besöksadress</h3>
    <ul>
      <li>Kyrkogatan 5</li>
      <li>341 35</li>
      <li>Ljungby</li>
      <li><!--Visa karta--></li>
    </ul>
  </div>

  <div class="footerpart">
    <h3>Öppettider</h3>
    <ul>
      <li>24 juni-22 augusti</li>
      <li>Tisdag - Fredag 13-16</li>
      <li>Entré 20 kr</li>
  </div>

  <div class="footerpart">
    <h3>Kontakt</h3>
    <ul>
        <li>Telefon, vardagar 9-12:</li>
        <li>0372-671 10</li>
    </ul>
  </div>

  <div class="footerpart">
    <h3>Övrigt</h3>
    <ul>
      <li>    
        <?php if(userIsAuthenticated()): ?>
          <a href="login.php?p=logout">Logga ut</a>
        <?php else: ?>
          <a href="login.php?p=login">Admin login</a>
        <?php endif; ?>
    </li>
    </ul>
  </div>
</footer>

</div> <!-- end of container	-->
</body>     
</html>