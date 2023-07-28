<?php

//<li><input type="reset" class="style2" value="Reset" /></li>

//bibliotheque de fonctions 
//Ne pas oublier d'appeler les fonctions dans index.php sinon elles ne se declenchent pas toutes seules les fénéantes

//Possibilité de créer des alias afin de faciliter la modification et la maintenabilité du code 

/*
define("USER_BDD", "root");
define("NAME_BDD", "Wassingue");
define("HOST_BDD", "localhost");
define("MDP_BDD", "");
define("NAME_BDD_PRINCIPAL", "mysql");
define("NAME_TABLE", "yahourt");
*/





// fonction pour se connecter à mysql une premiere fois afin de pouvoir créer une bdd

//connexion première
function connexion_bdd_mysql()
{
    try {
        $db = new PDO('mysql:host=localhost;dbname=mysql;charset=utf8', 'root', '');
        echo "connexion réussie à la base de données mysql<br>";
    } catch (Exception $e) {
        'Erreur : ' . $e->getMessage();
    }
    return $db;
    close_connexion($db);
};

// fonction pour se connecter à une base de données existante, en l'occurence Wassingue
//connexion à la base de données Wassingue
function connexion_bdd()
{
    try {
        $db = new PDO('mysql:host=localhost;dbname=Wassingue;charset=utf8', 'root', '');
        echo "connexion réussie à la base de données<br>";
    } catch (Exception $e) {
        'Erreur : ' . $e->getMessage();
    }
    return $db;
};

//fermeture de la connexion à la bdd
function close_connexion($db)
{

    $db = null;
}


//fonction pour créer une table
function creation_table()
{
    try {
        //utilisation de la fonction de connexion à la base de données
        //Attention au nom d la base de donnée préalablement crée => Wassingue
        $db = connexion_bdd();

        // création d'une table nommée yahourt

        $tablePers = "CREATE TABLE IF NOT EXISTS yahourt /* NAME_TABLE*/ (
            id INT NOT NULL AUTO_INCREMENT,
            nom VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            descript TEXT NOT NULL,
            PRIMARY KEY (id)       
        )";
        $stmt = $db->prepare($tablePers);
        $stmt->execute();
        //message de confirmation   
        echo "Table pers créée ou déjà existante<br>";
    } catch (Exception $e) {
        'Erreur : ' . $e->getMessage();
    } finally {
        //utilisation de la fonction de fermeture de connexion à la base de données
        close_connexion($db);
    }
}

/*Lors de l'insertion de données dans une base de données, les contraintes NOT NULL définissent que certaines colonnes ne peuvent pas contenir de valeurs NULL (c'est-à-dire vides). Cependant, dans certains cas, lorsque vous insérez des données à partir d'un formulaire, les champs vides peuvent être interprétés comme des chaînes de caractères vides ("") plutôt que comme des valeurs NULL.

Cela signifie que même si un champ est laissé vide dans le formulaire, lors de la soumission, PHP considérera ce champ comme une chaîne vide et l'insérera dans la base de données. Ainsi, les contraintes NOT NULL ne sont pas violées, car une chaîne vide est une valeur valide pour les colonnes définies comme NOT NULL.

Si vous souhaitez empêcher l'insertion de champs vides dans des colonnes NOT NULL, vous pouvez effectuer une vérification supplémentaire avant d'exécuter la requête d'insertion. Par exemple, vous pouvez ajouter une condition pour vérifier si les champs obligatoires sont vides avant d'exécuter l'instruction d'insertion. Si un champ obligatoire est vide, vous pouvez afficher un message d'erreur ou empêcher l'insertion des données tant que tous les champs obligatoires ne sont pas remplis.

En résumé, lors de l'insertion de données via un formulaire, les champs vides sont généralement interprétés comme des chaînes vides et ne violent pas les contraintes NOT NULL. Pour empêcher l'insertion de champs vides dans des colonnes NOT NULL, vous pouvez effectuer des vérifications supplémentaires avant l'exécution de la requête d'insertion. */


//fonction pour insérer manuellement des données dans la table NAME_TABLE
//sert surtout à tester sa base de données 

function inser_brut()
{

    //utilisation de la fonction de connexion à la bdd NAME_BDD
    $db = connexion_bdd();
    $nom_table = "contact";
    $nom = "Joul";
    $mail = "Croissant@youpimail.com";
    $descript = "on dit chocolatines";

    $sql = "INSERT INTO $nom_table (nom, mail, descript)
            VALUES (:nom, :mail, :descript)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":nom", $nom);
    $stmt->bindParam(":mail", $mail);
    $stmt->bindParam(":descript", $descript);
    $stmt->execute();

    //message de confirmation
    echo "Insertion réussie.<br>";
    //utilisation de la fonction pour fermer la connexion à la bdd
    close_connexion($db);
}

