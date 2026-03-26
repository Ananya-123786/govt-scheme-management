
<?php
session_start();
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}
// Handle application submission
if(isset($_POST['submit_application'])){
    $scheme_id = (int)$_POST['scheme_id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $age_input = (int)$_POST['age'];
    $income_input = (float)$_POST['income'];
    $gender = $_POST['gender'];

   $stmt = $conn->prepare("INSERT INTO applications 
    (scheme_id, name, contact, age, income, gender) 
    VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issdis", $scheme_id, $name, $contact, $age_input, $income_input, $gender);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Application submitted successfully!');</script>";
}
$username = $_SESSION['username'];
$loginMsg = "You are logged in successfully!";

// ========================
// Eligibility check logic
// ========================
$eligibilityChecked = false;
$age = $income = "";
$schemes = [];

if(isset($_POST['check'])){
    $age = (int)$_POST['age'];
    $income = (float)$_POST['income'];
    
    // Correct query using JOIN with eligibility table
    $query = "
        SELECT s.*
        FROM schemes s
        JOIN eligibility e ON s.id = e.scheme_id
        WHERE e.min_age <= $age AND e.max_income >= $income
        ORDER BY s.id ASC
    ";
    
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()){
        $schemes[] = $row;
    }
    
    $eligibilityChecked = true;
} else {
    // Default: show all schemes
    $result = $conn->query("SELECT * FROM schemes ORDER BY id ASC");
    while($row = $result->fetch_assoc()){
        $schemes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: auto; background-color: #eef2f7; }
        h2 { text-align: center; color: #333; }
        .schemes { display: flex; flex-direction: column; gap: 15px; }
        .scheme {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .scheme strong { color: #007BFF; font-size: 18px; }
        .scheme p { margin: 5px 0 0 0; color: #333; }
        .apply {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .apply:hover { background-color: #1e7e34; }
        .eligibility { text-align: center; margin-bottom: 20px; }
        .eligibility input { padding: 8px; margin: 5px; width: 150px; border-radius: 5px; border: 1px solid #ccc; }
        .eligibility button { padding: 8px 15px; border-radius: 5px; border: none; background-color: #007BFF; color: white; cursor: pointer; }
        .eligibility button:hover { background-color: #0056b3; }
        .logout {
            display: block;
            text-align: center;
            margin: 10px auto 30px auto;
            background-color: #ff4d4d;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            width: 100px;
        }
        .logout:hover { background-color: #e60000; }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
<p style="text-align:center; color: green;"><?php echo $loginMsg; ?></p>

<a class="logout" href="logout.php">Logout</a>

<!-- Eligibility Check Form -->
<div class="eligibility">
    <form method="POST">
        <input type="number" name="age" placeholder="Enter your age" value="<?php echo htmlspecialchars($age); ?>" required><br><br>
        <input type="number" name="income" placeholder="Enter your income" value="<?php echo htmlspecialchars($income); ?>" required><br><br>
        <button type="submit" name="check">Check Eligibility</button><br>
    </form>
</div>

<!-- Schemes List -->
<div class="schemes">
<?php if(count($schemes) > 0){ ?>
    <?php foreach($schemes as $s){ ?>
        <div class="scheme">
            <div>
                <strong><?php echo htmlspecialchars($s['scheme_name']); ?></strong><br><br>
                <?php echo isset($s['description']) ? htmlspecialchars($s['description']) : ''; ?>
            </div>
            <?php if($eligibilityChecked){ ?>
    <button onclick="showForm(<?php echo $s['id']; ?>)" class="apply">Apply</button>

    <div id="form-<?php echo $s['id']; ?>" style="display:none; margin-top:10px; background:#e6f2ff; padding:10px; border-radius:5px;">
        <form method="POST">
            <input type="hidden" name="scheme_id" value="<?php echo $s['id']; ?>">
            Name: <input type="text" name="name" required><br>
            Contact: <input type="text" name="contact" required><br>
            Age: <input type="number" name="age" required><br>
            Income: <input type="number" name="income" required><br>
            Gender: 
            <select name="gender" required>
                <option value="">Select</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
                <option value="Other">Other</option>
            </select><br><br>
            <button type="submit" name="submit_application" class="apply">Submit Application</button>
        </form>
    </div>
<?php } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <p style="text-align:center;">No schemes available.</p>
<?php } ?>
</div>
<script>
function showForm(id){
    var f = document.getElementById('form-' + id);
    f.style.display = (f.style.display === 'none') ? 'block' : 'none';
}
</script>
</body>
</html>