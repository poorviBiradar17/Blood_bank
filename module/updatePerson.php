<?php
session_start();

// Ensure user is logged in and is an admin
if ($_SESSION["login"] != 1 || $_SESSION["user_type"] != "admin") {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
    exit();
} else {
    include_once('connection.php');  // Include database connection

    // Get the form data
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $address = $_POST['address'];
    $med_issues = $_POST['med_issues'];

    // Update the person's details in the database
    $sql = "UPDATE Person 
            SET p_name = '$name', p_phone = '$phone', p_gender = '$gender', p_dob = '$dob', 
                p_blood_group = '$blood_group', p_address = '$address', p_med_issues = '$med_issues'
            WHERE p_id = '$pid'";

    if (mysqli_query($con, $sql)) {
        echo "<h2>Person details updated successfully!</h2>";
        echo "<p><a href='searchPerson.php'>Go back to Search Person</a></p>";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }

    include_once('footer.php');
}
?>
