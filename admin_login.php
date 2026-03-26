<?php
session_start();
include("../db.php");

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
    if($res->num_rows > 0){
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Reset default margins/padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body takes full height and centers content */
html, body {
    height: 100%;
    font-family: Arial, sans-serif;
    background-color: #f0f4f7;
    display: flex;
    justify-content: center; /* horizontal center */
    align-items: center;     /* vertical center */
}

/* Card container for login form */
.login-container {
    background-color: #ffffff;
    padding: 50px 45px;   /* increased padding for bigger form */
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    width: 420px;         /* increased width */
    text-align: center;
}

/* Heading */
.login-container h2 {
    margin-bottom: 30px;
    color: #333;
    font-size: 26px;      /* slightly bigger font */
}

/* Input fields */
.login-container input[type="text"], 
.login-container input[type="password"] {
    width: 100%;
    padding: 16px;        /* increased padding */
    margin: 14px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;      /* slightly bigger text */
}

/* Login button */
.login-container button {
    width: 100%;
    padding: 16px;        /* bigger button */
    margin-top: 18px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 18px;      /* bigger text */
    cursor: pointer;
}

.login-container button:hover {
    background-color: #0056b3;
}

/* Error message */
.login-container .error {
    color: red;
    margin-top: 14px;
    font-size: 15px;
}

/* Responsive for small screens */
@media screen and (max-width: 500px) {
    .login-container {
        width: 90%;
        padding: 35px;
    }
}
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <?php if($error != "") { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
    </div>
</div>

</body>
</html>