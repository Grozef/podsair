<?php
require_once "acces_bdd.php";

//fonction pour créer la Base De Données
function creation_bdd(){
    try
    { 
    //utilisation de la fonction pour initialiser la connexion à la bdd
    $db = connexion_bdd_mysql();
    $sql = "CREATE DATABASE IF NOT EXISTS TrouverUnJobPourMoussa";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    //message de confirmation
    echo "creation bdd TrouverUnJobPourMoussa ok ou bdd TrouverUnJobPourMoussa déja existante</p> ";

    }
    catch (Exception $e)
    {
        'Erreur : ' . $e->getMessage();
    }
    finally{
        //utilisation de la fonction pour fermer la connexion à la bdd
        close_connexion($db);
    }

       
};


//fonction pour créer une table
function creation_table(){
    try{
        //utilisation de la fonction de connexion à la base de données
        //Attention au nom d la base de donnée préalablement crée => Wassingue
        $db = connexion_bdd();
        
// création d'une table nommée proteine

        $tablePers = "CREATE TABLE IF NOT EXISTS proteine (
            id INT NOT NULL AUTO_INCREMENT,
            nom VARCHAR(150) NOT NULL,
            mail VARCHAR(200) NOT NULL,
            messages TEXT NOT NULL,
            PRIMARY KEY (id)       
        )";
        $stmt = $db->prepare($tablePers);
        $stmt->execute();
        //message de confirmation   
        echo "Table proteine créée ou déjà existante<br>";

        // création d'une table nommée user

        $tableUser = "CREATE TABLE IF NOT EXISTS user (
            id INT NOT NULL AUTO_INCREMENT,
            nom VARCHAR(150) NOT NULL,
            mdp VARCHAR(200) NOT NULL,
            PRIMARY KEY (id)       
        )";
        $stmt = $db->prepare($tableUser);
        $stmt->execute();
        //message de confirmation   
        echo "Table user créée ou déjà existante<br>";

        }
catch (Exception $e)
        {
            'Erreur : ' . $e->getMessage();        
        }
finally{
        //utilisation de la fonction de fermeture de connexion à la base de données
        close_connexion($db);
        }  
        
        
}


