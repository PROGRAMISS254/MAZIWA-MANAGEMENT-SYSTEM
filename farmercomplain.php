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
    // Ensure input fields match the form correctly
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $complaint_text = isset($_POST['complaint_text']) ? trim($_POST['complaint_text']) : '';

    if (empty($name) || empty($complaint_text)) {
        echo json_encode(['success' => false, 'error' => 'Both fields are required.']);
        exit;
    }

    // Insert into database with a timestamp
    $stmt = $conn->prepare("INSERT INTO complaints (name, complaint_text, date_submitted, status) VALUES (?, ?, NOW(), 'Pending')");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $name, $complaint_text);

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
    <title>Farmer Complaint Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
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
            width: 350px;
        }
        h2 {
            text-align: center;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #c82333;
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Complaint submitted successfully!");
                        window.location.reload();
                    } else {
                        alert("Error: " + (data.error || "Submission failed."));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the complaint.');
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Submit a Complaint</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <textarea name="complaint_text" rows="4" placeholder="Describe your complaint..." required></textarea>
            <button type="submit">Submit Complaint</button>
        </form>
    </div><br><br>
        <a href="logout.php">LOGOUT</a>&nbsp;&nbsp;

    <a href="farmer_dashboards.php">HOME</a>
</body>
</html>
