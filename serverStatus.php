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

// $_SESSION["activated"] = 0;
/* function getEmail($conn, $input){
    $sql = "SELECT email, userId FROM clients WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $input);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

$data = getEmail($conn, "admin@email.com");

foreach($data as $key => $d){
    echo $key.": ".$d."<br>";
}

if ($data["userId"] !== $_SESSION["userId"]){
    echo "not";
} else{
    echo "same";
} */