<?php
// dashboard.php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: oauth_client.php');
    exit;
}

// Display user dashboard
$userEmail = htmlspecialchars($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        .info { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #f44336; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <div class="info">
            <p>You are logged in as: <strong><?php echo $userEmail; ?></strong></p>
        </div>
        <a href="logout.php" class="btn">Log Out</a>
    </div>
</body>
</html>