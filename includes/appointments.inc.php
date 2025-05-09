<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointmentId = isset($_POST["appointment-id"]) ? htmlspecialchars(trim($_POST["appointment-id"])) : "";
    $action = htmlspecialchars(trim($_POST["appointment-action"]));
    $appointmentDate = htmlspecialchars(trim($_POST["appointment-date"]));
    $appointmentTime = htmlspecialchars(trim($_POST["appointment-time"]));
    $reason = htmlspecialchars(trim($_POST["appointment-reason"]));
    
    
    echo "Action: ".$action."<br>";
    echo "ID: ".$appointmentId."<br>";
    /* echo "Date: ".$appointmentDate."<br>";
    echo "Time: ".$appointmentTime."<br>";
    echo "Reason: ".$reason."<br>"; */
    
    
    try { 

        require_once "Appointments.php";
        $appointment = new Appointments();
        
        if ($action === "create"){
            $appointment -> create($appointmentDate, $appointmentTime, $reason);
            header("Location: ../appointments.php?created=success");
            die();
        } else if ($action === "reschedule" && !empty($appointmentId)){
            $appointment -> reschedule($appointmentId, $appointmentDate, $appointmentTime, $reason);
            header("Location: ../appointments.php?rescheduled=success");
            die();
        } else if ($action === "cancel" && !empty($appointmentId)){
            $appointment -> cancel($appointmentId);
            header("Location: ../appointments.php?cancelled=success");
            die();
        } else{
            header("Location: ../appointments.php?action=invalid");
            die();
        }

    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
   

} else{
    header("Location: ../index.php");
    die();
}