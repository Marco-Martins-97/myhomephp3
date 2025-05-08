<?php
require_once 'Dbh.php';
session_start();

class Appointments{
    private $conn;
    private $errors = [];

    public function __construct(){
        $dbh = new Dbh();
        $this->conn = $dbh->connect();
    }

    private function createAppointment($username, $clientName, $date, $time, $reason, $status){
        echo "Username: ".$username."<br>";
        echo "Name: ".$clientName."<br>";
        echo "Date: ".$date."<br>";
        echo "Time: ".$time."<br>";
        echo "Reason: ".$reason."<br>";
        echo "Status: ".$status."<br>";

        $sql = "INSERT INTO appointments (username, clientName, appointmentDate, appointmentTime, reason, appointmentStatus) VALUES (:username, :clientName, :appointmentDate, :appointmentTime, :reason, :appointmentStatus)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':clientName', $clientName);
        $stmt->bindParam(':appointmentDate', $date);
        $stmt->bindParam(':appointmentTime', $time);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':appointmentStatus', $status);
        $stmt->execute();
    }


    public function create($date, $time, $reason){
        $username = $_SESSION["username"];
        $clientName = $_SESSION["clientFirstName"]." ".$_SESSION["clientLastName"];
        $status = "pending";




        $this->createAppointment($username, $clientName, $date, $time, $reason, $status);
    }

    public function loadAppointments($username){
        $sql="SELECT id, appointmentDate, DATE_FORMAT(appointmentTime, '%H:%i') AS appointmentTime, reason, appointmentStatus FROM appointments WHERE username = :username;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function loadAppointment($username, $appointmentId){
        $sql="SELECT appointmentDate, DATE_FORMAT(appointmentTime, '%H:%i') AS appointmentTime, reason FROM appointments WHERE username = :username AND id = :appointmentId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':appointmentId', $appointmentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}