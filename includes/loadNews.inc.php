<?php
// session_start();
require_once "News.php";

/* if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
} else{ */
    $newId = isset($_POST["newId"]) ? htmlspecialchars(trim($_POST["newId"])) : "";
    $newsQt = isset($_POST["newsQt"]) ? htmlspecialchars(trim($_POST["newsQt"])) : 5;
    // $newsQt = 5;

    $new = new News();
    if (!empty($newId)){
        try {
            $newData = $new -> loadNew($newId);
            
            $data = array(
                "title" => $newData["title"],
                "link" => $newData["link"],
                "content" => $newData["content"],
            );

            echo json_encode($data);
        } catch (PDOException $e) {
            die ("Query Falhou: ".$e->getMessage());
            // echo json_encode(["error" => "Query Failed: " . $e->getMessage()]);
        }
    } else {
        try {
            $newsData = $new -> loadNews($newsQt);
            
            $data = [];
            foreach ($newsData as $newData) {
                $data[] = [
                    "newId" => $newData["id"],  
                    "title" => $newData["title"],
                    "link" => $newData["link"],
                "content" => $newData["content"],
                "author" => $newData["author"],
                ];
            }
            echo json_encode($data);
        } catch (PDOException $e) {
            die ("Query Falhou: ".$e->getMessage());
            // echo json_encode(["error" => "Query Failed: " . $e->getMessage()]);
        }
    }

/* } */