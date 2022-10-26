<?php

header('content-Type: application/json');
require './database/database.php';

               
$db = Database::connect();
$param = "";
$comment;

if( !empty($_REQUEST["param"])){
    $param = $_REQUEST["param"];

    $base = $db->query('SELECT * FROM '.$param);

    $statement = $base->fetchAll();
    $comment["results"]["nb_$param"] = count($statement);
    $comment["results"]["$param"] = $statement;
    $retour["success"] = true;
    $retour["message"] = "Connexion réussie";
} else {
    $retour["success"] = false;
    $retour["message"] = "Echec de la connexion";
}

echo json_encode($comment);

Database::disconnect();