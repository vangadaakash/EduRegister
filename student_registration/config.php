<?php
// Start session for flash messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB CONFIG (XAMPP defaults)
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "student_db";

// Create a single shared connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Helper: escape output to prevent XSS
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? "", ENT_QUOTES, 'UTF-8');
    }
}

// Helper: flash message
if (!function_exists('flash')) {
    function flash($key, $msg = null) {
        if ($msg !== null) {
            $_SESSION[$key] = $msg;
            return;
        }
        if (!empty($_SESSION[$key])) {
            $m = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $m;
        }
        return "";
    }
}
