
<?php

require_once __DIR__ . '/Model.php';

class Book extends Model
{
// Get all books with search, filter and pagination
public function getAll($limit, $offset, $search = '', $classId = null)
{
    $sql = "SELECT * FROM books WHERE 1=1";

    $params = [];

    // Search by book name
    if ($search !== '') {
        $sql .= " AND name LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    // Filter by class_id
    if ($classId !== null) {
        $sql .= " AND class_id = :class_id";
        $params[':class_id'] = $classId;
    }

    // Sorting
    $sql .= " ORDER BY id DESC";

    // Pagination
    $sql .= " LIMIT :limit OFFSET :offset";

    $stmt = $this->db->prepare($sql);

    // Bind search and filter parameters
    foreach ($params as $key => $value) {
        if ($key === ':class_id') {
            $stmt->bindValue($key, (int) $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    // Bind pagination parameters
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}


// Count total matching books
public function count($search = '', $classId = null)
{
    $sql = "SELECT COUNT(*) FROM books WHERE 1=1";

    $params = [];

    // Search by book name
    if ($search !== '') {
        $sql .= " AND name LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    // Filter by class_id
    if ($classId !== null) {
        $sql .= " AND class_id = :class_id";
        $params[':class_id'] = $classId;
    }

    $stmt = $this->db->prepare($sql);

    // Bind parameters
    foreach ($params as $key => $value) {
        if ($key === ':class_id') {
            $stmt->bindValue($key, (int) $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    $stmt->execute();

    return (int) $stmt->fetchColumn();
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


  
}
