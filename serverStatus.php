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





# Confirmed – The appointment has been confirmed by both parties.
# Pending – Appointment is awaiting confirmation or further details.
# Cancelled – The appointment has been canceled by one or both parties.
# Rescheduled – The appointment was moved to a different time.
# Completed – The appointment has taken place and is finished.
# No-show – The person missed the appointment without prior notice.
# Expired – The appointment time has passed without the meeting taking place.
# Declined – The appointment request was rejected. */