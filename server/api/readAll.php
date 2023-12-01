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

$result = $bookmark->readAll();

if ($result) {
    echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo json_encode(array('message' => 'No Bookmarks Found'));
}
?>
