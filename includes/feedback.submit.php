<?php
require 'config.inc.php'; // Ensure $conn is defined

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userName = $_POST['userName'];
    $food_rating = $_POST['food_rating'];
    $maintenance_rating = $_POST['maintenance_rating'];
    $comments = $_POST['comments'];

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO feedback (userName, food_rating, maintenance_rating, comments) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("siis", $userName, $food_rating, $maintenance_rating, $comments);
        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your feedback!'); window.location.href = '../home.php'</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
