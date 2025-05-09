<?php
require_once "Appointments.php";
// session_start();

if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
}
$appointmentId = isset($_POST["appointmentId"]) ? htmlspecialchars(trim($_POST["appointmentId"])) : "";
$appointment = new Appointments();
$username = $_SESSION["username"];


if (!empty($appointmentId)){
    try {
        $appointmentData = $appointment -> loadAppointment($username, $appointmentId);

        $data = array(
            "date" => $appointmentData["appointmentDate"],  
            "time" => $appointmentData["appointmentTime"],
            "reason" => $appointmentData["reason"],
            "status" => $appointmentData["appointmentStatus"],
        );

        echo json_encode($data);
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
} else {
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
}