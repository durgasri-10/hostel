<?php
require '../includes/config.inc.php'; // Include database connection

// Handle form submission for approve, reject, or delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['outpass_id'])) {
        $outpass_id = $_POST['outpass_id'];

        if ($_POST['action'] == 'approve') {
            // Approve the outpass request
            $stmt = $conn->prepare("UPDATE outpass SET Approve = 'Approved' WHERE userName = ?");
            $stmt->bind_param("i", $outpass_id);
            if ($stmt->execute()) {
                echo "<script>alert('Outpass approved successfully!');</script>";
            } else {
                echo "<script>alert('Error approving outpass.');</script>";
            }
        } elseif ($_POST['action'] == 'reject') {
            // Reject the outpass request
            $stmt = $conn->prepare("UPDATE outpass SET Approve = 'Rejected' WHERE userName = ?");
            $stmt->bind_param("s", $outpass_id);
            if ($stmt->execute()) {
                echo "<script>alert('Outpass rejected successfully!');</script>";
            } else {
                echo "<script>alert('Error rejecting outpass.');</script>";
            }
        } elseif ($_POST['action'] == 'delete') {
            // Delete the outpass request
            $stmt = $conn->prepare("DELETE FROM outpass WHERE userName = ?");
            $stmt->bind_param("i", $outpass_id);
            if ($stmt->execute()) {
                echo "<script>alert('Outpass deleted successfully!');</script>";
            } else {
                echo "<script>alert('Error deleting outpass.');</script>";
            }
        }
    }
}

// Fetch all outpass requests
$sql = "SELECT * FROM outpass";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Outpass Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .approve {
            background-color: green;
            color: white;
        }
        .reject {
            background-color: red;
            color: white;
        }
        .delete {
            background-color: orange;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Admin Outpass Management</h1>
    <table>
        <thead>
            <tr>
                <th>Student-ID</th>
                <th>Leave-Type</th>
                <th>Date-From</th>
                <th>Date-To</th>
                <th>Reason</th>
                <th>Approval Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['userName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['leave_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_from']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_to']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Approve']) . "</td>";
                    echo "<td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='outpass_id' value='" . htmlspecialchars($row['userName']) . "'>
                                <button type='submit' name='action' value='approve' class='approve'>Approve</button>
                            </form>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='outpass_id' value='" . htmlspecialchars($row['userName']) . "'>
                                <button type='submit' name='action' value='reject' class='reject'>Reject</button>
                            </form>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='outpass_id' value='" . htmlspecialchars($row['userName']) . "'>
                                <button type='submit' name='action' value='delete' class='delete'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No outpass requests found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
