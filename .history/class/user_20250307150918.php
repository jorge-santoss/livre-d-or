<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function userExists($nom, $email) {
        $sql = "SELECT * FROM user WHERE nom = :nom OR email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'email' => $email]);
        return $stmt->fetch();
    }

    public function createUser($nom, $email, $password_hashed) {
        $sql = "INSERT INTO user (nom, email, password) VALUES (:nom, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'email' => $email, 'password' => $password_hashed]);
    }
}
?>