//fonction pour inserer les données reçues via le formulaire contact du portfolio
function inser()
{

    //si la valeur passé via la methode POST est définie ET est non nulle cf commentaire au dessus => isset
    //pour vérifier que le champs du formulaire est correctement rempli

    if (isset($_POST["submit"]) && $_POST["submit"] === "Envoyer le message") {
        //utilisation de la fonction pour se connecter à la bdd portfolio_bdd
        $db = connexion_bdd();
        $nom_table = "contact";

        //modifier ? $_POST["nom"] : "" pour faire en sorte que le formulaire n'accepte pas les chaines de charactères vides
        //if (!empty($nom)) => à affiner
        $nom = isset($_POST["nom"]) ? $_POST["nom"] : "";
        $mail = isset($_POST["mail"]) ? $_POST["mail"] : "";
        strip_tags($nom);
        strip_tags($mail);
        $descript = isset($_POST["descript"]) ? $_POST["descript"] : "";

        $sql = "INSERT INTO $nom_table (nom, mail, descript)
                VALUES (:nom, :mail, :descript)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":descript", $descript);
        $stmt->execute();
        //message de confirmation
        echo "Insertion réussie.";
        //utilisation de la fonction pour se déconnecter de la bdd
        close_connexion($db);
    } else {
        $db = connexion_bdd();
        //message de confirmation
        echo "Le formulaire n'a pas été soumis correctement.";
        //utilisation de la fonction de fermeture de connexion à la bdd
        close_connexion($db);
    }
}

/*
function inser()
{
    if (isset($_POST["submit"]) || $_POST["submit"] == "submit") {
        // Vérifier que les champs obligatoires ne sont pas vides
        echo ("🍕");
        if (!empty($_POST["nom"]) && !empty($_POST["mail"])) {
            // Utilisation de la fonction pour se connecter à la bdd TrouverUnJobPourMoussa
            $db = connexion_bdd();
            $nom_table = "proteine";
            $nom = strip_tags($_POST["nom"]);
            $mail = strip_tags($_POST["mail"]);
            $descript = isset($_POST["messages"]) ? $_POST["messages"] : "";

            // La requête d'insertion n'est exécutée que si les champs obligatoires ne sont pas vides
            $sql = "INSERT INTO $nom_table (nom, mail, messages)
                    VALUES (:nom, :mail, :messages)";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":mail", $mail);
            $stmt->bindParam(":messages", $descript);
            $stmt->execute();
            // Message de confirmation
            echo "Insertion réussie, Modafoka";
            // Utilisation de la fonction pour se déconnecter de la bdd
            close_connexion($db);
        } else {
            // Afficher un message d'erreur si des champs obligatoires sont vides
            echo "Veuillez remplir tous les champs obligatoires (nom, mail).";
        }
    } else {
        $db = connexion_bdd();
        // Message de confirmation
        echo "Le formulaire n'a pas été soumis correctement.";
        // Utilisation de la fonction de fermeture de connexion à la bdd
        close_connexion($db);
    }
}
*/
//si ce qu'on a récupéré via la methode POST (login et pass) sont définis

function inser_incription(){

if( isset($_POST["login"]) && isset($_POST["pass"]) )
{
    require __DIR__."/../config.php";
    $pass = $_POST["pass"];
    $repass = $_POST["repass"];
    $login = $_POST["login"];
    // on génére une requete qui va vérifier selon le login 
    $sel=$pdo->prepare("select id from utilisateur where login=:login limit 1");
    // on execute la require
    $sel->execute([
        "login" => $login
    ]);
    $tab=$sel->fetchAll();
    // Si le nombre de retour est supérieur à 0 donc s'il y en a au moins 1 cela veut dire que l'utilisateur existe déja
    var_dump($tab);
    if(count($tab)>0){
        session_start();
        $erreur="Login existe déjà";
        $_SESSION["flash"] = $erreur;
        header("location:login.php");
    }
    else{
        //si le mot de passe et la confirmation du mot de passe concordent    
        if($pass === $repass){

        // Si l'utilisateur n'existe pas on insere l'utilisateur
        $sql=$pdo->prepare("insert into utilisateur(nom,prenom,login,pass,role) values(:nom,:prenom,:login,:pass, 'utilisateur')");
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $pass = $_POST["pass"];
        $sql->execute([
            "nom"=>$nom,
            "prenom"=>$prenom,
            "login"=>$login, 
            "pass" => password_hash($pass, PASSWORD_BCRYPT)     
        ]);
        // Si la requete d'insertion est reussie alors on redirige vers la page de login
        header("location:login.php");
        }   
        // si le mot de passe et la confirmation du mot de passe ne sont pas identiques
        else {
            //on initialise la session qui n'est pas accessible depuis l'interieur de la fonction
            session_start();
            //message d'erreur
            $erreur="Les mots de passes ne sont pas identiques";
            $_SESSION["flash"] = $erreur;
            //redirection vers la page d'inscription 
            header("location:inscription.php");
        }
    }   
}
else{
    session_start();
    $erreur="Les mots de passes ne sont pas identiques";
    $_SESSION["flash"] = $erreur;
    header("location:inscription.php");
}
};