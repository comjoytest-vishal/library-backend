
<?php

require_once __DIR__ . '/Model.php';

class Student extends Model
{
    // Get all students with search, filter and pagination
    public function getAll($limit, $offset, $search = '', $classId = null)
    {
        $sql = "SELECT * FROM students WHERE 1=1";

        $params = [];

        // Search by student name or roll number
        if ($search !== '') {
            $sql .= " AND (name LIKE :search_name OR roll LIKE :search_roll)";

            $params[':search_name'] = '%' . $search . '%';
            $params[':search_roll'] = '%' . $search . '%';
        }

        // Filter by class_id
        if ($classId !== null) {
            $sql .= " AND class_id = :class_id";
            $params[':class_id'] = $classId;
        }

        $sql .= " ORDER BY id DESC";
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':class_id') {
                $stmt->bindValue($key, (int) $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    // Get student by ID
    public function getById($id)
    {
        $sql = "SELECT * FROM students WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }


    // Create new student
    public function create($data)
    {
        $sql = "INSERT INTO students
                (name, roll, phone, address, class_id)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $data['name'],
            $data['roll'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['class_id']
        ]);

        return $this->db->lastInsertId();
    }


    // Update existing student
    public function update($id, $data)
    {
        $sql = "UPDATE students SET
                name = ?,
                roll = ?,
                phone = ?,
                address = ?,
                class_id = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['roll'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['class_id'],
            $id
        ]);
    }


    // Delete student
    public function delete($id)
    {
        $sql = "DELETE FROM students WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }


    // Count total matching students
    public function count($search = '', $classId = null)
    {
        $sql = "SELECT COUNT(*) FROM students WHERE 1=1";

        $params = [];

        if ($search !== '') {
            $sql .= " AND (name LIKE :search_name OR roll LIKE :search_roll)";

            $params[':search_name'] = '%' . $search . '%';
            $params[':search_roll'] = '%' . $search . '%';
        }

        if ($classId !== null) {
            $sql .= " AND class_id = :class_id";
            $params[':class_id'] = $classId;
        }

        $stmt = $this->db->prepare($sql);

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
}
