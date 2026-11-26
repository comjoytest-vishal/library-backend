
<?php

require_once __DIR__ . '/../models/Book.php';

class BookController
{
    private $book;

    public function __construct()
    {
        $this->book = new Book();
    }


    
// GET: Get all books with search, filter and pagination
public function index()
{
    // 1. Get search parameter
    $search = trim($_GET['search'] ?? '');

    // 2. Get class_id parameter
    $classId = filter_input(INPUT_GET, 'class_id', FILTER_VALIDATE_INT);

    // Validate class_id if provided
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

    // 3. Get pagination parameters
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

    // Set maximum limit
    $limit = min($limit, 50);

    // 4. Calculate offset
    $offset = ($page - 1) * $limit;

    // 5. Fetch filtered books with pagination
    $books = $this->book->getAll(
        $limit,
        $offset,
        $search,
        $classId
    );

    // 6. Get total matching books (without pagination)
    $totalBooks = $this->book->count($search, $classId);

    // 7. Calculate total pages
    $totalPages = (int) ceil($totalBooks / $limit);

    // 8. Return response
    return [
        'status' => true,
        'message' => 'Books fetched successfully',
        'data' => $books,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_books' => $totalBooks,
            'total_pages' => $totalPages
        ]
    ];
}


    // GET: Get book by ID
    public function show($id)
    {
        $book = $this->book->getById($id);

        if (!$book) {
            return [
                'status' => false,
                'message' => 'Book not found'
            ];
        }

        return [
            'status' => true,
            'message' => 'Book fetched successfully',
            'data' => $book
        ];
    }


    // POST: Create new book
    public function store($data)
    {
        $bookId = $this->book->create($data);

        return [
            'status' => true,
            'message' => 'Book created successfully',
            'data' => [
                'id' => $bookId
            ]
        ];
    }


    // PUT: Update existing book
    public function update($id, $data)
    {
        $existingBook = $this->book->getById($id);

        if (!$existingBook) {
            return [
                'status' => false,
                'message' => 'Book not found'
            ];
        }

        $updated = $this->book->update($id, $data);

        if (!$updated) {
            return [
                'status' => false,
                'message' => 'Book update failed'
            ];
        }

        return [
            'status' => true,
            'message' => 'Book updated successfully'
        ];
    }


    // DELETE: Delete book
    public function destroy($id)
    {
        $existingBook = $this->book->getById($id);

        if (!$existingBook) {
            return [
                'status' => false,
                'message' => 'Book not found'
            ];
        }

        $deleted = $this->book->delete($id);

        if (!$deleted) {
            return [
                'status' => false,
                'message' => 'Book deletion failed'
            ];
        }

        return [
            'status' => true,
            'message' => 'Book deleted successfully'
        ];
    }


    // GET: Count total books
    public function count()
    {
        $total = $this->book->count();

        return [
            'status' => true,
            'message' => 'Total books fetched successfully',
            'data' => [
                'total' => $total
            ]
        ];
    }
}
