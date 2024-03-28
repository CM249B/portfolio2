<?php

  require_once('include.php');

  if(isset($_SESSION['id'])){
    $var = $_SESSION['prenom'];
     $_SESSION['nom'];
  }else{
    $var = "Bonjour";
  }

?>

<!doctype html>
<html lang="fr">
  <head>
    <title>Acceuil</title>
    <?php
      require_once('_head/meta.php');
      require_once('_head/link.php');
      require_once('_head/script.php')
    ?>
  </head>
  <body>
    <?php
      require_once('_menu/menu.php');
    ?>
    <h1><?= $var ?></h1>
    <?php
      require_once('_footer/footer.php');
    ?>
   </body>
</html>