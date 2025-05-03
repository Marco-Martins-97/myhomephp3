<?php
session_start();

if(!isset($_SESSION["userRole"]) || $_SESSION["userRole"] !== "admin"){ 
    header("Location: ../index.php"); 
    die();
}

    $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    
    require_once 'Dbh.php';
    $dbh = new Dbh();
    $conn = $dbh->connect();

    $sql = "SELECT username FROM users";
    if (!empty($username)) {
        $sql .= " WHERE username LIKE :username";
        $stmt = $conn->prepare($sql);
        $like = "%" . $username . "%";
        $stmt->bindValue(':username', $like, PDO::PARAM_STR);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);

