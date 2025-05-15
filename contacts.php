<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Titulo & Descrição -->
        <title>MyHome - Contactos</title>
        <meta name="description" content="MyHome - Contactos">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/contacts.css">
        <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
        <!-- Script -->
        <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- OpenStreetMap -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    </head>
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
            <div id="map" class="map"></div>
            <div class="contact-container">
                <h2>Informações de Contacto</h2>
                <p>Morada: <a href="">Rua de Caires, 4700-206 Braga</a></p>
                <p>Telemovel: <a href="">+351 987 654 321</a></p>
                <p>Email: <a href="">contacto@myhome.pt</a></p>
                <p>Site: <a href="index.html">www.myhome.pt</a></p>
                <div class="social">
                    <a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank"><i class="fa fa-instagram"></i></a>
                    <a href="https://www.youtube.com/" target="_blank"><i class="fa fa-youtube"></i></a>
                </div>
            </div>
        </main>
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="js/navMenu.js"></script>
        <script src="js/contacts.js"></script>
     </body>
</html