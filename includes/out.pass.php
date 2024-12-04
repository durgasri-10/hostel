<?php

require 'config.inc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_SESSION['roll'];
    $leave_type = $_POST["leaveType"]; 
    $date_from = $_POST["fromDate"];
    $date_to = $_POST["toDate"];
    $reason = $_POST["reason"];
    $approval = "Pending";

    // Prepare the INSERT statement for the outpass data, including the file
    $stmt = $conn->prepare("INSERT INTO outpass (userName, leave_type, date_from, date_to, reason, Approve, file_name, file_type, file_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Check if a file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileContent = file_get_contents($fileTmpName);

        // Bind the parameters and execute the query
        $stmt->bind_param("sssssssss", $uname, $leave_type, $date_from, $date_to, $reason, $approval, $fileName, $fileType, $fileContent);
        if ($stmt->execute()) {
            echo "<script>alert('Outpass request submitted successfully with the file!'); window.location.href = '../home.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // If no file is uploaded, execute the query without file data
        $stmt->bind_param("ssssssss", $uname, $leave_type, $date_from, $date_to, $reason, $approval, null, null);
        if ($stmt->execute()) {
            echo "<script>alert('Outpass request submitted successfully without a file.'); window.location.href = '../home.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the prepared statement
    $stmt->close();
}
?>
