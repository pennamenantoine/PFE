<?php

session_set_cookie_params([
    'lifetime' => 86400, // Cookie lasts 1 day, 0 Cookie expires when the browser is closed
    'path' => '/', // Available across the entire domain
    'domain' => '', // Leave empty to use the current domain
    'secure' => isset($_SERVER['HTTPS']), // Enable Secure flag if HTTPS is used
    'httponly' => true, // Prevents JavaScript from accessing the cookie
    'samesite' => 'Strict' // Options: "Lax" (default), "Strict", or "None" (None requires Secure)
]);

session_start();

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Secure random token
    }
    return $_SESSION['csrf_token'];
}

// Set CSRF token in an HttpOnly cookie
setcookie('csrf_token', generateCSRFToken(), [
    'secure' => true,      // Only send over HTTPS
    'httponly' => true,    // Prevent access via JavaScript
    'samesite' => 'Strict' // Mitigates CSRF (use 'Lax' for login forms)
]);

// Check if the config file exists
$CONFIG_FILE = './config.php';

// If the config file doesn't exist, show an error
if (!file_exists($CONFIG_FILE)) {
    echo "Error: config file '$CONFIG_FILE' not found!";
    exit(1);
}

$nonce = base64_encode(random_bytes(16)); // Generate a unique nonce

/* header("Content-Security-Policy: 
    default-src 'self';  // Only allow content from the same origin (your website) 
    script-src 'self';  // Blocks inline scripts & eval(), only allows scripts from your server , with the nonce if >
    style-src 'self' 'unsafe-inline';  // Blocks inline styles, only allows styles from your server , with the nonce>
    img-src 'self' data:;  // Allow images from your server and Base64-encoded images (data URIs) 
    font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com;  // Allow fonts from Google Fonts 
    connect-src 'self';  // Restrict AJAX, WebSockets, and APIs to your own server 
    object-src 'none';  // Blocks Flash, Silverlight, and other plugin-based content 
    frame-ancestors 'none';  // Prevents your site from being embedded in iframes (mitigates clickjacking) 
    base-uri 'self';  // Prevents attackers from changing the base URL for relative links (reduces phishing risks) 
    form-action 'self';  // Ensures forms can only be submitted to your own website 
"); */

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce'; img-src 'self' data:; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; connect-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'; upgrade-insecure-requests;report-uri http://127.0.0.1:8000/csp-violation-report-endpoint/");
// Strict-Transport-Security (HSTS)     Forces HTTPS for all requests. preload makes browsers enforce it.
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
// X-Frame-Options      Prevents clickjacking attacks (DENY means no iframes allowed).
header("X-Frame-Options: DENY");
// X-Content-Type-Options       Prevents MIME-type sniffing to reduce attack surface.
header("X-Content-Type-Options: nosniff");
// X-XSS-Protection     Enables browser XSS protection (mode=block stops the page if an attack is detected).
header("X-XSS-Protection: 1; mode=block");
// Referrer-Policy      Controls what information is sent in Referer headers. no-referrer-when-downgrade is a good ba>
header("Referrer-Policy: no-referrer-when-downgrade");
// Permissions-Policy   Controls access to browser features like microphone, camera, and geolocation.
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
// Access-Control-Allow-Origin  Restricts cross-origin requests (CORS). 'self' means only the same origin can access.
header("Access-Control-Allow-Origin: self");
// Access-Control-Allow-Methods Limits HTTP methods allowed in requests.
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Access-Control-Allow-Headers Specifies which headers are allowed in requests.
header("Access-Control-Allow-Headers: Content-Type");
//Certificate Transparency (CT) aims to prevent the use of misissued certificates for that site from going unnoticed.
header("Expect-CT: max-age=86400, enforce");
//Cross Origin Ressource Policy (CORP)
//deny access to our ressources from other websites
header("Cross-Origin-Resource-Policy: same-origin");
//Cross-Origin Opener Policy (COOP)
header("Cross-Origin-Opener-Policy: same-origin");
//Cross-Origin Embedder Policy (COEP)
header("Cross-Origin-Embedder-Policy: require-corp");


// Get the requested URI (path)
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Document root (../website) and parent path (one level above)
$basePath = realpath(__DIR__ . '/../website'); // Path to website directory
$parentPath = realpath(__DIR__ . '/../'); // Path to the parent directory

// Define allowed subdirectories in the parent directory
$allowedParentDirs = [
    'logs/',
    'uploads/',  // This is outside of the website directory
    'images/'
];

// First, try resolving the file in the website directory
$script_filename = realpath($basePath . $request_uri);

// If the file doesn't exist in the website directory, try checking in the allowed parent directories
if (!$script_filename) {
    foreach ($allowedParentDirs as $dir) {
        // Check if the file exists in the parent directory's allowed directories
        $script_filename = realpath($parentPath . $dir . $request_uri);
        
        // If found in one of the allowed directories, break the loop
        if ($script_filename) {
            break;
        }
    }
}

if (!$script_filename) {
    // If the file doesn't exist in either location
    header("HTTP/1.1 404 Not Found");
    exit("File not found: {$request_uri}");
}
echo "Resolved file: " . $script_filename;  // Just for debugging
exit(2);

// Normalize the requested file path, which might point to the parent directory too
$normalized_request_path = realpath($basePath . $request_uri);

// Check if the requested file path exists and is within the allowed directories
if (!$normalized_request_path) {
    // If the file cannot be found
    header("HTTP/1.1 404 Not Found");
    exit("File not found: {$request_uri}");
}

if (!isValidFile($normalized_request_path, $basePath, $parentPath, $allowedParentDirs)) {
    // If the file is not in an allowed directory
    header("HTTP/1.1 403 Forbidden");
    exit("Access Forbidden: {$normalized_request_path}");
}

// Allow direct access only to `/` (homepage) and `/index.php`
if ($request_uri === '/' || $request_uri === '/index.php') {
    require $basePath . '/index.php';
    exit();
}

// CSRF protection for POST requests
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("403 Forbidden - Invalid CSRF Token");
    }
}
*/
// Allow internal app execution (for includes and requires)
if (file_exists($normalized_request_path)) {
    return false; // Let the app access valid internal resources
}

// Fallback: Always load the main `index.php` if no specific resource found
require $basePath . '/index.php';
exit();

/**
 * Validate the file's path to ensure it's inside an allowed directory
 * 
 * @param string $filePath The full file path to validate.
 * @param string $basePath The base directory (website directory).
 * @param string $parentPath The parent directory.
 * @param array $allowedDirs The list of allowed subdirectories in the parent directory.
 * 
 * @return bool True if the file is valid, false otherwise.
 */
function isValidFile($filePath, $basePath, $parentPath, $allowedDirs) {
    // Check if the file is inside the website directory
    if (strpos($filePath, $basePath) === 0) {
        return true;
    }

    // Check if the file is inside one of the allowed subdirectories in the parent directory
    foreach ($allowedDirs as $dir) {
        $allowedPath = realpath($parentPath . $dir); // Resolve real path of each allowed dir
        if (strpos($filePath, $allowedPath) === 0) {
            return true; // File is inside allowed path (logs, uploads, images)
        }
    }

    // If it's not in any allowed path, return false
    return false;
}


?>
