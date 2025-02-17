<?php


session_start();
$nonce = base64_encode(random_bytes(16)); // Generate a unique nonce

// Security Headers
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; connect-src 'self'; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
header("Access-Control-Allow-Origin: 'self'");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Header	Purpose
// Content-Security-Policy (CSP)	
/* header("Content-Security-Policy: 
    default-src 'self';  // Only allow content from the same origin (your website) 
    script-src 'self';  // Blocks inline scripts & eval(), only allows scripts from your server , with the nonce if inline
    style-src 'self' 'unsafe-inline';  // Blocks inline styles, only allows styles from your server , with the nonce if inline 
    img-src 'self' data:;  // Allow images from your server and Base64-encoded images (data URIs) 
    font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com;  // Allow fonts from Google Fonts 
    connect-src 'self';  // Restrict AJAX, WebSockets, and APIs to your own server 
    object-src 'none';  // Blocks Flash, Silverlight, and other plugin-based content 
    frame-ancestors 'none';  // Prevents your site from being embedded in iframes (mitigates clickjacking) 
    base-uri 'self';  // Prevents attackers from changing the base URL for relative links (reduces phishing risks) 
    form-action 'self';  // Ensures forms can only be submitted to your own website 
"); */
// Strict-Transport-Security (HSTS)	Forces HTTPS for all requests. preload makes browsers enforce it.
// X-Frame-Options	Prevents clickjacking attacks (DENY means no iframes allowed).
// X-Content-Type-Options	Prevents MIME-type sniffing to reduce attack surface.
// X-XSS-Protection	Enables browser XSS protection (mode=block stops the page if an attack is detected).
// Referrer-Policy	Controls what information is sent in Referer headers. no-referrer-when-downgrade is a good balance.
// Permissions-Policy	Controls access to browser features like microphone, camera, and geolocation.
// Access-Control-Allow-Origin	Restricts cross-origin requests (CORS). 'self' means only the same origin can access.
// Access-Control-Allow-Methods	Limits HTTP methods allowed in requests.
// Access-Control-Allow-Headers	Specifies which headers are allowed in requests.

// Serve static files (CSS, JS, images)
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_filename = __DIR__ . $request_uri;

if (file_exists($script_filename) && !is_dir($script_filename)) {
    return false; // Let PHP's built-in server handle static files
}

// Otherwise, forward to index.php (main PHP file)
require_once __DIR__ . '/index.php';
?>