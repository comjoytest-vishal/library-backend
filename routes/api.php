
<?php

require_once __DIR__ . '/../controllers/BookController.php';

// Create controller instance
$bookController = new BookController();

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


    // Route not found
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
