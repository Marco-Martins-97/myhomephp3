<?php
require_once 'Dbh.php';
// session_start();

class Profile{
    private $username;
    private $userData;
    private $required = ["firstName", "email", "birthDate"];

    private $conn;
    private $errors = [];

    public function __construct($username){
        $this->username = $username;
        
        $dbh = new Dbh();
        $this->conn = $dbh->connect();
        
        $this->userData = $this->getUserData();
    }

    //SQL
    private function getUserData(){
        $sql = "SELECT * FROM users WHERE username = :username;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    private function getEmail($input){
        $sql = "SELECT * FROM clients WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $input);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    private function createClientInDatabase($firstName, $lastName, $email, $birthDate, $nif, $phone, $clientAddress, $district){
        $sql = "INSERT INTO clients (firstName, lastName, email, birthDate, nif, phone, clientAddress, district, userId) 
                VALUES (:firstName, :lastName, :email, :birthDate, :nif, :phone, :clientAddress, :district, :userId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->bindParam(':nif', $nif);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':clientAddress', $clientAddress);
        $stmt->bindParam(':district', $district);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();    
    }
    
    private function updateClientInDatabase($firstName, $lastName, $email, $birthDate, $nif, $phone, $clientAddress, $district){
        $sql = "UPDATE clients 
                SET firstName = :firstName, lastName = :lastName, email = :email, birthDate = :birthDate, nif = :nif, phone = :phone, clientAddress = :clientAddress, district = :district
                WHERE clientId = :clientId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->bindParam(':nif', $nif);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':clientAddress', $clientAddress);
        $stmt->bindParam(':district', $district);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();    
    }

    private function activateAccount($activated){
        $sql = "UPDATE users SET activated = :activated WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":activated", $activated);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        $_SESSION["activated"] = 1;
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
        } else if (!$this->isInputEmpty($firstName) && $this->isNameInvalid($firstName)){
            $this->errors["firstName"] = "invalid";
        }
        
        if ($this->isInputRequired("lastName") && $this->isInputEmpty($lastName)){
            $this->errors["lastName"] = "empty";
        } else if (!$this->isInputEmpty($lastName) && $this->isNameInvalid($lastName)){
            $this->errors["lastName"] = "invalid";
        } 
        
        if ($this->isInputRequired("email") && $this->isInputEmpty($email)){
            $this->errors["email"] = "empty";
        } else if (!$this->isInputEmpty($email) && $this->isEmailInvalid($email)){
            $this->errors["email"] = "invalid";
        }  else if (!$this->isInputEmpty($email) && $this->isEmailTaken($email)){
            $this->errors["email"] = "taken";
        }
        
        if ($this->isInputRequired("birthDate") && $this->isInputEmpty($birthDate)){
            $this->errors["birthDate"] = "empty";
        } else if (!$this->isInputEmpty($birthDate) && $this->isDateInvalid($birthDate)){
            $this->errors["birthDate"] = "invalid";
        }
        
        if ($this->isInputRequired("nif") && $this->isInputEmpty($nif)){
            $this->errors["nif"] = "empty";
        } else if (!$this->isInputEmpty($nif) && $this->isNumInvalid($nif)){
            $this->errors["nif"] = "invalid";
        }
        
        if ($this->isInputRequired("phone") && $this->isInputEmpty($phone)){
            $this->errors["phone"] = "empty";
        } else if (!$this->isInputEmpty($phone) && $this->isNumInvalid($phone)){
            $this->errors["phone"] = "invalid";
        }
        
        if ($this->isInputRequired("clientAddress") && $this->isInputEmpty($clientAddress)){
            $this->errors["clientAddress"] = "empty";
        } else if (!$this->isInputEmpty($clientAddress) && $this->isAddressInvalid($clientAddress)){
            $this->errors["clientAddress"] = "invalid";
        }
        
        if ($this->isInputRequired("district")  && $this->isInputEmpty($district)){
            $this->errors["district"] = "empty";
        } else if (!$this->isInputEmpty($district) && $this->isNameInvalid($district)){
            $this->errors["district"] = "invalid";
        }
        
        //Erros
        if ($this->errors){
            header("Location: ../profile.php?save=failed");
            die();
        }

        //Salva os dadoa do cliente na base dados
        $userId = $this->userData["id"];
        $activated = $this->userData["activated"];

        if ($activated){
            $this->updateClientInDatabase($firstName, $lastName, $email, $birthDate, $nif, $phone, $clientAddress, $district, $userId);
            echo "Atualizado com  sucesso";
        } else{
            $this->createClientInDatabase($firstName, $lastName, $email, $birthDate, $nif, $phone, $clientAddress, $district, $userId);
            echo "Criado com sucesso <br>";
            $this->activateAccount(1);
            echo "Conta Ativada";
            $_SESSION["clientfirstName"] = $firstName;
            $_SESSION["clientLastName"] = $lastName;
        
        }

        header("Location: ../profile.php?save=success");
        die();
    }
}