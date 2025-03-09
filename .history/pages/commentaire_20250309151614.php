session_start();

if (!isset($_SESSION['id'])) {
    echo "User is not logged in.";
    exit(); // Empêche d'afficher le formulaire si l'utilisateur n'est pas connecté
}

echo "User is logged in. User ID: " . $_SESSION['id'];

include '../utils/config.php';
include '../class/comment.php';

$message = '';
$commentObj = new Comment($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['go']) && $_POST['go'] == 'Signer') {
    $user_id = $_SESSION['id']; // Utiliser l'ID de la session
    $comment = $commentObj->testInput($_POST['comment']);
    $date = date('Y-m-d H:i:s');

    if (!empty($comment)) {
        if ($commentObj->addComment($user_id, $comment, $date)) {
            header('Location: index.php');
            exit();
        } else {
            $message = 'Erreur lors de l\'insertion du commentaire.';
        }
    } else {
        $message = 'Le champ commentaire ne peut pas être vide.';
    }
}
