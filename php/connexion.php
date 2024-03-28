
<?php

  require_once('include.php');

  if(isset($_SESSION['id'])){
    beader('Location: /');
    exit;
  }

  if(!empty($_POST)){
    extract($_POST);

    if(isset($_POST['connexion'])){

      [$err_mail, $err_password] = $_Connexion->verification_connexion($mail,$password);

    }

  }


?>
<!doctype html>
<html lang="fr">
  <head>
    <title>Connexion</title>
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
    <div class=container>
      <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
          <h1>Connexion</h1>
          <form method="post">
            <div class="mb-3">
              <?php if(isset($err_mail)){ echo '<div>' . $err_mail . '</div>';}?>
              <label class= "form-label">mail</label>
              <input class="form-control" type="email" name="mail" value="<?php if(isset($mail)){ echo $mail;}?>" placeholder="Email">
            </div>
            <div class="mb-3">
              <?php if(isset($err_password)){ echo '<div>' . $err_password . '</div>';}?>
              <label class= "form-label">mot de passe</label>
              <input class="form-control" type="password" name="password" value="<?php if(isset($password)){ echo $password;}?>" placeholder="Mot de passe">
            </div>
            <div class="mb-3">
              <button type="submit" name="connexion" class="btn btn-danger">Se connecter</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php
      require_once('_footer/footer.php');
    ?> 
   </body>
</html>

    


    