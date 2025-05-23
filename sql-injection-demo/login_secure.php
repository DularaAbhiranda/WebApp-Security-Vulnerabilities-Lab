<?php
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Secure query using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging line (optional)
    echo "<pre>Using prepared statement with parameters for username and password</pre>";

    if ($result && $result->num_rows > 0) {
        echo "<h3>Login successful!</h3>";
    } else {
        echo "<h3>Invalid credentials</h3>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>SQL Injection Demo (Secure)</title></head>
<body>
    <h2>Secure Login</h2>
    <form method="POST">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="text" name="password"><br><br>
        <button name="login">Login</button>
    </form>
</body>
</html>