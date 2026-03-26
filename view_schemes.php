<?php
session_start();
if(!isset($_SESSION['admin_username'])){
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Schemes</title>
</head>
<body>
<h2>View/Update/Delete Schemes Page (placeholder)</h2>
<a href="admin_dashboard.php">Return to Dashboard</a>
</body>
</html>