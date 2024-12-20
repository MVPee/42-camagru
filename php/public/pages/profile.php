<h1>Profile</h1>
<?php
    $query = "SELECT * FROM users WHERE username = '" . $_SESSION["username"] . "'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    echo "<p>Username: " . $row["username"] . "</p>";
    echo "<p>Member since: " . $row["created_at"] . "</p>";
?>