<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Titulo & Descrição -->
        <title>MyHome</title>
        <meta name="description" content="MyHome - Especialistas em casas modulares personalizadas, oferecendo soluções inovadoras e sustentáveis para o seu lar ideal.">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/main6.css">
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
                    <li><a href="index.php#catalog">Catálogo</a></li>
                    <li><a href="contacts.php">Contactos</a></li>
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
                                    echo $_SESSION['clientFirstName'] . " " . $_SESSION['clientLastName']; 
                                } else{
                                    echo $_SESSION['username'];
                                }
                                ?>
                            </div>
                            <ul class="dropdown">
                                <li><a href="profile.php">Perfil</a></li>
                                <li><a href="appointments.php">Marcaçoes</a></li>
                                <?php if($_SESSION["userRole"] === 'admin'){ ?>
                                    <li><a href="adminAppointments.php">Marcaçoes Clientes</a></li>
                                    <li><a href="adminNews.php">Notícias</a></li>
                                    <li><a href="adminCatalog.php">Catálogo</a></li>
                                    <li><a href="adminClients.php">Clientes</a></li>
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
            <section class="news">
                <ul id="news-feed"></ul>
            </section>
            <div class="main-container">
                <div class="new-display" id="new-display">
                    
                </div>
                <section class="catalog" id="catalog">
                    <ul class="gallery"></ul>
                </section>

                <div class="footer">&copy; 2025 - MyHome</div>
            </div>
        </main>
        <script src="js/navMenu.js"></script>
        <script src="js/main4.js"></script>
    </body>
</html>