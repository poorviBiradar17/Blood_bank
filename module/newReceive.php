<?php
session_start();
$_SESSION["tab"] = "New Receive";

if ($_SESSION["login"] != 1) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
} else {
    include_once('header.php');
    
    // Get form data
    $pid = $_POST['pid'];
    $units = $_POST['units'];
    $hospital = $_POST['hospital'];

    try {
        // Prepare and execute the INSERT query for the Receive table
        $stmt = $con->prepare("INSERT INTO Receive (p_id, r_date, r_time, r_quantity, r_hospital) VALUES (?, CURDATE(), CURTIME(), ?, ?)");
        $stmt->bind_param("iis", $pid, $units, $hospital);
        
        // Execute the query
        $stmt->execute();

        // Check for any trigger-generated warnings (errors)
        $result = $con->query("SHOW WARNINGS");
        if ($result && $result->num_rows > 0) {
            // If there are warnings (from the trigger), fetch and display the message
            $row = $result->fetch_assoc();
            echo "<h2 style='color:red'>" . $row['Message'] . "</h2>";
        } else {
            // If no warnings, the insert was successful
            echo "<h2>Your receiving is successful.</h2>";
        }

        // Close the statement
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        // Handle MySQL errors gracefully
        echo "<h2 style='color:red'>" . $e->getMessage() . "</h2>";
    }

    include_once('footer.php');
}
?>
