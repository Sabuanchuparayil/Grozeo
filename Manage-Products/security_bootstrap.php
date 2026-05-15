<?php

/**
 * Security bootstrap for legacy Grozeo-Manage-Products.
 * Include this file at the very top of index.php BEFORE any other code.
 */

// === Session Security ===
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);

// === PHP Security ===
ini_set('expose_php', 'Off');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');

// === Security Headers ===
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header_remove('X-Powered-By');

// === Input Sanitization Helper ===
function sanitize_input($value) {
    if (is_array($value)) {
        return array_map('sanitize_input', $value);
    }
    if (is_string($value)) {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }
    return $value;
}

function get_safe($key, $default = '') {
    $value = $_GET[$key] ?? $default;
    return sanitize_input($value);
}

function post_safe($key, $default = '') {
    $value = $_POST[$key] ?? $default;
    return sanitize_input($value);
}

function request_safe($key, $default = '') {
    $value = $_REQUEST[$key] ?? $default;
    return sanitize_input($value);
}

function get_int($key, $default = 0) {
    return (int)($_GET[$key] ?? $_POST[$key] ?? $default);
}

// === CSRF Token Functions ===
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';
}

// === Rate Limiting (simple file-based) ===
function check_rate_limit($identifier, $maxAttempts = 60, $decaySeconds = 60) {
    $cacheDir = __DIR__ . '/logs/rate_limit/';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    $file = $cacheDir . md5($identifier) . '.json';

    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : ['attempts' => 0, 'reset_at' => time() + $decaySeconds];

    if (time() > ($data['reset_at'] ?? 0)) {
        $data = ['attempts' => 0, 'reset_at' => time() + $decaySeconds];
    }

    $data['attempts']++;
    file_put_contents($file, json_encode($data));

    return $data['attempts'] <= $maxAttempts;
}

// === Health Check Endpoint ===
if (isset($_GET['module']) && $_GET['module'] === 'health') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'service' => 'grozeo-bizadmin',
        'timestamp' => date('c'),
    ]);
    exit;
}

// === Request Logging ===
function log_request($message, $context = []) {
    $logFile = __DIR__ . '/logs/access-' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $entry = json_encode(array_merge([
        'timestamp' => date('c'),
        'message' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    ], $context)) . "\n";
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}
