<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST["username"]));
    $pwd = htmlspecialchars(trim($_POST["pwd"]));
    $confirmPwd = htmlspecialchars(trim($_POST["confirmPwd"]));
    /* 
    echo "Username: ".$username."<br>";
    echo "Pwf: ".$pwd."<br>";
    echo "ConfirmPwd: ".$confirmPwd."<br>";
     */
    try { 
        require_once "Signup.php";
        $signup = new Signup($username, $pwd, $confirmPwd);
        $signup -> signup();
        

    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }

} else{
    header("Location: ../signup.php");
    die();
}