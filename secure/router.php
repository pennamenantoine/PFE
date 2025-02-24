<?php

session_set_cookie_params([
    'lifetime' => 0, // Cookie expires when the browser is closed
    'path' => '/', // Available across the entire domain
    'domain' => '', // Leave empty to use the current domain
    'secure' => isset($_SERVER['HTTPS']), // Enable Secure flag if HTTPS is used
    'httponly' => true, // Prevents JavaScript from accessing the cookie
    'samesite' => 'Strict' // Options: "Lax" (default), "Strict", or "None" (None requires Secure)
]);

session_start();
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
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce'; img-src 'self' data:; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; connect-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self'; upgrade-insecure-requests;");
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

// Get the requested URI
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check for directory traversal (LFI attempt)
if (strpos($request_uri, '..') !== false) {
    // Redirect to home page if a LFI attempt is detected
    header("Location: /");
    exit();
}

// Serve static files (CSS, JS, images)
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = __DIR__ . '/../website'; // Point to the 'website' directory

$script_filename = $basePath . $request_uri;

if (file_exists($script_filename) && !is_dir($script_filename)) {
    return false; // Let PHP's built-in server handle static files
}

// Otherwise, forward to index.php (main PHP file)
require_once __DIR__ . '/../website/index.php';
?>
