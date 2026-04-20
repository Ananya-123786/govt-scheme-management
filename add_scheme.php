<?php
session_start();
include("../db.php");

// Only allow admin
if(!isset($_SESSION['admin_username'])){
    header("Location: admin_login.php");
    exit();
}

$successMsg = "";

// Add new scheme
if(isset($_POST['add_scheme'])){

    $name = $_POST['scheme_name'];
    $details = $_POST['description'];
    $min_age = $_POST['min_age'];
    $max_income = $_POST['max_income'];
    $gender = $_POST['gender'];

    // Check duplicate (case-insensitive)
    $check = $conn->query("SELECT * FROM schemes WHERE LOWER(scheme_name)=LOWER('$name')");

    if($check->num_rows > 0){

        // ❌ Already exists
        echo "<script>alert('Scheme already exists!');</script>";

    } else {

        // ✅ Insert into schemes
        $conn->query("INSERT INTO schemes (scheme_name, description) VALUES ('$name','$details')");

        // Get inserted ID
        $scheme_id = $conn->insert_id;

        // ✅ Insert into eligibility ONLY once
        $conn->query("INSERT INTO eligibility (scheme_id, min_age, max_income, gender) 
                      VALUES ($scheme_id, $min_age, $max_income, '$gender')");

        echo "<script>alert('Scheme added successfully!');</script>";
    }
}
// Update scheme
if(isset($_POST['update_scheme'])){

    $id = $_POST['scheme_id'];
    $name = $_POST['update_name'];
    $details = $_POST['update_description'];
    $min_age = $_POST['update_min_age'];
    $max_income = $_POST['update_max_income'];
    $gender = $_POST['update_gender'];

    // 🔍 Fetch existing data
    $old = $conn->query("
        SELECT s.scheme_name, s.description, e.min_age, e.max_income, e.gender
        FROM schemes s
        LEFT JOIN eligibility e ON s.id = e.scheme_id
        WHERE s.id = $id
    ")->fetch_assoc();

    // 🧠 Check if anything changed
    if(
        $old['scheme_name'] == $name &&
        $old['description'] == $details &&
        $old['min_age'] == $min_age &&
        $old['max_income'] == $max_income &&
        $old['gender'] == $gender
    ){
        echo "<script>alert('No changes were made');</script>";
    } else {

        // ✅ Perform update
        $conn->query("UPDATE schemes 
                      SET scheme_name='$name', description='$details' 
                      WHERE id=$id");

        $conn->query("UPDATE eligibility 
                      SET min_age=$min_age, max_income=$max_income, gender='$gender' 
                      WHERE scheme_id=$id");

        echo "<script>alert('Scheme updated successfully');</script>";
    }
}

// Delete scheme
if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
    $id = $_GET['delete'];

    $conn->query("DELETE FROM schemes WHERE id=$id");
    $conn->query("DELETE FROM eligibility WHERE scheme_id=$id");

    echo "<script>
        alert('Scheme deleted successfully');
        window.location='add_scheme.php';
    </script>";
    exit();
}
// Fetch all schemes
$schemes = $conn->query("SELECT s.id, s.scheme_name, s.description, e.min_age, e.max_income, e.gender 
FROM schemes s LEFT JOIN eligibility e ON s.id = e.scheme_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Schemes</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial; padding: 20px; max-width: 700px; margin: auto; background:#f0f4f7; }
        h2 { text-align: center; color:#333; margin-bottom: 15px; }
        form { 
    background: #fff; 
    padding: 30px 25px;       /* bigger padding */
    border-radius: 10px; 
    max-width: 600px;          /* wider form */
    margin: auto;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
        input, textarea, select { width: 100%; padding: 10px; margin: 5px 0; border-radius: 4px; border: 1px solid #ccc; font-size:14px; }
        button { padding: 10px; margin-top:10px; border-radius:5px; border:none; cursor:pointer; font-weight:bold; font-size:14px; }
        button.add { background-color:#007BFF; color:white; width:100%; }
        button.add:hover { background-color:#0056b3; }
        button.update { background-color:#28a745; color:white; width:100%; }
        button.update:hover { background-color:#1e7e34; }
        button.delete { background-color:#dc3545; color:white; }
        button.delete:hover { opacity:0.85; }
        .scheme-list { background:#fff; padding:15px; border-radius:8px; box-shadow:0 6px 20px rgba(0,0,0,0.1);}
        .scheme-item { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #ccc; }
        .scheme-item span { flex:1; }
        .back-btn { display:block; text-align:center; background-color:#007BFF; color:white; padding:10px 16px; border-radius:5px; text-decoration:none; font-weight:bold; margin-top:15px; }
        .back-btn:hover { background-color:#0056b3; }
    </style>
</head>
<body>

<h2>Add New Scheme</h2>
<form method="POST">
    <input type="text" name="scheme_name" placeholder="Scheme Name" required>
    <textarea name="description" placeholder="Description" rows="3" required></textarea>
    <input type="number" name="min_age" placeholder="Minimum Age" required>
    <input type="number" name="max_income" placeholder="Maximum Income" required>
    <select name="gender" required>
    <option value="">Select Gender</option>
    <option value="All">All</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
</select>
    <button type="submit" name="add_scheme" class="add">Add Scheme</button>
</form>

<h2>Update Existing Scheme</h2>
<form method="POST">
    <select name="scheme_id" id="updateSelect" required>
    <option value="">Select Scheme</option>

    <?php while($row = $schemes->fetch_assoc()): ?>
        <option value="<?php echo $row['id']; ?>"
            data-name="<?php echo htmlspecialchars($row['scheme_name']); ?>"
            data-description="<?php echo htmlspecialchars($row['description']); ?>"
            data-min_age="<?php echo $row['min_age']; ?>"
            data-max_income="<?php echo $row['max_income']; ?>"
            data-gender="<?php echo isset($row['gender']) ? $row['gender'] : ''; ?>">
            
            <?php echo htmlspecialchars($row['scheme_name']); ?>
        </option>
    <?php endwhile; ?>

</select>
    <input type="text" name="update_name" id="update_name" placeholder="Updated Name" required>
    <textarea name="update_description" id="update_description" placeholder="Updated Description" rows="3" required></textarea>
    <input type="number" name="update_min_age" id="update_min_age" placeholder="Minimum Age" required>
    <input type="number" name="update_max_income" id="update_max_income" placeholder="Maximum Income" required>
    <select name="update_gender" id="update_gender" required>
    <option value="">Select Gender</option>
    <option value="All">All</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
</select>
    <button type="submit" name="update_scheme" class="update">Update Scheme</button>
</form>

<h2>View Schemes</h2>
<div class="scheme-list">
    <?php
    $schemes = $conn->query("SELECT s.id, s.scheme_name, s.description, e.min_age, e.max_income 
                             FROM schemes s LEFT JOIN eligibility e ON s.id = e.scheme_id");
    while($row = $schemes->fetch_assoc()):
    ?>
    <div class="scheme-item">
        <span><strong><?php echo $row['scheme_name']; ?></strong>: <?php echo $row['description']; ?> (Age ≥ <?php echo $row['min_age']; ?>, Income ≤ <?php echo $row['max_income']; ?>)</span>
        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete this scheme?');">
            <button type="button" class="delete">Delete</button>
        </a>
    </div>
    <?php endwhile; ?>
</div>

<a href="admin_dashboard.php" class="back-btn">Return to Dashboard</a>

<?php if($successMsg != "") { ?>
<script>
    alert("<?php echo $successMsg; ?>");
</script>
<?php } ?>

<script>
// Auto-fill update form when a scheme is selected
const select = document.getElementById('updateSelect');
const nameInput = document.getElementById('update_name');
const descriptionInput = document.getElementById('update_description');
const minAgeInput = document.getElementById('update_min_age');
const maxIncomeInput = document.getElementById('update_max_income');
const genderInput = document.getElementById('update_gender');
select.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    nameInput.value = selected.dataset.name || '';
    descriptionInput.value = selected.dataset.description || '';
    minAgeInput.value = selected.dataset.min_age || '';
    maxIncomeInput.value = selected.dataset.max_income || '';
    genderInput.value = selected.dataset.gender || '';
});
</script>

</body>
</html>
