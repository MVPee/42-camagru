<?php
    $error_message = "";
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
        $confirm_password = htmlspecialchars($_POST["confirm_password"], ENT_QUOTES, 'UTF-8');

        # Fields are empty?
        if (empty($username) || empty($password) || empty($confirm_password) || empty($email))
            $error_message = "All fields are required.";
        # Username is invalid?
        else if (strlen($username) < 5 || strlen($username) > 20)
            $error_message = "Username must be between 5 and 25 characters.";
        # Password is invalid?
        else if (strlen($password) < 8 || strlen($password) > 32)
            $error_message = "Password must be between 8 and 32 characters.";
        # Email is invalid?
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $error_message = "Invalid email.";
        # Passwords do not match?
        else if ($password != $confirm_password)
            $error_message = "Passwords do not match.";
        # Username already exists?
        else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "SELECT * FROM users WHERE username = '$username'";
            try {
                $result = $conn->query($query);
                if ($result->num_rows > 0)
                    $error_message = "Username already exists.";
                else {
                    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
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

<p id="error" style="color:red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
<p id="success" style="color:green;"><?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></p>

<form action="/register" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="/login">Login</a></p>
