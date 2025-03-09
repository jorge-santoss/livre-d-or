<?php
class Comment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getAllComments() {
        $query = "
            SELECT comments.comment, user.nom
            FROM comments 
            INNER JOIN user ON comment.id_user = user.id
            ORDER BY comments.date DESC
            LIMIT 2
        ";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
