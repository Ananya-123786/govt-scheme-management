<?php
include("db.php");  // Make sure your db.php connects correctly
$registrationSuccess = false;
$errorMsg = "";

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact = $_POST['contact'];

    // Check if passwords match
    if($password != $confirm_password){
        $errorMsg = "Passwords do not match!";
    } else {
        // Optional: check for duplicate username
        $check = $conn->query("SELECT * FROM users WHERE username='$username'");
        if($check->num_rows > 0){
            $errorMsg = "Username already exists!";
        } else {
            // Insert user into database
            $result = $conn->query("INSERT INTO users(username,password,contact) VALUES('$username','$password','$contact')");

if(!$result){
    die("Database insert failed: " . $conn->error);
}
$registrationSuccess = true;
            $registrationSuccess = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Register</h2>

<form id="registerForm" method="POST" onsubmit="return validateForm()" autocomplete="off" >

<label>Username</label>
<input type="text" id="username" name="username" autocomplete="Enter username" required>

<label>Password</label>
<input type="password" id="password" name="password" autocomplete="Enter password" required>

<label>Confirm Password</label>
<input type="password" id="confirm_password" name="confirm_password" autocomplete="Confirm password" required>
<label>Contact Number</label>
<input type="text" id="contact" name="contact" required>

<button type="submit" name="register">Register</button>

</form>

<script>
// Show alert and redirect if registration succeeded
<?php if($registrationSuccess){ ?>
alert("Registration Successful!");
window.location.href = "index.php";
<?php } ?>

<?php if($errorMsg != ""){ ?>
alert("<?php echo $errorMsg; ?>");
<?php } ?>

// Client-side form validation
function validateForm(){
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let confirm_password = document.getElementById("confirm_password").value;
    let contact = document.getElementById("contact").value;

    if(username.length < 3){
        alert("Username must be at least 3 characters");
        return false;
    }

    if(password != confirm_password){
        alert("Passwords do not match");
        return false;
    }

    if(contact.length != 10 || isNaN(contact)){
        alert("Contact number must be exactly 10 digits");
        return false;
    }

    return true;
}
</script>

</body>
</html>
