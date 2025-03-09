class User {
    private $pdo;

    // Constructeur uniquement pour la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour vérifier si un utilisateur existe déjà avec ce nom ou email
    public function userExists($nom, $email): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE nom = :nom OR email = :email");
        $stmt->execute(['nom' => $nom, 'email' => $email]);
        return $stmt->fetch() !== false; // Renvoie true si l'utilisateur existe déjà
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser($nom, $email, $password_hashed) {
        // Vérifie si l'utilisateur existe déjà
        if ($this->userExists($nom, $email)) {
            throw new Exception("L'utilisateur avec ce nom ou email existe déjà.");
        }

        // Si l'utilisateur n'existe pas, on crée un nouveau
        $sql = "INSERT INTO user (nom, email, password) VALUES (:nom, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'email' => $email, 'password' => $password_hashed]);
    }
}
