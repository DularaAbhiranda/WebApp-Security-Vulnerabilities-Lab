<?php
// Simple mock OAuth2 server for demonstration
session_start();

// Parse the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Handle different OAuth endpoints
if (strpos($requestUri, '/oauth/authorize') !== false) {
    // Authorization endpoint
    $clientId = $_GET['client_id'] ?? '';
    $redirectUri = $_GET['redirect_uri'] ?? '';
    $state = $_GET['state'] ?? '';
    $codeChallenge = $_GET['code_challenge'] ?? '';
    
    // Display authorization form
    echo '<html><head><title>Mock OAuth2 Server</title>';
    echo '<style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #4285f4; }
        button { background-color: #4285f4; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        .request-info { background-color: #f9f9f9; padding: 10px; border-radius: 4px; margin: 10px 0; font-family: monospace; }
    </style></head><body>';
    
    echo '<div class="container">';
    echo '<h1>Mock OAuth2 Authorization Server</h1>';
    echo '<p>The application "<strong>' . htmlspecialchars($clientId) . '</strong>" is requesting access to your account.</p>';
    
    echo '<div class="request-info">';
    echo '<strong>Request Details:</strong><br>';
    echo 'Client ID: ' . htmlspecialchars($clientId) . '<br>';
    echo 'Redirect URI: ' . htmlspecialchars($redirectUri) . '<br>';
    if (!empty($state)) {
        echo 'State: ' . htmlspecialchars($state) . '<br>';
    } else {
        echo 'State: <span style="color: red;">Missing!</span><br>';
    }
    if (!empty($codeChallenge)) {
        echo 'Code Challenge: ' . htmlspecialchars($codeChallenge) . '<br>';
    }
    echo '</div>';
    
    echo '<form method="post" action="/oauth-demo/mock-server/index.php/oauth/approve">';
    echo '<input type="hidden" name="redirect_uri" value="' . htmlspecialchars($redirectUri) . '">';
    echo '<input type="hidden" name="state" value="' . htmlspecialchars($state) . '">';
    echo '<button type="submit">Approve</button>';
    echo '</form>';
    echo '</div></body></html>';
    exit;
} 
elseif (strpos($requestUri, '/oauth/approve') !== false) {
    // User approval endpoint
    $redirectUri = $_POST['redirect_uri'] ?? '';
    $state = $_POST['state'] ?? '';
    
    // Generate a random authorization code
    $code = bin2hex(random_bytes(16));
    
    // Redirect back to the client
    $redirectUrl = $redirectUri . '?code=' . $code;
    if (!empty($state)) {
        $redirectUrl .= '&state=' . urlencode($state);
    }
    
    header('Location: ' . $redirectUrl);
    exit;
} 
elseif (strpos($requestUri, '/oauth/token') !== false) {
    // Token endpoint
    header('Content-Type: application/json');
    
    // In a real implementation, we would validate the authorization code
    // For this demo, we'll just return a mock access token
    echo json_encode([
        'access_token' => 'mock_access_token_' . bin2hex(random_bytes(16)),
        'token_type' => 'bearer',
        'expires_in' => 3600
    ]);
    exit;
} 
elseif (strpos($requestUri, '/oauth/userinfo') !== false) {
    // User info endpoint
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    
    if (strpos($authHeader, 'Bearer ') === 0) {
        // Return mock user info
        header('Content-Type: application/json');
        echo json_encode([
            'sub' => '12345',
            'email' => 'user@example.com',
            'name' => 'Example User'
        ]);
    } else {
        // Unauthorized
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'invalid_token']);
    }
    exit;
} 
else {
    // Main page
    echo '<html><head><title>Mock OAuth2 Server</title>';
    echo '<style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #4285f4; }
    </style></head><body>';
    
    echo '<div class="container">';
    echo '<h1>Mock OAuth2 Server</h1>';
    echo '<p>This is a mock OAuth2 server for demonstration purposes.</p>';
    echo '<p>To test the OAuth flow, go to one of these clients:</p>';
    echo '<ul>';
    echo '<li><a href="/oauth-demo/vulnerable/oauth_client.php">Vulnerable OAuth Client</a></li>';
    echo '<li><a href="/oauth-demo/secure/oauth_client_secure.php">Secure OAuth Client</a></li>';
    echo '</ul>';
    echo '</div></body></html>';
}
?>