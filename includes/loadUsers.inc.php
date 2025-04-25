<?php
session_start();

if(!isset($_SESSION["userRole"]) || $_SESSION["userRole"] !== "admin"){ 
    header("Location: ../index.php"); 
    die();
}

    $username = isset($_GET['username']) ? htmlspecialchars(trim($_GET['username'])) : '';
    
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

    if ($result && count($result) > 0) {
        echo "<ul id='users-list'>";
        foreach ($result as $row) {
            echo "<li>" . htmlspecialchars($row['username']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Nenhum utilizador encontrado.";
    }

