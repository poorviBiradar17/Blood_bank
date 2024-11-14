<?php
session_start();
include('connection.php');

// Check if the form is submitted
if (isset($_POST['submit_signup'])) {
    // Get the input values
    $username = trim($_POST['user']);
    $password = trim($_POST['pass']);
    $user_type = trim($_POST['user_type']); // user type can be donor, recipient, or admin

    // Check if the fields are filled
    if (empty($username) || empty($password)) {
        $_SESSION['signup_error'] = 'Please fill in all fields.';
        header('Location: signup.php');
        exit();
    } else {
        // Check if user type is 'admin' and ensure only one admin exists
        if ($user_type === 'admin') {
            // Query to count the number of admins in the database
            $result = $con->query("SELECT COUNT(*) AS admin_count FROM user WHERE user_type = 'admin'");
            $row = $result->fetch_assoc();

            if ($row['admin_count'] > 0) {
                // Set session error message to display in GUI
                $_SESSION['signup_error'] = 'Only one admin user is allowed.';
                header('Location: signup.php');
                exit();
            }
        }

        // Prepare to call the stored procedure to insert user
        $stmt = $con->prepare("CALL add_user(?, ?, ?, @result_message)");
        if ($stmt) {
            // Bind parameters to the stored procedure
            $stmt->bind_param("sss", $username, $password, $user_type); // You should hash the password in a real application

            // Execute the stored procedure and check for errors
            if ($stmt->execute()) {
                // Retrieve the output message from the stored procedure
                $result = $con->query("SELECT @result_message AS result_message");
                $row = $result->fetch_assoc();

                // Display appropriate message based on the result from the stored procedure
                if ($row['result_message'] === 'Registration successful!') {
                    $_SESSION['signup_success'] = $row['result_message'];
                    header('Location: index.php');
                    exit();
                } else {
                    // If error message from stored procedure, display it
                    $_SESSION['signup_error'] = $row['result_message'];
                    header('Location: signup.php');
                    exit();
                }
            } else {
                // Generic error message if stored procedure execution fails
                $_SESSION['signup_error'] = 'Error executing the stored procedure.';
                header('Location: signup.php');
                exit();
            }
            $stmt->close();
        } else {
            $_SESSION['signup_error'] = 'Error preparing the stored procedure call: ' . $con->error;
            header('Location: signup.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>

    <div class="title">	
        <center>
            <img src="blood.png" height="100" width="100" align="top">
            <h1>BLOOD BANK MANAGEMENT SYSTEM</h1><br>
        </center>
    </div>

    <form name="f1" action="signup.php" method="POST">
        <h2>Signup</h2>

        <!-- Show error or success messages -->
        <?php
        if (isset($_SESSION['signup_error'])) {
            echo "<p class='error'>" . $_SESSION['signup_error'] . "</p>";
            unset($_SESSION['signup_error']);
        }

        if (isset($_SESSION['signup_success'])) {
            echo "<p class='success'>" . $_SESSION['signup_success'] . "</p>";
            unset($_SESSION['signup_success']);
        }
        ?>

        <p>  
            <label>Username:</label><br><br>
            <input type="text" class="input" name="user" placeholder="Enter Username" required />  
        </p>  

        <p>  
            <label>Password:</label><br><br>
            <input type="password" class="input" name="pass" placeholder="Enter Password" required />  
        </p>

        <p>
            <label>User Type:</label><br><br>
            <select name="user_type" class="input" required>
                <option value="donor">Donor</option>
                <option value="recipient">Recipient</option>
                <option value="admin">Admin</option>
            </select>
        </p>  

        <p>  
            <input type="submit" id="btn" value="Sign Up" name="submit_signup"/>  
        </p>

        <p>  
            <a href="index.php">Already a user? Login here.</a>
        </p> 
    </form>   

</body>     
</html>
