<?php
// security.php

// Définir les en-têtes de sécurité
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://trusted.cdn.com; object-src 'none'; style-src 'self' https://trusted.cdn.com; img-src 'self' data: https://trusted.cdn.com;");
header("X-XSS-Protection: 1; mode=block");

// Paramètres de cookie
setcookie("sessionId", "abc123", [
    'expires' => time() + 3600, // 1 heure
    'path' => '/',
    'domain' => 'example.com', // Remplacez par votre domaine
    'secure' => true, // Cookie envoyé uniquement sur HTTPS
    'httponly' => true, // Cookie inaccessible via JavaScript
    'samesite' => 'Strict' // Protège contre CSRF
]);
?>
