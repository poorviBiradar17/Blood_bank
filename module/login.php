<?php
session_start();
include('connection.php'); 

// Check if the submit is set
if(isset($_POST['submit'])){

    // Prevent SQL injection possibilities
    $username = stripcslashes($_POST['user']);  
    $username = mysqli_real_escape_string($con, $username);  
    $password = stripcslashes($_POST['pass']);  
    $password = mysqli_real_escape_string($con, $password);  

    // Check whether the username or password is empty
    if($username === '' || $password === ''){
        $_SESSION["login_error"] = 'Username or password cannot be empty';
        header('Location: index.php');
        exit();
    }

    // Query to check whether the user is present
    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";  
    $result = $con->query($sql);

    // User is present
    if($result->num_rows > 0){  
        $row = $result->fetch_assoc();
        $_SESSION["login"] = 1;
        $_SESSION["username"] = $row["username"];
        $_SESSION["user_type"] = $row["user_type"]; // Store user type in session
        header('Location: Home.php');
        exit();
    }  
    // User is not present
    else{
        $_SESSION["login"] = 0;
        $_SESSION["login_error"] = 'Invalid login credentials. New user? Click to register.';
        header('Location: index.php');
        exit();
    }
} 
// Submit is not set, redirect to homepage
else {
    header('Location: home.php');
    exit();
}
?>
