<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $modelId = isset($_POST["model-id"]) ? htmlspecialchars(trim($_POST["model-id"])) : "";
    $action = htmlspecialchars(trim($_POST["model-action"]));
    $modelName = htmlspecialchars(trim($_POST["model-name"]));

    if (isset($_FILES['model-img']) && $_FILES['model-img']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['model-img'];
    }

    $area = htmlspecialchars(trim($_POST["model-area"]));
    $bedrooms = htmlspecialchars(trim($_POST["model-bedrooms"]));
    $bathrooms = htmlspecialchars(trim($_POST["model-bathrooms"]));
    
    // echo "modelId: ".$modelId."<br>";
    // echo "action: ".$action."<br><br>";
    // echo "modelName: ".$modelName."<br>";
    // // echo "img: " . $img["name"] . "<br>";
    // echo "area: ".$area."<br>";
    // echo "bedrooms: ".$bedrooms."<br>";
    // echo "bathrooms: ".$bathrooms."<br>";
 

    try { 
        require_once "Catalog.php";
        $catalog = new Catalog();

        if ($action === "create"){
            $catalog -> createModel($modelName, $img, $area, $bedrooms, $bathrooms);
            header("Location: ../adminCatalog.php?created=success");
            die();
        } else if ($action === "edit" && !empty($modelId)){
            if (!isset($img)){ $img = ""; }
            $catalog -> editModel($modelName, $img, $area, $bedrooms, $bathrooms, $modelId);
            header("Location: ../adminCatalog.php?saved=success");
            die();
        } else if ($action === "delete" && !empty($modelId)){
            $catalog -> deleteModel($modelId);
            header("Location: ../adminCatalog.php?deleted=success");
            die();
        } else{
            header("Location: ../adminCatalog.php?action=invalid");
            die();
        }
        
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
        
} else{
    header("Location: ../index.php");
}