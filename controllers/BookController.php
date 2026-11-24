
<?php

require_once __DIR__ . '/../models/Book.php';

class BookController
{
    private $book;

    public function __construct()
    {
        $this->book = new Book();
    }


    // GET: Get all books
    public function index()
    {
        $books = $this->book->getAll();

        return [
            'status' => true,
            'message' => 'Books fetched successfully',
            'data' => $books
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
