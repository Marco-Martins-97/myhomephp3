<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = isset($_POST["username"]) ? htmlspecialchars(trim($_POST["username"])) : $_SESSION["username"];
    
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $birthDate = htmlspecialchars(trim($_POST["birthDate"]));
    $nif = filter_input(INPUT_POST, "nif", FILTER_SANITIZE_NUMBER_INT);
    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_NUMBER_INT);
    $clientAddress = htmlspecialchars(trim($_POST["clientAddress"]));
    $district = htmlspecialchars(trim($_POST["district"]));
    
    echo "Username: ".$username."<br><br>";
    echo "firstName: ".$firstName."<br>";
    echo "lastName: ".$lastName."<br>";
    echo "email: ".$email."<br>";
    echo "birthDate: ".$birthDate."<br>";
    echo "nif: ".$nif."<br>";
    echo "phone: ".$phone."<br>";
    echo "clientAddress: ".$clientAddress."<br>";
    echo "district: ".$district."<br>";


    try { 
        require_once "Profile.php";
        $client = new Profile($username);
        $client -> saveClientData($firstName, $lastName, $email, $birthDate, $nif, $phone, $clientAddress, $district);
        
        if(strpos($_SERVER['HTTP_REFERER'], 'adminClients.php')){
            header("Location: " . $_SERVER['HTTP_REFERER']."?saved=success");
        } else{
            header("Location: ../profile.php?saved=success");
        }
        die();
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }

} else{
    header("Location: ../profile.php");
}