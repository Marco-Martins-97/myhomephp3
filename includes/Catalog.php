<?php
require_once 'Dbh.php';
session_start();

class Catalog{
    private $conn;
    private $errors = [];
    private $uploadDir = "../img/uploads/";

    private $uploadedImg;

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

    private function saveEditedModel($modelName, $area, $bedrooms, $bathrooms, $modelId){
        if($this->uploadedImg !== ""){
            $sql = "UPDATE houseModels SET modelName = :modelName, imgName = :imgName, area = :area, bedrooms = :bedrooms, bathrooms = :bathrooms WHERE id = :modelId;";
        } else {
            $sql = "UPDATE houseModels SET modelName = :modelName, area = :area, bedrooms = :bedrooms, bathrooms = :bathrooms WHERE id = :modelId;";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':modelName', $modelName);
        if($this->uploadedImg !== ""){
            $imgName = $this->uploadedImg['name'];
            $stmt->bindParam(':imgName', $imgName);
        }
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':bedrooms', $bedrooms);
        $stmt->bindParam(':bathrooms', $bathrooms);
        $stmt->bindParam(':modelId', $modelId);
        $stmt->execute();
    }

    private function removeModel($modelId){
        $sql = "DELETE FROM houseModels WHERE id = :modelId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':modelId', $modelId);
        $stmt->execute();
    }
    
    
    private function getSavedImgName($modelId){
        $sql="SELECT imgName FROM houseModels WHERE id = :modelId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':modelId', $modelId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['imgName'];
    }

    private function modelExists($modelId){
        $sql = "SELECT id FROM houseModels WHERE id = :modelId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':modelId', $modelId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }


    private function uploadImg(){
        $error =  $this->uploadedImg['error'];
        if ($error !== 0){
            return false;
        }

        if (!file_exists($this->uploadDir)){
            mkdir($this->uploadDir, 0777, true);
        }
        //renomeia a img, substitui espaços e caracters especiais por "_"
        $this->uploadedImg['name'] = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", $this->uploadedImg['name']);

        $tmpDir = $this->uploadedImg['tmp_name'];
        $destDir = $this->uploadDir.$this->uploadedImg['name'];
        
        if(move_uploaded_file($tmpDir, $destDir)){
            return true;
        }else {
            return false;
        }
    }

    private function deleteImg($imgName){
        $imgDir = $this->uploadDir.$imgName;

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
        $type = $this->uploadedImg['type'];
        if (!in_array($type, $allowedTypes)){
            return true;
        } else{
            return false;
        }
    }

    private function isSizeInvalid(){
        $maxSize = 2097152; // 2MB
        $size = $this->uploadedImg['size'];
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
        $this->uploadedImg = $img;
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

            if ($this->deleteImg($this->uploadedImg['name'])){
                echo "deleted";
            }
            header("Location: ../adminCatalog.php?create=failed");
            die();
        }

        $userId = $_SESSION["userId"];
        $imgName = $this->uploadedImg['name'];
        $this->saveModel($modelName, $imgName, $area, $bedrooms, $bathrooms, $userId);
    }
    public function editModel($modelName, $img, $area, $bedrooms, $bathrooms, $modelId){
        $this->uploadedImg = $img;
        
        //Validaçao de dados
        if ($this->isInputEmpty($modelName)){
            $this->errors["name"] = "empty";
        } else if ($this->isInputInvalid($modelName)){
            $this->errors["name"] = "invalid";
        }

        if($this->uploadedImg !== ""){
            $currentImg = $this->getSavedImgName($modelId);
            if (!$this->uploadImg()){
                $this->errors["img"] = "upload";
            } else if ($this->isFormatInvalid()){
                $this->errors["img"] = "format";
            } else if ($this->isSizeInvalid()){
                $this->errors["img"] = "size";
            }
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
            if($this->uploadedImg !== ""){
                if ($this->deleteImg($this->uploadedImg['name'])){
                    echo "deleted";
                }
            }
            header("Location: ../adminCatalog.php?create=failed");
            die();
        }

        if($this->uploadedImg !== ""){//apaga a imagem antiga
            if ($this->deleteImg($currentImg)){
                echo "deleted saved img";
            }
        }
        $this->saveEditedModel($modelName, $area, $bedrooms, $bathrooms, $modelId);
    }

    public function deleteModel($modelId){
        if (!$this->modelExists($modelId)){
            header("Location: ../adminCatalog.php?delete=failed");
            die();
        }
        $currentImg = $this->getSavedImgName($modelId);
        $this->deleteImg($currentImg);
        $this->removeModel($modelId);
    }

    public function loadModel($modelId){
        $sql="SELECT modelName, area, bedrooms, bathrooms FROM houseModels WHERE id = :modelId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':modelId', $modelId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function loadModels(){
        $sql="SELECT * FROM houseModels;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}