<?php
require 'includes/config.inc.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['userName']; // Validate input
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    // Delete the outpass record
    try {
        // Delete record from `outpass_upload`
        $sql = "DELETE FROM outpass WHERE userName = ? AND date_from = ? AND date_to = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing SQL1: " . $conn->error);
        }

        $stmt->bind_param("sss", $userName, $date_from, $date_to);
        $stmt->execute();

        // Check if rows were affected in the first table
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Outpass Deleted successfully.'); window.location.href='message_user.php';</script>";
        } else {
            echo "<script>alert('Fail to Deleted Out Pass.'); window.location.href='message_user.php';</script>";
        }
    } catch (Exception $e) {
        $errorMessage = "An error occurred: " . $e->getMessage();
    }
    $stmt1->close();
    $stmt2->close();
}
?>
