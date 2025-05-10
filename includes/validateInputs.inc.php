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
    $error = "";
    $input = trim($_POST['loginusername']);

    if (empty($input)) {
        $error = "O nome de utilizador é obrigatório.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $input)) {
        $error = "Só são permitidos letras, números e underscores.";
    }

    echo $error;
}

if (isset($_POST['username'])){
    $error = "";
    $input = htmlspecialchars(trim($_POST['username']));
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error = "O nome de utilizador é obrigatório.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $input)) {
        $error = "Só são permitidos letras, números e underscores.";
    } elseif (usernameExists($input)) {
        $error = "Este nome de utilizador já está em uso.";
    }else {
        $error = "";
    }
    echo  $error;
}

if (isset($_POST['pwd']) && isset($_POST['confirmPwd'])) {
    $error = "";
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
    }
    echo  $error;
}

if (isset($_POST['firstName'])) {
    $error = "";
    $input = trim($_POST['firstName']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error = "O nome é obrigatório.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $input)) {
        $error =  "Só são permitidos letras.";
    }
    echo  $error;
}

if (isset($_POST['lastName'])) {
    $error = "";
    $input = trim($_POST['lastName']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O apelido é obrigatório.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $input)) {
        $error =  "Só são permitidos letras.";
    }
    echo  $error;
}

if (isset($_POST['email'])) {
    $error = "";
    $input = trim($_POST['email']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O email é obrigatório.";
    } elseif (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $error =  "O email não é válido.";
    } elseif (emailExists($input)) {
        $error =  "Este email já está registado.";
    }
    echo  $error;
}

if (isset($_POST['birthDate'])) {
    $error = "";
    $input = trim($_POST['birthDate']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "A data de nascimento é obrigatória.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input)) {
        $error =  "A data de nascimento não é válida.";
    }
    echo  $error;
}

if (isset($_POST['nif'])) {
    $error = "";
    $input = trim($_POST['nif']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O NIF é obrigatório.";
    } elseif (!preg_match('/^\d{9}$/', $input)) {
        $error =  "O NIF não é válido.";
    }
    echo  $error;
}

if (isset($_POST['phone'])) {
    $error = "";
    $input = trim($_POST['phone']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O telefone é obrigatório.";
    } elseif (!preg_match('/^\d{9}$/', $input)) {
        $error =  "O telefone não é válido.";
    }
    echo  $error;
}

if (isset($_POST['clientAddress'])) {
    $error = "";
    $input = trim($_POST['clientAddress']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "A morada é obrigatória.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s,.-]+$/', $input)) {
        $error =  "A morada não é válida.";
    }
    echo  $error;
}

if (isset($_POST['district'])) {
    $error = "";
    $input = trim($_POST['district']);
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if ($required && empty($input)) {
        $error =  "O distrito é obrigatório.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $input)) {
        $error =  "O distrito não é válido.";
    }
    echo  $error;
}

if (isset($_POST['new-title'])) {
    $error = "";
    $input = trim($_POST['new-title']);

    if (empty($input)) {
        $error = "O titulo é obrigatório.";
    } elseif (!preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)) {
        $error =  "O título contém caracteres inválidos.";
    }
    echo  $error;
}

if (isset($_POST['new-url'])) {
    $error = "";
    $input = trim($_POST['new-url']);

    if (empty($input)) {
        $error = "O url é obrigatório.";
    } elseif (!preg_match('/^(https?:\/\/)?[a-z0-9\-\.]+\.[a-z]{2,}(\/[a-z0-9\-._~:\/?#\[\]@!$&\'()*+,;=]*)?$/i', $input)) {
        $error =  "Url Inválido";
    }
    echo  $error;
}

if (isset($_POST['new-content'])) {
    $error = "";
    $input = trim($_POST['new-content']);

    if (empty($input)) {
        $error = "O conteúdo é obrigatório.";
    } elseif (!preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)) {
        $error =  "O conteúdo contém caracteres inválidos.";
    }
    echo  $error;
}

