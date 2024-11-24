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

// Pobierz dane z żądania POST
$playlistId = $_POST['playlistId'] ?? null;
$playlistPublic = $_POST['playlistPublic'] ?? null;
$songs = $_POST['songs'] ?? []; // Tablica z ID piosenek

if (!$playlistId || !isset($playlistPublic)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit();
}

// Weryfikacja poprawności playlistId
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

// Aktualizacja ustawienia prywatności
$sql = "UPDATE playlistname SET public = ? WHERE id = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param('ii', $playlistPublic, $playlistId);
$stmt->execute();

// Usuń wszystkie piosenki przypisane do tej playlisty
$sql = "DELETE FROM playlistdatabase WHERE id_playlist = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param('i', $playlistId);
$stmt->execute();

// Dodaj wybrane piosenki do playlisty
if (!empty($songs)) {
    $placeholders = implode(',', array_fill(0, count($songs), '(?, ?)'));
    $sql = "INSERT INTO playlistdatabase (id_playlist, id_song) VALUES $placeholders";
    $stmt = $database->prepare($sql);

    // Przygotuj dynamiczne parametry
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

error_log("Updating playlist ID: " . $playlistId);
// Wyślij odpowiedź JSON
echo json_encode(["success" => true]);
?>
