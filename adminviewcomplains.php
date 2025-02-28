<?php
$host = 'localhost';
$db = 'milk_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch complaints
$result = $conn->query("SELECT * FROM complaints ORDER BY date_submitted DESC");

// Handle admin response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $admin_response = $_POST['admin_response'];

    $stmt = $conn->prepare("UPDATE complaints SET admin_response = ?, status = 'Resolved' WHERE id = ?");
    $stmt->bind_param("si", $admin_response, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Complaint Management</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #f4f4f4; }
        .resolved { color: green; }
        .pending { color: orange; }
        button { padding: 5px 10px; cursor: pointer; }
    </style>
    <script>
        function addResponse(id) {
            let response = prompt("Enter your response:");
            if (response) {
                fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id + '&admin_response=' + encodeURIComponent(response)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Response added successfully.");
                        window.location.reload();
                    } else {
                        alert("Error: " + data.error);
                    }
                });
            }
        }
    </script>
</head>
<body>
    <a href="logout.php">LOGOUT</a>&nbsp;
    <a href="admin_dashboard.php">HOME</a><br><br>
    <h2>Complaints List</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Complaint</th>
            <th>Date</th>
            <th>Status</th>
            <th>Response</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['complaint_text']) ?></td>
            <td><?= $row['date_submitted'] ?></td>
            <td class="<?= $row['status'] == 'Resolved' ? 'resolved' : 'pending' ?>">
                <?= $row['status'] ?>
            </td>
            <td><?= $row['admin_response'] ? htmlspecialchars($row['admin_response']) : 'No response yet' ?></td>
            <td><button onclick="addResponse(<?= $row['id'] ?>)">Respond</button></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
