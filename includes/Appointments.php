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

    /* 
        Esta Funçao verifica se as marcaçoes expiraram(passaram da data marcada),
        Esta funçao deve estar em constante funcionamento a fazer verificaçoes periodicas(ex:1h),
        Neste caso esta a ser aplicada toda a vez que um admin fizer uma pesquisa de marcaçoes.
     */
    private function ckeckExpiredAppointments(){
        $sql = "UPDATE appointments SET expired = 1, appointmentStatus = 'expired' WHERE expired = 0 AND appointmentStatus IN ('pending', 'rescheduled') AND CONCAT(appointmentDate, ' ', appointmentTime) < NOW();";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    }

    private function appointmentExists($appointmenId){
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
    private function setStatus($appointmentId, $status){
        $currentTime = date('Y-m-d H:i:s');
        $sql = "UPDATE appointments SET lastUpdate = :lastUpdate,  appointmentStatus = :appointmentStatus WHERE id = :appointmentId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':lastUpdate', $currentTime);
        $stmt->bindParam(':appointmentStatus', $status);
        $stmt->bindParam(':appointmentId', $appointmentId);
        $stmt->execute();
    }

    //Verficação de dados
    private function isInputEmpty($input){
        if (empty($input)){
            return true;
        } else{
            return false;
        }
    }
    private function isDateInvalid($input){
        $dateParts = explode('-', $input);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input)){
            return true;
        } elseif (!checkdate($dateParts[1], $dateParts[2], $dateParts[0])){
            return true;
        } else{
            return false;
        }
    }
    private function isDateExpired($input){
        $inputDate = strtotime($input);
        $currentDate = strtotime(date('Y-m-d'));
        if ($inputDate < $currentDate){
            return true;
        } else{
            return false;
        }
    }
    private function isDateSunday($input){
        $inputDate = strtotime($input);
        $dayOfWeek = date('w', $inputDate);
        if ($dayOfWeek == 0){
            return true;
        } else{
            return false;
        }
    }
    private function isTimeInvalid($input){
        $appointmentTimes = ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30"];
        if (!in_array($input, $appointmentTimes)){
            return true;
        } else{
            return false;
        }
    }
    private function isInputInvalid($input){
        if (!preg_match('/^[\p{L}\p{N}\s.,;:!?()\'"€$%&@#\-–—…]*$/u', $input)){
            return true;
        } else{
            return false;
        }
    }

    public function create($date, $time, $reason){
        if ($this->isInputEmpty($date) || $this->isInputEmpty($time) || $this->isInputEmpty($reason)){
            $this->errors["createAppointment"] = "empty";
        } else if ($this->isDateInvalid($date)){
            $this->errors["createAppointment"] = "dateInvalid";
        } else if ($this->isDateExpired($date)){
            $this->errors["createAppointment"] = "dateExpired";
        } else if ($this->isDateSunday($date)){
            $this->errors["createAppointment"] = "dateSunday";
        } else if ($this->isTimeInvalid($time)){
            $this->errors["createAppointment"] = "timeInvalid";
        } else if ($this->isInputInvalid($reason)){
            $this->errors["createAppointment"] = "reasonInvalid";
        }

        //Erros
        if ($this->errors){
            echo $this->errors["createAppointment"];
            header("Location: ../appointments.php?create=failed");
            die();
        }

        $username = $_SESSION["username"];
        $clientName = $_SESSION["clientFirstName"]." ".$_SESSION["clientLastName"];
        $status = "pending";
        $this->createAppointment($username, $clientName, $date, $time, $reason, $status);
    }

    public function reschedule($appointmenId, $date, $time, $reason){

        if ($this->isInputEmpty($date) || $this->isInputEmpty($time) || $this->isInputEmpty($reason)){
            $this->errors["rescheduleAppointment"] = "empty";
        } else if ($this->isDateInvalid($date)){
            $this->errors["rescheduleAppointment"] = "dateInvalid";
        } else if ($this->isDateExpired($date)){
            $this->errors["rescheduleAppointment"] = "dateExpired";
        } else if ($this->isDateSunday($date)){
            $this->errors["rescheduleAppointment"] = "dateSunday";
        } else if ($this->isTimeInvalid($time)){
            $this->errors["rescheduleAppointment"] = "timeInvalid";
        } else if ($this->isInputInvalid($reason)){
            $this->errors["rescheduleAppointment"] = "reasonInvalid";
        } else if (!$this->appointmentExists($appointmenId)){
            $this->errors["rescheduleAppointment"] = "idInvalid";
        }

        //Erros
        if ($this->errors){
            header("Location: ../appointments.php?reschedule=failed");
            die();
        }

        $username = $_SESSION["username"];
        $status = "rescheduled";
        $this->rescheduleAppointment($appointmenId, $username, $date, $time, $reason, $status);
    }

    public function cancel($appointmenId){
        $status = "cancelled";

        if (!$this->appointmentExists($appointmenId)){
            header("Location: ../appointments.php?delete=failed");
            die();
        }
        $this->setStatus($appointmenId, $status);
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
        $this->ckeckExpiredAppointments();  //verifica as marcaçoes expiradas toda s a vezes que um admin faz uma pesquisa
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
    public function changeStatus($appointmenId, $status){
        if (!$this->appointmentExists($appointmenId)){
            return false;
        }
        $this->setStatus($appointmenId, $status);
        return true;
    }
}