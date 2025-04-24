<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST["username"]));
    $pwd = htmlspecialchars(trim($_POST["pwd"]));
    /* 
    echo "Username: ".$username."<br>";
    echo "Pwf: ".$pwd."<br>";
     */
    try { 
        require_once "Login.php";
        $login = new Login($username, $pwd);
        $login -> login();
        
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }

} else{
    header("Location: ../login.php");
    die();
}