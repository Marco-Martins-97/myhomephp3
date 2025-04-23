<?php
require_once 'Dbh.php';

function usernameExists($username) {
    $dbh = new Dbh();
    $conn = $dbh->connect();
    $query = "SELECT * FROM users WHERE username = :username;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}



if (isset($_POST['loginusername'])){
    $input = trim($_POST['loginusername']);

    if (empty($input)) {
        $error = "O nome de utilizador é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[a-zA-Z0-9_]+$/', $input)) {
        $error = "Só são permitidos letras, números e underscores.";
    } else {
        $error = "";
    }

    echo $error;
}


if (isset($_POST['username'])){
    $input = htmlspecialchars(trim($_POST['username']));
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error = "O nome de utilizador é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[a-zA-Z0-9_]+$/', $input)) {
        $error = "Só são permitidos letras, números e underscores.";
    } elseif (!empty($input) && usernameExists($input)) {
        $error = "Este nome de utilizador já está em uso.";
    }else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['pwd']) && isset($_POST['confirmPwd'])) {
    $pwd = trim($_POST['pwd']);
    $confirmPwd = trim($_POST['confirmPwd']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN); 

    if ($required && empty($pwd)) {
        $error = "A password é obrigatória.";
    } else if (!empty($pwd) && strlen($pwd) < 3) { //usei 3 caracteres por preguiça, mas o ideal seria 8+
        $error = "A password deve ter pelo menos 3 caracteres."; 
    } else if ($required && empty($confirmPwd)) {
        $error = "A confirmação da password é obrigatória."; 
    } else if (!empty($confirmPwd) && strlen($confirmPwd) < 3) {
        $error = "A confirmação da password deve ter pelo menos 3 caracteres.";
    } else if (!empty($pwd) && $confirmPwd !== $pwd) {
        $error = "As passwords não coincidem."; 
    } else {
        $error = "";
    }
    echo  $error;
}