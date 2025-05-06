<?php
require_once 'Dbh.php';
session_start();

class Catalog{
    private $conn;
    private $errors = [];
    private $uploadDir = "../img/uploads/";

    private $uploadImg;

    public function __construct(){
        $dbh = new Dbh();
        $this->conn = $dbh->connect();
    }
    //SQL

    private function saveModel($modelName, $imgName, $area, $bedrooms, $bathrooms, $userId){
        $sql = "INSERT INTO houseModels (modelName, imgName, area, bedrooms, bathrooms, userId) VALUES (:modelName, :imgName, :area, :bedrooms, :bathrooms, :userId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':modelName', $modelName);
        $stmt->bindParam(':imgName', $imgName);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':bedrooms', $bedrooms);
        $stmt->bindParam(':bathrooms', $bathrooms);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }





    private function uploadImg(){
        $error =  $this->uploadImg['error'];
        if ($error !== 0){
            return false;
        }

        if (!file_exists($this->uploadDir)){
            mkdir($this->uploadDir, 0777, true);
        }
        //renomeia a img, substitui espaços e caracters especiais por "_"
        $this->uploadImg['name'] = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", $this->uploadImg['name']);

        $tmpDir = $this->uploadImg['tmp_name'];
        $destDir = $this->uploadDir.$this->uploadImg['name'];
        
        if(move_uploaded_file($tmpDir, $destDir)){
            return true;
        }else {
            return false;
        }
    }

    private function deleteImg(){
        $imgDir = $this->uploadDir.$this->uploadImg['name'];

        if (file_exists($imgDir)) {
            if (unlink($imgDir)) {
                return true;
            } 
        }
        return false;
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
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s,.:()\'"@#+-]+$/', $input)){
            return true;
        } else{
            return false;
        }
    }
    
    private function isFormatInvalid(){
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $type = $this->uploadImg['type'];
        if (!in_array($type, $allowedTypes)){
            return true;
        } else{
            return false;
        }
    }

    private function isSizeInvalid(){
        $maxSize = 2097152; // 2MB
        $size = $this->uploadImg['size'];
        if ($size > $maxSize){
            return true;
        } else{
            return false;
        }
    }
    
        private function isNumEmpty($input){
            if ($input === ""){
                return true;
            } else{
                return false;
            }
        }
        
        private function isNotNumber($input){
            if (!ctype_digit($input)){
                return true;
            } else{
                return false;
            }
        }
        private function isNegative($input){
            if ((int)$input < 0){
                return true;
            } else{
                return false;
            }
        }



    public function createModel($modelName, $img, $area, $bedrooms, $bathrooms){
        $this->uploadImg = $img;
        //Validaçao de dados
        if ($this->isInputEmpty($modelName)){
            $this->errors["name"] = "empty";
        } else if ($this->isInputInvalid($modelName)){
            $this->errors["name"] = "invalid";
        }

        if (!$this->uploadImg()){
            $this->errors["img"] = "upload";
        } else if ($this->isFormatInvalid()){
            $this->errors["img"] = "format";
        } else if ($this->isSizeInvalid()){
            $this->errors["img"] = "size";
        }

        if ($this->isNumEmpty($area)){
            $this->errors["area"] = "empty";
        } else if ($this->isNotNumber($area)){
            $this->errors["area"] = "invalid";
        } else if ($this->isNegative($area)){
            $this->errors["area"] = "negative";
        }
        
        if ($this->isNumEmpty($bedrooms)){
            $this->errors["bedrooms"] = "empty";
        } else if ($this->isNotNumber($bedrooms)){
            $this->errors["bedrooms"] = "invalid";
        } else if ($this->isNegative($bedrooms)){
            $this->errors["bedrooms"] = "negative";
        }
        
        if ($this->isNumEmpty($bathrooms)){
            $this->errors["bathrooms"] = "empty";
        } else if ($this->isNotNumber($bathrooms)){
            $this->errors["bathrooms"] = "invalid";
        } else if ($this->isNegative($bathrooms)){
            $this->errors["bathrooms"] = "negative";
        }
 
        //Erros
        if ($this->errors){
            foreach ($this->errors as $key => $error) {
                echo $key." ".$error;
            }

            if ($this->deleteImg()){
                echo "deleted";
            }
            header("Location: ../adminCatalog.php?create=failed");
            die();
        }

        $userId = $_SESSION["userId"];
        $imgName = $this->uploadImg['name'];
        $this->saveModel($modelName, $imgName, $area, $bedrooms, $bathrooms, $userId);
    }
}