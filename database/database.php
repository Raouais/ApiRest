<?php


class Database{
    
    private static $dbHost = "localhost";
    private static $dbName = "";
    private static $dbUser = "root";
    private static $dbUserPassword = "";
    private static $connection = null;

    public static function connect (){

        try{

            self::$connection = new PDO('mysql:host='.self::$dbHost.';dbname='.self::$dbName,self::$dbUser,self::$dbUserPassword,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
        }
        catch(PDOException $e){
            die($e->getMessage());
        }
        
        return self::$connection;
    }

    public static function disconnect (){
            $connection = null;
    }
    
    public static function getDBName(){
        return self::$dbName;
    }

    public static function setDBName($name){
        self::$dbName = $name;
    }
}
   
Database::connect();

?>