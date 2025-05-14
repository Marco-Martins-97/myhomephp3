<?php
require_once "Appointments.php";
// session_start();

if(!isset($_SESSION["username"])){ 
    header("Location: ../index.php"); 
    die();
}
$appointment = new Appointments();
$appointmentId = isset($_POST["appointmentId"]) ? htmlspecialchars(trim($_POST["appointmentId"])) : "";
$username = isset($_POST["username"]) ? htmlspecialchars(trim($_POST["username"])) : $_SESSION["username"];
$statusA = isset($_POST["statusA"]) ? htmlspecialchars(trim($_POST["statusA"])) : "all";


if (isset($_POST["username"]) && isset($_POST["statusA"])){
    try {
        $appointmentsData = $appointment -> loadClientAppointments($username, $statusA);

        $data = [];
        foreach ($appointmentsData as $appointmentData) {
            $data[] = [
                "appointmentId" => $appointmentData["id"],  
                "clientName" => $appointmentData["clientName"],  
                "date" => $appointmentData["appointmentDate"],  
                "time" => $appointmentData["appointmentTime"],
                "reason" => $appointmentData["reason"],
                "status" => $appointmentData["appointmentStatus"],
                "expired" => $appointmentData["expired"],
            ];
        }
        echo json_encode($data);
    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
} elseif (!empty($appointmentId)){
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