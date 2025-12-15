<?php
class UserModel {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Cek apakah username sudah digunakan
    public function isUsernameExists($username) {
        $sql = "SELECT id_user FROM user WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    // Registrasi user baru
    public function registerUser($nama, $username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (nama, username, password, role, email) VALUES (?, ?, ?, 'user', ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $nama, $username, $hashedPassword, $email);

        return $stmt->execute();
    }
}
?>
