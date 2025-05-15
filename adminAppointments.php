<?php 
    session_start();
    if(!isset($_SESSION["userId"])){ 
        header("Location: index.php"); 
        die();
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Titulo & Descrição -->
        <title>MyHome - Marcações</title>
        <meta name="description" content="MyHome - Marcações">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/adminAppointments.css">
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
                                <li><a href="appointments.php">Marcações</a></li>
                                <?php if($_SESSION["userRole"] === 'admin'){ ?>
                                    <li><a href="adminAppointments.php">Marcaçoes Clientes</a></li>
                                    <li><a href="adminNews.php">News</a></li>
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
            <?php if(!$_SESSION['activated']){ echo "<a href='profile.php'> Ativar Conta! </a>"; } else{ ?>
                
                <div class="search-bar">
                    <input type="text" id="username-search" placeholder="@username">
                    <select id="appointment-status">
                        <option value="all">Todos</option>
                        <option value="pending">Pendentes</option>
                        <option value="confirmed">Confirmados</option>
                        <option value="cancelled">Cancelados</option>
                        <option value="declined">Recusados</option>
                        <option value="no-show">Sem Presença</option>
                        <option value="expired">Expirados</option>
                    </select>
                    <button id="search-appointments">Pesquisar</button>
                </div>   
                <ul class="schedule-appointments"></ul>
            <?php } ?>

        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="js/navMenu.js"></script>
        <script src="js/appointmentsAdm3.js"></script>
    </body>
</html>