if (isset($_POST['model-name'])) {
    $error = "";
    $input = trim($_POST['model-name']);

    if (empty($input)) {
        $error = "O nome do modelo é obrigatório.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s,.:()\'"@#+-]+$/', $input)) {
        $error =  "O nome do modelo contém caracteres inválidos.";
    }
    echo  $error;
}

if (isset($_FILES['model-img'])) {
    $error = "";
    $file = $_FILES['model-img'];
    $required = filter_var($_POST['required'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $type = $file['type'];
    $size = $file['size'];
    $imgError = $file['error'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2097152; // 2MB

    if ($required && $imgError === UPLOAD_ERR_NO_FILE){
        $error = "A imagem é obrigatória.";
    } else if (!in_array($type, $allowedTypes)) {
        $error = "Tipo de imagem inválido. Apenas JPEG, PNG ou GIF são permitidos.";
    } elseif ($size > $maxSize) {
        $error = "A imagem excede o tamanho máximo permitido de 2MB.";
    } elseif ($imgError !== 0) {
        $error = "Ocorreu um erro durante o upload da imagem.";
    }

    echo $error;
}

if (isset($_POST['model-area'])) {
    $error = "";
    $input = trim($_POST['model-area']);

    if ($input === '') {
        $error = "A área é obrigatória.";
    } elseif (!ctype_digit($input)) {
        $error = "A área deve ser um número inteiro positivo.";
    } elseif ((int)$input < 0) {
        $error = "A área deve ser maior que zero.";
    }

    echo $error;
}

if (isset($_POST['model-bedrooms'])) {
    $error = "";
    $input = trim($_POST['model-bedrooms']);

    if ($input === '') {
        $error = "O numero de quatos é obrigatório.";
    } elseif (!ctype_digit($input)) {
        $error = "O numero de quatos deve ser um número inteiro positivo.";
    } elseif ((int)$input < 0) {
        $error = "O numero de quatos deve ser maior ou igual a zero.";
    }

    echo $error;
}

if (isset($_POST['model-bathrooms'])) {
    $error = "";
    $input = trim($_POST['model-bathrooms']);

    if ($input === '') {
        $error = "O numero de wc é obrigatório.";
    } elseif (!ctype_digit($input)) {
        $error = "O numero de wc deve ser um número inteiro positivo.";
    } elseif ((int)$input < 0) {
        $error = "O numero de wc deve ser maior ou igual a zero.";
    }

    echo $error;
}

if (isset($_POST['appointment-date'])) {
    $error = "";
    $input = trim($_POST['appointment-date']);

    $dateParts = explode('-', $input);
    $inputDate = strtotime($input);
    $currentDate = strtotime(date('Y-m-d'));
    $dayOfWeek = date('w', $inputDate);

    if (empty($input)) {
        $error = "A data da marcação é obrigatória.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input)) {
        $error = "A data da marcação não é válida.";
    } elseif (!checkdate($dateParts[1], $dateParts[2], $dateParts[0])){
        $error = "A data da marcação não é válida.";
    } elseif ($inputDate < $currentDate){
        $error = "A data da marcação não pode ser no passado.";
    } elseif ($dayOfWeek == 0){
        $error = "Não são permitidas marcações ao domingo.";
    }
    echo  $error;
}

if (isset($_POST['appointment-time'])) {
    $error = "";
    $input = trim($_POST['appointment-time']);

    $appointmentTimes = ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30"];

    if (empty($input)) {
        $error = "A hora da marcação é obrigatória.";
    } elseif (!in_array($input, $appointmentTimes)) {
        $error = "A hora da marcação não é válida.";
    } 
    echo  $error;
}

if (isset($_POST['appointment-reason'])) {
    $error = "";
    $input = trim($_POST['appointment-reason']);

    if (empty($input)) {
        $error = "O motivo da marcação é obrigatório.";
    } elseif (!preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)) {
        $error =  "O motivo da marcação contém caracteres inválidos.";
    } 
    echo  $error;
}
