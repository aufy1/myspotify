<?php
session_start();
header('Content-Type: application/json');


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

// Check database connection
if ($database->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $database->connect_error]);
    exit();
}

// Query to fetch playlists for the logged-in user
$sql = "
    SELECT id, name, public, datetime
    FROM playlistname
    WHERE id_user = ?
";

$stmt = $database->prepare($sql);
$stmt->bind_param('i', $userId);  // Bind the user ID to the query

$stmt->execute();
$result = $stmt->get_result();  // Get the result of the query

$playlists = [];

// Fetch all rows and store them in the $playlists array
while ($row = $result->fetch_assoc()) {
    $playlists[] = $row;
}

file_put_contents('../../php_error.log', "get playlists: " . $sql . "\n", FILE_APPEND);

if ($playlists) {
    echo json_encode(["playlists" => $playlists]);
} else {
    echo json_encode(["playlists" => []]);  // No playlists found
}

// Close the database connection
$stmt->close();
$database->close();
?>
