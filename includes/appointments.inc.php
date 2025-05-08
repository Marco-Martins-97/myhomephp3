<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointmentId = isset($_POST["appointment-id"]) ? htmlspecialchars(trim($_POST["appointment-id"])) : "";
    $action = htmlspecialchars(trim($_POST["appointment-action"]));
    $appointmentDate = htmlspecialchars(trim($_POST["appointment-date"]));
    $appointmentTime = htmlspecialchars(trim($_POST["appointment-time"]));
    $reason = htmlspecialchars(trim($_POST["appointment-reason"]));
    
    
    echo "Action: ".$action."<br>";
    /* echo "Date: ".$appointmentDate."<br>";
    echo "Time: ".$appointmentTime."<br>";
    echo "Reason: ".$reason."<br>"; */
    
    
    try { 
        require_once "Appointments.php";
        $appointment = new Appointments();
        
        // if ($action === "create"){
        $appointment -> create($appointmentDate, $appointmentTime, $reason);
        //     header("Location: ../adminNews.php?created=success");
        //     die();
        // } else if ($action === "edit" && !empty($newId)){
        //     $new -> edit($title, $url, $content, $newId);
        //     header("Location: ../adminNews.php?saved=success");
        //     die();
        // } else if ($action === "delete" && !empty($newId)){
        //     $new -> delete($newId);
        //     header("Location: ../adminNews.php?deleted=success");
        //     die();
        // } else{
        //     header("Location: ../adminNews.php?action=invalid");
        //     die();
        // }

    } catch (PDOException $e) {
        die ("Query Falhou: ".$e->getMessage());
    }
   

} else{
    header("Location: ../index.php");
    die();
}