<?php
require_once 'Dbh.php';

class Signup{
    private $username;
    private $pwd;
    private $confirmPwd;
    private $userRole = "client";
    private $activated = false;

    private $conn;
    private $errors = [];

    public function __construct($username, $pwd, $confirmPwd){
        $this->username = $username;
        $this->pwd = $pwd;
        $this->confirmPwd = $confirmPwd;

        $dbh = new Dbh();
        $this->conn = $dbh->connect();
    }

    //SQL
    private function getUsername() {
        $sql = "SELECT * FROM users WHERE username = :username;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    private function createNewUser(){
        $sql = "INSERT INTO users (username, pwd, userRole, activated) VALUES (:username, :pwd, :userRole, :activated)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $this->username);

        $options = ["cost" => 12];
        $hashedPwd = password_hash($this->pwd, PASSWORD_BCRYPT, $options);

        $stmt->bindParam(':pwd', $hashedPwd);
        $stmt->bindParam(':userRole', $this->userRole);
        $stmt->bindParam(':activated', $this->activated);
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

    private function isPwdNoMatch(){
        if ($this->pwd !== $this->confirmPwd){
            return true;
        } else{
            return false;
        }
    }

    private function isUsernameTaken(){
        if ($this->getUsername()){
            return true;
        } else{
            return false;
        }
    }

    private function isInputInvalid($input){
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $input)){
            return true;
        } else{
            return false;
        }
    }

    private function isPwdShort($input){
        if(strlen($input) < 3){
            return true;
        } else {
            return false;
        }
    }

    //Registar user
    public function signup(){
        //Username
        if ($this->isInputEmpty($this->username)){
            $this->errors["username"] = "empty";
        } else if ($this->isInputInvalid($this->username)){
            $this->errors["username"] = "invalid";
        } else if ($this->isUsernameTaken()){
            $this->errors["username"] = "taken";
        }
        //Pwd
        if ($this->isInputEmpty($this->pwd)){
            $this->errors["pwd"] = "empty";
        } else if ($this->isPwdShort($this->pwd)){
            $this->errors["pwd"] = "short";
        } else if ($this->isInputEmpty($this->confirmPwd)){
            $this->errors["confirmPwd"] = "empty";
        } else if ($this->isPwdShort($this->confirmPwd)){
            $this->errors["confirmPwd"] = "short";
        } else if ($this->isPwdNoMatch()){
            $this->errors["pwd"] = "noMatch";
        }
        //Erros
        if ($this->errors){
            header("Location: ../signup.php?signup=failed");
            die();
        }
        //create user
        $this->createNewUser();

        header("Location: ../login.php?signup=success");
        die();
    }
}