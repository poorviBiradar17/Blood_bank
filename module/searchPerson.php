<?php
session_start();
$_SESSION["tab"] = "Search Person";

// Ensure user is logged in
if ($_SESSION["login"] != 1) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
} else {
    include_once('header.php');
    include_once('connection.php'); // Include database connection

    // Check if a person ID is submitted
    if (isset($_POST['pid'])) {
        $pid = mysqli_real_escape_string($con, $_POST['pid']);  // Sanitize input

        // Query to fetch person details
        $sql = "SELECT * FROM Person WHERE p_id = '$pid'";
        $result = mysqli_query($con, $sql);

        // Fetch person details if exists
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $pname = $row['p_name'];
            $gender = $row['p_gender'];
            $phone = $row['p_phone'];
            $dob = $row['p_dob'];
            $blood_group = $row['p_blood_group'];
            $address = $row['p_address'];
            $med_issues = $row['p_med_issues'];

            // Display person details
            echo '<h2>' . $pname . '</h2><br><br>';
            echo 'Personal Id: ' . $pid . '<br><br>';
            echo 'Name: ' . $pname . '<br><br>';
            echo 'Phone Number: ' . $phone . '<br><br>';
            echo 'DOB: ' . $dob . '<br><br>';
            echo 'Blood Group: ' . $blood_group . '<br><br>';
            echo 'Gender: ' . ($gender == 'm' ? 'Male' : ($gender == 'f' ? 'Female' : 'Others')) . '<br><br>';
            echo 'Address: ' . $address . '<br><br>';
            echo 'Medical Issues: ' . ($med_issues == "" ? 'None' : $med_issues) . '<br><br>';

            // Display Donation History
            echo '<h3>Donation History</h3><br>';
            $sql_donations = "SELECT * FROM Donation WHERE p_id = '$pid'";
            $result_donations = mysqli_query($con, $sql_donations);

            if (mysqli_num_rows($result_donations) > 0) {
                echo "<table><tr><th>Date of Donation</th><th>Time of Donation</th><th>Units of blood</th></tr>";
                while ($row = mysqli_fetch_assoc($result_donations)) {
                    echo "<tr><td>" . $row["d_date"] . "</td><td>" . $row["d_time"] . "</td><td>" . $row["d_quantity"] . "</td></tr>";
                }
                echo "</table><br><br>";
            } else {
                echo "No donations yet.<br><br>";
            }

            // Display Receiving History
            echo '<h3>Receiving History</h3><br>';
            $sql_receives = "SELECT * FROM Receive WHERE p_id = '$pid'";
            $result_receives = mysqli_query($con, $sql_receives);

            if (mysqli_num_rows($result_receives) > 0) {
                echo "<table><tr><th>Date of Receive</th><th>Time of Receive</th><th>Units of blood</th><th>Hospital</th></tr>";
                while ($row = mysqli_fetch_assoc($result_receives)) {
                    echo "<tr><td>" . $row["r_date"] . "</td><td>" . $row["r_time"] . "</td><td>" . $row["r_quantity"] . "</td><td>" . $row["r_hospital"] . "</td></tr>";
                }
                echo "</table><br>";
            } else {
                echo "No receives yet.<br>";
            }

            // Admin delete button
            if ($_SESSION["user_type"] == "admin") {
                echo '<form method="POST" action="deletePerson.php">
                        <input type="hidden" name="pid" value="' . $pid . '">
                        <button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this person?\')">Delete Person</button>
                      </form>';
                // Admin edit button
                echo '<form method="GET" action="editPerson.php">
                        <input type="hidden" name="pid" value="' . $pid . '">
                        <button type="submit" class="btn btn-primary">Edit Person</button>
                      </form>';
            }

        } else {
            echo 'Person with PID: ' . $pid . ' not found!<br>Please provide a valid personal ID.<br>';
        }
    }

    // Close the database connection
    //mysqli_close($con);

    include_once('footer.php');
}
?>