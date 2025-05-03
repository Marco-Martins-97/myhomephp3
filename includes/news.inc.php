<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newId = isset($_POST["new-id"]) ? htmlspecialchars(trim($_POST["new-id"])) : "";
    $action = htmlspecialchars(trim($_POST["new-action"]));
    $title = htmlspecialchars(trim($_POST["new-title"]));
    $url = htmlspecialchars(trim($_POST["new-url"]));
    $content = htmlspecialchars(trim($_POST["new-content"]));
    
    
    // echo "Action: ".$action."<br>";
    // echo "Titulo: ".$title."<br>";
    // echo "URL: ".$url."<br>";
    // echo "Descição: ".$content."<br>";
    
    
    try { 
        require_once "News.php";
        $new = new News();
        if ($action === "create"){
            $new -> create($title, $url, $content);
            header("Location: ../adminNews.php?created=success");
            die();
        } else if ($action === "edit" && !empty($newId)){
            $new -> edit($title, $url, $content, $newId);
            header("Location: ../adminNews.php?saved=success");
            die();
        } else if ($action === "delete" && !empty($newId)){
            $new -> delete($newId);
            header("Location: ../adminNews.php?deleted=success");
            die();
        } else{
            header("Location: ../adminNews.php?action=invalid");
            die();
        }

    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
   

} else{
    header("Location: ../index.php");
    die();
}