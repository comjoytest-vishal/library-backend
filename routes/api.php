
<?php

require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/StudentController.php';

// Create controller instances
$bookController = new BookController();
$studentController = new StudentController();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove trailing slash
$uri = rtrim($uri, '/');

// API base path
$basePath = '/library/backend/public/api';

// Remove base path
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Default route
if ($uri === '') {
    $uri = '/';
}


// Route handling
switch (true) {

    // =====================================
    // BOOKS API
    // =====================================

    // GET: All books
    case $method === 'GET' && $uri === '/books':
        $response = $bookController->index();
        break;

    // GET: Total books count
    case $method === 'GET' && $uri === '/books/count':
        $response = $bookController->count();
        break;

    // GET: Single book
    case $method === 'GET' && preg_match('/^\/books\/(\d+)$/', $uri, $matches):
        $response = $bookController->show((int) $matches[1]);
        break;

    // POST: Create book
    case $method === 'POST' && $uri === '/books':
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $bookController->store($data ?? []);
        break;

    // PUT: Update book
    case $method === 'PUT' && preg_match('/^\/books\/(\d+)$/', $uri, $matches):
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $bookController->update((int) $matches[1], $data ?? []);
        break;

    // DELETE: Delete book
    case $method === 'DELETE' && preg_match('/^\/books\/(\d+)$/', $uri, $matches):
        $response = $bookController->destroy((int) $matches[1]);
        break;


    // =====================================
    // STUDENTS API
    // =====================================

    // GET: All students
    case $method === 'GET' && $uri === '/students':
        $response = $studentController->index();
        break;

    // GET: Total students count
    case $method === 'GET' && $uri === '/students/count':
        $response = $studentController->count();
        break;

    // GET: Single student
    case $method === 'GET' && preg_match('/^\/students\/(\d+)$/', $uri, $matches):
        $response = $studentController->show((int) $matches[1]);
        break;

    // POST: Create student
    case $method === 'POST' && $uri === '/students':
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $studentController->store($data ?? []);
        break;

    // PUT: Update student
    case $method === 'PUT' && preg_match('/^\/students\/(\d+)$/', $uri, $matches):
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $studentController->update((int) $matches[1], $data ?? []);
        break;

    // DELETE: Delete student
    case $method === 'DELETE' && preg_match('/^\/students\/(\d+)$/', $uri, $matches):
        $response = $studentController->destroy((int) $matches[1]);
        break;


    // =====================================
    // ROUTE NOT FOUND
    // =====================================

    default:
        http_response_code(404);

        $response = [
            'status' => false,
            'message' => 'API route not found'
        ];
        break;
}


// Return JSON response
header('Content-Type: application/json');

echo json_encode($response);
