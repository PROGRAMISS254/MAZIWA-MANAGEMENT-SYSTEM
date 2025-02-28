<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'milk_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Payment Approval/Rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'], $_POST['status'])) {
    $payment_id = intval($_POST['payment_id']);
    $status = in_array($_POST['status'], ['Approved', 'Rejected']) ? $_POST['status'] : 'Pending';

    $stmt = $conn->prepare("UPDATE payment_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $payment_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}

// Handle Complaint Response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'], $_POST['response'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $response = htmlspecialchars(trim($_POST['response']));

    $stmt = $conn->prepare("UPDATE complaints SET admin_response = ?, status = 'Resolved' WHERE id = ?");
    $stmt->bind_param("si", $response, $complaint_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}

// Fetch Payment Requests
$payments = $conn->query("SELECT * FROM payment_requests");

// Fetch Complaints
$complaints = $conn->query("SELECT * FROM complaints");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; text-align: center; }
        .container { width: 80%; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #007bff; color: white; }
        button { padding: 8px 12px; margin: 5px; border: none; cursor: pointer; color: white; border-radius: 5px; }
        .approve { background: green; }
        .reject { background: red; }
        .respond { background: orange; }
    </style>
    <script>
        function updatePaymentStatus(id, status) {
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `payment_id=${id}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Payment status updated successfully!");
                    window.location.reload();
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => alert('An error occurred.'));
        }

        function respondToComplaint(id) {
            const responseText = prompt("Enter your response:");
            if (responseText) {
                fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `complaint_id=${id}&response=${encodeURIComponent(responseText)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Response submitted successfully!");
                        window.location.reload();
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => alert('An error occurred.'));
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <h3>Payment Requests</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Farmer Name</th>
                <th>Amount (KES)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $payments->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['farmer_name']) ?></td>
                <td><?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <button class="approve" onclick="updatePaymentStatus(<?= $row['id'] ?>, 'Approved')">Approve</button>
                        <button class="reject" onclick="updatePaymentStatus(<?= $row['id'] ?>, 'Rejected')">Reject</button>
                    <?php else: ?>
                        <?= $row['status'] ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Complaints</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Farmer Name</th>
                <th>Complaint</th>
                <th>Status</th>
                <th>Admin Response</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $complaints->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['farmer_name']) ?></td>
                <td><?= htmlspecialchars($row['complaint_text']) ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['admin_response'] ?: 'No response yet' ?></td>
                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <button class="respond" onclick="respondToComplaint(<?= $row['id'] ?>)">Respond</button>
                    <?php else: ?>
                        Resolved
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
