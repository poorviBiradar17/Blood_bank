<?php
session_start();

// Ensure the user is logged in and has the appropriate role
if ($_SESSION["login"] != 1 || (isset($_SESSION["user_role"]) && $_SESSION["user_role"] != "admin")) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
} else {
    include_once('header.php');

    // Database connection
    //$con = mysqli_connect("localhost", "root", "", "blood_donation");

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if PID is provided
    if (isset($_POST['pid'])) {
        $pid = mysqli_real_escape_string($con, $_POST['pid']);

        // Step 1: Delete records in related tables (Donation, Receive, etc.)
        $sql_delete_donations = "DELETE FROM Donation WHERE p_id = '$pid'";
        $sql_delete_receives = "DELETE FROM Receive WHERE p_id = '$pid'";

        if (mysqli_query($con, $sql_delete_donations) && mysqli_query($con, $sql_delete_receives)) {
            // Step 2: Delete the person from the Person table
            $sql_delete_person = "DELETE FROM Person WHERE p_id = '$pid'";
            if (mysqli_query($con, $sql_delete_person)) {
                echo "Person with PID: $pid has been deleted successfully.<br>";
            } else {
                echo "Error deleting record from Person table: " . mysqli_error($con) . "<br>";
            }
        } else {
            echo "Error deleting related records: " . mysqli_error($con) . "<br>";
        }

        // Redirect back to search page after deletion
        echo '<a href="searchPerson.php">Go back to Search Person</a>';
    } else {
        echo "No person ID provided!";
    }

    // Close the database connection
    //mysqli_close($con);

    include_once('footer.php');
}
?>
