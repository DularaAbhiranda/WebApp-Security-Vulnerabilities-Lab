<?php
// oauth-callback-secure.php - Secure implementation
session_start();

// Validate that we have a state parameter
if (!isset($_GET['state']) || !isset($_SESSION['oauth_state'])) {
    die("Missing state parameter");
}

// Validate that the received state matches the stored state
if ($_GET['state'] !== $_SESSION['oauth_state']) {
    die("Invalid state parameter - possible CSRF attack detected");
}

// Validate that we have an authorization code
if (!isset($_GET['code'])) {
    die("Missing authorization code");
}

// Redirect to the main application with the authorization code and state
header('Location: oauth_client_secure.php?code=' . urlencode($_GET['code']) . '&state=' . urlencode($_GET['state']));
exit;
?>