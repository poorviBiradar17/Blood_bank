<?php
session_start();
$_SESSION["tab"] = "Edit Person";

// Ensure user is logged in and is an admin
if ($_SESSION["login"] != 1 || $_SESSION["user_type"] != "admin") {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
    exit();
} else {
    include_once('header.php');
    include_once('connection.php');  // Include database connection

    // Get the person ID from the URL
    if (isset($_GET['pid'])) {
        $pid = $_GET['pid'];

        // Query to fetch person's current details
        $sql = "SELECT * FROM Person WHERE p_id = '$pid'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $pname = $row['p_name'];
            $phone = $row['p_phone'];
            $gender = $row['p_gender'];
            $dob = $row['p_dob'];
            $blood_group = $row['p_blood_group'];
            $address = $row['p_address'];
            $med_issues = $row['p_med_issues'];
?>

<form name="editPerson" action="updatePerson.php" method="POST">
    <h2>Edit Person</h2>

    <input type="hidden" name="pid" value="<?php echo $pid; ?>" />

    <p>  
        <label>Name: </label>  
        <br>
        <input type="text" name="name" class="input" value="<?php echo $pname; ?>" required />  
    </p>  

    <p>  
        <label>Phone Number: </label>  
        <br>
        <input type="number" name="phone" maxlength="10" class="input" value="<?php echo $phone; ?>" required />  
    </p>  

    <p>  
        <label>Gender:</label><br>
        <input type="radio" id="male" name="gender" value="m" <?php echo ($gender == 'm') ? 'checked' : ''; ?> required>
        <label for="male">Male</label><br>
        <input type="radio" id="female" name="gender" value="f" <?php echo ($gender == 'f') ? 'checked' : ''; ?> required>
        <label for="female">Female</label><br>
        <input type="radio" id="other" name="gender" value="o" <?php echo ($gender == 'o') ? 'checked' : ''; ?> required>
        <label for="other">Other</label>
    </p>  

    <p>
        <label>Date of birth: </label>  
        <br>
        <input type="date" name="dob" class="input" value="<?php echo $dob; ?>" required/>  
    </p>  

    <p>
        <label>Blood Group:</label>
        <br>
        <select name="blood_group" class="input" required>
            <option value="A+" <?php echo ($blood_group == 'A+') ? 'selected' : ''; ?>>A+</option>
            <option value="A-" <?php echo ($blood_group == 'A-') ? 'selected' : ''; ?>>A-</option>
            <option value="B+" <?php echo ($blood_group == 'B+') ? 'selected' : ''; ?>>B+</option>
            <option value="B-" <?php echo ($blood_group == 'B-') ? 'selected' : ''; ?>>B-</option>
            <option value="O+" <?php echo ($blood_group == 'O+') ? 'selected' : ''; ?>>O+</option>
            <option value="O-" <?php echo ($blood_group == 'O-') ? 'selected' : ''; ?>>O-</option>
            <option value="AB+" <?php echo ($blood_group == 'AB+') ? 'selected' : ''; ?>>AB+</option>
            <option value="AB-" <?php echo ($blood_group == 'AB-') ? 'selected' : ''; ?>>AB-</option>
        </select>
    </p>

    <p>
        <label>Address:</label><br>
        <textarea rows="5" cols="30" name="address" class="input area" required><?php echo $address; ?></textarea>
    </p>

    <p>
        <label>Medical Issues(if any):</label>
        <br>
        <textarea rows="5" cols="30" name="med_issues" class="input area"><?php echo $med_issues; ?></textarea>
    </p>
    
    <p>
        <button class="btn">Update</button>
    </p>
</form>

<?php
        } else {
            echo "Person not found!";
        }
    }
    include_once('footer.php');
}
?>
