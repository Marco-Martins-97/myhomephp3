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
    // echo "img: " . $imgName . "<br>";
    // echo "area: ".$area."<br>";
    // echo "bedrooms: ".$bedrooms."<br>";
    // echo "bathrooms: ".$bathrooms."<br>";
 

    try { 
        require_once "Catalog.php";
        $catalog = new Catalog();

        if ($action === "create"){
            $catalog -> createModel($modelName, $img, $area, $bedrooms, $bathrooms);
            // header("Location: ../adminNews.php?created=success");
            // die();
        } else if ($action === "edit" && !empty($newId)){
            // $new -> edit($title, $url, $content, $newId);
            // header("Location: ../adminNews.php?saved=success");
            // die();
        } else if ($action === "delete" && !empty($newId)){
            // $new -> delete($newId);
            // header("Location: ../adminNews.php?deleted=success");
            // die();
        } else{
            header("Location: ../adminNews.php?action=invalid");
            die();
        }
        
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
        
} else{
    header("Location: ../index.php");
}