<?php 
    session_start();
    if(!isset($_SESSION["userRole"]) || $_SESSION["userRole"] !== "admin"){ 
        header("Location: index.php"); 
        die();
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Titulo & Descrição -->
        <title>MyHome - Administração</title>
        <meta name="description" content="MyHome - Administração">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/administrator.css">
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
                    <li><a href="administrator.php#news-feed">News</a></li>
                    <li><a href="administrator.php#catalog">Catálogo</a></li>
                    <li><a href="administrator.php#clients">Clientes</a></li>
                    <li class="user">
                        <?php if(!isset($_SESSION["userId"])){ ?>
                            <ul class="guest">
                                <li><a href="login.php">Login</a></li>
                                <li><a href="signup.php">Registar</a></li>
                            </ul>
                        <?php } else{ ?>
                            <div class="dropdown-toggle">
                                <i class="fas fa-chevron-down"></i> 
                                <?php
                                if($_SESSION['activated']){
                                    echo $_SESSION['clientfirstName'] . " " . $_SESSION['clientLastName']; 
                                } else{
                                    echo $_SESSION['username'];
                                }
                                ?>
                            </div>
                            <ul class="dropdown">
                                <li><a href="profile.php">Perfil</a></li>
                                <li><a href="appointments.php">Marcaçoes</a></li>
                                <?php if($_SESSION["userRole"] === 'admin'){ ?>
                                    <li><a href="administrator.php">Administrador</a></li>
                                <?php } ?>
                                <li>
                                    <form action="includes/logout.inc.php" method="post">
                                        <button>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        <?php } ?>
                    </li>
                </ul>
            </nav>
        </header>
        <main>
            <section id="news-feed">NEWS</section>
            <section id="catalog">CATALOGO</section>
            <section id="clients">CLIENTES
                <div class="search-container">
                    <label for="serachUser">Insere o Username: </label>
                    <input type="text" name="searchUser" id="search-input">
                    <button id="search-clients"><i class="fa fa-search"></i></button>
                    <div class="results" id="results"></div>
                </div>
                <div class="edit" id="edit">
                    <input type="text" name="username">
                </div>
            </section>
        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="js/navMenu.js"></script>
        <script src="js/admClients.js"></script>
    </body>
</html>