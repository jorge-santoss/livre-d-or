// Classe User avec fonctionnalités étendues
class User {
    private $pdo;
    private string $username;
    private string $mail;
    private string $password;
    private bool $isOK = false;

    // Constructeur pour initialiser la connexion PDO et l'utilisateur
    public function __construct($pdo, int $id = null) {
        $this->pdo = $pdo;
        if ($id !== null) {
            $this->init($id);
        }
    }

    // Méthode pour récupérer un utilisateur par son ID
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

    // Méthode pour vérifier si un utilisateur existe déjà avec ce nom ou email
    public function userExists($nom, $email): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE nom = :nom OR email = :email");
        $stmt->execute(['nom' => $nom, 'email' => $email]);
        return $stmt->fetch() !== false; // Renvoie true si l'utilisateur existe
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser($nom, $email, $password_hashed) {
        $sql = "INSERT INTO user (nom, email, password) VALUES (:nom, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'email' => $email, 'password' => $password_hashed]);
    }

    // Méthode pour mettre à jour les informations de l'utilisateur
    public function updateUser($id, $nom, $email, $password) {
        $stmt = $this->pdo->prepare("UPDATE user SET nom = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$nom, $email, $password, $id]);
    }

    // Getters
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
