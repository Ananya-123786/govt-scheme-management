<?php
session_start();
include("db.php");

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check user in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        // User found → set session and redirect to dashboard
        $_SESSION['username'] = $username;
        echo "<script>alert('You are logged in successfully!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        // Invalid login
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Login</h2>

<div class="form-container">
<form method="POST" action="">
    Username:<br>
    <input type="text" name="username" required><br>
    Password:<br>
    <input type="password" name="password" required><br>
    <button type="submit" name="login">Login</button>
</form>
<a class="register" href="register.php">Register Here</a>
</div>

</body>
</html>
