<?php
session_start();
include("db.php");

// Check login
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user data
$user = $conn->query("SELECT * FROM users WHERE username='$username'")->fetch_assoc();

$msg = "";

// Update profile
if(isset($_POST['update'])){
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    if(!empty($password)){
        $conn->query("UPDATE users SET contact='$contact', password='$password' WHERE username='$username'");
    } else {
        $conn->query("UPDATE users SET contact='$contact' WHERE username='$username'");
    }

    $msg = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body { font-family: Arial; padding: 20px; background:#eef2f7; }
        form {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            padding: 10px;
            background: green;
            color: white;
            border: none;
            width: 100%;
        }
        h2 { text-align:center; }
.back-btn {
    display: block;
    text-align: center;
    background-color: #007BFF;
    color: white;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    margin: 20px auto;
    width: 220px;
}
.back-btn:hover {
    background-color: #0056b3;
}
    </style>
</head>
<body>

<h2>My Profile</h2>

<?php if($msg != "") echo "<p style='text-align:center;color:green;'>$msg</p>"; ?>

<form method="POST">
    <label>Username</label>
    <input type="text" value="<?php echo $user['username']; ?>" disabled>

    <label>Contact</label>
    <input type="text" name="contact" value="<?php echo $user['contact']; ?>" required>

    <label>New Password (optional)</label>
    <input type="password" name="password" placeholder="Enter new password">

    <button type="submit" name="update">Update Profile</button>
</form>
<a href="dashboard.php" class="back-btn">Return to Dashboard</a>
</body>
</html>