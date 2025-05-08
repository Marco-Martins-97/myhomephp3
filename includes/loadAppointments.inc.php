<?php
require_once "Appointments.php";
// session_start();

if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
}

$username = $_SESSION["username"];
$appointment = new Appointments();
try {
    $appointmentsData = $appointment -> loadAppointments($username);
    
    $data = [];
    foreach ($appointmentsData as $appointmentData) {
        $data[] = [
            "appointmentId" => $appointmentData["id"],  
            "date" => $appointmentData["appointmentDate"],  
            "time" => $appointmentData["appointmentTime"],
            "reason" => $appointmentData["reason"],
            "status" => $appointmentData["appointmentStatus"],
        ];
    }
    echo json_encode($data);
} catch (PDOException $e) {
    die ("Query Falhou: ".$e->getMessage());
}