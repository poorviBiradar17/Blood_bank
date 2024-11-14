<?php      
$host = "localhost";  // Keep it as 'localhost' if the MySQL server is running locally
$user = "root";  // Ensure this is the MySQL user you want to use
$password = 'Poorvi@17';  // Enter the MySQL root user's password
$db_name = "blood_bank";  // Your database name

// Establish connection to the standalone MySQL server
$con = mysqli_connect($host, $user, $password, $db_name);  

// Check connection
if(mysqli_connect_errno()) {  
    die("Failed to connect with MySQL: ". mysqli_connect_error());  
}

// Set the connection character set to utf8mb4 (handles special characters like emojis, etc.)
mysqli_set_charset($con, "utf8mb4");

?>
