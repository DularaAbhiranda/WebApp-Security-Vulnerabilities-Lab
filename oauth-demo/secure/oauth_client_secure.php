<?php
// oauth_client_secure.php - Secure implementation
session_start();

// Enable strict session management
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');

// Regenerate session ID to prevent fixation
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id(true);
    $_SESSION['initialized'] = true;
}

// OAuth provider endpoints (mock)
$authorizationEndpoint = "http://localhost/oauth-demo/mock-server/index.php/oauth/authorize";
$tokenEndpoint = "http://localhost/oauth-demo/mock-server/index.php/oauth/token";
$userInfoEndpoint = "http://localhost/oauth-demo/mock-server/index.php/oauth/userinfo";

// Application settings
$clientId = "example-client-id";
$clientSecret = "example-client-secret";
$redirectUri = "http://localhost/oauth-demo/secure/oauth-callback-secure.php";

// PKCE extension for public clients (additional security)
function generateCodeVerifier() {
    $random = bin2hex(random_bytes(32));
    return rtrim(strtr(base64_encode($random), '+/', '-_'), '=');
}

function generateCodeChallenge($codeVerifier) {
    $hash = hash('sha256', $codeVerifier, true);
    return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
}

// Handle the redirect from the authorization server
if (isset($_GET['code'])) {
    // SECURE CODE: Validate state parameter to prevent CSRF
    if (!isset($_GET['state']) || !isset($_SESSION['oauth_state']) || 
        $_GET['state'] !== $_SESSION['oauth_state']) {
        die("Invalid state parameter - possible CSRF attack detected");
    }
    
    $authorizationCode = $_GET['code'];
    $codeVerifier = $_SESSION['code_verifier'];
    
    // Clear the state from the session to prevent reuse
    unset($_SESSION['oauth_state']);
    unset($_SESSION['code_verifier']);
    
    // Exchange the authorization code for an access token
    $ch = curl_init($tokenEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $authorizationCode,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'code_verifier' => $codeVerifier
    ]));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // SECURE CODE: Validate the token response
    if ($httpCode !== 200) {
        die("Error exchanging authorization code for access token");
    }
    
    $tokenData = json_decode($response, true);
    
    if (!isset($tokenData['access_token']) || !isset($tokenData['token_type'])) {
        die("Invalid token response");
    }
    
    if (strtolower($tokenData['token_type']) !== 'bearer') {
        die("Unsupported token type");
    }
    
    $accessToken = $tokenData['access_token'];
    
    // Fetch user information
    $ch = curl_init($userInfoEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // SECURE CODE: Validate the userinfo response
    if ($httpCode !== 200) {
        die("Error fetching user information");
    }
    
    $userData = json_decode($response, true);
    
    // SECURE CODE: Thorough validation of user data
    if (!isset($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        die("Invalid user information");
    }
    
    // SECURE CODE: Regenerate session ID when authentication state changes
    session_regenerate_id(true);
    
    // Set authenticated session
    $_SESSION['user_email'] = $userData['email'];
    $_SESSION['user_id'] = $userData['sub'] ?? null;
    $_SESSION['authenticated'] = true;
    $_SESSION['auth_time'] = time();
    
    header('Location: dashboard-secure.php');
    exit;
}

// Initiate the OAuth flow
function initiateOAuthFlow() {
    global $authorizationEndpoint, $clientId, $redirectUri;
    
    // SECURE CODE: Generate and store state parameter for CSRF protection
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;
    
    // SECURE CODE: Implement PKCE for additional security
    $codeVerifier = generateCodeVerifier();
    $_SESSION['code_verifier'] = $codeVerifier;
    $codeChallenge = generateCodeChallenge($codeVerifier);
    
    $authUrl = $authorizationEndpoint . '?' . http_build_query([
        'response_type' => 'code',
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'scope' => 'email profile',
        'state' => $state,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256'
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
    <title>OAuth 2.0 Client (Secure)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #4285f4; color: white; text-decoration: none; border-radius: 4px; }
        .secure-badge { display: inline-block; background-color: #0f9d58; color: white; font-size: 12px; padding: 3px 8px; border-radius: 12px; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Example App <span class="secure-badge">Secure</span></h1>
        <p>Please log in to continue:</p>
        <a href="?login=true" class="btn">Log in with OAuth Provider</a>
    </div>
</body>
</html>