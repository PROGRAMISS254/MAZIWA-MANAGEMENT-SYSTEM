<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Maziwa System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .navbar h1 {
            color: #2575fc;
        }

        .nav-links a {
            margin: 0 15px;
            text-decoration: none;
            color: #2575fc;
            font-weight: bold;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            padding: 20px;
        }

        .card {
            background: white;
            width: 250px;
            height: 150px;
            margin: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.1);
            background: #2575fc;
            color: white;
        }

        .card h3 {
            color: #2575fc;
        }

        .card:hover h3 {
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <div class="nav-links">
            <a href="adminrecordmaziwa.php">Make Records</a>
            <a href="adminviewcomplains.php">View Complaints</a>
            <a href="adminpayfarmer.php">Make Payments</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="card" onclick="location.href='adminrecordmaziwa.php'">
            <h3>Make Records</h3>
        </div>

        <div class="card" onclick="location.href='adminviewcomplains.php'">
            <h3>View Complaints</h3>
        </div>

        <div class="card" onclick="location.href='adminpayfarmer.php'">
            <h3>Make Payments</h3>
        </div>
    </div>
</body>
</html>
