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
file_put_contents('../../php_error.log', "Username: $username\n", FILE_APPEND);
$userId = getUserIdByUsername($username);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $musician = $_POST['musician'] ?? '';
    $lyrics = $_POST['lyrics'] ?? '';
    $musictype = $_POST['musictype'] ?? '';
    $filename = '';

    // Log POST data
    file_put_contents('../../php_error.log', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);

    if (empty($title) || empty($musician) || empty($musictype)) {
        http_response_code(400);
        echo json_encode(["error" => "Required fields are missing"]);
        exit();
    }

    $fileTitle = str_replace(' ', '-', $title);

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Check file type based on MIME type
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileMimeType = mime_content_type($fileTmpPath);

        // Check if file is an MP3 (audio/mpeg)
        if ($fileMimeType !== 'audio/mpeg') {
            file_put_contents('../../php_error.log', "Invalid file type: " . $fileMimeType . "\n", FILE_APPEND);
            http_response_code(400);
            echo json_encode(["error" => "Invalid file type. Only MP3 files are allowed"]);
            exit();
        }

        // Validate file extension (optional but recommended)
        $allowedExtensions = ['mp3'];
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            file_put_contents('../../php_error.log', "Invalid file extension: " . $fileExtension . "\n", FILE_APPEND);
            http_response_code(400);
            echo json_encode(["error" => "Invalid file extension. Only MP3 files are allowed"]);
            exit();
        }

        // Generowanie losowego ciągu znaków dla nazwy pliku
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5);
        $filename = $userId . "_" . $fileTitle . "_" . $randomString . ".mp3"; // Zmieniona nazwa pliku

        // Specify upload directory
        $uploadDir = '../../uploads/songs/';
        $targetFilePath = $uploadDir . $filename;

        // Move the file to the target directory
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            file_put_contents('../../php_error.log', "Failed to move uploaded file: " . $_FILES['file']['name'] . "\n", FILE_APPEND);
            http_response_code(500);
            echo json_encode(["error" => "Failed to upload file"]);
            exit();
        }
    } else {
        file_put_contents('../../php_error.log', "File upload error: " . $_FILES['file']['error'] . "\n", FILE_APPEND);
        http_response_code(400);
        echo json_encode(["error" => "File upload failed or no file provided"]);
        exit();
    }

    try {
        // Insert data into the database (tu wstawiamy oryginalny tytuł z przestrzeniami)
        $stmt = $database->prepare("INSERT INTO song (title, musician, datetime, id_user, filename, lyrics, id_musictype) VALUES (?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->execute([ 
            $title, // Oryginalny tytuł z przestrzeniami
            $musician, 
            $userId, 
            $filename, 
            $lyrics, 
            $musictype 
        ]);
        http_response_code(200);
        echo json_encode(["message" => "Song added successfully"]);
    } catch (PDOException $e) {
        file_put_contents('../../php_error.log', "Database error: " . $e->getMessage() . "\n", FILE_APPEND);
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
}
