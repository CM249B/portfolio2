<?php
    class Connexion {

        private $valid;

        private $mail;
        private $password;

        public function verification_connexion($mail, $password){

            global $DB;

            $mail = trim($mail);
            $password = trim($password);

            $this->valid = (boolean) true;


            if(empty($mail)){
                $this->valid = false;
                $this->err_mail = "Ce champ ne peut pas être vide";
            }
        
            if(empty($password)){
                $this->valid = false;
                $this->err_password = "Ce champ ne peut pas être vide";
            }


            if ($this->valid){
                $req = $DB -> prepare("SELECT mdp
                FROM utilisateur
                Where mail = ?");

                $req -> execute(array($mail));

                $req = $req->fetch();

                if(isset($req['mdp'])){


                if(!password_verify($password, $req['mdp'])){
                    $this->valid = false;
                    $this->err_mal ="Le mot de passe ou le mail est incorrect";
                }

                
                }else{
                    $this->valid = false;
                    $this->err_mail ="Le mail ou le mot de passe est incorrect";
                }
            }
        

            if($this->valid){

                    $req = $DB -> prepare("SELECT *
                    FROM utilisateur
                    Where mail = ?");

                    $req -> execute(array($mail));

                    $req_user = $req->fetch();

                    if(isset($req_user['id'])){
                    $req = $DB->prepare("UPDATE utilisateur(nom, prenom, mail, mdp, Statut) VALUES (?, ?, ?, ?, ?");                    
                    $req->execute(array($req_user['id']));

                    $_SESSION['id'] = $req_user['id'];
                    $_SESSION['mail'] = $req_user['mail'];
                    $_SESSION['nom'] = $req_user['nom'];
                    $_SESSION['prenom'] = $req_user['prenom'];
                    $_SESSION['role'] = $req_user['role'];

            
                    header('Location: /');
                    exit;
                }else{
                    $this->valid = false;
                    $this->err_mail ="Le mail ou le mot de passe est incorrect";
                }

            }

            return[$this->err_mail, $this->err_password];
        }
        
    }
?>