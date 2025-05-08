<?php
// session_start();
require_once "Catalog.php";
/* 
if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
} */

$modelId = isset($_POST["modelId"]) ? htmlspecialchars(trim($_POST["modelId"])) : "";

$catalog = new Catalog();

if (!empty($modelId)){
    try {
        $modelData = $catalog->loadModel($modelId);

        $data = array(
            "modelName" => $modelData["modelName"],
            // "imgName" => $modelData["imgName"],
            "area" => $modelData["area"],
            "bedrooms" => $modelData["bedrooms"],
            "bathrooms" => $modelData["bathrooms"],
        );
        echo json_encode($data);
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
} else {
    try {
        $modelsData = $catalog->loadModels();

        $data = [];
        foreach ($modelsData as $modelData) {
            $data[] = [
                "modelId" => $modelData["id"],  
                "modelName" => $modelData["modelName"],
                "imgName" => $modelData["imgName"],
                "area" => $modelData["area"],
                "bedrooms" => $modelData["bedrooms"],
                "bathrooms" => $modelData["bathrooms"],
                "userId" => $modelData["userId"],
            ];
        }
        echo json_encode($data);
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
}