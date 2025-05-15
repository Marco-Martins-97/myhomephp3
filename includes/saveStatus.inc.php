<?php
require_once "Appointments.php";

if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
}
try {
    $appointmentId = htmlspecialchars(trim($_POST["appointmentId"])) ;
    $statusA = htmlspecialchars(trim($_POST["statusA"]));

    $appointment = new Appointments();
    if($appointment -> changeStatus($appointmentId, $statusA)){
        echo json_encode("Status changed.");
    } else {
        echo json_encode("Failed to change status!");
    }
} catch (PDOException $e) {
    die ("Query Falhou: ".$e->getMessage());
}
