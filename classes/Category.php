<?php

class Category
{
    private $db;
    private $table_name = "categories";
    public  $id;
    public  $name;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCategories()
    {
        $query = "SELECT id, name FROM $this->table_name";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

