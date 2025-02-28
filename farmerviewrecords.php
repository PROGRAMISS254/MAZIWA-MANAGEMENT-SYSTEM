<?php
session_start();
include 'db_connections.php'; // Database connection

// Fetch Farmer Name from Session
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';

// Fetch Delivery Records
$deliveries = [];
if ($name) {
    $stmt = $conn->prepare("SELECT date, litres FROM milk_deliveries WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $deliveries[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer View Records</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delivery Records for <?= htmlspecialchars($name) ?></h2>

        <?php if (!empty($deliveries)): ?>
            <table>
                <tr><th>Date</th><th>Litres</th></tr>
                <?php foreach ($deliveries as $delivery): ?>
                    <tr>
                        <td><?= htmlspecialchars($delivery['date']) ?></td>
                        <td><?= htmlspecialchars($delivery['litres']) ?></td>
                       
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No delivery records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
