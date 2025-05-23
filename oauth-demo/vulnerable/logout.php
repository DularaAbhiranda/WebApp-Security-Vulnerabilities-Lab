<?php
// logout.php
session_start();
session_destroy();
header('Location: oauth_client.php');
exit;
?>