<?php
require_once 'Dbh.php';

function usernameExists($username) {
    $dbh = new Dbh();
    $conn = $dbh->connect();
    $sql = "SELECT * FROM users WHERE username = :username;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}

function emailExists($email) {
    $dbh = new Dbh();
    $conn = $dbh->connect();
    $sql = "SELECT * FROM clients WHERE email = :email;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
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

if (isset($_POST['firstName'])) {
    $input = trim($_POST['firstName']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error = "O nome é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $input)) {
        $error =  "Só são permitidos letras.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['lastName'])) {
    $input = trim($_POST['lastName']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O apelido é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $input)) {
        $error =  "Só são permitidos letras.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['email'])) {
    $input = trim($_POST['email']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O email é obrigatório.";
    } elseif (!empty($input) && !filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $error =  "O email não é válido.";
    } elseif (!empty($input) && emailExists($input)) {
        $error =  "Este email já está registado.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['birthDate'])) {
    $input = trim($_POST['birthDate']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "A data de nascimento é obrigatória.";
    } elseif (!empty($input) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $input)) {
        $error =  "A data de nascimento não é válida.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['nif'])) {
    $input = trim($_POST['nif']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O NIF é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^\d{9}$/', $input)) {
        $error =  "O NIF não é válido.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['phone'])) {
    $input = trim($_POST['phone']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O telefone é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^\d{9}$/', $input)) {
        $error =  "O telefone não é válido.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['clientAddress'])) {
    $input = trim($_POST['clientAddress']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "A morada é obrigatória.";
    } elseif (!empty($input) && !preg_match('/^[a-zA-ZÀ-ÿ0-9\s,.-]+$/', $input)) {
        $error =  "A morada não é válida.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['district'])) {
    $input = trim($_POST['district']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O distrito é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $input)) {
        $error =  "O distrito não é válido.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['new-title'])) {
    $input = trim($_POST['new-title']);

    if (empty($input)) {
        $error = "O titulo é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)) {
        $error =  "O título contém caracteres inválidos.";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['new-url'])) {
    $input = trim($_POST['new-url']);

    if (empty($input)) {
        $error = "O url é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^(https?:\/\/)?[a-z0-9\-\.]+\.[a-z]{2,}(\/[a-z0-9\-._~:\/?#\[\]@!$&\'()*+,;=]*)?$/i', $input)) {
        $error =  "Url Inválido";
    } else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['new-content'])) {
    $input = trim($_POST['new-content']);

    if (empty($input)) {
        $error = "O conteúdo é obrigatório.";
    } elseif (!empty($input) && !preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)) {
        $error =  "O conteúdo contém caracteres inválidos.";
    } else {
        $error = "";
    }
    echo  $error;
}