<?php
    $error_message = "";
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "SELECT * FROM users WHERE username = '$username'";
        
        try {
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user["password"])) {
                    $_SESSION["username"] = $username;
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["email"] = $user["email"];
                    echo "<script>window.location.href = '/profile';</script>";
                    exit();
                } else {
                    $error_message = "Invalid username or password.";
                }
            } else {
                $error_message = "Invalid username or password.";
            }
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
?>


<h1>Login</h1>

<p id="error" style="color:red;"><?php echo $error_message; ?></p>
<p id="success" style="color:green;"><?php echo $success_message; ?></p>

<form action="/login" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="/register">Register</a></p>
