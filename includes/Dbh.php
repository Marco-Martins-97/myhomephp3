<?php

class Dbh{
    private $host = "localhost";
    private $dbname = "myhome";
    private $dbusername = "root";
    private $dbpassword = "";

    public function connect(){
        try{
            $pdo = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->dbusername, $this->dbpassword);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e){
            die ("Conecção Falhou: ".$e->getMessage());
        }
    }
}