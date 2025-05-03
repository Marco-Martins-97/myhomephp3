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
        <title>MyHome - Administração - Clientes</title>
        <meta name="description" content="MyHome - Administração - Clientes">
        <meta name="author" content="Marco Martins">
        <!-- Browser -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Style -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/adminClients2.css">
        <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
        <!-- Script -->
        <script src="https://kit.fontawesome.com/d132031da6.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Header -->
        <header>
            <nav>
                <a href="index.php" class="mobile-only title">My Home - Adm - Clientes</a>
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
            <div class="search-container">
                <div class="search">
                    <input type="text" name="searchUser" id="search-input" placeholder="@username">
                    <button id="search-clients"><i class="fa fa-search"></i></button>
                </div>
                <div class="results" id="results"></div>
            </div>
            
            <div class="edit" id="edit">
                <div class="form-container" id="user-profile">
                    <h1>Perfil: <span id="user"></span></h1>
                    <form action="includes/profile.inc.php" method="post" id="profile-form">
                        <input type="hidden" name="username">
                        <div class="field-container required">
                            <div class="field">
                                <label for="firstName">Nome:</label>
                                <input type="text" name="firstName">
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container">
                            <div class="field">
                                <label for="lastName">Apelido:</label>
                                <input type="text" name="lastName">
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container required">
                            <div class="field">
                                <label for="email">Email:</label>
                                <input type="email" name="email">
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container required">
                            <div class="field">
                                <label for="birthDate">Data de Nascimento:</label>
                                <input type="date" name="birthDate">
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container">
                            <div class="field">
                                <label for="nif">NIF:</label>
                                <input type="text" name="nif"><!--  pattern="[0-9]{9}" -->
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container">
                            <div class="field">
                                <label for="phone">Telefone:</label>
                                <input type="tel" name="phone"><!--  pattern="[0-9]{9}" -->
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container">
                            <div class="field">
                                <label for="clientAddress">Morada:</label>
                                <input type="text" name="clientAddress">
                            </div>
                            <div class="error"></div>
                        </div>

                        <div class="field-container">
                            <div class="field">
                                <label for="district">Distrito:</label>
                                <select name="district">
                                    <option value="">--Selecione um distrito--</option>
                                    <option value="Aveiro">Aveiro</option>
                                    <option value="Beja">Beja</option>
                                    <option value="Braga">Braga</option>
                                    <option value="Bragança">Bragança</option>
                                    <option value="Castelo Branco">Castelo Branco</option>
                                    <option value="Coimbra">Coimbra</option>
                                    <option value="Évora">Évora</option>
                                    <option value="Faro">Faro</option>
                                    <option value="Guarda">Guarda</option>
                                    <option value="Leiria">Leiria</option>
                                    <option value="Lisboa">Lisboa</option>
                                    <option value="Portalegre">Portalegre</option>
                                    <option value="Porto">Porto</option>
                                    <option value="Santarém">Santarém</option>
                                    <option value="Setúbal">Setúbal</option>
                                    <option value="Viana do Castelo">Viana do Castelo</option>
                                    <option value="Vila Real">Vila Real</option>
                                    <option value="Viseu">Viseu</option>
                                    <option value="Açores">Açores</option>
                                    <option value="Madeira">Madeira</option>
                                </select>
                            </div>
                            <div class="error"></div>
                        </div>
                        <p>Campos de preenchimento obrigatório.</p>
                        <div class="btn-container">
                            <button type="submit"><i class='fa fa-save'></i> Salvar</button>
                            <button type="reset"><i class='fa fa-undo'></i> Repor</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <!-- Footer -->
        <footer>
            <div>&copy; 2025 - MyHome</div>
        </footer>
        <script src="js/navMenu.js"></script>
        <script src="js/admClients2.js"></script>
    </body>
</html>