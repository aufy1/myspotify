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

if (!isset($_GET['playlist_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing playlist_id"]);
    exit();
}

$playlistId = intval($_GET['playlist_id']);

// Check database connection
if ($database->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $database->connect_error]);
    exit();
}

// Query to fetch songs for the given playlist
$sql = "
    SELECT s.id, s.title, s.musician, s.filename
    FROM playlistdatabase pd
    INNER JOIN song s ON pd.id_song = s.id
    WHERE pd.id_playlist = ?
";

$stmt = $database->prepare($sql);
$stmt->bind_param('i', $playlistId);

$stmt->execute();
$result = $stmt->get_result();

$songs = [];

// Fetch all rows and store them in the $songs array
while ($row = $result->fetch_assoc()) {
    $songs[] = $row;
}

if ($songs) {
    echo json_encode(["songs" => $songs]);
} else {
    echo json_encode(["songs" => []]);
}

// Close the database connection
$stmt->close();
$database->close();
?>
