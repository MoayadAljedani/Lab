<?php
// Headers for CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:3001');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    exit(0);
}

header('Access-Control-Allow-Origin: http://localhost:3001');
include_once '../db/Database.php';
include_once '../models/Bookmark.php';

$database = new Database();
$db = $database->connect();

$bookmark = new Bookmark($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && (!empty($data->url) || !empty($data->title))) {
    $bookmark->id = $data->id;
    $bookmark->url = $data->url ?? null;
    $bookmark->title = $data->title ?? null;

    if ($bookmark->update()) {
        echo json_encode(array('message' => 'Bookmark Updated'));
    } else {
        echo json_encode(array('message' => 'Bookmark Not Updated'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Missing Required Data'));
}
?>
