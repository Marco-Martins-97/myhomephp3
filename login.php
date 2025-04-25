<?php 
    session_start();
    if(isset($_SESSION["userId"])){ 
        header("Location: index.php"); 
        die();
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Titulo & Descrição -->
        <title>MyHome - Login</title>
        <meta name="description" content="MyHome - Login">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/login.css">
        <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
        <!-- Script -->
        <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Header -->
        <header>
            <nav>
                <a href="index.php" class="mobile-only title">My Home</a>
                <div class="menu-toggle mobile-only"><i class="fas fa-bars"></i></div>
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>
                    
                    <li class="mobile-only"><a href="index.php#news-feed">Notícias</a></li>
                    <li><a href="index.php#catalogo">Catálogo</a></li>
                    <li><a href="index.php#orcamento">Orçamento</a></li>
                    <li><a href="contactos.php">Contactos</a></li>
                    <li class="user">
                        <ul class="guest">
                            <li><a href="login.php">Login</a></li>
                            <li><a href="signup.php">Registar</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <main>
        <div class="form-container <?php if (isset($_SESSION["loginError"])) { echo 'invalid'; } ?>">
                <h1>Login</h1>
                <form action="includes/login.inc.php" method="post">
                    <div class="field-container">
                        <div class="field">
                            <label for="username">Username:</label>
                            <input type="text" name="username">
                        </div>
                    </div>
                    <div class="field-container">
                        <div class="field">
                            <label for="pwd">Password:</label>
                            <input type="password" name="pwd">
                        </div>
                    </div>
                    <?php 
                        if (isset($_SESSION["loginError"])){
                            $error = $_SESSION['loginError']['login'];
                            echo "<div class='error'>".$error."</div>";
                            unset($_SESSION['loginError']);
                        } else{
                            echo "<div class='error'></div>";
                        }
                    ?>
                    
                    <button>Entrar</button>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script>    //mostra uma alerta caso o registo seja bem sucedido ou o login falhe
            const params = new URLSearchParams(window.location.search);
            const signupStatus = params.get('signup');
            const loginStatus = params.get('login');

            if (signupStatus === 'success') {
                alert("Registo bem-sucedido!");
            }
            if (loginStatus === 'failed') {
                alert("O Login Falhou! Tente novamente.");
            }
            
        </script>
        <script src="js/navMenu.js"></script>
        <script src="js/loginValidate.js"></script>
    </body>
</html>