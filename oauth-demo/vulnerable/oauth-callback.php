<?php
// oauth-callback.php - Vulnerable implementation
session_start();

// Just redirects to the main application which processes the code
header('Location: oauth_client.php?code=' . $_GET['code']);
exit;
?>