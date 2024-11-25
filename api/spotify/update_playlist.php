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



//dodac weryfikacje id uzytkownika czy tej ta sama co w tabeli przed wykonaniem zapytania
//dodać w js również weryfikacje przed wyswietleniem przycisku


$playlistId = $_POST['playlistId'] ?? null;
$playlistPublic = $_POST['playlistPublic'] ?? null;
$songs = $_POST['songs'] ?? [];

if (!$playlistId || !isset($playlistPublic)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit();
}

$sql = "SELECT id FROM playlistname WHERE id = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param('i', $playlistId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Playlist not found"]);
    exit();
}

$sql = "UPDATE playlistname SET public = ? WHERE id = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param('ii', $playlistPublic, $playlistId);
$stmt->execute();

$sql = "DELETE FROM playlistdatabase WHERE id_playlist = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param('i', $playlistId);
$stmt->execute();

if (!empty($songs)) {
    $placeholders = implode(',', array_fill(0, count($songs), '(?, ?)'));
    $sql = "INSERT INTO playlistdatabase (id_playlist, id_song) VALUES $placeholders";
    $stmt = $database->prepare($sql);

    $params = [];
    foreach ($songs as $songId) {
        if (!is_numeric($songId)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid song ID"]);
            exit();
        }

        $params[] = $playlistId;
        $params[] = $songId;
    }

    if (!empty($params)) {
        $stmt->bind_param(str_repeat('ii', count($songs)), ...$params);
        $stmt->execute();
    }
}

echo json_encode(["success" => true]);
?>
