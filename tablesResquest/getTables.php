<?php 


function getUpdateRequest(){
    $request = "UPDATE `".$_REQUEST["table"]."` SET ". generateUpdateRequest();
    return $request." WHERE id = :id;";
}

function getPostRequest(){
    $request = "INSERT INTO `".$_REQUEST["table"]."` ".generatePostRequest();
    return $request;
}

function getTableColumns(){
    $db = Database::connect();
    $statement = $db->query("SHOW COLUMNS FROM `".$_REQUEST["table"]."`");
    $res = $statement->fetchAll();
    Database::disconnect();
    return $res;
}

function generatePostRequest(){
    $table = "";
    $columns = getTableColumns();
    
    for($i = 0; $i < sizeof($columns) ; $i++){
        if($i == 0)
            $table.="(".$columns[$i][0].", ";
        else if ($i == sizeof($columns) -1 )
            $table.=$columns[$i][0]." )";
        else 
            $table.=$columns[$i][0].", ";
    }

    for($i = 0; $i < sizeof($columns) ; $i++){
        if($i == 0)
            $table.=" VALUES ( :".$columns[$i][0].", ";
        else if ($i == sizeof($columns) -1 )
            $table.=" :".$columns[$i][0]." ); ";
        else 
            $table.=" :".$columns[$i][0].", ";
    }


    return $table;
}

function generateUpdateRequest(){
    $table = "";
    $columns = getTableColumns();
    

    for($i = 1; $i < sizeof($columns) ; $i++){
        if($i == sizeof($columns) - 1)
            $table.=$columns[$i][0]." = :".$columns[$i][0];
        else
            $table.=$columns[$i][0]." = :".$columns[$i][0].", ";
    }
    return $table;
}