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
$bookmark->id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(array('message' => 'ID Not Provided')));

if ($bookmark->readOne()) {
    echo json_encode(array(
        'id' => $bookmark->id,
        'url' => $bookmark->url,
        'title' => $bookmark->title,
        'date_added' => $bookmark->dateAdded
    ));
} else {
    echo json_encode(array('message' => 'No Bookmark Found'));
}
?>
