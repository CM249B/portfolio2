<?php

  require_once('include.php');

  if(isset($_SESSION['id'])){
    beader('Location: /');
    exit;
  }

  if(!empty($_POST)){
    extract($_POST);

    $valid = (boolean) true;

    if(isset($_POST['inscription'])){
      $prenom = trim($prenom);
      $nom = trim($nom);
      $mail = trim($mail);
      $confmail = trim($confmail);
      $password = trim($password);
      $confpassword = trim($confpassword);
      $statut = trim($statut);

      if(empty($prenom)){
        $valid = false;
        $err_prenom = "Ce champ ne peut pas être vide";
      }else{
        $req = $DB -> prepare("SELECT *
          FROM utilisateur
          Where prenom = ?");

        $req -> execute(array($prenom));

        $req = $req->fetch();

      }

      if(empty($nom)){
        $valid = false;
        $err_nom = "Ce champ ne peut pas être vide";
      }else{
        $req = $DB -> prepare("SELECT *
          FROM utilisateur
          Where nom = ?");

        $req -> execute(array($nom));
        $req = $req->fetch();

      }

      if(empty($mail)){
        $valid = false;
        $err_mail = "Ce champ ne peut pas être vide";
      
      }elseif(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
        $valid = false;
        $err_mail = "Mail invalide";

      }elseif($mail <> $confmail){
        $valid = false;
        $err_mail = "Ce mail n'est pas le meme que la confirmation";

      }else{
        $req = $DB -> prepare("SELECT *
          FROM utilisateur
          Where mail = ?");

        $req -> execute(array($mail));

        $req = $req->fetch();

        if(isset($req['id'])){
          $valid = false;
          $err_mail = "Ce mail est deja pris ";
        }

      }
      
      if(empty($password)){
        $valid = false;
        $err_password = "Ce champ ne peut pas être vide";
      }elseif($password <> $confpassword){
        $valid = false;
        $err_password = "Ce mot de passe n'est pas le meme que la confirmation";
      }
      
      if(empty($statut)){
        $valid = false;
        $err_statut = "Ce champ doit etre selectionné";
      }else{
        $req = $DB -> prepare("SELECT *
          FROM utilisateur
          Where statut = ?");

        $req -> execute(array($statut));

        $req = $req->fetch();

      }

      if($valid){

        //$crytp_password = crypt($password, '$6$rounds=5000$UEc~RD_p,@|>#M]2I||(Y]Z9:pDg%=5Hi5u~Ko!~8k8TBp,P3,b6 BLU+mL_pVL7$');
        $crytp_password = password_hash($password, PASSWORD_ARGON2ID);




        $req = $DB->prepare("INSERT INTO utilisateur(nom, prenom, mail, mdp, Statut) VALUES (?, ?, ?, ?, ?");
        $req->execute(array($prenom, $nom, $mail, $crytp_password, $statut));

        header('Location: connexion.php');
        exit;
      }else{
        echo 'no ok';
      }

    }

  }


?>

<!doctype html>
<html lang="fr">
  <head>
    <title>Inscription</title>
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
          <h1>Inscription</h1>
          <form method="post">
            <div class="mb-3">
              <?php if(isset($err_prenom)){ echo '<div>' . $err_prenom . '</div>';}?>
              <label class= "form-label">prenom</label>
              <input class="form-control" type="text" name="prenom" value="<?php if(isset($prenom)){ echo $prenom; }?>" placeholder="Prenom"/>
            </div>
            <div class="mb-3">
              <?php if(isset($err_nom)){ echo '<div>' . $err_nom . '</div>';}?>
              <label class= "form-label">nom</label>
              <input class="form-control" type="text" name="nom" value="<?php if(isset($nom)){ echo $nom;}?>" placeholder="Nom"/>
            </div>
            <div class="mb-3">
              <?php if(isset($err_mail)){ echo '<div>' . $err_mail . '</div>';}?>
              <label class= "form-label">mail</label>
              <input class="form-control" type="email" name="mail" value="<?php if(isset($mail)){ echo $mail;}?>" placeholder="Email"/>
            </div>
            <div class="mb-3">
              <label class= "form-label">confirmation du mail</label>
              <input class="form-control" type="email" name="confmail" value="<?php if(isset($confmail)){ echo $confmail;}?>" placeholder=" Confirmation de l'Email"/>
            </div>
            <div class="mb-3">
              <?php if(isset($err_password)){ echo '<div>' . $err_password . '</div>';}?>
              <label class= "form-label">mot de passe</label>
              <input class="form-control" type="password" name="password" value="<?php if(isset($password)){ echo $password;}?>" placeholder="Mot de passe"/>
            </div>
            <div class="mb-3">
              <label class= "form-label">confirmation du mot de passe</label>
              <input class="form-control" type="password" name="confpassword" value="" placeholder="Confirmation de mot de passe"/>
            </div>
            <div class="mb-3">
              <select class="" name="statut" <?php if(isset($statut)){ echo $statut;}?> required>
                <option value="" selected hidden>professeur</option>
                <option value="DEV">professeur de dev</option>
                <option value="FRANCAIS">professeur de francais</option>
                <option value="ECO">professeur d'économie</option>
              </select>
            </div>
            <div class="mb-3">
              <button type="submit" name="inscription" class="btn btn-danger">Inscription</button>
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