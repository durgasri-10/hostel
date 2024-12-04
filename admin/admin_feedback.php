<?php
require '../includes/config.inc.php'; // Ensure $conn is defined

$query = "SELECT userName, food_rating, maintenance_rating, comments, submitted_at FROM feedback ORDER BY submitted_at DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr>
            <th>Name</th>
            <th>Food Rating</th>
            <th>Maintenance Rating</th>
            <th>Comments</th>
            <th>Submitted At</th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['userName']}</td>
            <td>{$row['food_rating']} stars</td>
            <td>{$row['maintenance_rating']} stars</td>
            <td>{$row['comments']}</td>
            <td>{$row['submitted_at']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No feedback available.";
}

$conn->close();
?>
