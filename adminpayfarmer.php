<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $requested_amount = $_POST['amount'];
    $comment = $_POST['comment'];
    $status = $_POST['status'];
    
    // Get farmer's total milk earnings
    $query = "SELECT SUM(litres) AS total_litres FROM milk_deliveries WHERE name='$name'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $total_litres = $row['total_litres'];
    $milk_earnings = $total_litres * 40; // KSh 40 per litre
    
    if ($requested_amount > $milk_earnings) {
        $status = "Rejected";
        $comment = "Requested amount exceeds earnings (KSh $milk_earnings).";
    } else {
        $status = "Approved";
    }
    
    // Update payment request
    $update_query = "UPDATE payment_requests SET status='$status' WHERE id='$id'";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Payment request updated successfully!'); window.location.href='adminpayfarmer.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch pending requests
$sql = "SELECT * FROM payment_requests WHERE status='Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Payment Requests</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Pending Payment Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Farmer Name</th>
            <th>Requested Amount</th>
            <th>Status</th>
            <th>Comment</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <form method="POST" action="">
                <td><?php echo $row['id']; ?><input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['name']; ?><input type="hidden" name="name" value="<?php echo $row['name']; ?>"></td>
                <td><?php echo $row['amount']; ?><input type="hidden" name="amount" value="<?php echo $row['amount']; ?>"></td>
                <td><?php echo $row['status']; ?></td>
                <td><input type="text" name="comment" placeholder="Admin comment"></td>
                <td>
                    <button type="submit" name="status" value="Approve">Approve</button>
                    <button type="submit" name="status" value="Reject">Reject</button>
                </td>
            </form>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
