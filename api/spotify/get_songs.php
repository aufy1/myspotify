<?php
session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

require_once('../../config.php');
require_once('../../functions.php');

$username = $_SESSION['username'] ?? '';
$userId = getUserIdByUsername($username);

header('Content-Type: application/json');

// Check database
if ($database->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database database failed: " . $database->connect_error]);
    exit();
}

// Query to fetch songs for the logged-in user
$sql = "
    SELECT song.id, song.title, song.musician, song.filename, song.lyrics, song.id_musictype
    FROM song
    WHERE song.id_user = ?
";

$stmt = $database->prepare($sql);
$stmt->bind_param('i', $userId);  // Bind the user ID to the query

$stmt->execute();
$result = $stmt->get_result();  // Get the result of the query

$songs = [];

// Fetch all rows and store them in the $songs array
while ($row = $result->fetch_assoc()) {
    $songs[] = $row;
}

if ($songs) {
    echo json_encode(["songs" => $songs]);
} else {
    echo json_encode(["songs" => []]);  // No songs found
}

// Close the database database
$stmt->close();
$database->close();
