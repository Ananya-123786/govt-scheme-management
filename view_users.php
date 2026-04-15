<?php
session_start();
include("../db.php");

// Only allow admin
if(!isset($_SESSION['admin_username'])){
    header("Location: admin_login.php");
    exit();
}

// Approve application
if(isset($_GET['approve'])){
    $app_id = intval($_GET['approve']);
    $conn->query("UPDATE applications SET status='Approved' WHERE id=$app_id");
    $msg = "Application approved successfully!";
}

// Reject application
if(isset($_GET['reject'])){
    $app_id = intval($_GET['reject']);
    $conn->query("UPDATE applications SET status='Rejected' WHERE id=$app_id");
    $msg = "Application rejected successfully!";
}

// Fetch all applications
$applications = $conn->query("SELECT * FROM applications ORDER BY applied_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Applications</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 1000px; margin: auto; background: #eef2f7; }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007BFF; color: white; }
        tr:hover { background-color: #f2f2f2; }

        button { padding: 6px 12px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold; margin-right: 5px; }
        button.approve { background-color: #28a745; color: white; }
        button.approve:hover { background-color: #1e7e34; }
        button.reject { background-color: #dc3545; color: white; }
        button.reject:hover { opacity: 0.85; }

        .back-btn { display: block; text-align: center; background-color: #007BFF; color: white; padding: 12px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 20px auto 0 auto; width: 220px; }
        .back-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<h2>View Applications</h2>

<?php if(isset($msg)): ?>
<script>alert("<?php echo $msg; ?>");</script>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Age</th>
        <th>Income</th>
        <th>Gender</th>
        <th>Applied Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while($app = $applications->fetch_assoc()): ?>
    <tr>
        <td><?php echo $app['id']; ?></td>
        <td><?php echo htmlspecialchars($app['name']); ?></td>
        <td><?php echo htmlspecialchars($app['contact']); ?></td>
        <td><?php echo $app['age']; ?></td>
        <td><?php echo $app['income']; ?></td>
        <td><?php echo $app['gender']; ?></td>
        <td><?php echo $app['applied_date']; ?></td>
        <td><?php echo $app['status'] ?? 'Pending'; ?></td>
        <td>
            <?php 
$status = $app['status'] ?? 'Pending';
?>
<a href="?approve=<?php echo $app['id']; ?>">
    <button class="approve" <?php echo ($status != 'Pending') ? 'disabled style="opacity:0.6;cursor:not-allowed;"' : ''; ?>>Approve</button>
</a>
<a href="?reject=<?php echo $app['id']; ?>">
    <button class="reject" <?php echo ($status != 'Pending') ? 'disabled style="opacity:0.6;cursor:not-allowed;"' : ''; ?>>Reject</button>
</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="admin_dashboard.php" class="back-btn">Return to Dashboard</a>

</body>
</html>
