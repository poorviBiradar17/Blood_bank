<?php
// Check if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if ($_SESSION["login"] != 1) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
    exit();
}

// Include the database connection
include('connection.php');

// Check if form is submitted
if (isset($_POST['blood_group'])) {
    $blood_group = $_POST['blood_group']; // Get the blood group from the form input

    // Prepare the SQL query to get the total units for a particular blood group
    $sql = "SELECT s_blood_group, SUM(s_quantity) AS total_units
            FROM Stock
            WHERE s_blood_group = ?
            GROUP BY s_blood_group";

    // Prepare and execute the statement
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('s', $blood_group); // Bind the blood group parameter
        $stmt->execute();
        $stmt->bind_result($s_blood_group, $total_units); // Bind the result variables

        // Fetch and display the result
        if ($stmt->fetch()) {
            echo "<h2>Total Units for Blood Group: $s_blood_group</h2>";
            echo "<p>Total Units: $total_units</p>";
        } else {
            echo "<p>No records found for blood group: $blood_group</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error in query execution.</p>";
    }
}

// Close the database connection after all queries are done
$con->close();
?>

<!-- HTML form to get the blood group input -->
<form method="POST" action="">
    <label for="blood_group">Enter Blood Group:</label>
    <input type="text" name="blood_group" id="blood_group" required>
    <button type="submit">Get Total Units</button>
</form>
