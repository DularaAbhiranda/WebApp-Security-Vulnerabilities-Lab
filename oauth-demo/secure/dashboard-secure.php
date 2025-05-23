<?php
// dashboard-secure.php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: oauth_client_secure.php');
    exit;
}

// SECURE CODE: Implement session timeout
$sessionTimeout = 30 * 60; // 30 minutes
if (time() - $_SESSION['auth_time'] > $sessionTimeout) {
    // Session expired, clear session and redirect to login
    session_unset();
    session_destroy();
    header('Location: oauth_client_secure.php?session_expired=1');
    exit;
}

// Display user dashboard
$userEmail = htmlspecialchars($_SESSION['user_email']);
$userId = htmlspecialchars($_SESSION['user_id'] ?? 'Unknown');
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard (Secure)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        .info { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #f44336; color: white; text-decoration: none; border-radius: 4px; }
        .secure-badge { display: inline-block; background-color: #0f9d58; color: white; font-size: 12px; padding: 3px 8px; border-radius: 12px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard <span class="secure-badge">Secure</span></h1>
        <div class="info">
            <p>You are logged in as: <strong><?php echo $userEmail; ?></strong></p>
            <p>User ID: <strong><?php echo $userId; ?></strong></p>
            <p>Session authenticated at: <strong><?php echo date('Y-m-d H:i:s', $_SESSION['auth_time']); ?></strong></p>
        </div>
        <a href="logout-secure.php" class="btn">Log Out</a>
    </div>
</body>
</html>