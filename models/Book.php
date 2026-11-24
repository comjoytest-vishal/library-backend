
<?php

require_once __DIR__ . '/Model.php';

class Book extends Model
{
    // Get all books
    public function getAll()
    {
        $sql = "SELECT * FROM books ORDER BY id DESC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    // Get book by ID
    public function getById($id)
    {
        $sql = "SELECT * FROM books WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        return $stmt->fetch();
    }


    // Create new book
    public function create($data)
    {
        $sql = "INSERT INTO books
                (name, image_link, quantity, price, class_id)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $data['name'],
            $data['image_link'] ?? null,
            $data['quantity'],
            $data['price'],
            $data['class_id']
        ]);

        return $this->db->lastInsertId();
    }


    // Update existing book
    public function update($id, $data)
    {
        $sql = "UPDATE books SET
                name = ?,
                image_link = ?,
                quantity = ?,
                price = ?,
                class_id = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['image_link'] ?? null,
            $data['quantity'],
            $data['price'],
            $data['class_id'],
            $id
        ]);
    }


    // Delete book
    public function delete($id)
    {
        $sql = "DELETE FROM books WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }


    // Count total books
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM books";

        return (int) $this->db->query($sql)->fetchColumn();
    }
}
