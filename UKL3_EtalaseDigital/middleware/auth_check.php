<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_login($loginUrl = 'auth/login.php') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . $loginUrl);
        exit();
    }
}

function check_role($roles = [], $loginUrl = 'auth/login.php') {
    check_login($loginUrl);
    if (!in_array($_SESSION['role'], $roles)) {
        die("Akses Ditolak: Anda tidak memiliki hak akses ke halaman ini.");
    }
}
?>