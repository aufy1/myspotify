<?php
function shouldDisplayCaptcha() {
    global $database;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $previous_login_datetime = date('Y-m-d H:i:s', strtotime('-3 minutes'));
    
    if(!$database) {
        echo "Błąd: ". mysqli_connect_errno() . " " . mysqli_connect_error();
        exit();
    }
    
    mysqli_query($database, "SET NAMES 'utf8'");
    
    // Przygotowanie zapytania do bazy danych
    $stmt = mysqli_prepare($database, "SELECT COUNT(*) AS attempts FROM login_attempts WHERE ip_address = ? AND login_datetime > ? AND successful_login = 0");
    
    // Sprawdzenie, czy zapytanie zostało poprawnie przygotowane
    if ($stmt === false) {
        echo "Błąd przygotowania zapytania: " . mysqli_error($database);
        exit();
    }
    
    // Wiązanie parametrów i wykonanie zapytania
    mysqli_stmt_bind_param($stmt, "ss", $ip_address, $previous_login_datetime);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $login_attempts = $row['attempts'];
    mysqli_stmt_close($stmt);
    
    mysqli_close($database);
    
    return $login_attempts > 2;
}

function ip_location($ip) {
    $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
    $details = json_decode($json);
    return $details;
}

function hasAccessToDisk($database, $username, $disk) {
    // Zapytanie do bazy danych, aby pobrać nazwy dysków użytkownika lub te, które są z nim udostępnione
    $query = "SELECT disk_name FROM disks WHERE (owner = ? OR FIND_IN_SET(?, shared_with))";
    $stmt = mysqli_prepare($database, $query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Błąd zapytania SQL: ' . mysqli_error($database)]);
        exit();
    }

    // Przypisanie parametrów
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);

    // Wykonanie zapytania
    mysqli_stmt_execute($stmt);

    // Pobranie wyników
    mysqli_stmt_bind_result($stmt, $disk_name);
    $available_disks = [];

    while (mysqli_stmt_fetch($stmt)) {
        $available_disks[] = $disk_name;
    }

    // Zamykamy zapytanie
    mysqli_stmt_close($stmt);

    // Sprawdzenie, czy żądany dysk znajduje się na liście dostępnych dysków
    return in_array($disk, $available_disks);
}


        // Funkcja do usuwania folderu i jego zawartości
        function deleteFolder($folder_path) {
            if (is_dir($folder_path)) {
                $files = scandir($folder_path);
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        $filePath = $folder_path . '/' . $file;
                        is_dir($filePath) ? deleteFolder($filePath) : unlink($filePath);
                    }
                }
                rmdir($folder_path);
            }
        }
        function getMusicTypeNameById($id_musictype) {
            global $database; // Use the global database connection
        
            // Prepare SQL query to fetch the music type name
            $query = "SELECT name FROM musictype WHERE id = ?";
            
            // Prepare the statement
            $stmt = $database->prepare($query);
            
            // Check if the prepare statement was successful
            if ($stmt === false) {
                die('MySQL prepare error: ' . $database->error);
            }
        
            // Bind the ID parameter to the query
            $stmt->bind_param('i', $id_musictype); // 'i' is for integer
        
            // Execute the query
            $stmt->execute();
        
            // Get the result
            $result = $stmt->get_result();
        
            // Fetch the music type name
            if ($row = $result->fetch_assoc()) {
                return $row['name'];
            } else {
                return null; // Return null if no music type found
            }
        }


function getUserIdByUsername($username) {
    global $database;  // Używamy globalnego obiektu mysqli do połączenia z bazą danych

    $stmt = $database->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];  // Upewnij się, że 'id' to nazwa kolumny w tabeli users
    } else {
        return null;
    }
}

        // Funkcja do tworzenia tokenu
function generateToken($fileName, $disk, $path, $expiryTime, $username, $secretKey) {
    $payload = json_encode([
        'fileName' => $fileName,
        'disk' => $disk,
        'path' => $path ?? '',
        'expiryTime' => $expiryTime,
        'username' => $username
    ]);
    
    // Generowanie tokenu
    $base64Payload = base64_encode($payload);
    $hmac = hash_hmac('sha256', $payload, $secretKey);

    return $base64Payload . '.' . $hmac;
}

function formatSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}


?>