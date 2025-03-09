<?php
// Dans ../class/user.php
class User {
    protected $pdo;
    private string $username;
    private string $mail;
    private string $password;

    private bool $isOK = false;

    public function __construct($pdo, int $id) {
        $this->pdo = $pdo;
        $this->init($id);
    }

    // Ajoute cette méthode pour récupérer un utilisateur par son id
    public function init($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        if ($results = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->username = $results['nom'];
            $this->mail = $results['email'];
            $this->password = $results['password'];
            $this->isOK = true;
        }
    }

    // Exemple de méthode pour vérifier l'existence d'un autre utilisateur avec ce nom ou email
    public function userExistsExcludingCurrent($nom, $email, $user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE (nom = ? OR email = ?) AND id != ?");
        $stmt->execute([$nom, $email, $user_id]);
        return $stmt->rowCount() > 0;
    }

    // Méthode pour mettre à jour les informations de l'utilisateur
    public function updateUser($user_id, $nom, $email, $password) {
        $stmt = $this->pdo->prepare("UPDATE user SET nom = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$nom, $email, $password, $user_id]);
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getMail(): string {
        return $this->mail;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getIsOK(): bool {
        return $this->isOK;
    }
}

?>