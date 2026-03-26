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
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard-container {
            text-align: center;
            background-color: #fff;
            padding: 50px 40px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            width: 450px;
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .dashboard-buttons a {
            text-decoration: none;
        }

        .dashboard-buttons button {
            width: 100%;
            padding: 16px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            transition: 0.3s;
        }

        .dashboard-buttons button:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 500px) {
            .dashboard-container { width: 90%; padding: 35px; }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Welcome, <?php echo $_SESSION['admin_username']; ?>!</h2>

    <div class="dashboard-buttons">
        <!-- 6.1.1 Add New Government Scheme -->
        <a href="add_scheme.php"><button>Add Schemes</button></a>
        <!-- 6.1.2 Update Scheme Eligibility Criteria -->
        <a href="eligibility.php"><button>Update Eligibility Criteria</button></a>

        <!-- 6.1.3 View Users -->
        <a href="view_users.php"><button>View Users</button></a>

        <!-- 6.1.4 View Number of Applications -->
        <a href="applications.php"><button>View Applications</button></a>

        <!-- 6.1.5 Generate Performance Report -->
        <a href="report.php"><button>Performance Report</button></a>
    </div>
</div>

</body>
</html>