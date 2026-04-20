<!DOCTYPE html>
<html>
<head>
    <title>Government Scheme Portal</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(to right, #e6f2ff, #ffffff);
        }

        /* HEADER */
        .header {
            background: #007BFF;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header img {
            width: 60px;
        }

        .header h2 {
            margin: 0;
        }

        /* HERO SECTION */
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 60px 20px;
            flex-wrap: wrap;
        }

        .hero-text {
            max-width: 500px;
        }

        .hero-text h1 {
            font-size: 36px;
            color: #333;
        }

        .hero-text p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
        }

        .hero img {
            width: 350px;
        }

        /* BUTTONS */
        .btn {
            display: inline-block;
            margin: 15px 10px 0 0;
            padding: 12px 25px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn.register {
            background: #28a745;
        }

        /* FEATURES */
        .features {
            text-align: center;
            padding: 40px 20px;
        }

        .features h2 {
            margin-bottom: 20px;
        }

        .feature-box {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 20px;
            width: 220px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .card img {
            width: 60px;
            margin-bottom: 10px;
        }

        /* MARQUEE */
        marquee {
            background: #007BFF;
            color: white;
            padding: 10px;
            font-weight: bold;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 15px;
            background: #333;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<!-- HEADER WITH CENTER LOGO -->
<div class="header" style="flex-direction: column; text-align: center;">

    <img src="logo.png" style="width:250px; height:auto; margin-bottom:10px;" alt="Logo">


</div>

<!-- HERO -->
<div class="hero">

    <div class="hero-text">
        <h1>Welcome to Government Scheme Portal</h1>
       <p>
        This Government Scheme Portal is designed to help citizens access multiple welfare schemes in one place. 
        Users can check eligibility based on their details, apply for schemes online, and track their application status easily.
    </p>


        <a href="index.php" class="btn">Login</a>
        <a href="register.php" class="btn register">Register</a>
    </div>

    <!-- 👇 You can change this image -->
    <img src="prof.png">

</div>

<!-- FEATURES -->
<div class="features">
    <h2>Portal Features</h2>

    <div class="feature-box">

        <div class="card">
            <img src="eligibility.png">
            <p>Check Eligibility Instantly</p>
        </div>

        <div class="card">
            <img src="applyy.png">
            <p>Apply for Schemes Online</p>
        </div>

        <div class="card">
            <img src="track.png">
            <p>Track Application Status</p>
        </div>

    </div>
</div>

<!-- MARQUEE -->
<marquee>
        🌐 Government Scheme Portal: One place to discover, check eligibility, apply, and track all government schemes easily and transparently
    </marquee>


<!-- FOOTER -->
<div class="footer">
    © 2026 Government Scheme Portal | All Rights Reserved
</div>

</body>
</html>s