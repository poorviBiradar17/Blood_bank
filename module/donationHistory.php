<?php
session_start();
$_SESSION["tab"] = "Donation History";

if ($_SESSION["login"] != 1)
    echo '<h2 txtcolor="red">Authentication Error!!!</h2>';
else {
    include_once('header.php');
    
    // Get the start date and end date from the form
    $sdate = $_POST['sdate'];  
    $edate = $_POST['edate'];  
    
    // Main query with nested subqueries for total donations and total quantity donated
    $sql = "
        SELECT d.d_date, d.d_time, d.d_quantity, p.p_id, p.p_name, p.p_phone, p.p_blood_group,
            (SELECT COUNT(*) FROM Donation WHERE p_id = p.p_id) AS total_donations,
            (SELECT SUM(d_quantity) FROM Donation WHERE p_id = p.p_id) AS total_blood_donated
        FROM Person p
        JOIN Donation d ON p.p_id = d.p_id
        WHERE d.d_date >= '$sdate' 
        AND d.d_date <= '$edate'
    ";
    
    $result = mysqli_query($con, $sql);
    
    ###########contents of div goes here###########
    
    echo "<h2>Donation History</h2><br>";
    
    // Check if there are any results
    if ($result->num_rows > 0) {
        echo "
        <table>
            <tr>
                <th>Personal ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Blood Group</th>
                <th>Date</th>
                <th>Time</th>
                <th>Units of Blood</th>
                <th>Total Donations</th>
                <th>Total Blood Donated</th>
            </tr>
        ";
        
        // Fetch each row of data
        while ($row = $result->fetch_assoc()) {
            echo "
            <tr>
                <td>" . $row["p_id"] . "</td>
                <td>" . $row["p_name"] . "</td>
                <td>" . $row["p_phone"] . "</td>
                <td>" . $row["p_blood_group"] . "</td>
                <td>" . $row["d_date"] . "</td>
                <td>" . $row["d_time"] . "</td>
                <td>" . $row["d_quantity"] . "</td>
                <td>" . $row["total_donations"] . "</td>
                <td>" . $row["total_blood_donated"] . "</td>
            </tr>";
        }
        
        echo "</table><br><br>";
    } else {
        echo "No record found in the specified interval";
    }
    
    include_once('footer.php');
}
?>
