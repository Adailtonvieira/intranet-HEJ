<?php
// authenticate.php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit; 
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    header('Location: login.php?error=1');
    exit;
}

try {
    // prepared statement to fetch user by username
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // correct
        session_regenerate_id(true);
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: adm.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
} catch (Exception $e) {
    // In production, log errors. For now, redirect with error.
    header('Location: login.php?error=1');
    exit;
}
