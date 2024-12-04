<?php
require 'includes/config.inc.php'; // Ensure $conn is defined here

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['userName']) || isset($_GET['date_from']) && isset($_GET['date_to'])) {
    $uName = urldecode($_GET['userName']);
    $date_from = urldecode($_GET['date_from']);
    $date_to = urldecode($_GET['date_to']);

    // Check database connection
    if (!$conn) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Fetch file details
    $stmt = $conn->prepare("SELECT file_name, file_type, file_data FROM outpass WHERE userName = ? AND date_from = ? AND date_to = ?");
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $uName,$date_from,$date_to);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind the result variables correctly
        $stmt->bind_result($file_name, $file_type, $file_data);
        $stmt->fetch();

        // Debug: Output retrieved data details
        echo "File Name: $file_name<br>";
        echo "File Type: $file_type<br>";
        echo "File Size: " . strlen($file_data) . " bytes<br>";

        // Output headers for inline viewing
        header("Content-Type: $file_type");
        header("Content-Disposition: inline; filename=\"$file_name\"");
        echo $file_data;
    } else {
        // File not found
        echo "File not found.";
    }

    $stmt->close();
} else {
    // Invalid request
    header("HTTP/1.0 400 Bad Request");
    echo "Invalid request.";
}

$conn->close();
?>
