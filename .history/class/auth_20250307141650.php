<?php
class Auth {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        
    }

    public function login(string $nom, string $password) {
        $nom = htmlspecialchars($nom);

        // Prepare the SQL statement
        $sql = "SELECT * FROM user WHERE nom = :nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom]);
        $user = $stmt->fetch();

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            return true; // Successful login
        } else {
            return false; // Failed login
        }
    }
}
?>
