<?php

require 'database.php';

header('content-Type: application/json');
               
$retour;

if( !empty($_REQUEST["commentaire"]) && !empty($_REQUEST["name"]) && !empty($_REQUEST["age"]) ){
    
    if( intval($_REQUEST["age"]) > 0 && intval($_REQUEST["age"]) < 120 ){
        
        $db = Database::connect();
        $statement = $db->prepare("INSERT INTO `commentaire` (`id`, `name`, `age`, `commentaire`) VALUES (NULL, :name , :age , :commentaire)");
        $statement->bindParam(':name', $_REQUEST["name"]);
        $statement->bindParam(':age', $_REQUEST["age"]);
        $statement->bindParam(':commentaire', $_REQUEST["commentaire"]);
        $statement->execute();
        Database::disconnect();
        $retour["success"] = true;
        $retour["message"] = "Le commentaire à été ajouté";
        $retour["results"] = $_REQUEST["name"]." ".$_REQUEST["age"]." ".$_REQUEST["commentaire"];
    } else {
        $retour["success"] = false;
        $retour["message"] = "Connexion raté";    
    }

} else {
    $retour["success"] = false;
    $retour["message"] = "Connexion raté";
    $retour["essaie"] = "";
}

echo json_encode($retour);
