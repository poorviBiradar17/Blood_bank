<?php
session_start();
$_SESSION["tab"] = "Add Person";

// Check if the user is logged in and is an admin
if($_SESSION["login"] != 1) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
    exit();
}
include_once('header.php'); 
include_once('connection.php'); // include database connection

$name = $_POST['name'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$blood_group = $_POST['blood_group'];
$address = $_POST['address'];
$med_issues = $_POST['med_issues'];

// Check for duplicate record
$check_sql = "SELECT p_id FROM Person WHERE p_name = '$name' AND p_phone = '$phone' AND p_dob = '$dob'";
$check_result = mysqli_query($con, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // Duplicate found
    $_SESSION["entry_error"] = "A person with the same name, phone number, and date of birth already exists.";
    header("Location: add Person.php"); // Redirect back to the form
    exit();
} else {
    // No duplicate, proceed with insert
    $sql = "INSERT INTO Person (p_name, p_phone, p_dob, p_address, p_gender, p_blood_group, p_med_issues) 
            VALUES ('$name', '$phone', '$dob', '$address', '$gender', '$blood_group', '$med_issues')";

    if ($con->query($sql) === TRUE) {
        // Retrieve the newly inserted person's ID
        $sql = "SELECT p_id FROM Person WHERE p_name ='$name' AND p_phone = '$phone' AND p_gender = '$gender' 
                AND p_dob = '$dob' AND p_blood_group = '$blood_group' AND p_address = '$address' 
                AND p_med_issues = '$med_issues'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        $pid = $row['p_id'];

        echo '<h2>' . $name . '</h2><br><br>';
        echo 'Your registration is successful.<br><br>';
        echo 'Personal ID : ' . $pid . '<br><br>';
        echo 'Name : ' . $name . '<br><br>';
        echo 'Phone Number: ' . $phone . '<br><br>';
        echo 'DOB : ' . $dob . '<br><br>';
        echo 'Blood Group : ' . $blood_group . '<br><br>';

        echo 'Gender : ';
        if ($gender === 'm') echo 'Male<br><br>';
        elseif ($gender === 'f') echo 'Female<br><br>';
        elseif ($gender === 'o') echo 'Other<br><br>';

        echo 'Address : ' . $address . '<br><br>';
        echo 'Medical Issues : ' . ($med_issues ? $med_issues : 'None') . '<br><br>';
        echo '<div style="color:red;">NB: Please keep your Personal ID for future reference!</div>';
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

include_once('footer.php');
?>
