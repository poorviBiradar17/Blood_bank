<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood_donation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create stored procedure if it doesn't exist
$createProcedure = "
    DELIMITER $$

    CREATE PROCEDURE IF NOT EXISTS deletePerson(IN p_person_id INT)
    BEGIN
        DELETE FROM Person WHERE person_id = p_person_id;
    END $$

    DELIMITER ;
";

// Execute the stored procedure creation query
if ($conn->query($createProcedure) === TRUE) {
    echo "Stored Procedure for Deletion created successfully.";
} else {
    echo "Error creating stored procedure: " . $conn->error;
}

$conn->close();
?>
