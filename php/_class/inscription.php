<?php
    class Inscription {

        function verification_inscription($prenom, $nom, $mail, $confmail, $password, $confpassword, $statut){
            
            global $DB;
            
            
            $prenom = (String) ucfirst(trim($prenom));
            $nom = (String) trim($nom);
            $mail = (String) trim($mail);
            $confmail = (String) trim($confmail);
            $password = (String) trim($password);
            $confpassword = (String) trim($confpassword);
            $statut = (String) trim($statut);

            $err_prenom =(String)'';            
            $err_nom =(String)'';
            $err_mail =(String)'';
            $err_password =(String)'';
            $err_statut =(String)'';

            $valid = (boolean) true;

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
            }

            return [$err_prenom, $err_nom, $err_password, $err_mail, $err_statut];
        }
    }

?>