<?php
    use PHPUnit\Framework\TestCase;

    class SecurityIntegrityTest extends TestCase {
        private $key = '12345678901234567890123456789012'; 

        public function testUntamperedLifecycle() {
            $data = "Patient_Record_001";
            $encrypted = secure_encrypt($data, $this->key);
            $this->assertEquals($data, secure_decrypt($encrypted, $this->key));
        }
        public function testTamperedCiphertextThrowsException() {
            $encrypted = secure_encrypt("Valid_Data", $this->key);
            $tampered = substr_replace($encrypted, 'X', 30, 1); 
            
            $this->expectException(SecurityException::class);
            secure_decrypt($tampered, $this->key);
        }
        public function testCredentialIntegrityMatch() {
            $password = "User_Password_123";
            $hash = password_hash($password, PASSWORD_ARGON2ID);
            $this->assertTrue(password_verify($password, $hash));
        }
    }
?>