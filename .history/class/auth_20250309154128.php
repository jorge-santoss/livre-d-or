<?php
class Auth {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login(string $nom, string $password) {
        $nom = htmlspecialchars($nom);

        // Préparer la requête SQL
        $sql = "SELECT * FROM user WHERE nom = :nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom]);
        $user = $stmt->fetch();

        // Vérifier le mot de passe
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            return true; // Connexion réussie
        } else {
            return false; // Échec de la connexion
        }
    }

    // Nouvelle méthode pour récupérer l'ID de l'utilisateur
    public function getUserId() {
        if (isset($_SESSION['id'])) {
            return $_SESSION['id']; // Retourner l'ID de l'utilisateur
        }
        return null; // Retourner null si aucun utilisateur n'est connecté
    }
}
?>
