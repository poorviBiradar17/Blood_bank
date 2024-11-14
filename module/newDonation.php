<?php
session_start();
$_SESSION["tab"] = "New Donation";

if($_SESSION["login"] != 1) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
} else {
    include_once('header.php');
    include_once('connection.php');

    $pid = $_POST['pid'];
    $units = $_POST['units'];
    date_default_timezone_set("Asia/Calcutta");

    // First, check if the person exists
    $check_person = $con->prepare("SELECT COUNT(*) as count FROM Person WHERE p_id = ?");
    $check_person->bind_param("i", $pid);
    $check_person->execute();
    $result = $check_person->get_result();
    $person_exists = $result->fetch_object()->count;

    if ($person_exists == 0) {
        echo '<h2 style="color:red">Error: Person not found.</h2>';
    } else {
        // Prepare and execute the stored procedure
        if ($stmt = $con->prepare("CALL newDonationProcedure(?, ?, @result_message)")) {
            // Bind parameters
            $stmt->bind_param("ii", $pid, $units);
            
            // Execute the procedure
            if ($stmt->execute()) {
                // Get the result message
                $select = $con->query("SELECT @result_message AS message");
                $result = $select->fetch_object();
                
                if ($result && $result->message == 'Donation successful.') {
                    echo '<h2>Your donation is successful.</h2>';
                } else if ($result) {
                    echo '<h2 style="color:red">' . htmlspecialchars($result->message) . '</h2>';
                } else {
                    echo '<h2 style="color:red">Error: Unable to get procedure result.</h2>';
                }
            } else {
                echo '<h2 style="color:red">Error executing procedure: ' . $stmt->error . '</h2>';
            }
            
            $stmt->close();
        } else {
            echo '<h2 style="color:red">Error preparing statement: ' . $con->error . '</h2>';
        }
    }

    include_once('footer.php');
}
?>