<?php
// Start the session and enable error reporting
session_start();

header('Content-Type: application/json');


// Include the database configuration and functions
require_once('../../config.php');
require_once('../../functions.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

$username = $_SESSION['username'];
$userId = getUserIdByUsername($username);

// Handle the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the POST data from the form
    $name = $_POST['name'] ?? '';
    $public = $_POST['public'] ?? '0'; // Default to private playlist (0)

    // Validate the input fields
    if (empty($name)) {
        http_response_code(400);
        echo json_encode(["error" => "Playlist name is required"]);
        exit();
    }

    // Optionally, validate the 'public' value (0 or 1)
    if (!in_array($public, ['0', '1'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid value for 'public'. Must be 0 or 1."]);
        exit();
    }

    try {
        // Insert playlist into the database
        $stmt = $database->prepare("INSERT INTO playlistname (id_user, name, public, datetime) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$userId, $name, $public]);

        // Send success response
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Playlist added successfully"]);

    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
}
?>
