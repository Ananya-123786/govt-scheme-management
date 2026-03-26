<?php
session_start();
include("../db.php");

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

// Handle approve/reject
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $action = $_GET['action'];
    if($action == "approve"){
        $conn->query("UPDATE applications SET status='Approved' WHERE id='$id'");
    } elseif($action == "reject"){
        $conn->query("UPDATE applications SET status='Rejected' WHERE id='$id'");
    }
}

// Get all applications
$sql = "SELECT a.id, u.username, s.scheme_name, a.status 
        FROM applications a
        JOIN users u ON a.user_id = u.id
        JOIN schemes s ON a.scheme_id = s.id";
$result = $conn->query($sql);
?>
<head>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h2>Manage Applications</h2>

<table border="1" cellpadding="5">
<tr>
    <th>User</th>
    <th>Scheme</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<script src="../script.js"></script>
</body>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?php echo $row['username']; ?></td>
    <td><?php echo $row['scheme_name']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <?php if($row['status'] == 'Pending'){ ?>
            <a href="?action=approve&id=<?php echo $row['id']; ?>">Approve</a> | 
            <a href="?action=reject&id=<?php echo $row['id']; ?>">Reject</a>
        <?php } else {
            echo "-";
        } ?>
    </td>
</tr>
<?php } ?>
</table>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a>