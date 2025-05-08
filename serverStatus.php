<?php 
session_start();

require_once "includes/Dbh.php";

$dbh = new Dbh();
$conn = $dbh->connect();
if ($conn) {
    echo "Database: <span style='color:green'>Connected</span><br>";
}



echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// $author = $_SESSION["clientFirstName"].$_SESSION["clientLastName"];
// echo $author;
// unset($_SESSION["accountType"]);

// $_SESSION["activated"] = 0;
/* function getEmail($conn, $input){
    $sql = "SELECT email, userId FROM clients WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $input);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

$data = getEmail($conn, "admin@email.com");

foreach($data as $key => $d){
    echo $key.": ".$d."<br>";
}

if ($data["userId"] !== $_SESSION["userId"]){
    echo "not";
} else{
    echo "same";
} */


/* require_once "includes/News.php";
$new = new News();
$newsData = $new -> loadNews(5);
$data = [];
        foreach ($newsData as $newData) {
            $data[] = [
                "newId" => $newData["id"],  
                "title" => $newData["title"],
                "link" => $newData["link"],
                "content" => $newData["content"],
                "userId" => $newData["userId"],
            ];
        }

echo json_encode($data); */
// var_dump($data);




/* function loadNew($conn, $newId){
    $sql="SELECT title, link, content FROM news WHERE id = :newId;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':newId', $newId);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
$data = loadNew($conn, 2);
echo json_encode($data); */





/* Here’s a list of possible appointment statuses that could be used in various settings:
1. Scheduled – Appointment is confirmed and set for a specific time.
2. Confirmed – The appointment has been confirmed by both parties.
3. Pending – Appointment is awaiting confirmation or further details.
4. Cancelled – The appointment has been canceled by one or both parties.
5. Rescheduled – The appointment was moved to a different time.
6. Completed – The appointment has taken place and is finished.
7. No-show – The person missed the appointment without prior notice.
8. In Progress – The appointment is currently taking place.
9. Expired – The appointment time has passed without the meeting taking place.
10. Awaiting Payment – Appointment is confirmed but requires payment before it can proceed.
11. Overdue – The scheduled time has passed, and the appointment has not been attended or completed.
12. Rescheduling – The process of selecting a new time for the appointment.
13. Confirmed (by client) – The client has confirmed the appointment.
14. Confirmed (by service provider) – The service provider has confirmed the appointment.
15. Cancelled (by client) – The client canceled the appointment.
16. Cancelled (by service provider) – The service provider canceled the appointment.
17. Waiting for Client – Appointment is set, but the client has not arrived yet.
18. Waiting for Service Provider – Appointment is set, but the service provider has not arrived yet.
19. Unconfirmed – Appointment has not been confirmed by either party.
20. Under Review – The appointment is being checked or evaluated before confirming.
21. Declined – The appointment request was rejected. */