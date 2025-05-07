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
        <title>MyHome - Administração - Catálogo</title>
        <meta name="description" content="MyHome - Administração - Catálogo">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/adminCatalog2.css">
        <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
        <!-- Script -->
        <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Header -->
        <header>
            <nav>
                <a href="index.php" class="mobile-only title">My Home - Adm - Catalogo</a>
                <div class="menu-toggle mobile-only"><i class="fas fa-bars"></i></div>
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>    
                    <li><a href="adminNews.php">Notícias</a></li>
                    <li><a href="adminCatalog.php">Catálogo</a></li>
                    <li><a href="adminClients.php">Clientes</a></li>
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
            <button class="create-model">Criar Modelo</button>

            <div class="modal" id="catalog-modal">
                <div class="modal-content">
                    <span id="close-modal">&times;</span>
                    <h2 id="modal-title"></h2>
                    <form action="includes/catalog.inc.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="model-action">
                        <input type="hidden" name="model-id" value="">
                        <div class="field-container">
                            <div class="field">
                                <label for="model-name">Modelo:</label>
                                <input type="text" name="model-name">
                            </div>
                            <div class="error"></div>
                        </div>
                        <div class="field-container">
                            <div class="field">
                                <label for="model-img">Modelo Img:</label>
                                <input type="file" name="model-img">
                            </div>
                            <div class="error"></div>
                        </div>
                        <div class="field-container">
                            <div class="field">
                                <label for="model-area">Área (m2):</label>
                                <input type="number" name="model-area" min="0" step="1">
                            </div>
                            <div class="error"></div>
                        </div>
                        <div class="field-container">
                            <div class="field">
                                <label for="model-bedrooms">Quartos:</label>
                                <input type="number" name="model-bedrooms" min="0" step="1">
                            </div>
                            <div class="error"></div>
                        </div>
                        <div class="field-container">
                            <div class="field">
                                <label for="model-bathrooms">Wc:</label>
                                <input type="number" name="model-bathrooms" min="0" step="1">
                            </div>
                            <div class="error"></div>
                        </div>
                        <button type="submit" id="submit-model"></button>
                    </form>
                </div>
            </div>
            <div class="models-container">
                <ul class="gallery"></ul>
            </div> 

        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="js/navMenu.js"></script>
        <script src="js/admCatalog4.js"></script>
    </body>
</html>