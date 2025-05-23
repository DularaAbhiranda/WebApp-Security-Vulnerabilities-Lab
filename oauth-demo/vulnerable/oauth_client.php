<?php
// oauth_client.php - Vulnerable implementation
session_start();

// OAuth provider endpoints (mock)
$authorizationEndpoint = "http://localhost/oauth-demo/mock-server/index.php/oauth/authorize";
$tokenEndpoint = "http://localhost/oauth-demo/mock-server/index.php/oauth/token";
$userInfoEndpoint = "http://localhost/oauth-demo/mock-server/index.php/oauth/userinfo";

// Application settings
$clientId = "example-client-id";
$clientSecret = "example-client-secret";
$redirectUri = "http://localhost/oauth-demo/vulnerable/oauth-callback.php";

// Handle the redirect from the authorization server
if (isset($_GET['code'])) {
    // VULNERABLE CODE: No state parameter validation
    $authorizationCode = $_GET['code'];
    
    // Exchange the authorization code for an access token
    $ch = curl_init($tokenEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $authorizationCode,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri
    ]));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $tokenData = json_decode($response, true);
    
    // VULNERABLE CODE: No validation of token response
    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];
        
        // Fetch user information
        $ch = curl_init($userInfoEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $userData = json_decode($response, true);
        
        // VULNERABLE CODE: Insufficient validation of user data
        if (isset($userData['email'])) {
            // Automatically log the user in
            $_SESSION['user_email'] = $userData['email'];
            $_SESSION['authenticated'] = true;
            
            header('Location: dashboard.php');
            exit;
        }
    }
    
    // Handle error
    echo "Authentication failed.";
    exit;
}

// Initiate the OAuth flow
function initiateOAuthFlow() {
    global $authorizationEndpoint, $clientId, $redirectUri;
    
    // VULNERABLE CODE: No CSRF protection (missing state parameter)
    $authUrl = $authorizationEndpoint . '?' . http_build_query([
        'response_type' => 'code',
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'scope' => 'email profile'
    ]);
    
    header('Location: ' . $authUrl);
    exit;
}

// Handle login request
if (isset($_GET['login'])) {
    initiateOAuthFlow();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OAuth 2.0 Client</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #4285f4; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Example App</h1>
        <p>Please log in to continue:</p>
        <a href="?login=true" class="btn">Log in with OAuth Provider</a>
    </div>
</body>
</html>