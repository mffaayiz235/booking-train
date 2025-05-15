<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["trainNumber"])) {
        echo json_encode(["error" => "Train number is required"]);
        exit;
    }

    $trainNumber = $_POST["trainNumber"];

    $query = "SELECT available_seats FROM trains WHERE train_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $trainNumber);
    $stmt->execute();
    $stmt->bind_result($availableSeats);
    $stmt->fetch();
    
    if ($availableSeats !== null) {
        echo json_encode(["availableSeats" => $availableSeats]);
    } else {
        echo json_encode(["error" => "Train not found"]);
    }

    $stmt->close();
}
$conn->close();
?>
