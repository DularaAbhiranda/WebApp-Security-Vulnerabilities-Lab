<?php
// logout-secure.php
session_start();
session_unset();
session_destroy();
header('Location: oauth_client_secure.php');
exit;
?>