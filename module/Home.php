<?php
session_start();
$_SESSION["tab"] = "Home";

if ($_SESSION["login"] != 1) {
    echo '<h2 style="color:red;">Authentication Error!!!</h2>';
} else {
    include_once('header.php');

    // Start of main content with background styling
    echo "<div style='
        background-image: url(\"background2.png\");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        padding: 20px;
        font-family: Arial, sans-serif;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        min-height: 100vh;
    '>";
    

    // // Fetch and display the total registrations
    // $sql = "SELECT COUNT(p_id) FROM Person";
    // $result = mysqli_query($con, $sql);  
    // $row = mysqli_fetch_array($result);  
    // echo "<p>We have registered <strong>".$row[0]."</strong> individuals willing to contribute to our life-saving mission.</p>";

    // // Fetch and display the total donations
    // $sql = "SELECT COUNT(p_id) FROM Donation";
    // $result = mysqli_query($con, $sql);  
    // $row = mysqli_fetch_array($result);  
    // echo "<p>So far, we have received <strong>".$row[0]."</strong> donations from registered donors.</p>";

    // // Fetch and display the total blood given
    // $sql = "SELECT COUNT(p_id) FROM Receive";
    // $result = mysqli_query($con, $sql);  
    // $row = mysqli_fetch_array($result);  
    // echo "<p>We have supplied blood in <strong>".$row[0]."</strong> instances to those in need, helping save lives in critical times.</p>";

    // echo "<p>Our journey continues as we work tirelessly to make blood donation accessible, reliable, and impactful for society.</p>";
    // echo "</div>";

    include_once('footer.php');
}
?>
