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


    private function uploadImg(){
        if (!file_exists($this->uploadDir)){
            mkdir($this->uploadDir, 0777, true);
        }
        //renomeia a img e substitui caracter especiais por "_"
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

    public function createModel($modelName, $img, $area, $bedrooms, $bathrooms){
        $this->uploadImg = $img;
        if (!$this->uploadImg()){
            $this->errors["upload"] = "fail to upload";
        }
        
        //Erros
        if ($this->errors){
            foreach ($this->errors as $key => $error) {
                echo $key." ".$error;
            }

            if ($this->deleteImg()){
                echo "deleted";
            }
            // header("Location: ../adminCatalog.php?create=failed");
            // die();
        }
        
        echo $this->uploadImg['name'];
    }
}