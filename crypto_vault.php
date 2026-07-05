<?php
// crypto_vault.php - Patient Medical Records Symmetric Protection

// use secret from .env
$secret_key = getenv('APP_SECRET_KEY'); 

class SecurityException extends Exception {}

// use AES-256-GCM
function secure_encrypt($plaintext, $key) {
    $iv = random_bytes(12);
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    return base64_encode($iv . $tag . $ciphertext);
}

// decrypt and verify
function secure_decrypt($encrypted_data, $key) {
    $decoded = base64_decode($encrypted_data);
    $iv = substr($decoded, 0, 12);
    $tag = substr($decoded, 12, 16);
    $ciphertext = substr($decoded, 28);

    $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);

    if ($plaintext === false) {
        throw new SecurityException("Integrity check failed: Data tampered.");
    }
    return $plaintext;
}
?>
?>