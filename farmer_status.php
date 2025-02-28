<?php
session_start();
include 'db_connections.php'; // Ensure database connection is included

// Ensure farmer is logged in
if (!isset($_SESSION['name'])) {
    die("Unauthorized access!");
}

$farmer_name = $_SESSION['name'];

// Fetch payment requests
$query_payments = "SELECT * FROM payment_requests WHERE name = ?";
$stmt_payments = $conn->prepare($query_payments);
$stmt_payments->bind_param("s", $farmer_name);
$stmt_payments->execute();
$result_payments = $stmt_payments->get_result();

// Fetch complaint feedback
$query_complaints = "SELECT * FROM complaints WHERE name = ?";
$stmt_complaints = $conn->prepare($query_complaints);
$stmt_complaints->bind_param("s", $farmer_name);
$stmt_complaints->execute();
$result_complaints = $stmt_complaints->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Farmer Status</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Payment Request Status</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Requested Amount (KSh)</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result_payments->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['amount']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>Complaint Feedback</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Complaint</th>
            <th>Admin Response</th>
        </tr>
        <?php while ($row = $result_complaints->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['complaint']); ?></td>
                <td><?php echo htmlspecialchars($row['response']); ?></td>
            </tr>
        <?php } ?>
    </table>
    <a href="logout.php">LOGOUT</a>
        <a href="farmer_dashboards.php">LOGOUT</a>

</body>
</html>
<?php
$stmt_payments->close();
$stmt_complaints->close();
$conn->close();
?>
