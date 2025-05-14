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


    private function appointmenExists($appointmenId){
        $sql = "SELECT id FROM appointments WHERE id = :appointmenId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':appointmenId', $appointmenId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    private function createAppointment($username, $clientName, $date, $time, $reason, $status){
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
    private function rescheduleAppointment($appointmentId, $username, $date, $time, $reason, $status){
        $currentTime = date('Y-m-d H:i:s');
        $sql = "UPDATE appointments SET appointmentDate = :appointmentDate, appointmentTime = :appointmentTime, reason = :reason, lastUpdate = :lastUpdate,  appointmentStatus = :appointmentStatus WHERE username = :username AND id = :appointmentId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':appointmentDate', $date);
        $stmt->bindParam(':appointmentTime', $time);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':lastUpdate', $currentTime);
        $stmt->bindParam(':appointmentStatus', $status);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':appointmentId', $appointmentId);
        $stmt->execute();
    }
    private function setStatus($appointmentId, $username, $status){
        $currentTime = date('Y-m-d H:i:s');
        $sql = "UPDATE appointments SET lastUpdate = :lastUpdate,  appointmentStatus = :appointmentStatus WHERE username = :username AND id = :appointmentId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':lastUpdate', $currentTime);
        $stmt->bindParam(':appointmentStatus', $status);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':appointmentId', $appointmentId);
        $stmt->execute();
    }


    public function create($date, $time, $reason){
        $username = $_SESSION["username"];
        $clientName = $_SESSION["clientFirstName"]." ".$_SESSION["clientLastName"];
        $status = "pending";




        $this->createAppointment($username, $clientName, $date, $time, $reason, $status);
    }

    public function reschedule($appointmenId, $date, $time, $reason){
        $username = $_SESSION["username"];
        $status = "rescheduled";




        $this->rescheduleAppointment($appointmenId, $username, $date, $time, $reason, $status);
    }

    public function cancel($appointmenId){
        $username = $_SESSION["username"];
        $status = "cancelled";

        if (!$this->appointmenExists($appointmenId)){
            header("Location: ../adminNews.php?delete=failed");
            die();
        }
        $this->setStatus($appointmenId, $username, $status);
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
        $sql="SELECT appointmentDate, DATE_FORMAT(appointmentTime, '%H:%i') AS appointmentTime, reason, appointmentStatus FROM appointments WHERE username = :username AND id = :appointmentId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':appointmentId', $appointmentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function loadClientAppointments($username, $statusA){
        $sql="SELECT id, clientName, appointmentDate, DATE_FORMAT(appointmentTime, '%H:%i') AS appointmentTime, reason, appointmentStatus, expired FROM appointments";

        if ($username !== ""){  //username
            $sql .= " WHERE username = :username";

            if ($statusA === "all"){
                $sql .= ";";
            } elseif ($statusA === "pending"){
                $sql .= " AND appointmentStatus = 'pending' OR username = :username AND appointmentStatus = 'rescheduled';";
            } elseif ($statusA === "expired"){
                $sql .= " AND expired = 1;";
            } else {
                $sql .= " AND appointmentStatus = :statusA;";
            }

        } else {                //empty username
            if ($statusA === "all"){
                $sql .= ";";
            } elseif ($statusA === "pending"){
                $sql .= " WHERE appointmentStatus = 'pending' OR appointmentStatus = 'rescheduled';";
            } elseif ($statusA === "expired"){
                $sql .= " WHERE expired = 1;";
            } else {
                $sql .= " WHERE appointmentStatus = :statusA;";
            }
        }

        $stmt = $this->conn->prepare($sql);

        if ($username !== "") { $stmt->bindParam(':username', $username); }
        if ($statusA !== "all" && $statusA !== "pending" && $statusA !== "expired"){ $stmt->bindParam(':statusA', $statusA); }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;                                                                
    }
    public function changeStatus($appointmenId, $username, $status){
        if (!$this->appointmenExists($appointmenId)){
            return false;
        }
        $this->setStatus($appointmenId, $username, $status);
        return true;
    }
}