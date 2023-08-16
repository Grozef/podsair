<?php
require_once "acces_bdd.php";

function inser()
{
         // !empty pour vérifier que la valeur entrée dans le champs du formulaire n'est pas nulle
         // Ne sert a rien puisque le champs du formulaire possede l'attribut (required )
         // penser a remplacer !empty par des filtres de sécurité

        if (!empty($_GET["nom"]) && !empty($_GET["mail"])) {
            // Utilisation de la fonction pour se connecter à la bdd TrouverUnJobPourMoussa
                $db = connexion_bdd();
                $nom_table = "proteine";
                $nom = strip_tags($_GET["nom"]);
                $mail = strip_tags($_GET["mail"]);
                $descript = isset($_GET["messages"]) ? $_GET["messages"] : "";

            // La requête d'insertion n'est exécutée que si les champs obligatoires ne sont pas vides
                $sql = "INSERT INTO $nom_table (nom, mail, messages) VALUES (:nom, :mail, :messages)";
                        
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":nom", $nom);
                $stmt->bindParam(":mail", $mail);
                $stmt->bindParam(":messages", $descript);
                $stmt->execute();
            // Message de confirmation
                echo "Insertion réussie, 🍕";
            // Utilisation de la fonction pour se déconnecter de la bdd
                close_connexion($db);
           // var_dump($_GET);

         } else {
        $db = connexion_bdd();
        // Message de confirmation
            echo "Le formulaire n'a pas été soumis correctement.";
        // Utilisation de la fonction de fermeture de connexion à la bdd
            close_connexion($db);
    }
};

function inser_inscription(){
//si ce qu'on a récupéré via la methode POST (login et pass) sont définis
    if( !empty($_POST["nom"]) && !empty($_POST["mdp"]) )
    {
        //utilisation de la fonction connexion_bdd()pour se connecter à la bdd trouverunjobpourmoussa
            $db = connexion_bdd();
            $nom_table = "user";
          //recupération des champs du formulaire
          //strip_tags — Supprime les balises HTML et PHP d'une chaîne
            $nom = strip_tags($_POST["nom"]);
            $mdp = strip_tags($_POST["mdp"]);
        // mettre une condition pour ne pas inserer d'utilisateur déja engistré dans la bdd
        // on génére une requete qui va vérifier selon le login 
         $sql=$db->prepare("SELECT nom FROM user WHERE nom=:nom LIMIT 1");
    // on execute la require
     $sql->execute ([
        "nom"=>$nom
    ]);
    $tab=$sql->fetchAll();
    // Si le nombre de retour est supérieur à 0 donc s'il y en a au moins 1 cela veut dire que l'utilisateur existe déja
        if(count($tab)>0){
            // echo "<script>alert(\"l'utilisateur existe déjà \")</script>";
             echo "l'utilisateur existe déjà";
        }else{
            // Sinon insérer l'utilisateur / user  
                $sql=$db->prepare("INSERT INTO $nom_table(nom,mdp) VALUES(:nom,:mdp)");
                $nom = strip_tags($_POST["nom"]);
                $mdp = strip_tags($_POST["mdp"]);
                $sql->execute([
                    "nom"=>$nom,
                    //utilisation de password_hash(parametre, methode de chiffrement) pour chiffrer le mot de passe dans la bdd 
                        "mdp" => password_hash($mdp, PASSWORD_BCRYPT)     
                ]);
            }   
            //utilisation de la fonction pour fermer la connexion à la base de données trouverunjobpourmoussa
                close_connexion($db);
        }   

}

function connexion_user(){

        
    if(isset($_POST["nom"]) && isset($_POST["mdp"]))
    {
        //var_dump($_POST);
        //utilisation de la fonction connexion_bdd()pour se connecter à la bdd trouverunjobpourmoussa
            $db = connexion_bdd();
            $nom_table = "user";
        // On récupére les données depuis le formulaire
        // On récupére l'utilisateurs 
            $nom = strip_tags($_POST["nom"]);
            $mdp = strip_tags($_POST["mdp"]);
            $remdp = strip_tags($_POST["remdp"]);
            //à utiliser pour comparer le mot de passe et la confirmation
           // $remdp = strip_tags($_POST["remdp"]);
            $sql=$db->prepare("SELECT nom,mdp FROM $nom_table WHERE nom=:nom LIMIT 1");
            $sql->execute([
                "nom" => $nom
            ]);
            $tab=$sql->fetchAll();
            //print_r($tab);
            //ajouter une condition pour vérifier la concordance entre les champs mdp et remdp
            if($mdp == $remdp)
                {        // On verifie s'il y en y a un qui répond à l'authentification
                    if(count($tab)>0 && password_verify($mdp, $tab[0]["mdp"])){
                        echo "connexion réussie, bienvenu $nom";
                    }           
                // S'il y a un soucis alors il y a un message d'erreur qui sera intégré
                    else
                    {
                        echo "On ne se connait pas. Va t'en.";
                    }
                 } else {
                        echo "le mot de passe et la confirmation ne correspondent pas.";
          }
        }

}
