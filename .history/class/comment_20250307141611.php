<?php
class Comment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function addComment($id_user, $comment, $date) {
        $stmt = $this->pdo->prepare("INSERT INTO comment (user_id, comment, date) VALUES (user, :comment, :date)");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':date', $date);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
