<?php
require_once 'Dbh.php';
session_start();

class Login{
    private $username;
    private $pwd;
    
    private $conn;
    private $errors = [];
    private $sessionId;

    public function __construct($username, $pwd){
        $this->username = $username;
        $this->pwd = $pwd;

        $dbh = new Dbh();
        $this->conn = $dbh->connect();
    }

    //SQL
    private function getUserData(){
        $sql = "SELECT * FROM users WHERE username = :username;";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(":username", $this->username);
        $stmt -> execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    private function getClientData($userId){
        $sql = "SELECT * FROM clients WHERE userId = :userId;";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(":userId", $userId);
        $stmt -> execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //Verficação de dados
    private function isInputEmpty($input){
        if (empty($input)){
            return true;
        } else{
            return false;
        }
    }

    private function isUsernameWrong($result){
        if(!$result){
            return true;
        } else{
            return false;
        }
    }

    private function isPasswordWrong($hasedpwd){
        if (!password_verify($this->pwd, $hasedpwd)){
            return true;
        } else{
            return false;
        }
    }

    //Cria Sessão
    private function createSessionId($userData){
        $userId = $userData["id"];
        $newSessionId = session_create_id();
        $this->sessionId = $newSessionId."_".$userId;
        session_id($this->sessionId);
        
        $_SESSION["userId"] = $userId;
        $_SESSION["username"] = $userData["username"];
        $_SESSION["userRole"] = $userData["userRole"];
        $_SESSION["activated"] = $userData["activated"];
        if ($userData["activated"]){
            $clientData = $this->getClientData($userId);
            $_SESSION["clientfirstName"] = $clientData["firstName"];
            $_SESSION["clientLastName"] = $clientData["lastName"];
        }
        $_SESSION["last_regeneration"] = time();
    }

    //Logar User
    public function login(){
        $userData = $this->getUserData();

        //Validaçao de dados
        if ($this->isInputEmpty($this->username) || $this->isInputEmpty($this->pwd)){
            $this->errors["login"] = "Campos de preenchimento obrigatório!";
        }

        if ($this->isUsernameWrong($userData)){
            $this->errors["login"] = "O nome de utilizador está incorreto.";
        }

        if (!$this->isUsernameWrong($userData) && $this->isPasswordWrong($userData["pwd"])){
            $this->errors["login"] = "A password está incorreta.";
        }

        //Erros
        if ($this->errors){
            header("Location: ../login.php?login=failed");
            $_SESSION["loginError"] = $this->errors;
            die();
        }
        //Inicia a Sessão
        $this->createSessionId($userData);

        unset($_SESSION["loginError"]);

        $page = (isset($_SESSION["activated"]) && $_SESSION["activated"]) ? "index.php" : "profile.php";
        header("Location: ../" . $page . "?login=success");
        die();
    }
}