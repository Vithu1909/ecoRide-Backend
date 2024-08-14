<?php
 namespace classes;
 use PDO;
 use PDOException;

 class DBconnector{
    private $host = "localhost";
    private $dbname = "ecoRide";
    private $dbuser = "root";
    private $dbpwd = "";
    
    public function getConnection(){
      $dsn = "mysql:host=$this->host;dbname=$this->dbname";
      try {
         $con = new PDO($dsn,$this->dbuser,$this->dbpwd);
         return $con;
      } catch (PDOException $ex) {
         echo("ERROR :".$ex->getMessage());
      }
    }
 }

 ?>