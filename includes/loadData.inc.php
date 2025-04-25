<?php
session_start();
require_once "Profile.php";

if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
} else{
    $username = !empty($_POST["username"]) ? htmlspecialchars(trim($_POST["username"])) : $_SESSION["username"];
    //$username =  $_SESSION["username"];
    
    $client = new Profile($username);
    if ($client->userExists() && $client->userExists()["activated"] === 1){   //verifica se o user existe e esta ativo
        try { 
            $clientData = $client->getClientData();

            $data = array(
                // "username" => $username,
                "firstName" => $clientData["firstName"],
                "lastName" => $clientData["lastName"],
                "email" => $clientData["email"],
                "birthDate" => $clientData["birthDate"],
                "nif" => $clientData["nif"],
                "phone" => $clientData["phone"],
                "clientAddress" => $clientData["clientAddress"],
                "district" => $clientData["district"],  
            );

            echo json_encode($data);
        } catch (PDOException $e) {
            die ("Query Falhou: ".$e->getMessage());
        }
    } else{
        echo json_encode(["error" => "User nao está ativo"]);
    }
}
