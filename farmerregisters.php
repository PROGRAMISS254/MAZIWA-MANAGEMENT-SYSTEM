<?php
session_start();
include 'db_connections.php'; // Connect to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert data into the farmers table
    $stmt = $conn->prepare("INSERT INTO farmers (name,password) VALUES (?,?)");
    $stmt->bind_param("ss", $name,$password);

    if ($stmt->execute()) {
        $registered = true;
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Registration - Maziwa Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 350px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .register-container:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"],  input[type="password"] {
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus {
            border-color: #ff7e5f;
            box-shadow: 0 0 8px rgba(255, 126, 95, 0.8);
        }
        button {
            background: #ff7e5f;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background: #eb4d4b;
            transform: scale(1.05);
        }
        .message {
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
    <script>
        function validateForm() {
            const name = document.forms["registerForm"]["name"].value.trim();
            const password = document.forms["registerForm"]["password"].value.trim();

            if (name === "" || password === "") {
                alert("Please fill out all fields.");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>Farmer Registration</h2>
        <?php if (isset($registered) && $registered): ?>
            <div class="message success">Registration successful!</div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form name="registerForm" method="POST" onsubmit="return validateForm()">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
    <a href="logout.php">LOGOUT</a>
</body>
</html>
