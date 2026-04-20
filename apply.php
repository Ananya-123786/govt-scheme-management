<?php
session_start();
include("db.php");

// Check login
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Get scheme id
if(!isset($_GET['scheme_id'])){
    header("Location: dashboard.php");
    exit();
}

$scheme_id = (int)$_GET['scheme_id'];

// Fetch scheme name
$scheme = $conn->query("SELECT scheme_name FROM schemes WHERE id=$scheme_id")->fetch_assoc();
$scheme_name = $scheme['scheme_name'];

// Fetch user contact
$user = $conn->query("SELECT contact FROM users WHERE username='$username'")->fetch_assoc();
$contact = $user['contact'];

// Handle form submit
if(isset($_POST['submit'])){
    $age = (int)$_POST['age'];
    $income = (float)$_POST['income'];
    $gender = $_POST['gender'];

    // Check duplicate
    $check = $conn->query("SELECT * FROM applications 
        WHERE scheme_id='$scheme_id' AND name='$username'");

    if($check->num_rows > 0){
        echo "<script>alert('You already applied for this scheme!'); window.location='dashboard.php';</script>";
    } else {
        $conn->query("INSERT INTO applications 
        (scheme_id, name, contact, age, income, gender) 
        VALUES ('$scheme_id','$username','$contact','$age','$income','$gender')");

        echo "<script>alert('Application submitted successfully!'); window.location='dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Scheme</title>
    <style>
        body { font-family: Arial; background:#eef2f7; padding:20px; }
        h2 { text-align:center; }
        .form-box {
            background:#fff;
            padding:25px;
            max-width:400px;
            margin:auto;
            border-radius:10px;
            box-shadow:0 6px 15px rgba(0,0,0,0.1);
        }
        input, select {
            width:100%;
            padding:10px;
            margin:10px 0;
            border-radius:5px;
            border:1px solid #ccc;
        }
        button {
            width:100%;
            padding:10px;
            background:#28a745;
            color:white;
            border:none;
            border-radius:5px;
            font-weight:bold;
            cursor:pointer;
        }
        button:hover { background:#1e7e34; }

        .back-btn {
    display: block;
    text-align: center;
    background: #007BFF;
    color: white;
    padding: 12px;
    border-radius: 5px;
    text-decoration: none;
    margin: 20px auto;
    width: 400px;   /* same as form width */
    max-width: 100%;
    font-weight: bold;
}
.back-btn:hover {
    background-color: #0056b3;
}
    </style>
</head>
<body>

<h2>Apply for: <?php echo htmlspecialchars($scheme_name); ?></h2>

<div class="form-box">
<form method="POST">

    <label>Username</label>
    <input type="text" value="<?php echo htmlspecialchars($username); ?>" readonly>

    <label>Contact</label>
    <input type="text" value="<?php echo htmlspecialchars($contact); ?>" readonly>

    <label>Age</label>
    <input type="number" name="age" required>

    <label>Income</label>
    <input type="number" name="income" required>

    <label>Gender</label>
    <select name="gender" required>
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <button type="submit" name="submit">Submit Application</button>
</form>
</div>
<a href="dashboard.php" class="back-btn">Return to Dashboard</a>
</body>
</html>
