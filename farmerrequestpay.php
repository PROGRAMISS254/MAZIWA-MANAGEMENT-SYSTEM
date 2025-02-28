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

// Handle form submission (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

    if (empty($name) || $amount <= 0) {
        echo json_encode(['success' => false, 'error' => 'Valid name and amount required']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO payment_requests (name, amount, status) VALUES (?, ?, 'Pending')");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("sd", $name, $amount);

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
    <title>Farmer Payment Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #a1c4fd, #c2e9fb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            width: 300px;
        }
        h2 {
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('form').addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network error');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert("Payment request submitted successfully!");
                        window.location.reload();
                    } else {
                        alert("Error: " + (data.error || "Submission failed."));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the request.');
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Request Payment</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="number" name="amount" placeholder="Amount to request" step="0.01" required>
            <button type="submit">Submit Request</button>
        </form>
    </div><br><br>
    <a href="logout.php">LOGOUT</a>&nbsp;&nbsp;
    <a href="farmer_dashboards.php">HOME</a>
</body>
</html>
