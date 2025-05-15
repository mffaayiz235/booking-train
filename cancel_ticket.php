<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["bookingId"])) {
        echo json_encode(["error" => "Booking ID is required"]);
        exit;
    }

    $bookingId = $_POST["bookingId"];

    // Fetch train number before deletion
    $query = "SELECT train_number FROM reservations WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->bind_result($trainNumber);
    $stmt->fetch();
    $stmt->close();

    if ($trainNumber) {
        // Delete reservation
        $query = "DELETE FROM reservations WHERE booking_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $stmt->close();

        // Update available seats
        $query = "UPDATE trains SET available_seats = available_seats + 1 WHERE train_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $trainNumber);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Booking not found"]);
    }
}
$conn->close();
?>
