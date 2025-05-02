<?php
require_once 'Dbh.php';
session_start();

class News{
    private $conn;
    private $errors = [];

    public function __construct(){
        $dbh = new Dbh();
        $this->conn = $dbh->connect();
    }
    //SQL
    private function createNew($title, $url, $content, $author, $userId){
        $sql = "INSERT INTO news (title, link, content, author, userId) VALUES (:title, :link, :content, :author, :userId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':link', $url);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }

    //Verficação de dados
    private function isInputEmpty($input){
        if (empty($input)){
            return true;
        } else{
            return false;
        }
    }

    public function create($title, $url, $content){
        if ($this->isInputEmpty($title) || $this->isInputEmpty($url) || $this->isInputEmpty($content)){
            $this->errors["createNew"] = "empty";
        }
        //Erros
        if ($this->errors){
            header("Location: ../adminNews.php?create=failed");
            die();
        }
        $userId = $_SESSION["userId"];
        $author = $_SESSION["clientFirstName"].$_SESSION["clientFastName"];
        $this-> createNew($title, $url, $content, $author, $userId);
    }
    public function edit(){

    }
    public function delete(){

    }
    public function loadNews($newsQt){
        //carrega a quantidade de noticias pedigas default : 5
        $sql="SELECT * FROM news ORDER BY published DESC LIMIT ?;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$newsQt, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    
}