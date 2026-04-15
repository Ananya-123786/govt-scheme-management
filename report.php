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

// Prepare data for chart
$scheme_names = [];
$app_counts = [];
while($row = $result->fetch_assoc()){
    $scheme_names[] = $row['scheme_name'];
    $app_counts[] = $row['total_applications'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Performance Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: auto; background: #eef2f7; }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        canvas { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .back-btn { display: block; text-align: center; background-color: #007BFF; color: white; padding: 12px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 20px auto 0 auto; width: 220px; }
        .back-btn:hover { background-color: #0056b3; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Performance Report: Applications per Scheme</h2>

<div class="chart-container">
    <canvas id="performanceChart"></canvas>
</div>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($scheme_names); ?>,
            datasets: [{
                label: 'Number of Applications',
                data: <?php echo json_encode($app_counts); ?>,
                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
</script>

<a href="admin_dashboard.php" class="back-btn">Return to Dashboard</a>
<script>
const ctx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($scheme_names); ?>,
        datasets: [{
            label: 'Number of Applications',
            data: <?php echo json_encode($app_counts); ?>,
            backgroundColor: 'rgba(0, 123, 255, 0.7)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        },
        plugins: { legend: { display: false }, tooltip: { enabled: true } }
    }
});
</script>
</body>
</html>
