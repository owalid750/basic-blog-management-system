<?php
class Comment
{
    private $db;
    private $table_name = "comments";

    public $id;
    public $user_id;
    public $post_id;
    public $content;
    public $create_at;
    public $update_at;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createComment(): bool
    {
        $query = "INSERT INTO " . $this->table_name . " (user_id, post_id, content) VALUES (:user_id, :post_id, :content)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':post_id', $this->post_id);
        $stmt->bindParam(':content', $this->content);

        return $stmt->execute();
    }

    public function getAllComments($post_id, $order = "DESC")
    {
        $query = "SELECT c.id, c.user_id,c.post_id,c.content, c.created_at,c.updated_at, u.username AS user_name
        FROM " . $this->table_name . " c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = :post_id ORDER BY c.created_at $order";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":post_id", $post_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateComment($id, $content)
    {
        $query = "UPDATE " . $this->table_name . " SET content = :content WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteComment($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    //
    public function getTotalComments($user_id)
    {
        $query = "SELECT COUNT(id) FROM comments WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // Return the count directly
    }
    //
    public function getRecentComment($user_id)
    {
        $query = "SELECT posts.title,comments.post_id FROM comments
                  JOIN posts ON comments.post_id = posts.id
                  WHERE comments.user_id = :user_id ORDER BY comments.created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
