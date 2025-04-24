<?php
require_once 'Dbh.php';
// session_start();

class Profile{
    private $username;
    //private $userData;
    private $required = ["firstName"];

    private $conn;
    private $errors = [];

    public function __construct($username){
        $this->username = $username;
        //$this->userData = $this->getUserData();

        $dbh = new Dbh();
        $this->conn = $dbh->connect();
    }

    //SQL
    /* private function getUserData(){
        $sql = "SELECT * FROM users WHERE username = :username;";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(":username", $this->username);
        $stmt -> execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    } */

    private function getEmail($input){
        $sql = "SELECT * FROM clients WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt -> bindParam(":email", $input);
        $stmt -> execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //Verficação de dados
    private function isInputRequired($input){
        if (in_array($input, $this->required)){
            return true;
        } else{
            return false;
        }
    }
    private function isInputEmpty($input){
        if (empty($input)){
            return true;
        } else{
            return false;
        }
    }

    private function isNameInvalid($input){
        if (!preg_match("/^[a-zA-ZÀ-ÿ' -]+$/", $input)){
            return true;
        } else{
            return false;
        }
    }

    private function isEmailInvalid($input){
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)){
            return true;
        } else{
            return false;
        }
    }

    private function isEmailTaken($input){
        if ($this->getEmail($input)){
            return true;
        } else{
            return false;
        }
    }

    private function isDateInvalid($input){
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $input)){
            return true;
        } else{
            return false;
        }
    }

    private function isNumInvalid($input){
        if (!preg_match("/^[0-9]{9}$/", $input)){
            return true;
        } else{
            return false;
        }
    }

    private function isAddressInvalid($input){
        if (!preg_match("/^[a-zA-Z0-9\s,.-]*$/", $input)){
            return true;
        } else{
            return false;
        }
    }





    public function saveClientData($firstName, $lastName, $email, $birthDate, $nif, $phone, $clientAddress, $district){
        //$userData = $this->getUserData();
        //Validaçao de dados
        if ($this->isInputRequired("firstName") && $this->isInputEmpty($firstName)){
            $this->errors["firstName"] = "empty";
        } else if ($this->isNameInvalid($firstName)){
            $this->errors["firstName"] = "invalid";
        }
        
        if ($this->isInputRequired("lastName") && $this->isInputEmpty($lastName)){
            $this->errors["lastName"] = "empty";
        } else if ($this->isNameInvalid($lastName)){
            $this->errors["lastName"] = "invalid";
        } 
        
        if ($this->isInputRequired("email") && $this->isInputEmpty($email)){
            $this->errors["email"] = "empty";
        } else if ($this->isEmailInvalid($email)){
            $this->errors["email"] = "invalid";
        }  else if ($this->isEmailTaken($email)){
            $this->errors["email"] = "taken";
        }
        
        if ($this->isInputRequired("birthDate") && $this->isInputEmpty($birthDate)){
            $this->errors["birthDate"] = "empty";
        } else if ($this->isDateInvalid($birthDate)){
            $this->errors["birthDate"] = "invalid";
        }
        
        if ($this->isInputRequired("nif") && $this->isInputEmpty($nif)){
            $this->errors["nif"] = "empty";
        } else if ($this->isNumInvalid($nif)){
            $this->errors["nif"] = "invalid";
        }
        
        if ($this->isInputRequired("phone") && $this->isInputEmpty($phone)){
            $this->errors["phone"] = "empty";
        } else if ($this->isNumInvalid($phone)){
            $this->errors["phone"] = "invalid";
        }
        
        if ($this->isInputRequired("clientAddress") && $this->isInputEmpty($clientAddress)){
            $this->errors["clientAddress"] = "empty";
        } else if ($this->isAddressInvalid($clientAddress)){
            $this->errors["clientAddress"] = "invalid";
        }
        
        if ($this->isInputRequired("district")  && $this->isInputEmpty($district)){
            $this->errors["district"] = "empty";
        } else if ($this->isNameInvalid($district)){
            $this->errors["district"] = "invalid";
        }
        
        //Erros
        if ($this->errors){

            echo "<br>ERROS: <br>";
            /* header("Location: ../profile.php?save=failed");
            die(); */
            foreach ($this->errors as $key => $error){
                echo $key." => ".$error."<br>";
            }
        }
        //create user
        // $this->createNewUser();

        // header("Location: ../profile.php?save=success");
        // die();




    }
}