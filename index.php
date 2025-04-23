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
        <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
        <!-- Script -->
        <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
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
                            <div class="dropdown-toggle"><i class="fas fa-chevron-down"></i>
                                <?php 
                                    if(isset($_SESSION['activated']) && $_SESSION['activated']){
                                        echo $_SESSION['clientfirstName'] . " " . $_SESSION['clientLastName']; 
                                    } else{
                                        echo $_SESSION['username'];
                                    }
                                ?>
                            </div>
                            <ul class="dropdown">
                                <li><a href="perfil.php">Perfil</a></li>
                                <li><a href="">Marcaçoes</a></li>
                                <?php if($_SESSION["accountType"] === 'admin'){ ?>
                                    <li><a href="">Administrador</a></li>
                                <?php } ?>
                                <li>
                                    <form action="php/includes/logout.inc.php" method="post">
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
            <section id="catalogo">CATALOGO</section>
        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="js/menu.js"></script>
    </body>
</html>