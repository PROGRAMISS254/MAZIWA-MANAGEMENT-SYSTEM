

<?php
session_start();
include 'db_connections.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];  // Admin or Farmer
    $name = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if ($role === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE name = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM farmers WHERE name = ?");
    }

    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user[$role === 'admin' ? 'admin_id' : 'farmer_id'];
            $_SESSION['name'] = $role === 'admin' ? $user['name'] : $user['name'];
            header("Location: " . ($role === 'admin' ? "admin_dashboard.php" : "farmer_dashboards.php"));
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = ucfirst($role) . " not found!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Maziwa Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 350px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-container:hover {
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
        select, input[type="text"], input[type="password"] {
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.8);
        }
        button {
            background: #3498db;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        .message {
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 10px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
    <script>
        function validateForm() {
            const role = document.forms["loginForm"]["role"].value;
            const username = document.forms["loginForm"]["username"].value.trim();
            const password = document.forms["loginForm"]["password"].value.trim();

            if (username === "" || password === "") {
                alert(`Please enter ${role === "admin" ? "username" : "name"} and password.`);
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Login - Maziwa System</h2>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form name="loginForm" method="POST" onsubmit="return validateForm()">
            <select name="role" required>
                <option value="farmer">Farmer</option>
                <option value="admin">Admin</option>
            </select>
            <input type="text" name="username" placeholder="Username or Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
