<?php 
session_start();

require_once "includes/Dbh.php";

$dbh = new Dbh();
$conn = $dbh->connect();
if ($conn) {
    echo "Database: <span style='color:green'>Connected</span><br>";
}


echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// unset($_SESSION["accountType"]);