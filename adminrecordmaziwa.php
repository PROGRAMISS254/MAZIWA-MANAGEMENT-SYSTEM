<?php
include('db_connections.php');

// Fetch farmer names
$query = "SELECT name FROM farmers";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $litres = $_POST['litres'];

    $stmt = $conn->prepare("INSERT INTO milk_deliveries (name,date, litres) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $date, $litres);
    
    if ($stmt->execute()) {
        echo "<div class='success'>Delivery recorded successfully.</div>";
    } else {
        echo "<div class='error'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Maziwa Deliveries</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
    <script>
        function validateForm() {
            const name = document.forms["deliveryForm"]["name"].value;
            const date = document.forms["deliveryForm"]["date"].value;
            const litres = document.forms["deliveryForm"]["litres"].value;
            if (name == "" || date == "" || litres == "") {
                alert("All fields must be filled out");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h1>Record Maziwa Deliveries</h1>
    <form name="deliveryForm" method="POST" action="" onsubmit="return validateForm()">
        <label for="name">Farmer Name:</label>
        <select name="name" required>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="date">Date:</label>
        <input type="date" name="date" required><br>

        <label for="litres">Litres:</label>
        <input type="number" name="litres" step="0.01" required><br>

        <button type="submit">Record Delivery</button>
    </form>
</body>
</html>
