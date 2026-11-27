
<?php

require_once __DIR__ . '/../models/Student.php';

class StudentController
{
    private $student;

    public function __construct()
    {
        $this->student = new Student();
    }


    // GET: Get all students with search, filter and pagination
    public function index()
    {
        // Get search parameter
        $search = trim($_GET['search'] ?? '');

        // Get class_id parameter
        $classId = filter_input(INPUT_GET, 'class_id', FILTER_VALIDATE_INT);

        // Validate class_id
        if (isset($_GET['class_id'])) {
            if ($classId === false || $classId === null || $classId < 1) {
                return [
                    'status' => false,
                    'message' => 'Invalid class_id'
                ];
            }
        } else {
            $classId = null;
        }

        // Get pagination parameters
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);

        // Validate page
        $page = ($page !== false && $page !== null && $page >= 1)
            ? $page
            : 1;

        // Validate limit
        $limit = ($limit !== false && $limit !== null && $limit >= 1)
            ? $limit
            : 10;

        // Maximum limit
        $limit = min($limit, 50);

        // Calculate offset
        $offset = ($page - 1) * $limit;

        // Fetch students
        $students = $this->student->getAll(
            $limit,
            $offset,
            $search,
            $classId
        );

        // Count matching students
        $totalStudents = $this->student->count($search, $classId);

        // Calculate total pages
        $totalPages = (int) ceil($totalStudents / $limit);

        return [
            'status' => true,
            'message' => 'Students fetched successfully',
            'data' => $students,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total_students' => $totalStudents,
                'total_pages' => $totalPages
            ]
        ];
    }


    // GET: Get student by ID
    public function show($id)
    {
        $student = $this->student->getById($id);

        if (!$student) {
            return [
                'status' => false,
                'message' => 'Student not found'
            ];
        }

        return [
            'status' => true,
            'message' => 'Student fetched successfully',
            'data' => $student
        ];
    }


    // POST: Create new student
    public function store($data)
    {
        $studentId = $this->student->create($data);

        return [
            'status' => true,
            'message' => 'Student created successfully',
            'data' => [
                'id' => $studentId
            ]
        ];
    }


    // PUT: Update existing student
    public function update($id, $data)
    {
        $existingStudent = $this->student->getById($id);

        if (!$existingStudent) {
            return [
                'status' => false,
                'message' => 'Student not found'
            ];
        }

        $updated = $this->student->update($id, $data);

        if (!$updated) {
            return [
                'status' => false,
                'message' => 'Student update failed'
            ];
        }

        return [
            'status' => true,
            'message' => 'Student updated successfully'
        ];
    }


    // DELETE: Delete student
    public function destroy($id)
    {
        $existingStudent = $this->student->getById($id);

        if (!$existingStudent) {
            return [
                'status' => false,
                'message' => 'Student not found'
            ];
        }

        $deleted = $this->student->delete($id);

        if (!$deleted) {
            return [
                'status' => false,
                'message' => 'Student deletion failed'
            ];
        }

        return [
            'status' => true,
            'message' => 'Student deleted successfully'
        ];
    }


    // GET: Count total students
    public function count()
    {
        $total = $this->student->count();

        return [
            'status' => true,
            'message' => 'Total students fetched successfully',
            'data' => [
                'total' => $total
            ]
        ];
    }
}
