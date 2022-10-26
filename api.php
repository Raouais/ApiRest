<?php

header('content-Type: application/json');
require './database/database.php';
require './tablesResquest/getTables.php';

function getItems() {

    $table = $_REQUEST["table"];
    $db = Database::connect();

    $base = NULL;
    
    if(!empty($_REQUEST['type'])){
        $base = $db->query('SELECT * FROM `'.$table.'` WHERE type = "'.$_REQUEST['type'].'"');
    } else {
        $base =  $db->query('SELECT * FROM `'.$table.'`');
    }
    $statement = $base->fetchAll();


    $results["results"]["nb_$table"] = count($statement);
    $results["results"]["$table"] = $statement;

    echo json_encode($results);

    Database::disconnect();
}

function isTableGot(){

    $db = Database::connect();
    $base = $db->query('SHOW TABLES FROM '.Database::getDBName().'');
    $statement = $base->fetchAll();
    
    $isTable = false;
    $table = $_REQUEST["table"];
    
    for($i = 0; $i < sizeof($statement) && !$isTable; $i++)
        if(strcmp($statement[$i][0],$table) === 0)
            $isTable = true;

    if(!$isTable){
        $retour["success"] = false;
        $retour["message"] = "The table wasn't taken";
        echo json_encode($retour);
    }

    Database::disconnect();


    return $isTable;

}

// function isTablesSend(){

//     $columns = getTableColumns();
//     $isSend = true;
//     for($i = 0; $i < sizeof($columns)-1 && $isSend; $i++)
//         if(empty($_REQUEST[$columns[$i][0]]))
//             $isSend = false;
        
//     if(!$isSend){
//         $retour["success"] = false;
//         $retour["message"] = "The table wasn't found";
//         echo json_encode($retour);
//     }

//     Database::disconnect();

//     return $isSend;

// }

function update(){

    $body = getBody();

    if(!empty($body)){
        $columns = getTableColumns();
        $request = getUpdateRequest();
        $db = Database::connect();
        
        $statement = $db->prepare($request);
        for($i = 0; $i < sizeof($columns); $i++)
            if($i == 0)
                $statement->bindValue(':'.$columns[$i][0], $body[$columns[$i][0]],PDO::PARAM_INT);
            else
                $statement->bindValue(':'.$columns[$i][0], $body[$columns[$i][0]]);
        
        $statement->execute();
        
        Database::disconnect();

        $retour["success"] = true;
        $retour["message"] = "The item was updated";

        echo json_encode($retour);
    } else {
        
        $retour["success"] = false;
        $retour["message"] = "The item wasn't updated";
        
        echo json_encode($retour);
    }
}

function getHighestId(){
    $db = Database::connect();
    $statement = $db->query("SELECT * FROM `".$_REQUEST["table"]."` ORDER BY id DESC LIMIT 0, 1");
    $highestId = $statement->fetchAll()[0][0];
    Database::disconnect();
    return $highestId;
}

function insert(){

    $body = getBody();

    if(!empty($body)){
        $columns = getTableColumns();
        $request = getPostRequest();
        $highestId = getHighestId() + 1;
        $db = Database::connect();
        
        $statement = $db->prepare($request);
        for($i = 0; $i < sizeof($columns); $i++){
            if($i == 0 && $body['id'] == 0)
                $statement->bindValue(':'.$columns[$i][0] ,$highestId);
            else 
                $statement->bindValue(':'.$columns[$i][0], $body[$columns[$i][0]]);
        }
        
        $statement->execute();
        
        Database::disconnect();

        $retour["success"] = true;
        $retour["message"] = "The item was added";

        echo json_encode($retour);
    } else {
        
        $retour["success"] = false;
        $retour["message"] = "The item wasn't be added";
        
        echo json_encode($retour);
    }
}

function delete(){

    $body = getBody();

    if(!empty($body)){
        $db = Database::connect();
        $statement = $db->query('DELETE FROM '.$_REQUEST["table"].' WHERE '.$_REQUEST["table"].'.id='.$body["id"]);
        $statement->execute();
        Database::disconnect();

        $retour["success"] = true;
        $retour["message"] = "The item was deleted";

        echo json_encode($retour);
    } else {
        
        $retour["success"] = false;
        $retour["message"] = "The item wasn't deleted";
        
        echo json_encode($retour);
    }
}


function getResquest(){

    if(isTableGot()){
        switch($_SERVER["REQUEST_METHOD"]){
            case 'GET':
                getItems();
            break;
            
            case 'UPDATE':
            case 'PATCH':
                update();
            break;
            
            case 'POST':
                insert();
            break;
            
            case 'DELETE':
                delete();
            break;
        }
    }
}

function getBody(){
    if(!empty($_POST)) {
        // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
        // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
        return $_POST;
    }
    // when using application/json as the HTTP Content-Type in the request 
    $post = json_decode(file_get_contents('php://input'), true);
    if(json_last_error() == JSON_ERROR_NONE) {
        return $post;
    }
    return [];
}

function getDBName(){
    if(!empty($_REQUEST["db"])){
        Database::setDBName($_REQUEST["db"]);
        getResquest();
    } else {
        $retour["success"] = false;
        $retour["message"] = "The database wasn't found";
        echo json_encode($retour);
    }
}


getDBName();

