<?php
session_start();
header('Content-Type: application/json');

require_once('../../config.php');
require_once('../../functions.php');

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

$username = $_SESSION['username'] ?? '';
$userId = getUserIdByUsername($username);

// Check database connection
if ($database->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $database->connect_error]);
    exit();
}

// Check if the request is for public playlists
$allPublic = isset($_GET['all_public']) && $_GET['all_public'] === 'true';

if ($allPublic) {
    // Query to fetch all public playlists
    $sql = "
        SELECT id, name, public, datetime, id_user
        FROM playlistname
        WHERE public = 1
    ";
    $stmt = $database->prepare($sql);
} else {
    // Query to fetch playlists for the logged-in user
    $sql = "
        SELECT id, name, public, datetime, id_user
        FROM playlistname
        WHERE id_user = ?
    ";
    $stmt = $database->prepare($sql);
    $stmt->bind_param('i', $userId);  // Bind the user ID to the query
}

$stmt->execute();
$result = $stmt->get_result();  // Get the result of the query

$playlists = [];

// Fetch all rows and store them in the $playlists array
while ($row = $result->fetch_assoc()) {
    $playlists[] = $row;
}

// Log the query (for debugging purposes)
file_put_contents('../../php_error.log', "get playlists: " . $sql . "\n", FILE_APPEND);

if ($playlists) {
    echo json_encode([
        "playlists" => $playlists,
        "loggedInUserId" => $userId
    ]);
} else {
    echo json_encode(["playlists" => []]);  // No playlists found
}

// Close the database connection
$stmt->close();
$database->close();
?>
