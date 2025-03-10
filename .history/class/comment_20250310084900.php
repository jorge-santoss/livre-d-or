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

    public function addComment($user_id, $comment, $date) {
        $stmt = $this->pdo->prepare("INSERT INTO comments (user_id, comment, date) VALUES (:user_id, :comment, :date)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':date', $date);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getCommentById($comment_id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateComment($comment_id, $newComment)
{
    // Example query; adjust column/table names as needed
    $sql = "UPDATE comments 
            SET comment = :comment, date = NOW() 
            WHERE id = :comment_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':comment', $newComment, PDO::PARAM_STR);
    $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
    return $stmt->execute();
}

}
?>
