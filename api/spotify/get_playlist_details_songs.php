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

// Check database connection
if ($database->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $database->connect_error]);
    exit();
}

$playlistId = $_GET['playlist_id'] ?? null;

// Query to fetch all songs
$sql = "
    SELECT song.id, song.title, song.musician, song.filename, song.lyrics, song.id_musictype
    FROM song
";

$stmt = $database->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$songs = [];
while ($row = $result->fetch_assoc()) {
    $songs[] = $row;
}

// Now check which songs are already in the playlist
$playlistSongs = [];
if ($playlistId) {
    // Query to check which songs are already in the playlist
    $sqlPlaylist = "
        SELECT pd.id_song 
        FROM playlistdatabase pd
        WHERE pd.id_playlist = ?
    ";
    $stmtPlaylist = $database->prepare($sqlPlaylist);
    $stmtPlaylist->bind_param('i', $playlistId);
    $stmtPlaylist->execute();
    $resultPlaylist = $stmtPlaylist->get_result();

    while ($row = $resultPlaylist->fetch_assoc()) {
        $playlistSongs[] = $row['id_song'];
    }

    $stmtPlaylist->close();
}

// Add an "in_playlist" field to the songs array
foreach ($songs as &$song) {
    $song['in_playlist'] = in_array($song['id'], $playlistSongs);
}

echo json_encode(["songs" => $songs]);

// Close the database connection
$stmt->close();
$database->close();
?>
