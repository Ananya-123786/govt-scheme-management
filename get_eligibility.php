<?php
include("../db.php");

if(isset($_GET['scheme_id'])){
    $scheme_id = $_GET['scheme_id'];

    $result = $conn->query("SELECT * FROM eligibility WHERE scheme_id=$scheme_id");

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        echo json_encode([
            "min_age" => $row['min_age'],
            "max_income" => $row['max_income'],
            "gender" => $row['gender']
        ]);
    } else {
        echo json_encode([]);
    }
}
?>
