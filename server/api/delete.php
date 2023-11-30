<?php
// Headers for CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Set necessary headers for CORS
    header('Access-Control-Allow-Origin: http://localhost:3001');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    exit(0);
}

// Set CORS headers for actual requests
header('Access-Control-Allow-Origin: http://localhost:3001');
include_once '../db/Database.php';
include_once '../models/Bookmark.php';

$database = new Database();
$db = $database->connect();

$bookmark = new Bookmark($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $bookmark->id = $data->id;

    if ($bookmark->delete()) {
        echo json_encode(array('message' => 'Bookmark Deleted'));
    } else {
        echo json_encode(array('message' => 'Bookmark Not Deleted'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Missing Required ID'));
}
?>
