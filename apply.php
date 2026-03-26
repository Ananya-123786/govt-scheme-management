<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$scheme_id = $_GET['scheme_id'];

// Check if user already applied
$check = $conn->query("SELECT * FROM applications WHERE user_id='$user_id' AND scheme_id='$scheme_id'");
if($check->num_rows > 0){
    echo "<script>alert('You have already applied for this scheme!'); window.location.href='dashboard.php';</script>";
    exit();
}

if(isset($_POST['apply'])){
    // Insert application with status Pending
    $conn->query("INSERT INTO applications(user_id, scheme_id, status) VALUES('$user_id', '$scheme_id', 'Pending')");
    echo "<script>alert('Application Submitted Successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}

// Get scheme details
$scheme = $conn->query("SELECT * FROM schemes WHERE id='$scheme_id'")->fetch_assoc();
?>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Apply for Scheme: <?php echo $scheme['scheme_name']; ?></h2>
<p><?php echo $scheme['description']; ?></p>

<form id="registerForm" method="POST" onsubmit="return validateForm()">
    <p>Click below to apply for this scheme:</p>
    <button name="apply">Apply</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>
<script src="script.js"></script>
</body>