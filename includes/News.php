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

    public function newExists($newId){
        $sql = "SELECT id FROM news WHERE id = :newId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':newId', $newId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

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

    private function editNew($title, $url, $content, $newId){
        $currentTime = date('Y-m-d H:i:s');
        $sql = "UPDATE news SET title = :title, link = :link, content = :content, lastUpdate = :lastUpdate WHERE id = :newId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':link', $url);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':lastUpdate', $currentTime);
        $stmt->bindParam(':newId', $newId);
        $stmt->execute();
    }
    private function deleteNew($newId){
        $sql = "DELETE FROM news WHERE id = :newId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':newId', $newId);
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
    private function isInputInvalid($input){
        if (!preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)){
            return true;
        } else{
            return false;
        }
    }
    private function isUrlInvalid($input){
        if (!preg_match('/^(https?:\/\/)?[a-z0-9\-\.]+\.[a-z]{2,}(\/[a-z0-9\-._~:\/?#\[\]@!$&\'()*+,;=]*)?$/i', $input)){
            return true;
        } else{
            return false;
        }
    }

    public function create($title, $url, $content){
        if ($this->isInputEmpty($title) || $this->isInputEmpty($url) || $this->isInputEmpty($content)){
            $this->errors["createNew"] = "empty";
        } else if (!$this->isInputEmpty($title) && $this->isInputInvalid($title)){
            $this->errors["createNew"] = "titleInvalid";
        } else if (!$this->isInputEmpty($url) && $this->isUrlInvalid($url)){
            $this->errors["createNew"] = "urlInvalid";
        } else if (!$this->isInputEmpty($content) && $this->isInputInvalid($content)){
            $this->errors["createNew"] = "contentInvalid";
        }

        //Erros
        if ($this->errors){
            header("Location: ../adminNews.php?create=failed");
            die();
        }

        $userId = $_SESSION["userId"];
        $author = $_SESSION["clientFirstName"]." ".$_SESSION["clientLastName"];
        $this->createNew($title, $url, $content, $author, $userId);
    }

    public function edit($title, $url, $content, $newId){
        if ($this->isInputEmpty($title) || $this->isInputEmpty($url) || $this->isInputEmpty($content)){
            $this->errors["updateNew"] = "empty";
        } else if (!$this->isInputEmpty($title) && $this->isInputInvalid($title)){
            $this->errors["updateNew"] = "titleInvalid";
        } else if (!$this->isInputEmpty($url) && $this->isUrlInvalid($url)){
            $this->errors["updateNew"] = "urlInvalid";
        } else if (!$this->isInputEmpty($content) && $this->isInputInvalid($content)){
            $this->errors["updateNew"] = "contentInvalid";
        }

        //Erros
        if ($this->errors["updateNew"]){
            header("Location: ../adminNews.php?saved=failed");
            die();
        }
        $this->editNew($title, $url, $content, $newId);

    }

    public function delete($newId){
        if (!$this->newExists($newId)){
            header("Location: ../adminNews.php?delete=failed");
            die();
        }
        $this->deleteNew($newId);
    }


    public function loadNews($newsQt){
        $sql="SELECT * FROM news ORDER BY published DESC LIMIT ?;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$newsQt, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function loadNew($newId){
        $sql="SELECT title, link, content FROM news WHERE id = :newId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':newId', $newId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    
}