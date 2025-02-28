<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Dashboard - Maziwa Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        header {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .slider {
            display: flex;
            overflow: hidden;
            position: relative;
            height: 300px;
        }
        .slides {
            display: flex;
            width: 400%;
            animation: slide 110s infinite;
        }
        .slides img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        @keyframes slide {
            0% { transform: translateX(0); }
            25% { transform: translateX(-100%); }
            50% { transform: translateX(-200%); }
            75% { transform: translateX(-300%); }
            100% { transform: translateX(0); }
        }
        .content {
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
        }
        video {
            width: 100%;
            margin-top: 20px;
            border: 3px solid white;
        }
    </style>
</head>
<body>
    <audio autoplay loop>
        <source src="background-music.mp3" type="audio">
        Your browser does not support the audio element.
    </audio>

    <header>
        <h1>Welcome to Maziwa Farmer Dashboard</h1>
        <nav>
            <a href="farmerviewrecords.php">DAILY RECORDS</a>&nbsp;&nbsp;
                  <a href="farmerrequestpay.php">REQUEST PAYMENT</a>&nbsp;&nbsp;
         <a href="farmer_status.php">farmer status</a>&nbsp;&nbsp;

         <a href="farmercomplain.php">FILE COMPLAIN</a>&nbsp;&nbsp;
            <a href="#about">About Us</a>
            <a href="#contact">Contact Us</a>
              <a href="#services">Our Services</a>&nbsp;&nbsp;

                   <a href="logout.php">LOGOUT</a>

        </nav>
    </header>

    <div class="slider">
        <div class="slides">
            <img src="slide1.jpg" alt="Milk Image 1">
            <img src="slide2.jpg" alt="Milk Image 2">
            <img src="slide3.jpg" alt="Milk Image 3">
            <img src="slide1a.jpg" alt="Milk Image 4">
        </div>
    </div>

    <div class="content">
        <section id="about">
            <h2>About Us</h2>
            <p>Welcome to Maziwa Management System. We help farmers manage their milk production, payments, and records efficiently.</p>
        </section>

        <section id="contact">
            <h2>Contact Us</h2>
            <p>Email: support@maziwa.com | Phone: +123 456 7890</p>
        </section>
         

        <section id="services">
            <h2>Our Services</h2>
            <p>Track milk deliveries, manage payments, and stay connected with the latest updates.</p>
        </section>

        <video controls>
            <source src="farmers_story.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</body>
</html>
