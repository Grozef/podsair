<?php
//connexion première
function connexion_bdd_mysql(){
    try {
        $db = new PDO('mysql:host=localhost;dbname=mysql;charset=utf8','root','');
        echo "connexion réussie à la base de données mysql<br>"; 
    }
    catch (Exception $e) {
       'Erreur : ' . $e->getMessage();
    } 
    return $db;
    close_connexion($db);
};

// fonction pour se connecter à une base de données existante, en l'occurence TrouverUnJobPourMoussa
//connexion à la base de données Wassingue
function connexion_bdd(){
    try {
        $db = new PDO('mysql:host=localhost;dbname=TrouverUnJobPourMoussa;charset=utf8','root','');
        echo "connexion réussie à la base de données<br>"; 
    }
    catch (Exception $e) {
       'Erreur : ' . $e->getMessage();
    } 
    return $db;
};

//fermeture de la connexion à la bdd
function close_connexion($db){
    
    $db = null;
     
}