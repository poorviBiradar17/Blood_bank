<?php
session_start();
$_SESSION["tab"] = "Search Person";

// Ensure user is logged in
if ($_SESSION["login"] != 1) {
    echo '<h2 style="color:red">Authentication Error!!!</h2>';
} else {
    include_once('header.php');
    ?>

    <form name="searchPerson" action="searchPerson.php" method="POST">
        <h2>Search Person</h2>
        <br>

        <p>
            <label>Personal ID: </label>
            <br>
            <input type="text" name="pid" class="input" required/>
        </p>

        <p>
            <button class="btn">Submit</button>
        </p>
    </form>

    <?php
    include_once('footer.php');
}
?>
