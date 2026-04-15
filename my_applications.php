<?php
session_start();
include("db.php");

// Check login
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

// Get user contact
$user = $conn->query("SELECT contact FROM users WHERE username='$username'")->fetch_assoc();
$contact = $user['contact'];

// Fetch applications
$result = $conn->query("
    SELECT a.*, s.scheme_name 
    FROM applications a
    JOIN schemes s ON a.scheme_id = s.id
    WHERE a.contact = '$contact'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Applications</title>
    <style>
        body { font-family: Arial; padding: 20px; background:#eef2f7; }
        h2 { text-align:center; }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th { background: #007BFF; color: white; }

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

<h2>My Applications</h2>

<table>
<tr>
    <th>Scheme</th>
    <th>Status</th>
    <th>Applied Date</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?php echo $row['scheme_name']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td><?php echo $row['applied_date']; ?></td>
</tr>
<?php } ?>

</table>
<a href="dashboard.php" class="back-btn">Return to Dashboard</a>
</body>
</html>