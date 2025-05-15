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
        <link rel="stylesheet" href="css/appointments5.css">
        <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
        <!-- Script -->
        <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <style>
            .status{
                text-transform: capitalize;
                font-weight: bold;
            }
            .status.pending, .status.rescheduled{
                color: yellow;
            }
            .status.cancelled{
                color: red;
            }
            .status.approved{
                color: green;
            }
        </style>
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
                                <li><a href="appointments.php">Marcaçoes</a></li>
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
            <div class="appointments-container">
                <?php if(!$_SESSION['activated']){ 
                    echo "<a href='profile.php'> Ativar Conta! </a>"; 
                } else{ ?>
                    <button class="create-appointment">Criar Marcação</button>
                    <div class="modal" id="warning-modal">
                        <div class="modal-content">
                            <span id="close-warning">&times;</span>
                            <h2 id="warning-title"></h2>
                        </div>
                    </div>
                    <div class="modal" id="appointment-modal">
                        <div class="modal-content">
                            <span id="close-modal">&times;</span>
                            <h2 id="modal-title"></h2>
                            <form action="includes/appointments.inc.php" method="post" id="appointment-form">
                                <input type="hidden" name="appointment-action">
                                <input type="hidden" name="appointment-id" value="">
                                <div class="field-container">
                                    <div class="field">
                                        <label for="appointment-date">Data/Hora:</label>
                                        <div class="dateTime">
                                            <input type="date" name="appointment-date">
                                            <select name="appointment-time" id="times">
                                                <option value="" id="default" disabled selected>--Selecione uma Hora--</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="error"></div>
                                </div>
                                <div class="field-container">
                                    <div class="field">
                                        <label for="appointment-reason">Motivo de Contacto:</label>
                                        <textarea name="appointment-reason" rows="4"></textarea>
                                    </div>
                                    <div class="error"></div>
                                </div>
                                <button type="submit" id="submit-appointment">Marcar</button>
                            </form>
                        </div>
                    </div>

                    <ul class="schedule-appointments"></ul>
                <?php } ?>
            </div>

        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="js/navMenu.js"></script>
        <script src="js/appointments9.js"></script>
    </body>
</html>
