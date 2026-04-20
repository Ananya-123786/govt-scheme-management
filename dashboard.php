<?php
session_start();
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
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
    $gender = $_POST['gender'];

    $query = "
        SELECT s.*
        FROM schemes s
        JOIN eligibility e ON s.id = e.scheme_id
        WHERE e.min_age <= $age 
        AND e.max_income >= $income
        AND (e.gender = '$gender' OR e.gender = 'All')
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
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: auto; background-color: #eef2f7; }
        h1 { text-align: center; }
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

        .disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .eligibility { text-align: center; margin-top: 30px; }
        input, select {
            padding: 8px;
            margin: 5px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        .logout {
            display: block;
            text-align: center;
            margin: 10px auto;
            background-color: #ff4d4d;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            width: 120px;
        }
    </style>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
<p style="text-align:center; color: green;"><?php echo $loginMsg; ?></p>

<a class="logout" href="logout.php">Logout</a>

<a href="my_applications.php" style="display:block;text-align:center;background:#007BFF;color:white;padding:10px;border-radius:5px;margin:10px auto;width:200px;text-decoration:none;">
    My Applications
</a>

<!-- ================= SCHEMES ================= -->
<h2>View Available Schemes</h2>

<div class="schemes">
<?php if(count($schemes) > 0){ ?>
    <?php foreach($schemes as $s){ ?>

        <?php
        // 🔥 Check if already applied
        $check = $conn->query("SELECT * FROM applications 
        WHERE scheme_id='".$s['id']."' AND name='$username'");

        $alreadyApplied = ($check->num_rows > 0);
        ?>

        <div class="scheme">
            <div>
                <strong><?php echo htmlspecialchars($s['scheme_name']); ?></strong><br><br>
                <?php echo htmlspecialchars($s['description']); ?>
            </div>

            <?php if($eligibilityChecked){ ?>

                <?php if($alreadyApplied){ ?>
                    <!-- ❌ Already Applied -->
                    <button class="apply disabled" onclick="alert('You have already applied for this scheme!')">
                        Already Applied
                    </button>
                <?php } else { ?>
                    <!-- ✅ Apply -->
                    <a href="apply.php?scheme_id=<?php echo $s['id']; ?>">
                        <button class="apply">Apply</button>
                    </a>
                <?php } ?>

            <?php } ?>

        </div>

    <?php } ?>
<?php } else { ?>
    <p style="text-align:center;">No schemes available.</p>
<?php } ?>
</div>

<!-- ================= ELIGIBILITY ================= -->
<h2>Check Eligibility Criteria</h2>

<div class="eligibility">
    <form method="POST">
        <input type="number" name="age" placeholder="Enter your age" required><br><br>
        <input type="number" name="income" placeholder="Enter your income" required><br><br>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others">Others</option>
        </select><br><br>

        <button type="submit" name="check">Check Eligibility</button>
    </form>
</div>

</body>
</html>
