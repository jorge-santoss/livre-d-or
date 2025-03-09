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
        // Vérifie d'abord si l'utilisateur existe déjà
        if ($this->userExists($nom, $email)) {
            throw new Exception("L'utilisateur avec ce nom ou email existe déjà.");
        }

        // Si l'utilisateur n'existe pas, on crée un nouveau
        $sql = "INSERT INTO user (nom, email, password) VALUES (:nom, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nom' => $nom, 'email' => $email, 'password' => $password_hashed]);
    }

    // Getters
    public function
