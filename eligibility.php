<?php
session_start();
include("../db.php");

// Only allow admin
if(!isset($_SESSION['admin_username'])){
    header("Location: admin_login.php");
    exit();
}

$successMsg = "";

// Update eligibility criteria
if(isset($_POST['update_eligibility'])){
    $scheme_id = $_POST['scheme_id'];
    $min_age = $_POST['min_age'];
    $max_income = $_POST['max_income'];
    $gender = $_POST['gender'];
    // Check if eligibility already exists
    $check = $conn->query("SELECT * FROM eligibility WHERE scheme_id=$scheme_id");
    if($check->num_rows > 0){
        // Update existing eligibility
        $conn->query("UPDATE eligibility SET min_age=$min_age, max_income=$max_income, gender='$gender' WHERE scheme_id=$scheme_id");
    } else {
        // Insert new eligibility
        $conn->query("INSERT INTO eligibility (scheme_id, min_age, max_income, gender) VALUES ($scheme_id, $min_age, $max_income, '$gender')");
    }

    $successMsg = "Eligibility criteria updated successfully!";
}

// Fetch all schemes for dropdown
$schemes = $conn->query("SELECT * FROM schemes");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Eligibility Criteria</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            padding: 20px;
            max-width: 500px;
            margin: auto;
        }
        h2 { text-align: center; color:#333; margin-bottom: 20px; }
        form { background:#fff; padding:20px; border-radius:10px; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
        select, input { width:100%; padding:10px; margin:8px 0 15px 0; border-radius:5px; border:1px solid #ccc; font-size:14px; }
        button { width:100%; padding:12px; border:none; border-radius:6px; background-color:#28a745; color:white; font-weight:bold; cursor:pointer; }
        button:hover { background-color:#1e7e34; }
        .back-btn { display:block; text-align:center; background-color:#007BFF; color:white; padding:10px 15px; border-radius:6px; text-decoration:none; font-weight:bold; margin-top:15px; }
        .back-btn:hover { background-color:#0056b3; }
    </style>
</head>
<body>

<h2>Update Eligibility Criteria</h2>

<form method="POST">
    <select name="scheme_id" id="schemeSelect" required>
        <option value="">Select Scheme</option>
        <?php while($row = $schemes->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['scheme_name']; ?></option>
        <?php endwhile; ?>
    </select>

    <input type="number" name="min_age" id="min_age" placeholder="New Minimum Age" required>
    <input type="number" name="max_income" id="max_income" placeholder="New Maximum Income" required>
<select name="gender" id="gender" required>
    <option value="">Select Gender</option>
    <option value="All">All</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
</select>
    <button type="submit" name="update_eligibility">Update Eligibility</button>
</form>

<a href="admin_dashboard.php" class="back-btn">Return to Dashboard</a>

<?php if($successMsg != ""): ?>
<script>
    alert("<?php echo $successMsg; ?>");
</script>
<?php endif; ?>

<script>
// Optional: Auto-fill eligibility if already exists
const schemeSelect = document.getElementById('schemeSelect');
schemeSelect.addEventListener('change', function() {
    const scheme_id = this.value;
    if(!scheme_id) return;

    // Fetch current eligibility via AJAX
    fetch('get_eligibility.php?scheme_id=' + scheme_id)
    .then(res => res.json())
    .then(data => {
        document.getElementById('min_age').value = data.min_age || '';
        document.getElementById('max_income').value = data.max_income || '';
        document.getElementById('gender').value = data.gender || '';
    });
});
</script>

</body>
</html>
