<?php
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vulnerable query (for demonstration ONLY)
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    // Debugging line to print full query
    echo "<pre>Query: $query</pre>";

    try {
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            echo "<h3>Login successful!</h3>";
        } else {
            echo "<h3>Invalid credentials</h3>";
        }
    } catch (mysqli_sql_exception $e) {
        echo "<h3>Error in SQL syntax</h3>";
        echo "<pre>" . $e->getMessage() . "</pre>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>SQL Injection Demo</title></head>
<body>
    <h2>Login</h2>
    <form method="POST">
        Username: <input type="text" name="username"><br><br>
        Password: <input type="text" name="password"><br><br>
        <button name="login">Login</button>
    </form>
</body>
</html>
