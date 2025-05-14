<?php
require_once "Appointments.php";

if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = htmlspecialchars(trim($_POST["appointmentId"])) ;
    $statusA = htmlspecialchars(trim($_POST["statusA"]));

    $appointment = new Appointments();
    if($appointment -> changeStatus($appointmentId, $statusA)){
        echo $appointmentId.$statusA;
    }
}
