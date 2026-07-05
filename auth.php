<?php
// auth.php - Staff Key Authentication System
function secure_password_hash($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}
function verify_user_login($username, $provided_password, $pdo) {
    // prepared statement
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($provided_password, $user['password_hash'])) {
        session_start();
        $_SESSION['user'] = $username;
        return true;
    }
    
    // return generic failure
    return false;
}
?>