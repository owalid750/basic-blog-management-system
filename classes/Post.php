<?php

class Post
{
    private $db;
    private $table_name = "posts";
    public $id;
    public $title;
    public $image_url;
    public $content;
    public $user_id;
    public $category_id;
    public $created_at;
    public $updated_at;
    public function __construct($db)
    {
        $this->db = $db;
    }


    public function createPost()
    {
        $query = "INSERT INTO $this->table_name (title, content, user_id, category_id) VALUES (:title, :content, :user_id, :category_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':category_id', $this->category_id);
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function getPosts($limit = null, $date = null, $category = null, $id = null, $user_id = null)
    {
        $query = "SELECT p.id, p.user_id,p.category_id,p.title, p.content, p.created_at, p.updated_at, p.image_url, c.id AS category_id,c.name AS category_name, u.username AS user_name, u.user_image AS user_image
        FROM $this->table_name p 
        JOIN categories c ON p.category_id = c.id 
        JOIN users u ON p.user_id = u.id";

        if ($category) {
            $query .= " WHERE category_id = :category";
        }

        if ($date) {
            $query .= " AND p.created_at = :date";
        }
        
        if ($id) {
            $query .= " AND p.id = :id";
        }
        if ($user_id) {
            $query .= " AND p.user_id = :user_id";
        }

        $query .= " ORDER BY p.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($query);

        if ($category) {
            $stmt->bindParam(':category', $category);
        }

        if ($date) {
            $stmt->bindParam(':date', $date);
        }

        if ($id) {
            $stmt->bindParam(':id', $id);
        }
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id);
        }

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePost($id, $title, $content, $category_id)
    {
        $query = "UPDATE $this->table_name SET title = :title, content = :content, category_id = :category_id WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function deletePost($id)
    {
        $query = "DELETE FROM $this->table_name WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    //v1  get sum of total posts based on user id
    public function getTotalPosts($user_id)
    {
        $query = "SELECT COUNT(id) FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // Return the count directly
    }

    public function getRecentPost($user_id)
    {
        $query = "SELECT title , id FROM posts WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
