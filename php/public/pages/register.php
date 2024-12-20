<?php
    $error_message = "";
    $success_message = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password != $confirm_password)
            $error_message = "Passwords do not match.";
        else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "SELECT * FROM users WHERE username = '$username'";
            try {
                $result = $conn->query($query);
                if ($result->num_rows > 0)
                    $error_message = "Username already exists.";
                else {
                    $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
                    if ($conn->query($query) === TRUE)
                        $success_message = "Register successfully.";
                    else
                        $error_message = "Error: " . $query . "<br>" . $conn->error;
                }
            }
            catch (Exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
?>

<h1>Register</h1>

<p id="error" style="color:red;"><?php echo $error_message; ?></p>
<p id="success" style="color:green;"><?php echo $success_message; ?></p>
<form action="/register" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <!-- <input type="email" name="email" placeholder="Email" required> -->
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Register</button>
    <p>Already have an account? <a href="/login">Login</a></p>
</form>
