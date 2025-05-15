<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"]) || empty($_POST["trainNumber"])) {
        echo json_encode(["error" => "Name and Train Number are required"]);
        exit;
    }

    $name = $_POST["name"];
    $trainNumber = $_POST["trainNumber"];
    $seatPreference = $_POST["seatPreference"];

    // Check seat availability
    $query = "SELECT available_seats FROM trains WHERE train_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $trainNumber);
    $stmt->execute();
    $stmt->bind_result($availableSeats);
    $stmt->fetch();
    $stmt->close();

    if ($availableSeats > 0) {
        // Reserve a seat
        $query = "INSERT INTO reservations (name, train_number, seat_preference) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $trainNumber, $seatPreference);
        $stmt->execute();
        $bookingId = $stmt->insert_id;
        $stmt->close();

        // Update seat availability
        $query = "UPDATE trains SET available_seats = available_seats - 1 WHERE train_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $trainNumber);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true, "bookingId" => $bookingId]);
    } else {
        echo json_encode(["error" => "No seats available"]);
    }
}
$conn->close();
?>
