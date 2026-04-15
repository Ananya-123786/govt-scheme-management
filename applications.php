<?php
session_start();
include("../db.php");

// Only allow admin
if(!isset($_SESSION['admin_username'])){
    header("Location: admin_login.php");
    exit();
}

// Fetch number of applications per scheme
$query = "
    SELECT s.scheme_name, COUNT(a.id) AS total_applications
    FROM schemes s
    LEFT JOIN applications a ON s.id = a.scheme_id
    GROUP BY s.id
    ORDER BY s.id ASC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Applications per Scheme</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; background: #eef2f7; }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007BFF; color: white; }
        tr:hover { background-color: #f2f2f2; }

        .back-btn { display: block; text-align: center; background-color: #007BFF; color: white; padding: 12px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 20px auto 0 auto; width: 220px; }
        .back-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<h2>Applications per Scheme</h2>

<table>
    <tr>
        <th>S.No</th>
        <th>Scheme Name</th>
        <th>Total Applications</th>
    </tr>

    <?php 
    $count = 1;
    while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $count++; ?></td>
        <td><?php echo htmlspecialchars($row['scheme_name']); ?></td>
        <td><?php echo $row['total_applications']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="admin_dashboard.php" class="back-btn">Return to Dashboard</a>

</body>
</html>
